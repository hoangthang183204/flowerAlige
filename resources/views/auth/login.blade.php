<x-guest-layout>
    <h2 class="text-xl font-semibold text-slate-900 mb-1 text-center">Đăng nhập tài khoản</h2>
    <p class="text-sm text-slate-500 mb-5 text-center">Đăng nhập để đặt hoa nhanh hơn và theo dõi đơn hàng của bạn.</p>

    <!-- Session Status -->
    <x-auth-session-status class="mb-4" :status="session('status')" />

    <form method="POST" action="{{ route('login') }}" class="space-y-4">
        @csrf

        <!-- Email Address -->
        <div>
            <x-input-label for="email" :value="__('Email')" />
            <x-text-input id="email" class="block mt-1 w-full" type="email" name="email" :value="old('email')" required
                autofocus autocomplete="username" />
            <x-input-error :messages="$errors->get('email')" class="mt-2" />
        </div>

        <!-- Password -->
        <div class="mt-4">
            <x-input-label for="password" :value="__('Password')" />

            <x-text-input id="password" class="block mt-1 w-full" type="password" name="password" required
                autocomplete="current-password" />

            <x-input-error :messages="$errors->get('password')" class="mt-2" />
        </div>

        <!-- Remember Me -->
        <div class="flex items-center justify-between pt-1">
            <label for="remember_me" class="inline-flex items-center">
                <input id="remember_me" type="checkbox"
                    class="rounded border-gray-300 text-indigo-600 shadow-sm focus:ring-indigo-500" name="remember">
                <span class="ms-2 text-sm text-gray-600">{{ __('Remember me') }}</span>
            </label>
            @if (Route::has('password.request'))
                <a class="text-xs text-pink-600 hover:text-pink-700 font-medium" href="{{ route('password.request') }}">
                    {{ __('Forgot your password?') }}
                </a>
            @endif
        </div>

        <div class="mt-4 flex flex-col gap-3">
            <x-primary-button class="w-full justify-center">
                {{ __('Đăng Nhập') }}
            </x-primary-button>

            @if (Route::has('register'))
                <p class="text-xs text-center text-slate-500">
                    Chưa có tài khoản?
                    <a href="{{ route('register') }}" class="text-pink-600 hover:text-pink-700 font-medium">
                        Đăng ký ngay
                    </a>
                </p>
            @endif
        </div>
    </form>
</x-guest-layout>
