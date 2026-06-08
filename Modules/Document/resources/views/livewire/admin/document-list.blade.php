<div x-data="{ notification: null }" 
     @notify.window="notification = $event.detail; setTimeout(() => notification = null, 3000)"
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
    <div class="grid gap-4 md:gap-6 grid-cols-1 md:grid-cols-2 xl:grid-cols-4">
        <article class="rounded-3xl border border-slate-200 bg-white p-6 shadow-sm">
            <p class="text-sm uppercase tracking-[0.24em] text-slate-500">Tổng số tài liệu</p>
            <p class="mt-4 text-3xl font-semibold text-slate-900">{{ number_format($totalCount) }}</p>
            <p class="mt-2 text-sm text-slate-500">Tất cả tài liệu trong hệ thống</p>
        </article>

        <article class="rounded-3xl border border-slate-200 bg-white p-6 shadow-sm">
            <p class="text-sm uppercase tracking-[0.24em] text-slate-500">Đã phê duyệt</p>
            <p class="mt-4 text-3xl font-semibold text-slate-900">{{ number_format($approvedCount) }}</p>
            <p class="mt-2 text-sm text-slate-500">Tài liệu đang hiển thị công khai</p>
        </article>

        <article class="rounded-3xl border border-amber-200 bg-amber-50 p-6 shadow-sm">
            <p class="text-sm uppercase tracking-[0.24em] text-amber-600">Chờ duyệt</p>
            <p class="mt-4 text-3xl font-semibold text-amber-700">{{ number_format($pendingCount) }}</p>
            <p class="mt-2 text-sm text-amber-600">Tài liệu cần kiểm duyệt</p>
        </article>

        <article class="rounded-3xl border border-slate-200 bg-white p-6 shadow-sm">
            <p class="text-sm uppercase tracking-[0.24em] text-slate-500">Tổng lượt tải xuống</p>
            <p class="mt-4 text-3xl font-semibold text-slate-900">{{ number_format($totalDownloads) }}</p>
            <p class="mt-2 text-sm text-slate-500">Số lượt học viên tải tài nguyên</p>
        </article>
    </div>

    <!-- Main List Card -->
    <section class="rounded-3xl border border-slate-200 bg-white shadow-sm overflow-hidden">
        <!-- Filter Header -->
        <div class="p-6 border-b border-slate-200 flex flex-col md:flex-row md:items-center md:justify-between gap-4 bg-slate-50/50">
            <div>
                <h2 class="text-lg font-semibold text-slate-900">Quản lý toàn bộ tài liệu</h2>
                <p class="text-sm text-slate-500 mt-1">Tìm kiếm, lọc trạng thái, thay đổi chế độ hiển thị hoặc xóa tài liệu.</p>
            </div>
            
            <div class="flex flex-col sm:flex-row items-stretch sm:items-center gap-3 w-full md:w-auto">
                <!-- Status Filter -->
                <select wire:model.live="statusFilter" aria-label="Lọc theo trạng thái" class="w-full sm:w-auto rounded-2xl border border-slate-200 bg-white px-4 py-2.5 text-sm text-slate-900 focus:border-slate-400 focus:outline-none">
                    <option value="all">Tất cả trạng thái</option>
                    <option value="approved">Đã phê duyệt</option>
                    <option value="pending">Chờ phê duyệt</option>
                    <option value="rejected">Bị từ chối</option>
                    <option value="draft">Bản nháp</option>
                    <option value="deleted">Đã xóa mềm</option>
                </select>

                <!-- Category Filter -->
                <select wire:model.live="categoryFilter" aria-label="Lọc theo danh mục" class="w-full sm:w-auto rounded-2xl border border-slate-200 bg-white px-4 py-2.5 text-sm text-slate-900 focus:border-slate-400 focus:outline-none">
                    <option value="all">Tất cả danh mục</option>
                    @foreach($categories as $cat)
                        <option value="{{ $cat->id }}">{{ $cat->name }}</option>
                    @endforeach
                </select>

                <!-- Search Input -->
                <div class="relative flex-1 sm:w-64 sm:flex-initial">
                    <input type="search" 
                           id="search-admin-documents"
                           aria-label="Tìm kiếm tài liệu"
                           wire:model.live.debounce.300ms="search" 
                           placeholder="Tìm kiếm tài liệu..." 
                           class="w-full rounded-2xl border border-slate-200 bg-white px-4 py-2.5 pl-10 text-sm text-slate-900 focus:border-slate-400 focus:outline-none" />
                    <span class="absolute inset-y-0 left-3.5 inline-flex items-center text-slate-400">
                        <svg viewBox="0 0 24 24" class="h-4.5 w-4.5" fill="none" stroke="currentColor" stroke-width="2"><circle cx="11" cy="11" r="8" /><path d="M21 21l-4.35-4.35" /></svg>
                    </span>
                </div>
            </div>
        </div>

        <!-- Mobile Card View (hidden on md and up) -->
        <div class="block md:hidden divide-y divide-slate-100">
            @forelse($documents as $doc)
                <div class="p-4 space-y-3">
                    <div class="flex items-start justify-between gap-4">
                        <div class="space-y-1.5 flex-1 min-w-0">
                            <a href="{{ route('admin.moderation.documents.show', ['id' => $doc->id, 'from' => 'list']) }}" class="hover:text-blue-600 font-bold text-slate-900 block leading-tight text-base truncate" title="{{ $doc->title }}">
                                {{ $doc->title }}
                            </a>
                            
                            <div class="flex flex-wrap items-center gap-2 text-[10px] text-slate-400 font-semibold">
                                <span class="inline-flex items-center rounded bg-slate-100 px-1.5 py-0.5 uppercase text-slate-700 font-bold">
                                    {{ $doc->file_type }}
                                </span>
                                <span>{{ number_format($doc->file_size / 1024 / 1024, 2) }} MB</span>
                                <span>•</span>
                                <span class="text-slate-500">{{ $doc->category?->name ?? 'Mặc định' }}</span>
                            </div>

                            @if($doc->status === 'rejected' && $doc->rejected_reason)
                                <div class="mt-1.5 text-[10px] text-rose-600 bg-rose-50 border border-rose-100 rounded-xl px-2.5 py-1.5 flex items-start gap-1 whitespace-normal break-words">
                                    <svg class="w-3.5 h-3.5 shrink-0 mt-0.5" fill="none" stroke="currentColor" viewBox="0 0 24 24"><path stroke-linecap="round" stroke-linejoin="round" stroke-width="2" d="M12 9v2m0 4h.01m-6.938 4h13.856c1.54 0 2.502-1.667 1.732-3L13.732 4c-.77-1.333-2.694-1.333-3.464 0L3.34 16c-.77 1.333.192 3 1.732 3z"/></svg>
                                    <span>Lý do từ chối: <strong class="font-medium text-rose-700">{{ $doc->rejected_reason }}</strong></span>
                                </div>
                            @endif
                        </div>

                        <!-- Price tag -->
                        <div class="shrink-0 text-right">
                            @if($doc->product)
                                <span class="text-blue-600 font-bold text-sm">{{ number_format($doc->product->price) }}đ</span>
                            @else
                                <span class="text-emerald-600 font-semibold bg-emerald-50 px-2 py-0.5 rounded-xl text-xs">Miễn phí</span>
                            @endif
                        </div>
                    </div>

                    <!-- Meta info (Author, Stats) -->
                    <div class="flex flex-wrap items-center justify-between gap-2 text-[11px] text-slate-500 bg-slate-50 p-2.5 rounded-2xl">
                        <div>Đăng bởi: <span class="font-semibold text-slate-700">{{ $doc->author?->name ?? 'Uploader' }}</span></div>
                        <div class="flex items-center gap-2">
                            <span>Tải: <strong class="text-slate-700">{{ number_format($doc->download_count) }}</strong></span>
                            <span>|</span>
                            <span>Xem: <strong class="text-slate-700">{{ number_format($doc->view_count) }}</strong></span>
                        </div>
                    </div>

                    <!-- Actions & Status -->
                    <div class="flex items-center justify-between gap-4 pt-1">
                        <div>
                            @if($doc->trashed())
                                <span class="inline-flex rounded-full bg-slate-100 px-2.5 py-1 text-xs font-semibold text-slate-600">
                                    Đã xóa mềm
                                </span>
                            @elseif($doc->status === 'approved')
                                <span class="inline-flex rounded-full bg-emerald-100 px-2.5 py-1 text-xs font-semibold text-emerald-800">
                                    Đã duyệt
                                </span>
                            @elseif($doc->status === 'pending')
                                <span class="inline-flex rounded-full bg-yellow-100 px-2.5 py-1 text-xs font-semibold text-yellow-800">
                                    Chờ duyệt
                                </span>
                            @elseif($doc->status === 'rejected')
                                <span class="inline-flex rounded-full bg-rose-100 px-2.5 py-1 text-xs font-semibold text-rose-800" title="Lý do: {{ $doc->rejected_reason }}">
                                    Bị từ chối
                                </span>
                            @else
                                <span class="inline-flex rounded-full bg-slate-100 px-2.5 py-1 text-xs font-semibold text-slate-800">
                                    Bản nháp
                                </span>
                            @endif
                        </div>

                        <div class="flex items-center gap-2">
                            <button wire:click="toggleVisibility({{ $doc->id }})" 
                                    @if($doc->trashed()) disabled @endif
                                    class="inline-flex items-center gap-1 px-3 py-1.5 rounded-xl text-xs font-bold uppercase tracking-wider transition-colors {{ $doc->visibility === 'public' ? 'bg-blue-50 text-blue-600 hover:bg-blue-100' : 'bg-slate-50 text-slate-400 hover:bg-slate-100' }}">
                                {{ $doc->visibility === 'public' ? 'Công khai' : 'Riêng tư' }}
                            </button>
                            @if(!$doc->trashed())
                                <button onclick="confirm('Bạn có chắc chắn muốn xóa mềm tài liệu này không?') || event.stopImmediatePropagation()" 
                                        wire:click="deleteDocument({{ $doc->id }})" 
                                        class="inline-flex rounded-xl bg-rose-50 hover:bg-rose-100 text-rose-600 px-3 py-1.5 text-xs font-bold transition-all shadow-sm">
                                    Xóa
                                </button>
                            @endif
                        </div>
                    </div>
                </div>
            @empty
                <div class="p-8 text-center text-slate-400">
                    Không tìm thấy tài liệu nào khớp với bộ lọc.
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
                        <th class="px-4 py-4">Hình thức / Giá</th>
                        <th class="px-4 py-4">Trạng thái</th>
                        <th class="px-4 py-4 hidden sm:table-cell">Hiển thị</th>
                        <th class="px-4 py-4 hidden lg:table-cell cursor-pointer hover:bg-slate-100 transition-colors" wire:click="sortBy('created_at')">
                            Ngày tạo
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
                                    <a href="{{ route('admin.moderation.documents.show', ['id' => $doc->id, 'from' => 'list']) }}" class="hover:text-blue-600 font-bold text-slate-900 block leading-tight truncate" title="{{ $doc->title }}">
                                        {{ $doc->title }}
                                    </a>
                                    
                                    <div class="flex flex-wrap items-center gap-2 text-[10px] text-slate-400 font-semibold">
                                        <span class="inline-flex items-center rounded bg-slate-100 px-1.5 py-0.5 uppercase text-slate-700 font-bold">
                                            {{ $doc->file_type }}
                                        </span>
                                        <span>{{ number_format($doc->file_size / 1024 / 1024, 2) }} MB</span>
                                        <span>•</span>
                                        <span class="text-slate-500">Danh mục: {{ $doc->category?->name ?? 'Mặc định' }}</span>
                                        <span>•</span>
                                        <span class="flex items-center gap-0.5 text-slate-500">
                                            Tải: {{ number_format($doc->download_count) }} | Xem: {{ number_format($doc->view_count) }}
                                        </span>
                                    </div>

                                    @if($doc->status === 'rejected' && $doc->rejected_reason)
                                        <div class="mt-1.5 text-[11px] text-rose-600 bg-rose-50 border border-rose-100 rounded-xl px-2.5 py-1.5 flex items-start gap-1 max-w-md whitespace-normal break-words">
                                            <svg class="w-3.5 h-3.5 shrink-0 mt-0.5" fill="none" stroke="currentColor" viewBox="0 0 24 24"><path stroke-linecap="round" stroke-linejoin="round" stroke-width="2" d="M12 9v2m0 4h.01m-6.938 4h13.856c1.54 0 2.502-1.667 1.732-3L13.732 4c-.77-1.333-2.694-1.333-3.464 0L3.34 16c-.77 1.333.192 3 1.732 3z"/></svg>
                                            <span>Lý do từ chối: <strong class="font-medium text-rose-700">{{ $doc->rejected_reason }}</strong></span>
                                        </div>
                                    @endif

                                    <!-- Author info shown on mobile/tablet instead of dedicated column -->
                                    <div class="md:hidden text-[10px] text-slate-400">
                                        Đăng bởi: <span class="font-medium text-slate-600">{{ $doc->author?->name ?? 'Uploader' }}</span>
                                    </div>
                                </div>
                            </td>
                            <td class="px-4 py-4 hidden md:table-cell whitespace-nowrap">
                                <div class="font-medium text-slate-900 truncate max-w-[150px]" title="{{ $doc->author?->name ?? 'Uploader' }}">{{ $doc->author?->name ?? 'Uploader' }}</div>
                                <div class="text-[10px] text-slate-400 mt-0.5 truncate max-w-[150px]" title="{{ $doc->author?->email ?? '' }}">{{ $doc->author?->email ?? '' }}</div>
                            </td>
                            <td class="px-4 py-4 whitespace-nowrap">
                                @if($doc->product)
                                    <span class="text-blue-600 font-bold">{{ number_format($doc->product->price) }}đ</span>
                                @else
                                    <span class="text-emerald-600 font-semibold bg-emerald-50 px-2 py-1 rounded-xl text-xs">Miễn phí</span>
                                @endif
                            </td>
                            <td class="px-4 py-4 whitespace-nowrap">
                                @if($doc->trashed())
                                    <span class="inline-flex rounded-full bg-slate-100 px-2.5 py-1 text-xs font-semibold text-slate-600">
                                        Đã xóa mềm
                                    </span>
                                @elseif($doc->status === 'approved')
                                    <span class="inline-flex rounded-full bg-emerald-100 px-2.5 py-1 text-xs font-semibold text-emerald-800">
                                        Đã duyệt
                                    </span>
                                @elseif($doc->status === 'pending')
                                    <span class="inline-flex rounded-full bg-yellow-100 px-2.5 py-1 text-xs font-semibold text-yellow-800">
                                        Chờ duyệt
                                    </span>
                                @elseif($doc->status === 'rejected')
                                    <span class="inline-flex rounded-full bg-rose-100 px-2.5 py-1 text-xs font-semibold text-rose-800" title="Lý do: {{ $doc->rejected_reason }}">
                                        Bị từ chối
                                    </span>
                                @else
                                    <span class="inline-flex rounded-full bg-slate-100 px-2.5 py-1 text-xs font-semibold text-slate-800">
                                        Bản nháp
                                    </span>
                                @endif
                            </td>
                            <td class="px-4 py-4 hidden sm:table-cell whitespace-nowrap">
                                <button wire:click="toggleVisibility({{ $doc->id }})" 
                                        @if($doc->trashed()) disabled @endif
                                        class="inline-flex items-center gap-1.5 px-3 py-1.5 rounded-xl text-xs font-bold uppercase tracking-wider transition-colors {{ $doc->visibility === 'public' ? 'bg-blue-50 text-blue-600 hover:bg-blue-100' : 'bg-slate-50 text-slate-400 hover:bg-slate-100' }}">
                                    <span class="h-1.5 w-1.5 rounded-full {{ $doc->visibility === 'public' ? 'bg-blue-600' : 'bg-slate-400' }}"></span>
                                    {{ $doc->visibility === 'public' ? 'Công khai' : 'Riêng tư' }}
                                </button>
                            </td>
                            <td class="px-4 py-4 hidden lg:table-cell text-slate-500 font-medium whitespace-nowrap">
                                {{ $doc->created_at->format('d/m/Y') }}
                                <span class="block text-[10px] text-slate-400 mt-0.5">{{ $doc->created_at->format('H:i') }}</span>
                            </td>
                            <td class="px-4 py-4 text-right whitespace-nowrap">
                                <div class="flex items-center justify-end gap-2">
                                    <!-- Visibility toggle on smallest screens where the column is hidden -->
                                    <button wire:click="toggleVisibility({{ $doc->id }})" 
                                            @if($doc->trashed()) disabled @endif
                                            class="sm:hidden inline-flex rounded-xl bg-slate-100 hover:bg-slate-200 text-slate-700 px-2.5 py-2 text-xs font-semibold">
                                        {{ $doc->visibility === 'public' ? 'Ẩn' : 'Hiện' }}
                                    </button>

                                    @if(!$doc->trashed())
                                        <button onclick="confirm('Bạn có chắc chắn muốn xóa mềm tài liệu này không?') || event.stopImmediatePropagation()" 
                                                wire:click="deleteDocument({{ $doc->id }})" 
                                                class="inline-flex rounded-xl bg-rose-50 hover:bg-rose-100 text-rose-600 px-3.5 py-2 text-xs font-bold transition-all shadow-sm">
                                            Xóa
                                        </button>
                                    @endif
                                </div>
                            </td>
                        </tr>
                    @empty
                        <tr>
                            <td colspan="7" class="px-4 py-12 text-center text-slate-400">
                                <svg class="w-12 h-12 text-slate-300 mx-auto mb-3" fill="none" stroke="currentColor" viewBox="0 0 24 24"><path stroke-linecap="round" stroke-linejoin="round" stroke-width="1.5" d="M20 13V6a2 2 0 00-2-2H6a2 2 0 00-2 2v7m16 0v5a2 2 0 01-2 2H6a2 2 0 01-2-2v-5m16 0h-2.586a1 1 0 00-.707.293l-2.414 2.414a1 1 0 01-.707.293h-3.172a1 1 0 01-.707-.293l-2.414-2.414A1 1 0 006.586 13H4"/></svg>
                                Không tìm thấy tài liệu nào khớp với bộ lọc.
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
</div>
