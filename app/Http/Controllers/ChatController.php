<?php

namespace App\Http\Controllers;

use Illuminate\Http\Request;
use Illuminate\Support\Facades\Http;
use Illuminate\Support\Facades\Log;

class ChatController extends Controller
{
    public function chat(Request $request)
    {
        $request->validate([
            'message' => 'required|string|max:2000',
        ]);

        $key = config('services.openai.key');
        $message = $request->input('message');
        if (empty($key)) {
            $reply = $this->fallbackReply($message);
            return response()->json(['reply' => $reply]);
        }

        $systemPrompt = <<<TEXT
Bạn là trợ lý tư vấn mua hoa của shop "Flower Corner". Trả lời ngắn gọn, thân thiện, bằng tiếng Việt.

Nhiệm vụ chính: TƯ VẤN CHỌN HOA theo dịp và nhu cầu:
- Hoa sinh nhật: bó tươi, màu sắc vui (hồng, vàng, nhiều màu), có thể gợi ý hồng, cẩm chướng, đồng tiền.
- Hoa khai trương: sang trọng, may mắn (lan hồ điệp, chậu để bàn, hoa tươi lâu).
- Hoa chia buồn: trang trọng, tông trắng/vàng nhạt (cúc trắng, lily, hoa lan).
- Hoa tình yêu / Valentine: hồng đỏ, bó lãng mạn.
- Khách hỏi "nên mua gì", "gợi ý hoa": hỏi lại dịp (sinh nhật, khai trương, thăm bệnh...) rồi gợi ý phù hợp.

Sau khi gợi ý loại hoa, nhắc khách xem trang Sản phẩm trên web hoặc gọi Hotline 1900 000 000 để đặt và báo giá cụ thể.
Ngoài ra có thể trả lời về cách đặt hàng (Sản phẩm → Giỏ hàng → Thanh toán), thanh toán (COD, chuyển khoản, MoMo), giao hàng.
Luôn lịch sự và hữu ích.
TEXT;

        try {
            $response = Http::withToken($key)
                ->timeout(30)
                ->connectTimeout(10)
                ->post('https://api.openai.com/v1/chat/completions', [
                    'model' => 'gpt-4o-mini',
                    'messages' => [
                        ['role' => 'system', 'content' => $systemPrompt],
                        ['role' => 'user', 'content' => $request->input('message')],
                    ],
                    'max_tokens' => 500,
                ]);

            if (!$response->successful()) {
                Log::warning('OpenAI chat error', [
                    'status' => $response->status(),
                    'body' => $response->body(),
                ]);
                // Khi API lỗi (rate limit, key sai...) vẫn trả lời bằng fallback để khách vẫn có câu trả lời hữu ích
                $reply = $this->fallbackReply($message);
                return response()->json(['reply' => $reply]);
            }

            $data = $response->json();
            $reply = $data['choices'][0]['message']['content'] ?? 'Xin lỗi, tôi chưa trả lời được. Bạn gọi Hotline 1900 000 000 để được tư vấn.';

            return response()->json(['reply' => trim($reply)]);
        } catch (\Throwable $e) {
            Log::error('Chat error: ' . $e->getMessage(), ['trace' => $e->getTraceAsString()]);
            $reply = $this->fallbackReply($message);
            return response()->json(['reply' => $reply]);
        }
    }

