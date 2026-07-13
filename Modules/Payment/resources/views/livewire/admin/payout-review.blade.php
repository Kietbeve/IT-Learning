<div id="admin-payout-review" class="space-y-6 py-4" wire:poll.10s.keep-alive>

    <style>
        #admin-payout-review,
        #admin-payout-review.wire-loading,
        #admin-payout-review * {
            transition: none !important;
            animation: none !important;
            opacity: 1 !important;
            transform: none !important;
            visibility: visible !important;
        }
    </style>

    {{-- Thông báo thành công --}}
    @if(session('success'))
        <div class="rounded-2xl border border-emerald-200 bg-emerald-50 px-4 py-3 text-emerald-800 text-sm font-medium">
            {{ session('success') }}
        </div>
    @endif

    {{-- Header --}}
    
    {{-- Thống kê --}}
    <div class="grid gap-4 sm:gap-6 grid-cols-1 sm:grid-cols-3">
        <div class="rounded-2xl bg-white p-6 shadow-sm ring-1 ring-gray-200 transition hover:shadow-md">
            <div class="flex items-center justify-between">
                <p class="text-sm font-medium text-gray-500">Chờ duyệt</p>
                <span class="rounded-xl bg-amber-50 p-2.5 text-amber-600">
                    <svg class="h-5 w-5" fill="none" stroke="currentColor" viewBox="0 0 24 24">
                        <path stroke-linecap="round" stroke-linejoin="round" stroke-width="2" d="M12 8v4l3 3m6-3a9 9 0 11-18 0 9 9 0 0118 0z"/>
                    </svg>
                </span>
            </div>
            <p class="mt-4 text-3xl font-bold text-gray-900">{{ $stats['pending_count'] }}</p>
            <p class="mt-1 text-xs text-gray-500">{{ number_format($stats['pending_amount']) }}đ</p>
        </div>

        <div class="rounded-2xl bg-white p-6 shadow-sm ring-1 ring-gray-200 transition hover:shadow-md">
            <div class="flex items-center justify-between">
                <p class="text-sm font-medium text-gray-500">Duyệt hôm nay</p>
                <span class="rounded-xl bg-emerald-50 p-2.5 text-emerald-600">
                    <svg class="h-5 w-5" fill="none" stroke="currentColor" viewBox="0 0 24 24">
                        <path stroke-linecap="round" stroke-linejoin="round" stroke-width="2" d="M9 12l2 2 4-4m6 2a9 9 0 11-18 0 9 9 0 0118 0z"/>
                    </svg>
                </span>
            </div>
            <p class="mt-4 text-3xl font-bold text-gray-900">{{ $stats['completed_today'] }}</p>
            <p class="mt-1 text-xs text-gray-500">{{ number_format($stats['completed_today_amount']) }}đ</p>
        </div>

        <div class="rounded-2xl bg-white p-6 shadow-sm ring-1 ring-gray-200 transition hover:shadow-md">
            <div class="flex items-center justify-between">
                <p class="text-sm font-medium text-gray-500">Tổng đã chi trả</p>
                <span class="rounded-xl bg-blue-50 p-2.5 text-blue-600">
                    <svg class="h-5 w-5" fill="none" stroke="currentColor" viewBox="0 0 24 24">
                        <path stroke-linecap="round" stroke-linejoin="round" stroke-width="2" d="M12 8c-1.657 0-3 .895-3 2s1.343 2 3 2 3 .895 3 2-1.343 2-3 2m0-8c1.11 0 2.08.402 2.599 1M12 8V7m0 1v8m0 0v1m0-1c-1.11 0-2.08-.402-2.599-1M21 12a9 9 0 11-18 0 9 9 0 0118 0z"/>
                    </svg>
                </span>
            </div>
            <p class="mt-4 text-3xl font-bold text-gray-900">{{ $stats['total_paid_count'] }}</p>
            <p class="mt-1 text-xs text-gray-500">{{ number_format($stats['total_paid']) }}đ</p>
        </div>
    </div>

    {{-- Thanh tìm kiếm và bộ lọc --}}
    <div class="flex flex-col sm:flex-row items-center gap-3">
        {{-- Ô tìm kiếm --}}
        <div class="relative flex-1 w-full">
            <div class="pointer-events-none absolute inset-y-0 left-0 flex items-center pl-3">
                <svg class="h-5 w-5 text-gray-400" viewBox="0 0 20 20" fill="currentColor">
                    <path fill-rule="evenodd" d="M9 3.5a5.5 5.5 0 100 11 5.5 5.5 0 000-11zM2 9a7 7 0 1112.452 4.391l3.328 3.329a.75.75 0 11-1.06 1.06l-3.329-3.328A7 7 0 012 9z" clip-rule="evenodd" />
                </svg>
            </div>
            <input type="search" 
                   wire:model.live.debounce.300ms="search" 
                   placeholder="Tìm kiếm contributor..." 
                   class="block w-full rounded-xl border-0 py-2.5 pl-10 pr-10 text-gray-900 shadow-sm ring-1 ring-inset ring-gray-300 placeholder:text-gray-400 focus:ring-2 focus:ring-inset focus:ring-blue-600 sm:text-sm sm:leading-6 transition-all" />
            <div wire:loading.flex wire:target="search" class="absolute inset-y-0 right-3 items-center">
                <svg class="h-4 w-4 animate-spin text-blue-600" xmlns="http://www.w3.org/2000/svg" fill="none" viewBox="0 0 24 24">
                    <circle class="opacity-25" cx="12" cy="12" r="10" stroke="currentColor" stroke-width="4"></circle>
                    <path class="opacity-75" fill="currentColor" d="M4 12a8 8 0 018-8V0C5.373 0 0 5.373 0 12h4z"></path>
                </svg>
            </div>
        </div>

        {{-- Bộ lọc trạng thái --}}
        <div class="relative w-full sm:w-auto min-w-[200px] shrink-0" x-data="{ open: false }" @click.away="open = false">
            <div @click="open = !open" 
                 class="flex items-center justify-between w-full rounded-xl border border-gray-200 bg-white px-4 py-2.5 text-sm text-gray-900 cursor-pointer hover:bg-gray-50 transition-colors shadow-sm h-[42px] ring-1 ring-inset ring-gray-300">
                <div class="flex items-center gap-2">
                    <svg class="w-4 h-4 text-gray-400" fill="none" stroke="currentColor" viewBox="0 0 24 24"><path stroke-linecap="round" stroke-linejoin="round" stroke-width="2" d="M3 4a1 1 0 011-1h16a1 1 0 011 1v2.586a1 1 0 01-.293.707l-6.414 6.414a1 1 0 00-.293.707V17l-4 4v-6.586a1 1 0 00-.293-.707L3.293 7.293A1 1 0 013 6.586V4z"/></svg>
                    <span class="font-medium">
                        @if($statusFilter === 'all' || empty($statusFilter)) Tất cả trạng thái
                        @elseif($statusFilter === 'pending') Chờ duyệt
                        @elseif($statusFilter === 'completed') Đã duyệt
                        @elseif($statusFilter === 'rejected') Từ chối
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
                        'pending' => 'Chờ duyệt',
                        'completed' => 'Đã duyệt',
                        'rejected' => 'Từ chối'
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

        {{-- Bộ lọc ngày --}}
        <div class="flex items-center gap-2 w-full sm:w-auto">
            <input type="date" wire:model.live="dateFrom" max="{{ date('Y-m-d') }}"
                   class="flex-1 sm:flex-none h-[42px] rounded-xl border border-gray-200 px-3 text-sm text-gray-900 shadow-sm focus:border-blue-400 focus:outline-none focus:ring-0 transition-colors">
            <span class="text-gray-400">-</span>
            <input type="date" wire:model.live="dateTo" max="{{ date('Y-m-d') }}"
                   class="flex-1 sm:flex-none h-[42px] rounded-xl border border-gray-200 px-3 text-sm text-gray-900 shadow-sm focus:border-blue-400 focus:outline-none focus:ring-0 transition-colors">
            {{-- Wrapper cố định chiều rộng để không bị layout shift --}}
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
    </div>

    {{-- Bảng --}}
    <div class="overflow-hidden rounded-2xl bg-white shadow ring-1 ring-gray-200">
            <div wire:loading.class="opacity-50 pointer-events-none" class="transition-opacity duration-200">
                <div class="overflow-x-auto">
                    <table class="w-full table-fixed divide-y divide-gray-200 min-w-[1000px]">
                        <thead class="bg-slate-50/50">
                            <tr>
                                <th scope="col" class="py-3.5 pl-6 pr-3 text-left text-sm font-semibold text-gray-900 w-[8%]">ID</th>
                                <th scope="col" class="px-3 py-3.5 text-left text-sm font-semibold text-gray-900 w-[18%]">Contributor</th>
                                <th scope="col" class="px-3 py-3.5 text-right text-sm font-semibold text-gray-900 cursor-pointer hover:bg-slate-100/50 transition-colors w-[12%]" wire:click="sortBy('amount')">
                                    <div class="flex items-center justify-end gap-2">
                                        Số tiền
                                        @if($sortField === 'amount')
                                            <span class="text-gray-400 text-xs">{{ $sortDirection === 'asc' ? '▲' : '▼' }}</span>
                                        @endif
                                    </div>
                                </th>
                                <th scope="col" class="px-3 py-3.5 text-left text-sm font-semibold text-gray-900 w-[18%]">Ngân hàng</th>
                                <th scope="col" class="px-3 py-3.5 text-left text-sm font-semibold text-gray-900 w-[12%]">Trạng thái</th>
                                <th scope="col" class="px-3 py-3.5 text-left text-sm font-semibold text-gray-900 cursor-pointer hover:bg-slate-100/50 transition-colors w-[12%]" wire:click="sortBy('created_at')">
                                    <div class="flex items-center gap-2">
                                        Ngày tạo
                                        @if($sortField === 'created_at')
                                            <span class="text-gray-400 text-xs">{{ $sortDirection === 'asc' ? '▲' : '▼' }}</span>
                                        @endif
                                    </div>
                                </th>
                                <th scope="col" class="relative py-3.5 pl-3 pr-6 text-center text-sm font-semibold text-gray-900 w-[20%]">Thao tác</th>
                            </tr>
                        </thead>
                        <tbody class="divide-y divide-gray-200 bg-white">
                            @forelse($payouts as $payout)
                                <tr class="transition hover:bg-gray-50/50">
                                    <td class="whitespace-nowrap py-4 pl-6 pr-3 text-sm font-mono text-gray-900">#{{ $payout->id }}</td>
                                    <td class="whitespace-nowrap px-3 py-4 text-sm">
                                        <div class="font-medium text-gray-900 truncate max-w-[140px]" title="{{ $payout->user->name }}">{{ $payout->user->name }}</div>
                                        <div class="text-xs text-gray-500 truncate max-w-[140px]" title="{{ $payout->user->email }}">{{ $payout->user->email }}</div>
                                        <div class="text-xs text-gray-400 mt-0.5">Số dư: {{ number_format($payout->user->contributor_balance) }}đ</div>
                                    </td>
                                    <td class="whitespace-nowrap px-3 py-4 text-right text-sm font-bold text-gray-900">{{ number_format($payout->amount) }}đ</td>
                                    <td class="whitespace-nowrap px-3 py-4 text-sm">
                                        <div class="font-medium text-gray-900 truncate max-w-[120px]" title="{{ $payout->bank_name }}">{{ $payout->bank_name }}</div>
                                        <div class="text-xs text-gray-500 truncate max-w-[120px]" title="{{ $payout->bank_account_number }}">{{ $payout->bank_account_number }}</div>
                                        <div class="text-xs text-gray-500 truncate max-w-[120px]" title="{{ $payout->bank_account_name }}">{{ $payout->bank_account_name }}</div>
                                    </td>
                                    <td class="whitespace-nowrap px-3 py-4 text-sm">
                                        @if($payout->status === 'pending')
                                            <span class="inline-flex items-center rounded-md bg-amber-50 px-2 py-1 text-xs font-medium text-amber-800 ring-1 ring-inset ring-amber-600/20">Chờ duyệt</span>
                                        @elseif($payout->status === 'completed')
                                            <span class="inline-flex items-center rounded-md bg-emerald-50 px-2 py-1 text-xs font-medium text-emerald-700 ring-1 ring-inset ring-emerald-600/20">Đã duyệt</span>
                                        @elseif($payout->status === 'rejected')
                                            <span class="inline-flex items-center rounded-md bg-rose-50 px-2 py-1 text-xs font-medium text-rose-700 ring-1 ring-inset ring-rose-600/10">Từ chối</span>
                                        @endif
                                        @if($payout->status === 'rejected' && $payout->rejection_reason)
                                            <p class="mt-1 text-xs text-rose-600 truncate max-w-[150px]" title="{{ $payout->rejection_reason }}">Lý do: {{ Str::limit($payout->rejection_reason, 30) }}</p>
                                        @endif
                                        @if($payout->note)
                                            <p class="mt-1 text-xs text-gray-500 truncate max-w-[150px]" title="{{ $payout->note }}">{{ Str::limit($payout->note, 30) }}</p>
                                        @endif
                                        @if($payout->processed_at)
                                            <p class="mt-1 text-xs text-gray-400">{{ $payout->processed_at->format('d/m/Y H:i') }}</p>
                                        @endif
                                    </td>
                                    <td class="whitespace-nowrap px-3 py-4 text-sm text-gray-500">
                                        <div class="text-gray-900">{{ $payout->created_at->format('d/m/Y') }}</div>
                                        <div class="text-xs">{{ $payout->created_at->format('H:i') }}</div>
                                    </td>
                                    <td class="relative whitespace-nowrap py-4 pl-3 pr-6 text-center text-sm font-medium">
                                        @if($payout->status === 'pending')
                                            <div class="flex flex-col items-center justify-center gap-1.5">
                                                <button wire:click="openDetailModal({{ $payout->id }})"
                                                        class="inline-flex w-[100px] items-center justify-center gap-1.5 rounded-lg bg-blue-600 px-3 py-1.5 text-xs font-semibold text-white shadow-sm hover:bg-blue-500 transition-colors">
                                                    <svg class="h-3.5 w-3.5" fill="none" stroke="currentColor" viewBox="0 0 24 24"><path stroke-linecap="round" stroke-linejoin="round" stroke-width="2" d="M15 12a3 3 0 11-6 0 3 3 0 016 0z"/><path stroke-linecap="round" stroke-linejoin="round" stroke-width="2" d="M2.458 12C3.732 7.943 7.523 5 12 5c4.478 0 8.268 2.943 9.542 7-1.274 4.057-5.064 7-9.542 7-4.477 0-8.268-2.943-9.542-7z"/></svg>
                                                    Xem chi tiết
                                                </button>
                                                <div class="flex gap-1.5">
                                                    <button wire:click="openApproveModal({{ $payout->id }})"
                                                            class="inline-flex w-[48px] items-center justify-center rounded-lg bg-emerald-600 px-2 py-1.5 text-xs font-semibold text-white shadow-sm hover:bg-emerald-500 transition-colors">
                                                        <svg class="h-3.5 w-3.5" viewBox="0 0 20 20" fill="currentColor"><path fill-rule="evenodd" d="M16.704 4.153a.75.75 0 01.143 1.052l-8 10.5a.75.75 0 01-1.127.075l-4.5-4.5a.75.75 0 011.06-1.06l3.894 3.893 7.48-9.817a.75.75 0 011.05-.143z" clip-rule="evenodd" /></svg>
                                                    </button>
                                                    <button wire:click="openRejectModal({{ $payout->id }})"
                                                            class="inline-flex w-[48px] items-center justify-center rounded-lg bg-rose-600 px-2 py-1.5 text-xs font-semibold text-white shadow-sm hover:bg-rose-500 transition-colors">
                                                        <svg class="h-3.5 w-3.5" fill="none" stroke="currentColor" viewBox="0 0 24 24"><path stroke-linecap="round" stroke-linejoin="round" stroke-width="2" d="M6 18L18 6M6 6l12 12"/></svg>
                                                    </button>
                                                </div>
                                            </div>
                                        @elseif($payout->receipt_image)
                                            <a href="{{ $payout->receipt_url }}" target="_blank"
                                               class="inline-flex items-center gap-1.5 rounded-lg bg-blue-50 px-3 py-1.5 text-xs font-semibold text-blue-700 ring-1 ring-inset ring-blue-600/20 hover:bg-blue-100 transition-colors">
                                                <svg class="h-3.5 w-3.5" fill="none" stroke="currentColor" viewBox="0 0 24 24"><path stroke-linecap="round" stroke-linejoin="round" stroke-width="2" d="M15 12a3 3 0 11-6 0 3 3 0 016 0z"/><path stroke-linecap="round" stroke-linejoin="round" stroke-width="2" d="M2.458 12C3.732 7.943 7.523 5 12 5c4.478 0 8.268 2.943 9.542 7-1.274 4.057-5.064 7-9.542 7-4.477 0-8.268-2.943-9.542-7z"/></svg>
                                                Xem chứng từ
                                            </a>
                                        @else
                                            <span class="text-xs text-gray-400">-</span>
                                        @endif
                                    </td>
                                </tr>
                            @empty
                                <tr>
                                    <td colspan="7" class="py-12 px-4 text-center">
                                        <svg class="mx-auto h-12 w-12 text-gray-300" fill="none" viewBox="0 0 24 24" stroke="currentColor">
                                            <path stroke-linecap="round" stroke-linejoin="round" stroke-width="1.5" d="M19 11H5m14 0a2 2 0 012 2v6a2 2 0 01-2 2H5a2 2 0 01-2-2v-6a2 2 0 012-2m14 0V9a2 2 0 00-2-2M5 11V9a2 2 0 012-2m0 0V5a2 2 0 012-2h6a2 2 0 012 2v2M7 7h10" />
                                        </svg>
                                        <h3 class="mt-2 text-sm font-semibold text-gray-900">Không có dữ liệu</h3>
                                        <p class="mt-1 text-sm text-gray-500">Không có yêu cầu rút tiền nào khớp với bộ lọc hiện tại.</p>
                                    </td>
                                </tr>
                            @endforelse
                        </tbody>
                    </table>
                </div>
            </div>

            @if($payouts->hasPages())
                <div class="border-t border-gray-200 px-4 py-3">
                    {{ $payouts->links() }}
                </div>
            @endif
        </div>

    {{-- Modal duyệt --}}
    <div x-data="{ showApproveModal: @entangle('showApproveModal') }" x-effect="document.body.style.overflow = showApproveModal ? 'hidden' : ''">
        <template x-teleport="body">
            <div x-show="showApproveModal" class="fixed inset-0 z-[100] overflow-y-auto flex items-center justify-center p-4 sm:p-0" style="display: none;">
                <div x-show="showApproveModal" 
                     x-transition:enter="ease-out duration-300" x-transition:enter-start="opacity-0" x-transition:enter-end="opacity-100" 
                     x-transition:leave="ease-in duration-200" x-transition:leave-start="opacity-100" x-transition:leave-end="opacity-0"
                     class="fixed inset-0 bg-gray-900/50 backdrop-blur-sm transition-opacity" @click="showApproveModal = false"></div>

                <div x-show="showApproveModal"
                     x-transition:enter="ease-out duration-300" x-transition:enter-start="opacity-0 translate-y-4 sm:translate-y-0 sm:scale-95" x-transition:enter-end="opacity-100 translate-y-0 sm:scale-100"
                     x-transition:leave="ease-in duration-200" x-transition:leave-start="opacity-100 translate-y-0 sm:scale-100" x-transition:leave-end="opacity-0 translate-y-4 sm:translate-y-0 sm:scale-95"
                     class="relative transform overflow-hidden rounded-2xl bg-white px-4 pb-4 pt-5 text-left shadow-xl transition-all sm:my-8 sm:w-full sm:max-w-lg sm:p-6">

                    <div class="flex items-center justify-between border-b border-gray-100 pb-4 mb-4">
                        <h3 class="text-lg font-bold leading-6 text-gray-900">Duyệt yêu cầu rút tiền</h3>
                        <button @click="showApproveModal = false" class="rounded-lg p-1 text-gray-400 hover:bg-gray-100 hover:text-gray-500 transition-colors">
                            <svg class="h-5 w-5" viewBox="0 0 20 20" fill="currentColor"><path fill-rule="evenodd" d="M4.293 4.293a1 1 0 011.414 0L10 8.586l4.293-4.293a1 1 0 111.414 1.414L11.414 10l4.293 4.293a1 1 0 01-1.414 1.414L10 11.414l-4.293 4.293a1 1 0 01-1.414-1.414L8.586 10 4.293 5.707a1 1 0 010-1.414z" clip-rule="evenodd" /></svg>
                        </button>
                    </div>

                    <div class="space-y-4">
                        <p class="text-sm text-gray-600">Xác nhận duyệt và chuyển khoản cho contributor</p>

                        <div>
                            <label class="block text-sm font-medium text-gray-700 mb-2">Chứng từ chuyển khoản <span class="text-gray-400 font-normal">(tùy chọn)</span></label>
                            <div class="flex items-center gap-3">
                                <label for="receiptUpload" class="cursor-pointer inline-flex items-center gap-2 rounded-xl bg-white px-4 py-2 text-sm font-semibold text-gray-700 shadow-sm ring-1 ring-inset ring-gray-300 hover:bg-gray-50 transition-colors">
                                    <svg class="w-4 h-4" fill="none" stroke="currentColor" viewBox="0 0 24 24"><path stroke-linecap="round" stroke-linejoin="round" stroke-width="2" d="M4 16v1a3 3 0 003 3h10a3 3 0 003-3v-1m-4-8l-4-4m0 0L8 8m4-4v12"/></svg>
                                    Chọn tệp
                                </label>
                                <input type="file" id="receiptUpload" wire:model="receiptImage" accept="image/*" class="hidden">
                                <span class="text-sm text-gray-500">
                                    <span wire:loading.remove wire:target="receiptImage">
                                        @if(!$receiptImage) Chưa chọn tệp @endif
                                    </span>
                                    <span wire:loading wire:target="receiptImage" class="text-blue-600">Đang tải lên...</span>
                                </span>
                            </div>

                            @if($receiptImage)
                                <div class="mt-3" x-data="{ showPreview: false }">
                                    <p class="text-xs text-gray-600 mb-2">Xem trước ảnh đã chọn:</p>
                                    <div class="relative inline-block">
                                        <img src="{{ $receiptImage->temporaryUrl() }}" 
                                             @click="showPreview = true"
                                             class="w-32 h-32 object-cover rounded-lg border-2 border-gray-200 cursor-pointer hover:border-blue-500 transition-colors"
                                             alt="Preview">
                                        <button type="button" wire:click="$set('receiptImage', null)" 
                                                class="absolute top-1 right-1 bg-red-500 text-white rounded-full p-1 hover:bg-red-600 shadow-lg">
                                            <svg class="w-4 h-4" fill="none" stroke="currentColor" viewBox="0 0 24 24"><path stroke-linecap="round" stroke-linejoin="round" stroke-width="2" d="M6 18L18 6M6 6l12 12"/></svg>
                                        </button>
                                    </div>
                                    <!-- Lightbox -->
                                    <div x-show="showPreview" @click="showPreview = false"
                                         class="fixed inset-0 z-[110] flex items-center justify-center bg-black/90 p-4">
                                        <div class="relative max-w-4xl max-h-full">
                                            <img src="{{ $receiptImage->temporaryUrl() }}" 
                                                 class="max-w-full max-h-[90vh] rounded-lg" @click.stop alt="Full preview">
                                            <button @click="showPreview = false" 
                                                    class="absolute top-2 right-2 bg-white text-gray-900 rounded-full p-2 hover:bg-gray-100">
                                                <svg class="w-6 h-6" fill="none" stroke="currentColor" viewBox="0 0 24 24"><path stroke-linecap="round" stroke-linejoin="round" stroke-width="2" d="M6 18L18 6M6 6l12 12"/></svg>
                                            </button>
                                        </div>
                                    </div>
                                </div>
                            @endif
                            @error('receiptImage') <span class="text-xs text-rose-600 mt-1 block">{{ $message }}</span> @enderror
                        </div>
                    </div>

                    <div class="mt-6 flex flex-col-reverse sm:flex-row sm:justify-end gap-3">
                        <button type="button" @click="showApproveModal = false" class="inline-flex w-full sm:w-auto justify-center rounded-xl bg-white px-4 py-2.5 text-sm font-semibold text-gray-900 shadow-sm ring-1 ring-inset ring-gray-300 hover:bg-gray-50 transition-colors">
                            Hủy bỏ
                        </button>
                        <button type="button" wire:click="approvePayout" class="inline-flex w-full sm:w-auto justify-center rounded-xl bg-emerald-600 px-4 py-2.5 text-sm font-semibold text-white shadow-sm hover:bg-emerald-500 focus-visible:outline focus-visible:outline-2 focus-visible:outline-offset-2 focus-visible:outline-emerald-600 transition-colors">
                            Xác nhận duyệt
                        </button>
                    </div>
                </div>
            </div>
        </template>
    </div>

    {{-- Modal từ chối --}}
    <div x-data="{ showRejectModal: @entangle('showRejectModal') }" x-effect="document.body.style.overflow = showRejectModal ? 'hidden' : ''">
        <template x-teleport="body">
            <div x-show="showRejectModal" class="fixed inset-0 z-[100] overflow-y-auto flex items-center justify-center p-4 sm:p-0" style="display: none;">
                <div x-show="showRejectModal" 
                     x-transition:enter="ease-out duration-300" x-transition:enter-start="opacity-0" x-transition:enter-end="opacity-100" 
                     x-transition:leave="ease-in duration-200" x-transition:leave-start="opacity-100" x-transition:leave-end="opacity-0"
                     class="fixed inset-0 bg-gray-900/50 backdrop-blur-sm transition-opacity" @click="showRejectModal = false"></div>

                <div x-show="showRejectModal"
                     x-transition:enter="ease-out duration-300" x-transition:enter-start="opacity-0 translate-y-4 sm:translate-y-0 sm:scale-95" x-transition:enter-end="opacity-100 translate-y-0 sm:scale-100"
                     x-transition:leave="ease-in duration-200" x-transition:leave-start="opacity-100 translate-y-0 sm:scale-100" x-transition:leave-end="opacity-0 translate-y-4 sm:translate-y-0 sm:scale-95"
                     class="relative transform overflow-hidden rounded-2xl bg-white px-4 pb-4 pt-5 text-left shadow-xl transition-all sm:my-8 sm:w-full sm:max-w-lg sm:p-6">

                    <div class="flex items-center justify-between border-b border-gray-100 pb-4 mb-4">
                        <h3 class="text-lg font-bold leading-6 text-gray-900">Từ chối yêu cầu rút tiền</h3>
                        <button @click="showRejectModal = false" class="rounded-lg p-1 text-gray-400 hover:bg-gray-100 hover:text-gray-500 transition-colors">
                            <svg class="h-5 w-5" viewBox="0 0 20 20" fill="currentColor"><path fill-rule="evenodd" d="M4.293 4.293a1 1 0 011.414 0L10 8.586l4.293-4.293a1 1 0 111.414 1.414L11.414 10l4.293 4.293a1 1 0 01-1.414 1.414L10 11.414l-4.293 4.293a1 1 0 01-1.414-1.414L8.586 10 4.293 5.707a1 1 0 010-1.414z" clip-rule="evenodd" /></svg>
                        </button>
                    </div>

                    <div class="space-y-3">
                        <label for="rejection-reason" class="block text-sm font-medium text-gray-700">Lý do từ chối <span class="text-rose-500">*</span> <span class="text-gray-400 font-normal">(tối thiểu 10 ký tự)</span></label>
                        <textarea id="rejection-reason"
                                  wire:model="rejectionReason" 
                                  rows="4" 
                                  placeholder="Ví dụ: Sai thông tin tài khoản, số tiền vượt quá hạn mức..."
                                  class="w-full rounded-2xl border border-gray-200 bg-gray-50 px-4 py-3 text-sm text-gray-900 focus:border-gray-400 focus:outline-none"></textarea>
                        @error('rejectionReason')
                            <p class="text-sm text-rose-500 font-medium mt-1">{{ $message }}</p>
                        @enderror
                    </div>

                    <div class="mt-6 flex flex-col-reverse sm:flex-row sm:justify-end gap-3">
                        <button type="button" @click="showRejectModal = false" class="inline-flex w-full sm:w-auto justify-center rounded-xl bg-white px-4 py-2.5 text-sm font-semibold text-gray-900 shadow-sm ring-1 ring-inset ring-gray-300 hover:bg-gray-50 transition-colors">
                            Hủy bỏ
                        </button>
                        <button type="button" wire:click="rejectPayout" class="inline-flex w-full sm:w-auto justify-center rounded-xl bg-rose-600 px-4 py-2.5 text-sm font-semibold text-white shadow-sm hover:bg-rose-500 focus-visible:outline focus-visible:outline-2 focus-visible:outline-offset-2 focus-visible:outline-rose-600 transition-colors">
                            Xác nhận từ chối
                        </button>
                    </div>
                </div>
            </div>
        </template>
    </div>

    {{-- Modal chi tiết --}}
    <div x-data="{ showDetailModal: @entangle('showDetailModal') }" x-effect="document.body.style.overflow = showDetailModal ? 'hidden' : ''">
        <template x-teleport="body">
            <div x-show="showDetailModal" class="fixed inset-0 z-[100] overflow-y-auto flex items-center justify-center p-4 sm:p-0" style="display: none;">
                <div x-show="showDetailModal" 
                     x-transition:enter="ease-out duration-300" x-transition:enter-start="opacity-0" x-transition:enter-end="opacity-100" 
                     x-transition:leave="ease-in duration-200" x-transition:leave-start="opacity-100" x-transition:leave-end="opacity-0"
                     class="fixed inset-0 bg-gray-900/50 backdrop-blur-sm transition-opacity" @click="showDetailModal = false"></div>

                <div x-show="showDetailModal"
                     x-transition:enter="ease-out duration-300" x-transition:enter-start="opacity-0 translate-y-4 sm:translate-y-0 sm:scale-95" x-transition:enter-end="opacity-100 translate-y-0 sm:scale-100"
                     x-transition:leave="ease-in duration-200" x-transition:leave-start="opacity-100 translate-y-0 sm:scale-100" x-transition:leave-end="opacity-0 translate-y-4 sm:translate-y-0 sm:scale-95"
                     class="relative transform overflow-hidden rounded-2xl bg-white px-4 pb-4 pt-5 text-left shadow-xl transition-all sm:my-8 sm:w-full sm:max-w-2xl sm:p-6">

                    <div class="flex items-center justify-between border-b border-gray-100 pb-4 mb-4">
                        <h3 class="text-lg font-bold leading-6 text-gray-900">Chi tiết yêu cầu rút tiền</h3>
                        <button @click="showDetailModal = false" class="rounded-lg p-1 text-gray-400 hover:bg-gray-100 hover:text-gray-500 transition-colors">
                            <svg class="h-5 w-5" viewBox="0 0 20 20" fill="currentColor"><path fill-rule="evenodd" d="M4.293 4.293a1 1 0 011.414 0L10 8.586l4.293-4.293a1 1 0 111.414 1.414L11.414 10l4.293 4.293a1 1 0 01-1.414 1.414L10 11.414l-4.293 4.293a1 1 0 01-1.414-1.414L8.586 10 4.293 5.707a1 1 0 010-1.414z" clip-rule="evenodd" /></svg>
                        </button>
                    </div>

                    @if($detailPayout)
                        <div class="space-y-5 max-h-[60vh] overflow-y-auto pr-2 custom-scrollbar">
                            <div class="rounded-xl bg-blue-50 p-4">
                                <h4 class="text-sm font-semibold text-blue-900 mb-2">Thông tin contributor</h4>
                                <div class="grid grid-cols-2 gap-2 text-sm">
                                    <div>
                                        <span class="text-blue-700 font-medium">Tên:</span>
                                        <span class="text-blue-900 ml-1">{{ $detailPayout->user->name }}</span>
                                    </div>
                                    <div>
                                        <span class="text-blue-700 font-medium">Email:</span>
                                        <span class="text-blue-900 ml-1">{{ $detailPayout->user->email }}</span>
                                    </div>
                                    <div class="col-span-2">
                                        <span class="text-blue-700 font-medium">Số dư hiện tại:</span>
                                        <span class="text-blue-900 font-bold text-lg ml-1">{{ number_format($detailPayout->user->contributor_balance) }}đ</span>
                                    </div>
                                </div>
                            </div>

                            <div class="rounded-xl bg-gray-50 p-4">
                                <h4 class="text-sm font-semibold text-gray-900 mb-2">Thông tin yêu cầu</h4>
                                <div class="space-y-2 text-sm">
                                    <div class="flex justify-between py-2 border-b border-gray-200">
                                        <span class="text-gray-600">Số tiền rút:</span>
                                        <span class="font-bold text-gray-900 text-lg">{{ number_format($detailPayout->amount) }}đ</span>
                                    </div>
                                    <div class="flex justify-between py-2 border-b border-gray-200">
                                        <span class="text-gray-600">Ngân hàng:</span>
                                        <span class="font-medium text-gray-900">{{ $detailPayout->bank_name }}</span>
                                    </div>
                                    <div class="flex justify-between py-2 border-b border-gray-200">
                                        <span class="text-gray-600">Số tài khoản:</span>
                                        <span class="font-mono text-gray-900">{{ $detailPayout->bank_account_number }}</span>
                                    </div>
                                    <div class="flex justify-between py-2 border-b border-gray-200">
                                        <span class="text-gray-600">Tên chủ TK:</span>
                                        <span class="font-medium text-gray-900">{{ $detailPayout->bank_account_name }}</span>
                                    </div>
                                    @if($detailPayout->note)
                                        <div class="py-2">
                                            <span class="text-gray-600 block mb-1">Ghi chú của contributor:</span>
                                            <p class="text-gray-900 bg-white p-2 rounded border border-gray-200">{{ $detailPayout->note }}</p>
                                        </div>
                                    @endif
                                    <div class="flex justify-between py-2">
                                        <span class="text-gray-600">Ngày tạo:</span>
                                        <span class="text-gray-900">{{ $detailPayout->created_at->format('d/m/Y H:i') }}</span>
                                    </div>
                                </div>
                            </div>
                        </div>

                        <div class="mt-6 flex flex-col-reverse sm:flex-row sm:justify-end gap-3">
                            <button type="button" @click="showDetailModal = false" class="inline-flex w-full sm:w-auto justify-center rounded-xl bg-white px-4 py-2.5 text-sm font-semibold text-gray-900 shadow-sm ring-1 ring-inset ring-gray-300 hover:bg-gray-50 transition-colors">
                                Đóng
                            </button>
                            <button type="button" @click="showDetailModal = false; $wire.openApproveModal({{ $detailPayout->id }})" class="inline-flex w-full sm:w-auto justify-center rounded-xl bg-emerald-600 px-4 py-2.5 text-sm font-semibold text-white shadow-sm hover:bg-emerald-500 transition-colors">
                                Duyệt ngay
                            </button>
                            <button type="button" @click="showDetailModal = false; $wire.openRejectModal({{ $detailPayout->id }})" class="inline-flex w-full sm:w-auto justify-center rounded-xl bg-rose-600 px-4 py-2.5 text-sm font-semibold text-white shadow-sm hover:bg-rose-500 transition-colors">
                                Từ chối
                            </button>
                        </div>
                    @endif
                </div>
            </div>
        </template>
    </div>

    <style>
        .custom-scrollbar::-webkit-scrollbar {
            width: 6px;
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
</div>