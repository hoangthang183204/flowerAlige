@extends('layouts.store')

@section('title', 'Đặt hàng thành công')

@section('content')
    <div class="thankyou-intro">
        <h1 class="page-title">Cảm ơn bạn đã đặt hoa!</h1>
        <p class="page-subtitle" style="margin-bottom:0;">
            Mã đơn hàng #{{ $order->id }}. Chúng tôi sẽ liên hệ để xác nhận và giao hàng trong thời gian sớm nhất.
        </p>
    </div>

    <div class="thankyou-card">
        <h2>Thông tin đơn hàng</h2>
        <div class="order-info">
            <div class="order-info-row">
                <span class="label">Khách hàng</span>
                <span class="value">{{ $order->customer_name }}</span>
            </div>
            <div class="order-info-row">
                <span class="label">Số điện thoại</span>
                <span class="value">{{ $order->customer_phone }}</span>
            </div>
            @if($order->customer_email)
                <div class="order-info-row">
                    <span class="label">Email</span>
                    <span class="value">{{ $order->customer_email }}</span>
                </div>
            @endif
            <div class="order-info-row">
                <span class="label">Địa chỉ giao hàng</span>
                <span class="value">{{ $order->shipping_address }}</span>
            </div>
            <div class="order-info-row">
                <span class="label">Thanh toán</span>
                <span class="value">
                    {{ match($order->payment_method) { 'cod' => 'Thanh toán khi nhận hàng (COD)', 'momo' => 'Ví MoMo', 'vnpay' => 'VNPay', default => 'Chuyển khoản ngân hàng' } }}
                </span>
            </div>
            <div class="order-info-row">
                <span class="label">Tổng tiền</span>
                <span class="value total">
                    {{ number_format($order->total_amount, 0, ',', '.') }} đ
                </span>
            </div>
        </div>
    </div>

    <div class="thankyou-card">
        <h2>Sản phẩm đã đặt</h2>
        <ul class="order-products">
            @foreach($order->items as $item)
                <li>
                    <span>
                        {{ optional($item->product)->name ?? 'Sản phẩm đã xóa' }} × {{ $item->quantity }}
                    </span>
                    <span class="price">
                        {{ number_format($item->subtotal, 0, ',', '.') }} đ
                    </span>
                </li>
            @endforeach
        </ul>
    </div>

    <div class="thankyou-actions">
        <a href="{{ route('home') }}" class="btn btn-primary">
            Quay lại trang chủ
        </a>
    </div>
@endsection
