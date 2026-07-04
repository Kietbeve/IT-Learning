<div class="space-y-6" wire:poll.10s>

    @if(session('success'))
        <div class="rounded-2xl border border-emerald-200 bg-emerald-50 px-4 py-3 text-emerald-800">
            {{ session('success') }}
        </div>
    @endif

    <div class="grid gap-6 sm:grid-cols-2 lg:grid-cols-3">
        <div class="rounded-2xl border border-slate-200 bg-white p-6">
            <div class="flex items-center justify-between">
                <p class="text-sm font-medium text-slate-600">Chờ duyệt</p>
                <span class="rounded-xl bg-amber-50 p-2">
                    <svg class="h-5 w-5 text-amber-600" fill="none" stroke="currentColor" viewBox="0 0 24 24">
                        <path stroke-linecap="round" stroke-linejoin="round" stroke-width="2" d="M12 8v4l3 3m6-3a9 9 0 11-18 0 9 9 0 0118 0z"/>
                    </svg>
                </span>
            </div>
            <p class="mt-4 text-3xl font-bold text-slate-900">{{ $stats['pending_count'] }}</p>
            <p class="mt-1 text-sm text-slate-500">{{ number_format($stats['pending_amount']) }}đ</p>
        </div>

        <div class="rounded-2xl border border-slate-200 bg-white p-6">
            <div class="flex items-center justify-between">
                <p class="text-sm font-medium text-slate-600">Duyệt hôm nay</p>
                <span class="rounded-xl bg-green-50 p-2">
                    <svg class="h-5 w-5 text-green-600" fill="none" stroke="currentColor" viewBox="0 0 24 24">
                        <path stroke-linecap="round" stroke-linejoin="round" stroke-width="2" d="M9 12l2 2 4-4m6 2a9 9 0 11-18 0 9 9 0 0118 0z"/>
                    </svg>
                </span>
            </div>
            <p class="mt-4 text-3xl font-bold text-slate-900">{{ $stats['completed_today'] }}</p>
            <p class="mt-1 text-sm text-slate-500">{{ number_format($stats['completed_today_amount']) }}đ</p>
        </div>

        <div class="rounded-2xl border border-slate-200 bg-white p-6">
            <div class="flex items-center justify-between">
                <p class="text-sm font-medium text-slate-600">Tổng đã chi trả</p>
                <span class="rounded-xl bg-blue-50 p-2">
                    <svg class="h-5 w-5 text-blue-600" fill="none" stroke="currentColor" viewBox="0 0 24 24">
                        <path stroke-linecap="round" stroke-linejoin="round" stroke-width="2" d="M12 8c-1.657 0-3 .895-3 2s1.343 2 3 2 3 .895 3 2-1.343 2-3 2m0-8c1.11 0 2.08.402 2.599 1M12 8V7m0 1v8m0 0v1m0-1c-1.11 0-2.08-.402-2.599-1M21 12a9 9 0 11-18 0 9 9 0 0118 0z"/>
                    </svg>
                </span>
            </div>
            <p class="mt-4 text-3xl font-bold text-slate-900">{{ $stats['total_paid_count'] }}</p>
            <p class="mt-1 text-sm text-slate-500">{{ number_format($stats['total_paid']) }}đ</p>
        </div>
    </div>

    <div class="rounded-2xl border border-slate-200 bg-white">
        <div class="border-b border-slate-200 p-6">
            <div class="flex flex-col gap-4 sm:flex-row sm:items-center sm:justify-between">
                <div>
                    <h2 class="text-lg font-semibold text-slate-900">Yêu cầu rút tiền</h2>
                    <p class="text-sm text-slate-500">Duyệt hoặc từ chối yêu cầu của contributor</p>
                </div>
                <div class="flex gap-3">
                    <select wire:model.live="statusFilter" class="rounded-xl border-slate-300 text-sm">
                        <option value="all">Tất cả</option>
                        <option value="pending">Chờ duyệt</option>
                        <option value="completed">Đã duyệt</option>
                        <option value="rejected">Đã từ chối</option>
                    </select>
                    <input type="search" wire:model.live.debounce.300ms="search" placeholder="Tìm contributor..."
                           class="rounded-xl border-slate-300 text-sm" />
                </div>
            </div>
        </div>

        <div class="overflow-x-auto">
            <table class="w-full">
                <thead class="border-b border-slate-200 bg-slate-50">
                    <tr>
                        <th class="px-6 py-3 text-left text-xs font-semibold uppercase text-slate-600">ID</th>
                        <th class="px-6 py-3 text-left text-xs font-semibold uppercase text-slate-600">Contributor</th>
                        <th class="px-6 py-3 text-right text-xs font-semibold uppercase text-slate-600 cursor-pointer hover:bg-slate-100" wire:click="sortBy('amount')">
                            Số tiền
                            @if($sortField === 'amount')
                                <span class="text-xs">{{ $sortDirection === 'asc' ? '↑' : '↓' }}</span>
                            @endif
                        </th>
                        <th class="px-6 py-3 text-left text-xs font-semibold uppercase text-slate-600">Ngân hàng</th>
                        <th class="px-6 py-3 text-left text-xs font-semibold uppercase text-slate-600">Trạng thái</th>
                        <th class="px-6 py-3 text-left text-xs font-semibold uppercase text-slate-600 cursor-pointer hover:bg-slate-100" wire:click="sortBy('created_at')">
                            Ngày tạo
                            @if($sortField === 'created_at')
                                <span class="text-xs">{{ $sortDirection === 'asc' ? '↑' : '↓' }}</span>
                            @endif
                        </th>
                        <th class="px-6 py-3 text-center text-xs font-semibold uppercase text-slate-600">Thao tác</th>
                    </tr>
                </thead>
                <tbody class="divide-y divide-slate-200">
                    @forelse($payouts as $payout)
                        <tr class="hover:bg-slate-50">
                            <td class="px-6 py-4 text-sm font-mono text-slate-900">#{{ $payout->id }}</td>
                            <td class="px-6 py-4">
                                <p class="font-semibold text-slate-900">{{ $payout->user->name }}</p>
                                <p class="text-xs text-slate-500">{{ $payout->user->email }}</p>
                                <p class="text-xs text-slate-400">Số dư: {{ number_format($payout->user->contributor_balance) }}đ</p>
                            </td>
                            <td class="px-6 py-4 text-right">
                                <p class="font-bold text-slate-900">{{ number_format($payout->amount) }}đ</p>
                            </td>
                            <td class="px-6 py-4">
                                <p class="text-sm font-medium text-slate-900">{{ $payout->bank_name }}</p>
                                <p class="text-xs text-slate-500">{{ $payout->bank_account_number }}</p>
                                <p class="text-xs text-slate-500">{{ $payout->bank_account_name }}</p>
                            </td>
                            <td class="px-6 py-4">
                                @if($payout->status === 'pending')
                                    <span class="inline-flex rounded-full bg-amber-100 px-2.5 py-0.5 text-xs font-semibold text-amber-800">Chờ duyệt</span>
                                @elseif($payout->status === 'completed')
                                    <span class="inline-flex rounded-full bg-green-100 px-2.5 py-0.5 text-xs font-semibold text-green-800">Đã duyệt</span>
                                @elseif($payout->status === 'rejected')
                                    <span class="inline-flex rounded-full bg-rose-100 px-2.5 py-0.5 text-xs font-semibold text-rose-800">Từ chối</span>
                                @endif
                                @if($payout->status === 'rejected' && $payout->rejection_reason)
                                    <p class="mt-1 text-xs text-rose-600">Lý do: {{ Str::limit($payout->rejection_reason, 50) }}</p>
                                @endif
                                @if($payout->note)
                                    <p class="mt-1 text-xs text-slate-500">Contributor: {{ Str::limit($payout->note, 50) }}</p>
                                @endif
                                @if($payout->processed_at)
                                    <p class="mt-1 text-xs text-slate-400">{{ $payout->processed_at->format('d/m/Y H:i') }}</p>
                                @endif
                            </td>
                            <td class="px-6 py-4 text-sm text-slate-500">
                                {{ $payout->created_at->format('d/m/Y H:i') }}
                            </td>
                            <td class="px-6 py-4 text-center">
                                @if($payout->status === 'pending')
                                    <div class="flex justify-center gap-2">
                                        <button wire:click="openDetailModal({{ $payout->id }})"
                                                class="rounded-xl bg-blue-600 px-3 py-1.5 text-xs font-semibold text-white hover:bg-blue-700">
                                            Xem chi tiết
                                        </button>
                                        <button wire:click="openApproveModal({{ $payout->id }})"
                                                class="rounded-xl bg-green-600 px-3 py-1.5 text-xs font-semibold text-white hover:bg-green-700">
                                            Duyệt
                                        </button>
                                        <button wire:click="openRejectModal({{ $payout->id }})"
                                                class="rounded-xl bg-rose-600 px-3 py-1.5 text-xs font-semibold text-white hover:bg-rose-700">
                                            Từ chối
                                        </button>
                                    </div>
                                @elseif($payout->receipt_image)
                                    <a href="{{ $payout->receipt_url }}" target="_blank"
                                       class="text-xs text-blue-600 hover:underline">
                                        Xem chứng từ
                                    </a>
                                @else
                                    <span class="text-xs text-slate-400">-</span>
                                @endif
                            </td>
                        </tr>
                    @empty
                        <tr>
                            <td colspan="7" class="px-6 py-12 text-center text-slate-500">
                                Không có yêu cầu rút tiền nào
                            </td>
                        </tr>
                    @endforelse
                </tbody>
            </table>
        </div>

        @if($payouts->hasPages())
            <div class="border-t border-slate-200 p-4">
                {{ $payouts->links() }}
            </div>
        @endif
    </div>

    @if($showApproveModal)
        <div class="fixed inset-0 z-50 flex items-center justify-center bg-black/50">
            <div class="w-full max-w-md rounded-2xl bg-white p-6 shadow-xl">
                <h3 class="text-lg font-semibold text-slate-900">Duyệt yêu cầu rút tiền</h3>
                <p class="mt-2 text-sm text-slate-600">Xác nhận duyệt và chuyển khoản cho contributor</p>

                <div class="mt-4">
                    <label class="block text-sm font-medium text-slate-700 mb-2">Chứng từ chuyển khoản (tùy chọn)</label>
                    <div class="flex items-center gap-3">
                        <label for="receiptUpload" class="cursor-pointer inline-flex items-center gap-2 px-4 py-2 bg-slate-100 hover:bg-slate-200 border border-slate-300 rounded-xl text-sm font-medium text-slate-700 transition-colors">
                            <svg class="w-4 h-4" fill="none" stroke="currentColor" viewBox="0 0 24 24">
                                <path stroke-linecap="round" stroke-linejoin="round" stroke-width="2" d="M4 16v1a3 3 0 003 3h10a3 3 0 003-3v-1m-4-8l-4-4m0 0L8 8m4-4v12"/>
                            </svg>
                            Chọn tệp
                        </label>
                        <input type="file" id="receiptUpload" wire:model="receiptImage" accept="image/*" class="hidden">
                        <span class="text-sm text-slate-500">
                            <span wire:loading.remove wire:target="receiptImage">
                                @if(!$receiptImage)
                                    Chưa chọn tệp
                                @endif
                            </span>
                            <span wire:loading wire:target="receiptImage" class="text-blue-600">
                                Đang tải lên...
                            </span>
                        </span>
                    </div>
                    
                    @if($receiptImage)
                        <div class="mt-3" x-data="{ showPreview: false }">
                            <p class="text-xs text-slate-600 mb-2">Xem trước ảnh đã chọn:</p>
                            <div class="relative inline-block">
                                <img src="{{ $receiptImage->temporaryUrl() }}" 
                                     @click="showPreview = true"
                                     class="w-32 h-32 object-cover rounded-lg border-2 border-slate-200 cursor-pointer hover:border-blue-500 transition-colors"
                                     alt="Preview">
                                <div class="absolute top-1 right-1">
                                    <button type="button" wire:click="$set('receiptImage', null)" 
                                            class="bg-red-500 text-white rounded-full p-1 hover:bg-red-600 shadow-lg">
                                        <svg class="w-4 h-4" fill="none" stroke="currentColor" viewBox="0 0 24 24">
                                            <path stroke-linecap="round" stroke-linejoin="round" stroke-width="2" d="M6 18L18 6M6 6l12 12"/>
                                        </svg>
                                    </button>
                                </div>
                            </div>
                            
                            <!-- Lightbox -->
                            <div x-show="showPreview" 
                                 x-cloak
                                 @click="showPreview = false"
                                 class="fixed inset-0 z-[60] flex items-center justify-center bg-black/90 p-4">
                                <div class="relative max-w-4xl max-h-full">
                                    <img src="{{ $receiptImage->temporaryUrl() }}" 
                                         class="max-w-full max-h-[90vh] rounded-lg"
                                         @click.stop
                                         alt="Full preview">
                                    <button @click="showPreview = false" 
                                            class="absolute top-2 right-2 bg-white text-slate-900 rounded-full p-2 hover:bg-slate-100">
                                        <svg class="w-6 h-6" fill="none" stroke="currentColor" viewBox="0 0 24 24">
                                            <path stroke-linecap="round" stroke-linejoin="round" stroke-width="2" d="M6 18L18 6M6 6l12 12"/>
                                        </svg>
                                    </button>
                                </div>
                            </div>
                        </div>
                    @endif
                    @error('receiptImage') <span class="text-xs text-rose-600 mt-1 block">{{ $message }}</span> @enderror
                </div>

                <div class="mt-6 flex gap-3">
                    <button wire:click="$set('showApproveModal', false)"
                            class="flex-1 rounded-xl border border-slate-300 px-4 py-2 text-sm font-semibold text-slate-700 hover:bg-slate-50">
                        Hủy
                    </button>
                    <button wire:click="approvePayout"
                            class="flex-1 rounded-xl bg-green-600 px-4 py-2 text-sm font-semibold text-white hover:bg-green-700">
                        Xác nhận duyệt
                    </button>
                </div>
            </div>
        </div>
    @endif

    @if($showRejectModal)
        <div class="fixed inset-0 z-50 flex items-center justify-center bg-black/50">
            <div class="w-full max-w-md rounded-2xl bg-white p-6 shadow-xl">
                <h3 class="text-lg font-semibold text-slate-900">Từ chối yêu cầu</h3>
                <p class="mt-2 text-sm text-slate-600">Nhập lý do từ chối yêu cầu rút tiền</p>

                <div class="mt-4">
                    <textarea wire:model="rejectionReason" rows="4" placeholder="Nhập lý do từ chối..."
                              class="w-full rounded-xl border-slate-300 text-sm"></textarea>
                    @error('rejectionReason') <span class="text-xs text-rose-600">{{ $message }}</span> @enderror
                </div>

                <div class="mt-6 flex gap-3">
                    <button wire:click="$set('showRejectModal', false)"
                            class="flex-1 rounded-xl border border-slate-300 px-4 py-2 text-sm font-semibold text-slate-700 hover:bg-slate-50">
                        Hủy
                    </button>
                    <button wire:click="rejectPayout"
                            class="flex-1 rounded-xl bg-rose-600 px-4 py-2 text-sm font-semibold text-white hover:bg-rose-700">
                        Xác nhận từ chối
                    </button>
                </div>
            </div>
        </div>
    @endif

    @if($showDetailModal && $detailPayout)
        <div class="fixed inset-0 z-50 flex items-center justify-center bg-black/50">
            <div class="w-full max-w-2xl rounded-2xl bg-white p-6 shadow-xl max-h-[90vh] overflow-y-auto custom-scrollbar">
                <style>
                    .custom-scrollbar::-webkit-scrollbar {
                        width: 8px;
                    }
                    .custom-scrollbar::-webkit-scrollbar-track {
                        background: #f1f5f9;
                        border-radius: 10px;
                    }
                    .custom-scrollbar::-webkit-scrollbar-thumb {
                        background: #cbd5e1;
                        border-radius: 10px;
                    }
                    .custom-scrollbar::-webkit-scrollbar-thumb:hover {
                        background: #94a3b8;
                    }
                </style>
                <div class="flex items-center justify-between mb-4">
                    <h3 class="text-lg font-semibold text-slate-900">Chi tiết yêu cầu rút tiền #{{ $detailPayout->id }}</h3>
                    <button wire:click="closeDetailModal" class="text-slate-400 hover:text-slate-600">
                        <svg class="w-6 h-6" fill="none" stroke="currentColor" viewBox="0 0 24 24">
                            <path stroke-linecap="round" stroke-linejoin="round" stroke-width="2" d="M6 18L18 6M6 6l12 12"/>
                        </svg>
                    </button>
                </div>

