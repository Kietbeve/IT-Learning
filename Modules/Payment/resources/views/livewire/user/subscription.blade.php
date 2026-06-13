<div>
    {{-- Success Modal --}}
    @if($showSuccessModal)
        <div class="fixed inset-0 z-50 flex items-center justify-center bg-black/50" wire:click.self="closeSuccessModal">
            <div class="relative bg-white rounded-2xl shadow-2xl max-w-md w-full mx-4 p-8 text-center animate-scale-in">
                <button wire:click="closeSuccessModal" class="absolute top-3 right-3 text-slate-400 hover:text-slate-600">
                    <svg class="w-5 h-5" fill="none" stroke="currentColor" viewBox="0 0 24 24">
                        <path stroke-linecap="round" stroke-linejoin="round" stroke-width="2" d="M6 18L18 6M6 6l12 12"/>
                    </svg>
                </button>

                <div class="mx-auto flex h-20 w-20 items-center justify-center rounded-full bg-gradient-to-br from-amber-100 to-yellow-100">
                    <svg class="w-10 h-10 text-amber-600" fill="none" stroke="currentColor" viewBox="0 0 24 24" stroke-width="2">
                        <path stroke-linecap="round" stroke-linejoin="round" d="M5 3v4M3 5h4M6 17v4m-2-2h4m5-16l2.286 6.857L21 12l-5.714 2.143L13 21l-2.286-6.857L5 12l5.714-2.143L13 3z"/>
                    </svg>
                </div>

                <h2 class="mt-4 text-2xl font-bold text-gray-900">Mua gói VIP thành công!</h2>
                <p class="mt-2 text-sm text-slate-600">Tài khoản của bạn đã được nâng cấp VIP. Hãy tận hưởng các quyền lợi đặc biệt!</p>

                @php $pkg = $packages[array_key_first($packages)]; @endphp
                @if($pkg)
                    <div class="mt-6 rounded-xl border-2 border-amber-200 bg-amber-50 p-4 text-left space-y-2">
                        <div class="flex justify-between text-sm">
                            <span class="text-slate-600">Lượt tải:</span>
                            <span class="font-bold text-amber-700">{{ $pkg['download_quota'] }} tài liệu Premium</span>
                        </div>
                        <div class="flex justify-between text-sm">
                            <span class="text-slate-600">Hiệu lực:</span>
                            <span class="font-bold text-amber-700">{{ $pkg['duration_days'] }} ngày</span>
                        </div>
                        <div class="flex justify-between text-sm">
                            <span class="text-slate-600">Số tiền:</span>
                            <span class="font-bold text-amber-700">{{ number_format($successData['amount'] ?? $pkg['sale_price'] ?? $pkg['price']) }}đ</span>
                        </div>
                    </div>
                @endif

                <div class="mt-6 space-y-3">
                    <x-button primary class="w-full" wire:click="closeSuccessModal" label="Bắt đầu khám phá ngay" />
                </div>
            </div>
        </div>
    @endif

    <div class="max-w-7xl mx-auto py-6">
        <div class="mb-8">
            <h2 class="text-3xl font-bold text-gray-900">Gói VIP Premium</h2>
            <p class="text-base text-gray-500 mt-2">Nâng cấp tài khoản để tải tài liệu Premium với giá ưu đãi</p>
        </div>

        @if($this->isVipActive)
            <div class="mb-8 bg-gradient-to-r from-green-50 to-emerald-50 border border-green-200 rounded-2xl p-6">
                <div class="flex items-start justify-between">
                    <div class="flex-1">
                        <div class="flex items-center gap-3 mb-3">
                            <x-icon name="check-circle" class="w-8 h-8 text-green-600" />
                            <h3 class="text-2xl font-semibold text-green-900">Bạn đang là thành viên VIP</h3>
                        </div>
                        
                        <div class="grid grid-cols-1 md:grid-cols-3 gap-4 mt-4">
                            <div class="bg-white/60 rounded-lg p-4">
                                <p class="text-sm text-gray-600">Hết hạn</p>
                                <p class="text-lg font-bold text-gray-900 mt-1">
                                    {{ $this->vipExpiresAt?->format('d/m/Y') }}
                                </p>
                            </div>
                            
                            <div class="bg-white/60 rounded-lg p-4">
                                <p class="text-sm text-gray-600">Còn lại</p>
                                <p class="text-lg font-bold text-gray-900 mt-1">
                                    {{ $this->daysRemaining }} ngày
                                </p>
                            </div>
                            
                            <div class="bg-white/60 rounded-lg p-4">
                                <p class="text-sm text-gray-600">Lượt tải còn lại</p>
                                <p class="text-lg font-bold text-gray-900 mt-1">
                                    {{ $this->vipQuota }} lượt
                                </p>
                            </div>
                        </div>
                    </div>
                    
                    <x-badge flat positive label="VIP Active" class="text-sm" />
                </div>
                
                <p class="text-sm text-green-700 mt-4">
                    <x-icon name="information-circle" class="w-4 h-4 inline" />
                    Bạn có thể gia hạn gói VIP bất cứ lúc nào
                </p>
            </div>
        @endif

        <div class="max-w-lg mx-auto">
            @php $package = reset($packages); $key = array_key_first($packages); @endphp
            <div class="relative flex flex-col bg-white rounded-2xl border-2 border-primary-500 p-8 hover:shadow-xl transition">
                <div class="absolute -top-3 left-1/2 -translate-x-1/2">
                    <x-badge primary label="Giảm 50%" class="text-xs" />
                </div>

                <div class="text-center mb-6">
                    <h3 class="text-2xl font-semibold text-gray-900">{{ $package['name'] }}</h3>
                    <p class="text-sm text-gray-500 mt-2">{{ $package['description'] }}</p>
                </div>

                <div class="text-center mb-6">
                    @if($package['sale_price'])
                        <div class="flex items-center justify-center gap-2">
                            <span class="text-lg text-gray-400 line-through">
                                {{ number_format($package['price']) }}đ
                            </span>
                            <x-badge flat negative label="-50%" class="text-xs" />
                        </div>
                        <p class="text-4xl font-bold text-primary-600 mt-2">
                            {{ number_format($package['sale_price']) }}đ
                        </p>
                    @else
                        <p class="text-4xl font-bold text-primary-600">
                            {{ number_format($package['price']) }}đ
                        </p>
                    @endif
                    <p class="text-sm text-gray-500 mt-1">{{ $package['duration_days'] }} ngày</p>
                </div>

                <div class="border-t border-gray-100 pt-4 mb-6">
                    <ul class="space-y-3">
                        @foreach($package['features'] as $feature)
                            <li class="flex items-start gap-2 text-sm text-gray-700">
                                <x-icon name="check" class="w-5 h-5 text-green-500 shrink-0 mt-0.5" />
                                <span>{{ $feature }}</span>
                            </li>
                        @endforeach
                    </ul>
                </div>

                <button 
                    type="button"
                    wire:click="purchaseVip('{{ $key }}')"
                    wire:loading.attr="disabled"
                    class="w-full mt-auto inline-flex items-center justify-center gap-2 rounded-lg bg-primary-600 px-4 py-3 text-sm font-semibold text-white shadow hover:bg-primary-700 disabled:opacity-60 transition"
                >
                    <span wire:loading.remove.delay>Mua ngay</span>
                    <span wire:loading.delay>Đang xử lý...</span>
                </button>
            </div>
        </div>

        @if($errorMessage)
            <div class="mt-4 max-w-lg mx-auto rounded-xl bg-red-50 border border-red-200 p-4 text-sm text-red-700">
                <p class="font-semibold">Lỗi:</p>
                <p>{{ $errorMessage }}</p>
            </div>
        @endif

        <div class="mt-12 bg-blue-50 border border-blue-200 rounded-2xl p-6">
            <div class="flex items-start gap-4">
                <x-icon name="information-circle" class="w-6 h-6 text-blue-600 shrink-0 mt-1" />
                <div>
                    <h4 class="text-lg font-semibold text-blue-900 mb-2">Lưu ý quan trọng</h4>
                    <ul class="space-y-2 text-sm text-blue-800">
                        <li>• Gói VIP có hiệu lực ngay sau khi thanh toán thành công</li>
                        <li>• Lượt tải sẽ được cộng dồn nếu gia hạn trước khi hết hạn</li>
                        <li>• Thời hạn VIP sẽ được gia hạn thêm khi mua gói mới</li>
                        <li>• Lượt tải chỉ áp dụng cho tài liệu Premium, tài liệu miễn phí không bị giới hạn</li>
                    </ul>
                </div>
            </div>
        </div>
    </div>
</div>
