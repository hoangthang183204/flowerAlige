<?php

namespace App\Http\Controllers;

use App\Models\Order;
use App\Services\VnPayService;
use Illuminate\Http\Request;
use Illuminate\Support\Facades\Log;

class VnPayController extends Controller
{
    /**
     * VNPay redirect về đây (GET) sau khi khách thanh toán.
     */
    public function return(Request $request)
    {
        $params = $request->query();
        $vnpay = new VnPayService;

        if (! $vnpay->verifyReturnSignature($params)) {
            Log::warning('VNPay return invalid signature', ['params' => $params]);
            return redirect()->route('checkout.show')->with('error', 'Giao dịch VNPay không hợp lệ.');
        }

        $txnRef = $params['vnp_TxnRef'] ?? '';
        $responseCode = $params['vnp_ResponseCode'] ?? '';

        $order = Order::find($txnRef);
        if (! $order) {
            Log::warning('VNPay return order not found', ['vnp_TxnRef' => $txnRef]);
            return redirect()->route('home')->with('error', 'Không tìm thấy đơn hàng.');
        }

        $order->load('items.product');

        if ($responseCode === '00' && $order->status === 'pending') {
            $order->update(['status' => 'confirmed']);
            Log::info('VNPay return order confirmed', ['orderId' => $order->id]);
            return redirect()
                ->route('order.thankyou', $order)
                ->with('success', 'Thanh toán VNPay thành công. Chúng tôi sẽ liên hệ xác nhận đơn hàng.');
        }

        if ($responseCode !== '00') {
            $message = $params['vnp_Message'] ?? 'Thanh toán chưa thành công.';
            return redirect()
                ->route('order.thankyou', $order)
                ->with('error', 'VNPay: ' . $message . ' Bạn có thể liên hệ Hotline 1900 000 000 để thanh toán lại.');
        }

        return redirect()->route('order.thankyou', $order);
    }

    /**
     * IPN: VNPay gọi server-to-server (POST). Không dùng CSRF.
     */
    public function ipn(Request $request)
    {
        $params = $request->all();
        if (empty($params) && $request->getContent()) {
            parse_str($request->getContent(), $params);
        }
        $vnpay = new VnPayService;

        if (! $vnpay->verifyReturnSignature($params)) {
            Log::warning('VNPay IPN invalid signature', ['params' => $params]);
            return response()->json(['RspCode' => '97', 'Message' => 'Invalid signature']);
        }

        $txnRef = $params['vnp_TxnRef'] ?? '';
        $responseCode = $params['vnp_ResponseCode'] ?? '';

        $order = Order::find($txnRef);
        if (! $order) {
            Log::warning('VNPay IPN order not found', ['vnp_TxnRef' => $txnRef]);
            return response()->json(['RspCode' => '01', 'Message' => 'Order not found']);
        }

        if ($responseCode === '00' && $order->status === 'pending') {
            $order->update(['status' => 'confirmed']);
            Log::info('VNPay IPN order confirmed', ['orderId' => $order->id]);
        }

        return response()->json(['RspCode' => '00', 'Message' => 'Confirm success']);
    }
}
