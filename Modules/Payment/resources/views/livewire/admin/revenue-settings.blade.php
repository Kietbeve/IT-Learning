<div class="space-y-6 py-4">
    {{-- Header --}}
    <div>
        <h2 class="text-lg font-bold text-gray-900">Cài đặt hệ thống & Doanh thu</h2>
        <p class="text-sm text-gray-500 mt-1">Quản lý phí nền tảng, hạn mức rút tiền, giá gói VIP và các cài đặt chung</p>
    </div>

    <div class="rounded-2xl border border-gray-200 bg-white shadow-sm overflow-hidden">
        <div class="p-6">
            <div class="space-y-8">
                <div class="grid gap-6 lg:grid-cols-2">
                    <div>
                        <label class="block text-sm font-bold text-gray-900">Phí nền tảng (%)</label>
                        <p class="mt-1 text-xs text-gray-500">Phần doanh thu nền tảng giữ lại trên mỗi giao dịch</p>
                        <div class="mt-3 flex items-center gap-3">
                            <input type="number" wire:model="platformFeePercent" min="0" max="100"
                                   class="w-48 rounded-xl border-0 py-2.5 px-4 text-gray-900 shadow-sm ring-1 ring-inset ring-gray-300 focus:ring-2 focus:ring-inset focus:ring-blue-600 sm:text-sm sm:leading-6 transition-all">
                            <span class="text-sm font-semibold text-gray-600">%</span>
                        </div>
                        @error('platformFeePercent') <span class="text-xs font-semibold text-rose-600 mt-2 block">{{ $message }}</span> @enderror
                    </div>

                    <div>
                        <label class="block text-sm font-bold text-gray-900">Số tiền rút tối thiểu</label>
                        <p class="mt-1 text-xs text-gray-500">Số dư tối thiểu để contributor được gửi yêu cầu rút tiền</p>
                        <div class="mt-3 flex items-center gap-3">
                            <input type="number" wire:model="minPayoutAmount"
                                   class="w-48 rounded-xl border-0 py-2.5 px-4 text-gray-900 shadow-sm ring-1 ring-inset ring-gray-300 focus:ring-2 focus:ring-inset focus:ring-blue-600 sm:text-sm sm:leading-6 transition-all">
                            <span class="text-sm font-semibold text-gray-600">đ</span>
                        </div>
                        @error('minPayoutAmount') <span class="text-xs font-semibold text-rose-600 mt-2 block">{{ $message }}</span> @enderror
                    </div>
                </div>

                <div class="grid gap-6 border-t border-gray-100 pt-8 sm:grid-cols-3">
                    <div>
                        <label class="block text-sm font-bold text-gray-900">Giá gốc gói VIP</label>
                        <p class="mt-1 text-xs text-gray-500">Mức giá chưa giảm của gói VIP</p>
                        <div class="mt-3 flex items-center gap-3">
                            <input type="number" wire:model="vipPrice"
                                   class="w-full rounded-xl border-0 py-2.5 px-4 text-gray-900 shadow-sm ring-1 ring-inset ring-gray-300 focus:ring-2 focus:ring-inset focus:ring-blue-600 sm:text-sm sm:leading-6 transition-all">
                            <span class="text-sm font-semibold text-gray-600 shrink-0">đ</span>
                        </div>
                        @error('vipPrice') <span class="text-xs font-semibold text-rose-600 mt-2 block">{{ $message }}</span> @enderror
                    </div>
                    
                    <div>
                        <label class="block text-sm font-bold text-gray-900">Giá khuyến mãi VIP</label>
                        <p class="mt-1 text-xs text-gray-500">Mức giá thực tế user mua gói VIP</p>
                        <div class="mt-3 flex items-center gap-3">
                            <input type="number" wire:model="vipSalePrice"
                                   class="w-full rounded-xl border-0 py-2.5 px-4 text-gray-900 shadow-sm ring-1 ring-inset ring-gray-300 focus:ring-2 focus:ring-inset focus:ring-blue-600 sm:text-sm sm:leading-6 transition-all">
                            <span class="text-sm font-semibold text-gray-600 shrink-0">đ</span>
                        </div>
                        @error('vipSalePrice') <span class="text-xs font-semibold text-rose-600 mt-2 block">{{ $message }}</span> @enderror
                    </div>
                    
                    <div>
                        <label class="block text-sm font-bold text-gray-900">Số lượt tải (Quota)</label>
                        <p class="mt-1 text-xs text-gray-500">Số lượt tải tài liệu khi mua gói VIP</p>
                        <div class="mt-3 flex items-center gap-3">
                            <input type="number" wire:model="vipQuota"
                                   class="w-full rounded-xl border-0 py-2.5 px-4 text-gray-900 shadow-sm ring-1 ring-inset ring-gray-300 focus:ring-2 focus:ring-inset focus:ring-blue-600 sm:text-sm sm:leading-6 transition-all">
                            <span class="text-sm font-semibold text-gray-600 shrink-0">lượt</span>
                        </div>
                        @error('vipQuota') <span class="text-xs font-semibold text-rose-600 mt-2 block">{{ $message }}</span> @enderror
                    </div>
                </div>

                <div class="border-t border-gray-100 pt-8">
                    <label class="block text-sm font-bold text-gray-900 mb-4">Cài đặt chung hệ thống</label>
                    <div class="flex items-center justify-between p-4 bg-gray-50 rounded-xl border border-gray-200">
                        <div>
                            <p class="text-sm font-medium text-gray-900">Cho phép Contributor đăng tài liệu</p>
                            <p class="text-xs text-gray-500 mt-1">Tắt tính năng này sẽ chặn toàn bộ Contributor khỏi việc tải lên tài liệu mới.</p>
                        </div>
                        <label class="relative inline-flex items-center cursor-pointer">
                            <input type="checkbox" wire:model="allowContributorUpload" class="sr-only peer">
                            <div class="w-11 h-6 bg-gray-200 peer-focus:outline-none peer-focus:ring-4 peer-focus:ring-blue-300 rounded-full peer peer-checked:after:translate-x-full peer-checked:after:border-white after:content-[''] after:absolute after:top-[2px] after:left-[2px] after:bg-white after:border-gray-300 after:border after:rounded-full after:h-5 after:w-5 after:transition-all peer-checked:bg-blue-600"></div>
                        </label>
                    </div>
                </div>
            </div>
        <div class="border-t border-gray-100 px-6 py-6 mt-4">
            <h3 class="text-sm font-bold text-gray-900 mb-4">Giới hạn dung lượng tải lên (MB)</h3>
            <div class="grid gap-6 sm:grid-cols-3">
                <div>
                    <label class="block text-sm font-bold text-gray-900">Tài liệu chính</label>
                    <p class="mt-1 text-xs text-gray-500">Dung lượng tối đa cho file PDF/Word/Excel...</p>
                    <div class="mt-3 flex items-center gap-3">
                        <input type="number" wire:model="maxDocumentSizeMB"
                               class="w-full rounded-xl border-0 py-2.5 px-4 text-gray-900 shadow-sm ring-1 ring-inset ring-gray-300 focus:ring-2 focus:ring-inset focus:ring-blue-600 sm:text-sm sm:leading-6 transition-all">
                        <span class="text-sm font-semibold text-gray-600 shrink-0">MB</span>
                    </div>
                    @error('maxDocumentSizeMB') <span class="text-xs font-semibold text-rose-600 mt-2 block">{{ $message }}</span> @enderror
                </div>
                
                <div>
                    <label class="block text-sm font-bold text-gray-900">Ảnh bìa (Thumbnail)</label>
                    <p class="mt-1 text-xs text-gray-500">Dung lượng tối đa cho ảnh bìa tài liệu</p>
                    <div class="mt-3 flex items-center gap-3">
                        <input type="number" wire:model="maxThumbnailSizeMB"
                               class="w-full rounded-xl border-0 py-2.5 px-4 text-gray-900 shadow-sm ring-1 ring-inset ring-gray-300 focus:ring-2 focus:ring-inset focus:ring-blue-600 sm:text-sm sm:leading-6 transition-all">
                        <span class="text-sm font-semibold text-gray-600 shrink-0">MB</span>
                    </div>
                    @error('maxThumbnailSizeMB') <span class="text-xs font-semibold text-rose-600 mt-2 block">{{ $message }}</span> @enderror
                </div>

                <div>
                    <label class="block text-sm font-bold text-gray-900">Ảnh mô tả (Gallery)</label>
                    <p class="mt-1 text-xs text-gray-500">Dung lượng tối đa mỗi ảnh trong bộ sưu tập</p>
                    <div class="mt-3 flex items-center gap-3">
                        <input type="number" wire:model="maxGallerySizeMB"
                               class="w-full rounded-xl border-0 py-2.5 px-4 text-gray-900 shadow-sm ring-1 ring-inset ring-gray-300 focus:ring-2 focus:ring-inset focus:ring-blue-600 sm:text-sm sm:leading-6 transition-all">
                        <span class="text-sm font-semibold text-gray-600 shrink-0">MB</span>
                    </div>
                    @error('maxGallerySizeMB') <span class="text-xs font-semibold text-rose-600 mt-2 block">{{ $message }}</span> @enderror
                </div>
            </div>
        </div>

        <div class="flex justify-end border-t border-gray-100 bg-gray-50/50 px-6 py-4">
            <button type="button" wire:click="save" wire:loading.attr="disabled"
                    class="inline-flex items-center justify-center gap-2 rounded-xl bg-blue-600 px-6 py-2.5 text-sm font-semibold text-white shadow-sm hover:bg-blue-500 focus-visible:outline focus-visible:outline-2 focus-visible:outline-offset-2 focus-visible:outline-blue-600 disabled:opacity-50 disabled:cursor-not-allowed transition-all shrink-0">
                <svg wire:loading.remove class="h-4 w-4" fill="none" stroke="currentColor" viewBox="0 0 24 24">
                    <path stroke-linecap="round" stroke-linejoin="round" stroke-width="2.5" d="M5 13l4 4L19 7" />
                </svg>
                <svg wire:loading class="h-4 w-4 animate-spin" xmlns="http://www.w3.org/2000/svg" fill="none" viewBox="0 0 24 24">
                    <circle class="opacity-25" cx="12" cy="12" r="10" stroke="currentColor" stroke-width="4"></circle>
                    <path class="opacity-75" fill="currentColor" d="M4 12a8 8 0 018-8V0C5.373 0 0 5.373 0 12h4z"></path>
                </svg>
                <span wire:loading.remove>Lưu cài đặt</span>
                <span wire:loading>Đang lưu...</span>
            </button>
        </div>
    </div>
</div>
