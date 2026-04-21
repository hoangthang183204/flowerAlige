@extends('layouts.store')

@section('title', 'Cửa hàng hoa - Trang chủ')

@section('content')
    {{-- Banner slider từ admin --}}
    @if (isset($banners) && $banners->count())
        <section id="home-banner-slider"
            style="margin-bottom:1.5rem;border-radius:1rem;overflow:hidden;position:relative;background:#fff;">
            <div style="position:relative;height:320px;">
                @foreach ($banners as $index => $banner)
                    <div class="banner-slide" data-index="{{ $index }}"
                        style="position:absolute;top:0;left:0;width:100%;height:100%;opacity:{{ $index === 0 ? '1' : '0' }};transition:opacity .45s ease;z-index:{{ $index === 0 ? '1' : '0' }};">
                        @php
                            $imgSrc = preg_match('/^https?:\/\//', $banner->image_path)
                                ? $banner->image_path
                                : asset($banner->image_path);
                        @endphp
                        @if ($banner->link_url)
                            <a href="{{ $banner->link_url }}" target="_blank" rel="noopener"
                                style="display:block;width:100%;height:100%;">
                                <img src="{{ $imgSrc }}" alt="{{ $banner->title }}"
                                    style="width:100%;height:100%;object-fit:cover;">
                            </a>
                        @else
                            <img src="{{ $imgSrc }}" alt="{{ $banner->title }}"
                                style="width:100%;height:100%;object-fit:cover;">
                        @endif
                    </div>
                @endforeach
            </div>

            @if ($banners->count() > 1)
                <button type="button" id="slider-prev"
                    style="position:absolute;left:.75rem;top:50%;transform:translateY(-50%);width:34px;height:34px;border:none;border-radius:999px;background:rgba(0,0,0,.35);color:#fff;cursor:pointer;font-size:1.1rem;z-index:20;">‹</button>
                <button type="button" id="slider-next"
                    style="position:absolute;right:.75rem;top:50%;transform:translateY(-50%);width:34px;height:34px;border:none;border-radius:999px;background:rgba(0,0,0,.35);color:#fff;cursor:pointer;font-size:1.1rem;z-index:20;">›</button>
                <div id="slider-dots"
                    style="position:absolute;left:50%;bottom:.75rem;transform:translateX(-50%);display:flex;gap:.35rem;z-index:20;">
                    @foreach ($banners as $index => $banner)
                        <button type="button" data-dot-index="{{ $index }}"
                            style="width:8px;height:8px;border-radius:999px;border:none;cursor:pointer;background:{{ $index === 0 ? '#fff' : 'rgba(255,255,255,.55)' }};transition:all .3s ease;"></button>
                    @endforeach
                </div>
            @endif
        </section>
    @endif

    {{-- Banner slider lăng hoa --}}
    @if (isset($condolenceBanners) && $condolenceBanners->count())
        <section id="condolence-banner-slider"
            style="margin-bottom:1.5rem;border-radius:1rem;overflow:hidden;position:relative;background:linear-gradient(135deg,#2c1810,#4a2c20);">
            <div style="position:relative;height:380px;">
                @foreach ($condolenceBanners as $index => $banner)
                    <div class="condolence-slide" data-condolence-index="{{ $index }}"
                        style="position:absolute;top:0;left:0;width:100%;height:100%;opacity:{{ $index === 0 ? '1' : '0' }};transition:opacity .5s ease;z-index:{{ $index === 0 ? '1' : '0' }};">
                        <div
                            style="display:flex;align-items:center;justify-content:space-between;height:100%;padding:2rem 3rem;">
                            <div style="flex:1;z-index:2;">
                                <div
                                    style="font-size:.85rem;font-weight:600;color:#ffd966;text-transform:uppercase;letter-spacing:2px;margin-bottom:1rem;">
                                    {{ $banner->tag ?? 'LẴNG HOA ĐẸP' }}
                                </div>
                                <div
                                    style="font-size:2.5rem;font-weight:700;color:#fff;margin-bottom:.5rem;line-height:1.2;">
                                    {{ $banner->title ?? 'GIA YÊU CHỈ TỪ 399K' }}
                                </div>
                                <div
                                    style="font-size:1rem;color:rgba(255,255,255,0.9);margin:1rem 0 1.5rem;font-style:italic;">
                                    {{ $banner->message ?? 'Trọn Tâm, Tận Ý' }}
                                </div>
                                <div style="display:flex;gap:1rem;">
                                    @if ($banner->button1_url)
                                        <a href="{{ $banner->button1_url }}"
                                            style="display:inline-block;padding:.75rem 1.8rem;border:2px solid #fff;color:#fff;text-decoration:none;border-radius:2rem;font-weight:600;transition:all .3s ease;background:transparent;">
                                            {{ $banner->button1_text ?? 'XEM NGAY' }}
                                        </a>
                                    @endif
                                    @if ($banner->button2_url)
                                        <a href="{{ $banner->button2_url }}"
                                            style="display:inline-block;padding:.75rem 1.8rem;background:#ffd966;color:#2c1810;text-decoration:none;border-radius:2rem;font-weight:600;transition:all .3s ease;">
                                            {{ $banner->button2_text ?? 'ĐẶT NGAY' }}
                                        </a>
                                    @endif
                                </div>
                            </div>
                            @if ($banner->image_path)
                                @php
                                    $imgSrc2 = preg_match('/^https?:\/\//', $banner->image_path)
                                        ? $banner->image_path
                                        : asset($banner->image_path);
                                @endphp
                                <div style="flex:0.8;text-align:center;z-index:2;">
                                    <img src="{{ $imgSrc2 }}" alt="{{ $banner->title }}"
                                        style="max-width:100%;max-height:280px;object-fit:contain;filter:drop-shadow(0 4px 12px rgba(0,0,0,0.3));">
                                </div>
                            @endif
                        </div>
                    </div>
                @endforeach
            </div>

            @if ($condolenceBanners->count() > 1)
                <button type="button" id="condolence-prev"
                    style="position:absolute;left:.75rem;top:50%;transform:translateY(-50%);width:40px;height:40px;border:none;border-radius:999px;background:rgba(255,255,255,0.3);backdrop-filter:blur(4px);color:#fff;cursor:pointer;font-size:1.5rem;font-weight:bold;z-index:20;">‹</button>
                <button type="button" id="condolence-next"
                    style="position:absolute;right:.75rem;top:50%;transform:translateY(-50%);width:40px;height:40px;border:none;border-radius:999px;background:rgba(255,255,255,0.3);backdrop-filter:blur(4px);color:#fff;cursor:pointer;font-size:1.5rem;font-weight:bold;z-index:20;">›</button>
                <div id="condolence-dots"
                    style="position:absolute;left:50%;bottom:1.5rem;transform:translateX(-50%);display:flex;gap:.75rem;z-index:20;">
                    @foreach ($condolenceBanners as $index => $banner)
                        <button type="button" data-condolence-dot-index="{{ $index }}"
                            style="width:10px;height:10px;border-radius:999px;border:none;cursor:pointer;background:{{ $index === 0 ? '#ffd966' : 'rgba(255,255,255,0.5)' }};transition:all .3s ease;"></button>
                    @endforeach
                </div>
            @endif
        </section>
    @endif

    {{-- Hero banner --}}
    <section
        style="display:grid;grid-template-columns:minmax(0,1.4fr) minmax(0,1fr);gap:2rem;align-items:center;margin-bottom:2rem;">
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
                    <input type="text" name="q" placeholder="Tìm bó hoa bạn muốn..." class="search-input">
                    <button type="submit" class="btn btn-primary btn-icon search-btn">
                        <span>🔍</span><span>Tìm kiếm</span>
                    </button>
                </div>
            </form>
            @if ($categories->count())
                <div style="font-size:.85rem;color:#706f6c;margin-bottom:.35rem;">Chọn nhanh theo loại hoa:</div>
                <div style="display:flex;flex-wrap:wrap;gap:.5rem;">
                    @foreach ($categories->take(6) as $category)
                        <a href="{{ route('products.index', ['category' => $category->slug]) }}" class="btn btn-outline"
                            style="border-style:solid;padding:.25rem .6rem;font-size:.8rem;">
                            {{ $category->name }}
                        </a>
                    @endforeach
                </div>
            @endif
        </div>
        <div
            style="border-radius:1.25rem;background:linear-gradient(135deg,#fff2f2,#ffe9d9);padding:1.5rem;position:relative;overflow:hidden;">
            <div style="position:absolute;inset:auto -2rem -3rem auto;font-size:4rem;opacity:.15;">
                🌸
            </div>
            <div
                style="font-size:.8rem;font-weight:600;color:#f53003;text-transform:uppercase;letter-spacing:.08em;margin-bottom:.4rem;">
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
        @if ($featuredProducts->count())
            <div style="display:grid;grid-template-columns:repeat(auto-fill,minmax(180px,1fr));gap:1rem;">
                @foreach ($featuredProducts as $product)
                    <a href="{{ route('products.show', $product->slug) }}"
                        style="border-radius:.9rem;border:1px solid #e3e3e0;padding:.75rem;display:block;background:#fff;">
                        <div
                            style="border-radius:.75rem;overflow:hidden;background:#fff2f2;height:170px;display:flex;align-items:center;justify-content:center;margin-bottom:.55rem;">
                            @if ($product->image_path)
                                <img src="{{ asset($product->image_path) }}" alt="{{ $product->name }}"
                                    style="max-width:100%;max-height:100%;object-fit:cover;">
                            @else
                                <span style="font-size:2rem;">🌷</span>
                            @endif
                        </div>
                        <div
                            style="font-size:.9rem;font-weight:500;margin-bottom:.15rem;white-space:nowrap;overflow:hidden;text-overflow:ellipsis;">
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
            <p style="font-size:.9rem;color:#706f6c;">Chưa có sản phẩm khuyến mãi. Hãy đánh dấu một số sản phẩm là "nổi
                bật"
                trong trang quản trị.</p>
        @endif
    </section>

    <section style="margin-bottom:2rem;">
        <div style="display:flex;align-items:center;justify-content:space-between;margin-bottom:.75rem;">
            <h2 style="font-size:1.05rem;font-weight:600;text-transform:uppercase;">Đặt nhiều nhất</h2>
        </div>
        @if ($popularProducts->count())
            <div style="display:grid;grid-template-columns:repeat(auto-fill,minmax(180px,1fr));gap:1rem;">
                @foreach ($popularProducts as $product)
                    <a href="{{ route('products.show', $product->slug) }}"
                        style="border-radius:.9rem;border:1px solid #e3e3e0;padding:.75rem;display:block;background:#fff;">
                        <div
                            style="border-radius:.75rem;overflow:hidden;background:#fff2f2;height:170px;display:flex;align-items:center;justify-content:center;margin-bottom:.55rem;">
                            @if ($product->image_path)
                                <img src="{{ asset($product->image_path) }}" alt="{{ $product->name }}"
                                    style="max-width:100%;max-height:100%;object-fit:cover;">
                            @else
                                <span style="font-size:2rem;">💐</span>
                            @endif
                        </div>
                        <div
                            style="font-size:.9rem;font-weight:500;margin-bottom:.15rem;white-space:nowrap;overflow:hidden;text-overflow:ellipsis;">
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
        @if ($newProducts->count())
            <div style="display:grid;grid-template-columns:repeat(auto-fill,minmax(180px,1fr));gap:1rem;">
                @foreach ($newProducts as $product)
                    <a href="{{ route('products.show', $product->slug) }}"
                        style="border-radius:.9rem;border:1px solid #e3e3e0;padding:.75rem;display:block;background:#fff;">
                        <div
                            style="border-radius:.75rem;overflow:hidden;background:#fff2f2;height:170px;display:flex;align-items:center;justify-content:center;margin-bottom:.55rem;">
                            @if ($product->image_path)
                                <img src="{{ asset($product->image_path) }}" alt="{{ $product->name }}"
                                    style="max-width:100%;max-height:100%;object-fit:cover;">
                            @else
                                <span style="font-size:2rem;">🌹</span>
                            @endif
                        </div>
                        <div
                            style="font-size:.9rem;font-weight:500;margin-bottom:.15rem;white-space:nowrap;overflow:hidden;text-overflow:ellipsis;">
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
@if (isset($banners) && $banners->count() > 1)
    <script>
        document.addEventListener('DOMContentLoaded', function() {
            // Banner slider
            var slides = document.querySelectorAll('#home-banner-slider .banner-slide');
            var prevBtn = document.getElementById('slider-prev');
            var nextBtn = document.getElementById('slider-next');
            var dots = document.querySelectorAll('#slider-dots button');
            var currentIndex = 0;
            var autoTimer = null;
            var delay = 4000;

            function showSlide(index) {
                if (index < 0) index = slides.length - 1;
                if (index >= slides.length) index = 0;

                slides.forEach(function(slide, i) {
                    slide.style.opacity = i === index ? '1' : '0';
                    slide.style.zIndex = i === index ? '1' : '0';
                });

                dots.forEach(function(dot, i) {
                    dot.style.background = i === index ? '#fff' : 'rgba(255,255,255,.55)';
                });

                currentIndex = index;
            }

            function nextSlide() {
                showSlide(currentIndex + 1);
                resetAutoTimer();
            }

            function prevSlide() {
                showSlide(currentIndex - 1);
                resetAutoTimer();
            }

            function resetAutoTimer() {
                if (autoTimer) {
                    clearInterval(autoTimer);
                }
                autoTimer = setInterval(function() {
                    nextSlide();
                }, delay);
            }

            function stopAutoTimer() {
                if (autoTimer) {
                    clearInterval(autoTimer);
                    autoTimer = null;
                }
            }

            // Event listeners
            if (nextBtn) {
                nextBtn.addEventListener('click', function(e) {
                    e.preventDefault();
                    nextSlide();
                });
            }

            if (prevBtn) {
                prevBtn.addEventListener('click', function(e) {
                    e.preventDefault();
                    prevSlide();
                });
            }

            dots.forEach(function(dot, idx) {
                dot.addEventListener('click', function(e) {
                    e.preventDefault();
                    showSlide(idx);
                    resetAutoTimer();
                });
            });

            // Pause on hover
            var slider = document.getElementById('home-banner-slider');
            slider.addEventListener('mouseenter', stopAutoTimer);
            slider.addEventListener('mouseleave', resetAutoTimer);

            // Start auto play
            resetAutoTimer();
        });
    </script>
