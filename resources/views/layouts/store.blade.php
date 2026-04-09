<!DOCTYPE html>
<html lang="vi">
<head>
    <meta charset="utf-8">
    <meta name="viewport" content="width=device-width, initial-scale=1">
    <meta name="csrf-token" content="{{ csrf_token() }}">
    <title>@yield('title', 'Shop hoa tươi')</title>
    <link rel="preconnect" href="https://fonts.bunny.net">
    <link href="https://fonts.bunny.net/css?family=instrument-sans:400,500,600" rel="stylesheet" />
    <style>
        :root {
            --pink: #f53003;
            --pink-soft: #fff2f2;
            --border-soft: #e3e3e0;
        }
        body {
            margin: 0;
            font-family: 'Instrument Sans', system-ui, -apple-system, BlinkMacSystemFont, "Segoe UI", sans-serif;
            background-color: #fdfdfc;
            color: #1b1b18;
        }
        a {
            color: inherit;
            text-decoration: none;
        }
        .container {
            max-width: 1100px;
            margin: 0 auto;
            padding: 1.5rem;
        }
        .topbar {
            background: #1b1b18;
            color: #fff;
            font-size: .8rem;
        }
        .topbar-inner {
            max-width: 1100px;
            margin: 0 auto;
            padding: .35rem 1.5rem;
            display: flex;
            align-items: center;
            justify-content: space-between;
            gap: 1rem;
        }
        .topbar-links {
            display: flex;
            align-items: center;
            gap: .75rem;
        }
        .topbar-links a {
            text-decoration: none;
            font-weight: 500;
        }
        .topbar-links a:hover {
            text-decoration: underline;
            text-underline-offset: 3px;
        }
        header {
            border-bottom: 1px solid var(--border-soft);
            background: #ffffff;
            position: sticky;
            top: 0;
            z-index: 20;
        }
        .nav-main {
            display: flex;
            align-items: center;
            justify-content: space-between;
            gap: 1.5rem;
            padding: .75rem 1.5rem;
            max-width: 1100px;
            margin: 0 auto;
        }
        .nav-logo {
            font-weight: 600;
            font-size: 1.2rem;
            display: flex;
            align-items: center;
            gap: .5rem;
        }
        .nav-logo span:nth-child(1) {
            font-size: 1.5rem;
        }
        .nav-menu {
            display: flex;
            align-items: center;
            gap: 1.25rem;
            font-size: .88rem;
        }
        .nav-menu a {
            font-weight: 500;
        }
        .nav-menu-secondary {
            display: flex;
            align-items: center;
            gap: .75rem;
            font-size: .85rem;
        }
        .badge-pill {
            display: inline-flex;
            align-items: center;
            justify-content: center;
            padding: .15rem .5rem;
            border-radius: 999px;
            background: var(--pink-soft);
            color: var(--pink);
            font-size: .75rem;
        }
        .btn {
            display: inline-flex;
            align-items: center;
            justify-content: center;
            padding: .45rem .9rem;
            border-radius: .35rem;
            border: 1px solid #19140035;
            font-size: .9rem;
            cursor: pointer;
            background-color: #fff;
            transition: all .15s ease;
        }
        .btn-icon {
            gap: .35rem;
        }
        .btn-primary {
            background: var(--pink);
            color: #fff;
            border-color: var(--pink);
        }
        .btn-primary:hover {
            background: #d12600;
        }
        .btn-outline:hover {
            border-color: #000;
        }
        .filter-sidebar {
            flex: 0 0 260px;
            min-width: 0;
            max-width: 100%;
            box-sizing: border-box;
            overflow: hidden;
            background: #fff;
            border-radius: 1rem;
            border: 1px solid var(--border-soft);
            padding: 1.25rem;
        }
        .filter-field {
            margin-bottom: 1rem;
        }
        .filter-field label {
            display: block;
            font-size: .85rem;
            font-weight: 500;
            margin-bottom: .25rem;
        }
        .filter-input {
            width: 100%;
            padding: .5rem .75rem;
            border-radius: .5rem;
            border: 1px solid var(--border-soft);
            font-size: .9rem;
            box-sizing: border-box;
            font-family: inherit;
        }
        .filter-row {
            display: flex;
            gap: .5rem;
            margin-bottom: 1rem;
        }
        .filter-row .filter-field {
            flex: 1;
            margin-bottom: 0;
        }
        .filter-actions {
            width: 100%;
            box-sizing: border-box;
        }
        .filter-actions .btn,
        .filter-actions a.btn {
            display: flex;
            align-items: center;
            justify-content: center;
            width: 100%;
            max-width: 100%;
            box-sizing: border-box;
            border-radius: .5rem;
        }
        .filter-actions .btn + .btn,
        .filter-actions .btn + a {
            margin-top: .5rem;
        }
        .form-field {
            margin-bottom: 1rem;
        }
        .form-field label:first-child {
            display: block;
            font-size: .85rem;
            font-weight: 500;
            margin-bottom: .25rem;
        }
        .form-input {
            width: 100%;
            padding: .5rem .75rem;
            border-radius: .5rem;
            border: 1px solid var(--border-soft);
            font-size: .9rem;
            box-sizing: border-box;
            font-family: inherit;
        }
        .form-input::placeholder {
            color: #999;
        }
        textarea.form-input {
            resize: vertical;
            min-height: 4rem;
        }
        .form-row {
            display: flex;
            gap: .75rem;
            flex-wrap: wrap;
            margin-bottom: 1rem;
        }
        .form-row .form-field {
            flex: 1 1 160px;
            min-width: 0;
            margin-bottom: 0;
        }
        .search-form {
            max-width: 420px;
        }
        .search-row {
            display: flex;
            gap: 0.5rem;
            align-items: stretch;
        }
        .search-input {
            flex: 1;
            min-width: 0;
            padding: 0.55rem 0.85rem;
            border-radius: 0.5rem;
            border: 1px solid var(--border-soft);
            font-size: 0.9rem;
            font-family: inherit;
        }
        .search-input::placeholder {
            color: #999;
        }
        .search-btn {
            flex-shrink: 0;
            padding: 0.55rem 1rem;
        }
        main {
            padding: 1.5rem 0 3rem;
        }
        .page-title {
            font-size: 1.5rem;
            font-weight: 600;
            margin-bottom: .25rem;
        }
        .page-subtitle {
            font-size: .92rem;
            color: #706f6c;
            margin-bottom: 1.5rem;
        }
        .alert {
            padding: .75rem 1rem;
            border-radius: .5rem;
            font-size: .9rem;
            margin-bottom: 1rem;
        }
        .alert-success {
            background: #ecffef;
            border: 1px solid #a6e4b0;
        }
        .alert-error {
            background: #fff5f5;
            border: 1px solid #f8b1b1;
        }
        .thankyou-intro {
            margin-bottom: 1.5rem;
        }
        .thankyou-card {
            background: #fff;
            border-radius: 1rem;
            border: 1px solid var(--border-soft);
            padding: 1.25rem 1.5rem;
            margin-bottom: 1.25rem;
            box-shadow: 0 1px 3px rgba(0,0,0,.04);
        }
        .thankyou-card h2 {
            font-size: 1rem;
            font-weight: 600;
            margin-bottom: .75rem;
            color: #1b1b18;
        }
        .order-info {
            display: grid;
            gap: .5rem .75rem;
            font-size: .9rem;
        }
        .order-info-row {
            display: grid;
            grid-template-columns: 10rem 1fr;
            align-items: start;
            gap: .5rem;
        }
        .order-info-row .label {
            font-weight: 500;
            color: #4a4946;
        }
        .order-info-row .value {
            color: #1b1b18;
        }
        .order-info-row .value.total {
            font-weight: 600;
            color: var(--pink);
        }
        .order-products {
            list-style: none;
            margin: 0;
            padding: 0;
            font-size: .9rem;
        }
        .order-products li {
            display: flex;
            justify-content: space-between;
            align-items: center;
            gap: 1rem;
            padding: .6rem 0;
            border-bottom: 1px solid #f2f2ee;
        }
        .order-products li:last-child {
            border-bottom: none;
        }
        .order-products .price {
            font-weight: 500;
            color: #1b1b18;
        }
        .thankyou-actions {
            margin-top: 1.5rem;
        }
        footer {
            border-top: 1px solid var(--border-soft);
            background: #faf9f6;
            margin-top: auto;
        }
        .footer-inner {
            max-width: 1100px;
            margin: 0 auto;
            padding: 1.5rem 1.5rem 1rem;
        }
        .footer-grid {
            display: grid;
            grid-template-columns: 1fr auto auto;
            gap: 2rem;
            align-items: start;
            margin-bottom: 1.25rem;
        }
        .footer-brand {
            font-size: 1rem;
            font-weight: 600;
            color: #1b1b18;
            display: flex;
            align-items: center;
            gap: .4rem;
        }
        .footer-brand span:first-child {
            font-size: 1.25rem;
        }
        .footer-tagline {
            font-size: .85rem;
            color: #706f6c;
            margin-top: .25rem;
        }
        .footer-title {
            font-size: .8rem;
            font-weight: 600;
            color: #1b1b18;
            text-transform: uppercase;
            letter-spacing: .02em;
            margin-bottom: .5rem;
        }
        .footer-links {
            display: flex;
            flex-direction: column;
            gap: .35rem;
        }
        .footer-links a {
            font-size: .88rem;
            color: #706f6c;
        }
        .footer-links a:hover {
            color: var(--pink);
        }
        .footer-contact {
            font-size: .88rem;
            color: #706f6c;
        }
        .footer-contact strong {
            color: #1b1b18;
        }
        .footer-bottom {
            padding-top: 1rem;
            border-top: 1px solid var(--border-soft);
            font-size: .8rem;
            color: #706f6c;
            text-align: center;
        }
        @media (max-width: 640px) {
            .footer-grid {
                grid-template-columns: 1fr;
                gap: 1.25rem;
            }
            .footer-inner {
                padding-inline: 1rem;
            }
        }
        .category-strip {
            border-top: 1px solid var(--border-soft);
            border-bottom: 1px solid var(--border-soft);
            background: #faf9f6;
            overflow-x: auto;
            white-space: nowrap;
        }
        .category-strip-inner {
            max-width: 1100px;
            margin: 0 auto;
            padding: .4rem 1.5rem;
            display: flex;
            gap: 1rem;
            font-size: .82rem;
        }
        .category-pill {
            padding: .25rem .7rem;
            border-radius: 999px;
            border: 1px solid transparent;
        }
        .category-pill:hover {
            border-color: var(--pink);
            color: var(--pink);
        }
        #chat-widget {
            position: fixed;
            bottom: 1.25rem;
            right: 1.25rem;
            z-index: 999;
            font-family: inherit;
        }
        .chat-toggle {
            width: 56px;
            height: 56px;
            border-radius: 50%;
            background: var(--pink);
            color: #fff;
            border: none;
            cursor: pointer;
            box-shadow: 0 4px 12px rgba(245, 48, 3, .35);
            display: flex;
            align-items: center;
            justify-content: center;
            font-size: 1.5rem;
        }
        .chat-toggle:hover {
            background: #d12600;
        }
        .chat-panel {
            display: none;
            position: absolute;
            bottom: 0;
            right: 0;
            width: 340px;
            max-width: calc(100vw - 2rem);
            height: 420px;
            background: #fff;
            border-radius: 1rem;
            box-shadow: 0 8px 32px rgba(0,0,0,.12);
            border: 1px solid var(--border-soft);
            flex-direction: column;
            overflow: hidden;
        }
        #chat-widget.open .chat-panel {
            display: flex;
        }
        #chat-widget.open .chat-toggle {
            display: none;
        }
        .chat-panel-header {
            position: relative;
            padding: .75rem 1rem;
            background: var(--pink-soft);
            font-weight: 600;
            font-size: .9rem;
            color: #1b1b18;
        }
        .chat-messages {
            flex: 1;
            overflow-y: auto;
            padding: .75rem;
            display: flex;
            flex-direction: column;
            gap: .6rem;
        }
        .chat-msg {
            max-width: 85%;
            padding: .5rem .75rem;
            border-radius: .75rem;
            font-size: .88rem;
            line-height: 1.4;
        }
        .chat-msg.user {
            align-self: flex-end;
            background: var(--pink);
            color: #fff;
        }
        .chat-msg.bot {
            align-self: flex-start;
            background: #f2f2ee;
            color: #1b1b18;
        }
        .chat-typing {
            align-self: flex-start;
            padding: .5rem .75rem;
            background: #f2f2ee;
            border-radius: .75rem;
            font-size: .8rem;
            color: #706f6c;
        }
        .chat-form {
            padding: .75rem;
            border-top: 1px solid var(--border-soft);
            display: flex;
            gap: .5rem;
        }
        .chat-form input {
            flex: 1;
            padding: .5rem .75rem;
            border: 1px solid var(--border-soft);
            border-radius: .5rem;
            font-size: .9rem;
        }
        .chat-form button {
            padding: .5rem .85rem;
            background: var(--pink);
            color: #fff;
            border: none;
            border-radius: .5rem;
            font-weight: 500;
            cursor: pointer;
        }
        .chat-form button:hover {
            background: #d12600;
        }
        .chat-close {
            position: absolute;
            top: 50%;
            right: .5rem;
            transform: translateY(-50%);
            background: none;
            border: none;
            cursor: pointer;
            font-size: 1.2rem;
            color: #706f6c;
        }
        @media (max-width: 640px) {
            .nav-main {
                flex-direction: column;
                align-items: flex-start;
            }
            .topbar-inner {
                padding-inline: 1rem;
            }
            .category-strip-inner {
                padding-inline: 1rem;
            }
        }
    </style>
    @yield('head')
