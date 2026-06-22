<div class="space-y-6" x-data="{ activeTab: 'general' }">
    <div class="rounded-2xl border border-slate-200 bg-white">
        <div class="border-b border-slate-200 p-6">
            <h2 class="text-lg font-semibold text-slate-900">Cài đặt doanh thu</h2>
            <p class="text-sm text-slate-500">Quản lý phí nền tảng, hạn mức rút tiền và giá VIP</p>
        </div>

        <div class="border-b border-slate-200 px-6">
            <nav class="-mb-px flex gap-6">
                <button @click="activeTab = 'general'"
                        :class="activeTab === 'general' ? 'border-blue-600 text-blue-600' : 'border-transparent text-slate-500 hover:text-slate-700'"
                        class="border-b-2 py-3 text-sm font-semibold transition-colors">
                    Cài đặt chung
                </button>
                <button @click="activeTab = 'vip'"
                        :class="activeTab === 'vip' ? 'border-blue-600 text-blue-600' : 'border-transparent text-slate-500 hover:text-slate-700'"
                        class="border-b-2 py-3 text-sm font-semibold transition-colors">
                    Giá VIP
                </button>
            </nav>
        </div>

        <div class="p-6">
            <div x-show="activeTab === 'general'" class="space-y-6">
                <div>
                    <label class="block text-sm font-medium text-slate-700">Phí nền tảng (%)</label>
                    <p class="mt-1 text-xs text-slate-500">Phần trăm phí nền tảng trên mỗi giao dịch mua tài liệu</p>
                    <div class="mt-2 flex items-center gap-3">
                        <input type="number" wire:model="platformFeePercent" min="0" max="100"
                               class="rounded-xl border-slate-300 text-sm w-48">
                        <span class="text-sm font-medium text-slate-600">%</span>
                    </div>
                    @error('platformFeePercent') <span class="text-xs text-rose-600">{{ $message }}</span> @enderror
                </div>

                <div class="border-t border-slate-100 pt-6">
                    <label class="block text-sm font-medium text-slate-700">Số tiền rút tối thiểu</label>
                    <p class="mt-1 text-xs text-slate-500">Số dư tối thiểu contributor phải có để được rút tiền</p>
                    <div class="mt-2 flex items-center gap-3">
                        <input type="number" wire:model="payoutMinimum"
                               class="rounded-xl border-slate-300 text-sm w-48">
                        <span class="text-sm font-medium text-slate-600">đ</span>
                    </div>
                    @error('payoutMinimum') <span class="text-xs text-rose-600">{{ $message }}</span> @enderror
                </div>

                <div class="rounded-xl bg-blue-50 border border-blue-100 p-4">
                    <div class="flex items-start gap-3">
                        <svg class="mt-0.5 h-5 w-5 shrink-0 text-blue-600" fill="none" stroke="currentColor" viewBox="0 0 24 24">
                            <path stroke-linecap="round" stroke-linejoin="round" stroke-width="2" d="M13 16h-1v-4h-1m1-4h.01M21 12a9 9 0 11-18 0 9 9 0 0118 0z"/>
                        </svg>
                        <div>
                            <p class="text-sm font-semibold text-blue-800">Thông tin</p>
                            <p class="mt-1 text-xs text-blue-700">
                                Với phí nền tảng <strong>{{ $platformFeePercent }}%</strong>, 
                                contributor nhận <strong>{{ 100 - $platformFeePercent }}%</strong> doanh thu từ mỗi giao dịch.
                                Số tiền rút tối thiểu là <strong>{{ number_format($payoutMinimum) }}đ</strong>.
                            </p>
                        </div>
                    </div>
                </div>
            </div>

            <div x-show="activeTab === 'vip'" x-cloak class="space-y-6">
                <div class="grid gap-6 sm:grid-cols-2">
                    <div>
                        <label class="block text-sm font-medium text-slate-700">VIP tháng</label>
                        <p class="mt-1 text-xs text-slate-500">Giá gói VIP hàng tháng</p>
                        <div class="mt-2 flex items-center gap-3">
                            <input type="number" wire:model="vipMonthlyPrice"
                                   class="rounded-xl border-slate-300 text-sm w-48">
                            <span class="text-sm font-medium text-slate-600">đ</span>
                        </div>
                        @error('vipMonthlyPrice') <span class="text-xs text-rose-600">{{ $message }}</span> @enderror
                    </div>

                    <div>
                        <label class="block text-sm font-medium text-slate-700">VIP năm</label>
                        <p class="mt-1 text-xs text-slate-500">Giá gói VIP hàng năm</p>
                        <div class="mt-2 flex items-center gap-3">
                            <input type="number" wire:model="vipYearlyPrice"
                                   class="rounded-xl border-slate-300 text-sm w-48">
                            <span class="text-sm font-medium text-slate-600">đ</span>
                        </div>
                        @error('vipYearlyPrice') <span class="text-xs text-rose-600">{{ $message }}</span> @enderror
                    </div>
                </div>

                @php
                    $savedMonthly = $vipMonthlyPrice > 0 ? round(($vipYearlyPrice - $vipMonthlyPrice * 12) / ($vipMonthlyPrice * 12) * 100) : 0;
                @endphp
                <div class="rounded-xl bg-purple-50 border border-purple-100 p-4">
                    <div class="flex items-start gap-3">
                        <svg class="mt-0.5 h-5 w-5 shrink-0 text-purple-600" fill="none" stroke="currentColor" viewBox="0 0 24 24">
                            <path stroke-linecap="round" stroke-linejoin="round" stroke-width="2" d="M13 7h8m0 0v8m0-8l-8 8-4-4-6 6"/>
                        </svg>
                        <div>
                            <p class="text-sm font-semibold text-purple-800">So sánh giá</p>
                            <p class="mt-1 text-xs text-purple-700">
                                Gói tháng: <strong>{{ number_format($vipMonthlyPrice) }}đ</strong> / tháng
                                - Gói năm: <strong>{{ number_format($vipYearlyPrice) }}đ</strong> / năm
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

        <div class="border-t border-slate-200 px-6 py-4 flex justify-end">
            <button wire:click="save" wire:loading.attr="disabled"
                    class="rounded-xl bg-blue-600 px-6 py-2.5 text-sm font-semibold text-white hover:bg-blue-700 disabled:opacity-50">
                <span wire:loading.remove>Lưu cài đặt</span>
                <span wire:loading>Đang lưu...</span>
            </button>
        </div>
    </div>
</div>
