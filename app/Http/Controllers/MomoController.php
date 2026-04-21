<?php

namespace App\Http\Controllers;

use App\Models\Order;
use App\Services\MomoService;
use Illuminate\Http\Request;
use Illuminate\Support\Facades\Log;

class MomoController extends Controller
{
    // public function return(Request $request, Order $order)
    // {
    //     $order->load('items.product');

    //     if ($order->status !== 'pending') {
    //         return redirect()
    //             ->route('order.thankyou', $order)
    //             ->with('success', 'Đơn hàng đã được xác nhận thanh toán.');
    //     }

    //     return view('store.momo-pending', compact('order'));
    // }

    /**
     * IPN: MoMo gọi server-to-server (POST JSON hoặc form). Không dùng CSRF.
     */
    public function ipn(Request $request)
    {
        $params = $request->all();
        if (empty($params) && $request->getContent()) {
            $params = json_decode($request->getContent(), true) ?? [];
        }
        $momo = new MomoService;

        if (! $momo->verifyIpnSignature($params)) {
            Log::warning('MoMo IPN invalid signature', ['params' => $params]);

            return response()->json(['message' => 'Invalid signature'], 400);
        }

        $orderId = $params['orderId'] ?? '';
        $resultCode = (int) ($params['resultCode'] ?? -1);

        $order = Order::find($orderId);
        if (! $order) {
            Log::warning('MoMo IPN order not found', ['orderId' => $orderId]);

            return response()->json(['message' => 'Order not found'], 404);
        }

        if ($resultCode === 0 && $order->status === 'pending') {
            $order->update(['status' => 'confirmed']);
            Log::info('MoMo IPN order confirmed', ['orderId' => $orderId]);
        }

        return response()->json(['message' => 'OK'], 200);
    }

    public function return(Request $request, Order $order)
    {
        $resultCode = $request->query('resultCode');
        $message = $request->query('message');

        if ($resultCode == 0) {
            // Thanh toán thành công
            if ($order->status === 'pending') {
                $order->update(['status' => 'confirmed']);
            }
            return redirect()
                ->route('order.thankyou', $order)
                ->with('success', 'Thanh toán MoMo thành công!');
        }

        // Thanh toán thất bại
        return redirect()
            ->route('checkout.show')
            ->with('error', 'Thanh toán MoMo thất bại: ' . ($message ?? 'Vui lòng thử lại'));
    }
}
