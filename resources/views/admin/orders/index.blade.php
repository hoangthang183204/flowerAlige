@extends('admin.layouts.app')

@section('title', 'Quản lý đơn hàng')

@section('breadcrumb')
    <span class="font-medium text-gray-900">Đơn hàng</span>
@endsection

@section('content')
    <div class="admin-page admin-container">
        <div class="admin-page-header">
            <div>
                <div class="admin-page-title">Quản lý đơn hàng</div>
                <div class="admin-page-subtitle">Theo dõi đơn hàng, trạng thái và doanh thu.</div>
            </div>
        </div>

        <div class="admin-card overflow-hidden">
            <div class="px-5 py-4 admin-accent-bg" style="border-bottom: 1px solid var(--admin-border);">
                <h2 class="font-bold flex items-center gap-2" style="color: var(--admin-dark);">
                    <span class="w-9 h-9 rounded-lg admin-accent-bg-strong flex items-center justify-center text-base">📦</span>
                    Danh sách đơn hàng
                </h2>
            </div>
            <div class="overflow-x-auto w-full">
                <table class="admin-table">
                    <thead>
                        <tr>
                            <th style="width: 10%;">Mã đơn</th>
                            <th style="width: 22%;">Khách hàng</th>
                            <th style="width: 18%;">Liên hệ</th>
                            <th style="width: 14%; text-align:center;">Trạng thái</th>
                            <th style="width: 14%; text-align:right;">Tổng tiền</th>
                            <th style="width: 14%; text-align:right;">Thời gian</th>
                            <th style="width: 8%; text-align:right;">Xem</th>
                        </tr>
                    </thead>
                    <tbody>
                        @forelse($orders as $order)
                            <tr>
                                <td class="font-semibold">#{{ $order->id }}</td>
                                <td>
                                    <div class="font-semibold">{{ $order->customer_name }}</div>
                                    <div class="text-xs text-gray-500 mt-0.5">{{ optional($order->user)->email }}</div>
                                </td>
                                <td class="text-gray-700">
                                    {{ $order->customer_phone }}
                                    @if($order->customer_email)
                                        <div class="text-xs text-gray-500 mt-0.5">{{ $order->customer_email }}</div>
                                    @endif
                                </td>
                                <td style="text-align:center;">
                                    @php
                                        $statusMap = [
                                            'pending' => ['label' => 'Pending', 'badge' => 'admin-badge-muted'],
                                            'confirmed' => ['label' => 'Confirmed', 'badge' => 'admin-badge-success'],
                                            'shipping' => ['label' => 'Shipping', 'badge' => 'admin-badge-warning'],
                                            'completed' => ['label' => 'Completed', 'badge' => 'admin-badge-success'],
                                            'cancelled' => ['label' => 'Cancelled', 'badge' => 'admin-badge-danger'],
                                        ];
                                        $s = $statusMap[$order->status] ?? ['label' => ucfirst($order->status), 'badge' => 'admin-badge-muted'];
                                    @endphp
                                    <span class="admin-badge {{ $s['badge'] }}">{{ $s['label'] }}</span>
                                </td>
                                <td style="text-align:right;" class="font-semibold admin-accent">{{ number_format($order->total_amount, 0, ',', '.') }} đ</td>
                                <td style="text-align:right;" class="text-xs text-gray-500">{{ $order->created_at->format('d/m/Y H:i') }}</td>
                                <td style="text-align:right;">
                                    <a href="{{ route('admin.orders.show', $order) }}" class="admin-btn admin-btn-sm admin-btn-accent">Xem</a>
                                </td>
                            </tr>
                        @empty
                            <tr>
                                <td colspan="7" style="text-align:center; padding: 2rem 1rem;" class="text-sm text-gray-500">Chưa có đơn hàng nào.</td>
                            </tr>
                        @endforelse
                    </tbody>
                </table>
            </div>
        </div>

        <div class="mt-4">{{ $orders->links() }}</div>
    </div>
@endsection

