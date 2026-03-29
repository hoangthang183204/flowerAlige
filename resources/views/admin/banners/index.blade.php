@extends('admin.layouts.app')

@section('title', 'Quản lý banner')

@section('content')
    <div class="admin-page admin-container">
        <div class="admin-page-header">
            <div>
                <div class="admin-page-title">Quản lý banner</div>
                <div class="admin-page-subtitle">Quản lý banner hiển thị ở trang chủ.</div>
            </div>
            <div class="admin-actions">
                <a href="{{ route('admin.banners.create') }}" class="admin-btn admin-btn-accent">+ Thêm banner</a>
            </div>
        </div>

        <div class="admin-card overflow-hidden">
            <div class="px-5 py-4 admin-accent-bg" style="border-bottom: 1px solid var(--admin-border);">
                <h2 class="font-bold flex items-center gap-2" style="color: var(--admin-dark);">
                <span class="w-9 h-9 rounded-lg admin-accent-bg-strong flex items-center justify-center text-base">🏷️</span>
                Danh sách banner
                </h2>
            </div>
            <div class="overflow-x-auto w-full">
                <table class="admin-table">
                    <thead>
                        <tr>
                            <th style="width: 34%;">Banner</th>
                            <th style="width: 30%;">Liên kết</th>
                            <th style="width: 10%; text-align:center;">Vị trí</th>
                            <th style="width: 10%; text-align:center;">Trạng thái</th>
                            <th style="width: 16%; text-align:right;">Thao tác</th>
                        </tr>
                    </thead>
                    <tbody>
                        @forelse($banners as $banner)
                            <tr>
                                <td>
                                    <div class="flex items-center gap-3">
                                        <div class="rounded-lg overflow-hidden bg-slate-100 flex-shrink-0" style="width:140px;height:60px;">
                                            <img src="{{ asset($banner->image_path) }}" alt="{{ $banner->title }}" style="width:100%;height:100%;object-fit:cover;">
                                        </div>
                                        <span class="font-semibold">{{ $banner->title }}</span>
                                    </div>
                                </td>
                                <td class="text-xs text-gray-500">{{ $banner->link_url ?: '—' }}</td>
                                <td style="text-align:center;">{{ $banner->position }}</td>
                                <td style="text-align:center;">
                                    <span class="admin-badge {{ $banner->is_active ? 'admin-badge-success' : 'admin-badge-muted' }}">{{ $banner->is_active ? 'Hiển thị' : 'Ẩn' }}</span>
                                </td>
                                <td style="text-align:right;">
                                    <div class="admin-actions" style="justify-content:flex-end;">
                                        <a href="{{ route('admin.banners.edit', $banner) }}" class="admin-btn admin-btn-sm admin-btn-outline">Sửa</a>
                                        <form action="{{ route('admin.banners.destroy', $banner) }}" method="POST" class="inline">
                                            @csrf
                                            @method('DELETE')
                                            <button type="submit" class="admin-btn admin-btn-sm" style="background:#dc2626;" onclick="return confirm('Xóa banner này?')">Xóa</button>
                                        </form>
                                    </div>
                                </td>
                            </tr>
                        @empty
                            <tr>
                                <td colspan="5" style="text-align:center; padding: 2rem 1rem;" class="text-sm text-gray-500">Chưa có banner nào.</td>
                            </tr>
                        @endforelse
                    </tbody>
                </table>
            </div>
        </div>
    </div>
@endsection

