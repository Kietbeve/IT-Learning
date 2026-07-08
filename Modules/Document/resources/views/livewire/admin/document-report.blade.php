<div id="admin-document-report"
     x-data="{ notification: null, showReportResolveModal: false, showReportDismissModal: false }" 
     x-on:notify.window="notification = $event.detail; setTimeout(() => notification = null, 3000)"
     @open-modal.window="let d = $event.detail; if (d === 'report-resolve-modal' || d?.[0] === 'report-resolve-modal' || d?.id === 'report-resolve-modal') showReportResolveModal = true; if (d === 'report-dismiss-modal' || d?.[0] === 'report-dismiss-modal' || d?.id === 'report-dismiss-modal') showReportDismissModal = true;"
     @close-modal.window="let d = $event.detail; if (d === 'report-resolve-modal' || d?.[0] === 'report-resolve-modal' || d?.id === 'report-resolve-modal') showReportResolveModal = false; if (d === 'report-dismiss-modal' || d?.[0] === 'report-dismiss-modal' || d?.id === 'report-dismiss-modal') showReportDismissModal = false;"
     class="space-y-6">



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
                    <div class="rounded-xl bg-gray-900 p-3 font-mono text-xs text-gray-200 select-all max-w-max">
                        php artisan migrate
                    </div>
                </div>
            </div>
        </div>
    @else
        <!-- Statistics Cards -->
        <div class="grid gap-4 sm:gap-6 grid-cols-2 lg:grid-cols-4">
            <!-- Pending -->
            <div class="rounded-2xl border border-gray-200 bg-white p-6 shadow-sm">
                <div class="flex items-center justify-between">
                    <p class="text-sm font-medium text-gray-600">Chờ giải quyết</p>
                    <span class="rounded-xl bg-amber-50 p-2">
                        <svg class="h-5 w-5 text-amber-600" fill="none" stroke="currentColor" viewBox="0 0 24 24">
                            <path stroke-linecap="round" stroke-linejoin="round" stroke-width="2" d="M12 9v2m0 4h.01m-6.938 4h13.856c1.54 0 2.502-1.667 1.732-3L13.732 4c-.77-1.333-2.694-1.333-3.464 0L3.34 16c-.77 1.333.192 3 1.732 3z"/>
                        </svg>
                    </span>
                </div>
                <p class="mt-4 text-3xl font-bold text-gray-900">{{ number_format($pendingCount) }}</p>
                <p class="mt-1 text-sm text-gray-500">Yêu cầu chưa xử lý</p>
            </div>

            <!-- Resolved -->
            <div class="rounded-2xl border border-gray-200 bg-white p-6 shadow-sm">
                <div class="flex items-center justify-between">
                    <p class="text-sm font-medium text-gray-600">Đã xử lý</p>
                    <span class="rounded-xl bg-green-50 p-2">
                        <svg class="h-5 w-5 text-green-600" fill="none" stroke="currentColor" viewBox="0 0 24 24">
                            <path stroke-linecap="round" stroke-linejoin="round" stroke-width="2" d="M9 12l2 2 4-4m6 2a9 9 0 11-18 0 9 9 0 0118 0z"/>
                        </svg>
                    </span>
                </div>
                <p class="mt-4 text-3xl font-bold text-gray-900">{{ number_format($resolvedCount) }}</p>
                <p class="mt-1 text-sm text-gray-500">Tài liệu đã gỡ</p>
            </div>

            <!-- Dismissed -->
            <div class="rounded-2xl border border-gray-200 bg-white p-6 shadow-sm">
                <div class="flex items-center justify-between">
                    <p class="text-sm font-medium text-gray-600">Đã bác bỏ</p>
                    <span class="rounded-xl bg-gray-50 p-2">
                        <svg class="h-5 w-5 text-gray-600" fill="none" stroke="currentColor" viewBox="0 0 24 24">
                            <path stroke-linecap="round" stroke-linejoin="round" stroke-width="2" d="M18.364 18.364A9 9 0 005.636 5.636m12.728 12.728A9 9 0 015.636 5.636m12.728 12.728L5.636 5.636"/>
                        </svg>
                    </span>
                </div>
                <p class="mt-4 text-3xl font-bold text-gray-900">{{ number_format($dismissedCount) }}</p>
                <p class="mt-1 text-sm text-gray-500">Báo cáo không hợp lệ</p>
            </div>

            <!-- Total -->
            <div class="rounded-2xl border border-gray-200 bg-white p-6 shadow-sm">
                <div class="flex items-center justify-between">
                    <p class="text-sm font-medium text-gray-600">Tổng báo cáo</p>
                    <span class="rounded-xl bg-blue-50 p-2">
                        <svg class="h-5 w-5 text-blue-600" fill="none" stroke="currentColor" viewBox="0 0 24 24">
                            <path stroke-linecap="round" stroke-linejoin="round" stroke-width="2" d="M9 5H7a2 2 0 00-2 2v12a2 2 0 002 2h10a2 2 0 002-2V7a2 2 0 00-2-2h-2M9 5a2 2 0 002 2h2a2 2 0 002-2M9 5a2 2 0 012-2h2a2 2 0 012 2"/>
                        </svg>
                    </span>
                </div>
                <p class="mt-4 text-3xl font-bold text-gray-900">{{ number_format($totalCount) }}</p>
                <p class="mt-1 text-sm text-gray-500">Số lượt phản ánh</p>
            </div>
        </div>

        <!-- Filter Header -->
        <div class="rounded-2xl border border-gray-100 bg-white p-6 shadow-sm flex flex-col lg:flex-row lg:items-center lg:justify-between gap-4">
            <div>
                <h2 class="text-lg font-bold text-gray-900">Danh sách báo cáo vi phạm</h2>
                <p class="text-sm text-gray-500 mt-1">Tìm kiếm, phân loại và duyệt/bác bỏ các báo cáo tài liệu vi phạm.</p>
            </div>
            
            <div class="flex flex-col sm:flex-row items-stretch sm:items-center gap-3 w-full lg:w-auto">
                <!-- Status Filter -->
                <select wire:model.live="statusFilter" aria-label="Lọc trạng thái" class="w-full sm:w-auto rounded-2xl border border-gray-200 bg-white px-4 py-2.5 text-sm text-gray-900 focus:border-gray-400 focus:outline-none">
                    <option value="all">Tất cả trạng thái</option>
                    <option value="pending">Chờ giải quyết</option>
                    <option value="resolved">Đã xử lý (Gỡ tài liệu)</option>
                    <option value="dismissed">Đã bác bỏ</option>
                </select>

                <!-- Reason Filter -->
                <select wire:model.live="reasonFilter" aria-label="Lọc lý do" class="w-full sm:w-auto rounded-2xl border border-gray-200 bg-white px-4 py-2.5 text-sm text-gray-900 focus:border-gray-400 focus:outline-none">
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
                           class="w-full rounded-2xl border border-gray-200 bg-white px-4 py-2.5 pl-10 text-sm text-gray-900 focus:border-gray-400 focus:outline-none" />
                    <span class="absolute inset-y-0 left-3.5 inline-flex items-center text-gray-400">
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
                <article class="group relative rounded-3xl border border-gray-200 bg-white p-4 sm:p-6 shadow-sm hover:shadow-md transition-all duration-300 flex flex-col justify-between space-y-4">
                    <div class="space-y-3">
                        <!-- Card Header -->
                        <div class="flex items-center justify-between border-b border-gray-100 pb-3">
                            <span class="text-[10px] text-gray-400 font-mono">Báo cáo #{{ $rep->id }}</span>
                            <span class="text-xs text-gray-500 font-medium">{{ $rep->created_at->diffForHumans() }}</span>
                        </div>

                        <!-- Reported Reason & Status Badges -->
                        <div class="flex flex-wrap items-center gap-2">
                            @if($rep->reason === 'Bản quyền')
                                <span class="inline-flex rounded-full bg-rose-50 px-2.5 py-1 text-xs font-semibold text-rose-700">
                                    Bản quyền / Sở hữu trí tuệ
                                </span>
                            @elseif($rep->reason === 'Spam')
                                <span class="inline-flex rounded-full bg-amber-50 px-2.5 py-1 text-xs font-semibold text-amber-700">
                                    Spam / Quảng cáo
                                </span>
                            @elseif($rep->reason === 'Nội dung sai')
                                <span class="inline-flex rounded-full bg-orange-50 px-2.5 py-1 text-xs font-semibold text-orange-700">
                                    Nội dung sai lệch
                                </span>
                            @elseif($rep->reason === 'File hỏng')
                                <span class="inline-flex rounded-full bg-red-50 px-2.5 py-1 text-xs font-semibold text-red-700">
                                    Tệp tin lỗi / Mã độc
                                </span>
                            @else
                                <span class="inline-flex rounded-full bg-gray-50 px-2.5 py-1 text-xs font-semibold text-gray-700">
                                    {{ $rep->reason }}
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
                                <span class="inline-flex rounded-full bg-gray-100 px-2.5 py-1 text-xs font-bold text-gray-600">
                                    Đã bác bỏ
                                </span>
                            @endif
                        </div>

                        <!-- Reported Document -->
                        <div class="space-y-1">
                            <h3 class="text-xs font-semibold text-gray-400 uppercase tracking-wider">Tài liệu bị báo cáo:</h3>
                            @if($rep->document)
                                <a href="{{ route('admin.moderation.documents.show', $rep->document->id) }}" class="text-sm sm:text-base font-bold text-gray-900 hover:text-blue-600 line-clamp-2 leading-snug">
                                    {{ $rep->document->title }}
                                </a>
                                <p class="text-[11px] text-gray-400">Tác giả: <span class="font-semibold text-gray-500">{{ $rep->document->author?->name ?? 'Không rõ' }}</span></p>
                            @else
                                <p class="text-sm font-semibold text-rose-600 italic">Tài liệu đã bị xóa khỏi hệ thống</p>
                            @endif
                        </div>

                        <!-- Report Description -->
                        <div class="space-y-1">
                            <h3 class="text-xs font-semibold text-gray-400 uppercase tracking-wider">Nội dung phản ánh:</h3>
                            <div class="rounded-2xl bg-gray-50 border border-gray-100 p-3 sm:p-3.5">
                                <p class="text-xs sm:text-sm text-gray-700 leading-relaxed italic">
                                    "{{ !empty($rep->details) ? $rep->details : 'Không có mô tả chi tiết.' }}"
                                </p>
                            </div>
                        </div>

                        <!-- Reporter & Resolver info -->
                        <div class="grid grid-cols-2 gap-4 text-xs border-t border-gray-100 pt-3">
                            <div>
                                <span class="text-gray-400 font-semibold block">Người báo cáo:</span>
                                <span class="font-bold text-gray-800 block truncate mt-0.5" title="{{ $rep->user?->name ?? 'Học viên ẩn danh' }}">
                                    {{ $rep->user?->name ?? 'Học viên ẩn danh' }}
                                </span>
                            </div>
                            @if($rep->status !== 'pending' && $rep->resolver)
                                <div>
                                    <span class="text-gray-400 font-semibold block">Người xử lý:</span>
                                    <span class="font-bold text-gray-800 block truncate mt-0.5">
                                        {{ $rep->resolver->name }}
                                    </span>
                                </div>
                            @endif
                        </div>

                        <!-- Admin note -->
                        @if($rep->status !== 'pending' && $rep->review_note)
                            <div class="border-t border-gray-100 pt-3 text-xs space-y-1">
                                <span class="text-gray-400 font-semibold block">Ghi chú của Admin:</span>
                                <div class="rounded-xl bg-gray-50 border border-gray-100 p-2.5 text-gray-600 italic">
                                    "{{ $rep->review_note }}"
                                </div>
                            </div>
                        @endif
                    </div>

                    <div class="border-t border-gray-100 pt-4 flex items-center justify-end gap-2">
                        @if($rep->status === 'pending')
                            <button wire:click="openDismissModal({{ $rep->id }})" 
                                    class="inline-flex items-center justify-center rounded-xl border border-gray-200 hover:bg-gray-50 px-3 py-2 text-xs font-bold text-gray-700 transition-colors shadow-sm flex-1">
                                Bác bỏ
                            </button>
                            <button wire:click="openResolveModal({{ $rep->id }})" 
                                    class="inline-flex items-center justify-center rounded-xl bg-rose-600 hover:bg-rose-500 text-white px-3 py-2 text-xs font-bold transition-all shadow-sm shadow-rose-600/10 flex-1">
                                Duyệt & Gỡ
                            </button>
                        @else
                            <span class="text-xs text-gray-400 italic font-medium">Đã đóng báo cáo vào {{ $rep->resolved_at ? $rep->resolved_at->format('d/m/Y') : '' }}</span>
                        @endif
                    </div>
                </article>
            @empty
                <div class="col-span-full py-16 text-center text-gray-400 bg-white rounded-3xl border border-gray-200">
                    <svg class="w-14 h-14 text-gray-300 mx-auto mb-3" fill="none" stroke="currentColor" viewBox="0 0 24 24"><path stroke-linecap="round" stroke-linejoin="round" stroke-width="1.5" d="M9 12l2 2 4-4m6 2a9 9 0 11-18 0 9 9 0 0118 0z"/></svg>
                    <p class="text-base font-semibold text-gray-500">Hàng đợi trống. Không có báo cáo vi phạm nào.</p>
                </div>
            @endforelse
        </div>

        <!-- Pagination -->
        @if($reports && $reports->hasPages())
            <div class="p-4 border-t border-gray-200 bg-white rounded-3xl">
                {{ $reports->links(data: ['scrollTo' => '#admin-document-report']) }}
            </div>
        @endif
    @endif

    <!-- Resolve Note Modal -->
    <div x-show="showReportResolveModal" 
         class="fixed inset-0 z-50 overflow-y-auto flex items-center justify-center p-4 bg-gray-900/60 backdrop-blur-sm" 
         style="display: none;"
         x-transition>
        <div class="bg-white rounded-3xl max-w-lg w-full border border-gray-200 shadow-2xl p-6 space-y-6" @click.away="showReportResolveModal = false">
            <div class="flex items-center justify-between border-b border-gray-100 pb-3">
                <h3 class="text-lg font-bold text-gray-900">Duyệt & Gỡ tài liệu</h3>
                <button @click="showReportResolveModal = false" class="text-gray-400 hover:text-gray-600 text-xl font-bold">&times;</button>
            </div>
            
            <div class="space-y-2">
                <label for="unpublish-reason" class="mb-2 block text-sm font-medium text-gray-700">
                    Lý do gỡ tài liệu <span class="text-red-500">*</span>:
                </label>
                <textarea id="unpublish-reason"
                          wire:model="unpublishReason" 
                          rows="4" 
                          placeholder="Ví dụ: Tài liệu vi phạm bản quyền theo phản ánh..."
                          class="w-full rounded-2xl border border-gray-200 bg-gray-50 px-4 py-3 text-sm text-gray-900 focus:border-gray-400 focus:outline-none"></textarea>
                @error('unpublishReason')
                    <span class="text-xs text-red-500 font-medium">{{ $message }}</span>
                @enderror
            </div>

            <div class="flex justify-end gap-3 pt-3 border-t border-gray-100">
                <button type="button" @click="showReportResolveModal = false" class="rounded-xl border border-gray-200 hover:bg-gray-50 px-4 py-2.5 text-xs font-semibold text-gray-700 transition-colors">
                    Hủy bỏ
                </button>
                <button type="button" wire:click="confirmResolve" 
                        class="rounded-xl bg-rose-600 hover:bg-rose-500 text-white px-5 py-2.5 text-xs font-semibold shadow-sm transition-colors">
                    Xác nhận gỡ tài liệu
                </button>
            </div>
        </div>
    </div>

    <!-- Dismiss Note Modal -->
    <div x-show="showReportDismissModal" 
         class="fixed inset-0 z-50 overflow-y-auto flex items-center justify-center p-4 bg-gray-900/60 backdrop-blur-sm" 
         style="display: none;"
         x-transition>
        <div class="bg-white rounded-3xl max-w-md w-full border border-gray-200 shadow-2xl p-6 space-y-6" @click.away="showReportDismissModal = false">
            <div class="flex items-center justify-between border-b border-gray-100 pb-3">
                <h3 class="text-lg font-bold text-gray-900">Bác bỏ báo cáo</h3>
                <button @click="showReportDismissModal = false" class="text-gray-400 hover:text-gray-600 text-xl font-bold">&times;</button>
            </div>
            
            <div class="space-y-2 text-sm text-gray-600">
                <p>Bạn có chắc chắn muốn bác bỏ báo cáo vi phạm này không?</p>
                <p>Báo cáo này sẽ được đánh dấu là không hợp lệ và tài liệu vẫn giữ nguyên trạng thái hiển thị.</p>
            </div>

            <div class="flex justify-end gap-3 pt-3 border-t border-gray-100">
                <button type="button" @click="showReportDismissModal = false" class="rounded-xl border border-gray-200 hover:bg-gray-50 px-4 py-2.5 text-xs font-semibold text-gray-700 transition-colors">
                    Hủy bỏ
                </button>
                <button type="button" wire:click="confirmDismiss" 
                        class="rounded-xl bg-gray-700 hover:bg-gray-600 text-white px-5 py-2.5 text-xs font-semibold shadow-sm transition-colors">
                    Xác nhận bác bỏ
                </button>
            </div>
        </div>
    </div>
</div>

