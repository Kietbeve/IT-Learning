<div class="space-y-6 py-4">

    {{-- Header --}}
    <div class="flex flex-col gap-4 sm:flex-row sm:items-center sm:justify-between">
        <div>
            <h2 class="text-lg font-bold text-gray-900">Lịch sử giao dịch</h2>
            <p class="text-sm text-gray-500 mt-1">Tất cả giao dịch trên hệ thống</p>
        </div>
        
        <button wire:click="exportExcel"
                class="inline-flex items-center justify-center gap-2 rounded-xl bg-emerald-600 px-4 py-2.5 text-sm font-semibold text-white shadow-sm hover:bg-emerald-500 focus-visible:outline focus-visible:outline-2 focus-visible:outline-offset-2 focus-visible:outline-emerald-600 transition-all shrink-0">
            <svg class="h-4 w-4" fill="none" stroke="currentColor" viewBox="0 0 24 24">
                <path stroke-linecap="round" stroke-linejoin="round" stroke-width="2" d="M12 10v6m0 0l-3-3m3 3l3-3m2 8H7a2 2 0 01-2-2V5a2 2 0 012-2h5.586a1 1 0 01.707.293l5.414 5.414a1 1 0 01.293.707V19a2 2 0 01-2 2z"/>
            </svg>
            Xuất Excel
        </button>
    </div>

    {{-- Bộ lọc --}}
    <div class="flex flex-col sm:flex-row flex-wrap items-center gap-3">
        <select wire:model.live="typeFilter" aria-label="Lọc theo loại" class="w-full sm:w-auto rounded-xl border-0 py-2.5 px-4 text-gray-900 shadow-sm ring-1 ring-inset ring-gray-300 focus:ring-2 focus:ring-inset focus:ring-blue-600 sm:text-sm sm:leading-6 transition-all">
            <option value="all">Tất cả loại</option>
            <option value="purchase">Mua tài liệu</option>
            <option value="subscription">VIP</option>
            <option value="payout">Rút tiền</option>
            <option value="refund">Hoàn tiền</option>
            <option value="adjustment">Điều chỉnh</option>
        </select>

        <div class="flex items-center gap-2 w-full sm:w-auto">
            <input type="date" wire:model.live="dateFrom"
                   class="flex-1 sm:flex-none rounded-xl border-0 py-2.5 px-3 text-gray-900 shadow-sm ring-1 ring-inset ring-gray-300 focus:ring-2 focus:ring-inset focus:ring-blue-600 sm:text-sm sm:leading-6 transition-all">
            <span class="text-gray-400">-</span>
            <input type="date" wire:model.live="dateTo"
                   class="flex-1 sm:flex-none rounded-xl border-0 py-2.5 px-3 text-gray-900 shadow-sm ring-1 ring-inset ring-gray-300 focus:ring-2 focus:ring-inset focus:ring-blue-600 sm:text-sm sm:leading-6 transition-all">
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

    <div class="overflow-hidden rounded-2xl bg-white shadow ring-1 ring-gray-200">
        <div class="overflow-x-auto">
            <table class="w-full table-fixed divide-y divide-gray-200 text-left">
                <thead class="bg-slate-50/50">
                    <tr>
                        <th scope="col" class="py-3.5 pl-6 pr-3 text-left text-sm font-semibold text-gray-900 w-[8%]">ID</th>
                        <th scope="col" class="px-3 py-3.5 text-left text-sm font-semibold text-gray-900 w-[22%]">User</th>
                        <th scope="col" class="px-3 py-3.5 text-left text-sm font-semibold text-gray-900 w-[15%]">Loại</th>
                        <th scope="col" class="px-3 py-3.5 text-right text-sm font-semibold text-gray-900 w-[15%] cursor-pointer hover:bg-gray-100 transition-colors" wire:click="sortBy('amount')">
                            Số tiền
                            @if($sortField === 'amount')
                                <span class="ml-1 text-[10px]">{{ $sortDirection === 'asc' ? '▲' : '▼' }}</span>
                            @endif
                        </th>
                        <th scope="col" class="px-3 py-3.5 text-right text-sm font-semibold text-gray-900 w-[15%]">Số dư</th>
                        <th scope="col" class="px-3 py-3.5 text-left text-sm font-semibold text-gray-900 w-[15%]">Ghi chú</th>
                        <th scope="col" class="py-3.5 pl-3 pr-6 text-left text-sm font-semibold text-gray-900 w-[10%] cursor-pointer hover:bg-gray-100 transition-colors" wire:click="sortBy('created_at')">
                            Thời gian
                            @if($sortField === 'created_at')
                                <span class="ml-1 text-[10px]">{{ $sortDirection === 'asc' ? '▲' : '▼' }}</span>
                            @endif
                        </th>
                    </tr>
                </thead>
                <tbody class="divide-y divide-gray-200 bg-white">
                    @forelse($transactions as $tx)
                        <tr class="hover:bg-gray-50/50 transition-colors">
                            <td class="whitespace-nowrap py-4 pl-6 pr-3 text-sm font-mono text-gray-900">#{{ $tx->id }}</td>
                            <td class="px-3 py-4">
                                <p class="font-semibold text-gray-900 text-sm whitespace-normal break-words">{{ $tx->user->name ?? 'N/A' }}</p>
                                <p class="text-xs text-gray-500 whitespace-normal break-all mt-0.5">{{ $tx->user->email ?? '' }}</p>
                            </td>
                            <td class="px-3 py-4 whitespace-nowrap">
                                @php
                                    $typeLabels = [
                                        'earning' => ['text' => 'Bán tài liệu', 'class' => 'bg-emerald-50 text-emerald-700 ring-emerald-600/20'],
                                        'purchase' => ['text' => 'Mua tài liệu', 'class' => 'bg-blue-50 text-blue-700 ring-blue-600/20'],
                                        'subscription' => ['text' => 'VIP', 'class' => 'bg-purple-50 text-purple-700 ring-purple-600/20'],
                                        'payout' => ['text' => 'Rút tiền', 'class' => 'bg-rose-50 text-rose-700 ring-rose-600/20'],
                                        'refund' => ['text' => 'Hoàn tiền', 'class' => 'bg-amber-50 text-amber-700 ring-amber-600/20'],
                                        'adjustment' => ['text' => 'Điều chỉnh', 'class' => 'bg-gray-50 text-gray-700 ring-gray-600/20'],
                                    ];
                                    $label = $typeLabels[$tx->type] ?? ['text' => $tx->type, 'class' => 'bg-gray-50 text-gray-700 ring-gray-600/20'];
                                @endphp
                                <span class="inline-flex rounded-md px-2.5 py-1 text-xs font-semibold ring-1 ring-inset {{ $label['class'] }}">
                                    {{ $label['text'] }}
                                </span>
                            </td>
                            <td class="px-3 py-4 text-right whitespace-nowrap">
                                @php
                                    $isNegative = in_array($tx->type, ['payout', 'refund']);
                                    $sign = $isNegative ? '-' : '+';
                                    $colorClass = $isNegative ? 'text-rose-600' : 'text-emerald-600';
                                @endphp
                                <span class="font-bold text-sm {{ $colorClass }}">
                                    {{ $sign }}{{ number_format(abs($tx->amount)) }}đ
                                </span>
                            </td>
                            <td class="px-3 py-4 text-right whitespace-nowrap text-sm text-gray-600">
                                {{ number_format($tx->balance_after) }}đ
                            </td>
                            <td class="px-3 py-4 text-sm text-gray-500 whitespace-normal break-words">
                                @if($tx->reference_type === 'order_item' && $tx->reference)
                                    <a href="#" class="text-blue-600 font-medium hover:underline">
                                        Đơn hàng #{{ $tx->reference_id }}
                                    </a>
                                @elseif($tx->reference_type === 'payout_request')
                                    <span class="font-medium text-gray-700">Rút tiền #{{ $tx->reference_id }}</span>
                                @else
                                    {{ $tx->note ?? '-' }}
                                @endif
                            </td>
                            <td class="py-4 pl-3 pr-6 text-left whitespace-nowrap text-sm text-gray-500">
                                <div>{{ $tx->created_at->format('d/m/Y') }}</div>
                                <div class="text-xs mt-0.5">{{ $tx->created_at->format('H:i') }}</div>
                            </td>
                        </tr>
                    @empty
                        <tr>
                            <td colspan="7" class="py-12 px-4 text-center">
                                <svg class="mx-auto h-12 w-12 text-gray-300" fill="none" viewBox="0 0 24 24" stroke="currentColor">
                                    <path stroke-linecap="round" stroke-linejoin="round" stroke-width="1.5" d="M7 7h.01M7 3h5c.512 0 1.024.195 1.414.586l7 7a2 2 0 010 2.828l-7 7a2 2 0 01-2.828 0l-7-7A1.994 1.994 0 013 12V7a4 4 0 014-4z"/>
                                </svg>
                                <h3 class="mt-2 text-sm font-semibold text-gray-900">Không có dữ liệu</h3>
                                <p class="mt-1 text-sm text-gray-500">Không tìm thấy giao dịch nào khớp với bộ lọc.</p>
                            </td>
                        </tr>
                    @endforelse
                </tbody>
            </table>
        </div>

        @if($transactions->hasPages())
            <div class="border-t border-gray-200 px-4 py-3">
                {{ $transactions->links() }}
            </div>
        @endif
    </div>
</div>

