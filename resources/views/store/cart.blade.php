@extends('layouts.store')

@section('title', 'Giỏ hàng')

@section('content')
    <h1 class="page-title">Giỏ hàng của bạn</h1>
    <p class="page-subtitle">Kiểm tra lại sản phẩm trước khi thanh toán.</p>

    @if (empty($items))
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
                        @foreach ($items as $item)
                            <tr style="border-top:1px solid #f0f0ec;">
                                <td style="padding:.7rem .9rem;">
                                    <div style="display:flex;align-items:center;gap:.6rem;">
                                        <div
                                            style="width:52px;height:52px;border-radius:.6rem;overflow:hidden;background:#fff2f2;display:flex;align-items:center;justify-content:center;flex-shrink:0;">
                                            @if ($item['product']->image_path)
                                                <img src="{{ asset($item['product']->image_path) }}"
                                                    alt="{{ $item['product']->name }}"
                                                    style="max-width:100%;max-height:100%;object-fit:cover;">
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
                                    @if (isset($item['product']->stock) && $item['product']->stock == 0)
                                        <div style="color:#b91c1c;font-weight:600;">Số lượng không đủ</div>
                                    @else
                                        <div style="display:flex;flex-direction:column;align-items:center;gap:.35rem;">
                                            <div style="display:flex;align-items:center;gap:.5rem;">
                                                <input type="number" class="cart-qty"
                                                    data-product-id="{{ $item['product']->id }}"
                                                    data-price="{{ $item['product']->price }}"
                                                    data-stock='@json($item['product']->stock)'
                                                    name="items[{{ $item['product']->id }}][quantity]"
                                                    value="{{ $item['quantity'] }}" min="0"
                                                    style="width:70px;padding:.25rem .4rem;border-radius:.35rem;border:1px solid #e3e3e0;font-size:.85rem;">
                                                <button type="button" class="cart-delete"
                                                    data-product-id="{{ $item['product']->id }}"
                                                    style="background:transparent;border:1px solid #e3e3e0;color:#d12600;padding:.25rem .45rem;border-radius:.35rem;cursor:pointer;font-size:.82rem;">Xóa</button>
                                            </div>
                                            <div class="cart-stock-msg"
                                                style="color:#b91c1c;font-size:.8rem;margin-top:.35rem;display:none;"></div>
                                        </div>
                                    @endif
                                </td>
                                <td style="padding:.7rem .9rem;text-align:right;">
                                    {{ number_format($item['product']->price, 0, ',', '.') }} đ
                                </td>
                                <td style="padding:.7rem .9rem;text-align:right;font-weight:500;">
                                    <span class="cart-subtotal"
                                        data-product-id="{{ $item['product']->id }}">{{ number_format($item['subtotal'], 0, ',', '.') }}
                                        đ</span>
                                </td>
                            </tr>
                        @endforeach
                    </tbody>
                </table>
            </div>

            <div
                style="display:flex;flex-wrap:wrap;align-items:center;justify-content:flex-end;gap:.75rem;margin-bottom:1.25rem;">
                <div style="font-size:1rem;">
                    <span style="margin-right:.5rem;">Tổng cộng:</span>
                    <strong id="cart-total" style="font-size:1.1rem;color:#f53003;">
                        {{ number_format($total, 0, ',', '.') }} đ
                    </strong>
                </div>
            </div>
        </form>

        <a href="{{ route('checkout.show') }}" id="checkout-link" class="btn btn-primary">
            Tiến hành thanh toán
        </a>
    @endif
@endsection

