@extends('layouts.store')

@section('title', 'Đơn hàng #' . $order->id)

@section('content')
    <h1 class="page-title">Đơn hàng #{{ $order->id }}</h1>
    <p class="page-subtitle">
        Đặt ngày {{ $order->created_at->format('d/m/Y H:i') }} –
        Trạng thái:
        {{ match ($order->status) {
            'pending' => 'Đang chờ',
            'confirmed' => 'Đã xác nhận',
            'shipping' => 'Đang giao',
            'completed' => 'Hoàn thành',
            'cancelled' => 'Đã hủy',
            default => $order->status,
        } }}
    </p>

    <div style="display:flex;flex-wrap:wrap;gap:1.5rem;align-items:flex-start;margin-bottom:1.5rem;">
        {{-- Thông tin giao hàng --}}
        <div
            style="flex:1 1 260px;min-width:0;border-radius:.9rem;border:1px solid #e3e3e0;padding:1rem;background:#fff;font-size:.9rem;">
            <div style="font-weight:600;margin-bottom:.5rem;">Thông tin giao hàng</div>
            <p style="margin:.2rem 0;"><strong>Họ tên:</strong> {{ $order->customer_name }}</p>
            <p style="margin:.2rem 0;"><strong>Số điện thoại:</strong> {{ $order->customer_phone }}</p>
            @if ($order->customer_email)
                <p style="margin:.2rem 0;"><strong>Email:</strong> {{ $order->customer_email }}</p>
            @endif
            <p style="margin:.2rem 0;"><strong>Địa chỉ:</strong> {{ $order->shipping_address }}</p>
            <p style="margin:.2rem 0;">
                <strong>Thanh toán:</strong>
                {{ match ($order->payment_method) {'cod' => 'Thanh toán khi nhận hàng (COD)','momo' => 'Ví MoMo','vnpay' => 'VNPay',default => 'Chuyển khoản ngân hàng'} }}
            </p>
            <p style="margin:.2rem 0;">
                <strong>Tổng tiền:</strong>
                <span style="color:#f53003;font-weight:600;">
                    {{ number_format($order->total_amount, 0, ',', '.') }} đ
                </span>
            </p>
            @if ($order->notes)
                <p style="margin:.4rem 0 0;"><strong>Ghi chú:</strong> {{ $order->notes }}</p>
            @endif
        </div>

        {{-- ========== PHẦN QR CODE DÙNG ẢNH CỦA BẠN ========== --}}
        @if ($order->payment_method === 'bank_transfer' && $order->status === 'pending')
            <div
                style="flex:1 1 320px;border-radius:.9rem;border:1px solid #e3e3e0;padding:1rem;background:#fff;text-align:center;">
                <div style="font-weight:600;margin-bottom:.5rem;">💳 Thanh toán chuyển khoản</div>
                <div style="font-size:.85rem;color:#666;margin-bottom:1rem;">
                    Số tiền: <strong style="color:#f53003;">{{ number_format($order->total_amount, 0, ',', '.') }}
                        đ</strong>
                </div>

                {{-- QR CODE CỦA BẠN - Chỉ cần đổi đường dẫn ảnh --}}
                <div style="margin: 1rem auto; cursor: pointer; display: inline-block;" onclick="showFullQR()">
                    <img src="{{ asset('images/qr-code.jpg') }}" alt="QR Code thanh toán"
                        style="width: 200px; height: 200px; border-radius: 8px; border: 1px solid #e3e3e0;">
                    <p style="font-size: .75rem; color: #999; margin-top: .5rem;">Nhấn vào QR để phóng to</p>
                </div>

                {{-- Thông tin tài khoản --}}
                <div style="background:#f8f8f5;padding:.8rem;border-radius:.5rem;text-align:left;font-size:.8rem;">
                    <div style="font-weight:600;margin-bottom:.5rem;">🏦 Thông tin tài khoản:</div>
                    <p style="margin:.2rem 0;"><strong>Ngân hàng:</strong> Teckcombank</p>
                    <p style="margin:.2rem 0;"><strong>Số tài khoản:</strong> 1234567890</p>
                    <p style="margin:.2rem 0;"><strong>Chủ tài khoản:</strong> FLOWER CORNER</p>
                    <p style="margin:.2rem 0;"><strong>Nội dung:</strong> DH{{ $order->id }} -
                        {{ $order->customer_name }}</p>
                </div>

                <div style="margin-top: 1rem; font-size: .8rem; color: #e67e22;">
                    ⚠️ Sau khi chuyển khoản, vui lòng chụp màn hình và liên hệ Hotline để xác nhận đơn hàng
                </div>
            </div>
        @endif
    </div>

    {{-- Modal phóng to QR --}}
    <div id="qr-modal"
        style="display:none;position:fixed;top:0;left:0;width:100%;height:100%;background:rgba(0,0,0,0.8);z-index:9999;cursor:pointer;"
        onclick="closeFullQR()">
        <div style="position:absolute;top:50%;left:50%;transform:translate(-50%,-50%);text-align:center;">
            <img src="{{ asset('images/qr-code.jpg') }}" style="max-width:90%;max-height:90%;border-radius:12px;">
        </div>
    </div>

    {{-- Nút hủy đơn hàng --}}
    <div style="margin-bottom:1rem;">
        @if (!in_array($order->status, ['shipping', 'completed', 'cancelled']))
            <form action="{{ route('orders.my.cancel', $order) }}" method="POST"
                onsubmit="return confirm('Bạn có chắc muốn hủy đơn hàng này?');" style="display:inline-block;">
                @csrf
                <button type="submit" class="btn btn-danger"
                    style="background:#a02727;border-color:#a02727;color:#fff;">Hủy đơn hàng</button>
            </form>
        @endif
    </div>

    {{-- Bảng sản phẩm --}}
    <div
        style="border-radius:.9rem;border:1px solid #e3e3e0;overflow:hidden;background:#fff;font-size:.9rem;margin-bottom:1.5rem;">
        <table style="width:100%;border-collapse:collapse;">
            <thead style="background:#f8f8f5;">
                <tr>
                    <th style="text-align:left;padding:.6rem .9rem;">Sản phẩm</th>
                    <th style="text-align:center;padding:.6rem .9rem;width:80px;">Số lượng</th>
                    <th style="text-align:right;padding:.6rem .9rem;width:120px;">Đơn giá</th>
                    <th style="text-align:right;padding:.6rem .9rem;width:120px;">Thành tiền</th>
                </tr>
            </thead>
            <tbody>
                @foreach ($order->items as $item)
                    <tr style="border-top:1px solid #f0f0ec;">
                        <td style="padding:.6rem .9rem;">
                            {{ optional($item->product)->name ?? 'Sản phẩm đã xóa' }}
                        </td>
                        <td style="padding:.6rem .9rem;text-align:center;">
                            {{ $item->quantity }}
                        </td>
                        <td style="padding:.6rem .9rem;text-align:right;">
                            {{ number_format($item->unit_price, 0, ',', '.') }} đ
                        </td>
                        <td style="padding:.6rem .9rem;text-align:right;font-weight:500;">
                            {{ number_format($item->subtotal, 0, ',', '.') }} đ
                        </td>
                    </tr>
                @endforeach
            </tbody>
        </table>
    </div>

    <a href="{{ route('orders.my') }}" class="btn btn-outline" style="border-style:solid;">
        Quay lại lịch sử đơn hàng
    </a>
@endsection

{{-- JavaScript đơn giản để mở modal --}}
@push('scripts')
    <script>
        function showFullQR() {
            document.getElementById('qr-modal').style.display = 'block';
        }

        function closeFullQR() {
            document.getElementById('qr-modal').style.display = 'none';
        }

        // Tự động mở modal nếu vừa đặt hàng xong
        @if (session('show_qr'))
            setTimeout(function() {
                showFullQR();
            }, 500);
        @endif
    </script>
@endpush
