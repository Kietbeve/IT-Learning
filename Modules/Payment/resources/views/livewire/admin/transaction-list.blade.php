<div class="space-y-6">

    <div class="rounded-2xl border border-gray-200 bg-white">
        <div class="border-b border-gray-200 p-6">
            <div class="flex flex-col gap-4 sm:flex-row sm:items-center sm:justify-between">
                <div>
                    <h2 class="text-lg font-semibold text-gray-900">Lịch sử giao dịch</h2>
                    <p class="text-sm text-gray-500">Tất cả giao dịch trên hệ thống</p>
                </div>
                <div class="flex flex-wrap gap-3">
                    <select wire:model.live="typeFilter" class="rounded-xl border-gray-300 text-sm">
                        <option value="all">Tất cả loại</option>
                        <option value="purchase">Mua tài liệu</option>
                        <option value="subscription">VIP</option>
                        <option value="payout">Rút tiền</option>
                        <option value="refund">Hoàn tiền</option>
                        <option value="adjustment">Điều chỉnh</option>
                    </select>
                    <input type="date" wire:model.live="dateFrom"
                           class="rounded-xl border-gray-300 text-sm">
                    <input type="date" wire:model.live="dateTo"
                           class="rounded-xl border-gray-300 text-sm">
                    <input type="search" wire:model.live.debounce.300ms="search" placeholder="Tìm user..."
                           class="rounded-xl border-gray-300 text-sm w-48" />
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
                <thead class="border-b border-gray-200 bg-gray-50">
                    <tr>
                        <th class="px-6 py-3 text-left text-xs font-semibold uppercase text-gray-600">ID</th>
                        <th class="px-6 py-3 text-left text-xs font-semibold uppercase text-gray-600">User</th>
                        <th class="px-6 py-3 text-left text-xs font-semibold uppercase text-gray-600">Loại</th>
                        <th class="px-6 py-3 text-right text-xs font-semibold uppercase text-gray-600 cursor-pointer hover:bg-gray-100" wire:click="sortBy('amount')">
                            Số tiền
                            @if($sortField === 'amount')
                                <span class="text-xs">{{ $sortDirection === 'asc' ? '↑' : '↓' }}</span>
                            @endif
                        </th>
                        <th class="px-6 py-3 text-right text-xs font-semibold uppercase text-gray-600">Số dư</th>
                        <th class="px-6 py-3 text-left text-xs font-semibold uppercase text-gray-600">Ghi chú</th>
                        <th class="px-6 py-3 text-left text-xs font-semibold uppercase text-gray-600 cursor-pointer hover:bg-gray-100" wire:click="sortBy('created_at')">
                            Thời gian
                            @if($sortField === 'created_at')
                                <span class="text-xs">{{ $sortDirection === 'asc' ? '↑' : '↓' }}</span>
                            @endif
                        </th>
                    </tr>
                </thead>
                <tbody class="divide-y divide-slate-200">
                    @forelse($transactions as $tx)
                        <tr class="hover:bg-gray-50">
                            <td class="px-6 py-4 text-sm font-mono text-gray-900">#{{ $tx->id }}</td>
                            <td class="px-6 py-4">
                                <p class="font-semibold text-gray-900">{{ $tx->user->name ?? 'N/A' }}</p>
                                <p class="text-xs text-gray-500">{{ $tx->user->email ?? '' }}</p>
                            </td>
                            <td class="px-6 py-4">
                                @php
                                    $typeLabels = [
                                        'earning' => ['text' => 'Bán tài liệu', 'class' => 'bg-emerald-100 text-emerald-800'],
                                        'purchase' => ['text' => 'Mua tài liệu', 'class' => 'bg-blue-100 text-blue-800'],
                                        'subscription' => ['text' => 'VIP', 'class' => 'bg-purple-100 text-purple-800'],
                                        'payout' => ['text' => 'Rút tiền', 'class' => 'bg-rose-100 text-rose-800'],
                                        'refund' => ['text' => 'Hoàn tiền', 'class' => 'bg-amber-100 text-amber-800'],
                                        'adjustment' => ['text' => 'Điều chỉnh', 'class' => 'bg-gray-100 text-gray-800'],
                                    ];
                                    $label = $typeLabels[$tx->type] ?? ['text' => $tx->type, 'class' => 'bg-gray-100 text-gray-800'];
                                @endphp
                                <span class="inline-flex rounded-full px-2.5 py-0.5 text-xs font-semibold {{ $label['class'] }}">
                                    {{ $label['text'] }}
                                </span>
                            </td>
                            <td class="px-6 py-4 text-right">
                                @php
                                    $isNegative = in_array($tx->type, ['payout', 'refund']);
                                    $sign = $isNegative ? '-' : '+';
                                    $colorClass = $isNegative ? 'text-rose-700' : 'text-green-700';
                                @endphp
                                <span class="font-bold {{ $colorClass }}">
                                    {{ $sign }}{{ number_format(abs($tx->amount)) }}đ
                                </span>
                            </td>
                            <td class="px-6 py-4 text-right">
                                <p class="text-sm text-gray-600">{{ number_format($tx->balance_after) }}đ</p>
                            </td>
                            <td class="px-6 py-4 text-sm text-gray-500 max-w-xs truncate">
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
                            <td class="px-6 py-4 text-sm text-gray-500">
                                {{ $tx->created_at->format('d/m/Y H:i') }}
                            </td>
                        </tr>
                    @empty
                        <tr>
                            <td colspan="7" class="px-6 py-12 text-center text-gray-500">
                                Không có giao dịch nào
                            </td>
                        </tr>
                    @endforelse
                </tbody>
            </table>
        </div>

        @if($transactions->hasPages())
            <div class="border-t border-gray-200 p-4">
                {{ $transactions->links() }}
            </div>
        @endif
    </div>
</div>

