@extends('layouts.store')

@section('title', 'Thanh toán')

@section('content')
    <h1 class="page-title">Thanh toán</h1>
    <p class="page-subtitle">Điền thông tin giao hàng và phương thức thanh toán.</p>

    @if(empty($items))
        <p style="font-size:.9rem;color:#706f6c;">Giỏ hàng đang trống.</p>
        <a href="{{ route('products.index') }}" class="btn btn-primary" style="margin-top:.5rem;">Tiếp tục mua sắm</a>
    @else
        <div style="display:flex;flex-wrap:wrap;gap:2rem;align-items:flex-start;">
            <section style="flex:1 1 260px;min-width:0;">
                <form action="{{ route('checkout.place') }}" method="POST">
                    @csrf
                    <div class="form-field">
                        <label>Họ và tên *</label>
                        <input type="text" name="customer_name" value="{{ old('customer_name') }}" required class="form-input">
                        @error('customer_name')
                            <div style="color:#c53030;font-size:.8rem;margin-top:.25rem;">{{ $message }}</div>
                        @enderror
                    </div>

                    <div class="form-row">
                        <div class="form-field">
                            <label>Số điện thoại *</label>
                            <input type="text" name="customer_phone" value="{{ old('customer_phone') }}" required class="form-input">
                            @error('customer_phone')
                                <div style="color:#c53030;font-size:.8rem;margin-top:.25rem;">{{ $message }}</div>
                            @enderror
                        </div>
                        <div class="form-field">
                            <label>Email</label>
                            <input type="email" name="customer_email" value="{{ old('customer_email') }}" class="form-input">
                            @error('customer_email')
                                <div style="color:#c53030;font-size:.8rem;margin-top:.25rem;">{{ $message }}</div>
                            @enderror
                        </div>
                    </div>

                    <div class="form-field">
                        <label>Địa chỉ giao hàng *</label>
                        <textarea name="shipping_address" required rows="3" class="form-input">{{ old('shipping_address') }}</textarea>
                        @error('shipping_address')
                            <div style="color:#c53030;font-size:.8rem;margin-top:.25rem;">{{ $message }}</div>
                        @enderror
                    </div>

                    <div class="form-field">
                        <label>Hình thức thanh toán *</label>
                        <div style="display:flex;flex-direction:column;gap:.35rem;font-size:.9rem;">
                            <label style="display:flex;align-items:center;gap:.4rem;">
                                <input type="radio" name="payment_method" value="cod" {{ old('payment_method', 'cod') === 'cod' ? 'checked' : '' }}>
                                <span>Thanh toán khi nhận hàng (COD)</span>
                            </label>
                            <label style="display:flex;align-items:center;gap:.4rem;">
                                <input type="radio" name="payment_method" value="momo" {{ old('payment_method') === 'momo' ? 'checked' : '' }}>
                                <span>Ví MoMo</span>
                            </label>
                            <label style="display:flex;align-items:center;gap:.4rem;">
                                <input type="radio" name="payment_method" value="vnpay" {{ old('payment_method') === 'vnpay' ? 'checked' : '' }}>
                                <span>VNPay (ATM / Thẻ quốc tế / QR)</span>
                            </label>
                            <label style="display:flex;align-items:center;gap:.4rem;">
                                <input type="radio" name="payment_method" value="bank_transfer" {{ old('payment_method') === 'bank_transfer' ? 'checked' : '' }}>
                                <span>Chuyển khoản ngân hàng</span>
                            </label>
                        </div>
                        @error('payment_method')
                            <div style="color:#c53030;font-size:.8rem;margin-top:.25rem;">{{ $message }}</div>
                        @enderror
                    </div>

                    <div class="form-field">
                        <label>Ghi chú cho đơn hàng</label>
                        <textarea name="notes" rows="3" class="form-input">{{ old('notes') }}</textarea>
                        @error('notes')
                            <div style="color:#c53030;font-size:.8rem;margin-top:.25rem;">{{ $message }}</div>
                        @enderror
                    </div>

                    <button type="submit" class="btn btn-primary">
                        Đặt hàng
                    </button>
                </form>
            </section>

            <aside style="flex:0 0 260px;max-width:100%;">
                <div style="border-radius:.9rem;border:1px solid #e3e3e0;padding:1rem;background:#fff;">
                    <h2 style="font-size:1rem;font-weight:600;margin-bottom:.5rem;">Đơn hàng của bạn</h2>
                    <ul style="list-style:none;margin:0;padding:0 0 .5rem 0;font-size:.9rem;">
                        @foreach($items as $item)
                            <li style="display:flex;justify-content:space-between;margin-bottom:.35rem;gap:.5rem;">
                                <span>
                                    {{ $item['product']->name }} × {{ $item['quantity'] }}
                                </span>
                                <span style="font-weight:500;">
                                    {{ number_format($item['subtotal'], 0, ',', '.') }} đ
                                </span>
                            </li>
                        @endforeach
                    </ul>
                    <div style="border-top:1px dashed #e3e3e0;margin:.5rem 0 0;padding-top:.5rem;font-size:.95rem;display:flex;justify-content:space-between;">
                        <span>Tổng cộng</span>
                        <strong style="color:#f53003;">
                            {{ number_format($total, 0, ',', '.') }} đ
                        </strong>
                    </div>
                </div>
            </aside>
        </div>
    @endif
@endsection

