@extends('admin.layouts.app')

@section('title', 'Thêm sản phẩm')

@section('content')
    <div class="admin-page admin-container">
        <div class="admin-page-header">
            <div>
                <div class="admin-page-title">Thêm sản phẩm</div>
                <div class="admin-page-subtitle">Thêm sản phẩm mới, giá và tồn kho.</div>
            </div>
        </div>

        <div class="admin-card overflow-hidden">
            <div class="px-5 py-4 admin-accent-bg" style="border-bottom: 1px solid var(--admin-border);">
                <h2 class="font-bold flex items-center gap-2" style="color: var(--admin-dark);">
                    <span class="w-9 h-9 rounded-lg admin-accent-bg-strong flex items-center justify-center text-base">💐</span>
                    Thông tin sản phẩm
                </h2>
            </div>

            <form action="{{ route('admin.products.store') }}" method="POST" enctype="multipart/form-data" class="admin-form">
                @csrf

                <div class="grid grid-cols-1 md:grid-cols-2 gap-4">
                    <div class="admin-field">
                        <label class="admin-label">Tên sản phẩm *</label>
                        <input type="text" name="name" value="{{ old('name') }}" class="admin-input" required>
                        @error('name') <div class="admin-error">{{ $message }}</div> @enderror
                    </div>
                    <div class="admin-field">
                        <label class="admin-label">Danh mục *</label>
                        <select name="category_id" class="admin-select" required>
                            <option value="">-- Chọn danh mục --</option>
                            @foreach($categories as $category)
                                <option value="{{ $category->id }}" @selected(old('category_id') == $category->id)>{{ $category->name }}</option>
                            @endforeach
                        </select>
                        @error('category_id') <div class="admin-error">{{ $message }}</div> @enderror
                    </div>
                </div>

                <div class="grid grid-cols-1 md:grid-cols-2 gap-4 mt-4">
                    <div class="admin-field">
                        <label class="admin-label">Giá (VND) *</label>
                        <input type="number" name="price" value="{{ old('price') }}" min="0" class="admin-input" required>
                        @error('price') <div class="admin-error">{{ $message }}</div> @enderror
                    </div>
                    <div class="admin-field">
                        <label class="admin-label">Tồn kho *</label>
                        <input type="number" name="stock" value="{{ old('stock', 0) }}" min="0" class="admin-input" required>
                        @error('stock') <div class="admin-error">{{ $message }}</div> @enderror
                    </div>
                </div>

                <div class="grid grid-cols-1 md:grid-cols-3 gap-4 mt-4">
                    <div class="admin-field md:col-span-2">
                        <label class="admin-label">Mô tả ngắn</label>
                        <textarea name="short_description" rows="3" class="admin-textarea">{{ old('short_description') }}</textarea>
                    </div>
                    <div class="admin-field md:row-span-3">
                        <label class="admin-label">Xem trước ảnh</label>
                        <div class="w-full h-40 rounded-lg border border-dashed border-gray-300 flex items-center justify-center bg-gray-50 text-xs text-gray-500">
                            Sau khi chọn file và lưu, ảnh sẽ hiển thị ở frontend
                        </div>
                    </div>
                    <div class="admin-field md:col-span-2">
                        <label class="admin-label">Mô tả chi tiết</label>
                        <textarea name="description" rows="5" class="admin-textarea">{{ old('description') }}</textarea>
                    </div>
                    <div class="admin-field md:col-span-2">
                        <label class="admin-label">Ảnh sản phẩm</label>
                        <input type="file" name="image" class="admin-input">
                        @error('image') <div class="admin-error">{{ $message }}</div> @enderror
                        <div class="admin-help">Tối đa 2MB. Lưu tại <code>storage/products</code>.</div>
                    </div>
                </div>

                <div class="flex items-center gap-4 mt-4">
                    <label class="inline-flex items-center text-sm text-gray-700">
                        <input type="checkbox" name="is_active" value="1" class="rounded border-gray-300" checked>
                        <span class="ml-2">Hiển thị trên website</span>
                    </label>
                    <label class="inline-flex items-center text-sm text-gray-700">
                        <input type="checkbox" name="is_featured" value="1" class="rounded border-gray-300">
                        <span class="ml-2">Đánh dấu là sản phẩm nổi bật</span>
                    </label>
                </div>

                <div class="admin-form-footer">
                    <button type="submit" class="admin-btn admin-btn-accent">Lưu sản phẩm</button>
                    <a href="{{ route('admin.products.index') }}" class="admin-btn admin-btn-outline">Hủy</a>
                </div>
            </form>
        </div>
    </div>
@endsection

