@extends('admin.layouts.app')

@section('title', 'Quản lý bài viết')

@section('content')
    <div class="admin-page admin-container">
        <div class="admin-page-header">
            <div>
                <div class="admin-page-title">Quản lý bài viết</div>
                <div class="admin-page-subtitle">Quản lý bài viết tin tức, hướng dẫn.</div>
            </div>
            <div class="admin-actions">
                <a href="{{ route('admin.posts.create') }}" class="admin-btn admin-btn-accent">+ Thêm bài viết</a>
            </div>
        </div>

        <div class="admin-card overflow-hidden">
            <div class="px-5 py-4 admin-accent-bg" style="border-bottom: 1px solid var(--admin-border);">
                <h2 class="font-bold flex items-center gap-2" style="color: var(--admin-dark);">
                <span class="w-9 h-9 rounded-lg admin-accent-bg-strong flex items-center justify-center text-base">📰</span>
                Danh sách bài viết
                </h2>
            </div>
            <div class="overflow-x-auto w-full">
                <table class="admin-table">
                    <thead>
                        <tr>
                            <th style="width: 36%;">Tiêu đề</th>
                            <th style="width: 24%;">Slug</th>
                            <th style="width: 14%; text-align:center;">Trạng thái</th>
                            <th style="width: 14%; text-align:right;">Ngày đăng</th>
                            <th style="width: 12%; text-align:right;">Thao tác</th>
                        </tr>
                    </thead>
                    <tbody>
                        @forelse($posts as $post)
                            <tr>
                                <td>
                                    <div class="font-semibold">{{ $post->title }}</div>
                                    @if($post->excerpt)
                                        <div class="text-xs text-gray-500 line-clamp-1 mt-0.5">{{ $post->excerpt }}</div>
                                    @endif
                                </td>
                                <td class="font-mono text-xs text-gray-500">{{ $post->slug }}</td>
                                <td style="text-align:center;">
                                    <span class="admin-badge {{ $post->is_published ? 'admin-badge-success' : 'admin-badge-muted' }}">{{ $post->is_published ? 'Đã xuất bản' : 'Bản nháp' }}</span>
                                </td>
                                <td style="text-align:right;" class="text-xs text-gray-500">{{ optional($post->published_at)->format('d/m/Y H:i') }}</td>
                                <td style="text-align:right;">
                                    <div class="admin-actions" style="justify-content:flex-end;">
                                        <a href="{{ route('admin.posts.edit', $post) }}" class="admin-btn admin-btn-sm admin-btn-outline">Sửa</a>
                                        <form action="{{ route('admin.posts.destroy', $post) }}" method="POST" class="inline">
                                            @csrf
                                            @method('DELETE')
                                            <button type="submit" class="admin-btn admin-btn-sm" style="background:#dc2626;" onclick="return confirm('Xóa bài viết này?')">Xóa</button>
                                        </form>
                                    </div>
                                </td>
                            </tr>
                        @empty
                            <tr>
                                <td colspan="5" style="text-align:center; padding: 2rem 1rem;" class="text-sm text-gray-500">Chưa có bài viết nào.</td>
                            </tr>
                        @endforelse
                    </tbody>
                </table>
            </div>
        </div>

        <div class="mt-4">
            {{ $posts->links() }}
        </div>
    </div>
@endsection

