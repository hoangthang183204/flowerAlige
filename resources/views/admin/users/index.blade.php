@extends('admin.layouts.app')

@section('title', 'Quản lý tài khoản khách hàng')

@section('content')
    <div class="admin-page admin-container">
        <div class="admin-page-header">
            <div>
                <div class="admin-page-title">Quản lý tài khoản</div>
                <div class="admin-page-subtitle">Quản lý tài khoản khách hàng và admin.</div>
            </div>
        </div>

        <div class="admin-card overflow-hidden">
            <div class="px-5 py-4 admin-accent-bg" style="border-bottom: 1px solid var(--admin-border);">
                <h2 class="font-bold flex items-center gap-2" style="color: var(--admin-dark);">
                <span class="w-9 h-9 rounded-lg admin-accent-bg-strong flex items-center justify-center text-base">👤</span>
                Danh sách tài khoản
                </h2>
            </div>
            <div class="overflow-x-auto w-full">
                <table class="admin-table">
                    <thead>
                        <tr>
                            <th style="width: 24%;">Tên</th>
                            <th style="width: 34%;">Email</th>
                            <th style="width: 16%;">SĐT</th>
                            <th style="width: 10%; text-align:center;">Vai trò</th>
                            <th style="width: 16%; text-align:right;">Thao tác</th>
                        </tr>
                    </thead>
                    <tbody>
                        @forelse($users as $user)
                            <tr>
                                <td>
                                    <div class="font-semibold">{{ $user->name }}</div>
                                    <div class="text-xs text-gray-500 mt-0.5">ID: {{ $user->id }}</div>
                                </td>
                                <td>{{ $user->email }}</td>
                                <td>{{ $user->phone ?? '—' }}</td>
                                <td style="text-align:center;">
                                    <span class="admin-badge {{ $user->is_admin ? 'admin-badge-success' : 'admin-badge-muted' }}">{{ $user->is_admin ? 'Admin' : 'KH' }}</span>
                                </td>
                                <td style="text-align:right;">
                                    <div class="admin-actions" style="justify-content:flex-end;">
                                        <a href="{{ route('admin.users.edit', $user) }}" class="admin-btn admin-btn-sm admin-btn-outline">Sửa</a>
                                        @if(auth()->id() !== $user->id)
                                            <form action="{{ route('admin.users.destroy', $user) }}" method="POST" class="inline">
                                                @csrf
                                                @method('DELETE')
                                                <button type="submit" class="admin-btn admin-btn-sm" style="background:#dc2626;" onclick="return confirm('Xóa tài khoản này?')">Xóa</button>
                                            </form>
                                        @endif
                                    </div>
                                </td>
                            </tr>
                        @empty
                            <tr>
                                <td colspan="5" style="text-align:center; padding: 2rem 1rem;" class="text-sm text-gray-500">Chưa có tài khoản nào.</td>
                            </tr>
                        @endforelse
                    </tbody>
                </table>
            </div>
        </div>

        <div class="mt-3">
            {{ $users->links() }}
        </div>
    </div>
@endsection

