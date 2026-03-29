<x-guest-layout>
    <h2 class="text-xl font-semibold text-slate-900 mb-1 text-center">Tạo tài khoản mới</h2>
    <p class="text-sm text-slate-500 mb-5 text-center">Lưu thông tin giao hàng và quản lý tất cả đơn hoa của bạn tại một
        nơi.</p>

    <form method="POST" action="{{ route('register') }}" class="space-y-4">
        @csrf

        <!-- Name -->
        <div>
            <x-input-label for="name" :value="__('Name')" />
            <x-text-input id="name" class="block mt-1 w-full" type="text" name="name" :value="old('name')" required
                autofocus autocomplete="name" />
            <x-input-error :messages="$errors->get('name')" class="mt-2" />
        </div>

        <!-- Email Address -->
        <div class="mt-4">
            <x-input-label for="email" :value="__('Email')" />
            <x-text-input id="email" class="block mt-1 w-full" type="email" name="email" :value="old('email')"
                required autocomplete="username" />
            <x-input-error :messages="$errors->get('email')" class="mt-2" />
        </div>

        <!-- Password -->
        <div class="mt-4">
            <x-input-label for="password" :value="__('Password')" />

            <x-text-input id="password" class="block mt-1 w-full" type="password" name="password" required
                autocomplete="new-password" />

            <x-input-error :messages="$errors->get('password')" class="mt-2" />
        </div>

        <!-- Confirm Password -->
        <div class="mt-4">
            <x-input-label for="password_confirmation" :value="__('Confirm Password')" />

            <x-text-input id="password_confirmation" class="block mt-1 w-full" type="password"
                name="password_confirmation" required autocomplete="new-password" />

            <x-input-error :messages="$errors->get('password_confirmation')" class="mt-2" />
        </div>

        <div class="mt-4 flex flex-col gap-3">
            <x-primary-button class="w-full justify-center">
                {{ __('Register') }}
            </x-primary-button>

            <p class="text-xs text-center text-slate-500">
                Đã có tài khoản?
                <a href="{{ route('login') }}" class="text-pink-600 hover:text-pink-700 font-medium">
                    Đăng nhập
                </a>
            </p>
        </div>
    </form>
</x-guest-layout>
