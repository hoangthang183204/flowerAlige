@extends('admin.layouts.app')

@section('title', 'Dashboard')

@section('breadcrumb')
    <nav class="flex px-5 py-3 text-gray-700">
        <ol class="inline-flex items-center space-x-1 md:space-x-3">
            <li class="inline-flex items-center">
                <a href="{{ route('admin.dashboard') }}" class="inline-flex items-center text-sm font-medium text-gray-500 hover:text-blue-600">Dashboard</a>
            </li>
            <li aria-current="page">
                <span class="ml-1 text-sm font-medium text-gray-700 md:ml-2">Tổng quan</span>
            </li>
        </ol>
    </nav>
@endsection

@section('content')
    <div class="admin-page admin-container space-y-4">
        <div class="admin-page-header">
            <div>
                <div class="admin-page-title">Dashboard</div>
                <div class="admin-page-subtitle">Tổng quan hoạt động kinh doanh</div>
            </div>
        </div>

        {{-- Row 1: Small boxes (AdminLTE) - 4 cột 1 hàng --}}
        <div class="overflow-x-auto">
            <div class="grid gap-3 min-w-0" style="grid-template-columns: repeat(4, minmax(200px, 1fr));">
            <div class="small-box bg-info">
                <div class="inner">
                    <h3>{{ $ordersToday }}</h3>
                    <p>Đơn hàng hôm nay</p>
                </div>
                <div class="icon">
                    <svg fill="currentColor" viewBox="0 0 24 24"><path d="M16 11V7a4 4 0 00-8 0v4M5 9h14l1 12H4L5 9z"/></svg>
                </div>
                <a href="{{ route('admin.orders.index') }}" class="small-box-footer">Xem đơn hàng →</a>
            </div>
            <div class="small-box bg-success">
                <div class="inner">
                    <h3>{{ number_format($revenueToday, 0, ',', '.') }}đ</h3>
                    <p>Doanh thu hôm nay</p>
                </div>
                <div class="icon">
                    <svg fill="currentColor" viewBox="0 0 24 24"><path d="M12 8c-1.657 0-3 .895-3 2s1.343 2 3 2 3 .895 3 2-1.343 2-3 2m0-8c1.11 0 2.08.402 2.599 1M12 8V7m0 1v8m0 0v1m0-1c-1.11 0-2.08-.402-2.599-1M21 12a9 9 0 11-18 0 9 9 0 0118 0z"/></svg>
                </div>
                <a href="{{ route('admin.orders.index') }}" class="small-box-footer">Chi tiết →</a>
            </div>
            <div class="small-box bg-warning">
                <div class="inner">
                    <h3>{{ number_format($revenueThisMonth, 0, ',', '.') }}đ</h3>
                    <p>Doanh thu tháng</p>
                </div>
                <div class="icon">
                    <svg fill="currentColor" viewBox="0 0 24 24"><path d="M9 19v-6a2 2 0 00-2-2H5a2 2 0 00-2 2v6a2 2 0 002 2h2a2 2 0 002-2zm0 0V9a2 2 0 012-2h2a2 2 0 012 2v10m-6 0a2 2 0 002 2h2a2 2 0 002-2m0 0V5a2 2 0 012-2h2a2 2 0 012 2v14a2 2 0 01-2 2h-2a2 2 0 01-2-2z"/></svg>
                </div>
                <a href="{{ route('admin.orders.index') }}" class="small-box-footer">Báo cáo →</a>
            </div>
            <div class="small-box bg-danger">
                <div class="inner">
                    <h3>{{ $ordersByDay->sum('total_orders') }}</h3>
                    <p>Tổng đơn 14 ngày</p>
                </div>
                <div class="icon">
                    <svg fill="currentColor" viewBox="0 0 24 24"><path d="M9 5H7a2 2 0 00-2 2v12a2 2 0 002 2h10a2 2 0 002-2V7a2 2 0 00-2-2h-2M9 5a2 2 0 002 2h2a2 2 0 002-2M9 5a2 2 0 012-2h2a2 2 0 012 2"/></svg>
                </div>
                <a href="{{ route('admin.orders.index') }}" class="small-box-footer">Xem thêm →</a>
            </div>
            </div>
        </div>

        {{-- Row 2: Biểu đồ (full width) --}}
        <div class="admin-card overflow-hidden">
            <div class="px-5 py-4 admin-accent-bg" style="border-bottom: 1px solid var(--admin-border);">
                <div class="flex items-center justify-between gap-3 flex-wrap">
                    <h2 class="font-bold flex items-center gap-2" style="color: var(--admin-dark);">
                        <span class="w-9 h-9 rounded-lg admin-accent-bg-strong flex items-center justify-center text-base">📈</span>
                        Biểu đồ doanh thu
                    </h2>
                <div class="flex items-center gap-1" id="chartPeriodTabs">
                    <button type="button" class="traffic-tab active" data-period="day">Ngày</button>
                    <button type="button" class="traffic-tab" data-period="week">Tuần</button>
                    <button type="button" class="traffic-tab" data-period="month">Tháng</button>
                </div>
            </div>
                <p class="text-xs text-gray-500 mt-1" id="chartSubtitle">Doanh thu 14 ngày qua</p>
            </div>
            <div class="p-5">
                <div class="h-56 relative">
                    <canvas id="dashboardRevenueChart" height="220"></canvas>
                </div>
            </div>
        </div>

        {{-- Row 3: Top sản phẩm bán chạy --}}
        <div class="admin-card overflow-hidden">
                <div class="px-5 py-4 admin-accent-bg" style="border-bottom: 1px solid var(--admin-border);">
                    <h2 class="font-bold flex items-center gap-2" style="color: var(--admin-dark);">
                        <span class="w-9 h-9 rounded-lg admin-accent-bg-strong flex items-center justify-center text-base">🏆</span>
                        Top sản phẩm bán chạy
                    </h2>
                </div>
                <div class="p-0">
                    @if($topProducts->isEmpty())
                        <p class="p-4 text-sm text-gray-500 text-center">Chưa có dữ liệu.</p>
                    @else
                        <div class="overflow-x-auto">
                            <table class="admin-table">
                                <thead>
                                    <tr>
                                        <th style="width: 10%;">#</th>
                                        <th style="width: 50%;">Sản phẩm</th>
                                        <th style="width: 12%; text-align:center;">SL</th>
                                        <th style="width: 28%; text-align:right;">Doanh thu</th>
                                    </tr>
                                </thead>
                                <tbody>
                                    @foreach($topProducts as $index => $item)
                                        <tr>
                                            <td>
                                                <span class="admin-badge {{ $index < 3 ? 'admin-badge-warning' : 'admin-badge-muted' }}">{{ $index + 1 }}</span>
                                            </td>
                                            <td class="text-gray-900 font-semibold">{{ optional($item->product)->name ?? '—' }}</td>
                                            <td style="text-align:center;">{{ $item->total_quantity }}</td>
                                            <td style="text-align:right;" class="font-semibold">{{ number_format($item->total_revenue, 0, ',', '.') }}đ</td>
                                        </tr>
                                    @endforeach
                                </tbody>
                            </table>
                        </div>
                    @endif
                </div>
            </div>

        {{-- Row 4: Sản phẩm sắp hết hàng --}}
        <div class="admin-card overflow-hidden">
                <div class="px-5 py-4 admin-accent-bg" style="border-bottom: 1px solid var(--admin-border);">
                    <div class="flex items-center justify-between gap-3 flex-wrap">
                        <h2 class="font-bold flex items-center gap-2" style="color: var(--admin-dark);">
                            <span class="w-9 h-9 rounded-lg admin-accent-bg-strong flex items-center justify-center text-base">⚠️</span>
                            Sản phẩm sắp hết hàng
                        </h2>
                        <span class="admin-badge admin-badge-danger">{{ $lowStockProducts->count() }} SP</span>
                    </div>
                </div>
                <div class="p-5">
                    @if($lowStockProducts->isEmpty())
                        <p class="text-sm text-gray-500 text-center py-2">Chưa có sản phẩm sắp hết.</p>
                    @else
                        <ul class="space-y-2 max-h-64 overflow-y-auto">
                            @foreach($lowStockProducts as $product)
                                <li class="flex items-center justify-between py-2 px-3 rounded bg-orange-50 border border-orange-100">
                                    <div>
                                        <p class="text-sm font-medium text-gray-900">{{ $product->name }}</p>
                                        <p class="text-xs text-orange-600">Còn <strong>{{ $product->stock }}</strong> sản phẩm</p>
                                    </div>
                                    <a href="{{ route('admin.products.edit', $product) }}" class="text-xs font-medium text-orange-600 hover:text-orange-700">Nhập hàng →</a>
                                </li>
                            @endforeach
                        </ul>
                    @endif
                </div>
            </div>
    </div>

    <script src="https://cdn.jsdelivr.net/npm/chart.js@4.4.1/dist/chart.umd.min.js"></script>
    <script>
        (function() {
            var chartData = {
                day:   { labels: @json($chartLabels),   revenue: @json($chartRevenue),   subtitle: 'Doanh thu 14 ngày qua' },
                week:  { labels: @json($chartLabelsWeek),  revenue: @json($chartRevenueWeek),  subtitle: 'Doanh thu 12 tuần qua' },
                month: { labels: @json($chartLabelsMonth), revenue: @json($chartRevenueMonth), subtitle: 'Doanh thu 12 tháng qua' }
            };
            var ctx = document.getElementById('dashboardRevenueChart');
            var subtitleEl = document.getElementById('chartSubtitle');
            var tabsEl = document.getElementById('chartPeriodTabs');
            if (!ctx) return;

            var chart = new Chart(ctx, {
                type: 'bar',
                data: {
                    labels: chartData.day.labels,
                    datasets: [{
                        label: 'Doanh thu (đ)',
                        data: chartData.day.revenue,
                        backgroundColor: 'rgba(23, 162, 184, 0.7)',
                        borderColor: 'rgb(23, 162, 184)',
                        borderWidth: 1
                    }]
                },
                options: {
                    responsive: true,
                    maintainAspectRatio: false,
                    plugins: {
                        legend: { display: false },
                        tooltip: {
                            callbacks: {
                                label: function(ctx) {
                                    return new Intl.NumberFormat('vi-VN').format(ctx.raw) + 'đ';
                                }
                            }
                        }
                    },
                    scales: {
                        y: {
                            beginAtZero: true,
                            ticks: {
                                callback: function(v) {
                                    return v >= 1000000 ? (v/1e6) + 'M' : (v >= 1000 ? (v/1000) + 'k' : v);
                                }
                            }
                        }
                    }
                }
            });

            function setPeriod(period) {
                var data = chartData[period];
                if (!data) return;
                chart.data.labels = data.labels;
                chart.data.datasets[0].data = data.revenue;
                chart.update();
                if (subtitleEl) subtitleEl.textContent = data.subtitle;
                if (tabsEl) {
                    tabsEl.querySelectorAll('.traffic-tab').forEach(function(btn) {
                        btn.classList.toggle('active', btn.getAttribute('data-period') === period);
                    });
                }
            }

            if (tabsEl) {
                tabsEl.addEventListener('click', function(e) {
                    var btn = e.target.closest('.traffic-tab');
                    if (btn) setPeriod(btn.getAttribute('data-period'));
                });
            }
        })();
    </script>
@endsection
