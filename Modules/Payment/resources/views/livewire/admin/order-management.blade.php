<div class="space-y-6">
    <div class="grid gap-6 sm:grid-cols-2 lg:grid-cols-4">
        <div class="rounded-2xl border border-gray-200 bg-white p-6">
            <div class="flex items-center justify-between">
                <p class="text-sm font-medium text-gray-600">Tổng đơn hàng</p>
                <span class="rounded-xl bg-blue-50 p-2">
                    <svg class="h-5 w-5 text-blue-600" fill="none" stroke="currentColor" viewBox="0 0 24 24">
                        <path stroke-linecap="round" stroke-linejoin="round" stroke-width="2" d="M16 11V7a4 4 0 00-8 0v4M5 9h14l1 12H4L5 9z"/>
                    </svg>
                </span>
            </div>
            <p class="mt-4 text-3xl font-bold text-gray-900">{{ number_format($stats['total_orders']) }}</p>
            <p class="mt-1 text-sm text-gray-500">{{ number_format($stats['paid_orders']) }} đã thanh toán</p>
        </div>

        <div class="rounded-2xl border border-gray-200 bg-white p-6">
            <div class="flex items-center justify-between">
                <p class="text-sm font-medium text-gray-600">Tổng doanh thu</p>
                <span class="rounded-xl bg-green-50 p-2">
                    <svg class="h-5 w-5 text-green-600" fill="none" stroke="currentColor" viewBox="0 0 24 24">
                        <path stroke-linecap="round" stroke-linejoin="round" stroke-width="2" d="M12 8c-1.657 0-3 .895-3 2s1.343 2 3 2 3 .895 3 2-1.343 2-3 2m0-8c1.11 0 2.08.402 2.599 1M12 8V7m0 1v8m0 0v1m0-1c-1.11 0-2.08-.402-2.599-1M21 12a9 9 0 11-18 0 9 9 0 0118 0z"/>
                    </svg>
                </span>
            </div>
            <p class="mt-4 text-3xl font-bold text-green-700">{{ number_format($stats['total_revenue']) }}đ</p>
            <div class="mt-2 flex items-center gap-2 text-xs">
                <span class="text-gray-500">Contributor: {{ number_format($stats['total_contributor_amount']) }}đ</span>
                <span class="text-gray-400">•</span>
                <span class="text-gray-500">Platform: {{ number_format($stats['total_platform_amount']) }}đ</span>
            </div>
        </div>

        <div class="rounded-2xl border border-gray-200 bg-white p-6">
            <div class="flex items-center justify-between">
                <p class="text-sm font-medium text-gray-600">Chờ thanh toán</p>
                <span class="rounded-xl bg-amber-50 p-2">
                    <svg class="h-5 w-5 text-amber-600" fill="none" stroke="currentColor" viewBox="0 0 24 24">
                        <path stroke-linecap="round" stroke-linejoin="round" stroke-width="2" d="M12 8v4l3 3m6-3a9 9 0 11-18 0 9 9 0 0118 0z"/>
                    </svg>
                </span>
            </div>
            <p class="mt-4 text-3xl font-bold text-gray-900">{{ number_format($stats['pending_orders']) }}</p>
            <p class="mt-1 text-sm text-gray-500">Đơn hàng chưa hoàn tất</p>
        </div>

        <div class="rounded-2xl border border-gray-200 bg-white p-6">
            <div class="flex items-center justify-between">
                <p class="text-sm font-medium text-gray-600">Đăng ký VIP</p>
                <span class="rounded-xl bg-purple-50 p-2">
                    <svg class="h-5 w-5 text-purple-600" fill="none" stroke="currentColor" viewBox="0 0 24 24">
                        <path stroke-linecap="round" stroke-linejoin="round" stroke-width="2" d="M5 3v4M3 5h4M6 17v4m-2-2h4m5-16l2.286 6.857L21 12l-5.714 2.143L13 21l-2.286-6.857L5 12l5.714-2.143L13 3z"/>
                    </svg>
                </span>
            </div>
            <p class="mt-4 text-3xl font-bold text-gray-900">{{ number_format($stats['subscription_count']) }}</p>
            <p class="mt-1 text-sm text-gray-500">{{ number_format($stats['subscription_revenue']) }}đ doanh thu</p>
        </div>
    </div>

    <div class="rounded-2xl border border-gray-200 bg-white">
        <div class="border-b border-gray-200 p-6">
            <div class="flex flex-col gap-4 sm:flex-row sm:items-center sm:justify-between">
                <div>
                    <h2 class="text-lg font-semibold text-gray-900">Đơn hàng</h2>
                    <p class="text-sm text-gray-500">Danh sách tất cả đơn hàng mua tài liệu</p>
                </div>
                <div class="flex flex-wrap gap-3">
                    <select wire:model.live="statusFilter" class="rounded-xl border-gray-300 text-sm">
                        <option value="all">Tất cả trạng thái</option>
                        <option value="paid">Đã thanh toán</option>
                        <option value="pending">Chờ thanh toán</option>
                        <option value="failed">Thất bại</option>
                    </select>
                    <select wire:model.live="orderTypeFilter" class="rounded-xl border-gray-300 text-sm">
                        <option value="all">Tất cả loại</option>
                        <option value="document">Mua tài liệu</option>
                        <option value="subscription">Đăng ký VIP</option>
                    </select>
                    <input type="date" wire:model.live="dateFrom" class="rounded-xl border-gray-300 text-sm">
                    <input type="date" wire:model.live="dateTo" class="rounded-xl border-gray-300 text-sm">
                    <input type="search" wire:model.live.debounce.300ms="search" placeholder="Tìm user..."
                           class="rounded-xl border-gray-300 text-sm w-48" />
                </div>
            </div>
        </div>

        <div class="overflow-x-auto">
            <table class="w-full">
                <thead class="border-b border-gray-200 bg-gray-50">
                    <tr>
                        <th class="px-6 py-3 text-left text-xs font-semibold uppercase text-gray-600">Mã ĐH</th>
                        <th class="px-6 py-3 text-left text-xs font-semibold uppercase text-gray-600">Loại</th>
                        <th class="px-6 py-3 text-left text-xs font-semibold uppercase text-gray-600">Người mua</th>
                        <th class="px-6 py-3 text-left text-xs font-semibold uppercase text-gray-600">Tài liệu</th>
                        <th class="px-6 py-3 text-left text-xs font-semibold uppercase text-gray-600">Người bán</th>
                        <th class="px-6 py-3 text-right text-xs font-semibold uppercase text-gray-600 cursor-pointer hover:bg-gray-100" wire:click="sortBy('total_amount')">
                            Tổng tiền
                            @if($sortField === 'total_amount')
                                <span class="text-xs">{{ $sortDirection === 'asc' ? '↑' : '↓' }}</span>
                            @endif
                        </th>
                        <th class="px-6 py-3 text-left text-xs font-semibold uppercase text-gray-600">Trạng thái</th>
                        <th class="px-6 py-3 text-left text-xs font-semibold uppercase text-gray-600 cursor-pointer hover:bg-gray-100" wire:click="sortBy('created_at')">
                            Ngày tạo
                            @if($sortField === 'created_at')
                                <span class="text-xs">{{ $sortDirection === 'asc' ? '↑' : '↓' }}</span>
                            @endif
                        </th>
                    </tr>
                </thead>
                <tbody class="divide-y divide-slate-200">
                    @forelse($orders as $order)
                        <tr class="hover:bg-gray-50">
                            <td class="px-6 py-4 text-sm font-mono text-gray-900">
                                #{{ $order->order_code }}
                            </td>
                            <td class="px-6 py-4">
                                @if($order->order_type === 'subscription')
                                    <span class="inline-flex rounded-full bg-purple-100 px-2.5 py-0.5 text-xs font-semibold text-purple-800">Đăng ký VIP</span>
                                @elseif($order->total_amount > 0)
                                    <span class="inline-flex rounded-full bg-blue-100 px-2.5 py-0.5 text-xs font-semibold text-blue-800">Mua trực tiếp</span>
                                @else
                                    <span class="inline-flex rounded-full bg-teal-100 px-2.5 py-0.5 text-xs font-semibold text-teal-800">Tải bằng VIP</span>
                                @endif
                            </td>
                            <td class="px-6 py-4">
                                <p class="font-semibold text-gray-900">{{ $order->user->name ?? 'N/A' }}</p>
                                <p class="text-xs text-gray-500">{{ $order->user->email ?? '' }}</p>
                            </td>
                            <td class="px-6 py-4">
                                @if($order->order_type === 'subscription')
                                    <p class="text-sm text-gray-500 italic">Đăng ký VIP</p>
                                @else
                                    @foreach($order->items as $item)
                                        <div class="mb-1 last:mb-0">
                                            <p class="text-sm font-medium text-gray-900">{{ $item->document_title_snapshot ?? 'N/A' }}</p>
                                            <p class="text-xs text-gray-500">
                                                Contributor: {{ number_format($item->contributor_amount) }}đ
                                                <span class="text-gray-400">•</span>
                                                Platform: {{ number_format($item->platform_amount) }}đ
                                            </p>
                                        </div>
                                    @endforeach
                                @endif
                            </td>
                            <td class="px-6 py-4">
                                @if($order->order_type === 'subscription')
                                    <p class="text-sm text-gray-500">—</p>
                                @else
                                    @foreach($order->items as $item)
                                        <div class="mb-1 last:mb-0">
                                            <p class="text-sm text-gray-900">{{ $item->document->author->name ?? 'N/A' }}</p>
                                        </div>
                                    @endforeach
                                @endif
                            </td>
                            <td class="px-6 py-4 text-right">
                                <p class="font-bold text-gray-900">{{ number_format($order->total_amount) }}đ</p>
                            </td>
                            <td class="px-6 py-4">
                                @if($order->payment_status === 'paid')
                                    <span class="inline-flex rounded-full bg-green-100 px-2.5 py-0.5 text-xs font-semibold text-green-800">Đã thanh toán</span>
                                @elseif($order->payment_status === 'pending')
                                    <span class="inline-flex rounded-full bg-amber-100 px-2.5 py-0.5 text-xs font-semibold text-amber-800">Chờ thanh toán</span>
                                @elseif($order->payment_status === 'failed')
                                    <span class="inline-flex rounded-full bg-rose-100 px-2.5 py-0.5 text-xs font-semibold text-rose-800">Thất bại</span>
                                @else
                                    <span class="inline-flex rounded-full bg-gray-100 px-2.5 py-0.5 text-xs font-semibold text-gray-800">{{ $order->payment_status }}</span>
                                @endif
                            </td>
                            <td class="px-6 py-4 text-sm text-gray-500">
                                {{ $order->created_at->format('d/m/Y H:i') }}
                            </td>
                        </tr>
                    @empty
                        <tr>
                            <td colspan="8" class="px-6 py-12 text-center text-gray-500">
                                Không có đơn hàng nào
                            </td>
                        </tr>
                    @endforelse
                </tbody>
            </table>
        </div>

        @if($orders->hasPages())
            <div class="border-t border-gray-200 p-4">
                {{ $orders->links() }}
            </div>
        @endif
    </div>
</div>

