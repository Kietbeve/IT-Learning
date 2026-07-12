<div id="contributor-payout-request">
    <style>
        #contributor-payout-request,
        #contributor-payout-request.wire-loading,
        #contributor-payout-request * {
            transition: none !important;
            animation: none !important;
            opacity: 1 !important;
            transform: none !important;
            visibility: visible !important;
        }
    </style>
    <div class="max-w-7xl mx-auto py-6 space-y-6">
        <!-- Page Header -->
        <div>
            <h1 class="text-3xl font-bold text-gray-900">Rút tiền</h1>
            <p class="text-gray-500 mt-1">Tạo yêu cầu rút tiền và theo dõi lịch sử</p>
        </div>

        <!-- Stats Cards -->
        <div class="grid grid-cols-1 md:grid-cols-3 gap-6">
            <div class="bg-white rounded-2xl border border-gray-200 p-6">
                <div class="flex items-center justify-between mb-4">
                    <div class="bg-green-100 p-3 rounded-xl">
                        <svg class="w-6 h-6 text-green-600" fill="none" stroke="currentColor" viewBox="0 0 24 24">
                            <path stroke-linecap="round" stroke-linejoin="round" stroke-width="2" d="M12 8c-1.657 0-3 .895-3 2s1.343 2 3 2 3 .895 3 2-1.343 2-3 2m0-8c1.11 0 2.08.402 2.599 1M12 8V7m0 1v8m0 0v1m0-1c-1.11 0-2.08-.402-2.599-1M21 12a9 9 0 11-18 0 9 9 0 0118 0z"/>
                        </svg>
                    </div>
                </div>
                <p class="text-gray-500 text-sm font-medium">Tổng đã kiếm</p>
                <p class="text-2xl font-bold text-gray-900 mt-1">{{ number_format($totalEarnings) }}đ</p>
            </div>

            <div class="bg-white rounded-2xl border border-gray-200 p-6">
                <div class="flex items-center justify-between mb-4">
                    <div class="bg-blue-100 p-3 rounded-xl">
                        <svg class="w-6 h-6 text-blue-600" fill="none" stroke="currentColor" viewBox="0 0 24 24">
                            <path stroke-linecap="round" stroke-linejoin="round" stroke-width="2" d="M3 10h18M7 15h1m4 0h1m-7 4h12a3 3 0 003-3V8a3 3 0 00-3-3H6a3 3 0 00-3 3v8a3 3 0 003 3z"/>
                        </svg>
                    </div>
                </div>
                <p class="text-gray-500 text-sm font-medium">Đã rút được</p>
                <p class="text-2xl font-bold text-gray-900 mt-1">{{ number_format($totalWithdrawn) }}đ</p>
            </div>

            <div class="bg-white rounded-2xl border border-gray-200 p-6">
                <div class="flex items-center justify-between mb-4">
                    <div class="bg-yellow-100 p-3 rounded-xl">
                        <svg class="w-6 h-6 text-yellow-600" fill="none" stroke="currentColor" viewBox="0 0 24 24">
                            <path stroke-linecap="round" stroke-linejoin="round" stroke-width="2" d="M12 8v4l3 3m6-3a9 9 0 11-18 0 9 9 0 0118 0z"/>
                        </svg>
                    </div>
                </div>
                <p class="text-gray-500 text-sm font-medium">Đang chờ duyệt</p>
                <p class="text-2xl font-bold text-gray-900 mt-1">{{ number_format($pendingPayouts) }}đ</p>
            </div>
        </div>

        <div class="space-y-8">
            <!-- Request Form -->
            <div class="max-w-3xl w-full mx-auto">
                <div class="bg-white rounded-2xl border border-gray-200 p-6 sticky top-6">
                    <h2 class="text-lg font-bold text-gray-900 mb-4">Yêu cầu rút tiền</h2>

                    @if($hasPendingRequest)
                        <div class="mb-4 bg-yellow-50 border border-yellow-200 rounded-xl p-4">
                            <div class="flex items-start gap-3">
                                <svg class="w-5 h-5 text-yellow-600 mt-0.5 flex-shrink-0" fill="none" stroke="currentColor" viewBox="0 0 24 24">
                                    <path stroke-linecap="round" stroke-linejoin="round" stroke-width="2" d="M12 9v2m0 4h.01m-6.938 4h13.856c1.54 0 2.502-1.667 1.732-3L13.732 4c-.77-1.333-2.694-1.333-3.464 0L3.34 16c-.77 1.333.192 3 1.732 3z"/>
                                </svg>
