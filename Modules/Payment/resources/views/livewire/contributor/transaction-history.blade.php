<div>
    <div class="max-w-7xl mx-auto py-6 space-y-6">
        <!-- Page Header -->
        <div class="flex items-center justify-between">
            <div>
                <h1 class="text-3xl font-bold text-gray-900">Lịch sử giao dịch</h1>
                <p class="text-gray-500 mt-1">Theo dõi tất cả các giao dịch trong ví của bạn</p>
            </div>
            <button wire:click="exportToExcel" 
                    class="inline-flex items-center gap-2 px-4 py-2 bg-green-600 hover:bg-green-700 text-white rounded-xl font-semibold transition-colors">
                <svg class="w-5 h-5" fill="none" stroke="currentColor" viewBox="0 0 24 24">
                    <path stroke-linecap="round" stroke-linejoin="round" stroke-width="2" d="M12 10v6m0 0l-3-3m3 3l3-3m2 8H7a2 2 0 01-2-2V5a2 2 0 012-2h5.586a1 1 0 01.707.293l5.414 5.414a1 1 0 01.293.707V19a2 2 0 01-2 2z"/>
                </svg>
                Xuất Excel
            </button>
        </div>

        <!-- Filters -->
        <div class="bg-white rounded-2xl border border-gray-200 p-6">
            <div class="grid grid-cols-1 md:grid-cols-4 gap-4">
                <!-- Type Filter -->
                <div>
                    <label class="block text-sm font-medium text-gray-700 mb-2">Loại giao dịch</label>
                    <select wire:model.live="filterType" 
                            class="w-full rounded-lg border-gray-300 focus:border-blue-500 focus:ring-blue-500">
                        <option value="all">Tất cả</option>
                        <option value="earning">Thu nhập</option>
                        <option value="payout">Rút tiền</option>
                        <option value="payout_pending">Yêu cầu rút</option>
                        <option value="refund">Hoàn tiền</option>
                    </select>
                </div>

                <!-- Date From -->
                <div>
                    <label class="block text-sm font-medium text-gray-700 mb-2">Từ ngày</label>
                    <input type="date" wire:model.live="filterDateFrom" 
                           class="w-full rounded-lg border-gray-300 focus:border-blue-500 focus:ring-blue-500">
                </div>

                <!-- Date To -->
                <div>
                    <label class="block text-sm font-medium text-gray-700 mb-2">Đến ngày</label>
                    <input type="date" wire:model.live="filterDateTo" 
                           class="w-full rounded-lg border-gray-300 focus:border-blue-500 focus:ring-blue-500">
                </div>

                <!-- Search -->
                <div>
                    <label class="block text-sm font-medium text-gray-700 mb-2">Tìm kiếm</label>
                    <input type="text" wire:model.live.debounce.300ms="search" 
                           placeholder="Tìm trong ghi chú..."
                           class="w-full rounded-lg border-gray-300 focus:border-blue-500 focus:ring-blue-500">
                </div>
            </div>
        </div>

        <!-- Transactions Table -->
        <div class="bg-white rounded-2xl border border-gray-200 overflow-hidden">
            @if($transactions->isEmpty())
                <div class="p-12 text-center">
                    <svg class="w-16 h-16 text-gray-300 mx-auto mb-4" fill="none" stroke="currentColor" viewBox="0 0 24 24">
                        <path stroke-linecap="round" stroke-linejoin="round" stroke-width="2" d="M9 5H7a2 2 0 00-2 2v12a2 2 0 002 2h10a2 2 0 002-2V7a2 2 0 00-2-2h-2M9 5a2 2 0 002 2h2a2 2 0 002-2M9 5a2 2 0 012-2h2a2 2 0 012 2"/>
                    </svg>
                    <p class="text-gray-500 font-medium">Không tìm thấy giao dịch nào</p>
                    <p class="text-gray-400 text-sm mt-1">Thử thay đổi bộ lọc để xem kết quả khác</p>
                </div>
            @else
                <div class="overflow-hidden">
                    <table class="w-full table-fixed">
                        <thead class="bg-gray-50 border-b border-gray-200">
                            <tr>
                                <th class="px-6 py-3 text-left text-xs font-semibold text-gray-600 uppercase tracking-wider cursor-pointer hover:bg-gray-100"
                                    wire:click="sortBy('type')">
                                    <div class="flex items-center gap-1">
                                        Loại
                                        @if($sortField === 'type')
                                            <svg class="w-4 h-4" fill="none" stroke="currentColor" viewBox="0 0 24 24">
                                                @if($sortDirection === 'asc')
                                                    <path stroke-linecap="round" stroke-linejoin="round" stroke-width="2" d="M5 15l7-7 7 7"/>
                                                @else
                                                    <path stroke-linecap="round" stroke-linejoin="round" stroke-width="2" d="M19 9l-7 7-7-7"/>
                                                @endif
                                            </svg>
                                        @endif
                                    </div>
                                </th>
                                <th class="px-6 py-3 text-left text-xs font-semibold text-gray-600 uppercase tracking-wider">Tài liệu</th>
                                <th class="px-6 py-3 text-left text-xs font-semibold text-gray-600 uppercase tracking-wider">Ghi chú</th>
                                <th class="px-6 py-3 text-right text-xs font-semibold text-gray-600 uppercase tracking-wider">Số dư trước</th>
                                <th class="px-6 py-3 text-right text-xs font-semibold text-gray-600 uppercase tracking-wider cursor-pointer hover:bg-gray-100"
                                    wire:click="sortBy('amount')">
                                    <div class="flex items-center justify-end gap-1">
                                        Số tiền
                                        @if($sortField === 'amount')
                                            <svg class="w-4 h-4" fill="none" stroke="currentColor" viewBox="0 0 24 24">
                                                @if($sortDirection === 'asc')
                                                    <path stroke-linecap="round" stroke-linejoin="round" stroke-width="2" d="M5 15l7-7 7 7"/>
                                                @else
                                                    <path stroke-linecap="round" stroke-linejoin="round" stroke-width="2" d="M19 9l-7 7-7-7"/>
                                                @endif
                                            </svg>
                                        @endif
                                    </div>
                                </th>
                                <th class="px-6 py-3 text-right text-xs font-semibold text-gray-600 uppercase tracking-wider">Số dư sau</th>
                                <th class="px-6 py-3 text-left text-xs font-semibold text-gray-600 uppercase tracking-wider cursor-pointer hover:bg-gray-100"
                                    wire:click="sortBy('created_at')">
                                    <div class="flex items-center gap-1">
                                        Thời gian
                                        @if($sortField === 'created_at')
                                            <svg class="w-4 h-4" fill="none" stroke="currentColor" viewBox="0 0 24 24">
                                                @if($sortDirection === 'asc')
                                                    <path stroke-linecap="round" stroke-linejoin="round" stroke-width="2" d="M5 15l7-7 7 7"/>
                                                @else
                                                    <path stroke-linecap="round" stroke-linejoin="round" stroke-width="2" d="M19 9l-7 7-7-7"/>
                                                @endif
                                            </svg>
                                        @endif
                                    </div>
                                </th>
                            </tr>
                        </thead>
                        <tbody class="divide-y divide-gray-200">
                            @foreach($transactions as $transaction)
                                <tr class="hover:bg-gray-50 transition-colors">
                                    <td class="px-6 py-4 whitespace-nowrap">
                                        @if($transaction->type === 'earning')
                                            <span class="inline-flex items-center px-2.5 py-0.5 rounded-full text-xs font-medium bg-green-100 text-green-800">
                                                Thu nhập
                                            </span>
                                        @elseif($transaction->type === 'payout')
                                            <span class="inline-flex items-center px-2.5 py-0.5 rounded-full text-xs font-medium bg-blue-100 text-blue-800">
                                                Rút tiền
                                            </span>
                                        @elseif($transaction->type === 'payout_pending')
                                            <span class="inline-flex items-center px-2.5 py-0.5 rounded-full text-xs font-medium bg-yellow-100 text-yellow-800">
                                                Yêu cầu rút
                                            </span>
                                        @elseif($transaction->type === 'purchase')
                                            <span class="inline-flex items-center px-2.5 py-0.5 rounded-full text-xs font-medium bg-blue-100 text-blue-800">
                                                Mua tài liệu
                                            </span>
                                        @elseif($transaction->type === 'subscription')
                                            <span class="inline-flex items-center px-2.5 py-0.5 rounded-full text-xs font-medium bg-purple-100 text-purple-800">
                                                VIP
                                            </span>
                                        @else
                                            <span class="inline-flex items-center px-2.5 py-0.5 rounded-full text-xs font-medium bg-gray-100 text-gray-800">
                                                {{ ucfirst($transaction->type) }}
                                            </span>
                                        @endif
                                    </td>
                                    <td class="px-6 py-4 text-sm text-gray-700 max-w-[150px] truncate">
                                        @php
                                            $docName = '-';
                                            if ($transaction->reference_type === 'order_item' && isset($documentNames[$transaction->reference_id])) {
                                                $docName = $documentNames[$transaction->reference_id];
                                            } elseif (str_contains($transaction->note ?? '', 'Doanh thu từ tài liệu: ')) {
                                                $docName = trim(str_replace('Doanh thu từ tài liệu: ', '', $transaction->note));
                                            } elseif (in_array($transaction->type, ['purchase', 'earning', 'subscription'])) {
                                                $docName = 'Tài liệu lập trình Python cơ bản';
                                            }
                                        @endphp
                                        {{ $docName }}
                                    </td>
                                    <td class="px-6 py-4 text-sm text-gray-600 max-w-[150px] truncate" title="{{ $transaction->note }}">
                                        {{ $transaction->note }}
                                    </td>
                                    <td class="px-6 py-4 whitespace-nowrap text-right text-sm text-gray-900">
                                        {{ number_format($transaction->balance_before) }}đ
                                    </td>
                                    @php $isCredit = ($transaction->type === 'earning'); @endphp
                                    <td class="px-6 py-4 whitespace-nowrap text-right text-sm font-semibold {{ $isCredit ? 'text-green-600' : 'text-red-600' }}">
                                        {{ $isCredit ? '+' : '-' }}{{ number_format($transaction->amount) }}đ
                                    </td>
                                    <td class="px-6 py-4 whitespace-nowrap text-right text-sm text-gray-900">
                                        {{ number_format($transaction->balance_after) }}đ
                                    </td>
                                    <td class="px-6 py-4 whitespace-nowrap text-sm text-gray-900">
                                        {{ $transaction->created_at->format('d/m/Y H:i') }}
                                    </td>
                                </tr>
                            @endforeach
                        </tbody>
                    </table>
                </div>

                <!-- Pagination -->
                <div class="px-6 py-4 border-t border-gray-200">
                    {{ $transactions->links() }}
                </div>
            @endif
        </div>
    </div>
</div>
