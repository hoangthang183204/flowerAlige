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
    </div>

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