@endif

{{-- JavaScript cho banner slider lăng hoa --}}
@if (isset($condolenceBanners) && $condolenceBanners->count() > 1)
    <script>
        document.addEventListener('DOMContentLoaded', function() {
            // Condolence banner slider
            var slides = document.querySelectorAll('#condolence-banner-slider .condolence-slide');
            var prevBtn = document.getElementById('condolence-prev');
            var nextBtn = document.getElementById('condolence-next');
            var dots = document.querySelectorAll('#condolence-dots button');
            var currentIndex = 0;
            var autoTimer = null;
            var delay = 4000;

            function showSlide(index) {
                if (index < 0) index = slides.length - 1;
                if (index >= slides.length) index = 0;

                slides.forEach(function(slide, i) {
                    slide.style.opacity = i === index ? '1' : '0';
                    slide.style.zIndex = i === index ? '1' : '0';
                });

                dots.forEach(function(dot, i) {
                    dot.style.background = i === index ? '#ffd966' : 'rgba(255,255,255,0.5)';
                });

                currentIndex = index;
            }

            function nextSlide() {
                showSlide(currentIndex + 1);
                resetAutoTimer();
            }

            function prevSlide() {
                showSlide(currentIndex - 1);
                resetAutoTimer();
            }

            function resetAutoTimer() {
                if (autoTimer) {
                    clearInterval(autoTimer);
                }
                autoTimer = setInterval(function() {
                    nextSlide();
                }, delay);
            }

            function stopAutoTimer() {
                if (autoTimer) {
                    clearInterval(autoTimer);
                    autoTimer = null;
                }
            }

            // Event listeners
            if (nextBtn) {
                nextBtn.addEventListener('click', function(e) {
                    e.preventDefault();
                    nextSlide();
                });
            }

            if (prevBtn) {
                prevBtn.addEventListener('click', function(e) {
                    e.preventDefault();
                    prevSlide();
                });
            }

            dots.forEach(function(dot, idx) {
                dot.addEventListener('click', function(e) {
                    e.preventDefault();
                    showSlide(idx);
                    resetAutoTimer();
                });
            });

            // Pause on hover
            var slider = document.getElementById('condolence-banner-slider');
            slider.addEventListener('mouseenter', stopAutoTimer);
            slider.addEventListener('mouseleave', resetAutoTimer);

            // Start auto play
            resetAutoTimer();
        });
    </script>
@endif
