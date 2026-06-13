<div class="space-y-6">

    <div class="grid gap-6 sm:grid-cols-2 lg:grid-cols-4">
        <div class="rounded-2xl border border-slate-200 bg-white p-6">
            <div class="flex items-center justify-between">
                <p class="text-sm font-medium text-slate-600">Tổng giao dịch</p>
                <span class="rounded-xl bg-blue-50 p-2">
                    <svg class="h-5 w-5 text-blue-600" fill="none" stroke="currentColor" viewBox="0 0 24 24">
                        <path stroke-linecap="round" stroke-linejoin="round" stroke-width="2" d="M9 5H7a2 2 0 00-2 2v12a2 2 0 002 2h10a2 2 0 002-2V7a2 2 0 00-2-2h-2M9 5a2 2 0 002 2h2a2 2 0 002-2M9 5a2 2 0 012-2h2a2 2 0 012 2"/>
                    </svg>
                </span>
            </div>
            <p class="mt-4 text-3xl font-bold text-slate-900">{{ number_format($stats['total_count']) }}</p>
            <p class="mt-1 text-sm text-slate-500">{{ number_format($stats['purchase_count']) }} giao dịch mua</p>
        </div>

        <div class="rounded-2xl border border-slate-200 bg-white p-6">
            <div class="flex items-center justify-between">
                <p class="text-sm font-medium text-slate-600">Tổng thu</p>
                <span class="rounded-xl bg-green-50 p-2">
                    <svg class="h-5 w-5 text-green-600" fill="none" stroke="currentColor" viewBox="0 0 24 24">
                        <path stroke-linecap="round" stroke-linejoin="round" stroke-width="2" d="M12 8c-1.657 0-3 .895-3 2s1.343 2 3 2 3 .895 3 2-1.343 2-3 2m0-8c1.11 0 2.08.402 2.599 1M12 8V7m0 1v8m0 0v1m0-1c-1.11 0-2.08-.402-2.599-1M21 12a9 9 0 11-18 0 9 9 0 0118 0z"/>
                    </svg>
                </span>
            </div>
            <p class="mt-4 text-3xl font-bold text-green-700">{{ number_format($stats['total_credit']) }}đ</p>
        </div>

        <div class="rounded-2xl border border-slate-200 bg-white p-6">
            <div class="flex items-center justify-between">
                <p class="text-sm font-medium text-slate-600">Đã chi trả</p>
                <span class="rounded-xl bg-rose-50 p-2">
                    <svg class="h-5 w-5 text-rose-600" fill="none" stroke="currentColor" viewBox="0 0 24 24">
                        <path stroke-linecap="round" stroke-linejoin="round" stroke-width="2" d="M17 9V7a2 2 0 00-2-2H5a2 2 0 00-2 2v6a2 2 0 002 2h2m2 4h10a2 2 0 002-2v-6a2 2 0 00-2-2H9a2 2 0 00-2 2v6a2 2 0 002 2z"/>
                    </svg>
                </span>
            </div>
            <p class="mt-4 text-3xl font-bold text-rose-700">{{ number_format(abs($stats['total_debit'])) }}đ</p>
            <p class="mt-1 text-sm text-slate-500">Rút tiền + hoàn tiền</p>
        </div>

        <div class="rounded-2xl border border-slate-200 bg-white p-6">
            <div class="flex items-center justify-between">
                <p class="text-sm font-medium text-slate-600">Tỉ lệ mua hàng</p>
                <span class="rounded-xl bg-purple-50 p-2">
                    <svg class="h-5 w-5 text-purple-600" fill="none" stroke="currentColor" viewBox="0 0 24 24">
                        <path stroke-linecap="round" stroke-linejoin="round" stroke-width="2" d="M13 7h8m0 0v8m0-8l-8 8-4-4-6 6"/>
                    </svg>
                </span>
            </div>
            <p class="mt-4 text-3xl font-bold text-slate-900">
                {{ $stats['total_count'] > 0 ? round($stats['purchase_count'] / $stats['total_count'] * 100) : 0 }}%
            </p>
        </div>
    </div>

    <div class="rounded-2xl border border-slate-200 bg-white">
        <div class="border-b border-slate-200 p-6">
            <div class="flex flex-col gap-4 sm:flex-row sm:items-center sm:justify-between">
                <div>
                    <h2 class="text-lg font-semibold text-slate-900">Lịch sử giao dịch</h2>
                    <p class="text-sm text-slate-500">Tất cả giao dịch trên hệ thống</p>
                </div>
                <div class="flex flex-wrap gap-3">
                    <select wire:model.live="typeFilter" class="rounded-xl border-slate-300 text-sm">
                        <option value="all">Tất cả loại</option>
                        <option value="purchase">Mua tài liệu</option>
                        <option value="subscription">VIP</option>
                        <option value="payout">Rút tiền</option>
                        <option value="refund">Hoàn tiền</option>
                        <option value="adjustment">Điều chỉnh</option>
                    </select>
                    <input type="date" wire:model.live="dateFrom"
                           class="rounded-xl border-slate-300 text-sm">
                    <input type="date" wire:model.live="dateTo"
                           class="rounded-xl border-slate-300 text-sm">
                    <input type="search" wire:model.live.debounce.300ms="search" placeholder="Tìm user..."
                           class="rounded-xl border-slate-300 text-sm w-48" />
                    <button wire:click="exportExcel"
                            class="rounded-xl bg-blue-600 px-4 py-2 text-sm font-semibold text-white hover:bg-blue-700">
                        <svg class="mr-1 inline h-4 w-4" fill="none" stroke="currentColor" viewBox="0 0 24 24">
                            <path stroke-linecap="round" stroke-linejoin="round" stroke-width="2" d="M12 10v6m0 0l-3-3m3 3l3-3m2 8H7a2 2 0 01-2-2V5a2 2 0 012-2h5.586a1 1 0 01.707.293l5.414 5.414a1 1 0 01.293.707V19a2 2 0 01-2 2z"/>
                        </svg>
                        Export Excel
                    </button>
                </div>
            </div>
        </div>

        <div class="overflow-x-auto">
            <table class="w-full">
                <thead class="border-b border-slate-200 bg-slate-50">
                    <tr>
                        <th class="px-6 py-3 text-left text-xs font-semibold uppercase text-slate-600">ID</th>
                        <th class="px-6 py-3 text-left text-xs font-semibold uppercase text-slate-600">User</th>
                        <th class="px-6 py-3 text-left text-xs font-semibold uppercase text-slate-600">Loại</th>
                        <th class="px-6 py-3 text-right text-xs font-semibold uppercase text-slate-600 cursor-pointer hover:bg-slate-100" wire:click="sortBy('amount')">
                            Số tiền
                            @if($sortField === 'amount')
                                <span class="text-xs">{{ $sortDirection === 'asc' ? '↑' : '↓' }}</span>
                            @endif
                        </th>
                        <th class="px-6 py-3 text-right text-xs font-semibold uppercase text-slate-600">Số dư</th>
                        <th class="px-6 py-3 text-left text-xs font-semibold uppercase text-slate-600">Ghi chú</th>
                        <th class="px-6 py-3 text-left text-xs font-semibold uppercase text-slate-600 cursor-pointer hover:bg-slate-100" wire:click="sortBy('created_at')">
                            Thời gian
                            @if($sortField === 'created_at')
                                <span class="text-xs">{{ $sortDirection === 'asc' ? '↑' : '↓' }}</span>
                            @endif
                        </th>
                    </tr>
                </thead>
                <tbody class="divide-y divide-slate-200">
                    @forelse($transactions as $tx)
                        <tr class="hover:bg-slate-50">
                            <td class="px-6 py-4 text-sm font-mono text-slate-900">#{{ $tx->id }}</td>
                            <td class="px-6 py-4">
                                <p class="font-semibold text-slate-900">{{ $tx->user->name ?? 'N/A' }}</p>
                                <p class="text-xs text-slate-500">{{ $tx->user->email ?? '' }}</p>
                            </td>
                            <td class="px-6 py-4">
                                @php
                                    $typeLabels = [
                                        'purchase' => ['text' => 'Mua tài liệu', 'class' => 'bg-blue-100 text-blue-800'],
                                        'subscription' => ['text' => 'VIP', 'class' => 'bg-purple-100 text-purple-800'],
                                        'payout' => ['text' => 'Rút tiền', 'class' => 'bg-rose-100 text-rose-800'],
                                        'refund' => ['text' => 'Hoàn tiền', 'class' => 'bg-amber-100 text-amber-800'],
                                        'adjustment' => ['text' => 'Điều chỉnh', 'class' => 'bg-slate-100 text-slate-800'],
                                    ];
                                    $label = $typeLabels[$tx->type] ?? ['text' => $tx->type, 'class' => 'bg-slate-100 text-slate-800'];
                                @endphp
                                <span class="inline-flex rounded-full px-2.5 py-0.5 text-xs font-semibold {{ $label['class'] }}">
                                    {{ $label['text'] }}
                                </span>
                            </td>
                            <td class="px-6 py-4 text-right">
                                <span class="font-bold {{ $tx->amount > 0 ? 'text-green-700' : 'text-rose-700' }}">
                                    {{ $tx->amount > 0 ? '+' : '' }}{{ number_format($tx->amount) }}đ
                                </span>
                            </td>
                            <td class="px-6 py-4 text-right">
                                <p class="text-sm text-slate-600">{{ number_format($tx->balance_after) }}đ</p>
                            </td>
                            <td class="px-6 py-4 text-sm text-slate-500 max-w-xs truncate">
                                @if($tx->reference_type === 'order_item' && $tx->reference)
                                    <a href="#" class="text-blue-600 hover:underline">
                                        Đơn hàng #{{ $tx->reference_id }}
                                    </a>
                                @elseif($tx->reference_type === 'payout_request')
                                    <span>Rút tiền #{{ $tx->reference_id }}</span>
                                @else
                                    {{ $tx->note ?? '-' }}
                                @endif
                            </td>
                            <td class="px-6 py-4 text-sm text-slate-500">
                                {{ $tx->created_at->format('d/m/Y H:i') }}
                            </td>
                        </tr>
                    @empty
                        <tr>
                            <td colspan="7" class="px-6 py-12 text-center text-slate-500">
                                Không có giao dịch nào
                            </td>
                        </tr>
                    @endforelse
                </tbody>
            </table>
        </div>

        @if($transactions->hasPages())
            <div class="border-t border-slate-200 p-4">
                {{ $transactions->links() }}
            </div>
        @endif
    </div>
</div>
