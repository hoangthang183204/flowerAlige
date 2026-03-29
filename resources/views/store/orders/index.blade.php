@extends('layouts.store')

@section('title', 'Lịch sử đơn hàng')

@section('content')
    <h1 class="page-title">Lịch sử đơn hàng</h1>
    <p class="page-subtitle">Các đơn hàng bạn đã đặt và trạng thái hiện tại.</p>

    @if($orders->isEmpty())
        <p style="font-size:.9rem;color:#706f6c;">Bạn chưa có đơn hàng nào.</p>
        <a href="{{ route('products.index') }}" class="btn btn-primary" style="margin-top:.5rem;">
            Bắt đầu mua sắm
        </a>
    @else
        <div style="border-radius:.9rem;border:1px solid #e3e3e0;overflow:hidden;background:#fff;">
            <table style="width:100%;border-collapse:collapse;font-size:.9rem;">
                <thead style="background:#f8f8f5;">
                    <tr>
                        <th style="text-align:left;padding:.6rem .9rem;">Mã đơn</th>
                        <th style="text-align:left;padding:.6rem .9rem;">Ngày đặt</th>
                        <th style="text-align:left;padding:.6rem .9rem;">Thanh toán</th>
                        <th style="text-align:center;padding:.6rem .9rem;">Trạng thái</th>
                        <th style="text-align:right;padding:.6rem .9rem;">Tổng tiền</th>
                        <th style="text-align:right;padding:.6rem .9rem;">Chi tiết</th>
                    </tr>
                </thead>
                <tbody>
                    @foreach($orders as $order)
                        <tr style="border-top:1px solid #f0f0ec;">
                            <td style="padding:.6rem .9rem;font-weight:500;">#{{ $order->id }}</td>
                            <td style="padding:.6rem .9rem;">
                                {{ $order->created_at->format('d/m/Y H:i') }}
                            </td>
                            <td style="padding:.6rem .9rem;">
                                {{ match($order->payment_method) { 'cod' => 'Thanh toán khi nhận hàng', 'momo' => 'Ví MoMo', 'vnpay' => 'VNPay', default => 'Chuyển khoản' } }}
                            </td>
                            <td style="padding:.6rem .9rem;text-align:center;">
                                <span style="display:inline-flex;padding:.15rem .45rem;border-radius:999px;font-size:.78rem;
                                    @switch($order->status)
                                        @case('pending') background:#f3f3f0;color:#555; @break
                                        @case('confirmed') background:#e3f2ff;color:#18588a; @break
                                        @case('shipping') background:#fff7e0;color:#915f00; @break
                                        @case('completed') background:#e5f9eb;color:#1c7a3a; @break
                                        @case('cancelled') background:#ffe5e5;color:#a02727; @break
                                    @endswitch
                                ">
                                    {{ ucfirst($order->status) }}
                                </span>
                            </td>
                            <td style="padding:.6rem .9rem;text-align:right;font-weight:500;">
                                {{ number_format($order->total_amount, 0, ',', '.') }} đ
                            </td>
                            <td style="padding:.6rem .9rem;text-align:right;">
                                <a href="{{ route('orders.my.show', $order) }}" style="font-size:.85rem;color:#f53003;">
                                    Xem
                                </a>
                            </td>
                        </tr>
                    @endforeach
                </tbody>
            </table>
        </div>

        <div style="margin-top:1rem;font-size:.9rem;">
            {{ $orders->links() }}
        </div>
    @endif
@endsection

