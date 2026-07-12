<div>
    <div class="max-w-7xl mx-auto pb-8 pt-4 px-4 sm:px-6 lg:px-8 space-y-8">
        
        <!-- Header Section -->
        <div class="bg-linear-to-br from-indigo-500 via-purple-500 to-pink-500 rounded-2xl shadow-xl p-8 sm:p-12 text-white relative overflow-hidden">
            <div class="absolute inset-0 overflow-hidden rounded-2xl">
                <div class="absolute inset-0 bg-[url('https://www.transparenttextures.com/patterns/cubes.png')] opacity-10"></div>
            </div>
            
            <div class="relative z-20">
                <h1 class="text-3xl sm:text-4xl lg:text-5xl font-extrabold mb-2 text-white leading-tight">Lịch sử giao dịch</h1>
                <p class="text-indigo-50 text-sm sm:text-base max-w-xl">Theo dõi tất cả các thanh toán và giao dịch của bạn trên hệ thống.</p>
            </div>
        </div>
        
        <x-card padding="p-4 sm:p-6" class="shadow-sm border border-gray-100">
            <div class="grid grid-cols-1 md:grid-cols-3 gap-4 sm:gap-6">
                <div class="relative" x-data="{ open: false }" @click.away="open = false">
                    <label class="block text-sm font-bold text-gray-700 mb-2">Loại đơn hàng</label>
                    <div @click="open = !open" class="flex items-center justify-between w-full rounded-xl border border-gray-200 bg-gray-50 px-4 py-3 text-sm text-gray-900 cursor-pointer hover:border-indigo-400 transition-colors select-none">
                        <span class="font-medium text-gray-700">
                            @if($orderType === '') Tất cả 
                            @elseif($orderType === 'document') Mua tài liệu
                            @elseif($orderType === 'subscription') Mua gói VIP
                            @endif
                        </span>
                        <svg class="w-4 h-4 text-gray-400 transition-transform duration-200" :class="{'rotate-180': open}" fill="none" stroke="currentColor" viewBox="0 0 24 24"><path stroke-linecap="round" stroke-linejoin="round" stroke-width="2" d="M19 9l-7 7-7-7"/></svg>
                    </div>
                    <div x-show="open" x-cloak x-transition.opacity.duration.200ms style="display: none;"
                         class="absolute top-full left-0 mt-2 w-full bg-white border border-slate-100 rounded-xl shadow-lg py-1.5 z-[60]">
                        <div wire:click="$set('orderType', '')" @click="open = false" 
                             class="px-4 py-2.5 text-sm cursor-pointer transition-colors {{ $orderType === '' ? 'bg-blue-50/60 text-blue-600 font-semibold' : 'text-gray-700 hover:bg-gray-50' }}">Tất cả</div>
                        <div wire:click="$set('orderType', 'document')" @click="open = false" 
                             class="px-4 py-2.5 text-sm cursor-pointer transition-colors {{ $orderType === 'document' ? 'bg-blue-50/60 text-blue-600 font-semibold' : 'text-gray-700 hover:bg-gray-50' }}">Mua tài liệu</div>
                        <div wire:click="$set('orderType', 'subscription')" @click="open = false" 
                             class="px-4 py-2.5 text-sm cursor-pointer transition-colors {{ $orderType === 'subscription' ? 'bg-blue-50/60 text-blue-600 font-semibold' : 'text-gray-700 hover:bg-gray-50' }}">Mua gói VIP</div>
                    </div>
                </div>
                
                <div class="relative" x-data="{ open: false }" @click.away="open = false">
                    <label class="block text-sm font-bold text-gray-700 mb-2">Trạng thái thanh toán</label>
                    <div @click="open = !open" class="flex items-center justify-between w-full rounded-xl border border-gray-200 bg-gray-50 px-4 py-3 text-sm text-gray-900 cursor-pointer hover:border-indigo-400 transition-colors select-none">
                        <span class="font-medium text-gray-700">
                            @if($paymentStatus === '') Tất cả 
                            @elseif($paymentStatus === 'paid') Đã thanh toán
                            @elseif($paymentStatus === 'pending') Chờ thanh toán
                            @elseif($paymentStatus === 'failed') Thất bại
                            @endif
                        </span>
                        <svg class="w-4 h-4 text-gray-400 transition-transform duration-200" :class="{'rotate-180': open}" fill="none" stroke="currentColor" viewBox="0 0 24 24"><path stroke-linecap="round" stroke-linejoin="round" stroke-width="2" d="M19 9l-7 7-7-7"/></svg>
                    </div>
                    <div x-show="open" x-cloak x-transition.opacity.duration.200ms style="display: none;"
                         class="absolute top-full left-0 mt-2 w-full bg-white border border-slate-100 rounded-xl shadow-lg py-1.5 z-[60]">
                        <div wire:click="$set('paymentStatus', '')" @click="open = false" 
                             class="px-4 py-2.5 text-sm cursor-pointer transition-colors {{ $paymentStatus === '' ? 'bg-blue-50/60 text-blue-600 font-semibold' : 'text-gray-700 hover:bg-gray-50' }}">Tất cả</div>
                        <div wire:click="$set('paymentStatus', 'paid')" @click="open = false" 
                             class="px-4 py-2.5 text-sm cursor-pointer transition-colors {{ $paymentStatus === 'paid' ? 'bg-blue-50/60 text-blue-600 font-semibold' : 'text-gray-700 hover:bg-gray-50' }}">Đã thanh toán</div>
                        <div wire:click="$set('paymentStatus', 'pending')" @click="open = false" 
                             class="px-4 py-2.5 text-sm cursor-pointer transition-colors {{ $paymentStatus === 'pending' ? 'bg-blue-50/60 text-blue-600 font-semibold' : 'text-gray-700 hover:bg-gray-50' }}">Chờ thanh toán</div>
                        <div wire:click="$set('paymentStatus', 'failed')" @click="open = false" 
                             class="px-4 py-2.5 text-sm cursor-pointer transition-colors {{ $paymentStatus === 'failed' ? 'bg-blue-50/60 text-blue-600 font-semibold' : 'text-gray-700 hover:bg-gray-50' }}">Thất bại</div>
                    </div>
                </div>
            </div>
        </x-card>

        @if($orders->isEmpty())
            <x-card padding="py-16" class="text-center shadow-sm border border-gray-100">
                <x-icon name="shopping-cart" class="w-16 h-16 mx-auto text-gray-300 mb-4" />
                <h3 class="text-xl font-bold text-blue-900 mb-1">Chưa có giao dịch</h3>
                <p class="text-sm text-gray-500 mb-6">Lịch sử mua hàng của bạn sẽ hiển thị tại đây.</p>
                <x-button primary class="font-bold shadow-md" label="Khám phá tài liệu" icon="magnifying-glass" href="{{ route('documents.index') }}" />
            </x-card>
        @else
            <div class="space-y-4">
                @foreach($orders as $order)
                    <x-card padding="p-4 sm:p-6" class="shadow-sm border border-gray-100 hover:shadow-md transition-shadow">
                        <div class="flex flex-col md:flex-row md:items-start justify-between gap-4 mb-4">
                            <div>
                                <div class="flex flex-wrap items-center gap-3">
                                    <h3 class="text-xl font-bold text-blue-900">#{{ $order->order_code }}</h3>
                                    
                                    @if($order->order_type === 'subscription')
                                        <x-badge flat info label="Gói VIP" />
                                    @else
                                        <span class="inline-flex items-center px-2 py-0.5 rounded-lg text-xs font-bold bg-gray-100 text-gray-700">Tài liệu</span>
                                    @endif
                                    
                                    @if($order->payment_status === 'paid')
                                        <x-badge flat positive label="Đã thanh toán" />
                                    @elseif($order->payment_status === 'pending')
                                        <x-badge flat warning label="Chờ thanh toán" />
                                    @else
                                        <x-badge flat negative label="Thất bại" />
                                    @endif
                                </div>
                                
                                <p class="text-sm font-medium text-gray-400 mt-1.5 flex items-center gap-1.5">
                                    <svg class="w-4 h-4" fill="none" stroke="currentColor" viewBox="0 0 24 24"><path stroke-linecap="round" stroke-linejoin="round" stroke-width="2" d="M12 8v4l3 3m6-3a9 9 0 11-18 0 9 9 0 0118 0z"/></svg>
                                    {{ $order->created_at->format('d/m/Y H:i') }}
                                </p>
                            </div>
                            
                            <div class="md:text-right shrink-0">
                                @if($order->order_type === 'document' && $order->total_amount == 0)
                                    <p class="text-xl font-bold text-amber-500">
                                        <span class="text-sm font-semibold text-gray-500 mr-1">Dùng</span>1 lượt VIP
                                    </p>
                                @else
                                    <p class="text-2xl font-black text-indigo-600">{{ number_format($order->total_amount) }}<span class="text-lg">đ</span></p>
                                @endif
                            </div>
                        </div>

                        @if($order->order_type === 'subscription')
                            <div class="border-t border-gray-100 pt-4 mt-2">
                                <div class="flex items-center gap-3">
                                    <div class="p-2 bg-indigo-50 text-indigo-600 rounded-xl">
                                        <svg class="w-5 h-5" fill="none" stroke="currentColor" viewBox="0 0 24 24"><path stroke-linecap="round" stroke-linejoin="round" stroke-width="2" d="M5 3v4M3 5h4M6 17v4m-2-2h4m5-16l2.286 6.857L21 12l-5.714 2.143L13 21l-2.286-6.857L5 12l5.714-2.143L13 3z"/></svg>
                                    </div>
                                    <div>
                                        <p class="text-sm font-bold text-gray-900">
                                            Gói {{ config("subscription.packages.{$order->subscription_package_key}.name", 'VIP') }}
                                        </p>
                                        <p class="text-xs text-gray-500">Kích hoạt các quyền lợi VIP của nền tảng.</p>
                                    </div>
                                </div>
                            </div>
                        @else
                            <div class="border-t border-gray-100 pt-4 mt-2 space-y-3">
                                @foreach($order->items as $item)
                                    <div class="flex items-center gap-4">
                                        <div class="p-2.5 bg-gray-50 text-gray-400 rounded-xl">
                                            <svg class="w-5 h-5" fill="none" stroke="currentColor" viewBox="0 0 24 24"><path stroke-linecap="round" stroke-linejoin="round" stroke-width="2" d="M9 12h6m-6 4h6m2 5H7a2 2 0 01-2-2V5a2 2 0 012-2h5.586a1 1 0 01.707.293l5.414 5.414a1 1 0 01.293.707V19a2 2 0 01-2 2z"/></svg>
                                        </div>
                                        <div class="flex-1 min-w-0">
                                            <p class="text-sm font-bold text-gray-900 truncate">
                                                {{ $item->document_title_snapshot }}
                                            </p>
                                            <p class="text-xs font-medium text-gray-500 mt-0.5">
                                                @if($order->total_amount == 0)
                                                    <span class="text-amber-500">Thanh toán bằng lượt tải VIP</span>
                                                @else
                                                    {{ number_format($item->unit_price) }}đ × {{ $item->quantity }}
                                                @endif
                                            </p>
                                        </div>
                                    </div>
                                @endforeach
                            </div>
                        @endif

                        @if($order->paid_at)
                            <div class="mt-4 pt-4 border-t border-gray-50">
                                @php $payment = $order->payments->last(); @endphp
                                @if($payment && $order->total_amount > 0)
                                    <div class="bg-emerald-50/50 border border-emerald-100 rounded-xl p-4 mb-3">
                                        <div class="flex items-center gap-2 mb-2">
                                            <svg class="w-5 h-5 text-emerald-500" fill="none" stroke="currentColor" viewBox="0 0 24 24"><path stroke-linecap="round" stroke-linejoin="round" stroke-width="2" d="M9 12l2 2 4-4m6 2a9 9 0 11-18 0 9 9 0 0118 0z"/></svg>
                                            <span class="text-sm font-bold text-emerald-900">Chi tiết thanh toán</span>
                                        </div>
                                        <div class="grid grid-cols-1 sm:grid-cols-2 gap-3 text-sm mt-3">
                                            <div>
                                                <span class="text-gray-500">Mã giao dịch:</span>
                                                <span class="font-bold text-gray-900 ml-1">{{ $payment->transaction_code }}</span>
                                            </div>
                                            <div>
                                                <span class="text-gray-500">Kênh thanh toán:</span>
                                                <span class="font-bold uppercase text-gray-900 ml-1">{{ $payment->provider }}</span>
                                            </div>
                                        </div>
                                    </div>
                                @endif
                                <div class="flex items-center justify-between">
                                    <span class="text-[11px] font-bold text-gray-400 uppercase tracking-wider">Thời gian thanh toán</span>
                                    <span class="text-xs font-semibold text-gray-600">{{ $order->paid_at->format('d/m/Y H:i') }}</span>
                                </div>
                            </div>
                        @elseif($order->payment_status === 'pending' && $order->total_amount > 0)
                            <div class="mt-4 pt-4 border-t border-gray-50">
                                <div class="bg-blue-50/50 border border-blue-100 rounded-xl p-4">
                                    <div class="flex items-center justify-between mb-2">
                                        <span class="text-sm font-bold text-blue-900">Thông tin thanh toán</span>
                                        @if(!empty($order->checkout_data['checkoutUrl']))
                                            <a href="{{ $order->checkout_data['checkoutUrl'] }}" target="_blank" class="text-xs font-semibold text-blue-600 hover:text-blue-700 bg-blue-100 hover:bg-blue-200 px-3 py-1.5 rounded-lg transition-colors">
                                                Mở cổng thanh toán &rarr;
                                            </a>
                                        @endif
                                    </div>
                                    <div class="grid grid-cols-1 sm:grid-cols-2 gap-3 text-sm">
                                        <div>
                                            <span class="text-gray-500">Số tiền:</span>
                                            <span class="font-bold text-indigo-600 ml-1">{{ number_format($order->total_amount) }}đ</span>
                                        </div>
                                        <div>
                                            <span class="text-gray-500">Nội dung CK:</span>
                                            <span class="font-bold text-gray-900 ml-1">ITL {{ $order->order_code }}</span>
                                        </div>
                                    </div>
                                    <p class="text-[11px] text-gray-400 mt-3 italic">* Đơn hàng sẽ tự động hủy nếu không được thanh toán trong vòng 10 phút.</p>
                                </div>
                            </div>
                        @endif
                    </x-card>
                @endforeach
            </div>

            <div class="mt-8">
                <x-card class="shadow-sm border border-gray-100">
                    {{ $orders->links() }}
                </x-card>
            </div>
        @endif
    </div>
</div>
