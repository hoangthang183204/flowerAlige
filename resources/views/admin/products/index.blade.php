@extends('admin.layouts.app')

@section('title', 'Quản lý sản phẩm')

@section('breadcrumb')
    <span class="font-medium text-gray-900">Sản phẩm</span>
@endsection

@section('content')
    <div class="admin-page admin-container">
        <div class="flex items-center justify-between mb-4">
            <div>
                <div class="admin-page-title">  Danh sách sản phẩm</div>
                <div class="admin-page-subtitle">Quản lý sản phẩm, giá, tồn kho.</div>
            </div>
            <a href="{{ route('admin.products.create') }}" class="admin-btn">+ Thêm sản phẩm</a>
        </div>

        <div class="admin-card overflow-hidden">
            <div class="px-5 py-4 admin-accent-bg" style="border-bottom: 1px solid var(--admin-border);">
                <h2 class="font-bold flex items-center gap-2" style="color: var(--admin-dark);">
                    <span
                        class="w-9 h-9 rounded-lg admin-accent-bg-strong flex items-center justify-center text-base">📦</span>
                    Danh sách sản phẩm
                </h2>
            </div>

            <div class="overflow-x-auto w-full">
                <table class="min-w-full w-full text-sm table-fixed">
                    <thead>
                        <tr class="bg-gray-50 border-b" style="border-color: var(--admin-border);">
                            <th class="px-4 py-2.5 text-left font-medium text-gray-600" style="width: 28%;">Sản phẩm</th>
                            <th class="px-4 py-2.5 text-left font-medium text-gray-600" style="width: 14%;">Danh mục</th>
                            <th class="px-4 py-2.5 text-right font-medium text-gray-600" style="width: 10%;">Giá</th>
                            <th class="px-4 py-2.5 text-center font-medium text-gray-600" style="width: 8%;">Tồn kho</th>
                            <th class="px-4 py-2.5 text-center font-medium text-gray-600" style="width: 18%;">Trạng thái
                            </th>
                            <th class="px-4 py-2.5 text-right font-medium text-gray-600" style="width: 22%;">Thao tác</th>
                        </tr>
                    </thead>
                    <tbody>
                        @forelse($products as $product)
                            <tr class="hover-admin-row border-b" style="border-color: var(--admin-border);">
                                <td class="px-4 py-2.5">
                                    <div class="flex items-center gap-3">
                                        <div class="rounded-md overflow-hidden bg-slate-100 flex items-center justify-center flex-shrink-0"
                                            style="width:100px;height:100px;">
                                            @if ($product->image_path)
                                                <img src="{{ asset($product->image_path) }}" alt="{{ $product->name }}"
                                                    style="width:100%;height:100%;object-fit:cover;">
                                            @else
                                                <span class="text-lg">💐</span>
                                            @endif
                                        </div>
                                        <div>
                                            <div class="font-semibold text-slate-900">{{ $product->name }}</div>
                                            <div class="text-[11px] text-slate-500">{{ $product->slug }}</div>
                                        </div>
                                    </div>
                                </td>
                                <td class="px-4 py-2.5 text-gray-700">{{ optional($product->category)->name ?? '—' }}</td>
                                <td class="px-4 py-2.5 text-right font-medium text-gray-900">
                                    {{ number_format($product->price, 0, ',', '.') }} đ</td>
                                <td class="px-4 py-2.5 text-center">
                                    <span
                                        class="inline-flex items-center justify-center px-2.5 py-1 rounded-lg text-xs font-semibold
                                @if ($product->stock <= 0) bg-red-50 text-red-700
                                @elseif($product->stock < 5) bg-amber-50 text-amber-700
                                @else bg-emerald-50 text-emerald-700 @endif">
                                        {{ $product->stock }}
                                    </span>
                                </td>
                                <td class="px-4 py-2.5 text-center">
                                    <div class="flex flex-wrap items-center justify-center gap-1.5 text-xs">
                                        <span
                                            class="inline-flex px-2.5 py-1 rounded-lg font-medium
                                    {{ $product->is_active && !$product->deleted_at ? 'bg-emerald-50 text-emerald-700' : 'bg-slate-100 text-slate-600' }}">
                                            {{ $product->deleted_at ? 'Đã xóa mềm' : ($product->is_active ? 'Hiển thị' : 'Ẩn') }}
                                        </span>
                                        @if ($product->is_featured && !$product->deleted_at)
                                            <span
                                                class="inline-flex px-2.5 py-1 rounded-lg font-medium bg-amber-50 text-amber-700">Nổi
                                                bật</span>
                                        @endif
                                    </div>
                                </td>
                                <td class="px-4 py-2.5 text-right">
                                    <div class="flex flex-wrap items-center justify-end gap-2">
                                        @if (!$product->deleted_at)
                                            <a href="{{ route('admin.products.edit', $product) }}"
                                                class="admin-btn admin-btn-sm admin-btn-outline">Sửa</a>
                                            <form action="{{ route('admin.products.destroy', $product) }}" method="POST"
                                                class="inline">
                                                @csrf
                                                @method('DELETE')
                                                <button type="submit" class="admin-btn admin-btn-sm"
                                                    style="background: #dc2626; color: #fff; border: none;"
                                                    onclick="return confirm('Ẩn (xóa mềm) sản phẩm này?')">Xóa mềm</button>
                                            </form>
                                        @else
                                            <form action="{{ route('admin.products.restore', $product->id) }}"
                                                method="POST" class="inline">
                                                @csrf
                                                <button type="submit" class="admin-btn admin-btn-sm"
                                                    style="background: #059669; color: #fff; border: none;">Khôi
                                                    phục</button>
                                            </form>
                                            <form action="{{ route('admin.products.force-delete', $product->id) }}"
                                                method="POST" class="inline">
                                                @csrf
                                                @method('DELETE')
                                                <button type="submit" class="admin-btn admin-btn-sm"
                                                    style="background: #b91c1c; color: #fff; border: none;"
                                                    onclick="return confirm('Xóa vĩnh viễn sản phẩm này?')">Xóa
                                                    cứng</button>
                                            </form>
                                        @endif
                                    </div>
                                </td>
                            </tr>
                        @empty
                            <tr>
                                <td class="px-4 py-8 text-center text-sm text-gray-500" colspan="6">
                                    Chưa có sản phẩm nào.
                                </td>
                            </tr>
                        @endforelse
                    </tbody>
                </table>
            </div>
        </div>

        <div class="mt-4">
            {{ $products->links() }}
        </div>
    </div>
@endsection
