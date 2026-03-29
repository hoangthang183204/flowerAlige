@extends('admin.layouts.app')

@section('title', 'Sửa tài khoản khách hàng')

@section('content')
    <div class="admin-page admin-container">
        <div class="admin-page-header">
            <div>
                <div class="admin-page-title">Sửa tài khoản</div>
                <div class="admin-page-subtitle">Cập nhật thông tin và phân quyền: <strong>{{ $user->name }}</strong>.</div>
            </div>
        </div>

        <div class="admin-card overflow-hidden">
            <div class="px-5 py-4 admin-accent-bg" style="border-bottom: 1px solid var(--admin-border);">
                <h2 class="font-bold flex items-center gap-2" style="color: var(--admin-dark);">
                    <span class="w-9 h-9 rounded-lg admin-accent-bg-strong flex items-center justify-center text-base">👤</span>
                    Thông tin tài khoản
                </h2>
            </div>

            <form action="{{ route('admin.users.update', $user) }}" method="POST" class="admin-form">
                @csrf
                @method('PUT')

                <div class="grid grid-cols-1 md:grid-cols-2 gap-4">
                    <div class="admin-field">
                        <label class="admin-label">Họ tên *</label>
                        <input type="text" name="name" value="{{ old('name', $user->name) }}" class="admin-input" required>
                        @error('name')
                            <div class="admin-error">{{ $message }}</div>
                        @enderror
                    </div>

                    <div class="admin-field">
                        <label class="admin-label">Email *</label>
                        <input type="email" name="email" value="{{ old('email', $user->email) }}" class="admin-input" required>
                        @error('email')
                            <div class="admin-error">{{ $message }}</div>
                        @enderror
                    </div>
                </div>

                <div class="admin-field mt-4">
                    <label class="admin-label">Số điện thoại</label>
                    <input type="text" name="phone" value="{{ old('phone', $user->phone) }}" class="admin-input">
                    @error('phone')
                        <div class="admin-error">{{ $message }}</div>
                    @enderror
                </div>

                <div class="flex items-center gap-2 mt-4">
                    <input type="checkbox" name="is_admin" value="1" id="is_admin" class="rounded border-gray-300" {{ old('is_admin', $user->is_admin) ? 'checked' : '' }}>
                    <label for="is_admin" class="text-sm text-gray-700">Tài khoản quản trị (Admin)</label>
                </div>

                <div class="admin-form-footer">
                    <button type="submit" class="admin-btn admin-btn-accent">Lưu</button>
                    <a href="{{ route('admin.users.index') }}" class="admin-btn admin-btn-outline">Hủy</a>
                </div>
            </form>
        </div>
    </div>
@endsection

