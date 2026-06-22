<div>
    @section('content')
    <div class="max-w-7xl mx-auto py-6">
        <h2 class="text-3xl font-bold text-gray-900 mb-6">Lịch sử giao dịch</h2>
        
        <div class="grid grid-cols-1 md:grid-cols-3 gap-4 mb-6">
            <div>
                <label class="block text-sm font-medium text-gray-700 mb-2">Loại đơn hàng</label>
                <select wire:model.live="orderType" class="w-full rounded-lg border-gray-300 focus:border-primary-500 focus:ring-primary-500">
                    <option value="">Tất cả</option>
                    <option value="document">Mua tài liệu</option>
                    <option value="subscription">Mua gói VIP</option>
                </select>
            </div>
            
            <div>
                <label class="block text-sm font-medium text-gray-700 mb-2">Trạng thái thanh toán</label>
                <select wire:model.live="paymentStatus" class="w-full rounded-lg border-gray-300 focus:border-primary-500 focus:ring-primary-500">
                    <option value="">Tất cả</option>
                    <option value="paid">Đã thanh toán</option>
                    <option value="pending">Chờ thanh toán</option>
                    <option value="failed">Thất bại</option>
                </select>
            </div>
        </div>

        @if($orders->isEmpty())
            <div class="text-center py-10 bg-white rounded-lg border">
                <x-icon name="shopping-cart" class="w-12 h-12 mx-auto text-gray-400" />
                <h3 class="mt-2 text-sm font-semibold text-gray-900">Chưa có giao dịch</h3>
                <p class="mt-1 text-sm text-gray-500">Lịch sử mua hàng của bạn sẽ hiển thị tại đây.</p>
                <div class="mt-6">
                    <x-button primary label="Khám phá tài liệu" icon="magnifying-glass" href="{{ route('documents.index') }}" />
                </div>
            </div>
        @else
            <div class="space-y-4">
                @foreach($orders as $order)
                    <div class="bg-white rounded-lg border p-4 hover:shadow-md transition">
                        <div class="flex items-start justify-between mb-3">
                            <div>
                                <div class="flex items-center gap-3">
                                    <h3 class="text-lg font-semibold text-gray-900">#{{ $order->order_code }}</h3>
                                    
                                    @if($order->order_type === 'subscription')
                                        <x-badge flat primary label="Gói VIP" />
                                    @else
                                        <x-badge flat secondary label="Tài liệu" />
                                    @endif
                                    
                                    @if($order->payment_status === 'paid')
                                        <x-badge flat positive label="Đã thanh toán" />
                                    @elseif($order->payment_status === 'pending')
                                        <x-badge flat warning label="Chờ thanh toán" />
                                    @else
                                        <x-badge flat negative label="Thất bại" />
                                    @endif
                                </div>
                                
                                <p class="text-sm text-gray-500 mt-1">
                                    {{ $order->created_at->format('d/m/Y H:i') }}
                                </p>
                            </div>
                            
                            <div class="text-right">
                                <p class="text-2xl font-bold text-primary-600">{{ number_format($order->total_amount) }}đ</p>
                            </div>
                        </div>

                        @if($order->order_type === 'subscription')
                            <div class="border-t pt-3 mt-3">
                                <p class="text-sm text-gray-700">
                                    <span class="font-semibold">Gói:</span> 
                                    {{ config("subscription.packages.{$order->subscription_package_key}.name", 'VIP') }}
                                </p>
                            </div>
                        @else
                            <div class="border-t pt-3 mt-3">
                                @foreach($order->items as $item)
                                    <div class="flex items-center gap-3 py-2">
                                        <x-icon name="document-text" class="w-5 h-5 text-gray-400" />
                                        <div class="flex-1 min-w-0">
                                            <p class="text-sm font-medium text-gray-900 truncate">
                                                {{ $item->document_title_snapshot }}
                                            </p>
                                            <p class="text-xs text-gray-500">
                                                {{ number_format($item->unit_price) }}đ × {{ $item->quantity }}
                                            </p>
                                        </div>
                                    </div>
                                @endforeach
                            </div>
                        @endif

                        @if($order->paid_at)
                            <div class="border-t pt-3 mt-3">
                                <p class="text-xs text-gray-500">
                                    Thanh toán lúc: {{ $order->paid_at->format('d/m/Y H:i') }}
                                </p>
                            </div>
                        @endif
                    </div>
                @endforeach
            </div>

            <div class="mt-6">
                {{ $orders->links() }}
            </div>
        @endif
    </div>
    @endsection
</div>