</head>
<body>
    <div class="topbar">
        <div class="topbar-inner">
            <div>HOTLINE: 0123456789</div>
            <div class="topbar-links">
                @auth
                    <a href="{{ route('orders.my') }}">Lịch sử đơn hàng</a>
                    <a href="{{ route('profile.edit') }}">Tài khoản</a>
                @else
                    <a href="{{ route('register') }}">Đăng ký</a>
                    <a href="{{ route('login') }}">Đăng nhập</a>
                @endauth
                <a href=""></a>
            </div>
        </div>
    </div>

    <header>
        <div class="nav-main">
            <a href="{{ route('home') }}" class="nav-logo">
                <span>🌸</span>
                <span>Flower Corner</span>
            </a>
            <nav class="nav-menu">
                <a href="{{ route('products.index', ['category' => 'hoa-sinh-nhat']) }}">Hoa Sinh Nhật</a>
                <a href="{{ route('products.index', ['category' => 'hoa-khai-truong']) }}">Hoa Khai Trương</a>
                <a href="{{ route('products.index', ['category' => 'lan-ho-diep']) }}">Lan Hồ Điệp</a>
                <a href="{{ route('products.index') }}">Chủ đề</a>
                <a href="{{ route('products.index') }}">Thiết kế</a>
                <a href="{{ route('products.index') }}">Hoa tươi</a>
                <a href="{{ route('blog.index') }}">Blog</a>
            </nav>
            <div class="nav-menu-secondary">
                <span class="badge-pill">Giảm đến 30%</span>
                <a href="{{ route('cart.show') }}" class="btn btn-icon btn-outline" style="border-style:solid;">
                    <span>🛒</span><span>Giỏ hàng</span>
                </a>
            </div>
        </div>
        <div class="category-strip">
            <div class="category-strip-inner">
                <a href="{{ route('products.index') }}" class="category-pill">Đang giảm giá</a>
                <a href="{{ route('products.index') }}" class="category-pill">Đặt nhiều nhất</a>
                <a href="{{ route('products.index') }}" class="category-pill">Sản phẩm mới</a>
                <a href="{{ route('products.index', ['category' => 'hoa-sinh-nhat']) }}" class="category-pill">Hoa sinh nhật</a>
                <a href="{{ route('products.index', ['category' => 'hoa-khai-truong']) }}" class="category-pill">Hoa khai trương</a>
                {{-- <a href="{{ route('products.index', ['category' => 'hoa-chia-buon']) }}" class="category-pill">Hoa chia buồn</a> --}}
            </div>
        </div>
    </header>

    <div class="container">
        <main>
            @if (session('success'))
                <div class="alert alert-success">
                    {{ session('success') }}
                </div>
            @endif

            @if (session('error'))
                <div class="alert alert-error">
                    {{ session('error') }}
                </div>
            @endif

            @yield('content')
        </main>

        <footer>
            <div class="footer-inner">
                <div class="footer-grid">
                    <div>
                        <div class="footer-brand">
                            <span>🌸</span>
                            <span>Flower Corner</span>
                        </div>
                        <p class="footer-tagline">Tươi đẹp cho mọi khoảnh khắc.</p>
                    </div>
                    <div>
                        <div class="footer-title">Liên kết</div>
                        <nav class="footer-links">
                            <a href="{{ route('home') }}">Trang chủ</a>
                            <a href="{{ route('products.index') }}">Sản phẩm</a>
                            <a href="{{ route('blog.index') }}">Blog</a>
                            <a href="{{ route('cart.show') }}">Giỏ hàng</a>
                        </nav>
                    </div>
                    <div>
                        <div class="footer-title">Liên hệ</div>
                        <p class="footer-contact">
                            <strong>Hotline:</strong> 0123456789
                        </p>
                    </div>
                </div>
                <div class="footer-bottom">
                    &copy; {{ date('Y') }} Flower Corner. Tất cả quyền được bảo lưu.
                </div>
            </div>
        </footer>
    </div>

    <div id="chat-widget">
        <button type="button" class="chat-toggle" aria-label="Mở chat">💬</button>
        <div class="chat-panel">
            <div class="chat-panel-header">
                Tư vấn mua hoa
                <button type="button" class="chat-close" aria-label="Đóng">×</button>
            </div>
            <div class="chat-messages" id="chat-messages"></div>
            <form class="chat-form" id="chat-form">
                <input type="text" id="chat-input" placeholder="Nhập câu hỏi..." autocomplete="off">
                <button type="submit">Gửi</button>
            </form>
        </div>
    </div>

    <script>
