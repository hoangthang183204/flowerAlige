@extends('layouts.store')

@section('title', 'Cửa hàng hoa - Trang chủ')

@section('content')
    {{-- Banner slider từ admin --}}
    @if(isset($banners) && $banners->count())
        <section id="home-banner-slider" style="margin-bottom:1.5rem;border-radius:1rem;overflow:hidden;position:relative;background:#fff;">
            <div data-slider-track style="position:relative;height:320px;">
                @foreach($banners as $index => $banner)
                    <div data-slide style="position:absolute;inset:0;opacity:{{ $index === 0 ? '1' : '0' }};transition:opacity .45s ease;">
                        @if($banner->link_url)
                            <a href="{{ $banner->link_url }}" target="_blank" rel="noopener" style="display:block;width:100%;height:100%;">
                                <img src="{{ asset($banner->image_path) }}" alt="{{ $banner->title }}" style="width:100%;height:100%;object-fit:cover;">
                            </a>
                        @else
                            <img src="{{ asset($banner->image_path) }}" alt="{{ $banner->title }}" style="width:100%;height:100%;object-fit:cover;">
                        @endif
                    </div>
                @endforeach
            </div>

            @if($banners->count() > 1)
                <button type="button" data-slider-prev aria-label="Banner trước" style="position:absolute;left:.75rem;top:50%;transform:translateY(-50%);width:34px;height:34px;border:none;border-radius:999px;background:rgba(0,0,0,.35);color:#fff;cursor:pointer;font-size:1.1rem;">‹</button>
                <button type="button" data-slider-next aria-label="Banner tiếp theo" style="position:absolute;right:.75rem;top:50%;transform:translateY(-50%);width:34px;height:34px;border:none;border-radius:999px;background:rgba(0,0,0,.35);color:#fff;cursor:pointer;font-size:1.1rem;">›</button>
                <div data-slider-dots style="position:absolute;left:50%;bottom:.75rem;transform:translateX(-50%);display:flex;gap:.35rem;">
                    @foreach($banners as $index => $banner)
                        <button type="button" data-dot="{{ $index }}" aria-label="Đến banner {{ $index + 1 }}" style="width:8px;height:8px;border-radius:999px;border:none;cursor:pointer;background:{{ $index === 0 ? '#fff' : 'rgba(255,255,255,.55)' }};"></button>
                    @endforeach
                </div>
            @endif
        </section>
    @endif

    {{-- Banner slider lăng hoa --}}
    @if(isset($condolenceBanners) && $condolenceBanners->count())
        <section id="condolence-banner-slider" style="margin-bottom:1.5rem;border-radius:1rem;overflow:hidden;position:relative;background:linear-gradient(135deg,#2c1810,#4a2c20);">
            <div data-condolence-track style="position:relative;height:380px;">
                @foreach($condolenceBanners as $index => $banner)
                    <div data-condolence-slide style="position:absolute;inset:0;opacity:{{ $index === 0 ? '1' : '0' }};transition:opacity .5s ease;">
                        <div style="display:flex;align-items:center;justify-content:space-between;height:100%;padding:2rem 3rem;">
                            <div style="flex:1;z-index:2;">
                                <div style="font-size:.85rem;font-weight:600;color:#ffd966;text-transform:uppercase;letter-spacing:2px;margin-bottom:1rem;">
                                    {{ $banner->tag ?? 'LẴNG HOA ĐẸP' }}
                                </div>
                                <div style="font-size:2.5rem;font-weight:700;color:#fff;margin-bottom:.5rem;line-height:1.2;">
                                    {{ $banner->title ?? 'GIA YÊU CHỈ TỪ 399K' }}
                                </div>
                                <div style="font-size:1rem;color:rgba(255,255,255,0.9);margin:1rem 0 1.5rem;font-style:italic;">
                                    {{ $banner->message ?? 'Trọn Tâm, Tận Ý' }}
                                </div>
                                <div style="display:flex;gap:1rem;">
                                    @if($banner->button1_url)
                                        <a href="{{ $banner->button1_url }}" style="display:inline-block;padding:.75rem 1.8rem;border:2px solid #fff;color:#fff;text-decoration:none;border-radius:2rem;font-weight:600;transition:all .3s ease;background:transparent;">
                                            {{ $banner->button1_text ?? 'XEM NGAY' }}
                                        </a>
                                    @endif
                                    @if($banner->button2_url)
                                        <a href="{{ $banner->button2_url }}" style="display:inline-block;padding:.75rem 1.8rem;background:#ffd966;color:#2c1810;text-decoration:none;border-radius:2rem;font-weight:600;transition:all .3s ease;">
                                            {{ $banner->button2_text ?? 'ĐẶT NGAY' }}
                                        </a>
                                    @endif
                                </div>
                            </div>
                            @if($banner->image_path)
                                <div style="flex:0.8;text-align:center;z-index:2;">
                                    <img src="{{ asset($banner->image_path) }}" alt="{{ $banner->title }}" style="max-width:100%;max-height:280px;object-fit:contain;filter:drop-shadow(0 4px 12px rgba(0,0,0,0.3));">
                                </div>
                            @endif
                        </div>
                    </div>
                @endforeach
            </div>

            @if($condolenceBanners->count() > 1)
                <button type="button" data-condolence-prev aria-label="Banner trước" style="position:absolute;left:.75rem;top:50%;transform:translateY(-50%);width:40px;height:40px;border:none;border-radius:999px;background:rgba(255,255,255,0.3);backdrop-filter:blur(4px);color:#fff;cursor:pointer;font-size:1.5rem;font-weight:bold;z-index:10;">‹</button>
                <button type="button" data-condolence-next aria-label="Banner tiếp theo" style="position:absolute;right:.75rem;top:50%;transform:translateY(-50%);width:40px;height:40px;border:none;border-radius:999px;background:rgba(255,255,255,0.3);backdrop-filter:blur(4px);color:#fff;cursor:pointer;font-size:1.5rem;font-weight:bold;z-index:10;">›</button>
                <div data-condolence-dots style="position:absolute;left:50%;bottom:1.5rem;transform:translateX(-50%);display:flex;gap:.75rem;z-index:10;">
                    @foreach($condolenceBanners as $index => $banner)
                        <button type="button" data-condolence-dot="{{ $index }}" aria-label="Đến banner {{ $index + 1 }}" style="width:10px;height:10px;border-radius:999px;border:none;cursor:pointer;background:{{ $index === 0 ? '#ffd966' : 'rgba(255,255,255,0.5)' }};transition:all .3s ease;"></button>
                    @endforeach
                </div>
            @endif
        </section>
    @endif

    {{-- Hero banner --}}
    <section style="display:grid;grid-template-columns:minmax(0,1.4fr) minmax(0,1fr);gap:2rem;align-items:center;margin-bottom:2rem;">
        <div>
            <h1 class="page-title" style="font-size:2rem;margin-bottom:.5rem;">
                Shop hoa tươi giao nhanh trong 90 phút
            </h1>
            <p class="page-subtitle" style="max-width:520px;">
                Đặt hoa sinh nhật, khai trương, hoa tình yêu, hoa chia buồn... với hàng trăm mẫu hoa được thiết kế sẵn.
                Giao nhanh tại TP.HCM, Hà Nội và nhiều tỉnh thành.
            </p>
            <form action="{{ route('products.index') }}" method="GET" class="search-form" style="margin-bottom:1.25rem;">
                <div class="search-row">
                    <input
                        type="text"
                        name="q"
                        placeholder="Tìm bó hoa bạn muốn..."
                        class="search-input"
                    >
                    <button type="submit" class="btn btn-primary btn-icon search-btn">
                        <span>🔍</span><span>Tìm kiếm</span>
                    </button>
                </div>
            </form>
            @if($categories->count())
                <div style="font-size:.85rem;color:#706f6c;margin-bottom:.35rem;">Chọn nhanh theo loại hoa:</div>
                <div style="display:flex;flex-wrap:wrap;gap:.5rem;">
                    @foreach($categories->take(6) as $category)
                        <a href="{{ route('products.index', ['category' => $category->slug]) }}" class="btn btn-outline" style="border-style:solid;padding:.25rem .6rem;font-size:.8rem;">
                            {{ $category->name }}
                        </a>
                    @endforeach
                </div>
            @endif
        </div>
        <div style="border-radius:1.25rem;background:linear-gradient(135deg,#fff2f2,#ffe9d9);padding:1.5rem;position:relative;overflow:hidden;">
            <div style="position:absolute;inset:auto -2rem -3rem auto;font-size:4rem;opacity:.15;">
                🌸
            </div>
            <div style="font-size:.8rem;font-weight:600;color:#f53003;text-transform:uppercase;letter-spacing:.08em;margin-bottom:.4rem;">
                Ưu đãi hôm nay
            </div>
            <div style="font-size:1.2rem;font-weight:600;margin-bottom:.25rem;">Giảm đến 30% cho đơn hoa online</div>
            <p style="font-size:.9rem;color:#555;margin-bottom:.75rem;max-width:260px;">
                Miễn phí thiệp, giao nhanh nội thành, chụp hình hoa gửi duyệt trước khi giao.
            </p>
            <a href="{{ route('products.index') }}" class="btn btn-primary">
                Xem mẫu hoa khuyến mãi
            </a>
        </div>
    </section>

    {{-- Sections giống FlowerCorner --}}
    <section style="margin-bottom:2rem;">
        <div style="display:flex;align-items:center;justify-content:space-between;margin-bottom:.75rem;">
            <h2 style="font-size:1.05rem;font-weight:600;text-transform:uppercase;">Đang giảm giá</h2>
        </div>
        @if($featuredProducts->count())
            <div style="display:grid;grid-template-columns:repeat(auto-fill,minmax(180px,1fr));gap:1rem;">
                @foreach($featuredProducts as $product)
                    <a href="{{ route('products.show', $product->slug) }}" style="border-radius:.9rem;border:1px solid #e3e3e0;padding:.75rem;display:block;background:#fff;">
                        <div style="border-radius:.75rem;overflow:hidden;background:#fff2f2;height:170px;display:flex;align-items:center;justify-content:center;margin-bottom:.55rem;">
                            @if($product->image_path)
                                <img src="{{ asset($product->image_path) }}" alt="{{ $product->name }}" style="max-width:100%;max-height:100%;object-fit:cover;">
                            @else
                                <span style="font-size:2rem;">🌷</span>
                            @endif
                        </div>
                        <div style="font-size:.9rem;font-weight:500;margin-bottom:.15rem;white-space:nowrap;overflow:hidden;text-overflow:ellipsis;">
                            {{ $product->name }}
                        </div>
                        <div style="font-size:.8rem;color:#706f6c;margin-bottom:.15rem;">
                            {{ optional($product->category)->name }}
                        </div>
                        <div style="font-weight:600;color:#f53003;">
                            {{ number_format($product->price, 0, ',', '.') }} đ
                        </div>
                    </a>
                @endforeach
            </div>
        @else
            <p style="font-size:.9rem;color:#706f6c;">Chưa có sản phẩm khuyến mãi. Hãy đánh dấu một số sản phẩm là "nổi bật" trong trang quản trị.</p>
        @endif
    </section>

    <section style="margin-bottom:2rem;">
        <div style="display:flex;align-items:center;justify-content:space-between;margin-bottom:.75rem;">
            <h2 style="font-size:1.05rem;font-weight:600;text-transform:uppercase;">Đặt nhiều nhất</h2>
        </div>
        @if($popularProducts->count())
            <div style="display:grid;grid-template-columns:repeat(auto-fill,minmax(180px,1fr));gap:1rem;">
                @foreach($popularProducts as $product)
                    <a href="{{ route('products.show', $product->slug) }}" style="border-radius:.9rem;border:1px solid #e3e3e0;padding:.75rem;display:block;background:#fff;">
                        <div style="border-radius:.75rem;overflow:hidden;background:#fff2f2;height:170px;display:flex;align-items:center;justify-content:center;margin-bottom:.55rem;">
                            @if($product->image_path)
                                <img src="{{ asset($product->image_path) }}" alt="{{ $product->name }}" style="max-width:100%;max-height:100%;object-fit:cover;">
                            @else
                                <span style="font-size:2rem;">💐</span>
                            @endif
                        </div>
                        <div style="font-size:.9rem;font-weight:500;margin-bottom:.15rem;white-space:nowrap;overflow:hidden;text-overflow:ellipsis;">
                            {{ $product->name }}
                        </div>
                        <div style="font-size:.8rem;color:#706f6c;margin-bottom:.15rem;">
                            {{ optional($product->category)->name }}
                        </div>
                        <div style="font-weight:600;color:#f53003;">
                            {{ number_format($product->price, 0, ',', '.') }} đ
                        </div>
                    </a>
                @endforeach
            </div>
        @else
            <p style="font-size:.9rem;color:#706f6c;">Chưa có đủ dữ liệu đơn hàng để thống kê sản phẩm bán chạy.</p>
        @endif
    </section>

    <section style="margin-bottom:2rem;">
        <div style="display:flex;align-items:center;justify-content:space-between;margin-bottom:.75rem;">
            <h2 style="font-size:1.05rem;font-weight:600;text-transform:uppercase;">Sản phẩm mới</h2>
        </div>
        @if($newProducts->count())
            <div style="display:grid;grid-template-columns:repeat(auto-fill,minmax(180px,1fr));gap:1rem;">
                @foreach($newProducts as $product)
                    <a href="{{ route('products.show', $product->slug) }}" style="border-radius:.9rem;border:1px solid #e3e3e0;padding:.75rem;display:block;background:#fff;">
                        <div style="border-radius:.75rem;overflow:hidden;background:#fff2f2;height:170px;display:flex;align-items:center;justify-content:center;margin-bottom:.55rem;">
                            @if($product->image_path)
                                <img src="{{ asset($product->image_path) }}" alt="{{ $product->name }}" style="max-width:100%;max-height:100%;object-fit:cover;">
                            @else
                                <span style="font-size:2rem;">🌹</span>
                            @endif
                        </div>
                        <div style="font-size:.9rem;font-weight:500;margin-bottom:.15rem;white-space:nowrap;overflow:hidden;text-overflow:ellipsis;">
                            {{ $product->name }}
                        </div>
                        <div style="font-size:.8rem;color:#706f6c;margin-bottom:.15rem;">
                            {{ optional($product->category)->name }}
                        </div>
                        <div style="font-weight:600;color:#f53003;">
                            {{ number_format($product->price, 0, ',', '.') }} đ
                        </div>
                    </a>
                @endforeach
            </div>
        @else
            <p style="font-size:.9rem;color:#706f6c;">Chưa có sản phẩm nào. Hãy thêm sản phẩm trong trang quản trị.</p>
        @endif
    </section>
