@extends('admin.layouts.app')

@section('title', 'Chi tiết đơn hàng #'.$order->id)

@section('breadcrumb')
    <a href="{{ route('admin.orders.index') }}" class="hover:text-gray-700">Đơn hàng</a>
    <span>/</span>
    <span class="font-medium text-gray-900">#{{ $order->id }}</span>
@endsection

@section('content')
    <div class="admin-page admin-container">
    <div class="admin-page-header">
        <div>
            <div class="admin-page-title">Đơn hàng #{{ $order->id }}</div>
            <div class="admin-page-subtitle">Chi tiết khách hàng, giao hàng và trạng thái đơn.</div>
        </div>
    </div>

    <div class="grid grid-cols-1 md:grid-cols-2 gap-4 mb-4">
        <div class="admin-card p-5 text-sm">
            <h2 class="font-semibold text-gray-900 mb-3">Thông tin khách hàng</h2>
            <div class="space-y-1.5">
                <p><span class="font-medium text-gray-600">Tên:</span> {{ $order->customer_name }}</p>
                <p><span class="font-medium text-gray-600">SĐT:</span> {{ $order->customer_phone }}</p>
                @if($order->customer_email)
                    <p><span class="font-medium text-gray-600">Email:</span> {{ $order->customer_email }}</p>
                @endif
                <p><span class="font-medium text-gray-600">Tài khoản:</span> {{ optional($order->user)->email ?? 'Khách lẻ' }}</p>
            </div>
            @if($order->notes)
                <div class="mt-4 pt-4 border-t border-gray-100">
                    <p class="font-medium text-gray-600 mb-1">Ghi chú khách hàng</p>
                    <p class="text-gray-700 text-sm whitespace-pre-line">{{ $order->notes }}</p>
                </div>
            @endif
        </div>

        <div class="admin-card p-5 text-sm">
            <h2 class="font-semibold text-gray-900 mb-3">Giao hàng & Thanh toán</h2>
            <div class="space-y-1.5">
                <p><span class="font-medium text-gray-600">Địa chỉ:</span> {{ $order->shipping_address }}</p>
                <p><span class="font-medium text-gray-600">Thanh toán:</span>
                    {{ match($order->payment_method) { 'cod' => 'COD', 'momo' => 'MoMo', 'vnpay' => 'VNPay', default => 'Chuyển khoản' } }}
                </p>
                <p><span class="font-medium text-gray-600">Tổng tiền:</span> <span class="font-semibold">{{ number_format($order->total_amount, 0, ',', '.') }} đ</span></p>
            </div>
            <div class="mt-4 pt-4 border-t border-gray-100">
                <p class="font-medium text-gray-600 mb-2">Trạng thái đơn hàng</p>
                <form action="{{ route('admin.orders.update-status', $order) }}" method="POST" class="flex flex-wrap items-center gap-2">
                    @csrf
                    @method('PATCH')
                    <select name="status" class="admin-select" style="max-width: 220px;">
                        @foreach(['pending' => 'Chờ xử lý', 'confirmed' => 'Đã xác nhận', 'shipping' => 'Đang giao', 'completed' => 'Đã giao', 'cancelled' => 'Đã hủy'] as $value => $label)
                            <option value="{{ $value }}" @selected($order->status === $value)>{{ $label }}</option>
                        @endforeach
                    </select>
                    <button type="submit" class="admin-btn admin-btn-accent">Lưu trạng thái</button>
                </form>
                <p class="mt-2">
                    <span class="admin-badge
                        @switch($order->status)
                            @case('pending') admin-badge-muted @break
                            @case('confirmed') admin-badge-success @break
                            @case('shipping') admin-badge-warning @break
                            @case('completed') admin-badge-success @break
                            @case('cancelled') admin-badge-danger @break
                        @endswitch
                    ">
                        Hiện tại: {{ match($order->status) { 'pending' => 'Chờ xử lý', 'confirmed' => 'Đã xác nhận', 'shipping' => 'Đang giao', 'completed' => 'Đã giao', 'cancelled' => 'Đã hủy', default => $order->status } }}
                    </span>
                </p>
            </div>
        </div>
    </div>

    <div class="admin-card overflow-hidden">
        <div class="px-5 py-3 border-b border-gray-100 admin-accent-bg">
            <h2 class="font-semibold text-gray-900">Sản phẩm trong đơn</h2>
        </div>
        <div class="p-4">
        <div class="overflow-x-auto w-full">
            <table class="admin-table" style="table-layout: fixed;">
                <thead>
                    <tr>
                        <th style="width: 50%;">Sản phẩm</th>
                        <th style="width: 15%; text-align:center;">Số lượng</th>
                        <th style="width: 17%; text-align:right;">Đơn giá</th>
                        <th style="width: 18%; text-align:right;">Thành tiền</th>
                    </tr>
                </thead>
            <tbody>
                @foreach($order->items as $item)
                    <tr>
                        <td>
                            {{ optional($item->product)->name ?? 'Sản phẩm đã xóa' }}
                        </td>
                        <td style="text-align:center;">
                            {{ $item->quantity }}
                        </td>
                        <td style="text-align:right;">
                            {{ number_format($item->unit_price, 0, ',', '.') }} đ
                        </td>
                        <td style="text-align:right;" class="font-semibold">
                            {{ number_format($item->subtotal, 0, ',', '.') }} đ
                        </td>
                    </tr>
                @endforeach
            </tbody>
            </table>
        </div>
        </div>
    </div>
    </div>
@endsection

