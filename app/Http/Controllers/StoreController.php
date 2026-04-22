<?php

namespace App\Http\Controllers;

use App\Models\Category;
use App\Models\Product;
use App\Models\Order;
use App\Models\OrderItem;
use App\Models\Banner;
use App\Services\MomoService;
use App\Services\VnPayService;
use Illuminate\Http\Request;
use Illuminate\Support\Facades\DB;
use Illuminate\Support\Facades\Schema;

class StoreController extends Controller
{
    public function home(Request $request)
    {
        $featuredProducts = Product::where('is_active', true)
            ->where('is_featured', true)
            ->latest()
            ->take(8)
            ->get();

        $newProducts = Product::where('is_active', true)
            ->latest()
            ->take(8)
            ->get();

        $popularProducts = OrderItem::select('product_id', DB::raw('SUM(quantity) as total_quantity'))
            ->groupBy('product_id')
            ->orderByDesc('total_quantity')
            ->with(['product' => function ($query) {
                $query->where('is_active', true);
            }])
            ->take(8)
            ->get()
            ->pluck('product')
            ->filter();

        $categories = Category::where('is_active', true)->get();

        $banners = Banner::where('is_active', true)
            ->orderBy('position')
            ->orderByDesc('created_at')
            ->get();

        $condolenceBanners = collect();

        return view('store.home', compact(
            'featuredProducts',
            'newProducts',
            'popularProducts',
            'categories',
            'banners',
            'condolenceBanners'
        ));
    }

    public function products(Request $request)
    {
        $query = Product::query()
            ->where('is_active', true)
            ->with('category');

        if ($search = $request->input('q')) {
            $query->where(function ($q) use ($search) {
                $q->where('name', 'like', '%' . $search . '%')
                    ->orWhere('short_description', 'like', '%' . $search . '%');
            });
        }

        if ($categorySlug = $request->input('category')) {
            $query->whereHas('category', function ($q) use ($categorySlug) {
                $q->where('slug', $categorySlug);
            });
        }

        if ($priceMin = $request->input('price_min')) {
            $query->where('price', '>=', (float) $priceMin);
        }

        if ($priceMax = $request->input('price_max')) {
            $query->where('price', '<=', (float) $priceMax);
        }

        $products = $query->paginate(12)->withQueryString();
        $categories = Category::where('is_active', true)->get();

        return view('store.products.index', compact('products', 'categories'));
    }

    public function productDetail(string $slug)
    {
        $product = Product::where('slug', $slug)
            ->where('is_active', true)
            ->with('category')
            ->firstOrFail();

        $relatedProducts = Product::where('category_id', $product->category_id)
            ->where('id', '!=', $product->id)
            ->where('is_active', true)
            ->latest()
            ->take(4)
            ->get();

        return view('store.products.show', compact('product', 'relatedProducts'));
    }

    protected function getCart(Request $request): array
    {
        return $request->session()->get('cart', []);
    }

    protected function saveCart(Request $request, array $cart): void
    {
        $request->session()->put('cart', $cart);
    }

    public function cart(Request $request)
    {
        $cart = $this->getCart($request);

        $normalizedCart = [];
        foreach ($cart as $productId => $value) {
            if (is_array($value)) {
                $normalizedCart[$productId] = (int) ($value['quantity'] ?? 0);
            } else {
                $normalizedCart[$productId] = (int) $value;
            }
        }

        $normalizedCart = array_filter($normalizedCart, fn($qty) => $qty > 0);

        if ($cart != $normalizedCart) {
            $this->saveCart($request, $normalizedCart);
            $cart = $normalizedCart;
        }

        $productIds = array_keys($cart);

        $products = $productIds
            ? Product::whereIn('id', $productIds)->get()->keyBy('id')
            : collect();

        $items = [];
        $total = 0;

        foreach ($cart as $productId => $quantity) {
            $product = $products->get($productId);

            if (!$product) {
                continue;
            }

            $subtotal = $product->price * $quantity;
            $total += $subtotal;

            $items[] = [
                'product' => $product,
                'quantity' => $quantity,
                'subtotal' => $subtotal,
            ];
        }

        return view('store.cart', compact('items', 'total'));
    }

