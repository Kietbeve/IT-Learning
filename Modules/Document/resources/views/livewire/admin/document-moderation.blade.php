<div x-data="{ notification: null, showRejectModal: false }" 
     @notify.window="notification = $event.detail; setTimeout(() => notification = null, 3000)"
     @open-modal.window="let d = $event.detail; if (d === 'reject-modal' || d?.[0] === 'reject-modal' || d?.id === 'reject-modal') showRejectModal = true"
     @close-modal.window="let d = $event.detail; if (d === 'reject-modal' || d?.[0] === 'reject-modal' || d?.id === 'reject-modal') showRejectModal = false"
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
            <div>
                <p class="text-sm font-semibold text-slate-900" x-text="notification ? notification.message : ''"></p>
            </div>
        </div>
    </div>

    <!-- Statistics Cards -->
    <div class="grid gap-6 xl:grid-cols-3 lg:grid-cols-2">
        <article class="rounded-3xl border border-slate-200 bg-white p-6 shadow-sm">
            <p class="text-sm uppercase tracking-[0.24em] text-slate-500">Chờ kiểm duyệt</p>
            <p class="mt-4 text-3xl font-semibold text-slate-900">{{ number_format($pendingCount) }}</p>
            <p class="mt-2 text-sm text-slate-500">Tài liệu cần được xử lý</p>
        </article>

        <article class="rounded-3xl border border-slate-200 bg-white p-6 shadow-sm">
            <p class="text-sm uppercase tracking-[0.24em] text-slate-500">Đã duyệt hôm nay</p>
            <p class="mt-4 text-3xl font-semibold text-slate-900">{{ number_format($approvedTodayCount) }}</p>
            <p class="mt-2 text-sm text-slate-500">Tài liệu xuất bản thành công</p>
        </article>

        <article class="rounded-3xl border border-slate-200 bg-white p-6 shadow-sm">
            <p class="text-sm uppercase tracking-[0.24em] text-slate-500">Đã từ chối hôm nay</p>
            <p class="mt-4 text-3xl font-semibold text-slate-900">{{ number_format($rejectedTodayCount) }}</p>
            <p class="mt-2 text-sm text-slate-500">Tài liệu lỗi hoặc vi phạm</p>
        </article>
    </div>

    <!-- Main List Card -->
    <section class="rounded-3xl border border-slate-200 bg-white shadow-sm overflow-hidden">
        <!-- Filter Header -->
        <div class="p-6 border-b border-slate-200 flex flex-col sm:flex-row sm:items-center sm:justify-between gap-4 bg-slate-50/50">
            <div>
                <h2 class="text-lg font-semibold text-slate-900">Danh sách tài liệu chờ duyệt</h2>
                <p class="text-sm text-slate-500 mt-1">Học viên và CTV tải lên cần Admin kiểm duyệt tính hợp lệ.</p>
            </div>
            <div class="relative w-full sm:w-72">
                <input type="search" 
                       wire:model.live.debounce.300ms="search" 
                       placeholder="Tìm kiếm tài liệu, tác giả..." 
                       class="w-full rounded-2xl border border-slate-200 bg-white px-4 py-2.5 pl-10 text-sm text-slate-900 focus:border-slate-400 focus:outline-none" />
                <span class="absolute inset-y-0 left-3.5 inline-flex items-center text-slate-400">
                    <svg viewBox="0 0 24 24" class="h-4.5 w-4.5" fill="none" stroke="currentColor" stroke-width="2"><circle cx="11" cy="11" r="8" /><path d="M21 21l-4.35-4.35" /></svg>
                </span>
            </div>
        </div>

        <!-- Mobile Card View (hidden on md and up) -->
        <div class="block md:hidden divide-y divide-slate-100">
            @forelse($documents as $doc)
                <div class="p-4 space-y-3">
                    <div class="flex items-start justify-between gap-4">
                        <div class="space-y-1.5 flex-1 min-w-0">
                            <a href="{{ route('admin.moderation.documents.show', $doc->id) }}" class="hover:text-blue-600 font-bold text-slate-900 block leading-tight text-base truncate" title="{{ $doc->title }}">
                                {{ $doc->title }}
                            </a>
                            
                            <div class="flex flex-wrap items-center gap-2 text-[10px] text-slate-400 font-semibold">
                                <span class="inline-flex items-center rounded bg-slate-100 px-1.5 py-0.5 uppercase text-slate-700 font-bold">
                                    {{ $doc->file_type }}
                                </span>
                                <span>{{ number_format($doc->file_size / 1024 / 1024, 2) }} MB</span>
                                <span>•</span>
                                <span class="text-slate-500">Danh mục: {{ $doc->category?->name ?? 'Mặc định' }}</span>
                            </div>
                        </div>
                    </div>

                    <!-- Meta info (Author, Upload date) -->
                    <div class="flex flex-wrap items-center justify-between gap-2 text-[11px] text-slate-500 bg-slate-50 p-2.5 rounded-2xl">
                        <div class="flex items-center gap-1.5">
                            <div class="h-4.5 w-4.5 rounded-full bg-slate-900 text-white flex items-center justify-center text-[8px] font-bold">
                                {{ substr($doc->author?->name ?? 'A', 0, 1) }}
                            </div>
                            <span>Đăng bởi: <strong class="text-slate-700">{{ $doc->author?->name ?? 'Uploader' }}</strong></span>
                        </div>
                        <div>{{ $doc->created_at->diffForHumans() }}</div>
                    </div>

                    <!-- Actions -->
                    <div class="flex items-center justify-end gap-2 pt-1">
                        <a href="{{ route('admin.moderation.documents.show', $doc->id) }}" class="inline-flex rounded-xl bg-slate-100 hover:bg-slate-200 text-slate-700 px-3 py-1.5 text-xs font-bold transition-all shadow-sm">
                            Chi tiết
                        </a>
                        <button wire:click="approve({{ $doc->id }})" class="inline-flex rounded-xl bg-emerald-600 hover:bg-emerald-500 text-white px-3 py-1.5 text-xs font-bold shadow-sm shadow-emerald-600/10 transition-all">
                            Duyệt
                        </button>
                        <button wire:click="openRejectionModal({{ $doc->id }})" class="inline-flex rounded-xl bg-rose-600 hover:bg-rose-500 text-white px-3 py-1.5 text-xs font-bold shadow-sm shadow-rose-600/10 transition-all">
                            Từ chối
                        </button>
                    </div>
                </div>
            @empty
                <div class="p-8 text-center text-slate-400">
                    Hàng đợi hiện tại trống. Không có tài liệu nào cần kiểm duyệt.
                </div>
            @endforelse
        </div>

        <!-- Desktop Table View (hidden on mobile) -->
        <div class="hidden md:block overflow-x-auto">
            <table class="w-full text-left border-collapse min-w-full">
                <thead>
                    <tr class="border-b border-slate-200 text-xs font-semibold uppercase tracking-wider text-slate-500 bg-slate-50/70">
                        <th class="px-4 py-4 cursor-pointer hover:bg-slate-100 transition-colors" wire:click="sortBy('title')">
                            Tài liệu
                            @if($sortField === 'title')
                                <span class="ml-1 text-[10px]">{{ $sortDirection === 'asc' ? '▲' : '▼' }}</span>
                            @endif
                        </th>
                        <th class="px-4 py-4 hidden md:table-cell">Tác giả</th>
                        <th class="px-4 py-4 hidden sm:table-cell cursor-pointer hover:bg-slate-100 transition-colors" wire:click="sortBy('created_at')">
                            Ngày tải lên
                            @if($sortField === 'created_at')
                                <span class="ml-1 text-[10px]">{{ $sortDirection === 'asc' ? '▲' : '▼' }}</span>
                            @endif
                        </th>
                        <th class="px-4 py-4 text-right">Thao tác</th>
                    </tr>
                </thead>
                <tbody class="divide-y divide-slate-100 text-sm text-slate-700">
                    @forelse($documents as $doc)
                        <tr class="hover:bg-slate-50/50 transition-colors">
                            <td class="px-4 py-4">
                                <div class="space-y-1.5 max-w-[280px] sm:max-w-xs md:max-w-md lg:max-w-lg">
                                    <a href="{{ route('admin.moderation.documents.show', $doc->id) }}" class="hover:text-blue-600 font-bold text-slate-900 block leading-tight truncate" title="{{ $doc->title }}">
                                        {{ $doc->title }}
                                    </a>
                                    
                                    <div class="flex flex-wrap items-center gap-2 text-[10px] text-slate-400 font-semibold">
                                        <span class="inline-flex items-center rounded bg-slate-100 px-1.5 py-0.5 uppercase text-slate-700 font-bold">
                                            {{ $doc->file_type }}
                                        </span>
                                        <span>{{ number_format($doc->file_size / 1024 / 1024, 2) }} MB</span>
                                        <span>•</span>
                                        <span class="text-slate-500">Danh mục: {{ $doc->category?->name ?? 'Mặc định' }}</span>
                                        <span class="sm:hidden">•</span>
                                        <span class="sm:hidden text-slate-500">{{ $doc->created_at->diffForHumans() }}</span>
                                    </div>

                                    <!-- Author info shown on mobile/tablet instead of dedicated column -->
                                    <div class="md:hidden text-[10px] text-slate-400">
                                        Đăng bởi: <span class="font-medium text-slate-600">{{ $doc->author?->name ?? 'Uploader' }}</span>
                                    </div>
                                </div>
                            </td>
                            <td class="px-4 py-4 hidden md:table-cell whitespace-nowrap">
                                <div class="flex items-center gap-2">
                                    <div class="h-6 w-6 rounded-full bg-slate-900 text-white flex items-center justify-center text-[10px] font-bold">
                                        {{ substr($doc->author?->name ?? 'A', 0, 1) }}
                                    </div>
                                    <span class="font-medium text-slate-900">{{ $doc->author?->name ?? 'Uploader' }}</span>
                                </div>
                            </td>
                            <td class="px-4 py-4 hidden sm:table-cell text-slate-500 font-medium whitespace-nowrap">
                                {{ $doc->created_at->diffForHumans() }}
                                <span class="block text-[10px] text-slate-400 mt-0.5">{{ $doc->created_at->format('d/m/Y H:i') }}</span>
                            </td>
                            <td class="px-4 py-4 text-right whitespace-nowrap">
                                <div class="flex items-center justify-end gap-1.5">
                                    <a href="{{ route('admin.moderation.documents.show', $doc->id) }}" class="inline-flex rounded-xl bg-slate-100 hover:bg-slate-200 text-slate-700 px-3 py-2 text-xs font-bold transition-all shadow-sm">
                                        Chi tiết
                                    </a>
                                    <button wire:click="approve({{ $doc->id }})" class="inline-flex rounded-xl bg-emerald-600 hover:bg-emerald-500 text-white px-3 py-2 text-xs font-bold shadow-sm shadow-emerald-600/10 transition-all">
                                        Duyệt
                                    </button>
                                    <button wire:click="openRejectionModal({{ $doc->id }})" class="inline-flex rounded-xl bg-rose-600 hover:bg-rose-500 text-white px-3 py-2 text-xs font-bold shadow-sm shadow-rose-600/10 transition-all">
                                        Từ chối
                                    </button>
                                </div>
                            </td>
                        </tr>
                    @empty
                        <tr>
                            <td colspan="4" class="px-4 py-12 text-center text-slate-400">
                                <svg class="w-12 h-12 text-slate-300 mx-auto mb-3" fill="none" stroke="currentColor" viewBox="0 0 24 24"><path stroke-linecap="round" stroke-linejoin="round" stroke-width="1.5" d="M9 12l2 2 4-4M7.835 4.697a3.42 3.42 0 001.946-.806 3.42 3.42 0 014.438 0 3.42 3.42 0 001.946.806 3.42 3.42 0 013.138 3.138 3.42 3.42 0 00.806 1.946 3.42 3.42 0 010 4.438 3.42 3.42 0 00-.806 1.946 3.42 3.42 0 01-3.138 3.138 3.42 3.42 0 00-1.946.806 3.42 3.42 0 01-4.438 0 3.42 3.42 0 00-1.946-.806 3.42 3.42 0 01-3.138-3.138 3.42 3.42 0 00-.806-1.946 3.42 3.42 0 010-4.438 3.42 3.42 0 00.806-1.946 3.42 3.42 0 013.138-3.138z"/></svg>
                                Hàng đợi hiện tại trống. Không có tài liệu nào cần kiểm duyệt.
                            </td>
                        </tr>
                    @endforelse
                </tbody>
            </table>
        </div>

        <!-- Pagination -->
        @if($documents->hasPages())
            <div class="p-6 border-t border-slate-200 bg-slate-50/50">
                {{ $documents->links() }}
            </div>
        @endif
    </section>

    <!-- Rejection Modal -->
    <div x-show="showRejectModal" 
         class="fixed inset-0 z-50 overflow-y-auto flex items-center justify-center p-4 bg-slate-900/60 backdrop-blur-sm" 
         style="display: none;"
         x-transition>
        <div class="bg-white rounded-3xl max-w-lg w-full border border-slate-200 shadow-2xl p-6 space-y-6" @click.away="showRejectModal = false">
            <div class="flex items-center justify-between border-b border-slate-100 pb-3">
                <h3 class="text-lg font-bold text-slate-900">Từ chối phê duyệt tài liệu</h3>
                <button @click="showRejectModal = false" class="text-slate-400 hover:text-slate-600">&times;</button>
            </div>
            
            <div class="space-y-2">
                <label for="rejection-reason" class="block text-sm font-semibold text-slate-700">Lý do từ chối <span class="text-red-500">*</span> (tối thiểu 10 ký tự):</label>
                <textarea id="rejection-reason"
                          wire:model="rejectionReason" 
                          rows="4" 
                          placeholder="Ví dụ: Tài liệu tải lên bị lỗi font chữ, tài liệu có bản quyền, file bị hỏng không giải nén được..."
                          class="w-full rounded-2xl border border-slate-200 bg-slate-50 px-4 py-3 text-sm text-slate-900 focus:border-slate-400 focus:outline-none"></textarea>
                @error('rejectionReason')
                    <span class="text-xs text-red-500 font-medium">{{ $message }}</span>
                @enderror
            </div>

            <div class="flex justify-end gap-3 pt-3 border-t border-slate-100">
                <button type="button" @click="showRejectModal = false" class="rounded-xl border border-slate-200 hover:bg-slate-50 px-4 py-2.5 text-xs font-semibold text-slate-700 transition-colors">
                    Hủy bỏ
                </button>
                <button type="button" wire:click="confirmRejection" class="rounded-xl bg-rose-600 hover:bg-rose-500 text-white px-5 py-2.5 text-xs font-semibold shadow-sm transition-colors">
                    Xác nhận từ chối
                </button>
            </div>
        </div>
    </div>
</div>
