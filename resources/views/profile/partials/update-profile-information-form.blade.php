<section>
    {{-- Header --}}
    <div class="bg-gradient-to-r from-indigo-500 to-purple-600 px-8 py-6">
        <div class="flex items-center">
            <div class="h-12 w-12 bg-white/20 rounded-xl flex items-center justify-center mr-4">
                <svg class="h-6 w-6 text-white" fill="none" stroke="currentColor" viewBox="0 0 24 24">
                    <path stroke-linecap="round" stroke-linejoin="round" stroke-width="2"
                        d="M16 7a4 4 0 11-8 0 4 4 0 018 0zM12 14a7 7 0 00-7 7h14a7 7 0 00-7-7z"></path>
                </svg>
            </div>
            <div>
                <h2 class="text-xl font-bold text-white">{{ __('Thông tin hồ sơ') }}</h2>
                <p class="text-indigo-100 text-sm mt-0.5">{{ __('Cập nhật thông tin tài khoản và email của bạn.') }}</p>
            </div>
        </div>
    </div>

    {{-- Form --}}
    <form id="send-verification" method="post" action="{{ route('verification.send') }}">
        @csrf
    </form>

    <form method="post" action="{{ route('profile.update') }}" class="p-8">
        @csrf
        @method('patch')

        <div class="space-y-6">
            {{-- Họ và tên --}}
            <div>
                <label class="block text-sm font-semibold text-gray-700 mb-2">{{ __('Họ và tên') }}</label>
                <input type="text" id="name" name="name"
                    class="w-full px-4 py-3 rounded-xl border border-gray-300 bg-gray-50 focus:bg-white focus:border-indigo-500 focus:ring-2 focus:ring-indigo-200 transition duration-200"
                    value="{{ old('name', $user->name) }}" required autofocus autocomplete="name" />
                <x-input-error class="mt-2 text-sm text-red-600" :messages="$errors->get('name')" />
            </div>

            {{-- Địa chỉ email --}}
            <div>
                <label class="block text-sm font-semibold text-gray-700 mb-2">{{ __('Địa chỉ email') }}</label>
                <input type="email" id="email" name="email"
                    class="w-full px-4 py-3 rounded-xl border border-gray-300 bg-gray-50 focus:bg-white focus:border-indigo-500 focus:ring-2 focus:ring-indigo-200 transition duration-200"
                    value="{{ old('email', $user->email) }}" required autocomplete="username" />
                <x-input-error class="mt-2 text-sm text-red-600" :messages="$errors->get('email')" />

                @if ($user instanceof \Illuminate\Contracts\Auth\MustVerifyEmail && !$user->hasVerifiedEmail())
                    <div class="mt-3 p-3 bg-amber-50 rounded-lg border border-amber-200">
                        <p class="text-sm text-amber-800">
                            {{ __('Email của bạn chưa được xác thực.') }}
                            <button form="send-verification"
                                class="underline text-amber-600 hover:text-amber-700 font-medium ml-1">
                                {{ __('Gửi lại email xác thực') }}
                            </button>
                        </p>
                        @if (session('status') === 'verification-link-sent')
                            <p class="mt-2 text-sm text-green-600">
                                ✅ {{ __('Một link xác thực mới đã được gửi đến email của bạn.') }}
                            </p>
                        @endif
                    </div>
                @endif
            </div>

            {{-- Nút lưu --}}
            <div class="flex items-center justify-between pt-4 border-t border-gray-100">
                <button type="submit" <button type="submit"
                    class="inline-flex items-center px-6 py-3 bg-green-600 hover:bg-green-700 !text-white font-semibold rounded-xl shadow-md transition duration-200 transform hover:scale-[1.02] focus:outline-none focus:ring-2 focus:ring-green-500 focus:ring-offset-2">
                    <svg class="h-5 w-5 mr-2" fill="none" stroke="currentColor" viewBox="0 0 24 24">
                        <path stroke-linecap="round" stroke-linejoin="round" stroke-width="2"
                            d="M4 16v1a3 3 0 003 3h10a3 3 0 003-3v-1m-4-8l-4-4m0 0L8 8m4-4v12"></path>
                    </svg>
                    {{ __('Lưu thay đổi') }}
                </button>

                @if (session('status') === 'profile-updated')
                    <p x-data="{ show: true }" x-show="show" x-transition.duration.300ms x-init="setTimeout(() => show = false, 3000)"
                        class="flex items-center text-sm text-green-600 bg-green-50 px-4 py-2 rounded-lg">
                        <svg class="h-4 w-4 mr-1" fill="none" stroke="currentColor" viewBox="0 0 24 24">
                            <path stroke-linecap="round" stroke-linejoin="round" stroke-width="2" d="M5 13l4 4L19 7">
                            </path>
                        </svg>
                        {{ __('Đã lưu thành công!') }}
                    </p>
                @endif
            </div>
        </div>
    </form>
</section>
