<?php

use App\Http\Controllers\ProfileController;
use App\Http\Controllers\StoreController;
use App\Http\Controllers\PostController;
use App\Http\Controllers\Admin\CategoryController;
use App\Http\Controllers\Admin\ProductController;
use App\Http\Controllers\Admin\OrderController;
use App\Http\Controllers\Admin\PostController as AdminPostController;
use App\Http\Controllers\Admin\ReportController;
use App\Http\Controllers\Admin\UserController;
use App\Http\Controllers\Admin\BannerController;
use App\Http\Controllers\ChatController;
use App\Http\Controllers\MomoController;
use App\Http\Controllers\VnPayController;
use Illuminate\Support\Facades\Route;

// Trang khách hàng (KH)
Route::get('/', [StoreController::class, 'home'])->name('home');
Route::get('/products', [StoreController::class, 'products'])->name('products.index');
Route::get('/products/{slug}', [StoreController::class, 'productDetail'])->name('products.show');
Route::get('/thank-you/{order}', [StoreController::class, 'thankYou'])->name('order.thankyou');

Route::get('/momo/return/{order}', [MomoController::class, 'return'])->name('momo.return');
Route::post('/momo/ipn', [MomoController::class, 'ipn'])->name('momo.ipn');

// Thêm route test
Route::get('/test-momo-payment', function () {
    $momo = new \App\Services\MomoService();

    $orderId = 'TEST_' . time();
    $amount = 50000;
    $orderInfo = 'Test thanh toán MoMo';
    $redirectUrl = route('momo.return', ['order' => 'TEST_' . time()]);
    $ipnUrl = route('momo.ipn');

    $result = $momo->createPayment(
        $orderId,
        $amount,
        $orderInfo,
        $redirectUrl,
        $ipnUrl
    );

    if ($result['success']) {
        return redirect($result['payUrl']);
    }

    return response()->json($result);
});

Route::get('/test-timezone', function () {
    return [
        'php_timezone' => date_default_timezone_get(),
        'php_now' => date('Y-m-d H:i:s'),
        'laravel_config' => config('app.timezone'),
        'laravel_now' => now()->toDateTimeString(),
        'laravel_now_vn' => now('Asia/Ho_Chi_Minh')->toDateTimeString(),
        'utc_now' => now('UTC')->toDateTimeString(),
        'server_time' => shell_exec('date'),
    ];
});

Route::get('/vnpay/return', [VnPayController::class, 'return'])->name('vnpay.return');
Route::post('/vnpay/ipn', [VnPayController::class, 'ipn'])->name('vnpay.ipn');

Route::get('/test-vnpay', function () {
    $vnpay = new \App\Services\VnPayService();

    $txnRef = 'TEST_' . time();
    $amount = 50000;
    $orderInfo = 'Test thanh toan VNPay Flower Corner';
    $returnUrl = route('vnpay.return');
    $ipAddr = request()->ip() ?: '127.0.0.1';

    $result = $vnpay->createPaymentUrl($txnRef, $amount, $orderInfo, $returnUrl, $ipAddr);

    if ($result['success']) {
        return redirect($result['payment_url']);
    }

    return response()->json($result);
});

// Blog (KH)
Route::get('/blog', [PostController::class, 'index'])->name('blog.index');
Route::get('/blog/{slug}', [PostController::class, 'show'])->name('blog.show');

Route::post('/chat', [ChatController::class, 'chat'])->name('chat.send');

Route::get('/test-openai', function () {
    $key = config('services.openai.key');
    return response()->json([
        'has_key' => !empty($key),
        'key_preview' => substr($key, 0, 10) . '...',
    ]);
});

// (dev-only routes removed)

// Khu vực sau đăng nhập (KH) — giỏ hàng & thanh toán chỉ dành cho thành viên
Route::middleware('auth')->group(function () {
    Route::get('/cart', [StoreController::class, 'cart'])->name('cart.show');
    Route::post('/cart/add/{product}', [StoreController::class, 'addToCart'])->name('cart.add');
    Route::post('/cart/update', [StoreController::class, 'updateCart'])->name('cart.update');
    Route::get('/checkout', [StoreController::class, 'checkout'])->name('checkout.show');
    Route::post('/checkout', [StoreController::class, 'placeOrder'])->name('checkout.place');

    Route::get('/reset-cart', function () {
        session()->forget('cart');
        return redirect()->route('cart.show')->with('success', 'Đã reset giỏ hàng!');
    });

    Route::get('/dashboard', function () {
        $user = auth()->user();

        if ($user && $user->is_admin) {
            return redirect()->route('admin.dashboard');
        }

        return redirect()->route('home');
    })->name('dashboard');

    Route::get('/profile', [ProfileController::class, 'edit'])->name('profile.edit');
    Route::patch('/profile', [ProfileController::class, 'update'])->name('profile.update');
    Route::delete('/profile', [ProfileController::class, 'destroy'])->name('profile.destroy');

    Route::get('/my-orders', [StoreController::class, 'myOrders'])->name('orders.my');
    Route::get('/my-orders/{order}', [StoreController::class, 'myOrderDetail'])->name('orders.my.show');
    Route::post('/my-orders/{order}/cancel', [StoreController::class, 'cancelOrder'])->name('orders.my.cancel');
});

require __DIR__ . '/auth.php';

// Khu vực quản trị (Admin)
Route::middleware(['auth', 'admin'])
    ->prefix('admin')
    ->as('admin.')
    ->group(function () {
        // Trang dashboard admin hiển thị thống kê
        Route::get('/', [ReportController::class, 'index'])->name('dashboard');

        Route::resource('categories', CategoryController::class)->except(['show']);
        Route::post('categories/{id}/restore', [CategoryController::class, 'restore'])->name('categories.restore');
        Route::delete('categories/{id}/force-delete', [CategoryController::class, 'forceDelete'])->name('categories.force-delete');

        Route::resource('products', ProductController::class)->except(['show']);
        Route::post('products/{id}/restore', [ProductController::class, 'restore'])->name('products.restore');
        Route::delete('products/{id}/force-delete', [ProductController::class, 'forceDelete'])->name('products.force-delete');

        Route::get('orders', [OrderController::class, 'index'])->name('orders.index');
        Route::get('orders/{order}', [OrderController::class, 'show'])->name('orders.show');
        Route::patch('orders/{order}/status', [OrderController::class, 'updateStatus'])->name('orders.update-status');

        Route::resource('posts', AdminPostController::class)->except(['show']);

        Route::get('reports', [ReportController::class, 'index'])->name('reports.index');

        Route::get('users', [UserController::class, 'index'])->name('users.index');
        Route::get('users/{user}/edit', [UserController::class, 'edit'])->name('users.edit');
        Route::put('users/{user}', [UserController::class, 'update'])->name('users.update');
        Route::delete('users/{user}', [UserController::class, 'destroy'])->name('users.destroy');

        Route::resource('banners', BannerController::class)->except(['show']);
    });