    public function addToCart(Request $request, int $productId)
    {
        $product = Product::where('is_active', true)->findOrFail($productId);

        $validated = $request->validate([
            'quantity' => ['nullable', 'integer', 'min:0'],
        ]);

        $quantity = $validated['quantity'] ?? 0;

        if ($quantity <= 0) {
            return redirect()->route('products.show', $product->slug)
                ->with('error', 'Vui lòng chọn số lượng lớn hơn 0.');
        }

        $cart = $this->getCart($request);

        $current = 0;
        if (isset($cart[$product->id])) {
            if (is_array($cart[$product->id])) {
                $current = (int) ($cart[$product->id]['quantity'] ?? 0);
            } else {
                $current = (int) $cart[$product->id];
            }
        }

        $newQuantity = $current + $quantity;

        if (isset($product->stock) && $product->stock >= 0 && $newQuantity > $product->stock) {
            return redirect()->route('products.show', $product->slug)
                ->with('error', 'Số lượng yêu cầu vượt quá kho. Chỉ còn ' . $product->stock . ' sản phẩm.');
        }

        $cart[$product->id] = $newQuantity;

        $this->saveCart($request, $cart);

        return redirect()->route('cart.show')
            ->with('success', 'Đã thêm sản phẩm vào giỏ hàng.');
    }

    public function updateCart(Request $request)
    {
        $validated = $request->validate([
            'items' => ['array'],
            'items.*.quantity' => ['required', 'integer', 'min:0'],
        ]);

        $cart = [];
        $productIds = array_keys($validated['items'] ?? []);
        $products = $productIds ? Product::whereIn('id', $productIds)->get()->keyBy('id') : collect();

        $messages = [];

        foreach ($validated['items'] ?? [] as $productId => $item) {
            $pid = (int) $productId;
            $qty = (int) $item['quantity'];

            $product = $products->get($pid);
            if ($qty <= 0 || !$product) {
                continue;
            }

            if (isset($product->stock) && $product->stock >= 0 && $qty > $product->stock) {
                $messages[] = $product->name . ': chỉ còn ' . $product->stock . ' sản phẩm. Số lượng đã được điều chỉnh.';
                $qty = $product->stock;
            }

            if ($qty > 0) {
                $cart[$pid] = $qty;
            }
        }

        $this->saveCart($request, $cart);

        if (!empty($messages)) {
            return redirect()->route('cart.show')->with('error', implode(' ', $messages));
        }

        return redirect()->route('cart.show')->with('success', 'Đã cập nhật giỏ hàng.');
    }

    public function checkout(Request $request)
    {
        $cart = $this->getCart($request);

        $normalizedCart = [];
        foreach ($cart as $productId => $value) {
            if (is_array($value)) {
                $normalizedCart[$productId] = (int) ($value['quantity'] ?? 0);
            } else {
                $normalizedCart[$productId] = (int) $value;
            }
        }
        $normalizedCart = array_filter($normalizedCart, fn($qty) => $qty > 0);

        if (empty($normalizedCart)) {
            return redirect()->route('cart.show')->with('error', 'Giỏ hàng đang trống.');
        }

        if ($cart != $normalizedCart) {
            $this->saveCart($request, $normalizedCart);
            $cart = $normalizedCart;
        }

        $productIds = array_keys($cart);

        $products = Product::whereIn('id', $productIds)->get()->keyBy('id');

        $items = [];
        $total = 0;

        foreach ($cart as $productId => $quantity) {
            $product = $products->get($productId);

            if (!$product) {
                continue;
            }

            $subtotal = $product->price * $quantity;
            $total += $subtotal;

            $items[] = [
                'product' => $product,
                'quantity' => $quantity,
                'subtotal' => $subtotal,
            ];
        }

        $user = $request->user();

        $lastOrder = null;
        if ($user) {
            $lastOrder = Order::where('user_id', $user->id)->orderByDesc('created_at')->first();
        }

        return view('store.checkout', compact('items', 'total', 'user', 'lastOrder'));
    }

