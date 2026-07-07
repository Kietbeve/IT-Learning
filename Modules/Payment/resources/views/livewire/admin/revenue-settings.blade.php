<div class="space-y-6">
    <div class="rounded-2xl border border-slate-200 bg-white">
        <div class="border-b border-slate-200 p-6">
            <h2 class="text-lg font-semibold text-slate-900">Cài đặt doanh thu & gói VIP</h2>
            <p class="text-sm text-slate-500">Quản lý phí nền tảng, hạn mức rút tiền và giá gói VIP</p>
        </div>

        <div class="p-6">
            <div class="space-y-6">
                <div class="grid gap-6 lg:grid-cols-2">
                    <div>
                        <label class="block text-sm font-medium text-slate-700">Phí nền tảng (%)</label>
                        <p class="mt-1 text-xs text-slate-500">Phần doanh thu nền tảng giữ lại trên mỗi giao dịch</p>
                        <div class="mt-2 flex items-center gap-3">
                            <input type="number" wire:model="platformFeePercent" min="0" max="100"
                                   class="w-48 rounded-xl border-slate-300 text-sm">
                            <span class="text-sm font-medium text-slate-600">%</span>
                        </div>
                        @error('platformFeePercent') <span class="text-xs text-rose-600">{{ $message }}</span> @enderror
                    </div>

                    <div>
                        <label class="block text-sm font-medium text-slate-700">Số tiền rút tối thiểu</label>
                        <p class="mt-1 text-xs text-slate-500">Số dư tối thiểu để contributor được gửi yêu cầu rút tiền</p>
                        <div class="mt-2 flex items-center gap-3">
                            <input type="number" wire:model="minPayoutAmount"
                                   class="w-48 rounded-xl border-slate-300 text-sm">
                            <span class="text-sm font-medium text-slate-600">đ</span>
                        </div>
                        @error('minPayoutAmount') <span class="text-xs text-rose-600">{{ $message }}</span> @enderror
                    </div>
                </div>

                <div class="grid gap-6 border-t border-slate-100 pt-6 sm:grid-cols-3">
                    <div>
                        <label class="block text-sm font-medium text-slate-700">Giá gốc gói VIP</label>
                        <p class="mt-1 text-xs text-slate-500">Mức giá chưa giảm của gói VIP</p>
                        <div class="mt-2 flex items-center gap-3">
                            <input type="number" wire:model="vipPrice"
                                   class="w-full rounded-xl border-slate-300 text-sm">
                            <span class="text-sm font-medium text-slate-600">đ</span>
                        </div>
                        @error('vipPrice') <span class="text-xs text-rose-600">{{ $message }}</span> @enderror
                    </div>
                    
                    <div>
                        <label class="block text-sm font-medium text-slate-700">Giá khuyến mãi VIP</label>
                        <p class="mt-1 text-xs text-slate-500">Mức giá thực tế user mua gói VIP</p>
                        <div class="mt-2 flex items-center gap-3">
                            <input type="number" wire:model="vipSalePrice"
                                   class="w-full rounded-xl border-slate-300 text-sm">
                            <span class="text-sm font-medium text-slate-600">đ</span>
                        </div>
                        @error('vipSalePrice') <span class="text-xs text-rose-600">{{ $message }}</span> @enderror
                    </div>
                    
                    <div>
                        <label class="block text-sm font-medium text-slate-700">Số lượt tải (Quota)</label>
                        <p class="mt-1 text-xs text-slate-500">Số lượt tải tài liệu khi mua gói VIP</p>
                        <div class="mt-2 flex items-center gap-3">
                            <input type="number" wire:model="vipQuota"
                                   class="w-full rounded-xl border-slate-300 text-sm">
                            <span class="text-sm font-medium text-slate-600">lượt</span>
                        </div>
                        @error('vipQuota') <span class="text-xs text-rose-600">{{ $message }}</span> @enderror
                    </div>
                </div>
            </div>
        </div>

        <div class="flex justify-end border-t border-slate-200 px-6 py-4">
            <button type="button" wire:click="save" wire:loading.attr="disabled"
                    class="rounded-xl bg-blue-600 px-6 py-2.5 text-sm font-semibold text-white hover:bg-blue-700 disabled:opacity-50 transition-colors">
                <span wire:loading.remove>Lưu cài đặt</span>
                <span wire:loading>Đang lưu...</span>
            </button>
        </div>
    </div>
</div>