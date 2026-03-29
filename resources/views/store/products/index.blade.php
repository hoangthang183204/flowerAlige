@extends('layouts.store')

@section('title', 'Danh sách sản phẩm')

@section('content')
    <div style="display:flex;flex-wrap:wrap;gap:2rem;align-items:flex-start;">
        <aside class="filter-sidebar">
            <h1 class="page-title" style="font-size:1.25rem;">Bộ lọc & tìm kiếm</h1>
            <p class="page-subtitle">Lọc hoa theo danh mục, giá và từ khóa.</p>

            <form method="GET" action="{{ route('products.index') }}">
                <div class="filter-field">
                    <label>Từ khóa</label>
                    <input
                        type="text"
                        name="q"
                        value="{{ request('q') }}"
                        placeholder="Ví dụ: sinh nhật, hồng, lan..."
                        class="filter-input"
                    >
                </div>

                <div class="filter-field">
                    <label>Danh mục</label>
                    <select name="category" class="filter-input">
                        <option value="">Tất cả</option>
                        @foreach($categories as $category)
                            <option value="{{ $category->slug }}" @selected(request('category') === $category->slug)>
                                {{ $category->name }}
                            </option>
                        @endforeach
                    </select>
                </div>

                <div class="filter-row">
                    <div class="filter-field">
                        <label>Giá từ</label>
                        <input
                            type="number"
                            name="price_min"
                            value="{{ request('price_min') }}"
                            min="0"
                            class="filter-input"
                        >
                    </div>
                    <div class="filter-field">
                        <label>Đến</label>
                        <input
                            type="number"
                            name="price_max"
                            value="{{ request('price_max') }}"
                            min="0"
                            class="filter-input"
                        >
                    </div>
                </div>

                <div class="filter-actions">
                    <button type="submit" class="btn btn-primary">Áp dụng</button>
                    <a href="{{ route('products.index') }}" class="btn btn-outline" style="text-align:center;border-style:solid;">
                        Xóa bộ lọc
                    </a>
                </div>
            </form>
        </aside>

        <section style="flex:1 1 280px;min-width:0;">
            <div style="display:flex;align-items:flex-end;justify-content:space-between;margin-bottom:1rem;gap:.75rem;">
                <div>
                    <h2 class="page-title" style="font-size:1.25rem;margin-bottom:.25rem;">Danh sách sản phẩm</h2>
                    <p class="page-subtitle" style="margin-bottom:0;">
                        Tìm thấy {{ $products->total() }} sản phẩm.
                    </p>
                </div>
            </div>

            @if($products->count())
                <div style="display:grid;grid-template-columns:repeat(auto-fill,minmax(190px,1fr));gap:1rem;">
                    @foreach($products as $product)
                        <a href="{{ route('products.show', $product->slug) }}" style="border-radius:.75rem;border:1px solid #e3e3e0;padding:.75rem;display:block;background:#fff;">
                            <div style="border-radius:.6rem;overflow:hidden;background:#fff2f2;height:160px;display:flex;align-items:center;justify-content:center;margin-bottom:.5rem;">
                                @if($product->image_path)
                                    <img src="{{ asset($product->image_path) }}" alt="{{ $product->name }}" style="max-width:100%;max-height:100%;object-fit:cover;">
                                @else
                                    <span style="font-size:2rem;">🌹</span>
                                @endif
                            </div>
                            <div style="font-size:.9rem;font-weight:500;margin-bottom:.25rem;white-space:nowrap;overflow:hidden;text-overflow:ellipsis;">
                                {{ $product->name }}
                            </div>
                            <div style="font-size:.86rem;color:#706f6c;margin-bottom:.25rem;">
                                {{ optional($product->category)->name }}
                            </div>
                            <div style="display:flex;align-items:center;justify-content:space-between;">
                                <span style="font-weight:600;color:#f53003;">
                                    {{ number_format($product->price, 0, ',', '.') }} đ
                                </span>
                            </div>
                        </a>
                    @endforeach
                </div>

                <div style="margin-top:1.5rem;font-size:.9rem;">
                    {{ $products->links() }}
                </div>
            @else
                <p style="font-size:.9rem;color:#706f6c;">Không tìm thấy sản phẩm phù hợp.</p>
            @endif
        </section>
    </div>
@endsection

