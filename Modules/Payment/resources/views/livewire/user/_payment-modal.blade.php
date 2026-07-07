{{-- VIP Payment Modal --}}
@if($showPaymentModal)
<div
    wire:poll.3s="checkPaymentStatus"
    x-data="{
        isExpired: false,
        checking: false,
    }"
    @close-payment-modal.window="isExpired = false"
    @payment-success.window="$wire.closePaymentModal()"
    class="fixed inset-0 z-50 overflow-y-auto"
>
    {{-- Backdrop --}}
    <div class="fixed inset-0 bg-black/50 backdrop-blur-sm"></div>

    {{-- Modal --}}
    <div class="flex min-h-screen items-center justify-center p-4">
        <div class="relative w-full max-w-2xl bg-white rounded-2xl shadow-2xl overflow-hidden">

            {{-- Expired Overlay --}}
            <div
                x-show="isExpired"
                x-transition:enter="transition ease-out duration-200"
                x-transition:enter-start="opacity-0 scale-95"
                x-transition:enter-end="opacity-100 scale-100"
                class="absolute inset-0 bg-white z-30 flex flex-col items-center justify-center p-8 text-center"
            >
                <div class="mx-auto flex h-16 w-16 items-center justify-center rounded-full bg-red-100 mb-4">
                    <svg class="w-8 h-8 text-red-500" fill="none" stroke="currentColor" viewBox="0 0 24 24">
                        <path stroke-linecap="round" stroke-linejoin="round" stroke-width="2" d="M6 18L18 6M6 6l12 12"/>
                    </svg>
                </div>
                <h3 class="text-xl font-bold text-slate-900 mb-2">Đơn hàng đã hết hạn</h3>
                <p class="text-slate-500 mb-6">Vui lòng đóng và thực hiện mua lại gói VIP.</p>
                <button
                    wire:click="closePaymentModal"
                    class="px-6 py-2 text-sm font-semibold text-white bg-red-600 hover:bg-red-700 rounded-xl transition shadow"
                >
                    Đóng và Mua lại
                </button>
            </div>

            {{-- Header --}}
            <div class="flex items-center justify-between px-6 py-4 border-b border-slate-100">
                <h2 class="text-xl font-bold text-slate-900">Thanh toán gói VIP</h2>
                <button wire:click="closePaymentModal" class="rounded-full p-1.5 text-slate-400 hover:bg-slate-100 hover:text-slate-600 transition">
                    <svg class="w-5 h-5" fill="none" stroke="currentColor" viewBox="0 0 24 24">
                        <path stroke-linecap="round" stroke-linejoin="round" stroke-width="2" d="M6 18L18 6M6 6l12 12"/>
                    </svg>
                </button>
            </div>

            {{-- Body --}}
            <div class="p-6 md:p-8">
                <div class="grid grid-cols-1 md:grid-cols-2 gap-8 items-start">

                    {{-- Left: Bank Info --}}
                    <div class="space-y-4 mt-2">
                        <h3 class="text-lg font-bold text-slate-900">Thông tin chuyển khoản</h3>
                        <p class="text-sm text-slate-500">Sử dụng ứng dụng ngân hàng hoặc ví điện tử để quét mã QR hoặc chuyển khoản thủ công.</p>

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

                        {{-- Auto polling indicator --}}
                        <div class="flex items-center gap-2 text-xs text-slate-400 pt-2">
                            <span class="flex h-2 w-2 relative">
                                <span class="animate-ping absolute inline-flex h-full w-full rounded-full bg-green-400 opacity-75"></span>
                                <span class="relative inline-flex rounded-full h-2 w-2 bg-green-500"></span>
                            </span>
                            Hệ thống tự động phát hiện thanh toán mỗi 3 giây
                        </div>
                    </div>

                    {{-- Right: QR Code + Timer --}}
                    <div class="md:pl-8 md:border-l border-slate-200 flex flex-col items-center justify-center space-y-5">
                        {{-- Timer --}}
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
                                                     isExpired = true;
                                                 }
                                             }, 1000);
                                         },
                                         init() { this.start(this.time); },
                                         destroy() { if (this.interval) clearInterval(this.interval); },
                                         formatTime() {
                                             let m = Math.floor(this.time / 60);
                                             let s = this.time % 60;
                                             return `${m}:${s.toString().padStart(2, '0')}`;
                                         }
                                     }"
                                     x-text="formatTime()"
                                ></div>
                            </div>
                        </div>

                        {{-- QR --}}
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
                                    class="w-full h-full object-contain"
                                >
                            @else
                                <img src="{{ $paymentData['qrCode'] ?? '' }}" alt="Mã QR Thanh Toán" class="w-full h-full object-contain">
                            @endif
                        </div>
                    </div>
                </div>
            </div>

        </div>
    </div>
</div>
@endif