@endsection

{{-- JavaScript cho banner slider chính --}}
@if(isset($banners) && $banners->count() > 1)
    <script>
        (function () {
            var root = document.getElementById('home-banner-slider');
            if (!root) return;

            var slides = Array.from(root.querySelectorAll('[data-slide]'));
            var dots = Array.from(root.querySelectorAll('[data-dot]'));
            var prevBtn = root.querySelector('[data-slider-prev]');
            var nextBtn = root.querySelector('[data-slider-next]');
            var index = 0;
            var timer = null;
            var delay = 4000;

            function render() {
                slides.forEach(function (slide, i) {
                    slide.style.opacity = i === index ? '1' : '0';
                    slide.style.pointerEvents = i === index ? 'auto' : 'none';
                });
                dots.forEach(function (dot, i) {
                    dot.style.background = i === index ? '#fff' : 'rgba(255,255,255,.55)';
                });
            }

            function goTo(nextIndex) {
                var total = slides.length;
                index = (nextIndex + total) % total;
                render();
            }

            function next() { goTo(index + 1); }
            function prev() { goTo(index - 1); }

            function startAuto() {
                stopAuto();
                timer = setInterval(next, delay);
            }

            function stopAuto() {
                if (timer) clearInterval(timer);
                timer = null;
            }

            if (nextBtn) nextBtn.addEventListener('click', function () { next(); startAuto(); });
            if (prevBtn) prevBtn.addEventListener('click', function () { prev(); startAuto(); });
            dots.forEach(function (dot) {
                dot.addEventListener('click', function () {
                    goTo(parseInt(dot.getAttribute('data-dot'), 10) || 0);
                    startAuto();
                });
            });

            root.addEventListener('mouseenter', stopAuto);
            root.addEventListener('mouseleave', startAuto);

            render();
            startAuto();
        })();
    </script>
