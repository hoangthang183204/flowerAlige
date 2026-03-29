<!DOCTYPE html>
<html lang="{{ str_replace('_', '-', app()->getLocale()) }}">
    <head>
        <meta charset="utf-8">
        <meta name="viewport" content="width=device-width, initial-scale=1">
        <meta name="csrf-token" content="{{ csrf_token() }}">

        <title>{{ config('app.name', 'Laravel') }}</title>

        <!-- Fonts -->
        <link rel="preconnect" href="https://fonts.bunny.net">
        <link href="https://fonts.bunny.net/css?family=figtree:400,500,600&display=swap" rel="stylesheet" />

        <!-- Scripts -->
        @vite(['resources/css/app.css', 'resources/js/app.js'])
    </head>
    <body class="font-sans antialiased bg-[#fdfdfc] text-slate-900">
        <div class="min-h-screen flex items-center justify-center px-4 bg-gradient-to-b from-rose-50 to-orange-50/40">
            <div class="max-w-5xl w-full grid gap-10 md:grid-cols-[1.2fr,1fr] items-center">
                <div class="hidden md:block">
                    <a href="/" class="inline-flex items-center gap-3 mb-6">
                        <x-application-logo class="w-10 h-10 text-pink-500" />
                        <div>
                            <div class="text-lg font-semibold tracking-tight">Flower Corner</div>
                            <div class="text-xs text-slate-500">Tươi đẹp cho mọi khoảnh khắc</div>
                        </div>
                    </a>
                    <h1 class="text-3xl font-semibold tracking-tight text-slate-900 mb-3">
                        Đặt hoa online nhanh chóng
                    </h1>
                    <p class="text-sm text-slate-600 mb-4 max-w-md">
                        Đăng ký hoặc đăng nhập để theo dõi đơn hàng, lưu thông tin giao hàng và nhận ưu đãi dành riêng cho bạn.
                    </p>
                    <ul class="space-y-2 text-sm text-slate-600">
                        <li class="flex items-start gap-2">
                            <span class="mt-0.5 text-pink-500">✓</span>
                            <span>Theo dõi lịch sử đơn hàng và trạng thái giao hoa.</span>
                        </li>
                        <li class="flex items-start gap-2">
                            <span class="mt-0.5 text-pink-500">✓</span>
                            <span>Lưu sẵn địa chỉ người nhận cho những lần đặt sau.</span>
                        </li>
                        <li class="flex items-start gap-2">
                            <span class="mt-0.5 text-pink-500">✓</span>
                            <span>Nhận thông báo các chương trình khuyến mãi mới nhất.</span>
                        </li>
                    </ul>
                </div>

                <div class="mx-auto" style="width: 400px;">
                    {{-- <div class="mb-6 flex items-center gap-3 md:hidden justify-center">
                        <a href="/" class="inline-flex items-center gap-3">
                            <x-application-logo class="w-9 h-9 text-pink-500" />
                            <div>
                                <div class="text-base font-semibold tracking-tight">Flower Corner</div>
                                <div class="text-[11px] text-slate-500">Tươi đẹp cho mọi khoảnh khắc</div>
                            </div>
                        </a>
                    </div> --}}

                    <div class="bg-white/90 backdrop-blur-sm shadow-lg shadow-rose-100/60 border border-rose-100 rounded-2xl px-6 py-6 sm:px-7 sm:py-7 overflow-y-auto box-border flex flex-col" style="width: 400px; height: 600px;">
                        {{ $slot }}
                    </div>
                </div>
            </div>
        </div>
    </body>
</html>
