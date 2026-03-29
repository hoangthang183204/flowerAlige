<?php

namespace App\Services;

use Illuminate\Support\Facades\Log;

class VnPayService
{
    protected string $tmnCode;
    protected string $hashSecret;
    protected string $paymentUrl;

    public function __construct()
    {
        $this->tmnCode = (string) (config('services.vnpay.tmn_code') ?? '');
        $this->hashSecret = (string) (config('services.vnpay.hash_secret') ?? '');
        $env = config('services.vnpay.env', 'sandbox');
        $this->paymentUrl = $env === 'production'
            ? 'https://vnpayment.vn/paymentv2/vpcpay.html'
            : 'https://sandbox.vnpayment.vn/paymentv2/vpcpay.html';
    }

    public function isConfigured(): bool
    {
        return $this->tmnCode !== '' && $this->hashSecret !== '';
    }

    /**
     * Tạo URL thanh toán VNPay (GET redirect).
     * Số tiền gửi sang VNPay = amount (VND) * 100.
     *
     * @param  string  $txnRef  Mã tham chiếu đơn hàng (unique trong ngày)
     * @param  int  $amount  Số tiền VND (ví dụ 100000)
     * @param  string  $orderInfo  Mô tả đơn (tiếng Việt không dấu, không ký tự đặc biệt)
     * @param  string  $returnUrl  URL redirect sau khi thanh toán
     * @param  string  $ipAddr  IP khách hàng
     * @param  string  $locale  vn | en
     * @return array{success: bool, payment_url?: string, message?: string}
     */
    public function createPaymentUrl(
        string $txnRef,
        int $amount,
        string $orderInfo,
        string $returnUrl,
        string $ipAddr = '127.0.0.1',
        string $locale = 'vn'
    ): array {
        if (! $this->isConfigured()) {
            return ['success' => false, 'message' => 'VNPay chưa được cấu hình.'];
        }

        $amount = max(1000, min(999999999999, (int) $amount));
        $vnpAmount = $amount * 100; // VNPay yêu cầu nhân 100
        $createDate = date('YmdHis');
        $expireDate = date('YmdHis', strtotime('+15 minutes'));

        $orderInfoSafe = $this->sanitizeOrderInfo($orderInfo);

        $inputData = [
            'vnp_Version' => '2.1.0',
            'vnp_Command' => 'pay',
            'vnp_TmnCode' => $this->tmnCode,
            'vnp_Amount' => $vnpAmount,
            'vnp_CreateDate' => $createDate,
            'vnp_CurrCode' => 'VND',
            'vnp_IpAddr' => $ipAddr,
            'vnp_Locale' => $locale,
            'vnp_OrderInfo' => $orderInfoSafe,
            'vnp_OrderType' => 'other',
            'vnp_ReturnUrl' => $returnUrl,
            'vnp_ExpireDate' => $expireDate,
            'vnp_TxnRef' => $txnRef,
        ];

        ksort($inputData);
        $hashdata = $this->buildHashData($inputData);
        $secureHash = hash_hmac('sha512', $hashdata, $this->hashSecret);

        $query = $hashdata . '&vnp_SecureHash=' . $secureHash;
        $paymentUrl = $this->paymentUrl . '?' . $query;

        return ['success' => true, 'payment_url' => $paymentUrl];
    }

    /**
     * VNPay quy định OrderInfo: Tiếng Việt không dấu, không ký tự đặc biệt.
     */
    private function sanitizeOrderInfo(string $str): string
    {
        $str = trim($str);
        $str = preg_replace('/[^\pL\pN\s\-]/u', '', $str);
        $map = [
            'à' => 'a', 'á' => 'a', 'ả' => 'a', 'ã' => 'a', 'ạ' => 'a',
            'ă' => 'a', 'ằ' => 'a', 'ắ' => 'a', 'ẳ' => 'a', 'ẵ' => 'a', 'ặ' => 'a',
            'â' => 'a', 'ầ' => 'a', 'ấ' => 'a', 'ẩ' => 'a', 'ẫ' => 'a', 'ậ' => 'a',
            'è' => 'e', 'é' => 'e', 'ẻ' => 'e', 'ẽ' => 'e', 'ẹ' => 'e',
            'ê' => 'e', 'ề' => 'e', 'ế' => 'e', 'ể' => 'e', 'ễ' => 'e', 'ệ' => 'e',
            'ì' => 'i', 'í' => 'i', 'ỉ' => 'i', 'ĩ' => 'i', 'ị' => 'i',
            'ò' => 'o', 'ó' => 'o', 'ỏ' => 'o', 'õ' => 'o', 'ọ' => 'o',
            'ô' => 'o', 'ồ' => 'o', 'ố' => 'o', 'ổ' => 'o', 'ỗ' => 'o', 'ộ' => 'o',
            'ơ' => 'o', 'ờ' => 'o', 'ớ' => 'o', 'ở' => 'o', 'ỡ' => 'o', 'ợ' => 'o',
            'ù' => 'u', 'ú' => 'u', 'ủ' => 'u', 'ũ' => 'u', 'ụ' => 'u',
            'ư' => 'u', 'ừ' => 'u', 'ứ' => 'u', 'ử' => 'u', 'ữ' => 'u', 'ự' => 'u',
            'ỳ' => 'y', 'ý' => 'y', 'ỷ' => 'y', 'ỹ' => 'y', 'ỵ' => 'y',
            'đ' => 'd', 'Đ' => 'D',
        ];
        $str = strtr(mb_strtolower($str), $map);
        return mb_substr($str, 0, 255);
    }

    private function buildHashData(array $inputData): string
    {
        $i = 0;
        $hashdata = '';
        foreach ($inputData as $key => $value) {
            if ($i === 0) {
                $hashdata .= urlencode($key) . '=' . urlencode((string) $value);
                $i = 1;
            } else {
                $hashdata .= '&' . urlencode($key) . '=' . urlencode((string) $value);
            }
        }
        return $hashdata;
    }

    /**
     * Xác minh chữ ký khi VNPay redirect về (GET) hoặc IPN (POST).
     * Tham số có vnp_SecureHash và vnp_SecureHashType (bỏ qua khi build lại hash).
     */
    public function verifyReturnSignature(array $params): bool
    {
        $secureHash = $params['vnp_SecureHash'] ?? '';
        if ($secureHash === '') {
            return false;
        }
        $secureHashType = $params['vnp_SecureHashType'] ?? '';
        unset($params['vnp_SecureHash'], $params['vnp_SecureHashType']);
        ksort($params);
        $hashdata = $this->buildHashData($params);
        $expectedHash = hash_hmac('sha512', $hashdata, $this->hashSecret);
        return hash_equals($expectedHash, $secureHash);
    }
}
