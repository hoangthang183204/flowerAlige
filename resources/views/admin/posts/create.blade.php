@extends('admin.layouts.app')

@section('title', 'Thêm bài viết')

@section('content')
    <div class="admin-page admin-container">
        <div class="admin-page-header">
            <div>
                <div class="admin-page-title">Thêm bài viết</div>
                <div class="admin-page-subtitle">Soạn bài viết tin tức/hướng dẫn hiển thị trên website.</div>
            </div>
        </div>

        <div class="admin-card overflow-hidden">
            <div class="px-5 py-4 admin-accent-bg" style="border-bottom: 1px solid var(--admin-border);">
                <h2 class="font-bold flex items-center gap-2" style="color: var(--admin-dark);">
                    <span class="w-9 h-9 rounded-lg admin-accent-bg-strong flex items-center justify-center text-base">✍️</span>
                    Nội dung bài viết
                </h2>
            </div>

            <form action="{{ route('admin.posts.store') }}" method="POST" class="admin-form">
                @csrf

                <div class="admin-field">
                    <label class="admin-label">Tiêu đề *</label>
                    <input type="text" name="title" value="{{ old('title') }}" class="admin-input" required>
                    @error('title')
                        <div class="admin-error">{{ $message }}</div>
                    @enderror
                </div>

                <div class="admin-field mt-4">
                    <label class="admin-label">Mô tả ngắn</label>
                    <textarea name="excerpt" rows="3" class="admin-textarea">{{ old('excerpt') }}</textarea>
                </div>

                <div class="admin-field mt-4">
                    <label class="admin-label">Nội dung *</label>
                    <textarea name="content" rows="10" class="admin-textarea" required>{{ old('content') }}</textarea>
                    @error('content')
                        <div class="admin-error">{{ $message }}</div>
                    @enderror
                </div>

                <div class="flex items-center gap-2 mt-4">
                    <input type="checkbox" name="is_published" value="1" id="is_published" class="rounded border-gray-300">
                    <label for="is_published" class="text-sm text-gray-700">Xuất bản ngay</label>
                </div>

                <div class="admin-form-footer">
                    <button type="submit" class="admin-btn admin-btn-accent">Lưu</button>
                    <a href="{{ route('admin.posts.index') }}" class="admin-btn admin-btn-outline">Hủy</a>
                </div>
            </form>
        </div>
    </div>
@endsection

