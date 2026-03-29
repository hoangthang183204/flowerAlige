@extends('admin.layouts.app')

@section('title', 'Sửa danh mục')

@section('content')
    <div class="admin-page admin-container">
        <div class="admin-page-header">
            <div>
                <div class="admin-page-title">Sửa danh mục</div>
                <div class="admin-page-subtitle">Cập nhật thông tin nhóm sản phẩm: <strong>{{ $category->name }}</strong>.</div>
            </div>
        </div>

        <div class="admin-card overflow-hidden">
            <div class="px-5 py-4 admin-accent-bg" style="border-bottom: 1px solid var(--admin-border);">
                <h2 class="font-bold flex items-center gap-2" style="color: var(--admin-dark);">
                    <span class="w-9 h-9 rounded-lg admin-accent-bg-strong flex items-center justify-center text-base">🛠️</span>
                    Thông tin danh mục
                </h2>
            </div>
            <form action="{{ route('admin.categories.update', $category) }}" method="POST" class="admin-form">
                @csrf
                @method('PUT')

                <div class="grid grid-cols-1 md:grid-cols-2 gap-4">
                    <div class="admin-field">
                        <label class="admin-label">Tên danh mục *</label>
                        <input type="text" name="name" value="{{ old('name', $category->name) }}" class="admin-input" required>
                        @error('name')
                            <div class="admin-error">{{ $message }}</div>
                        @enderror
                    </div>

                    <div class="admin-field">
                        <label class="admin-label">Đường dẫn ảnh</label>
                        <input type="text" name="image_path" value="{{ old('image_path', $category->image_path) }}" class="admin-input">
                        <div class="admin-help">Ví dụ: <code>/storage/categories/hoa-sinh-nhat.jpg</code></div>
                    </div>
                </div>

                <div class="admin-field mt-4">
                    <label class="admin-label">Mô tả</label>
                    <textarea name="description" rows="4" class="admin-textarea">{{ old('description', $category->description) }}</textarea>
                </div>

                <div class="flex items-center gap-2 mt-4">
                    <input type="checkbox" name="is_active" value="1" id="is_active" class="rounded border-gray-300" {{ old('is_active', $category->is_active) ? 'checked' : '' }}>
                    <label for="is_active" class="text-sm text-gray-700">Hiển thị trên website</label>
                </div>

                <div class="admin-form-footer">
                    <button type="submit" class="admin-btn admin-btn-accent">Cập nhật</button>
                    <a href="{{ route('admin.categories.index') }}" class="admin-btn admin-btn-outline">Hủy</a>
                </div>
            </form>
        </div>
    </div>
@endsection

