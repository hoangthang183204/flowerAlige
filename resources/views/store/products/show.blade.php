@extends('layouts.store')

@section('title', $product->name)

@section('content')
    <div style="display:flex;flex-wrap:wrap;gap:2rem;align-items:flex-start;">
        <section style="flex:1 1 260px;min-width:0;">
            <div style="border-radius:1rem;overflow:hidden;background:#fff2f2;display:flex;align-items:center;justify-content:center;min-height:260px;margin-bottom:1rem;">
                @if($product->image_path)
                    <img src="{{ asset($product->image_path) }}" alt="{{ $product->name }}" style="max-width:100%;max-height:100%;object-fit:cover;">
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
                {{ number_format($product->price, 0, ',', '.') }} đ
            </div>

            @if($product->short_description)
                <p style="font-size:.95rem;margin-bottom:.75rem;">{{ $product->short_description }}</p>
            @endif

            @if($product->description)
                <div style="font-size:.9rem;color:#444;margin-bottom:1rem;white-space:pre-line;">
                    {{ $product->description }}
                </div>
            @endif

            @auth
                <form action="{{ route('cart.add', $product->id) }}" method="POST" style="margin-bottom:1.5rem;">
                    @csrf
                    <div style="display:flex;align-items:center;gap:.5rem;margin-bottom:.75rem;max-width:220px;">
                        <label for="quantity" style="font-size:.9rem;">Số lượng</label>
                        <input
                            type="number"
                            id="quantity"
                            name="quantity"
                            value="1"
                            min="1"
                            style="width:80px;padding:.35rem .5rem;border-radius:.35rem;border:1px solid #e3e3e0;font-size:.9rem;"
                        >
                    </div>
                    <button type="submit" class="btn btn-primary">
                        Thêm vào giỏ hàng
                    </button>
                </form>
            @else
                <p style="margin-bottom:1rem;font-size:.9rem;color:#706f6c;">Bạn cần đăng nhập để thêm sản phẩm vào giỏ và mua hàng.</p>
                <a href="{{ route('login') }}" class="btn btn-primary">Đăng nhập để thêm vào giỏ hàng</a>
            @endauth

            @if($relatedProducts->count())
                <div style="margin-top:1.5rem;">
                    <h2 style="font-size:1rem;font-weight:600;margin-bottom:.5rem;">Sản phẩm liên quan</h2>
                    <div style="display:grid;grid-template-columns:repeat(auto-fill,minmax(150px,1fr));gap:.75rem;">
                        @foreach($relatedProducts as $related)
                            <a href="{{ route('products.show', $related->slug) }}" style="border-radius:.6rem;border:1px solid #e3e3e0;padding:.5rem;display:block;background:#fff;">
                                <div style="border-radius:.5rem;overflow:hidden;background:#fff2f2;height:110px;display:flex;align-items:center;justify-content:center;margin-bottom:.4rem;">
                                    @if($related->image_path)
                                        <img src="{{ asset($related->image_path) }}" alt="{{ $related->name }}" style="max-width:100%;max-height:100%;object-fit:cover;">
                                    @else
                                        <span style="font-size:1.5rem;">🌺</span>
                                    @endif
                                </div>
                                <div style="font-size:.85rem;font-weight:500;margin-bottom:.1rem;white-space:nowrap;overflow:hidden;text-overflow:ellipsis;">
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
@endsection

