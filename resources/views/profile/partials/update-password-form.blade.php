<section>
    {{-- Header --}}
    <div class="bg-gradient-to-r from-blue-600 to-blue-700 px-8 py-6">
        <div class="flex items-center">
            <div class="h-12 w-12 bg-white/20 rounded-xl flex items-center justify-center mr-4">
                <svg class="h-6 w-6 text-white" fill="none" stroke="currentColor" viewBox="0 0 24 24">
                    <path stroke-linecap="round" stroke-linejoin="round" stroke-width="2"
                        d="M12 15v2m-6 4h12a2 2 0 002-2v-6a2 2 0 00-2-2H6a2 2 0 00-2 2v6a2 2 0 002 2zm10-10V7a4 4 0 00-8 0v4h8z">
                    </path>
                </svg>
            </div>
            <div>
                <h2 class="text-xl font-bold text-white">{{ __('Cập nhật mật khẩu') }}</h2>
                <p class="text-blue-100 text-sm mt-0.5">
                    {{ __('Đảm bảo tài khoản của bạn sử dụng mật khẩu mạnh để bảo mật.') }}</p>
            </div>
        </div>
    </div>

    {{-- Form --}}
    <form method="post" action="{{ route('password.update') }}" class="p-8">
        @csrf
        @method('put')

        <div class="space-y-6">
            {{-- Mật khẩu hiện tại --}}
            <div>
                <label class="block text-sm font-semibold text-gray-700 mb-2">{{ __('Mật khẩu hiện tại') }}</label>
                <input type="password" name="current_password"
                    class="w-full px-4 py-3 rounded-xl border border-gray-300 bg-gray-50 focus:bg-white focus:border-blue-500 focus:ring-2 focus:ring-blue-200 transition duration-200"
                    placeholder="Nhập mật khẩu hiện tại" />
                <x-input-error :messages="$errors->updatePassword->get('current_password')" class="mt-2 text-sm text-red-600" />
            </div>

            {{-- Mật khẩu mới --}}
            <div>
                <label class="block text-sm font-semibold text-gray-700 mb-2">{{ __('Mật khẩu mới') }}</label>
                <input type="password" name="password"
                    class="w-full px-4 py-3 rounded-xl border border-gray-300 bg-gray-50 focus:bg-white focus:border-blue-500 focus:ring-2 focus:ring-blue-200 transition duration-200"
                    placeholder="Nhập mật khẩu mới" />
                <div class="flex items-center mt-2 text-xs text-amber-600">
                    <svg class="h-3 w-3 mr-1" fill="none" stroke="currentColor" viewBox="0 0 24 24">
                        <path stroke-linecap="round" stroke-linejoin="round" stroke-width="2"
                            d="M13 16h-1v-4h-1m1-4h.01M21 12a9 9 0 11-18 0 9 9 0 0118 0z"></path>
                    </svg>
                    <span>{{ __('Trường này bắt buộc cho mục đích bảo mật.') }}</span>
                </div>
                <x-input-error :messages="$errors->updatePassword->get('password')" class="mt-2 text-sm text-red-600" />
            </div>

            {{-- Xác nhận mật khẩu mới --}}
            <div>
                <label class="block text-sm font-semibold text-gray-700 mb-2">{{ __('Xác nhận mật khẩu mới') }}</label>
                <input type="password" name="password_confirmation"
                    class="w-full px-4 py-3 rounded-xl border border-gray-300 bg-gray-50 focus:bg-white focus:border-blue-500 focus:ring-2 focus:ring-blue-200 transition duration-200"
                    placeholder="Nhập lại mật khẩu mới" />
                <x-input-error :messages="$errors->updatePassword->get('password_confirmation')" class="mt-2 text-sm text-red-600" />
            </div>

            {{-- Nút lưu --}}
            <div class="flex items-center justify-between pt-4 border-t border-gray-100">
                <button type="submit"
                    class="inline-flex items-center px-6 py-3 bg-blue-600 text-white !text-white font-semibold rounded-xl">
                    <svg class="h-5 w-5 mr-2" fill="none" stroke="currentColor" viewBox="0 0 24 24">
                        <path stroke-linecap="round" stroke-linejoin="round" stroke-width="2"
                            d="M4 16v1a3 3 0 003 3h10a3 3 0 003-3v-1m-4-8l-4-4m0 0L8 8m4-4v12"></path>
                    </svg>
                    {{ __('Lưu mật khẩu') }}
                </button>

                @if (session('status') === 'password-updated')
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
