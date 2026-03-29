<section>
    {{-- Header --}}
    <div class="bg-gradient-to-r from-red-500 to-red-600 px-8 py-6">
        <div class="flex items-center">
            <div class="h-12 w-12 bg-white/20 rounded-xl flex items-center justify-center mr-4">
                <svg class="h-6 w-6 text-white" fill="none" stroke="currentColor" viewBox="0 0 24 24">
                    <path stroke-linecap="round" stroke-linejoin="round" stroke-width="2" d="M19 7l-.867 12.142A2 2 0 0116.138 21H7.862a2 2 0 01-1.995-1.858L5 7m5 4v6m4-6v6m1-10V4a1 1 0 00-1-1h-4a1 1 0 00-1 1v3M4 7h16"></path>
                </svg>
            </div>
            <div>
                <h2 class="text-xl font-bold text-white">{{ __('Xóa tài khoản') }}</h2>
                <p class="text-red-100 text-sm mt-0.5">{{ __('Hành động này không thể hoàn tác') }}</p>
            </div>
        </div>
    </div>

    {{-- Nội dung --}}
    <div class="p-8">
        <div class="mb-6 p-4 bg-amber-50 rounded-xl border border-amber-200">
            <div class="flex">
                <svg class="h-5 w-5 text-amber-600 mt-0.5 mr-3" fill="none" stroke="currentColor" viewBox="0 0 24 24">
                    <path stroke-linecap="round" stroke-linejoin="round" stroke-width="2" d="M12 8v4m0 4h.01M21 12a9 9 0 11-18 0 9 9 0 0118 0z"></path>
                </svg>
                <div class="text-sm text-amber-800">
                    <p class="font-medium">Lưu ý quan trọng:</p>
                    <ul class="mt-1 list-disc list-inside space-y-1">
                        <li>Tất cả dữ liệu cá nhân sẽ bị xóa vĩnh viễn</li>
                        <li>Lịch sử đơn hàng sẽ không thể khôi phục</li>
                        <li>Email và số điện thoại có thể được sử dụng lại để đăng ký mới</li>
                    </ul>
                </div>
            </div>
        </div>

        <p class="text-sm text-gray-600 mb-6">
            {{ __('Trước khi xóa tài khoản, vui lòng tải xuống bất kỳ dữ liệu nào bạn muốn giữ lại. Sau khi xóa, tất cả sẽ biến mất vĩnh viễn.') }}
        </p>

        <button x-data="" x-on:click.prevent="$dispatch('open-modal', 'confirm-user-deletion')" class="inline-flex items-center px-6 py-3 bg-red-600 hover:bg-red-700 text-white font-semibold rounded-xl shadow-md transition duration-200 transform hover:scale-[1.02] focus:outline-none focus:ring-2 focus:ring-red-500 focus:ring-offset-2">
            <svg class="h-5 w-5 mr-2" fill="none" stroke="currentColor" viewBox="0 0 24 24">
                <path stroke-linecap="round" stroke-linejoin="round" stroke-width="2" d="M19 7l-.867 12.142A2 2 0 0116.138 21H7.862a2 2 0 01-1.995-1.858L5 7m5 4v6m4-6v6m1-10V4a1 1 0 00-1-1h-4a1 1 0 00-1 1v3M4 7h16"></path>
            </svg>
            {{ __('Xóa tài khoản') }}
        </button>
    </div>

    {{-- Modal xác nhận xóa --}}
    <x-modal name="confirm-user-deletion" :show="$errors->userDeletion->isNotEmpty()" focusable>
        <form method="post" action="{{ route('profile.destroy') }}" class="relative">
            @csrf
            @method('delete')

            <div class="p-6">
                <div class="mx-auto flex h-14 w-14 items-center justify-center rounded-full bg-red-100 mb-4">
                    <svg class="h-7 w-7 text-red-600" fill="none" stroke="currentColor" viewBox="0 0 24 24">
                        <path stroke-linecap="round" stroke-linejoin="round" stroke-width="2" d="M12 9v2m0 4h.01m-6.938 4h13.856c1.54 0 2.502-1.667 1.732-3L13.732 4c-.77-1.333-2.694-1.333-3.464 0L3.34 16c-.77 1.333.192 3 1.732 3z"></path>
                    </svg>
                </div>

                <h2 class="text-center text-xl font-bold text-gray-900 mb-2">
                    {{ __('Bạn có chắc chắn muốn xóa tài khoản?') }}
                </h2>

                <p class="text-center text-sm text-gray-500 mb-6">
                    {{ __('Hành động này sẽ xóa vĩnh viễn tài khoản của bạn. Vui lòng nhập mật khẩu để xác nhận.') }}
                </p>

                <div class="mb-6">
                    <label for="password" class="block text-sm font-medium text-gray-700 mb-1">
                        {{ __('Mật khẩu') }}
                    </label>
                    <input id="password" name="password" type="password" class="w-full rounded-xl border-gray-300 focus:border-red-500 focus:ring-red-500" placeholder="Nhập mật khẩu của bạn" />
                    <x-input-error :messages="$errors->userDeletion->get('password')" class="mt-2 text-sm text-red-600" />
                </div>

                <div class="flex justify-end space-x-3">
                    <button type="button" x-on:click="$dispatch('close')" class="px-5 py-2.5 bg-gray-100 hover:bg-gray-200 text-gray-700 text-sm font-medium rounded-lg transition duration-200">
                        {{ __('Hủy') }}
                    </button>
                    <button type="submit" class="px-5 py-2.5 bg-red-600 hover:bg-red-700 text-white text-sm font-medium rounded-lg shadow-sm transition duration-200">
                        <svg class="h-4 w-4 inline mr-1" fill="none" stroke="currentColor" viewBox="0 0 24 24">
                            <path stroke-linecap="round" stroke-linejoin="round" stroke-width="2" d="M19 7l-.867 12.142A2 2 0 0116.138 21H7.862a2 2 0 01-1.995-1.858L5 7m5 4v6m4-6v6m1-10V4a1 1 0 00-1-1h-4a1 1 0 00-1 1v3M4 7h16"></path>
                        </svg>
                        {{ __('Xóa vĩnh viễn') }}
                    </button>
                </div>
            </div>
        </form>
    </x-modal>
</section>