    public function thankYou(Order $order)
    {
        $order->load('items.product');

        return view('store.thankyou', compact('order'));
    }

    public function myOrders(Request $request)
    {
        $user = $request->user();

        $orders = Order::where('user_id', $user->id)
            ->orderByDesc('created_at')
            ->paginate(10);

        return view('store.orders.index', compact('orders'));
    }

    public function myOrderDetail(Request $request, Order $order)
    {
        $user = $request->user();

        if ($order->user_id !== $user->id) {
            abort(403);
        }

        $order->load('items.product');

        return view('store.orders.show', compact('order'));
    }

    public function cancelOrder(Request $request, Order $order)
    {
        $user = $request->user();

        if ($order->user_id !== $user->id) {
            abort(403);
        }

        if (in_array($order->status, ['shipping', 'completed', 'cancelled'], true)) {
            return redirect()->route('orders.my.show', $order)->with('error', 'Đơn hàng không thể hủy ở trạng thái hiện tại.');
        }

        DB::transaction(function () use ($order) {
            foreach ($order->items as $item) {
                if ($item->product) {
                    $item->product->increment('stock', $item->quantity);
                }
            }

            $order->update(['status' => 'cancelled']);
        });

        return redirect()->route('orders.my')->with('success', 'Đã hủy đơn hàng.');
    }