<div wire:poll.10s.keep-alive>
                                    <p class="text-sm font-semibold text-yellow-800">Không thể gửi yêu cầu mới</p>
                                    <p class="text-xs text-yellow-700 mt-1">Bạn có yêu cầu rút tiền đang chờ xử lý. Vui lòng đợi Admin xử lý trước khi tạo yêu cầu mới.</p>
                                </div>
                            </div>
                        </div>
                    @endif

                    <form wire:submit.prevent="submitPayoutRequest">
                        <div class="space-y-4">
                            <!-- Balance Info -->
                            <div class="bg-blue-50 rounded-xl p-4">
                                <p class="text-xs text-blue-600 font-medium">Số dư khả dụng</p>
                                <p class="text-2xl font-bold text-blue-900 mt-1">{{ number_format($availableBalance) }}đ</p>
                                <p class="text-xs text-blue-600 mt-1">Tối thiểu: {{ number_format($minimumAmount) }}đ</p>
                            </div>

                            <!-- Amount -->
                            <div>
                                <label class="block text-sm font-medium text-gray-700 mb-2">Số tiền muốn rút *</label>
                                <input type="number" wire:model="amount" 
                                       min="{{ $minimumAmount }}" max="{{ $availableBalance }}"
                                       class="w-full rounded-lg border-gray-300 focus:border-blue-500 focus:ring-2 focus:ring-blue-200 focus:outline-none transition-all"
                                       placeholder="Nhập số tiền"
                                       @disabled($hasPendingRequest)>
                                @error('amount') <span class="text-xs text-red-600 mt-1 block">{{ $message }}</span> @enderror
                            </div>

                            <!-- Bank Name -->
                            <div>
                                <label class="block text-sm font-medium text-gray-700 mb-2">Ngân hàng *</label>
                                <select wire:model="bank_name" 
                                        class="w-full rounded-lg border-gray-300 focus:border-blue-500 focus:ring-2 focus:ring-blue-200 focus:outline-none transition-all"
                                        @disabled($hasPendingRequest)>
                                    <option value="">Chọn ngân hàng</option>
                                    <option value="Vietcombank">Vietcombank</option>
                                    <option value="VietinBank">VietinBank</option>
                                    <option value="BIDV">BIDV</option>
                                    <option value="Agribank">Agribank</option>
                                    <option value="Techcombank">Techcombank</option>
                                    <option value="MB Bank">MB Bank</option>
                                    <option value="ACB">ACB</option>
                                    <option value="VPBank">VPBank</option>
                                    <option value="TPBank">TPBank</option>
                                    <option value="Sacombank">Sacombank</option>
                                    <option value="HDBank">HDBank</option>
                                    <option value="SHB">SHB</option>
                                    <option value="VIB">VIB</option>
                                    <option value="MSB">MSB</option>
                                    <option value="OCB">OCB</option>
                                </select>
                                @error('bank_name') <span class="text-xs text-red-600 mt-1 block">{{ $message }}</span> @enderror
                            </div>

                            <!-- Account Number -->
                            <div>
                                <label class="block text-sm font-medium text-gray-700 mb-2">Số tài khoản *</label>
                                <input type="text" wire:model="bank_account_number" 
                                       class="w-full rounded-lg border-gray-300 focus:border-blue-500 focus:ring-2 focus:ring-blue-200 focus:outline-none transition-all"
                                       placeholder="Nhập số tài khoản"
                                       @disabled($hasPendingRequest)>
                                @error('bank_account_number') <span class="text-xs text-red-600 mt-1 block">{{ $message }}</span> @enderror
                            </div>

                            <!-- Account Name -->
                            <div>
                                <label class="block text-sm font-medium text-gray-700 mb-2">Tên chủ tài khoản *</label>
                                <input type="text" wire:model="bank_account_name" 
                                       class="w-full rounded-lg border-gray-300 focus:border-blue-500 focus:ring-2 focus:ring-blue-200 focus:outline-none transition-all"
                                       placeholder="Nguyễn Văn A"
                                       @disabled($hasPendingRequest)>
                                @error('bank_account_name') <span class="text-xs text-red-600 mt-1 block">{{ $message }}</span> @enderror
                            </div>

                            <!-- Note -->
                            <div>
                                <label class="block text-sm font-medium text-gray-700 mb-2">Ghi chú</label>
                                <textarea wire:model="note" rows="3"
                                          class="w-full rounded-lg border-gray-300 focus:border-blue-500 focus:ring-2 focus:ring-blue-200 focus:outline-none transition-all"
                                          placeholder="Ghi chú cho admin..."
                                          @disabled($hasPendingRequest)></textarea>
                            </div>

                            <!-- Submit Button -->
                            <button type="submit" 
                                    @disabled($hasPendingRequest || $availableBalance < $minimumAmount)
                                    class="w-full px-4 py-3 bg-blue-600 hover:bg-blue-700 disabled:bg-gray-300 disabled:cursor-not-allowed text-white rounded-xl font-semibold transition-colors">
                                Gửi yêu cầu
                            </button>
                        </div>
                    </form>
                </div>
            </div>

            <!-- Payout History -->
            <div>
                <div class="bg-white rounded-2xl border border-gray-200 overflow-hidden">
                    <div class="p-6 border-b border-gray-200">
                        <h2 class="text-xl font-bold text-gray-900">Lịch sử rút tiền</h2>
                    </div>

                    @if($payoutHistory->isEmpty())
                        <div class="p-12 text-center">
                            <svg class="w-16 h-16 text-gray-300 mx-auto mb-4" fill="none" stroke="currentColor" viewBox="0 0 24 24">
                                <path stroke-linecap="round" stroke-linejoin="round" stroke-width="2" d="M17 9V7a2 2 0 00-2-2H5a2 2 0 00-2 2v6a2 2 0 002 2h2m2 4h10a2 2 0 002-2v-6a2 2 0 00-2-2H9a2 2 0 00-2 2v6a2 2 0 002 2zm7-5a2 2 0 11-4 0 2 2 0 014 0z"/>
                            </svg>
                            <p class="text-gray-500 font-medium">Chưa có yêu cầu rút tiền nào</p>
                        </div>
                    @else
                        <div class="max-h-[600px] overflow-auto">
                            <table class="w-full">
                                <thead class="bg-gray-50 border-b border-gray-200">
                                    <tr>
                                        <th class="px-6 py-3 text-left text-xs font-semibold text-gray-600 uppercase">Mã</th>
                                        <th class="px-6 py-3 text-left text-xs font-semibold text-gray-600 uppercase">Ngày tạo</th>
                                        <th class="px-6 py-3 text-right text-xs font-semibold text-gray-600 uppercase">Số tiền</th>
                                        <th class="px-6 py-3 text-right text-xs font-semibold text-gray-600 uppercase">Số dư sau</th>
                                        <th class="px-6 py-3 text-left text-xs font-semibold text-gray-600 uppercase">Ngân hàng</th>
                                        <th class="px-6 py-3 text-left text-xs font-semibold text-gray-600 uppercase">Trạng thái</th>
                                        <th class="px-6 py-3 text-left text-xs font-semibold text-gray-600 uppercase">Thông tin thêm</th>
                                        <th class="px-6 py-3 text-center text-xs font-semibold text-gray-600 uppercase">Hành động</th>
                                    </tr>
                                </thead>
                                <tbody class="divide-y divide-gray-200">
                                    @foreach($payoutHistory as $payout)
                                        <tr class="hover:bg-gray-50">
                                            <td class="px-6 py-4 whitespace-nowrap text-sm font-mono text-gray-900">
                                                #{{ $payout->id }}
                                            </td>
                                            <td class="px-6 py-4 whitespace-nowrap text-sm text-gray-600">
                                                {{ $payout->created_at->format('d/m/Y H:i') }}
                                            </td>
                                            <td class="px-6 py-4 whitespace-nowrap text-right text-sm">
                                                <div class="font-bold text-gray-900">{{ number_format($payout->amount) }}đ</div>
                                            </td>
                                            <td class="px-6 py-4 whitespace-nowrap text-right text-sm">
                                                @php
                                                    $wt = $payout->walletTransactions->first();
                                                @endphp
                                                @if($wt)
                                                    <span class="font-medium text-gray-700">{{ number_format($wt->balance_after) }}đ</span>
                                                @else
                                                    <span class="text-gray-400">-</span>
                                                @endif
                                            </td>
                                            <td class="px-6 py-4 text-sm text-gray-600">
                                                <div class="font-medium text-gray-900"><span class="text-xs text-gray-400 font-normal">NH:</span> {{ $payout->bank_name }}</div>
                                                <div class="text-xs text-gray-600 mt-0.5"><span class="text-gray-400 font-normal">Tên:</span> {{ $payout->bank_account_name }}</div>
                                                <div class="text-xs text-gray-600 font-mono mt-0.5"><span class="text-gray-400 font-sans font-normal">STK:</span> {{ $payout->bank_account_number }}</div>
                                            </td>
                                            <td class="px-6 py-4">
                                                @if($payout->status === 'pending')
                                                    <span class="inline-flex items-center px-2.5 py-0.5 rounded-full text-xs font-medium bg-yellow-100 text-yellow-800">
                                                        Chờ duyệt
                                                    </span>
                                                @elseif($payout->status === 'approved')
                                                    <span class="inline-flex items-center px-2.5 py-0.5 rounded-full text-xs font-medium bg-blue-100 text-blue-800">
                                                        Đã duyệt
                                                    </span>
                                                @elseif($payout->status === 'completed')
                                                    <span class="inline-flex items-center px-2.5 py-0.5 rounded-full text-xs font-medium bg-green-100 text-green-800">
                                                        Hoàn thành
                                                    </span>
                                                @elseif($payout->status === 'rejected')
                                                    <span class="inline-flex items-center px-2.5 py-0.5 rounded-full text-xs font-medium bg-red-100 text-red-800">
                                                        Từ chối
                                                    </span>
                                                @elseif($payout->status === 'cancelled')
                                                    <span class="inline-flex items-center px-2.5 py-0.5 rounded-full text-xs font-medium bg-gray-100 text-gray-600 border border-gray-200">
                                                        Đã hủy
                                                    </span>
                                                @else
                                                    <span class="inline-flex items-center px-2.5 py-0.5 rounded-full text-xs font-medium bg-gray-100 text-gray-800">
                                                        {{ ucfirst($payout->status) }}
                                                    </span>
                                                @endif
                                            </td>
                                            <td class="px-6 py-4">
                                                @if($payout->status === 'rejected' && $payout->rejection_reason)
                                                    <p class="text-xs text-red-600" title="{{ $payout->rejection_reason }}">
                                                        <span class="font-medium">Lý do từ chối:</span> {{ Str::limit($payout->rejection_reason, 50) }}
                                                    </p>
                                                @endif
                                                @if($payout->note)
                                                    <p class="text-xs text-gray-600 mt-1" title="{{ $payout->note }}">
                                                        <span class="font-medium">Ghi chú của bạn:</span> {{ Str::limit($payout->note, 50) }}
                                                    </p>
                                                @endif
                                                @if($payout->processed_at)
                                                    <p class="text-xs text-gray-500 mt-1">
                                                        Xử lý: {{ $payout->processed_at->format('d/m/Y H:i') }}
                                                        @if($payout->processor)
                                                            bởi {{ $payout->processor->name }}
                                                        @endif
                                                    </p>
                                                @endif
                                                @if($payout->receipt_image && $payout->status === 'completed')
                                                    <button wire:click="showReceipt('{{ $payout->receipt_url }}')" 
                                                       class="inline-flex items-center gap-1 text-xs text-blue-600 hover:text-blue-700 mt-1 font-medium">
                                                        <svg class="w-3 h-3" fill="none" stroke="currentColor" viewBox="0 0 24 24">
                                                            <path stroke-linecap="round" stroke-linejoin="round" stroke-width="2" d="M4 16l4.586-4.586a2 2 0 012.828 0L16 16m-2-2l1.586-1.586a2 2 0 012.828 0L20 14m-6-6h.01M6 20h12a2 2 0 002-2V6a2 2 0 00-2-2H6a2 2 0 00-2 2v12a2 2 0 002 2z"/>
                                                        </svg>
                                                        Xem chứng từ
                                                    </button>
                                                @endif
                                                @if(!$payout->rejection_reason && !$payout->note && !$payout->processed_at && !($payout->receipt_image && $payout->status === 'completed'))
                                                    <span class="text-gray-400 text-xs">-</span>
                                                @endif
                                            </td>
                                            <td class="px-6 py-4 whitespace-nowrap text-center">
                                                @if($payout->status === 'pending')
                                                    <button wire:click="cancelRequest({{ $payout->id }})"
                                                            wire:confirm="Bạn có chắc muốn hủy yêu cầu này?"
                                                            class="text-xs text-red-600 hover:text-red-700 font-medium">
                                                        Hủy
                                                    </button>
                                                @else
                                                    <span class="text-xs text-gray-400">-</span>
                                                @endif
                                            </td>
                                        </tr>
                                    @endforeach
                                </tbody>
                            </table>
                        </div>

                        <!-- Pagination -->
                        <div class="px-6 py-4 border-t border-gray-200">
                            {{ $payoutHistory->links() }}
                        </div>
                    @endif
                </div>
            </div>
        </div>
    </div>

    <!-- Receipt Modal -->
    @if($showReceiptModal)
        <div class="fixed inset-0 z-50 flex items-center justify-center bg-black bg-opacity-75 p-4"
             wire:click="closeReceiptModal">
            <div class="relative max-w-4xl max-h-full" onclick="event.stopPropagation()">
                <img src="{{ $selectedReceiptUrl }}" 
                     class="max-w-full max-h-[90vh] rounded-lg shadow-2xl"
                     alt="Chứng từ rút tiền">
            </div>
        </div>
    @endif
</div>