@endif

{{-- JavaScript cho banner slider lăng hoa --}}
@if(isset($condolenceBanners) && $condolenceBanners->count() > 1)
    <script>
        (function () {
            var root = document.getElementById('condolence-banner-slider');
            if (!root) return;

            var slides = Array.from(root.querySelectorAll('[data-condolence-slide]'));
            var dots = Array.from(root.querySelectorAll('[data-condolence-dot]'));
            var prevBtn = root.querySelector('[data-condolence-prev]');
            var nextBtn = root.querySelector('[data-condolence-next]');
            var index = 0;
            var timer = null;
            var delay = 4000;

            function render() {
                slides.forEach(function (slide, i) {
                    slide.style.opacity = i === index ? '1' : '0';
                    slide.style.pointerEvents = i === index ? 'auto' : 'none';
                });
                dots.forEach(function (dot, i) {
                    dot.style.background = i === index ? '#ffd966' : 'rgba(255,255,255,0.5)';
                });
            }

            function goTo(nextIndex) {
                var total = slides.length;
                index = (nextIndex + total) % total;
                render();
            }

            function next() { goTo(index + 1); }
            function prev() { goTo(index - 1); }

            function startAuto() {
                stopAuto();
                timer = setInterval(next, delay);
            }

            function stopAuto() {
                if (timer) clearInterval(timer);
                timer = null;
            }

            if (nextBtn) nextBtn.addEventListener('click', function () { next(); startAuto(); });
            if (prevBtn) prevBtn.addEventListener('click', function () { prev(); startAuto(); });
            dots.forEach(function (dot) {
                dot.addEventListener('click', function () {
                    goTo(parseInt(dot.getAttribute('data-condolence-dot'), 10) || 0);
                    startAuto();
                });
            });

            root.addEventListener('mouseenter', stopAuto);
            root.addEventListener('mouseleave', startAuto);

            render();
            startAuto();
        })();
    </script>
@endif