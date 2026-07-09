<div wire:poll.5s="refreshVipStatus" class="relative pb-16 pt-8 font-sans">
    {{-- Background Decorative Elements (Hiệu ứng nền mờ hiện đại) --}}
    <div class="absolute inset-0 overflow-hidden pointer-events-none -z-10">
        <div class="absolute top-0 left-1/4 w-96 h-96 bg-blue-400/10 rounded-full mix-blend-multiply filter blur-3xl"></div>
        <div class="absolute top-1/3 right-1/4 w-96 h-96 bg-indigo-400/10 rounded-full mix-blend-multiply filter blur-3xl"></div>
    </div>

    <div class="max-w-7xl mx-auto px-4 sm:px-6 lg:px-8">
        {{-- ===== HEADER SECTION ===== --}}
        <div class="mb-12 text-center max-w-2xl mx-auto">
            {{-- Premium Badge --}}
            <div class="inline-flex items-center gap-2 px-3 py-1.5 rounded-full bg-amber-500/10 border border-amber-500/20 text-amber-600 mb-4 shadow-sm">
                <x-icon name="sparkles" class="w-4 h-4 animate-pulse drop-shadow-[0_0_8px_rgba(251,191,36,0.8)]" />
                <span class="text-xs font-bold uppercase tracking-wider">Premium Access</span>
            </div>
            
            <h2 class="text-4xl lg:text-5xl font-extrabold text-blue-900 leading-tight mb-4 tracking-tight">
                Đặc Quyền Thành Viên VIP
            </h2>
            <p class="text-base text-slate-500 leading-relaxed">
                Nâng cấp tài khoản để trải nghiệm không giới hạn kho tài liệu Premium chất lượng cao với mức giá ưu đãi nhất.
            </p>
        </div>

        {{-- ===== VIP ACTIVE STATUS CARD ===== --}}
        @if($this->isVipActive)
            <div class="mb-12 max-w-4xl mx-auto">
                {{-- Border Gradient Wrapper --}}
                <div class="relative overflow-hidden rounded-3xl bg-gradient-to-br from-emerald-400 via-teal-500 to-emerald-600 p-[1px] shadow-2xl shadow-emerald-500/20 group hover:shadow-emerald-500/30 transition-shadow duration-300">
                    {{-- Inner Glassmorphism Card --}}
                    <div class="relative bg-white/95 backdrop-blur-xl rounded-[23px] p-6 sm:p-8">
                        <div class="flex flex-col md:flex-row md:items-start justify-between gap-6">
                            <div class="flex-1">
                                <div class="flex items-center gap-4 mb-6">
                                    <div class="w-12 h-12 rounded-2xl bg-gradient-to-br from-emerald-100 to-teal-50 flex items-center justify-center border border-emerald-200/50 shadow-inner group-hover:scale-110 transition-transform duration-300">
                                        <x-icon name="check-circle" class="w-7 h-7 text-emerald-600" />
                                    </div>
                                    <div>
                                        <h3 class="text-xl sm:text-2xl font-bold text-blue-900">Bạn đang là thành viên VIP</h3>
                                        <p class="text-sm text-emerald-600 font-medium flex items-center gap-1.5 mt-1">
                                            <span class="relative flex h-2.5 w-2.5">
                                              <span class="animate-ping absolute inline-flex h-full w-full rounded-full bg-emerald-400 opacity-75"></span>
                                              <span class="relative inline-flex rounded-full h-2.5 w-2.5 bg-emerald-500"></span>
                                            </span>
                                            Trạng thái kích hoạt
                                        </p>
                                    </div>
                                    <div class="hidden md:block ml-auto">
                                         <x-badge flat positive label="VIP Active" class="font-bold border-emerald-200 bg-emerald-50 text-emerald-700 shadow-sm" />
                                    </div>
                                </div>
                                
                                <div class="grid grid-cols-1 sm:grid-cols-3 gap-4">
                                    {{-- Hết hạn --}}
                                    <div class="bg-slate-50/70 border border-slate-100 rounded-2xl p-4 transition-all hover:bg-white hover:shadow-md hover:border-emerald-100">
                                        <div class="flex items-center gap-2 text-slate-500 mb-1.5">
                                            <x-icon name="calendar" class="w-4 h-4 text-slate-400" />
                                            <p class="text-xs font-semibold uppercase tracking-wider">Ngày hết hạn</p>
                                        </div>
                                        <p class="text-lg font-bold text-slate-900">
                                            {{ $this->vipExpiresAt?->format('d/m/Y') }}
                                        </p>
                                    </div>
                                    
                                    {{-- Còn lại --}}
                                    <div class="bg-slate-50/70 border border-slate-100 rounded-2xl p-4 transition-all hover:bg-white hover:shadow-md hover:border-emerald-100">
                                        <div class="flex items-center gap-2 text-slate-500 mb-1.5">
                                            <x-icon name="clock" class="w-4 h-4 text-slate-400" />
                                            <p class="text-xs font-semibold uppercase tracking-wider">Thời gian còn lại</p>
                                        </div>
                                        <p class="text-lg font-bold text-emerald-600">
                                            {{ $this->daysRemaining }} ngày
                                        </p>
                                    </div>
                                    
                                    {{-- Lượt tải --}}
                                    <div class="bg-slate-50/70 border border-slate-100 rounded-2xl p-4 transition-all hover:bg-white hover:shadow-md hover:border-emerald-100">
                                        <div class="flex items-center gap-2 text-slate-500 mb-1.5">
                                            <x-icon name="arrow-down-tray" class="w-4 h-4 text-slate-400" />
                                            <p class="text-xs font-semibold uppercase tracking-wider">Lượt tải còn lại</p>
                                        </div>
                                        <p class="text-lg font-bold text-blue-600">
                                            {{ $this->vipQuota }} lượt
                                        </p>
                                    </div>
                                </div>
                            </div>
                        </div>
                        
                        <div class="mt-6 pt-4 border-t border-slate-100 flex items-center justify-between text-sm">
                            <p class="text-slate-500 font-medium flex items-center gap-2">
                                <x-icon name="information-circle" class="w-4 h-4 text-emerald-500" />
                                Bạn có thể mua thêm gói để gia hạn và cộng dồn lượt tải.
                            </p>
                        </div>
                    </div>
                </div>
            </div>
        @endif

        {{-- ===== PRICING PACKAGE CARD ===== --}}
        <div class="max-w-md mx-auto relative z-10">
            @php 
                $package = reset($packages); 
                $key = array_key_first($packages); 
                $discountPercent = 0;
                if ($package['price'] > 0 && $package['sale_price'] < $package['price']) {
                    $discountPercent = round((($package['price'] - $package['sale_price']) / $package['price']) * 100);
                }
            @endphp
            
            <div class="relative bg-white/80 backdrop-blur-xl rounded-3xl border border-slate-200/60 shadow-2xl shadow-indigo-900/10 p-8 sm:p-10 transition-all duration-300 hover:shadow-indigo-500/20 hover:-translate-y-1 group">
                {{-- Top Gradient Line Accent --}}
                <div class="absolute top-0 inset-x-0 h-1.5 bg-gradient-to-r from-blue-500 via-indigo-500 to-purple-500 rounded-t-3xl"></div>

                {{-- Discount Badge --}}
                @if($discountPercent > 0)
                <div class="absolute -top-4 right-8">
                    <div class="bg-gradient-to-r from-rose-500 to-red-600 text-white text-xs font-bold px-3 py-1.5 rounded-full shadow-lg shadow-red-500/30 flex items-center gap-1 animate-bounce">
                        <x-icon name="fire" class="w-3.5 h-3.5" />
                        Giảm {{ $discountPercent }}%
                    </div>
                </div>
                @endif

                <div class="text-center mb-8">
                    <h3 class="text-3xl font-extrabold text-blue-900 mb-2">{{ $package['name'] }}</h3>
                    <p class="text-sm text-slate-500">{{ $package['description'] }}</p>
                </div>

                {{-- Pricing Info --}}
                <div class="flex flex-col items-center justify-center mb-8">
                    @if($package['sale_price'] && $package['sale_price'] < $package['price'])
                        <div class="flex items-center gap-2 mb-1">
                            <span class="text-lg text-slate-400 font-medium line-through decoration-slate-300">
                                {{ number_format($package['price']) }}đ
                            </span>
                        </div>
                        <div class="flex items-baseline gap-1">
                            <span class="text-5xl font-extrabold text-transparent bg-clip-text bg-gradient-to-r from-blue-600 to-indigo-600">
                                {{ number_format($package['sale_price']) }}
                            </span>
                            <span class="text-2xl font-bold text-slate-600">đ</span>
                        </div>
                    @else
                        <div class="flex items-baseline gap-1 mt-6">
                            <span class="text-5xl font-extrabold text-transparent bg-clip-text bg-gradient-to-r from-blue-600 to-indigo-600">
                                {{ number_format($package['price']) }}
                            </span>
                            <span class="text-2xl font-bold text-slate-600">đ</span>
                        </div>
                    @endif
                    
                    <div class="mt-4 py-1.5 px-4 bg-indigo-50 border border-indigo-100 rounded-full inline-flex items-center gap-2">
                        <x-icon name="clock" class="w-4 h-4 text-indigo-500" />
                        <p class="text-sm font-semibold text-slate-600">
                            Sử dụng trong <span class="text-indigo-600">{{ $package['duration_days'] }} ngày</span>
                        </p>
                    </div>
                </div>

                {{-- Features List --}}
                <div class="mb-8 bg-slate-50 rounded-2xl p-5 border border-slate-100">
                    <ul class="space-y-4">
                        @foreach($package['features'] as $feature)
                            <li class="flex items-start gap-3">
                                <div class="w-6 h-6 rounded-full bg-blue-100 flex items-center justify-center shrink-0 mt-0.5">
                                    <x-icon name="check" class="w-3.5 h-3.5 text-blue-600 font-bold" />
                                </div>
                                <span class="text-sm text-slate-700 font-medium leading-relaxed">{{ $feature }}</span>
                            </li>
                        @endforeach
                    </ul>
                </div>

                {{-- Submit Button --}}
                <button 
                    type="button"
                    wire:click="purchaseVip('{{ $key }}')"
                    wire:loading.attr="disabled"
                    class="w-full relative group/btn overflow-hidden inline-flex items-center justify-center gap-2 rounded-xl bg-gradient-to-r from-blue-600 to-indigo-600 px-6 py-4 text-base font-bold text-white shadow-lg shadow-blue-500/30 hover:shadow-blue-500/50 transition-all duration-300 disabled:opacity-70 disabled:cursor-not-allowed transform hover:scale-[1.02]"
                >
                    {{-- Button Hover Layer Effect --}}
                    <div class="absolute inset-0 bg-white/20 translate-y-full group-hover/btn:translate-y-0 transition-transform duration-300 ease-out"></div>
                    
                    <span class="relative flex items-center gap-2" wire:loading.remove.delay wire:target="purchaseVip">
                        Nâng Cấp Ngay
                        <x-icon name="arrow-right" class="w-5 h-5 group-hover/btn:translate-x-1 transition-transform" />
                    </span>
                    <span class="relative flex items-center gap-2" wire:loading.delay wire:target="purchaseVip">
                        <svg class="animate-spin h-5 w-5 text-white" xmlns="http://www.w3.org/2000/svg" fill="none" viewBox="0 0 24 24">
                            <circle class="opacity-25" cx="12" cy="12" r="10" stroke="currentColor" stroke-width="4"></circle>
                            <path class="opacity-75" fill="currentColor" d="M4 12a8 8 0 018-8V0C5.373 0 0 5.373 0 12h4zm2 5.291A7.962 7.962 0 014 12H0c0 3.042 1.135 5.824 3 7.938l3-2.647z"></path>
                        </svg>
                        Đang xử lý...
                    </span>
                </button>
            </div>
        </div>

        {{-- ===== ERROR MESSAGE ===== --}}
        @if($errorMessage)
            <div class="mt-6 max-w-md mx-auto transition-all duration-300">
                <div class="rounded-2xl bg-red-50/90 backdrop-blur-sm border border-red-200 p-4 flex items-start gap-3 text-sm text-red-800 shadow-lg shadow-red-900/5">
                    <div class="w-8 h-8 rounded-full bg-red-100 flex items-center justify-center shrink-0">
                        <x-icon name="exclamation-triangle" class="w-5 h-5 text-red-600" />
                    </div>
                    <div>
                        <p class="font-bold text-red-900 mb-0.5">Không thể thực hiện giao dịch</p>
                        <p class="text-red-700/90">{{ $errorMessage }}</p>
                    </div>
                </div>
            </div>
        @endif

        {{-- ===== IMPORTANT NOTES (Dark Sleek Style matching Footer) ===== --}}
        <div class="mt-16 max-w-4xl mx-auto">
            <div class="bg-gradient-to-r from-slate-800 to-slate-900 rounded-3xl p-1 shadow-xl">
                <div class="bg-slate-900/95 backdrop-blur-xl rounded-[22px] p-6 sm:p-8">
                    <div class="flex flex-col sm:flex-row items-start gap-5">
                        <div class="w-12 h-12 rounded-2xl bg-blue-500/20 border border-blue-500/30 flex items-center justify-center shrink-0">
                            <x-icon name="shield-check" class="w-6 h-6 text-blue-400" />
                        </div>
                        <div>
                            <h4 class="text-lg font-bold text-white mb-4 flex items-center gap-2">
                                Lưu ý khi thanh toán
                                <div class="w-1 h-4 bg-gradient-to-b from-blue-400 to-cyan-400 rounded-full"></div>
                            </h4>
                            <div class="grid grid-cols-1 md:grid-cols-2 gap-x-8 gap-y-3">
                                <div class="flex items-start gap-3 text-sm text-slate-300 group">
                                    <div class="w-5 h-5 rounded-lg bg-blue-500/10 group-hover:bg-blue-500/20 flex items-center justify-center border border-blue-500/20 mt-0.5 shrink-0 transition-colors">
                                        <x-icon name="bolt" class="w-3 h-3 text-blue-400" />
                                    </div>
                                    <p>Gói VIP có hiệu lực <strong class="text-white">ngay lập tức</strong> sau khi thanh toán thành công.</p>
                                </div>
                                <div class="flex items-start gap-3 text-sm text-slate-300 group">
                                    <div class="w-5 h-5 rounded-lg bg-blue-500/10 group-hover:bg-blue-500/20 flex items-center justify-center border border-blue-500/20 mt-0.5 shrink-0 transition-colors">
                                        <x-icon name="plus" class="w-3 h-3 text-blue-400" />
                                    </div>
                                    <p>Lượt tải sẽ được <strong class="text-white">cộng dồn</strong> nếu bạn gia hạn trước khi hết hạn.</p>
                                </div>
                                <div class="flex items-start gap-3 text-sm text-slate-300 group">
                                    <div class="w-5 h-5 rounded-lg bg-blue-500/10 group-hover:bg-blue-500/20 flex items-center justify-center border border-blue-500/20 mt-0.5 shrink-0 transition-colors">
                                        <x-icon name="calendar-days" class="w-3 h-3 text-blue-400" />
                                    </div>
                                    <p>Thời hạn VIP sẽ được <strong class="text-white">gia hạn thêm</strong> tương ứng khi mua gói mới.</p>
                                </div>
                                <div class="flex items-start gap-3 text-sm text-slate-300 group">
                                    <div class="w-5 h-5 rounded-lg bg-blue-500/10 group-hover:bg-blue-500/20 flex items-center justify-center border border-blue-500/20 mt-0.5 shrink-0 transition-colors">
                                        <x-icon name="document-text" class="w-3 h-3 text-blue-400" />
                                    </div>
                                    <p>Lượt tải chỉ trừ với tài liệu Premium. Tài liệu miễn phí <strong class="text-white">không bị giới hạn</strong>.</p>
                                </div>
                            </div>
                        </div>
                    </div>
                </div>
            </div>
        </div>
    </div>

    {{-- ===== PAYMENT MODAL (Giữ nguyên) ===== --}}
    @include('payment::livewire.user._payment-modal')
</div>
