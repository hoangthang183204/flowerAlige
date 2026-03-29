@extends('admin.layouts.app')

@section('title', 'Sửa banner')

@section('content')
    <div class="admin-page admin-container">
        <div class="admin-page-header">
            <div>
                <div class="admin-page-title">Sửa banner</div>
                <div class="admin-page-subtitle">Cập nhật nội dung hiển thị ở trang chủ.</div>
            </div>
        </div>

        <div class="admin-card overflow-hidden">
            <div class="px-5 py-4 admin-accent-bg" style="border-bottom: 1px solid var(--admin-border);">
                <h2 class="font-bold flex items-center gap-2" style="color: var(--admin-dark);">
                    <span class="w-9 h-9 rounded-lg admin-accent-bg-strong flex items-center justify-center text-base">🖼️</span>
                    Thông tin banner
                </h2>
            </div>

            <form action="{{ route('admin.banners.update', $banner) }}" method="POST" enctype="multipart/form-data" class="admin-form">
                @csrf
                @method('PUT')

                <div class="grid grid-cols-1 md:grid-cols-2 gap-4">
                    <div class="admin-field md:col-span-2">
                        <label class="admin-label">Tiêu đề *</label>
                        <input type="text" name="title" value="{{ old('title', $banner->title) }}" class="admin-input" required>
                        @error('title')
                            <div class="admin-error">{{ $message }}</div>
                        @enderror
                    </div>

                    <div class="admin-field md:col-span-2">
                        <label class="admin-label">Liên kết (URL)</label>
                        <input type="text" name="link_url" value="{{ old('link_url', $banner->link_url) }}" class="admin-input">
                    </div>

                    <div class="admin-field">
                        <label class="admin-label">Vị trí</label>
                        <input type="number" name="position" value="{{ old('position', $banner->position) }}" class="admin-input" min="0">
                    </div>

                    <div class="admin-field">
                        <label class="admin-label">Ảnh mới (nếu muốn thay)</label>
                        <input type="file" name="image" class="admin-input">
                        @error('image')
                            <div class="admin-error">{{ $message }}</div>
                        @enderror
                        <div class="admin-help">Nếu không chọn ảnh mới, hệ thống giữ nguyên ảnh hiện tại.</div>
                    </div>
                </div>

                <div class="admin-field mt-4">
                    <label class="admin-label">Ảnh hiện tại</label>
                    <div class="w-full h-40 rounded-lg border border-gray-200 overflow-hidden bg-gray-50">
                        <img src="{{ asset($banner->image_path) }}" alt="{{ $banner->title }}" style="width:100%;height:100%;object-fit:cover;">
                    </div>
                </div>

                <div class="flex items-center gap-2 mt-4">
                    <input type="checkbox" name="is_active" value="1" id="is_active" class="rounded border-gray-300" {{ old('is_active', $banner->is_active) ? 'checked' : '' }}>
                    <label for="is_active" class="text-sm text-gray-700">Hiển thị trên trang chủ</label>
                </div>

                <div class="admin-form-footer">
                    <button type="submit" class="admin-btn admin-btn-accent">Cập nhật banner</button>
                    <a href="{{ route('admin.banners.index') }}" class="admin-btn admin-btn-outline">Hủy</a>
                </div>
            </form>
        </div>
    </div>
@endsection

