@extends('layouts.store')

@section('title', 'Giỏ hàng')

@section('content')
    <h1 class="page-title">Giỏ hàng của bạn</h1>
    <p class="page-subtitle">Kiểm tra lại sản phẩm trước khi thanh toán.</p>

    @if(empty($items))
        <p style="font-size:.9rem;color:#706f6c;">Giỏ hàng đang trống.</p>
        <a href="{{ route('products.index') }}" class="btn btn-primary" style="margin-top:.5rem;">Tiếp tục mua sắm</a>
    @else
        <form action="{{ route('cart.update') }}" method="POST">
            @csrf
            <div style="border-radius:.9rem;border:1px solid #e3e3e0;overflow:hidden;margin-bottom:1rem;">
                <table style="width:100%;border-collapse:collapse;font-size:.9rem;">
                    <thead style="background:#f8f8f5;">
                        <tr>
                            <th style="text-align:left;padding:.6rem .9rem;">Sản phẩm</th>
                            <th style="text-align:center;padding:.6rem .9rem;width:80px;">Số lượng</th>
                            <th style="text-align:right;padding:.6rem .9rem;width:120px;">Đơn giá</th>
                            <th style="text-align:right;padding:.6rem .9rem;width:120px;">Thành tiền</th>
                        </tr>
                    </thead>
                    <tbody>
                        @foreach($items as $item)
                            <tr style="border-top:1px solid #f0f0ec;">
                                <td style="padding:.7rem .9rem;">
                                    <div style="display:flex;align-items:center;gap:.6rem;">
                                        <div style="width:52px;height:52px;border-radius:.6rem;overflow:hidden;background:#fff2f2;display:flex;align-items:center;justify-content:center;flex-shrink:0;">
                                            @if($item['product']->image_path)
                                                <img src="{{ asset($item['product']->image_path) }}" alt="{{ $item['product']->name }}" style="max-width:100%;max-height:100%;object-fit:cover;">
                                            @else
                                                <span>🌸</span>
                                            @endif
                                        </div>
                                        <div>
                                            <div style="font-weight:500;margin-bottom:.1rem;">
                                                <a href="{{ route('products.show', $item['product']->slug) }}">
                                                    {{ $item['product']->name }}
                                                </a>
                                            </div>
                                            <div style="font-size:.82rem;color:#706f6c;">
                                                {{ optional($item['product']->category)->name }}
                                            </div>
                                        </div>
                                    </div>
                                </td>
                                <td style="padding:.7rem .9rem;text-align:center;">
                                    <input
                                        type="number"
                                        name="items[{{ $item['product']->id }}][quantity]"
                                        value="{{ $item['quantity'] }}"
                                        min="0"
                                        style="width:70px;padding:.25rem .4rem;border-radius:.35rem;border:1px solid #e3e3e0;font-size:.85rem;"
                                    >
                                </td>
                                <td style="padding:.7rem .9rem;text-align:right;">
                                    {{ number_format($item['product']->price, 0, ',', '.') }} đ
                                </td>
                                <td style="padding:.7rem .9rem;text-align:right;font-weight:500;">
                                    {{ number_format($item['subtotal'], 0, ',', '.') }} đ
                                </td>
                            </tr>
                        @endforeach
                    </tbody>
                </table>
            </div>

            <div style="display:flex;flex-wrap:wrap;align-items:center;justify-content:space-between;gap:.75rem;margin-bottom:1.25rem;">
                <button type="submit" class="btn btn-outline" style="border-style:solid;">
                    Cập nhật giỏ hàng
                </button>
                <div style="font-size:1rem;">
                    <span style="margin-right:.5rem;">Tổng cộng:</span>
                    <strong style="font-size:1.1rem;color:#f53003;">
                        {{ number_format($total, 0, ',', '.') }} đ
                    </strong>
                </div>
            </div>
        </form>

        <a href="{{ route('checkout.show') }}" class="btn btn-primary">
            Tiến hành thanh toán
        </a>
    @endif
@endsection

