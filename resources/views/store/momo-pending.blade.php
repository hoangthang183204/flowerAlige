@extends('layouts.store')

@section('title', 'Đang chờ thanh toán')

@section('content')
    <h1 class="page-title">Đang chờ xác nhận thanh toán</h1>
    <p class="page-subtitle">
        Bạn đã thanh toán qua MoMo. Chúng tôi sẽ xác nhận trong giây lát.
    </p>

    <div class="thankyou-card">
        <h2>Đơn hàng #{{ $order->id }}</h2>
        <p style="margin:0;font-size:.9rem;color:#706f6c;">
            Tổng tiền: <strong style="color:#f53003;">{{ number_format($order->total_amount, 0, ',', '.') }} đ</strong>
        </p>
    </div>

    <p style="font-size:.9rem;color:#706f6c;">
        Nếu đã thanh toán thành công, trang sẽ cập nhật khi chúng tôi nhận được thông báo từ MoMo.
    </p>

    <div class="thankyou-actions">
        <a href="{{ route('order.thankyou', $order) }}" class="btn btn-primary">Xem đơn hàng</a>
        <a href="{{ route('home') }}" class="btn btn-outline" style="margin-left:.5rem;border-style:solid;">Về trang chủ</a>
    </div>
@endsection
