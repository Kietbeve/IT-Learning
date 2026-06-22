<div class="space-y-6" x-data="{ showApproveModal: false, showRejectModal: false }"
     @open-modal.window="$event.detail === 'approve-modal' ? showApproveModal = true : showRejectModal = true"
     @close-modal.window="$event.detail === 'approve-modal' ? showApproveModal = false : showRejectModal = false">

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
                                @if($payout->note)
                                    <p class="mt-1 text-xs text-slate-500">{{ Str::limit($payout->note, 50) }}</p>
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
                                    <a href="{{ asset('storage/' . $payout->receipt_image) }}" target="_blank"
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

    <div x-show="showApproveModal" x-cloak class="fixed inset-0 z-50 flex items-center justify-center bg-black/50">
        <div @click.away="showApproveModal = false" class="w-full max-w-md rounded-2xl bg-white p-6 shadow-xl">
            <h3 class="text-lg font-semibold text-slate-900">Duyệt yêu cầu rút tiền</h3>
            <p class="mt-2 text-sm text-slate-600">Xác nhận duyệt và chuyển khoản cho contributor</p>

            <div class="mt-4">
                <label class="block text-sm font-medium text-slate-700">Chứng từ chuyển khoản (tùy chọn)</label>
                <input type="file" wire:model="receiptImage" accept="image/*"
                       class="mt-1 w-full rounded-xl border-slate-300 text-sm">
                @error('receiptImage') <span class="text-xs text-rose-600">{{ $message }}</span> @enderror
            </div>

            <div class="mt-6 flex gap-3">
                <button @click="showApproveModal = false"
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

    <div x-show="showRejectModal" x-cloak class="fixed inset-0 z-50 flex items-center justify-center bg-black/50">
        <div @click.away="showRejectModal = false" class="w-full max-w-md rounded-2xl bg-white p-6 shadow-xl">
            <h3 class="text-lg font-semibold text-slate-900">Từ chối yêu cầu</h3>
            <p class="mt-2 text-sm text-slate-600">Nhập lý do từ chối yêu cầu rút tiền</p>

            <div class="mt-4">
                <textarea wire:model="rejectionReason" rows="4" placeholder="Nhập lý do từ chối..."
                          class="w-full rounded-xl border-slate-300 text-sm"></textarea>
                @error('rejectionReason') <span class="text-xs text-rose-600">{{ $message }}</span> @enderror
            </div>

            <div class="mt-6 flex gap-3">
                <button @click="showRejectModal = false"
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
</div>