    /**
     * Trả lời đơn giản khi chưa cấu hình OpenAI (hoặc lỗi).
     */
    private function fallbackReply(string $message): string
    {
        $m = mb_strtolower(trim($message));
        // Tư vấn mua hoa theo dịp
        if (str_contains($m, 'sinh nhật') || str_contains($m, 'sinh nhat')) {
            return 'Hoa sinh nhật nên chọn bó tươi màu vui như hồng, cẩm chướng, đồng tiền nhiều màu. Bạn xem mục "Hoa Sinh Nhật" trên website hoặc gọi 1900 000 000 để đặt bó phù hợp nhé!';
        }
        if (str_contains($m, 'khai trương') || str_contains($m, 'khai truong')) {
            return 'Hoa khai trương thường chọn lan hồ điệp, chậu hoa để bàn sang trọng, hoa tươi lâu. Bạn xem mục "Hoa Khai Trương" hoặc gọi 1900 000 000 để được tư vấn cụ thể.';
        }
        if (str_contains($m, 'chia buồn') || str_contains($m, 'chia buon') || str_contains($m, 'tang lễ')) {
            return 'Hoa chia buồn nên chọn tông trang trọng như cúc trắng, lily, hoa lan. Bạn xem mục Sản phẩm hoặc gọi 1900 000 000 để đặt đúng ý.';
        }
        if (str_contains($m, 'tình yêu') || str_contains($m, 'valentine') || str_contains($m, 'tinh yeu')) {
            return 'Dịp tình yêu / Valentine thường chọn hoa hồng đỏ, bó lãng mạn. Bạn xem trang Sản phẩm hoặc gọi 1900 000 000 để đặt bó đẹp nhé!';
        }
        if (str_contains($m, 'tư vấn') || str_contains($m, 'gợi ý') || str_contains($m, 'nên mua') || str_contains($m, 'chọn hoa') || str_contains($m, 'mua hoa gì') || str_contains($m, 'dịp')) {
            return 'Mình gợi ý theo dịp: sinh nhật → hoa tươi nhiều màu; khai trương → lan hồ điệp, chậu sang; chia buồn → cúc trắng, lily; tình yêu → hồng đỏ. Bạn nói rõ dịp hoặc xem mục Sản phẩm / gọi 1900 000 000 để được tư vấn chi tiết.';
        }
        if (str_contains($m, 'lan') || str_contains($m, 'hồ điệp') || str_contains($m, 'ho diep')) {
            return 'Lan hồ điệp rất phù hợp khai trương, chúc mừng, để bàn. Bạn xem mục "Lan Hồ Điệp" trên web hoặc gọi 1900 000 000 nhé!';
        }
        if (str_contains($m, 'hồng') || str_contains($m, 'hong') || str_contains($m, 'hoa đỏ')) {
            return 'Hoa hồng có nhiều màu: đỏ (tình yêu), hồng (dịu dàng), vàng (chúc mừng). Bạn xem trang Sản phẩm hoặc gọi 1900 000 000 để chọn bó ưng ý.';
        }
        // Đặt hàng, thanh toán, giao hàng
        if (str_contains($m, 'đặt hàng') || str_contains($m, 'mua hàng') || str_contains($m, 'mua hoa')) {
            return 'Bạn vào mục Sản phẩm chọn hoa, bấm "Thêm vào giỏ hàng", sau đó vào Giỏ hàng và chọn "Thanh toán" để điền thông tin giao hàng. Cần hỗ trợ thêm hãy gọi Hotline: 1900 000 000.';
        }
        if (str_contains($m, 'thanh toán') || str_contains($m, 'cod') || str_contains($m, 'chuyển khoản') || str_contains($m, 'momo')) {
            return 'Shop hỗ trợ thanh toán khi nhận hàng (COD), chuyển khoản ngân hàng và ví MoMo. Bạn chọn khi đặt hàng. Gọi 1900 000 000 nếu cần tư vấn thêm.';
        }
        if (str_contains($m, 'giá') || str_contains($m, 'phí') || str_contains($m, 'giao hàng')) {
            return 'Giá và phí giao hàng tùy sản phẩm và khu vực. Bạn xem chi tiết từng bó hoa ở trang Sản phẩm, hoặc gọi Hotline 1900 000 000 để được báo giá nhanh.';
        }
        if (str_contains($m, 'hotline') || str_contains($m, 'liên hệ') || str_contains($m, 'số điện thoại')) {
            return 'Hotline: 1900 000 000. Bạn gọi để đặt hoa hoặc hỏi đáp nhé!';
        }
        if (str_contains($m, 'chào') || str_contains($m, 'hello') || str_contains($m, 'xin chào')) {
            return 'Chào bạn! Mình là trợ lý của Flower Corner. Mình có thể tư vấn chọn hoa theo dịp (sinh nhật, khai trương, chia buồn, tình yêu), hướng dẫn đặt hàng và thanh toán. Bạn cần gì nhé?';
        }
        return 'Mình tư vấn chọn hoa theo dịp (sinh nhật, khai trương, chia buồn, tình yêu), hướng dẫn đặt hàng và thanh toán. Bạn hỏi rõ nhu cầu hoặc xem mục Sản phẩm / gọi 1900 000 000 nhé!';
    }
}
