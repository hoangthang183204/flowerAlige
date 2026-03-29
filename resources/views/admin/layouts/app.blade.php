<!DOCTYPE html>
<html lang="vi">
<head>
    <meta charset="utf-8">
    <meta name="viewport" content="width=device-width, initial-scale=1">
    <title>@yield('title', 'Admin - Flower Shop')</title>
    @vite(['resources/css/app.css', 'resources/js/app.js'])
    <style>
        :root {
            --admin-accent: #f53003;
            --admin-accent-soft: #fff2f2;
            --admin-dark: #1b1b18;
            --admin-sidebar-bg: #2c3e50;
            --admin-sidebar-active: rgba(245,48,3,.25);
            --admin-border: #e3e3e0;
            --admin-bg: #f0f2f5;
        }
        .admin-bg { background: var(--admin-bg); min-height: 100vh; }
        .admin-sidebar {
            background: var(--admin-sidebar-bg);
            box-shadow: 2px 0 12px rgba(0,0,0,.15);
            transition: width .22s ease, min-width .22s ease;
            overflow: hidden;
        }
        .admin-sidebar.collapsed { width: 4.5rem !important; min-width: 4.5rem !important; }
        .admin-sidebar .logo-wrap { border-bottom: 1px solid rgba(255,255,255,.08); flex-shrink: 0; }
        .admin-sidebar a.active { background: var(--admin-sidebar-active); color: #fff; border-left: 3px solid var(--admin-accent); }
        .admin-sidebar a:not(.active):hover { background: rgba(255,255,255,.06); }
        .admin-sidebar .nav-label { white-space: nowrap; opacity: 1; transition: opacity .15s; }
        .admin-sidebar.collapsed .nav-label { opacity: 0; width: 0; overflow: hidden; pointer-events: none; display: inline-block; }
        .admin-sidebar .nav-icon { flex-shrink: 0; width: 1.5rem; text-align: center; font-size: 1rem; }
        .admin-header { background: #fff; border-bottom: 1px solid var(--admin-border); box-shadow: 0 1px 2px rgba(0,0,0,.04); }
        .admin-card { box-shadow: 0 1px 4px rgba(0,0,0,.06); border: 1px solid var(--admin-border); border-radius: 0.5rem; background: #fff; }
        .admin-accent { color: var(--admin-accent); }
        .admin-accent-bg { background-color: var(--admin-accent-soft); }
        .admin-accent-bg-strong { background-color: rgba(245,48,3,.12); }
        tr.hover-admin-row:hover { background-color: var(--admin-accent-soft); }
        .admin-btn { display: inline-flex; align-items: center; justify-content: center; padding: 0.5rem 1rem; font-size: 0.875rem; font-weight: 600; border-radius: 0.375rem; transition: all .2s; color: #fff; background: var(--admin-dark); border: none; cursor: pointer; text-decoration: none; }
        .admin-btn:hover { opacity: 0.92; }
        .admin-btn-accent { background: var(--admin-accent); }
        .admin-btn-outline { background: transparent; color: var(--admin-accent); border: 1px solid var(--admin-accent); }
        .admin-btn-outline:hover { background: var(--admin-accent-soft); }
        .admin-btn-sm { padding: 0.35rem 0.65rem; font-size: 0.8125rem; border-radius: 0.25rem; }
        .sidebar-toggle {
            position: absolute; right: -11px; top: 50%; transform: translateY(-50%);
            width: 22px; height: 44px; border-radius: 0 6px 6px 0;
            background: var(--admin-sidebar-bg); color: rgba(255,255,255,.85);
            border: 1px solid rgba(255,255,255,.1); box-shadow: 2px 0 6px rgba(0,0,0,.2);
            cursor: pointer; display: flex; align-items: center; justify-content: center; z-index: 10;
        }
        .sidebar-toggle:hover { background: var(--admin-accent); color: #fff; }
        .admin-sidebar.collapsed [data-tooltip]:hover::after {
            content: attr(data-tooltip);
            position: absolute; left: 100%; top: 50%; transform: translateY(-50%);
            margin-left: 10px; padding: 5px 8px; background: #1b1b18; color: #fff;
            font-size: 0.75rem; white-space: nowrap; border-radius: 4px;
            box-shadow: 0 2px 8px rgba(0,0,0,.2); z-index: 50; pointer-events: none;
        }
        .admin-sidebar.collapsed .sidebar-user-wrap { justify-content: center; }
        .header-icon-btn { width: 2.25rem; height: 2.25rem; border-radius: 0.375rem; display: inline-flex; align-items: center; justify-content: center; color: #5a6c7d; text-decoration: none; position: relative; }
        .header-icon-btn:hover { background: #f0f2f5; color: #1b1b18; }
        .header-badge { position: absolute; top: 2px; right: 2px; min-width: 1rem; height: 1rem; padding: 0 4px; font-size: 0.65rem; font-weight: 700; border-radius: 10px; background: var(--admin-accent); color: #fff; display: inline-flex; align-items: center; justify-content: center; }
        .metric-card { border-radius: 0.5rem; overflow: hidden; color: #fff; position: relative; }
        .metric-card .metric-chart { position: absolute; bottom: 0; left: 0; right: 0; height: 60px; opacity: 0.9; }
        .traffic-tab { padding: 0.35rem 0.75rem; font-size: 0.8125rem; font-weight: 500; border-radius: 0.25rem; border: 1px solid var(--admin-border); background: #fff; color: #495057; cursor: pointer; }
        .traffic-tab.active { background: var(--admin-accent); color: #fff; border-color: var(--admin-accent); }
        .traffic-tab:hover:not(.active) { background: #f8f9fa; }
        .stat-bar { height: 4px; border-radius: 2px; margin-top: 4px; }
        /* AdminLTE-style: small box & card */
        :root {
            --lte-info: #17a2b8;
            --lte-success: #28a745;
            --lte-warning: #ffc107;
            --lte-danger: #dc3545;
        }
        .small-box { border-radius: 0.25rem; display: block; position: relative; box-shadow: 0 0 1px rgba(0,0,0,.125), 0 1px 3px rgba(0,0,0,.2); }
        .small-box > .inner { padding: 10px; }
        .small-box > .inner h3, .small-box > .inner p { margin: 0; color: #fff; }
        .small-box > .inner h3 { font-size: 1.75rem; font-weight: 700; }
        .small-box > .inner p { font-size: 0.875rem; opacity: .9; }
        .small-box > .icon { position: absolute; top: 0; right: 0; bottom: 0; width: 70px; display: flex; align-items: center; justify-content: center; font-size: 2rem; opacity: .3; }
        .small-box > .icon svg { width: 2.5rem; height: 2.5rem; }
        .small-box .small-box-footer { display: block; padding: 3px 0; position: relative; text-align: center; text-decoration: none; color: rgba(255,255,255,.8); font-size: 0.75rem; z-index: 10; }
        .small-box .small-box-footer:hover { color: #fff; }
        .small-box.bg-info { background: linear-gradient(135deg, #17a2b8 0%, #138496 100%); color: #fff; }
        .small-box.bg-success { background: linear-gradient(135deg, #28a745 0%, #1e7e34 100%); color: #fff; }
        .small-box.bg-warning { background: linear-gradient(135deg, #ffc107 0%, #e0a800 100%); color: #1f2937; }
        .small-box.bg-warning .inner h3, .small-box.bg-warning .inner p { color: #1f2937; }
        .small-box.bg-warning .small-box-footer { color: rgba(0,0,0,.6); }
        .small-box.bg-warning .small-box-footer:hover { color: #000; }
        .small-box.bg-danger { background: linear-gradient(135deg, #dc3545 0%, #c82333 100%); color: #fff; }
        .card { position: relative; display: flex; flex-direction: column; min-width: 0; word-wrap: break-word; background: #fff; border: 0 solid rgba(0,0,0,.125); border-radius: 0.25rem; box-shadow: 0 0 1px rgba(0,0,0,.125), 0 1px 3px rgba(0,0,0,.2); }
        .card-body { flex: 1 1 auto; padding: 1.25rem; }
        .card-header { padding: 0.75rem 1.25rem; margin-bottom: 0; background: #fff; border-bottom: 1px solid rgba(0,0,0,.125); font-weight: 600; color: #495057; display: flex; align-items: center; justify-content: space-between; flex-wrap: wrap; gap: 0.5rem; }
        .card-header .card-title { margin: 0; font-size: 1rem; font-weight: 600; }

        /* Unified admin theme helpers */
        .admin-container { max-width: 1200px; margin: 0 auto; }
        .admin-page { padding: 0.25rem; }
        @media (min-width: 768px) { .admin-page { padding: 0.75rem; } }
        .admin-page-header { display: flex; align-items: flex-start; justify-content: space-between; gap: 0.75rem; margin-bottom: 1rem; }
        .admin-page-title { font-size: 1.25rem; font-weight: 700; color: #1f2937; line-height: 1.2; }
        .admin-page-subtitle { font-size: 0.875rem; color: #6b7280; margin-top: 0.25rem; }
        .admin-actions { display: flex; align-items: center; gap: 0.5rem; flex-wrap: wrap; justify-content: flex-end; }

        .admin-form { padding: 1rem; }
        .admin-field { display: grid; gap: 0.35rem; }
        .admin-label { font-size: 0.875rem; font-weight: 600; color: #374151; }
        .admin-input, .admin-textarea, .admin-select {
            width: 100%;
            border: 1px solid #d1d5db;
            border-radius: 0.5rem;
            padding: 0.5rem 0.75rem;
            font-size: 0.875rem;
            background: #fff;
            outline: none;
        }
        .admin-input:focus, .admin-textarea:focus, .admin-select:focus {
            border-color: var(--admin-accent);
            box-shadow: 0 0 0 3px rgba(245,48,3,.14);
        }
        .admin-help { font-size: 0.75rem; color: #6b7280; }
        .admin-error { font-size: 0.75rem; color: #b91c1c; }
        .admin-form-footer { display: flex; align-items: center; gap: 0.75rem; padding-top: 0.75rem; margin-top: 1rem; border-top: 1px solid #e5e7eb; }

        .admin-table { width: 100%; border-collapse: collapse; }
        .admin-table thead tr { background: #f8fafc; }
        .admin-table th { text-align: left; font-size: 0.75rem; letter-spacing: .03em; text-transform: uppercase; color: #6b7280; font-weight: 700; padding: 0.75rem 1rem; border-bottom: 1px solid #e5e7eb; }
        .admin-table td { padding: 0.75rem 1rem; border-bottom: 1px solid #eef2f7; font-size: 0.875rem; color: #111827; vertical-align: middle; }
        .admin-table tbody tr:hover { background: #fff2f2; }

        .admin-badge { display: inline-flex; align-items: center; justify-content: center; padding: 0.15rem 0.5rem; border-radius: 999px; font-size: 0.75rem; font-weight: 700; }
        .admin-badge-success { background: rgba(40,167,69,.12); color: #166534; }
        .admin-badge-warning { background: rgba(255,193,7,.18); color: #92400e; }
        .admin-badge-danger { background: rgba(220,53,69,.12); color: #991b1b; }
        .admin-badge-muted { background: #f3f4f6; color: #4b5563; }
    </style>
</head>
<body class="font-sans antialiased" style="color: var(--admin-dark);" x-data="adminSidebar()">
    <div class="min-h-screen flex admin-bg">
        {{-- Sidebar --}}
        <aside class="admin-sidebar flex flex-col flex-shrink-0 relative"
                style="width: 16rem; min-width: 16rem; color: rgba(255,255,255,.9);"
                :class="{ 'collapsed': collapsed }">
            <div class="h-14 flex items-center logo-wrap px-3">
                <button type="button" class="flex-shrink-0 w-9 h-9 rounded-lg flex items-center justify-center hover:bg-white/10 mr-2" @click="toggle()" aria-label="Menu">
                    <svg class="w-5 h-5" fill="currentColor" viewBox="0 0 20 20"><path fill-rule="evenodd" d="M3 5a1 1 0 011-1h12a1 1 0 110 2H4a1 1 0 01-1-1zm0 5a1 1 0 011-1h12a1 1 0 110 2H4a1 1 0 01-1-1zm0 5a1 1 0 011-1h12a1 1 0 110 2H4a1 1 0 01-1-1z" clip-rule="evenodd"/></svg>
                </button>
                <a href="{{ route('admin.dashboard') }}" class="flex items-center gap-2 min-w-0">
                    <span class="w-8 h-8 rounded-lg admin-accent-bg-strong flex items-center justify-center text-lg flex-shrink-0">🌸</span>
                    <span class="nav-label font-semibold text-sm" style="color: #fff;">Flower Admin</span>
                </a>
            </div>
            <nav class="flex-1 py-3 text-sm overflow-y-auto overflow-x-hidden">
                <ul class="space-y-0.5 px-2">
                    <li>
                        <a href="{{ route('admin.reports.index') }}" class="admin-nav flex items-center gap-2.5 px-3 py-2 rounded-lg {{ request()->routeIs('admin.reports.*') ? 'active' : '' }}" data-tooltip="Dashboard">
                            <span class="nav-icon">📈</span><span class="nav-label">Dashboard</span>
                        </a>
                    </li>
                    <li class="pt-2">
                        <div class="nav-label px-3 py-1 text-[10px] font-semibold uppercase tracking-wider" style="color: rgba(255,255,255,.45);">Quản lý</div>
                    </li>
                    <li>
                        <a href="{{ route('admin.categories.index') }}" class="admin-nav flex items-center gap-2.5 px-3 py-2 rounded-lg {{ request()->routeIs('admin.categories.*') ? 'active' : '' }}" data-tooltip="Danh mục">
                            <span class="nav-icon">📂</span><span class="nav-label">Danh mục</span>
                        </a>
                    </li>
                    <li>
                        <a href="{{ route('admin.products.index') }}" class="admin-nav flex items-center gap-2.5 px-3 py-2 rounded-lg {{ request()->routeIs('admin.products.*') ? 'active' : '' }}" data-tooltip="Sản phẩm">
                            <span class="nav-icon">💐</span><span class="nav-label">Sản phẩm</span>
                        </a>
                    </li>
                    <li>
                        <a href="{{ route('admin.orders.index') }}" class="admin-nav flex items-center gap-2.5 px-3 py-2 rounded-lg {{ request()->routeIs('admin.orders.*') ? 'active' : '' }}" data-tooltip="Đơn hàng">
                            <span class="nav-icon">📦</span><span class="nav-label">Đơn hàng</span>
                        </a>
                    </li>
                    <li>
                        <a href="{{ route('admin.banners.index') }}" class="admin-nav flex items-center gap-2.5 px-3 py-2 rounded-lg {{ request()->routeIs('admin.banners.*') ? 'active' : '' }}" data-tooltip="Banner">
                            <span class="nav-icon">🏷️</span><span class="nav-label">Banner</span>
                        </a>
                    </li>
                    <li>
                        <a href="{{ route('admin.posts.index') }}" class="admin-nav flex items-center gap-2.5 px-3 py-2 rounded-lg {{ request()->routeIs('admin.posts.*') ? 'active' : '' }}" data-tooltip="Bài viết">
                            <span class="nav-icon">📰</span><span class="nav-label">Bài viết</span>
                        </a>
                    </li>
                    <li>
                        <a href="{{ route('admin.users.index') }}" class="admin-nav flex items-center gap-2.5 px-3 py-2 rounded-lg {{ request()->routeIs('admin.users.*') ? 'active' : '' }}" data-tooltip="Tài khoản">
                            <span class="nav-icon">👤</span><span class="nav-label">Tài khoản</span>
                        </a>
                    </li>
                </ul>
            </nav>
            <div class="border-t border-white/10 px-2 py-2.5 flex-shrink-0 sidebar-user-wrap">
                <div class="flex items-center gap-2 min-w-0">
                    <span class="w-8 h-8 rounded-full bg-white/15 flex items-center justify-center text-xs font-bold flex-shrink-0" style="color: #fff;">{{ strtoupper(substr(auth()->user()->name ?? 'A', 0, 1)) }}</span>
                    <div class="min-w-0 overflow-hidden">
                        <div class="nav-label text-xs font-medium truncate" style="color: rgba(255,255,255,.9);">{{ auth()->user()->name ?? '' }}</div>
                        <div class="nav-label text-[10px] truncate" style="color: rgba(255,255,255,.5);">{{ auth()->user()->email ?? '' }}</div>
                    </div>
                </div>
            </div>
            <button type="button" class="sidebar-toggle" @click="toggle()" :aria-label="collapsed ? 'Mở rộng' : 'Thu gọn'">
                <span x-text="collapsed ? '›' : '‹'" class="text-base font-bold leading-none"></span>
            </button>
        </aside>

        <div class="flex-1 flex flex-col min-w-0">
            {{-- Header: breadcrumb + icons --}}
            <header class="admin-header h-12 flex items-center px-4">
                <div class="w-full flex items-center justify-between gap-4">
                    <nav class="flex items-center gap-2 text-sm text-gray-500">
                        <a href="{{ route('admin.dashboard') }}" class="hover:text-gray-700">Home</a>
                        <span>/</span>
                        <a href="{{ route('admin.dashboard') }}" class="hover:text-gray-700">Admin</a>
                        @hasSection('breadcrumb')
                            <span>/</span>
                            @yield('breadcrumb')
                        @else
                            <span>/</span>
                            <span class="font-medium text-gray-900">@yield('title', 'Dashboard')</span>
                        @endif
                    </nav>
                    <div class="flex items-center gap-1">
                        <a href="{{ route('home') }}" class="header-icon-btn" title="Trang chủ">🏠</a>
                        <a href="#" class="header-icon-btn relative" title="Thông báo">
                            🔔
                            <span class="header-badge">5</span>
                        </a>
                        <a href="#" class="header-icon-btn relative" title="Tin nhắn">
                            ✉️
                            <span class="header-badge" style="background: #f59e0b;">15</span>
                        </a>
                        <a href="#" class="header-icon-btn w-8 h-8 rounded-full overflow-hidden border border-gray-200" title="Tài khoản">
                            <span class="w-full h-full bg-gray-200 flex items-center justify-center text-xs font-bold text-gray-600">{{ strtoupper(substr(auth()->user()->name ?? 'A', 0, 1)) }}</span>
                        </a>
                        <a href="#" class="header-icon-btn" title="Cài đặt">⚙️</a>
                    </div>
                </div>
            </header>

            <main class="flex-1 p-4">
                @if (session('success'))
                    <div class="mb-4 rounded-lg admin-accent-bg border border-[var(--admin-accent)] px-4 py-2.5 text-sm font-medium admin-accent">
                        {{ session('success') }}
                    </div>
                @endif
                @if (session('error'))
                    <div class="mb-4 rounded-lg bg-red-50 border border-red-200 px-4 py-2.5 text-sm text-red-800">
                        {{ session('error') }}
                    </div>
                @endif
                @yield('content')
            </main>
        </div>
    </div>
    <script>
        document.addEventListener('alpine:init', () => {
            Alpine.data('adminSidebar', () => ({
                collapsed: localStorage.getItem('adminSidebarCollapsed') === '1',
                toggle() {
                    this.collapsed = !this.collapsed;
                    localStorage.setItem('adminSidebarCollapsed', this.collapsed ? '1' : '0');
                }
            }));
        });
    </script>
</body>
</html>
