<div id="admin-document-report"
     x-data="{ notification: null, showReportDismissModal: false }" 
     x-on:notify.window="notification = $event.detail; setTimeout(() => notification = null, 3000)"
     @open-modal.window="let d = $event.detail; if (d === 'report-dismiss-modal' || d?.[0] === 'report-dismiss-modal' || d?.id === 'report-dismiss-modal') showReportDismissModal = true"
     @close-modal.window="let d = $event.detail; if (d === 'report-dismiss-modal' || d?.[0] === 'report-dismiss-modal' || d?.id === 'report-dismiss-modal') showReportDismissModal = false"
     class="space-y-6">

    <!-- Notification Toast -->
    <div x-show="notification" 
         x-transition:enter="transition ease-out duration-300"
         x-transition:enter-start="opacity-0 transform translate-y-2"
         x-transition:enter-end="opacity-100 transform translate-y-0"
         x-transition:leave="transition ease-in duration-200"
         x-transition:leave-start="opacity-100 transform translate-y-0"
         x-transition:leave-end="opacity-0 transform translate-y-2"
         class="fixed bottom-5 right-5 z-50 rounded-2xl border bg-white p-4 shadow-xl border-slate-200"
         style="display: none;">
        <div class="flex items-center gap-3">
            <template x-if="notification && notification.type === 'success'">
                <div class="flex h-8 w-8 items-center justify-center rounded-xl bg-emerald-100 text-emerald-600">
                    <svg class="w-5 h-5" fill="none" stroke="currentColor" viewBox="0 0 24 24"><path stroke-linecap="round" stroke-linejoin="round" stroke-width="2" d="M5 13l4 4L19 7"/></svg>
                </div>
            </template>
            <template x-if="notification && notification.type === 'error'">
                <div class="flex h-8 w-8 items-center justify-center rounded-xl bg-rose-100 text-rose-600">
                    <svg class="w-5 h-5" fill="none" stroke="currentColor" viewBox="0 0 24 24"><path stroke-linecap="round" stroke-linejoin="round" stroke-width="2" d="M12 9v2m0 4h.01m-6.938 4h13.856c1.54 0 2.502-1.667 1.732-3L13.732 4c-.77-1.333-2.694-1.333-3.464 0L3.34 16c-.77 1.333.192 3 1.732 3z"/></svg>
                </div>
            </template>
            <div>
                <p class="text-sm font-semibold text-slate-900" x-text="notification ? notification.message : ''"></p>
            </div>
        </div>
    </div>

    @if($dbError)
        <!-- Database Table Missing Alert -->
        <div class="rounded-3xl border border-rose-200 bg-rose-50 p-6 shadow-sm">
            <div class="flex items-start gap-4">
                <div class="flex h-11 w-11 shrink-0 items-center justify-center rounded-2xl bg-rose-100 text-rose-600">
                    <svg class="w-6 h-6" fill="none" stroke="currentColor" viewBox="0 0 24 24"><path stroke-linecap="round" stroke-linejoin="round" stroke-width="2" d="M12 9v2m0 4h.01m-6.938 4h13.856c1.54 0 2.502-1.667 1.732-3L13.732 4c-.77-1.333-2.694-1.333-3.464 0L3.34 16c-.77 1.333.192 3 1.732 3z"/></svg>
                </div>
                <div class="space-y-2">
                    <h3 class="text-lg font-bold text-rose-900">Bảng báo cáo "reports" chưa tồn tại</h3>
                    <p class="text-sm text-rose-700 leading-relaxed">
                        Hệ thống đã nhận diện cấu hình sử dụng bảng <strong>reports</strong> nhưng bảng này chưa được thiết lập trong database của bạn. 
                        Bạn hãy chạy lệnh migrate sau trên terminal để tạo bảng:
                    </p>
                    <div class="rounded-xl bg-slate-900 p-3 font-mono text-xs text-slate-200 select-all max-w-max">
                        php artisan migrate
                    </div>
                </div>
            </div>
        </div>
    @else
        <!-- Statistics Cards -->
        <div class="grid gap-4 sm:gap-6 grid-cols-2 lg:grid-cols-4">
            <!-- Pending -->
            <div class="rounded-2xl border border-slate-200 bg-white p-6 shadow-sm">
                <div class="flex items-center justify-between">
                    <p class="text-sm font-medium text-slate-600">Chờ giải quyết</p>
                    <span class="rounded-xl bg-amber-50 p-2">
                        <svg class="h-5 w-5 text-amber-600" fill="none" stroke="currentColor" viewBox="0 0 24 24">
                            <path stroke-linecap="round" stroke-linejoin="round" stroke-width="2" d="M12 9v2m0 4h.01m-6.938 4h13.856c1.54 0 2.502-1.667 1.732-3L13.732 4c-.77-1.333-2.694-1.333-3.464 0L3.34 16c-.77 1.333.192 3 1.732 3z"/>
                        </svg>
                    </span>
                </div>
                <p class="mt-4 text-3xl font-bold text-slate-900">{{ number_format($pendingCount) }}</p>
                <p class="mt-1 text-sm text-slate-500">Yêu cầu chưa xử lý</p>
            </div>

            <!-- Resolved -->
            <div class="rounded-2xl border border-slate-200 bg-white p-6 shadow-sm">
                <div class="flex items-center justify-between">
                    <p class="text-sm font-medium text-slate-600">Đã xử lý</p>
                    <span class="rounded-xl bg-green-50 p-2">
                        <svg class="h-5 w-5 text-green-600" fill="none" stroke="currentColor" viewBox="0 0 24 24">
                            <path stroke-linecap="round" stroke-linejoin="round" stroke-width="2" d="M9 12l2 2 4-4m6 2a9 9 0 11-18 0 9 9 0 0118 0z"/>
                        </svg>
                    </span>
                </div>
                <p class="mt-4 text-3xl font-bold text-slate-900">{{ number_format($resolvedCount) }}</p>
                <p class="mt-1 text-sm text-slate-500">Tài liệu đã gỡ</p>
            </div>

            <!-- Dismissed -->
            <div class="rounded-2xl border border-slate-200 bg-white p-6 shadow-sm">
                <div class="flex items-center justify-between">
                    <p class="text-sm font-medium text-slate-600">Đã bác bỏ</p>
                    <span class="rounded-xl bg-slate-50 p-2">
                        <svg class="h-5 w-5 text-slate-600" fill="none" stroke="currentColor" viewBox="0 0 24 24">
                            <path stroke-linecap="round" stroke-linejoin="round" stroke-width="2" d="M18.364 18.364A9 9 0 005.636 5.636m12.728 12.728A9 9 0 015.636 5.636m12.728 12.728L5.636 5.636"/>
                        </svg>
                    </span>
                </div>
                <p class="mt-4 text-3xl font-bold text-slate-900">{{ number_format($dismissedCount) }}</p>
                <p class="mt-1 text-sm text-slate-500">Báo cáo không hợp lệ</p>
            </div>

            <!-- Total -->
            <div class="rounded-2xl border border-slate-200 bg-white p-6 shadow-sm">
                <div class="flex items-center justify-between">
                    <p class="text-sm font-medium text-slate-600">Tổng báo cáo</p>
                    <span class="rounded-xl bg-blue-50 p-2">
                        <svg class="h-5 w-5 text-blue-600" fill="none" stroke="currentColor" viewBox="0 0 24 24">
                            <path stroke-linecap="round" stroke-linejoin="round" stroke-width="2" d="M9 5H7a2 2 0 00-2 2v12a2 2 0 002 2h10a2 2 0 002-2V7a2 2 0 00-2-2h-2M9 5a2 2 0 002 2h2a2 2 0 002-2M9 5a2 2 0 012-2h2a2 2 0 012 2"/>
                        </svg>
                    </span>
                </div>
                <p class="mt-4 text-3xl font-bold text-slate-900">{{ number_format($totalCount) }}</p>
                <p class="mt-1 text-sm text-slate-500">Số lượt phản ánh</p>
            </div>
        </div>

        <!-- Filter Header -->
        <div class="rounded-3xl border border-slate-200 bg-white p-6 shadow-sm flex flex-col lg:flex-row lg:items-center lg:justify-between gap-4">
            <div>
                <h2 class="text-lg font-bold text-slate-900">Danh sách báo cáo vi phạm</h2>
                <p class="text-sm text-slate-500 mt-1">Tìm kiếm, phân loại và duyệt/bác bỏ các báo cáo tài liệu vi phạm.</p>
            </div>
            
            <div class="flex flex-col sm:flex-row items-stretch sm:items-center gap-3 w-full lg:w-auto">
                <!-- Status Filter -->
                <select wire:model.live="statusFilter" aria-label="Lọc trạng thái" class="w-full sm:w-auto rounded-2xl border border-slate-200 bg-white px-4 py-2.5 text-sm text-slate-900 focus:border-slate-400 focus:outline-none">
                    <option value="all">Tất cả trạng thái</option>
                    <option value="pending">Chờ giải quyết</option>
                    <option value="resolved">Đã xử lý (Gỡ tài liệu)</option>
                    <option value="dismissed">Đã bác bỏ</option>
                </select>

                <!-- Reason Filter -->
                <select wire:model.live="reasonFilter" aria-label="Lọc lý do" class="w-full sm:w-auto rounded-2xl border border-slate-200 bg-white px-4 py-2.5 text-sm text-slate-900 focus:border-slate-400 focus:outline-none">
                    <option value="all">Tất cả lý do</option>
                    <option value="copyright">Bản quyền / Trùng lặp</option>
                    <option value="spam">Spam / Lừa đảo</option>
                    <option value="inappropriate">Nội dung không phù hợp</option>
                    <option value="other">Khác</option>
                </select>

                <!-- Search Input -->
                <div class="relative flex-1 sm:w-64 sm:flex-initial">
                    <input type="search" 
                           id="search-admin-reports"
                           aria-label="Tìm kiếm báo cáo"
                           wire:model.live.debounce.300ms="search" 
                           placeholder="Tìm kiếm tài liệu, mô tả..." 
                           class="w-full rounded-2xl border border-slate-200 bg-white px-4 py-2.5 pl-10 text-sm text-slate-900 focus:border-slate-400 focus:outline-none" />
                    <span class="absolute inset-y-0 left-3.5 inline-flex items-center text-slate-400">
                        <svg viewBox="0 0 24 24" class="h-4.5 w-4.5" fill="none" stroke="currentColor" stroke-width="2"><circle cx="11" cy="11" r="8" /><path d="M21 21l-4.35-4.35" /></svg>
                    </span>
                </div>
            </div>
        </div>

        <!-- Table Content with Loading State -->
        <div wire:loading.class="opacity-60 transition-opacity duration-200" class="transition-opacity duration-200">
            <!-- Cards Layout List -->
        <div class="grid gap-4 sm:gap-6 grid-cols-1 md:grid-cols-2">
            @forelse($reports as $rep)
                <article class="group relative rounded-3xl border border-slate-200 bg-white p-4 sm:p-6 shadow-sm hover:shadow-md transition-all duration-300 flex flex-col justify-between space-y-4">
                    <div class="space-y-3">
                        <!-- Card Header -->
                        <div class="flex items-center justify-between border-b border-slate-100 pb-3">
                            <span class="text-[10px] text-slate-400 font-mono">Báo cáo #{{ $rep->id }}</span>
                            <span class="text-xs text-slate-500 font-medium">{{ $rep->created_at->diffForHumans() }}</span>
                        </div>

                        <!-- Reported Reason & Status Badges -->
                        <div class="flex flex-wrap items-center gap-2">
                            @if($rep->reason === 'copyright')
                                <span class="inline-flex rounded-full bg-rose-50 px-2.5 py-1 text-xs font-semibold text-rose-700">
                                    Bản quyền / Trùng lặp
                                </span>
                            @elseif($rep->reason === 'spam')
                                <span class="inline-flex rounded-full bg-amber-50 px-2.5 py-1 text-xs font-semibold text-amber-700">
                                    Spam / Lừa đảo
                                </span>
                            @elseif($rep->reason === 'inappropriate')
                                <span class="inline-flex rounded-full bg-orange-50 px-2.5 py-1 text-xs font-semibold text-orange-700">
                                    Không phù hợp
                                </span>
                            @else
                                <span class="inline-flex rounded-full bg-slate-50 px-2.5 py-1 text-xs font-semibold text-slate-700">
                                    Khác
                                </span>
                            @endif

                            @if($rep->status === 'pending')
                                <span class="inline-flex rounded-full bg-yellow-100 px-2.5 py-1 text-xs font-bold text-yellow-800">
                                    Chờ giải quyết
                                </span>
                            @elseif($rep->status === 'resolved')
                                <span class="inline-flex rounded-full bg-emerald-100 px-2.5 py-1 text-xs font-bold text-emerald-800">
                                    Đã gỡ tài liệu
                                </span>
                            @else
                                <span class="inline-flex rounded-full bg-slate-100 px-2.5 py-1 text-xs font-bold text-slate-600">
                                    Đã bác bỏ
                                </span>
                            @endif
                        </div>

                        <!-- Reported Document -->
                        <div class="space-y-1">
                            <h3 class="text-xs font-semibold text-slate-400 uppercase tracking-wider">Tài liệu bị báo cáo:</h3>
                            @if($rep->document)
                                <a href="{{ route('admin.moderation.documents.show', $rep->document->id) }}" class="text-sm sm:text-base font-bold text-slate-900 hover:text-blue-600 line-clamp-2 leading-snug">
                                    {{ $rep->document->title }}
                                </a>
                                <p class="text-[11px] text-slate-400">Tác giả: <span class="font-semibold text-slate-500">{{ $rep->document->author?->name ?? 'Không rõ' }}</span></p>
                            @else
                                <p class="text-sm font-semibold text-rose-600 italic">Tài liệu đã bị xóa khỏi hệ thống</p>
                            @endif
                        </div>

                        <!-- Report Description -->
                        <div class="space-y-1">
                            <h3 class="text-xs font-semibold text-slate-400 uppercase tracking-wider">Nội dung phản ánh:</h3>
                            <div class="rounded-2xl bg-slate-50 border border-slate-100 p-3 sm:p-3.5">
                                <p class="text-xs sm:text-sm text-slate-700 leading-relaxed italic">
                                    "{{ $rep->description ?? 'Không có mô tả chi tiết.' }}"
                                </p>
                            </div>
                        </div>

                        <!-- Reporter & Resolver info -->
                        <div class="grid grid-cols-2 gap-4 text-xs border-t border-slate-100 pt-3">
                            <div>
                                <span class="text-slate-400 font-semibold block">Người báo cáo:</span>
                                <span class="font-bold text-slate-800 block truncate mt-0.5" title="{{ $rep->user?->name ?? 'Học viên ẩn danh' }}">
                                    {{ $rep->user?->name ?? 'Học viên ẩn danh' }}
                                </span>
                            </div>
                            @if($rep->status !== 'pending' && $rep->resolver)
                                <div>
                                    <span class="text-slate-400 font-semibold block">Người xử lý:</span>
                                    <span class="font-bold text-slate-800 block truncate mt-0.5">
                                        {{ $rep->resolver->name }}
                                    </span>
                                </div>
                            @endif
                        </div>

                        <!-- Admin note -->
                        @if($rep->status !== 'pending' && $rep->review_note)
                            <div class="border-t border-slate-100 pt-3 text-xs space-y-1">
                                <span class="text-slate-400 font-semibold block">Ghi chú của Admin:</span>
                                <div class="rounded-xl bg-slate-50 border border-slate-100 p-2.5 text-slate-600 italic">
                                    "{{ $rep->review_note }}"
                                </div>
                            </div>
                        @endif
                    </div>

                    <div class="border-t border-slate-100 pt-4 flex items-center justify-end gap-2">
                        @if($rep->status === 'pending')
                            <button wire:click="openDismissModal({{ $rep->id }})" 
                                    class="inline-flex items-center justify-center rounded-xl border border-slate-200 hover:bg-slate-50 px-3 py-2 text-xs font-bold text-slate-700 transition-colors shadow-sm flex-1">
                                Bác bỏ
                            </button>
                            <button onclick="confirm('Xác nhận CHẤP NHẬN báo cáo và GỠ BỎ tài liệu này?') || event.stopImmediatePropagation()"
                                    wire:click="resolveReport({{ $rep->id }})" 
                                    class="inline-flex items-center justify-center rounded-xl bg-rose-600 hover:bg-rose-500 text-white px-3 py-2 text-xs font-bold transition-all shadow-sm shadow-rose-600/10 flex-1">
                                Duyệt & Gỡ
                            </button>
                        @else
                            <span class="text-xs text-slate-400 italic font-medium">Đã đóng báo cáo vào {{ $rep->resolved_at ? $rep->resolved_at->format('d/m/Y') : '' }}</span>
                        @endif
                    </div>
                </article>
            @empty
                <div class="col-span-full py-16 text-center text-slate-400 bg-white rounded-3xl border border-slate-200">
                    <svg class="w-14 h-14 text-slate-300 mx-auto mb-3" fill="none" stroke="currentColor" viewBox="0 0 24 24"><path stroke-linecap="round" stroke-linejoin="round" stroke-width="1.5" d="M9 12l2 2 4-4m6 2a9 9 0 11-18 0 9 9 0 0118 0z"/></svg>
                    <p class="text-base font-semibold text-slate-500">Hàng đợi trống. Không có báo cáo vi phạm nào.</p>
                </div>
            @endforelse
        </div>

        <!-- Pagination -->
        @if($reports && $reports->hasPages())
            <div class="p-4 border-t border-slate-200 bg-white rounded-3xl">
                {{ $reports->links(data: ['scrollTo' => '#admin-document-report']) }}
            </div>
        @endif
    @endif

    <!-- Dismiss Note Modal -->
    <div x-show="showReportDismissModal" 
         class="fixed inset-0 z-50 overflow-y-auto flex items-center justify-center p-4 bg-slate-900/60 backdrop-blur-sm" 
         style="display: none;"
         x-transition>
        <div class="bg-white rounded-3xl max-w-lg w-full border border-slate-200 shadow-2xl p-6 space-y-6" @click.away="showReportDismissModal = false">
            <div class="flex items-center justify-between border-b border-slate-100 pb-3">
                <h3 class="text-lg font-bold text-slate-900">Bác bỏ báo cáo vi phạm</h3>
                <button @click="showReportDismissModal = false" class="text-slate-400 hover:text-slate-600 text-xl font-bold">&times;</button>
            </div>
            
            <div class="space-y-2">
                <label for="report-note" class="block text-sm font-semibold text-slate-700">
                    Lý do bác bỏ báo cáo <span class="text-red-500">*</span> (tối thiểu 5 ký tự):
                </label>
                <textarea id="report-note"
                          wire:model="reportNote" 
                          rows="4" 
                          placeholder="Ví dụ: Báo cáo không chính xác, tài liệu hoàn toàn hợp lệ và không sao chép..."
                          class="w-full rounded-2xl border border-slate-200 bg-slate-50 px-4 py-3 text-sm text-slate-900 focus:border-slate-400 focus:outline-none"></textarea>
                @error('reportNote')
                    <span class="text-xs text-red-500 font-medium">{{ $message }}</span>
                @enderror
            </div>

            <div class="flex justify-end gap-3 pt-3 border-t border-slate-100">
                <button type="button" @click="showReportDismissModal = false" class="rounded-xl border border-slate-200 hover:bg-slate-50 px-4 py-2.5 text-xs font-semibold text-slate-700 transition-colors">
                    Hủy bỏ
                </button>
                <button type="button" wire:click="confirmDismiss" 
                        class="rounded-xl bg-slate-700 hover:bg-slate-600 text-white px-5 py-2.5 text-xs font-semibold shadow-sm transition-colors">
                    Xác nhận bác bỏ
                </button>
            </div>
        </div>
    </div>
</div>
