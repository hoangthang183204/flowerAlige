@extends('admin.layouts.app')

@section('title', 'Sửa sản phẩm')

@section('content')
    <div class="admin-page admin-container">
        <div class="admin-page-header">
            <div>
                <div class="admin-page-title">Sửa sản phẩm</div>
                <div class="admin-page-subtitle">Cập nhật thông tin: <strong>{{ $product->name }}</strong>.</div>
            </div>
        </div>

        <div class="admin-card overflow-hidden">
            <div class="px-5 py-4 admin-accent-bg" style="border-bottom: 1px solid var(--admin-border);">
                <h2 class="font-bold flex items-center gap-2" style="color: var(--admin-dark);">
                    <span class="w-9 h-9 rounded-lg admin-accent-bg-strong flex items-center justify-center text-base">🛠️</span>
                    Thông tin sản phẩm
                </h2>
            </div>

            <form action="{{ route('admin.products.update', $product) }}" method="POST" enctype="multipart/form-data" class="admin-form">
                @csrf
                @method('PUT')

                <div class="grid grid-cols-1 md:grid-cols-2 gap-4">
                    <div class="admin-field">
                        <label class="admin-label">Tên sản phẩm *</label>
                        <input type="text" name="name" value="{{ old('name', $product->name) }}" class="admin-input" required>
                        @error('name') <div class="admin-error">{{ $message }}</div> @enderror
                    </div>
                    <div class="admin-field">
                        <label class="admin-label">Danh mục *</label>
                        <select name="category_id" class="admin-select" required>
                            @foreach($categories as $category)
                                <option value="{{ $category->id }}" @selected(old('category_id', $product->category_id) == $category->id)>{{ $category->name }}</option>
                            @endforeach
                        </select>
                        @error('category_id') <div class="admin-error">{{ $message }}</div> @enderror
                    </div>
                </div>

                <div class="grid grid-cols-1 md:grid-cols-2 gap-4 mt-4">
                    <div class="admin-field">
                        <label class="admin-label">Giá (VND) *</label>
                        <input type="number" name="price" value="{{ old('price', $product->price) }}" min="0" class="admin-input" required>
                        @error('price') <div class="admin-error">{{ $message }}</div> @enderror
                    </div>
                    <div class="admin-field">
                        <label class="admin-label">Tồn kho *</label>
                        <input type="number" name="stock" value="{{ old('stock', $product->stock) }}" min="0" class="admin-input" required>
                        @error('stock') <div class="admin-error">{{ $message }}</div> @enderror
                    </div>
                </div>

                <div class="grid grid-cols-1 md:grid-cols-3 gap-4 mt-4">
                    <div class="admin-field md:col-span-2">
                        <label class="admin-label">Mô tả ngắn</label>
                        <textarea name="short_description" rows="3" class="admin-textarea">{{ old('short_description', $product->short_description) }}</textarea>
                    </div>
                    <div class="admin-field md:row-span-3">
                        <label class="admin-label">Xem trước ảnh</label>
                        <div class="w-full h-40 rounded-lg border border-dashed border-gray-300 flex items-center justify-center bg-gray-50 overflow-hidden">
                            @if($product->image_path)
                                <img src="{{ asset($product->image_path) }}" alt="{{ $product->name }}" class="w-full h-full object-cover">
                            @else
                                <span class="text-xs text-gray-500">Chưa có ảnh</span>
                            @endif
                        </div>
                    </div>
                    <div class="admin-field md:col-span-2">
                        <label class="admin-label">Mô tả chi tiết</label>
                        <textarea name="description" rows="5" class="admin-textarea">{{ old('description', $product->description) }}</textarea>
                    </div>
                    <div class="admin-field md:col-span-2">
                        <label class="admin-label">Ảnh mới (nếu muốn thay)</label>
                        <input type="file" name="image" class="admin-input">
                        @error('image') <div class="admin-error">{{ $message }}</div> @enderror
                        <div class="admin-help">Nếu không chọn file mới, hệ thống sẽ giữ nguyên ảnh hiện tại.</div>
                        <input type="hidden" name="image_path" value="{{ old('image_path', $product->image_path) }}">
                    </div>
                </div>

                <div class="flex items-center gap-4 mt-4">
                    <label class="inline-flex items-center text-sm text-gray-700">
                        <input type="checkbox" name="is_active" value="1" class="rounded border-gray-300" {{ old('is_active', $product->is_active) ? 'checked' : '' }}>
                        <span class="ml-2">Hiển thị trên website</span>
                    </label>
                    <label class="inline-flex items-center text-sm text-gray-700">
                        <input type="checkbox" name="is_featured" value="1" class="rounded border-gray-300" {{ old('is_featured', $product->is_featured) ? 'checked' : '' }}>
                        <span class="ml-2">Đánh dấu là sản phẩm nổi bật</span>
                    </label>
                </div>

                <div class="admin-form-footer">
                    <button type="submit" class="admin-btn admin-btn-accent">Cập nhật</button>
                    <a href="{{ route('admin.products.index') }}" class="admin-btn admin-btn-outline">Hủy</a>
                </div>
            </form>
        </div>
    </div>
@endsection