    public function placeOrder(Request $request)
    {
        $cart = $this->getCart($request);

        if (empty($cart)) {
            return redirect()->route('cart.show')->with('error', 'Giỏ hàng đang trống.');
        }

        $validated = $request->validate([
            'customer_name' => ['required', 'string', 'max:255'],
            'customer_email' => ['nullable', 'email', 'max:255'],
            'customer_phone' => ['required', 'string', 'max:20'],
            'shipping_address' => ['required', 'string', 'max:500'],
            'payment_method' => ['required', 'in:cod,bank_transfer,momo,vnpay'],
            'notes' => ['nullable', 'string', 'max:1000'],
        ]);

        $productIds = array_keys($cart);

        $products = Product::whereIn('id', $productIds)
            ->where('is_active', true)
            ->lockForUpdate()
            ->get()
            ->keyBy('id');

        if ($products->isEmpty()) {
            return redirect()->route('cart.show')->with('error', 'Giỏ hàng không hợp lệ.');
        }

        $order = null;

        DB::transaction(function () use ($request, $products, $cart, $validated, &$order) {
            $itemsData = [];
            $total = 0;

            foreach ($cart as $productId => $quantity) {
                $product = $products->get($productId);

                if (!$product || $quantity <= 0) {
                    continue;
                }

                $quantity = min($quantity, max(0, (int) $product->stock));

                if ($quantity === 0) {
                    continue;
                }

                $subtotal = $product->price * $quantity;
                $total += $subtotal;

                $itemsData[] = [
                    'product' => $product,
                    'quantity' => $quantity,
                    'subtotal' => $subtotal,
                ];
            }

            if (empty($itemsData)) {
                throw new \RuntimeException('Không có sản phẩm hợp lệ trong giỏ hàng.');
            }

            $order = Order::create([
                'user_id' => $request->user()?->id,
                'customer_name' => $validated['customer_name'],
                'customer_email' => $validated['customer_email'] ?? null,
                'customer_phone' => $validated['customer_phone'],
                'shipping_address' => $validated['shipping_address'],
                'payment_method' => $validated['payment_method'],
                'status' => 'pending',
                'total_amount' => $total,
                'notes' => $validated['notes'] ?? null,
            ]);

            foreach ($itemsData as $item) {
                $product = $item['product'];
                $quantity = $item['quantity'];

                OrderItem::create([
                    'order_id' => $order->id,
                    'product_id' => $product->id,
                    'quantity' => $quantity,
                    'unit_price' => $product->price,
                    'subtotal' => $item['subtotal'],
                ]);

                $product->decrement('stock', $quantity);
            }
        });

        // Cập nhật thông tin user nếu cần
        if ($request->user()) {
            $u = $request->user();
            $data = ['name' => $validated['customer_name']];
            if (Schema::hasColumn('users', 'phone')) {
                $data['phone'] = $validated['customer_phone'];
            }
            $u->fill($data);
            $u->save();
        }

        // XỬ LÝ MoMo
        if ($validated['payment_method'] === 'momo') {
            $momo = new MomoService;
            if (!$momo->isConfigured()) {
                return redirect()
                    ->route('checkout.show')
                    ->withInput()
                    ->with('error', 'Thanh toán MoMo tạm thời chưa khả dụng. Vui lòng chọn phương thức khác.');
            }

            $amount = (int) round($order->total_amount);
            $orderId = (string) $order->id;
            $orderInfo = 'Thanh toán đơn hàng #' . $order->id . ' - Flower Corner';
            $redirectUrl = route('momo.return', ['order' => $order->id]);
            $ipnUrl = route('momo.ipn');
            $userInfo = array_filter([
                'name' => $validated['customer_name'],
                'phoneNumber' => $validated['customer_phone'],
                'email' => $validated['customer_email'] ?? null,
            ]);

            $result = $momo->createPayment($orderId, $amount, $orderInfo, $redirectUrl, $ipnUrl, $userInfo);

            if ($result['success']) {
                $request->session()->forget('cart');
                return redirect()->away($result['payUrl']);
            }

            $order->delete();
            return redirect()
                ->route('checkout.show')
                ->withInput()
                ->with('error', $result['message'] ?? 'Không tạo được link thanh toán MoMo. Vui lòng thử lại hoặc chọn COD/Chuyển khoản.');
        }

        // XỬ LÝ VNPay
        if ($validated['payment_method'] === 'vnpay') {
            $vnpay = new VnPayService;
            if (!$vnpay->isConfigured()) {
                return redirect()
                    ->route('checkout.show')
                    ->withInput()
                    ->with('error', 'Thanh toán VNPay tạm thời chưa khả dụng. Vui lòng chọn phương thức khác.');
            }

            $amount = (int) round($order->total_amount);
            $txnRef = (string) $order->id;
            $orderInfo = 'Thanh toan don hang #' . $order->id . ' Flower Corner';
            $returnUrl = route('vnpay.return');
            $ipAddr = $request->ip() ?: '127.0.0.1';

            $result = $vnpay->createPaymentUrl($txnRef, $amount, $orderInfo, $returnUrl, $ipAddr);

            if ($result['success']) {
                $request->session()->forget('cart');
                return redirect()->away($result['payment_url']);
            }

            $order->delete();
            return redirect()
                ->route('checkout.show')
                ->withInput()
                ->with('error', $result['message'] ?? 'Không tạo được link thanh toán VNPay.');
        }

        // XỬ LÝ COD - Xóa giỏ hàng, chuyển đến trang cảm ơn
        if ($validated['payment_method'] === 'cod') {
            $request->session()->forget('cart');
            $order->update(['status' => 'confirmed']);

            return redirect()
                ->route('order.thankyou', $order)
                ->with('success', 'Đặt hàng thành công. Chúng tôi sẽ liên hệ xác nhận trong thời gian sớm nhất.');
        }

        // XỬ LÝ BANK TRANSFER - Chuyển đến trang chi tiết đơn hàng để hiển thị QR
        if ($validated['payment_method'] === 'bank_transfer') {
            $request->session()->forget('cart');

            return redirect()
                ->route('orders.my.show', $order)
                ->with('success', 'Đặt hàng thành công! Vui lòng quét mã QR để thanh toán.')
                ->with('show_qr', true);
        }

        // Fallback (không nên xảy ra)
        $request->session()->forget('cart');
        return redirect()
            ->route('order.thankyou', $order)
            ->with('success', 'Đặt hàng thành công.');
    }
}
