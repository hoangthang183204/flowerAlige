<?php

namespace App\Services;

use Illuminate\Support\Facades\Http;
use Illuminate\Support\Facades\Log;

class MomoService
{
    protected string $partnerCode;
    protected string $accessKey;
    protected string $secretKey;
    protected string $storeId;
    protected string $storeName;
    protected string $baseUrl;

    public function __construct()
    {
        $this->partnerCode = (string) (config('services.momo.partner_code') ?? '');
        $this->accessKey = (string) (config('services.momo.access_key') ?? '');
        $this->secretKey = (string) (config('services.momo.secret_key') ?? '');
        $this->storeId = (string) (config('services.momo.store_id') ?? 'FlowerShop');
        $this->storeName = (string) (config('services.momo.store_name') ?? 'Flower Corner');
        $env = config('services.momo.env', 'sandbox');
        $this->baseUrl = $env === 'production'
            ? 'https://payment.momo.vn'
            : 'https://test-payment.momo.vn';
    }

    public function isConfigured(): bool
    {
        return ! empty($this->partnerCode) && ! empty($this->accessKey) && ! empty($this->secretKey);
    }

    /**
     * Tạo chuỗi ký (request) cho captureWallet.
     * Thứ tự key a-z: accessKey, amount, extraData, ipnUrl, orderId, orderInfo, partnerCode, redirectUrl, requestId, requestType
     */
    protected function makeSignature(array $params): string
    {
        $data = [
            'accessKey' => $this->accessKey,
            'amount' => $params['amount'],
            'extraData' => $params['extraData'] ?? '',
            'ipnUrl' => $params['ipnUrl'],
            'orderId' => $params['orderId'],
            'orderInfo' => $params['orderInfo'],
            'partnerCode' => $this->partnerCode,
            'redirectUrl' => $params['redirectUrl'],
            'requestId' => $params['requestId'],
            'requestType' => $params['requestType'],
        ];
        ksort($data);
        $raw = implode('&', array_map(fn ($k, $v) => $k . '=' . $v, array_keys($data), $data));

        return hash_hmac('sha256', $raw, $this->secretKey);
    }

    /**
     * Tạo thanh toán MoMo (e-wallet), trả về payUrl để redirect.
     *
     * @param  string  $orderId  Mã đơn hàng (unique, ví dụ: order_id hoặc "ORD_123_ts")
     * @param  int  $amount  Số tiền VND (1000 - 50_000_000)
     * @param  string  $orderInfo  Mô tả đơn hàng
     * @param  string  $redirectUrl  URL redirect sau khi thanh toán (trên browser)
     * @param  string  $ipnUrl  URL MoMo gọi server-to-server để báo kết quả
     * @param  array  $userInfo  ['name','phoneNumber','email'] (optional)
     * @return array{success: bool, payUrl?: string, message?: string}
     */
    public function createPayment(
        string $orderId,
        int $amount,
        string $orderInfo,
        string $redirectUrl,
        string $ipnUrl,
        array $userInfo = []
    ): array {
        if (! $this->isConfigured()) {
            return ['success' => false, 'message' => 'MoMo chưa được cấu hình.'];
        }

        $amount = max(1000, min(50_000_000, (int) $amount));
        $requestId = $orderId . '_' . time();
        $extraData = base64_encode(json_encode([]));

        $params = [
            'amount' => $amount,
            'extraData' => $extraData,
            'ipnUrl' => $ipnUrl,
            'orderId' => $orderId,
            'orderInfo' => $orderInfo,
            'redirectUrl' => $redirectUrl,
            'requestId' => $requestId,
            'requestType' => 'captureWallet',
        ];
        $signature = $this->makeSignature($params);

        $body = [
            'partnerCode' => $this->partnerCode,
            'storeId' => $this->storeId,
            'storeName' => $this->storeName,
            'requestId' => $requestId,
            'amount' => (int) $amount,
            'orderId' => $orderId,
            'orderInfo' => $orderInfo,
            'redirectUrl' => $redirectUrl,
            'ipnUrl' => $ipnUrl,
            'requestType' => 'captureWallet',
            'extraData' => $extraData,
            'lang' => 'vi',
            'signature' => $signature,
        ];
        if (! empty($userInfo)) {
            $body['userInfo'] = $userInfo;
        }

        try {
            $response = Http::timeout(30)
                ->connectTimeout(10)
                ->withHeaders(['Content-Type' => 'application/json'])
                ->post($this->baseUrl . '/v2/gateway/api/create', $body);

            $data = $response->json();
            $resultCode = (int) ($data['resultCode'] ?? -1);

            if ($resultCode === 0 && ! empty($data['payUrl'])) {
                return ['success' => true, 'payUrl' => $data['payUrl']];
            }

            $msg = $data['message'] ?? $response->body();
            Log::warning('MoMo create payment failed', [
                'resultCode' => $resultCode,
                'message' => $msg,
                'orderId' => $orderId,
            ]);

            $userMessage = is_string($msg) ? $msg : 'Không tạo được link thanh toán MoMo.';
            if ($resultCode === 41) {
                $userMessage = 'Mã đơn hàng trùng. Vui lòng thử lại hoặc chọn COD/Chuyển khoản.';
            } elseif ($resultCode === 22) {
                $userMessage = 'Số tiền không hợp lệ (MoMo: 1.000 - 50.000.000 VND).';
            } elseif ($resultCode === 20) {
                $userMessage = 'Thông tin thanh toán không đúng. Kiểm tra cấu hình MoMo (Partner Code, Access Key, Secret Key) và chạy: php artisan config:clear';
            }

            return ['success' => false, 'message' => $userMessage];
        } catch (\Throwable $e) {
            Log::error('MoMo create payment error: ' . $e->getMessage());

            return ['success' => false, 'message' => 'Lỗi kết nối MoMo. Kiểm tra mạng hoặc thử lại sau.'];
        }
    }

    /**
     * Xác minh chữ ký IPN từ MoMo (callback server-to-server).
     * Tham số theo tài liệu: accessKey, amount, extraData, message, orderId, orderInfo, orderType, partnerCode, payType, requestId, responseTime, resultCode, transId.
     */
    public function verifyIpnSignature(array $params): bool
    {
        $expected = $params['signature'] ?? '';
        if (empty($expected)) {
            return false;
        }
        $data = [
            'accessKey' => $this->accessKey,
            'amount' => $params['amount'] ?? 0,
            'extraData' => $params['extraData'] ?? '',
            'message' => $params['message'] ?? '',
            'orderId' => $params['orderId'] ?? '',
            'orderInfo' => $params['orderInfo'] ?? '',
            'orderType' => $params['orderType'] ?? '',
            'partnerCode' => $params['partnerCode'] ?? '',
            'payType' => $params['payType'] ?? '',
            'requestId' => $params['requestId'] ?? '',
            'responseTime' => $params['responseTime'] ?? 0,
            'resultCode' => $params['resultCode'] ?? -1,
            'transId' => $params['transId'] ?? 0,
        ];
        ksort($data);
        $raw = implode('&', array_map(fn ($k, $v) => $k . '=' . $v, array_keys($data), $data));
        $signature = hash_hmac('sha256', $raw, $this->secretKey);

        return hash_equals($signature, $expected);
    }
}
