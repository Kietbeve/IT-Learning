<div class="space-y-6" x-data="{ activeTab: 'general' }">
    <div class="rounded-2xl border border-slate-200 bg-white">
        <div class="border-b border-slate-200 p-6">
            <h2 class="text-lg font-semibold text-slate-900">Cài đặt hệ thống & doanh thu</h2>
            <p class="text-sm text-slate-500">Quản lý thông tin website, chia doanh thu, hạn mức rút tiền và giá VIP</p>
        </div>

        <div class="border-b border-slate-200 px-6">
            <nav class="-mb-px flex gap-6">
                <button type="button" @click="activeTab = 'general'"
                        :class="activeTab === 'general' ? 'border-blue-600 text-blue-600' : 'border-transparent text-slate-500 hover:text-slate-700'"
                        class="border-b-2 py-3 text-sm font-semibold transition-colors">
                    Cài đặt chung
                </button>
                <button type="button" @click="activeTab = 'vip'"
                        :class="activeTab === 'vip' ? 'border-blue-600 text-blue-600' : 'border-transparent text-slate-500 hover:text-slate-700'"
                        class="border-b-2 py-3 text-sm font-semibold transition-colors">
                    Giá VIP
                </button>
            </nav>
        </div>

        <div class="p-6">
            <div x-show="activeTab === 'general'" class="space-y-6">
                <div class="grid gap-6 lg:grid-cols-2">
                    <div>
                        <label class="block text-sm font-medium text-slate-700">Tên website</label>
                        <p class="mt-1 text-xs text-slate-500">Tên hiển thị chính của hệ thống</p>
                        <input type="text" wire:model="siteName"
                               class="mt-2 w-full rounded-xl border-slate-300 text-sm">
                        @error('siteName') <span class="text-xs text-rose-600">{{ $message }}</span> @enderror
                    </div>

                    <div>
                        <label class="block text-sm font-medium text-slate-700">Email gửi hệ thống</label>
                        <p class="mt-1 text-xs text-slate-500">Email dùng làm người gửi mặc định</p>
                        <input type="email" wire:model="smtpFromEmail"
                               class="mt-2 w-full rounded-xl border-slate-300 text-sm">
                        @error('smtpFromEmail') <span class="text-xs text-rose-600">{{ $message }}</span> @enderror
                    </div>
                </div>

                <div class="grid gap-6 border-t border-slate-100 pt-6 lg:grid-cols-2">
                    <div>
                        <label class="block text-sm font-medium text-slate-700">Tỷ lệ chia doanh thu cho contributor (%)</label>
                        <p class="mt-1 text-xs text-slate-500">Phần doanh thu contributor nhận từ mỗi giao dịch</p>
                        <div class="mt-2 flex items-center gap-3">
                            <input type="number" wire:model="commissionRate" min="0" max="100"
                                   class="w-48 rounded-xl border-slate-300 text-sm">
                            <span class="text-sm font-medium text-slate-600">%</span>
                        </div>
                        @error('commissionRate') <span class="text-xs text-rose-600">{{ $message }}</span> @enderror
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

                <div class="grid gap-6 border-t border-slate-100 pt-6 lg:grid-cols-2">
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
                        <label class="block text-sm font-medium text-slate-700">Hạn mức rút tương thích</label>
                        <p class="mt-1 text-xs text-slate-500">Giữ giá trị cho các màn hình đang dùng cấu hình doanh thu cũ</p>
                        <div class="mt-2 flex items-center gap-3">
                            <input type="number" wire:model="payoutMinimum"
                                   class="w-48 rounded-xl border-slate-300 text-sm">
                            <span class="text-sm font-medium text-slate-600">đ</span>
                        </div>
                        @error('payoutMinimum') <span class="text-xs text-rose-600">{{ $message }}</span> @enderror
                    </div>
                </div>

                <div class="border-t border-slate-100 pt-6">
                    <label class="block text-sm font-medium text-slate-700">Màu chủ đạo</label>
                    <p class="mt-1 text-xs text-slate-500">Màu nhận diện chính của giao diện</p>
                    <div class="mt-2 flex flex-wrap items-center gap-3">
                        <input type="color" wire:model="themePrimaryColor"
                               class="h-10 w-14 rounded-lg border border-slate-300 bg-white p-1">
                        <input type="text" wire:model="themePrimaryColor"
                               class="w-48 rounded-xl border-slate-300 text-sm">
                    </div>
                    @error('themePrimaryColor') <span class="text-xs text-rose-600">{{ $message }}</span> @enderror
                </div>

                <div class="rounded-xl border border-blue-100 bg-blue-50 p-4">
                    <div class="flex items-start gap-3">
                        <svg class="mt-0.5 h-5 w-5 shrink-0 text-blue-600" fill="none" stroke="currentColor" viewBox="0 0 24 24">
                            <path stroke-linecap="round" stroke-linejoin="round" stroke-width="2" d="M13 16h-1v-4h-1m1-4h.01M21 12a9 9 0 11-18 0 9 9 0 0118 0z"/>
                        </svg>
                        <div>
                            <p class="text-sm font-semibold text-blue-800">Thông tin</p>
                            <p class="mt-1 text-xs text-blue-700">
                                Contributor nhận <strong>{{ $commissionRate }}%</strong>, nền tảng nhận <strong>{{ $platformFeePercent }}%</strong>.
                                Số tiền rút tối thiểu là <strong>{{ number_format((int) $minPayoutAmount) }}đ</strong>.
                            </p>
                        </div>
                    </div>
                </div>
            </div>

            <div x-show="activeTab === 'vip'" x-cloak class="space-y-6">
                <div class="grid gap-6 sm:grid-cols-2">
                    <div>
                        <label class="block text-sm font-medium text-slate-700">VIP tháng</label>
                        <p class="mt-1 text-xs text-slate-500">Giá gói VIP hằng tháng</p>
                        <div class="mt-2 flex items-center gap-3">
                            <input type="number" wire:model="vipMonthlyPrice"
                                   class="w-48 rounded-xl border-slate-300 text-sm">
                            <span class="text-sm font-medium text-slate-600">đ</span>
                        </div>
                        @error('vipMonthlyPrice') <span class="text-xs text-rose-600">{{ $message }}</span> @enderror
                    </div>

                    <div>
                        <label class="block text-sm font-medium text-slate-700">VIP năm</label>
                        <p class="mt-1 text-xs text-slate-500">Giá gói VIP hằng năm</p>
                        <div class="mt-2 flex items-center gap-3">
                            <input type="number" wire:model="vipYearlyPrice"
                                   class="w-48 rounded-xl border-slate-300 text-sm">
                            <span class="text-sm font-medium text-slate-600">đ</span>
                        </div>
                        @error('vipYearlyPrice') <span class="text-xs text-rose-600">{{ $message }}</span> @enderror
                    </div>
                </div>

                @php
                    $savedMonthly = $vipMonthlyPrice > 0 ? round(($vipYearlyPrice - $vipMonthlyPrice * 12) / ($vipMonthlyPrice * 12) * 100) : 0;
                @endphp
                <div class="rounded-xl border border-purple-100 bg-purple-50 p-4">
                    <div class="flex items-start gap-3">
                        <svg class="mt-0.5 h-5 w-5 shrink-0 text-purple-600" fill="none" stroke="currentColor" viewBox="0 0 24 24">
                            <path stroke-linecap="round" stroke-linejoin="round" stroke-width="2" d="M13 7h8m0 0v8m0-8l-8 8-4-4-6 6"/>
                        </svg>
                        <div>
                            <p class="text-sm font-semibold text-purple-800">So sánh giá</p>
                            <p class="mt-1 text-xs text-purple-700">
                                Gói tháng: <strong>{{ number_format((int) $vipMonthlyPrice) }}đ</strong> / tháng
                                - Gói năm: <strong>{{ number_format((int) $vipYearlyPrice) }}đ</strong> / năm
                                ({{ $savedMonthly >= 0 ? 'tiết kiệm ' . $savedMonthly . '%' : 'đắt hơn ' . abs($savedMonthly) . '%' }} so với mua theo tháng)
                            </p>
                            <p class="mt-1 text-xs text-purple-700">
                                Gói năm tương đương <strong>{{ $vipYearlyPrice > 0 ? number_format($vipYearlyPrice / 12) : 0 }}đ</strong> / tháng
                            </p>
                        </div>
                    </div>
                </div>
            </div>
        </div>

        <div class="flex justify-end border-t border-slate-200 px-6 py-4">
            <button type="button" wire:click="save" wire:loading.attr="disabled"
                    class="rounded-xl bg-blue-600 px-6 py-2.5 text-sm font-semibold text-white hover:bg-blue-700 disabled:opacity-50">
                <span wire:loading.remove>Lưu cài đặt</span>
                <span wire:loading>Đang lưu...</span>
            </button>
        </div>
    </div>
</div>