@push('scripts')
    <script>
        (function() {
            function formatVND(n) {
                return n.toLocaleString('vi-VN') + ' đ';
            }

            function initCart() {
                let qtyInputs = Array.from(document.querySelectorAll('.cart-qty'));
                const totalEl = document.getElementById('cart-total');
                const updateBtn = document.querySelector('form button[type="submit"]');

                if (qtyInputs.length === 0) return;

                function recalc() {
                    let total = 0;
                    let hasError = false;
                    const toRemove = [];
                    qtyInputs.forEach(function(inp) {
                        const id = inp.dataset.productId;
                        const price = parseFloat(inp.dataset.price) || 0;
                        const stockRaw = inp.dataset.stock;
                        const stock = (stockRaw === undefined || stockRaw === '' || stockRaw === 'null') ?
                            null :
                            parseInt(stockRaw, 10);
                        let q = parseInt(inp.value, 10);
                        if (isNaN(q) || q < 0) q = 0;

                        if (q === 0) {
                            // mark for removal from DOM and skip adding to total
                            const subEl = document.querySelector('.cart-subtotal[data-product-id="' + id +
                            '"]');
                            if (subEl) subEl.textContent = formatVND(0);
                            toRemove.push(id);
                            return;
                        }

                        const subtotal = price * q;
                        total += subtotal;

                        const subEl = document.querySelector('.cart-subtotal[data-product-id="' + id + '"]');
                        if (subEl) subEl.textContent = formatVND(subtotal);

                        const msgEl = inp.closest('td').querySelector('.cart-stock-msg');
                        if (stock !== null && stock >= 0 && q > stock) {
                            if (msgEl) {
                                msgEl.style.display = 'block';
                                msgEl.textContent = 'Không đủ hàng — chỉ còn ' + stock + ' sản phẩm.';
                            }
                            hasError = true;
                        } else if (msgEl) {
                            msgEl.style.display = 'none';
                            msgEl.textContent = '';
                        }
                    });

                    // remove rows with qty == 0
                    if (toRemove.length) {
                        toRemove.forEach(function(id) {
                            const inp = document.querySelector('.cart-qty[data-product-id="' + id + '"]');
                            if (inp) {
                                const row = inp.closest('tr');
                                if (row) row.remove();
                            }
                        });
                        // refresh qtyInputs collection
                        qtyInputs = Array.from(document.querySelectorAll('.cart-qty'));
                    }

                    if (totalEl) totalEl.textContent = formatVND(total);
                    if (updateBtn) updateBtn.disabled = hasError;
                    // disable checkout link when there's an error
                    const checkoutLinkEl = document.getElementById('checkout-link');
                    if (checkoutLinkEl) {
                        if (hasError) {
                            checkoutLinkEl.style.pointerEvents = 'none';
                            checkoutLinkEl.style.opacity = '0.6';
                        } else {
                            checkoutLinkEl.style.pointerEvents = '';
                            checkoutLinkEl.style.opacity = '';
                        }
                    }
                }

                let saveTimeout = null;

                function saveRemote() {
                    const items = {};
                    qtyInputs.forEach(function(inp) {
                        const id = inp.dataset.productId;
                        let q = parseInt(inp.value, 10);
                        if (isNaN(q) || q < 0) q = 0;
                        items[id] = {
                            quantity: q
                        };
                    });

                    const tokenMeta = document.querySelector('meta[name="csrf-token"]');
                    const token = tokenMeta ? tokenMeta.getAttribute('content') : '';

                    fetch('{{ route('cart.update') }}', {
                        method: 'POST',
                        headers: {
                            'Content-Type': 'application/json',
                            'X-CSRF-TOKEN': token,
                            'X-Requested-With': 'XMLHttpRequest',
                            'Accept': 'application/json'
                        },
                        body: JSON.stringify({
                            items: items
                        })
                    }).then(function(res) {
                        if (!res.ok) return res.json().catch(() => {});
                        return res.json().catch(() => {});
                    }).then(function(data) {
                        // optionally handle server validation messages
                    }).catch(function() {
                        // ignore network errors for now
                    });
                }

                function debouncedSave() {
                    if (saveTimeout) clearTimeout(saveTimeout);
                    saveTimeout = setTimeout(function() {
                        saveRemote();
                    }, 600);
                }

                // attach listeners to quantity inputs
                function attachQtyListeners() {
                    qtyInputs.forEach(function(i) {
                        i.removeEventListener('input', function() {});
                        i.removeEventListener('change', function() {});
                        i.addEventListener('input', function() {
                            recalc();
                            debouncedSave();
                        });
                        i.addEventListener('change', function() {
                            recalc();
                            debouncedSave();
                        });
                    });
                }

                attachQtyListeners();

                // attach delete button listeners
                function attachDeleteListeners() {
                    const dels = Array.from(document.querySelectorAll('.cart-delete'));
                    dels.forEach(function(btn) {
                        btn.addEventListener('click', function() {
                            const id = btn.dataset.productId;
                            const inp = document.querySelector('.cart-qty[data-product-id="' + id +
                                '"]');
                            if (inp) {
                                inp.value = 0;
                            }
                            recalc();
                            debouncedSave();
                        });
                    });
                }

                attachDeleteListeners();

                // ensure totals are recalculated on load
                recalc();

                // ensure clicking update (form submit) recalculates totals before submission
                const cartForm = document.querySelector('form');
                if (cartForm) {
                    cartForm.addEventListener('submit', function() {
                        recalc();
                    });
                }

                // Ensure checkout navigates after saving latest quantities
                const checkoutLink = document.getElementById('checkout-link');
                if (checkoutLink) {
                    checkoutLink.addEventListener('click', function(e) {
                        e.preventDefault();
                        // try to save and then go to checkout
                        saveRemote();
                        // small delay to let request start; then navigate
                        setTimeout(function() {
                            window.location = checkoutLink.href;
                        }, 250);
                    });
                }
            }

            if (document.readyState === 'loading') {
                document.addEventListener('DOMContentLoaded', initCart);
            } else {
                initCart();
            }
        })();
    </script>
@endpush
