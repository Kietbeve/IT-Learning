<div>
    <x-modal wire:model="showModal" max-width="lg" persistent>
        <div class="relative z-10 bg-white rounded-2xl shadow-2xl p-6 space-y-6">
            {{-- Header --}}
            <div class="flex items-center justify-between">
                <h2 class="text-xl font-bold text-slate-900">Xác nhận thanh toán</h2>
                <button wire:click="close" class="rounded-full p-1.5 text-slate-400 hover:bg-slate-100 hover:text-slate-600 transition">
                    <x-icon name="x-mark" class="w-5 h-5" />
                </button>
            </div>

            {{-- Order Summary --}}
            <div class="rounded-2xl border border-slate-200 bg-slate-50 p-4 space-y-4">
                <div class="flex gap-4">
                    {{-- Thumbnail --}}
                    <div class="h-20 w-20 shrink-0 rounded-xl overflow-hidden bg-slate-200 flex items-center justify-center">
                        @if(isset($document['thumbnail']) && $document['thumbnail'])
                            <img src="{{ asset('storage/' . $document['thumbnail']) }}" alt="{{ $document['title'] }}" class="h-full w-full object-cover">
                        @else
                            <x-icon name="document-text" class="w-8 h-8 text-slate-400" />
                        @endif
                    </div>

                    {{-- Info --}}
                    <div class="flex-1 min-w-0 space-y-1">
                        <span class="text-xs font-semibold text-blue-600 uppercase tracking-wider">{{ $document['category_name'] ?? '' }}</span>
                        <h3 class="text-base font-bold text-slate-900 line-clamp-2">{{ $document['title'] ?? '' }}</h3>
                        <div class="flex items-center gap-3 text-xs text-slate-500">
                            <span class="flex items-center gap-1">
                                <x-icon name="user" class="w-3.5 h-3.5" />
                                {{ $document['author_name'] ?? '' }}
                            </span>
                            <span class="inline-flex items-center rounded-lg bg-slate-200 px-2 py-0.5 text-[10px] font-bold uppercase text-slate-600">
                                {{ $document['file_type'] ?? '' }}
                            </span>
                        </div>
                    </div>
                </div>

                {{-- Price --}}
                <div class="border-t border-slate-200 pt-3 flex items-center justify-between">
                    <span class="text-sm font-semibold text-slate-600">Tạm tính:</span>
                    <div class="text-right">
                        @if($product['sale_price'] ?? false)
                            <span class="text-sm text-slate-400 line-through mr-2">{{ number_format($product['price']) }}đ</span>
                        @endif
                        <span class="text-xl font-bold text-blue-600">{{ number_format($product['sale_price'] ?? $product['price'] ?? 0) }}đ</span>
                    </div>
                </div>
            </div>

            {{-- Payment Method --}}
            <div class="space-y-3">
                <label class="text-xs font-bold text-slate-500 uppercase tracking-wider block">Phương thức thanh toán</label>
                <div class="flex items-center gap-4 rounded-2xl border-2 border-blue-500 bg-blue-50 p-4">
                    <div class="h-10 w-10 shrink-0 rounded-xl bg-white flex items-center justify-center shadow-sm">
                        <img src="https://payos.vn/wp-content/uploads/sites/8/2024/04/cropped-favicon-192x192.png" alt="PayOS" class="h-7 w-7 object-contain">
                    </div>
                    <div>
                        <p class="text-sm font-bold text-slate-900">PayOS</p>
                        <p class="text-xs text-slate-500">Thanh toán qua chuyển khoản, thẻ tín dụng, ví điện tử</p>
                    </div>
                    <x-icon name="check-circle" class="w-6 h-6 text-blue-600 ml-auto shrink-0" />
                </div>
            </div>

            {{-- Actions --}}
            <div class="border-t border-slate-100 pt-4 flex items-center justify-end gap-3">
                <x-button flat label="Hủy" wire:click="close" />
                <x-button
                    primary
                    wire:click="processPayment"
                    wire:target="processPayment"
                    :disabled="$loading"
                    icon="credit-card"
                >
                    <span wire:loading.remove wire:target="processPayment">
                        Thanh toán - {{ number_format(($product['sale_price'] ?? $product['price'] ?? 0)) }}đ
                    </span>
                    <span wire:loading wire:target="processPayment">
                        Đang kết nối cổng thanh toán...
                    </span>
                </x-button>
            </div>
        </div>
    </x-modal>
</div>