(function () {
    var widget = document.getElementById('chat-widget');
    var toggle = widget.querySelector('.chat-toggle');
    var panel = widget.querySelector('.chat-panel');
    var closeBtn = widget.querySelector('.chat-close');
    var form = document.getElementById('chat-form');
    var input = document.getElementById('chat-input');
    var messages = document.getElementById('chat-messages');

    function appendBotMsg(text) {
        var div = document.createElement('div');
        div.className = 'chat-msg bot';
        div.textContent = text;
        messages.appendChild(div);
        messages.scrollTop = messages.scrollHeight;
    }

    function appendUserMsg(text) {
        var div = document.createElement('div');
        div.className = 'chat-msg user';
        div.textContent = text;
        messages.appendChild(div);
        messages.scrollTop = messages.scrollHeight;
    }

    function setTyping(show) {
        var el = document.getElementById('chat-typing');
        if (show) {
            if (!el) {
                el = document.createElement('div');
                el.id = 'chat-typing';
                el.className = 'chat-typing';
                el.textContent = 'Đang trả lời...';
                messages.appendChild(el);
            }
            messages.scrollTop = messages.scrollHeight;
        } else if (el) {
            el.remove();
        }
    }

    toggle.addEventListener('click', function () {
        widget.classList.add('open');
        if (messages.children.length === 0) {
            appendBotMsg('Chào bạn! Mình là trợ lý của Flower Corner. Bạn cần tư vấn gì về hoa, đặt hàng hay giao hàng?');
        }
        input.focus();
    });

    closeBtn.addEventListener('click', function () {
        widget.classList.remove('open');
    });

    form.addEventListener('submit', function (e) {
        e.preventDefault();
        var text = (input.value || '').trim();
        if (!text) return;
        input.value = '';
        appendUserMsg(text);
        setTyping(true);

        var token = document.querySelector('meta[name="csrf-token"]');
        fetch('{{ route("chat.send") }}', {
            method: 'POST',
            headers: {
                'Content-Type': 'application/json',
                'Accept': 'application/json',
                'X-CSRF-TOKEN': token ? token.getAttribute('content') : ''
            },
            body: JSON.stringify({ message: text })
        })
        .then(function (r) {
            return r.json().then(function (data) {
                if (!r.ok) throw new Error(data.reply || data.message || 'Lỗi ' + r.status);
                return data;
            }).catch(function (e) {
                if (e.message) throw e;
                throw new Error('Không đọc được phản hồi.');
            });
        })
        .then(function (data) {
            setTyping(false);
            appendBotMsg(data.reply || 'Xin lỗi, thử lại sau nhé.');
        })
        .catch(function (err) {
            setTyping(false);
            appendBotMsg(err && err.message ? err.message : 'Đang bận, bạn thử lại sau hoặc gọi Hotline 1900 1345 nhé!');
        });
    });
})();
    </script>
</body>
</html>

