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
        return !empty($this->partnerCode) && !empty($this->accessKey) && !empty($this->secretKey);
    }

    /**
     * Tạo chuỗi ký theo đúng định dạng MoMo
     */
    protected function makeSignature(array $params): string
    {
        // Loại bỏ dấu = ở cuối extraData nếu có
        $extraData = rtrim($params['extraData'], '=');
        
        // Tạo chuỗi theo đúng thứ tự
        $rawSignature = "accessKey=" . $this->accessKey
            . "&amount=" . $params['amount']
            . "&extraData=" . $extraData
            . "&ipnUrl=" . $params['ipnUrl']
            . "&orderId=" . $params['orderId']
            . "&orderInfo=" . $params['orderInfo']
            . "&partnerCode=" . $this->partnerCode
            . "&redirectUrl=" . $params['redirectUrl']
            . "&requestId=" . $params['requestId']
            . "&requestType=" . $params['requestType'];
        
        Log::info('Raw signature:', ['raw' => $rawSignature]);
        
        return hash_hmac('sha256', $rawSignature, $this->secretKey);
    }

    /**
     * Tạo thanh toán MoMo
     */
    public function createPayment(
        string $orderId,
        int $amount,
        string $orderInfo,
        string $redirectUrl,
        string $ipnUrl,
        array $userInfo = []
    ): array {
        if (!$this->isConfigured()) {
            Log::error('MoMo not configured');
            return ['success' => false, 'message' => 'MoMo chưa được cấu hình.'];
        }

        $amount = max(1000, min(50_000_000, (int) $amount));
        $requestId = $orderId . '_' . time();
        
        // QUAN TRỌNG: extraData để TRỐNG hoặc dùng ký tự đặc biệt
        // Cách 1: Để trống
        $extraData = '';
        
        // Hoặc cách 2: Dùng JSON không có dấu =
        // $extraData = urlencode(json_encode([]));
        
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
            'partnerName' => 'Flower Corner',
            'storeId' => $this->storeId,
            'storeName' => $this->storeName,
            'requestId' => $requestId,
            'amount' => $amount,
            'orderId' => $orderId,
            'orderInfo' => $orderInfo,
            'redirectUrl' => $redirectUrl,
            'ipnUrl' => $ipnUrl,
            'requestType' => 'captureWallet',
            'extraData' => $extraData,
            'lang' => 'vi',
            'signature' => $signature,
            'autoCapture' => true,
        ];
        
        if (!empty($userInfo)) {
            $body['userInfo'] = $userInfo;
        }

        Log::info('MoMo Request:', [
            'url' => $this->baseUrl . '/v2/gateway/api/create',
            'body' => $body
        ]);

        try {
            $response = Http::timeout(30)
                ->connectTimeout(10)
                ->withHeaders(['Content-Type' => 'application/json'])
                ->post($this->baseUrl . '/v2/gateway/api/create', $body);

            $data = $response->json();
            $resultCode = (int) ($data['resultCode'] ?? -1);

            Log::info('MoMo Response:', [
                'resultCode' => $resultCode,
                'message' => $data['message'] ?? 'N/A',
                'payUrl' => $data['payUrl'] ?? null
            ]);

            if ($resultCode === 0 && !empty($data['payUrl'])) {
                return ['success' => true, 'payUrl' => $data['payUrl']];
            }

            $msg = $data['message'] ?? 'Unknown error';
            return ['success' => false, 'message' => $msg];
            
        } catch (\Throwable $e) {
            Log::error('MoMo error: ' . $e->getMessage());
            return ['success' => false, 'message' => 'Lỗi kết nối: ' . $e->getMessage()];
        }
    }

    public function verifyIpnSignature(array $params): bool
    {
        $expected = $params['signature'] ?? '';
        if (empty($expected)) {
            return false;
        }
        
        $extraData = rtrim($params['extraData'] ?? '', '=');
        
        $rawSignature = "accessKey=" . ($params['accessKey'] ?? '')
            . "&amount=" . ($params['amount'] ?? '')
            . "&extraData=" . $extraData
            . "&message=" . ($params['message'] ?? '')
            . "&orderId=" . ($params['orderId'] ?? '')
            . "&orderInfo=" . ($params['orderInfo'] ?? '')
            . "&orderType=" . ($params['orderType'] ?? '')
            . "&partnerCode=" . ($params['partnerCode'] ?? '')
            . "&payType=" . ($params['payType'] ?? '')
            . "&requestId=" . ($params['requestId'] ?? '')
            . "&responseTime=" . ($params['responseTime'] ?? '')
            . "&resultCode=" . ($params['resultCode'] ?? '')
            . "&transId=" . ($params['transId'] ?? '');
        
        $signature = hash_hmac('sha256', $rawSignature, $this->secretKey);
        
        return hash_equals($signature, $expected);
    }
}