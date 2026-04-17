@extends('layouts.store')

@section('title', $product->name)

@section('content')
    <div style="display:flex;flex-wrap:wrap;gap:2rem;align-items:flex-start;">
        <section style="flex:1 1 260px;min-width:0;">
            <div
                style="border-radius:1rem;overflow:hidden;background:#fff2f2;display:flex;align-items:center;justify-content:center;min-height:260px;margin-bottom:1rem;">
                @if ($product->image_path)
                    <img src="{{ asset($product->image_path) }}" alt="{{ $product->name }}"
                        style="max-width:100%;max-height:100%;object-fit:cover;">
                @else
                    <span style="font-size:3rem;">💐</span>
                @endif
            </div>
        </section>

        <section style="flex:1 1 260px;min-width:0;">
            <h1 class="page-title">{{ $product->name }}</h1>
            <p class="page-subtitle" style="margin-bottom:.5rem;">
                {{ optional($product->category)->name }}
            </p>

            <div style="font-size:1.4rem;font-weight:600;color:#f53003;margin-bottom:.75rem;">
                <span id="product-price"
                    data-price="{{ $product->price }}">{{ number_format($product->price, 0, ',', '.') }} đ</span>
            </div>

            @if ($product->short_description)
                <p style="font-size:.95rem;margin-bottom:.75rem;">{{ $product->short_description }}</p>
            @endif

            @if ($product->description)
                <div style="font-size:.9rem;color:#444;margin-bottom:1rem;white-space:pre-line;">
                    {{ $product->description }}
                </div>
            @endif

            @auth
                <form action="{{ route('cart.add', $product->id) }}" method="POST" style="margin-bottom:1.5rem;">
                    @csrf
                    <div style="display:flex;align-items:center;gap:.5rem;margin-bottom:.75rem;max-width:220px;">
                        <label for="quantity" style="font-size:.9rem;">Số lượng</label>
                        <input type="number" id="quantity" name="quantity" value="1" min="1"
                            @if (isset($product->stock)) max="{{ $product->stock }}" @endif
                            style="width:80px;padding:.35rem .5rem;border-radius:.35rem;border:1px solid #e3e3e0;font-size:.9rem;">
                    </div>
                    <div id="stock-message" style="color:#b91c1c;font-size:.9rem;margin-bottom:.6rem;display:none;"></div>
                    <button type="submit" class="btn btn-primary">
                        Thêm vào giỏ hàng
                    </button>
                </form>
            @else
                <p style="margin-bottom:1rem;font-size:.9rem;color:#706f6c;">Bạn cần đăng nhập để thêm sản phẩm vào giỏ và mua
                    hàng.</p>
                <a href="{{ route('login') }}" class="btn btn-primary">Đăng nhập để thêm vào giỏ hàng</a>
            @endauth

            @if ($relatedProducts->count())
                <div style="margin-top:1.5rem;">
                    <h2 style="font-size:1rem;font-weight:600;margin-bottom:.5rem;">Sản phẩm liên quan</h2>
                    <div style="display:grid;grid-template-columns:repeat(auto-fill,minmax(150px,1fr));gap:.75rem;">
                        @foreach ($relatedProducts as $related)
                            <a href="{{ route('products.show', $related->slug) }}"
                                style="border-radius:.6rem;border:1px solid #e3e3e0;padding:.5rem;display:block;background:#fff;">
                                <div
                                    style="border-radius:.5rem;overflow:hidden;background:#fff2f2;height:110px;display:flex;align-items:center;justify-content:center;margin-bottom:.4rem;">
                                    @if ($related->image_path)
                                        <img src="{{ asset($related->image_path) }}" alt="{{ $related->name }}"
                                            style="max-width:100%;max-height:100%;object-fit:cover;">
                                    @else
                                        <span style="font-size:1.5rem;">🌺</span>
                                    @endif
                                </div>
                                <div
                                    style="font-size:.85rem;font-weight:500;margin-bottom:.1rem;white-space:nowrap;overflow:hidden;text-overflow:ellipsis;">
                                    {{ $related->name }}
                                </div>
                                <div style="font-size:.85rem;font-weight:600;color:#f53003;">
                                    {{ number_format($related->price, 0, ',', '.') }} đ
                                </div>
                            </a>
                        @endforeach
                    </div>
                </div>
            @endif
        </section>
    </div>
    @push('scripts')
        <script>
            (function() {
                const qtyInput = document.getElementById('quantity');
                const priceEl = document.getElementById('product-price');
                const stockMsg = document.getElementById('stock-message');
                const addButton = document.querySelector('form button[type="submit"]');
                if (!qtyInput || !priceEl) return;

                const unitPrice = parseFloat(priceEl.dataset.price) || 0;
                const stock = {{ json_encode($product->stock) }};

                function formatVND(amount) {
                    return amount.toLocaleString('vi-VN') + ' đ';
                }

                function update() {
                    let q = parseInt(qtyInput.value, 10);
                    if (isNaN(q) || q < 1) q = 1;
                    const total = unitPrice * q;
                    priceEl.textContent = formatVND(total);

                    if (stock !== null && stock >= 0) {
                        if (q > stock) {
                            stockMsg.style.display = 'block';
                            stockMsg.textContent = 'Không đủ hàng — chỉ còn ' + stock + ' sản phẩm.';
                            if (addButton) addButton.disabled = true;
                        } else {
                            stockMsg.style.display = 'none';
                            stockMsg.textContent = '';
                            if (addButton) addButton.disabled = false;
                        }
                    }
                }

                qtyInput.addEventListener('input', update);
                qtyInput.addEventListener('change', update);
                document.addEventListener('DOMContentLoaded', update);
            })();
        </script>
    @endpush
@endsection