<div class="space-y-6">
                    <!-- Thông tin Contributor -->
                    <div class="bg-blue-50 rounded-xl p-4">
                        <h4 class="text-sm font-semibold text-blue-900 mb-3">Thông tin Contributor</h4>
                        <div class="grid grid-cols-2 gap-3 text-sm">
                            <div>
                                <span class="text-blue-700 font-medium">Tên:</span>
                                <span class="text-blue-900">{{ $detailPayout->user->name }}</span>
                            </div>
                            <div>
                                <span class="text-blue-700 font-medium">Email:</span>
                                <span class="text-blue-900">{{ $detailPayout->user->email }}</span>
                            </div>
                            <div class="col-span-2">
                                <span class="text-blue-700 font-medium">Số dư hiện tại:</span>
                                <span class="text-blue-900 font-bold text-lg">{{ number_format($detailPayout->user->contributor_balance) }}đ</span>
                            </div>
                        </div>
                    </div>

                    <!-- Thông tin yêu cầu -->
                    <div class="bg-slate-50 rounded-xl p-4">
                        <h4 class="text-sm font-semibold text-slate-900 mb-3">Thông tin yêu cầu rút tiền</h4>
                        <div class="space-y-2 text-sm">
                            <div class="flex justify-between py-2 border-b border-slate-200">
                                <span class="text-slate-600">Số tiền rút:</span>
                                <span class="font-bold text-slate-900 text-lg">{{ number_format($detailPayout->amount) }}đ</span>
                            </div>
                            <div class="flex justify-between py-2 border-b border-slate-200">
                                <span class="text-slate-600">Ngân hàng:</span>
                                <span class="font-medium text-slate-900">{{ $detailPayout->bank_name }}</span>
                            </div>
                            <div class="flex justify-between py-2 border-b border-slate-200">
                                <span class="text-slate-600">Số tài khoản:</span>
                                <span class="font-mono text-slate-900">{{ $detailPayout->bank_account_number }}</span>
                            </div>
                            <div class="flex justify-between py-2 border-b border-slate-200">
                                <span class="text-slate-600">Tên chủ TK:</span>
                                <span class="font-medium text-slate-900">{{ $detailPayout->bank_account_name }}</span>
                            </div>
                            @if($detailPayout->note)
                                <div class="py-2">
                                    <span class="text-slate-600 block mb-1">Ghi chú của contributor:</span>
                                    <p class="text-slate-900 bg-white p-2 rounded border border-slate-200">{{ $detailPayout->note }}</p>
                                </div>
                            @endif
                            <div class="flex justify-between py-2">
                                <span class="text-slate-600">Ngày tạo:</span>
                                <span class="text-slate-900">{{ $detailPayout->created_at->format('d/m/Y H:i') }}</span>
                            </div>
                        </div>
                    </div>
                </div>

                <!-- Action Buttons -->
                <div class="mt-6 flex gap-3">
                    <button wire:click="closeDetailModal"
                            class="flex-1 rounded-xl border border-slate-300 px-4 py-2 text-sm font-semibold text-slate-700 hover:bg-slate-50">
                        Đóng
                    </button>
                    <button wire:click="closeDetailModal; openApproveModal({{ $detailPayout->id }})"
                            class="flex-1 rounded-xl bg-green-600 px-4 py-2 text-sm font-semibold text-white hover:bg-green-700">
                        Duyệt ngay
                    </button>
                    <button wire:click="closeDetailModal; openRejectModal({{ $detailPayout->id }})"
                            class="flex-1 rounded-xl bg-rose-600 px-4 py-2 text-sm font-semibold text-white hover:bg-rose-700">
                        Từ chối
                    </button>
                </div>
            </div>
        </div>
    @endif
</div>
