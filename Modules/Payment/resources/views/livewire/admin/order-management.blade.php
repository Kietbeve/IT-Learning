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

    <div class="py-2">
        {{-- Header --}}
        <div class="flex flex-col gap-4 sm:flex-row sm:items-center sm:justify-between mb-4">
            <div>
                <h2 class="text-lg font-bold text-gray-900">Đơn hàng</h2>
                <p class="text-sm text-gray-500 mt-1">Danh sách tất cả đơn hàng mua tài liệu</p>
            </div>
        </div>

        {{-- Bộ lọc --}}
        <div class="flex flex-col sm:flex-row flex-wrap items-center gap-3 mb-6">
            <div class="relative w-full sm:w-auto min-w-[200px]" x-data="{ open: false }" @click.away="open = false">
                <div @click="open = !open" 
                     class="flex items-center justify-between w-full rounded-xl border border-gray-200 bg-white px-4 py-2.5 text-sm text-gray-900 cursor-pointer hover:bg-gray-50 transition-colors shadow-sm h-[42px] ring-1 ring-inset ring-gray-300">
                    <div class="flex items-center gap-2">
                        <svg class="w-4 h-4 text-gray-400" fill="none" stroke="currentColor" viewBox="0 0 24 24"><path stroke-linecap="round" stroke-linejoin="round" stroke-width="2" d="M9 12l2 2 4-4m6 2a9 9 0 11-18 0 9 9 0 0118 0z"/></svg>
                        <span class="font-medium">
                            @if($statusFilter === 'all' || empty($statusFilter)) Tất cả trạng thái
                            @elseif($statusFilter === 'paid') Đã thanh toán
                            @elseif($statusFilter === 'pending') Chờ thanh toán
                            @elseif($statusFilter === 'expired') Hết hạn
                            @endif
                        </span>
                    </div>
                    <svg class="w-4 h-4 text-gray-400 transition-transform duration-200" :class="{ 'rotate-180': open }" fill="none" stroke="currentColor" viewBox="0 0 24 24"><path stroke-linecap="round" stroke-linejoin="round" stroke-width="2" d="M19 9l-7 7-7-7"/></svg>
                </div>
                
                <div x-show="open" x-cloak
                     x-transition:enter="transition ease-out duration-100"
                     x-transition:enter-start="transform opacity-0 scale-95"
                     x-transition:enter-end="transform opacity-100 scale-100"
                     x-transition:leave="transition ease-in duration-75"
                     x-transition:leave-start="transform opacity-100 scale-100"
                     x-transition:leave-end="transform opacity-0 scale-95"
                     class="absolute z-50 w-full mt-2 bg-white rounded-xl shadow-lg ring-1 ring-black ring-opacity-5 overflow-hidden">
                    <div class="py-1 max-h-60 overflow-y-auto">
                        @foreach([
                            'all' => 'Tất cả trạng thái',
                            'paid' => 'Đã thanh toán',
                            'pending' => 'Chờ thanh toán',
                            'expired' => 'Hết hạn'
                        ] as $val => $label)
                            <div wire:click="$set('statusFilter', '{{ $val }}'); open = false" 
                                 class="cursor-pointer px-4 py-2.5 text-sm transition-colors hover:bg-blue-50 flex items-center justify-between {{ $statusFilter === $val ? 'bg-blue-50 text-blue-700 font-semibold' : 'text-gray-700' }}">
                                <span>{{ $label }}</span>
                                @if($statusFilter === $val)
                                    <svg class="w-4 h-4 text-blue-600" fill="none" stroke="currentColor" viewBox="0 0 24 24"><path stroke-linecap="round" stroke-linejoin="round" stroke-width="2" d="M5 13l4 4L19 7"/></svg>
                                @endif
                            </div>
                        @endforeach
                    </div>
                </div>
            </div>

            <div class="relative w-full sm:w-auto min-w-[200px]" x-data="{ open: false }" @click.away="open = false">
                <div @click="open = !open" 
                     class="flex items-center justify-between w-full rounded-xl border border-gray-200 bg-white px-4 py-2.5 text-sm text-gray-900 cursor-pointer hover:bg-gray-50 transition-colors shadow-sm h-[42px] ring-1 ring-inset ring-gray-300">
                    <div class="flex items-center gap-2">
                        <svg class="w-4 h-4 text-gray-400" fill="none" stroke="currentColor" viewBox="0 0 24 24"><path stroke-linecap="round" stroke-linejoin="round" stroke-width="2" d="M3 3h2l.4 2M7 13h10l4-8H5.4M7 13L5.4 5M7 13l-2.293 2.293c-.63.63-.184 1.707.707 1.707H17m0 0a2 2 0 100 4 2 2 0 000-4zm-8 2a2 2 0 11-4 0 2 2 0 014 0z"/></svg>
                        <span class="font-medium">
                            @if($orderTypeFilter === 'all' || empty($orderTypeFilter)) Tất cả loại
                            @elseif($orderTypeFilter === 'document') Mua tài liệu
                            @elseif($orderTypeFilter === 'subscription') Đăng ký VIP
                            @endif
                        </span>
                    </div>
                    <svg class="w-4 h-4 text-gray-400 transition-transform duration-200" :class="{ 'rotate-180': open }" fill="none" stroke="currentColor" viewBox="0 0 24 24"><path stroke-linecap="round" stroke-linejoin="round" stroke-width="2" d="M19 9l-7 7-7-7"/></svg>
                </div>
                
                <div x-show="open" x-cloak
                     x-transition:enter="transition ease-out duration-100"
                     x-transition:enter-start="transform opacity-0 scale-95"
                     x-transition:enter-end="transform opacity-100 scale-100"
                     x-transition:leave="transition ease-in duration-75"
                     x-transition:leave-start="transform opacity-100 scale-100"
                     x-transition:leave-end="transform opacity-0 scale-95"
                     class="absolute z-50 w-full mt-2 bg-white rounded-xl shadow-lg ring-1 ring-black ring-opacity-5 overflow-hidden">
                    <div class="py-1 max-h-60 overflow-y-auto">
                        @foreach([
                            'all' => 'Tất cả loại',
                            'document' => 'Mua tài liệu',
                            'subscription' => 'Đăng ký VIP'
                        ] as $val => $label)
                            <div wire:click="$set('orderTypeFilter', '{{ $val }}'); open = false" 
                                 class="cursor-pointer px-4 py-2.5 text-sm transition-colors hover:bg-blue-50 flex items-center justify-between {{ $orderTypeFilter === $val ? 'bg-blue-50 text-blue-700 font-semibold' : 'text-gray-700' }}">
                                <span>{{ $label }}</span>
                                @if($orderTypeFilter === $val)
                                    <svg class="w-4 h-4 text-blue-600" fill="none" stroke="currentColor" viewBox="0 0 24 24"><path stroke-linecap="round" stroke-linejoin="round" stroke-width="2" d="M5 13l4 4L19 7"/></svg>
                                @endif
                            </div>
                        @endforeach
                    </div>
                </div>
            </div>
            <div class="flex items-center gap-2 w-full sm:w-auto">
                <input type="date" wire:model.live="dateFrom" max="{{ date('Y-m-d') }}" class="flex-1 sm:flex-none h-[42px] rounded-xl border border-gray-200 px-3 text-sm text-gray-900 shadow-sm focus:border-blue-400 focus:outline-none focus:ring-0 transition-colors">
                <span class="text-gray-400">-</span>
                <input type="date" wire:model.live="dateTo" max="{{ date('Y-m-d') }}" class="flex-1 sm:flex-none h-[42px] rounded-xl border border-gray-200 px-3 text-sm text-gray-900 shadow-sm focus:border-blue-400 focus:outline-none focus:ring-0 transition-colors">
                <div class="w-8 shrink-0 flex items-center justify-center">
                    @if($dateFrom || $dateTo)
                        <button wire:click="resetDateFilter"
                                class="rounded-lg p-1.5 text-gray-400 hover:text-rose-500 hover:bg-rose-50 transition-colors"
                                title="Xóa bộ lọc ngày">
                            <svg class="w-4 h-4" fill="none" stroke="currentColor" viewBox="0 0 24 24"><path stroke-linecap="round" stroke-linejoin="round" stroke-width="2" d="M6 18L18 6M6 6l12 12"/></svg>
                        </button>
                    @endif
                </div>
            </div>
            <div class="relative w-full sm:flex-1 sm:min-w-[200px]">
                <div class="pointer-events-none absolute inset-y-0 left-0 flex items-center pl-3">
                    <svg class="h-4 w-4 text-gray-400" viewBox="0 0 20 20" fill="currentColor">
                        <path fill-rule="evenodd" d="M9 3.5a5.5 5.5 0 100 11 5.5 5.5 0 000-11zM2 9a7 7 0 1112.452 4.391l3.328 3.329a.75.75 0 11-1.06 1.06l-3.329-3.328A7 7 0 012 9z" clip-rule="evenodd" />
                    </svg>
                </div>
                <input type="search" wire:model.live.debounce.300ms="search" placeholder="Tìm user..."
                       class="block w-full rounded-xl border-0 py-2.5 pl-9 pr-4 text-gray-900 shadow-sm ring-1 ring-inset ring-gray-300 placeholder:text-gray-400 focus:ring-2 focus:ring-inset focus:ring-blue-600 sm:text-sm sm:leading-6 transition-all" />
            </div>
        </div>
    </div>

    <div class="rounded-2xl border border-gray-200 bg-white shadow-sm overflow-hidden">

        <div class="overflow-x-auto">
            <table class="w-full table-fixed text-left divide-y divide-gray-200 min-w-[1000px]">
                <thead class="bg-slate-50/50">
                    <tr>
                        <th scope="col" class="py-3.5 pl-6 pr-3 text-left text-sm font-semibold text-gray-900 w-[12%]">Mã ĐH</th>
                        <th scope="col" class="px-3 py-3.5 text-left text-sm font-semibold text-gray-900 w-[12%]">Loại</th>
                        <th scope="col" class="px-3 py-3.5 text-left text-sm font-semibold text-gray-900 w-[14%]">Người mua</th>
                        <th scope="col" class="px-3 py-3.5 text-left text-sm font-semibold text-gray-900 w-[17%]">Tài liệu</th>
                        <th scope="col" class="px-3 py-3.5 text-left text-sm font-semibold text-gray-900 w-[11%]">Người bán</th>
                        <th scope="col" class="px-3 py-3.5 text-right text-sm font-semibold text-gray-900 w-[10%] cursor-pointer hover:bg-gray-100 transition-colors" wire:click="sortBy('total_amount')">
                            Tổng tiền
                            @if($sortField === 'total_amount')
                                <span class="ml-1 text-[10px]">{{ $sortDirection === 'asc' ? '▲' : '▼' }}</span>
                            @endif
                        </th>
                        <th scope="col" class="px-3 py-3.5 text-left text-sm font-semibold text-gray-900 w-[12%]">Trạng thái</th>
                        <th scope="col" class="py-3.5 pl-3 pr-6 text-left text-sm font-semibold text-gray-900 w-[12%] cursor-pointer hover:bg-gray-100 transition-colors" wire:click="sortBy('created_at')">
                            Ngày tạo
                            @if($sortField === 'created_at')
                                <span class="ml-1 text-[10px]">{{ $sortDirection === 'asc' ? '▲' : '▼' }}</span>
                            @endif
                        </th>
                    </tr>
                </thead>
                <tbody class="divide-y divide-gray-200 bg-white">
                    @forelse($orders as $order)
                        <tr class="hover:bg-gray-50/50 transition-colors">
                            <td class="py-4 pl-6 pr-3 text-sm font-mono text-gray-900 break-all whitespace-normal">
                                #{{ $order->order_code }}
                            </td>
                            <td class="px-3 py-4 whitespace-nowrap">
                                @if($order->order_type === 'subscription')
                                    <span class="inline-flex rounded-md bg-purple-50 px-2.5 py-1 text-xs font-semibold text-purple-700 ring-1 ring-inset ring-purple-600/20">Đăng ký VIP</span>
                                @elseif($order->total_amount > 0)
                                    <span class="inline-flex rounded-md bg-blue-50 px-2.5 py-1 text-xs font-semibold text-blue-700 ring-1 ring-inset ring-blue-600/20">Mua trực tiếp</span>
                                @else
                                    <span class="inline-flex rounded-md bg-teal-50 px-2.5 py-1 text-xs font-semibold text-teal-700 ring-1 ring-inset ring-teal-600/20">Tải bằng VIP</span>
                                @endif
                            </td>
                            <td class="px-3 py-4">
                                <p class="font-semibold text-gray-900 text-sm whitespace-normal break-words">{{ $order->user->name ?? 'N/A' }}</p>
                                <p class="text-xs text-gray-500 whitespace-normal break-all mt-0.5">{{ $order->user->email ?? '' }}</p>
                            </td>
                            <td class="px-3 py-4">
                                @if($order->order_type === 'subscription')
                                    <p class="text-sm text-gray-500 italic">Đăng ký VIP</p>
                                @else
                                    @foreach($order->items as $item)
                                        <div class="mb-2 last:mb-0">
                                            <p class="text-sm font-medium text-gray-900 whitespace-normal break-words line-clamp-2" title="{{ $item->document_title_snapshot ?? 'N/A' }}">
                                                {{ $item->document_title_snapshot ?? 'N/A' }}
                                            </p>
                                            <p class="text-xs text-gray-500 mt-0.5">
                                                Contributor: {{ number_format($item->contributor_amount) }}đ
                                                <span class="text-gray-400 mx-1">•</span>
                                                Platform: {{ number_format($item->platform_amount) }}đ
                                            </p>
                                        </div>
                                    @endforeach
                                @endif
                            </td>
                            <td class="px-3 py-4">
                                @if($order->order_type === 'subscription')
                                    <p class="text-sm text-gray-500">—</p>
                                @else
                                    @foreach($order->items as $item)
                                        <div class="mb-1 last:mb-0">
                                            <p class="text-sm text-gray-900 whitespace-normal break-words">{{ $item->document->author->name ?? 'N/A' }}</p>
                                        </div>
                                    @endforeach
                                @endif
                            </td>
                            <td class="px-3 py-4 text-right whitespace-nowrap">
                                <p class="font-bold text-gray-900 text-sm">{{ number_format($order->total_amount) }}đ</p>
                            </td>
                            <td class="px-3 py-4 whitespace-nowrap">
                                @if($order->payment_status === 'paid')
                                    <span class="inline-flex rounded-md bg-emerald-50 px-2.5 py-1 text-xs font-semibold text-emerald-700 ring-1 ring-inset ring-emerald-600/20">Đã thanh toán</span>
                                @elseif($order->payment_status === 'pending')
                                    <span class="inline-flex rounded-md bg-amber-50 px-2.5 py-1 text-xs font-semibold text-amber-700 ring-1 ring-inset ring-amber-600/20">Chờ thanh toán</span>
                                @elseif($order->payment_status === 'expired')
                                    <span class="inline-flex rounded-md bg-gray-50 px-2.5 py-1 text-xs font-semibold text-gray-700 ring-1 ring-inset ring-gray-600/20">Hết hạn</span>
                                @else
                                    <span class="inline-flex rounded-md bg-gray-50 px-2.5 py-1 text-xs font-semibold text-gray-700 ring-1 ring-inset ring-gray-600/20">{{ $order->payment_status }}</span>
                                @endif
                            </td>
                            <td class="py-4 pl-3 pr-6 text-left whitespace-nowrap text-sm text-gray-500">
                                <div>{{ $order->created_at->format('d/m/Y') }}</div>
                                <div class="text-xs mt-0.5">{{ $order->created_at->format('H:i') }}</div>
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
            <div class="border-t border-gray-200 px-4 py-3">
                {{ $orders->links() }}
            </div>
        @endif
    </div>
</div>

