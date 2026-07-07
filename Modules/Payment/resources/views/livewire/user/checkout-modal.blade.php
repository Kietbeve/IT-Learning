<div>
    <x-modal wire:model="showModal" max-width="2xl" persistent>
        <div class="relative z-10 bg-white rounded-2xl shadow-2xl p-6 md:p-8">
            {{-- Header --}}
            <div class="flex items-center justify-between mb-6">
                <h2 class="text-xl font-bold text-slate-900">Thanh toán tài liệu</h2>
                <button wire:click="close" class="rounded-full p-1.5 text-slate-400 hover:bg-slate-100 hover:text-slate-600 transition">
                    <x-icon name="x-mark" class="w-5 h-5" />
                </button>
            </div>

            <div 
                class="w-full" wire:poll.3s="checkPaymentStatus"
                x-data="{ isExpired: false }"
                @payment-expired="isExpired = true"
                @reset-timer.window="isExpired = false"
                @payment-completed.window="$wire.close()"
                @document-purchased-refresh.window="$wire.close()"
            >
                    <div x-show="isExpired" class="text-center py-8">
                        <div class="mx-auto flex h-16 w-16 items-center justify-center rounded-full bg-red-100 mb-4">
                            <x-icon name="x-mark" class="w-8 h-8 text-red-500" />
                        </div>
                        <h3 class="text-xl font-bold text-slate-900 mb-2">Mã QR đã hết hạn</h3>
                        <p class="text-slate-500 mb-6">Vui lòng đóng và thực hiện mua lại.</p>
                        <x-button primary label="Đóng" wire:click="close" />
                    </div>

                    <div x-show="!isExpired" class="grid grid-cols-1 md:grid-cols-2 gap-8 items-start">
                        {{-- Left: Bank Info --}}
                        <div class="space-y-4 mt-2">
                            <h3 class="text-lg font-bold text-slate-900">Thông tin chuyển khoản</h3>
                            <p class="text-sm text-slate-500 mb-4">Sử dụng Ứng dụng ngân hàng hoặc Ví điện tử để quét mã hoặc chuyển khoản thủ công.</p>
                            
                            @if(!empty($paymentData['accountNumber']))
                            <div class="bg-slate-50 border border-slate-200 rounded-xl p-4 text-sm space-y-3">
                                <div class="flex justify-between items-center">
                                    <span class="text-slate-500">Ngân hàng:</span>
                                    <span class="font-medium text-slate-900 text-right">{{ $paymentData['bankName'] ?? 'Vietcombank' }}</span>
                                </div>
                                <div class="flex justify-between items-center">
                                    <span class="text-slate-500">Số tài khoản:</span>
                                    <span class="font-bold text-slate-900 text-right">{{ $paymentData['accountNumber'] ?? '' }}</span>
                                </div>
                                <div class="flex justify-between items-center">
                                    <span class="text-slate-500">Chủ tài khoản:</span>
                                    <span class="font-medium text-slate-900 text-right">{{ $paymentData['accountName'] ?? '' }}</span>
                                </div>
                                <div class="flex justify-between items-center pt-2 border-t border-slate-200">
                                    <span class="text-slate-500">Nội dung CK:</span>
                                    <span class="font-bold text-blue-600 text-right">{{ $paymentData['description'] ?? '' }}</span>
                                </div>
                                <div class="flex justify-between items-center pt-2 border-t border-slate-200">
                                    <span class="text-slate-500">Số tiền:</span>
                                    <span class="font-bold text-blue-600 text-lg text-right">{{ number_format($paymentData['amount'] ?? 0) }}đ</span>
                                </div>
                            </div>
                            @endif
                        </div>

                        {{-- Right: QR Code --}}
                        <div class="md:pl-8 md:border-l border-slate-200 flex flex-col items-center justify-center space-y-5">
                            <div class="bg-gradient-to-r from-blue-500 to-purple-600 rounded-xl p-3 text-center shadow-lg w-full max-w-[250px]">
                                <div class="text-white" wire:ignore>
                                    <span class="text-sm font-medium opacity-90">Mã QR sẽ hết hạn sau</span>
                                    <div class="text-3xl font-bold tracking-wider font-mono mt-1" 
                                         x-data="{
                                             time: {{ $remainingSeconds ?? 600 }},
                                             endTime: 0,
                                             interval: null,
                                             start(seconds) {
                                                 this.time = seconds;
                                                 this.endTime = Date.now() + (seconds * 1000);
                                                 if (this.interval) clearInterval(this.interval);
                                                 this.interval = setInterval(() => {
                                                     this.time = Math.round((this.endTime - Date.now()) / 1000);
                                                     if (this.time <= 0) {
                                                         this.time = 0;
                                                         clearInterval(this.interval);
                                                         $dispatch('payment-expired');
                                                     }
                                                 }, 1000);
                                             },
                                             init() {
                                                 this.start(this.time);
                                             },
                                             destroy() {
                                                 if (this.interval) clearInterval(this.interval);
                                             },
                                             formatTime() {
                                                 let m = Math.floor(this.time / 60);
                                                 let s = this.time % 60;
                                                 return `${m}:${s.toString().padStart(2, '0')}`;
                                             }
                                         }"
                                         @reset-timer.window="start($event.detail.seconds)"
                                         x-text="formatTime()"
                                    ></div>
                                </div>
                            </div>

                            <div class="bg-white p-4 rounded-3xl border border-slate-200 flex items-center justify-center relative overflow-hidden shadow-sm w-full max-w-[250px] aspect-square">
                                @if(empty($paymentData))
                                    <div class="flex flex-col items-center justify-center text-slate-400 space-y-4">
                                        <svg class="animate-spin h-8 w-8 text-blue-600" xmlns="http://www.w3.org/2000/svg" fill="none" viewBox="0 0 24 24">
                                            <circle class="opacity-25" cx="12" cy="12" r="10" stroke="currentColor" stroke-width="4"></circle>
                                            <path class="opacity-75" fill="currentColor" d="M4 12a8 8 0 018-8V0C5.373 0 0 5.373 0 12h4zm2 5.291A7.962 7.962 0 014 12H0c0 3.042 1.135 5.824 3 7.938l3-2.647z"></path>
                                        </svg>
                                        <p class="text-sm font-semibold">Đang tạo mã...</p>
                                    </div>
                                @elseif(!empty($paymentData['bin']) && !empty($paymentData['accountNumber']))
                                    <img 
                                        src="https://img.vietqr.io/image/{{ $paymentData['bin'] }}-{{ $paymentData['accountNumber'] }}-compact2.jpg?amount={{ $paymentData['amount'] }}&addInfo={{ urlencode($paymentData['description']) }}&accountName={{ urlencode($paymentData['accountName'] ?? '') }}" 
                                        alt="Mã QR Thanh Toán" 
                                        class="w-full h-full object-contain transition-transform duration-300"
                                    >
                                @else
                                    <img src="{{ $paymentData['qrCode'] ?? '' }}" alt="Mã QR Thanh Toán" class="w-full h-full object-contain transition-transform duration-300">
                                @endif
                            </div>
                        </div>
                    </div>
                </div>
            </div>
    </x-modal>
</div>
