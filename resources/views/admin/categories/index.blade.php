@extends('admin.layouts.app')

@section('title', 'Quản lý danh mục')

@section('content')
    <div class="admin-page admin-container">
        <div class="admin-page-header">
            <div>
                <div class="admin-page-title">Quản lý danh mục</div>
                <div class="admin-page-subtitle">Quản lý các nhóm hoa như sinh nhật, khai trương...</div>
            </div>
            <div class="admin-actions">
                <a href="{{ route('admin.categories.create') }}" class="admin-btn admin-btn-accent">+ Thêm danh mục</a>
            </div>
        </div>

        <div class="admin-card overflow-hidden">
            <div class="px-5 py-4 admin-accent-bg" style="border-bottom: 1px solid var(--admin-border);">
                <h2 class="font-bold flex items-center gap-2" style="color: var(--admin-dark);">
                <span class="w-9 h-9 rounded-lg admin-accent-bg-strong flex items-center justify-center text-base">📂</span>
                Danh sách danh mục
                </h2>
            </div>
            <div class="overflow-x-auto w-full">
                <table class="admin-table">
                    <thead>
                        <tr>
                            <th style="width: 30%;">Tên</th>
                            <th style="width: 26%;">Slug</th>
                            <th style="width: 14%; text-align:center;">Trạng thái</th>
                            <th style="width: 10%; text-align:center;">Đã xóa?</th>
                            <th style="width: 20%; text-align:right;">Thao tác</th>
                        </tr>
                    </thead>
                    <tbody>
                        @forelse($categories as $category)
                            <tr>
                                <td><span class="font-semibold">{{ $category->name }}</span></td>
                                <td class="font-mono text-xs text-gray-500">{{ $category->slug }}</td>
                                <td style="text-align:center;">
                                    @if(!$category->deleted_at)
                                        <span class="admin-badge {{ $category->is_active ? 'admin-badge-success' : 'admin-badge-muted' }}">{{ $category->is_active ? 'Hiển thị' : 'Ẩn' }}</span>
                                    @else
                                        <span class="admin-badge admin-badge-warning">Đã xóa</span>
                                    @endif
                                </td>
                                <td style="text-align:center;">
                                    <span class="admin-badge {{ $category->deleted_at ? 'admin-badge-warning' : 'admin-badge-muted' }}">{{ $category->deleted_at ? 'Có' : 'Không' }}</span>
                                </td>
                                <td style="text-align:right;">
                                    <div class="admin-actions" style="justify-content: flex-end;">
                                        @if(!$category->deleted_at)
                                            <a href="{{ route('admin.categories.edit', $category) }}" class="admin-btn admin-btn-sm admin-btn-outline">Sửa</a>
                                            <form action="{{ route('admin.categories.destroy', $category) }}" method="POST" class="inline">
                                                @csrf
                                                @method('DELETE')
                                                <button type="submit" class="admin-btn admin-btn-sm" style="background: #dc2626;" onclick="return confirm('Xóa tạm thời danh mục này?')">Xóa mềm</button>
                                            </form>
                                        @else
                                            <form action="{{ route('admin.categories.restore', $category->id) }}" method="POST" class="inline">
                                                @csrf
                                                <button type="submit" class="admin-btn admin-btn-sm" style="background: #059669;">Khôi phục</button>
                                            </form>
                                            <form action="{{ route('admin.categories.force-delete', $category->id) }}" method="POST" class="inline">
                                                @csrf
                                                @method('DELETE')
                                                <button type="submit" class="admin-btn admin-btn-sm" style="background: #64748b;" onclick="return confirm('Xóa vĩnh viễn danh mục này?')">Xóa cứng</button>
                                            </form>
                                        @endif
                                    </div>
                                </td>
                            </tr>
                        @empty
                            <tr>
                                <td colspan="5" style="text-align:center; padding: 2.5rem 1rem;">
                                    <div class="text-4xl mb-2" style="color: #9ca3af;">📂</div>
                                    <p class="font-semibold" style="color: var(--admin-dark);">Chưa có danh mục nào</p>
                                    <p class="text-xs mt-1 text-gray-500">Thêm danh mục để bắt đầu quản lý.</p>
                                    <a href="{{ route('admin.categories.create') }}" class="admin-btn admin-btn-outline mt-3">+ Thêm danh mục</a>
                                </td>
                            </tr>
                        @endforelse
                    </tbody>
                </table>
            </div>
        </div>

        @if($categories->hasPages())
            <div class="mt-6 flex justify-center">
                {{ $categories->links() }}
            </div>
        @endif
    </div>
@endsection
