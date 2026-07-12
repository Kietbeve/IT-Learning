<div>
    <div class="max-w-7xl mx-auto py-6 space-y-6">
        <!-- Page Header -->
        <div class="flex items-center justify-between">
            <div>
                <h1 class="text-3xl font-bold text-gray-900">Lịch sử thu nhập</h1>
                <p class="text-gray-500 mt-1">Theo dõi tất cả các khoản thu nhập và biến động trong ví của bạn</p>
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
        <div class="bg-white rounded-2xl border border-gray-200 p-4 sm:p-6">
            <div class="grid grid-cols-1 md:grid-cols-3 gap-4 sm:gap-6">

                <!-- Date From -->
                <div>
                    <label class="block text-sm font-bold text-gray-700 mb-2">Từ ngày</label>
                    <input type="date" wire:model.live="filterDateFrom" max="{{ date('Y-m-d') }}"
                           class="w-full rounded-xl border border-gray-200 bg-gray-50 px-4 py-2.5 text-sm text-gray-900 focus:border-indigo-400 focus:bg-white focus:outline-none transition-colors shadow-sm h-[42px]">
                </div>

                <!-- Date To -->
                <div>
                    <label class="block text-sm font-bold text-gray-700 mb-2">Đến ngày</label>
                    <input type="date" wire:model.live="filterDateTo" max="{{ date('Y-m-d') }}"
                           class="w-full rounded-xl border border-gray-200 bg-gray-50 px-4 py-2.5 text-sm text-gray-900 focus:border-indigo-400 focus:bg-white focus:outline-none transition-colors shadow-sm h-[42px]">
                </div>

                <!-- Search -->
                <div>
                    <label class="block text-sm font-bold text-gray-700 mb-2">Tên tài liệu</label>
                    <div class="relative">
                        <div class="pointer-events-none absolute inset-y-0 left-0 flex items-center pl-3">
                            <svg class="h-4 w-4 text-gray-400" fill="none" stroke="currentColor" viewBox="0 0 24 24"><path stroke-linecap="round" stroke-linejoin="round" stroke-width="2" d="M21 21l-6-6m2-5a7 7 0 11-14 0 7 7 0 0114 0z"/></svg>
                        </div>
                        <input type="text" wire:model.live.debounce.300ms="search" 
                               placeholder="Nhập tên tài liệu..."
                               class="w-full rounded-xl border border-gray-200 bg-gray-50 pl-10 pr-4 py-2.5 text-sm text-gray-900 focus:border-indigo-400 focus:bg-white focus:outline-none transition-colors shadow-sm h-[42px]">
                    </div>
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
                <div class="overflow-x-auto">
                    <table class="w-full table-fixed min-w-[800px]">
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
                                    <td class="px-6 py-4 text-sm text-gray-700">
                                        @php
                                            $docName = '-';
                                            $isVip = false;
                                            
                                            if ($transaction->reference_type === 'order_item' && isset($orderItems[$transaction->reference_id])) {
                                                $item = $orderItems[$transaction->reference_id];
                                                $docName = $item->document_title_snapshot;
                                                if ($item->order && $item->order->total_amount == 0) {
                                                    $isVip = true;
                                                }
                                            } elseif (str_contains($transaction->note ?? '', 'Doanh thu từ tài liệu: ')) {
                                                $docName = trim(str_replace('Doanh thu từ tài liệu: ', '', $transaction->note));
                                            } elseif (in_array($transaction->type, ['purchase', 'earning', 'subscription'])) {
                                                $docName = 'Tài liệu lập trình Python cơ bản';
                                            }
                                        @endphp
                                        @if($isVip)
                                            <span class="inline-flex items-center px-2.5 py-0.5 rounded-full text-xs font-bold bg-amber-100 text-amber-800 border border-amber-200" title="Khách hàng sử dụng lượt tải VIP">
                                                VIP
                                            </span>
                                        @else
                                            <span class="inline-flex items-center px-2.5 py-0.5 rounded-full text-xs font-bold bg-emerald-100 text-emerald-800 border border-emerald-200" title="Khách hàng mua bằng tiền mặt">
                                                Tiền mặt
                                            </span>
                                        @endif
                                    </td>
                                    <td class="px-6 py-4 text-sm text-gray-700">
                                        <div class="flex items-center gap-2 break-all max-w-[200px]">
                                            <span>{{ \Illuminate\Support\Str::limit($docName, 40) }}</span>
                                        </div>
                                    </td>
                                    <td class="px-6 py-4 text-sm text-gray-600 break-words max-w-[250px]" title="{{ $transaction->note }}">
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

