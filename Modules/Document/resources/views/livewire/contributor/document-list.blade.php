<div id="contributor-document-list" wire:poll.10s.keep-alive
     x-data="{ notification: null }" 
     x-on:notify.window="notification = $event.detail; setTimeout(() => notification = null, 3000)"
     class="space-y-6">

    <style>
        #contributor-document-list, 
        #contributor-document-list.wire-loading,
        #contributor-document-list * { 
            transition: none !important; 
            animation: none !important;
            opacity: 1 !important;
            transform: none !important;
            visibility: visible !important;
        }
        [wire\:loading], [wire\:loading] * {
            opacity: 1 !important;
        }
    </style>

    {{-- Statistics Cards --}}
    <div class="grid gap-4 sm:gap-6 grid-cols-2 lg:grid-cols-4">
        <div class="rounded-2xl bg-white p-6 shadow-sm ring-1 ring-gray-200 transition hover:shadow-md">
            <div class="flex items-center justify-between">
                <p class="text-sm font-medium text-gray-500">Đã duyệt</p>
                <span class="rounded-xl bg-emerald-50 p-2.5 text-emerald-600">
                    <svg class="h-5 w-5" fill="none" stroke="currentColor" viewBox="0 0 24 24"><path stroke-linecap="round" stroke-linejoin="round" stroke-width="2" d="M9 12l2 2 4-4m6 2a9 9 0 11-18 0 9 9 0 0118 0z"/></svg>
                </span>
            </div>
            <p class="mt-4 text-3xl font-bold text-gray-900">{{ number_format($approvedCount) }}</p>
            <p class="mt-1 text-xs text-emerald-600 font-medium">Xuất bản công khai</p>
        </div>

        <div class="rounded-2xl bg-white p-6 shadow-sm ring-1 ring-gray-200 transition hover:shadow-md">
            <div class="flex items-center justify-between">
                <p class="text-sm font-medium text-gray-500">Chờ duyệt</p>
                <span class="rounded-xl bg-amber-50 p-2.5 text-amber-600">
                    <svg class="h-5 w-5" fill="none" stroke="currentColor" viewBox="0 0 24 24"><path stroke-linecap="round" stroke-linejoin="round" stroke-width="2" d="M12 8v4l3 3m6-3a9 9 0 11-18 0 9 9 0 0118 0z"/></svg>
                </span>
            </div>
            <p class="mt-4 text-3xl font-bold text-gray-900">{{ number_format($pendingCount) }}</p>
            <p class="mt-1 text-xs text-amber-600 font-medium animate-pulse">Đang kiểm duyệt</p>
        </div>

        <div class="rounded-2xl bg-white p-6 shadow-sm ring-1 ring-gray-200 transition hover:shadow-md">
            <div class="flex items-center justify-between">
                <p class="text-sm font-medium text-gray-500">Lượt tải</p>
                <span class="rounded-xl bg-blue-50 p-2.5 text-blue-600">
                    <svg class="h-5 w-5" fill="none" stroke="currentColor" viewBox="0 0 24 24"><path stroke-linecap="round" stroke-linejoin="round" stroke-width="2" d="M4 16v1a3 3 0 003 3h10a3 3 0 003-3v-1m-4-4l-4 4m0 0l-4-4m4 4V4"/></svg>
                </span>
            </div>
            <p class="mt-4 text-3xl font-bold text-gray-900">{{ number_format($totalDownloads) }}</p>
            <p class="mt-1 text-xs text-gray-500">Tổng lượt tải xuống</p>
        </div>

        <div class="rounded-2xl bg-white p-6 shadow-sm ring-1 ring-gray-200 transition hover:shadow-md">
            <div class="flex items-center justify-between">
                <p class="text-sm font-medium text-gray-500">Lượt xem</p>
                <span class="rounded-xl bg-purple-50 p-2.5 text-purple-600">
                    <svg class="h-5 w-5" fill="none" stroke="currentColor" viewBox="0 0 24 24"><path stroke-linecap="round" stroke-linejoin="round" stroke-width="2" d="M15 12a3 3 0 11-6 0 3 3 0 016 0z"/><path stroke-linecap="round" stroke-linejoin="round" stroke-width="2" d="M2.458 12C3.732 7.943 7.523 5 12 5c4.478 0 8.268 2.943 9.542 7-1.274 4.057-5.064 7-9.542 7-4.477 0-8.268-2.943-9.542-7z"/></svg>
                </span>
            </div>
            <p class="mt-4 text-3xl font-bold text-gray-900">{{ number_format($totalViews) }}</p>
            <p class="mt-1 text-xs text-gray-500">Xem thử tài nguyên</p>
        </div>
    </div>

    <!-- Main List Card -->
    <div class="overflow-hidden rounded-2xl bg-white shadow ring-1 ring-gray-200">
        <!-- Filter Header -->
        <div class="p-6 border-b border-gray-200 flex flex-col lg:flex-row lg:items-center lg:justify-between gap-4">
            <div>
                <h2 class="text-lg font-bold text-gray-900">Quản Lý Tài Liệu</h2>
                <p class="text-sm text-gray-500 mt-1">Tìm kiếm, cập nhật trạng thái hiển thị, chỉnh sửa thông tin tài nguyên.</p>
            </div>
            
            <div class="flex flex-col sm:flex-row items-stretch sm:items-center gap-3 w-full lg:w-auto">
                @if(\App\Services\SettingService::get('allow_contributor_upload', '1') === '1')
                    <a href="{{ route('contributor.documents.create') }}" class="inline-flex items-center justify-center gap-2 rounded-xl bg-blue-600 px-4 py-2.5 text-sm font-semibold text-white shadow-sm hover:bg-blue-500 focus-visible:outline focus-visible:outline-2 focus-visible:outline-offset-2 focus-visible:outline-blue-600 transition-all shrink-0">
                        <svg class="h-5 w-5" fill="none" stroke="currentColor" viewBox="0 0 24 24"><path stroke-linecap="round" stroke-linejoin="round" stroke-width="2" d="M12 4v16m8-8H4"/></svg>
                        Đăng tài liệu
                    </a>
                @else
                    <div class="inline-flex items-center justify-center gap-2 rounded-xl bg-red-50 px-4 py-2.5 text-sm font-semibold text-red-700 border border-red-200 shrink-0">
                        <svg class="h-5 w-5" fill="none" stroke="currentColor" viewBox="0 0 24 24"><path stroke-linecap="round" stroke-linejoin="round" stroke-width="2" d="M18.364 18.364A9 9 0 005.636 5.636m12.728 12.728A9 9 0 015.636 5.636m12.728 12.728L5.636 5.636"/></svg>
                        Tạm khóa đăng bài
                    </div>
                @endif

                <!-- Status Filter -->
                <div class="relative w-full sm:w-auto min-w-[160px]" x-data="{ open: false }" @click.away="open = false">
                    <div @click="open = !open" class="flex items-center justify-between w-full rounded-xl border border-gray-200 bg-gray-50 px-4 py-2.5 text-sm text-gray-900 cursor-pointer hover:border-indigo-400 transition-colors select-none shadow-sm h-[42px]">
                        <span class="font-medium text-gray-700 whitespace-nowrap">
                            @if($statusFilter === 'all') Tất cả trạng thái
                            @elseif($statusFilter === 'approved') Đã phê duyệt
                            @elseif($statusFilter === 'pending') Chờ phê duyệt
                            @elseif($statusFilter === 'rejected') Bị từ chối
                            @elseif($statusFilter === 'unpublished') Đã gỡ/Bị ẩn
                            @elseif($statusFilter === 'deleted') Đã xóa
                            @endif
                        </span>
                        <svg class="w-4 h-4 text-gray-400 transition-transform duration-200 shrink-0 ml-2" :class="{'rotate-180': open}" fill="none" stroke="currentColor" viewBox="0 0 24 24"><path stroke-linecap="round" stroke-linejoin="round" stroke-width="2" d="M19 9l-7 7-7-7"/></svg>
                    </div>
                    <div x-show="open" x-cloak x-transition.opacity.duration.200ms style="display: none;"
                         class="absolute top-full left-0 sm:right-0 sm:left-auto mt-2 w-full sm:w-48 bg-white border border-slate-100 rounded-xl shadow-lg py-1.5 z-[60]">
                        <div wire:click="$set('statusFilter', 'all')" @click="open = false" 
                             class="px-4 py-2.5 text-sm cursor-pointer transition-colors {{ $statusFilter === 'all' ? 'bg-blue-50/60 text-blue-600 font-semibold' : 'text-gray-700 hover:bg-gray-50' }}">Tất cả trạng thái</div>
                        <div wire:click="$set('statusFilter', 'approved')" @click="open = false" 
                             class="px-4 py-2.5 text-sm cursor-pointer transition-colors {{ $statusFilter === 'approved' ? 'bg-blue-50/60 text-blue-600 font-semibold' : 'text-gray-700 hover:bg-gray-50' }}">Đã phê duyệt</div>
                        <div wire:click="$set('statusFilter', 'pending')" @click="open = false" 
                             class="px-4 py-2.5 text-sm cursor-pointer transition-colors {{ $statusFilter === 'pending' ? 'bg-blue-50/60 text-blue-600 font-semibold' : 'text-gray-700 hover:bg-gray-50' }}">Chờ phê duyệt</div>
                        <div wire:click="$set('statusFilter', 'rejected')" @click="open = false" 
                             class="px-4 py-2.5 text-sm cursor-pointer transition-colors {{ $statusFilter === 'rejected' ? 'bg-blue-50/60 text-blue-600 font-semibold' : 'text-gray-700 hover:bg-gray-50' }}">Bị từ chối</div>
                        <div wire:click="$set('statusFilter', 'unpublished')" @click="open = false" 
                             class="px-4 py-2.5 text-sm cursor-pointer transition-colors {{ $statusFilter === 'unpublished' ? 'bg-blue-50/60 text-blue-600 font-semibold' : 'text-gray-700 hover:bg-gray-50' }}">Đã gỡ/Bị ẩn</div>
                        <div wire:click="$set('statusFilter', 'deleted')" @click="open = false" 
                             class="px-4 py-2.5 text-sm cursor-pointer transition-colors {{ $statusFilter === 'deleted' ? 'bg-blue-50/60 text-blue-600 font-semibold' : 'text-gray-700 hover:bg-gray-50' }}">Đã xóa</div>
                    </div>
                </div>

                <!-- Category Filter -->
                <div class="relative w-full sm:w-auto min-w-[160px]" x-data="{ open: false }" @click.away="open = false">
                    <div @click="open = !open" class="flex items-center justify-between w-full rounded-xl border border-gray-200 bg-gray-50 px-4 py-2.5 text-sm text-gray-900 cursor-pointer hover:border-indigo-400 transition-colors select-none shadow-sm h-[42px]">
                        <span class="font-medium text-gray-700 overflow-hidden text-ellipsis whitespace-nowrap max-w-[120px]">
                            @if($categoryFilter === 'all') Tất cả danh mục
                            @else
                                {{ collect($categories)->firstWhere('id', $categoryFilter)->name ?? 'Tất cả danh mục' }}
                            @endif
                        </span>
                        <svg class="w-4 h-4 text-gray-400 shrink-0 ml-2 transition-transform duration-200" :class="{'rotate-180': open}" fill="none" stroke="currentColor" viewBox="0 0 24 24"><path stroke-linecap="round" stroke-linejoin="round" stroke-width="2" d="M19 9l-7 7-7-7"/></svg>
                    </div>
                    <div x-show="open" x-cloak x-transition.opacity.duration.200ms style="display: none;"
                         class="absolute top-full left-0 sm:right-0 sm:left-auto mt-2 w-full sm:w-56 bg-white border border-slate-100 rounded-xl shadow-lg py-1.5 z-[60] max-h-64 overflow-y-auto">
                        <div wire:click="$set('categoryFilter', 'all')" @click="open = false" 
                             class="px-4 py-2.5 text-sm cursor-pointer transition-colors {{ $categoryFilter === 'all' ? 'bg-blue-50/60 text-blue-600 font-semibold' : 'text-gray-700 hover:bg-gray-50' }}">Tất cả danh mục</div>
                        @foreach($categories as $cat)
                            <div wire:click="$set('categoryFilter', '{{ $cat->id }}')" @click="open = false" 
                                 class="px-4 py-2.5 text-sm cursor-pointer transition-colors {{ $categoryFilter == $cat->id ? 'bg-blue-50/60 text-blue-600 font-semibold' : 'text-gray-700 hover:bg-gray-50' }}">{{ $cat->name }}</div>
                        @endforeach
                    </div>
                </div>

                <!-- Search Input -->
                <div class="relative w-full sm:w-60">
                    <div class="pointer-events-none absolute inset-y-0 left-0 flex items-center pl-3">
                        <svg class="h-5 w-5 text-gray-400" viewBox="0 0 20 20" fill="currentColor" aria-hidden="true">
                            <path fill-rule="evenodd" d="M9 3.5a5.5 5.5 0 100 11 5.5 5.5 0 000-11zM2 9a7 7 0 1112.452 4.391l3.328 3.329a.75.75 0 11-1.06 1.06l-3.329-3.328A7 7 0 012 9z" clip-rule="evenodd" />
                        </svg>
                    </div>
                    <input type="search" 
                           id="search-contributor-documents"
                           wire:model.live.debounce.300ms="search" 
                           placeholder="Tìm tài liệu..." 
                           class="block w-full rounded-xl border-0 py-2.5 pl-10 pr-10 text-gray-900 shadow-sm ring-1 ring-inset ring-gray-300 placeholder:text-gray-400 focus:ring-2 focus:ring-inset focus:ring-blue-600 sm:text-sm sm:leading-6 transition-all" />
                    
                    <div wire:loading.flex wire:target="search" class="absolute inset-y-0 right-3 items-center">
                        <svg class="h-4 w-4 animate-spin text-blue-600" xmlns="http://www.w3.org/2000/svg" fill="none" viewBox="0 0 24 24">
                            <circle class="opacity-25" cx="12" cy="12" r="10" stroke="currentColor" stroke-width="4"></circle>
                            <path class="opacity-75" fill="currentColor" d="M4 12a8 8 0 018-8V0C5.373 0 0 5.373 0 12h4z"></path>
                        </svg>
                    </div>
                </div>
            </div>
        </div>

        <!-- Table Content with Loading State -->
        <div wire:loading.class="opacity-50 pointer-events-none" class="transition-opacity duration-200">
            <!-- Mobile Card View -->
            <div class="block md:hidden divide-y divide-gray-100">
            @forelse($documents as $doc)
                <div class="p-5 space-y-4 hover:bg-gray-50/50 transition-colors">
                    <div class="flex items-start justify-between gap-4">
                        <div class="space-y-2 flex-1 min-w-0">
                            <a href="{{ route('documents.show', [$doc->id, Str::slug($doc->currentVersion?->title ?? $doc->title ?? 'tai-lieu')]) }}" target="_blank" class="text-sm font-semibold text-gray-900 hover:text-blue-600 block leading-tight truncate" title="{{ $doc->currentVersion?->title ?? $doc->title }}">
                                {{ \Illuminate\Support\Str::limit($doc->title, 50) }}
                            </a>
                            
                            <div class="flex flex-wrap items-center gap-2 mt-1">
                                <span class="inline-flex items-center rounded-md bg-gray-50 px-2 py-1 text-[10px] font-medium text-gray-600 ring-1 ring-inset ring-gray-500/10 uppercase">
                                    {{ $doc->file_type }}
                                </span>
                                <span class="text-xs text-gray-500">{{ number_format($doc->file_size / 1024 / 1024, 2) }} MB</span>
                                <span class="inline-flex items-center gap-1 rounded-md px-2 py-1 text-[10px] font-medium ring-1 ring-inset bg-blue-50 text-blue-700 ring-blue-600/20">
                                    {{ $doc->category?->name ?? 'Mặc định' }}
                                </span>
                                @if($doc->subject)
                                <span class="inline-flex items-center gap-1 rounded-md px-2 py-1 text-[10px] font-medium ring-1 ring-inset bg-indigo-50 text-indigo-700 ring-indigo-600/20">
                                    {{ $doc->subject->name }}
                                </span>
                                @endif
                            </div>

                            @if(!$doc->trashed() && (($doc->status === 'rejected' && $doc->rejected_reason) || ($doc->status === 'approved' && $doc->rejectedVersion)))
                                @php 
                                    $isEditRejection = $doc->status === 'approved' && $doc->rejectedVersion;
                                    $rejectedV = $doc->rejectedVersion;
                                    
                                    if ($isEditRejection) {
                                        $reason = $rejectedV->rejected_reason;
                                        $title = 'Cập nhật bị từ chối';
                                        $bgColor = 'bg-amber-50';
                                        $borderColor = 'border-amber-200';
                                        $textColor = 'text-amber-800';
                                        $iconColor = 'text-amber-500';
                                    } else {
                                        $reason = $doc->rejected_reason;
                                        $title = 'Bị từ chối';
                                        $bgColor = 'bg-rose-50';
                                        $borderColor = 'border-rose-200';
                                        $textColor = 'text-rose-800';
                                        $iconColor = 'text-rose-500';
                                    }
                                @endphp
                                <div class="mt-2 p-2.5 {{ $bgColor }} border {{ $borderColor }} rounded-xl" x-data="{ expanded: false }">
                                    <div class="flex items-start justify-between gap-2">
                                        <div class="flex items-start gap-2 flex-1 min-w-0">
                                            <svg class="w-4 h-4 {{ $iconColor }} shrink-0 mt-0.5" fill="none" stroke="currentColor" viewBox="0 0 24 24">
                                                <path stroke-linecap="round" stroke-linejoin="round" stroke-width="2" d="M12 9v2m0 4h.01m-6.938 4h13.856c1.54 0 2.502-1.667 1.732-3L13.732 4c-.77-1.333-2.694-1.333-3.464 0L3.34 16c-.77 1.333.192 3 1.732 3z"/>
                                            </svg>
                                            <div class="flex-1 text-xs min-w-0 leading-snug">
                                                <div :class="expanded ? '' : 'line-clamp-2'">
                                                    <span class="font-bold {{ $textColor }}">{{ $title }}:</span>
                                                    <span class="text-gray-700 ml-1">{{ $reason }}</span>
                                                </div>
                                            </div>
                                        </div>
                                        <button wire:click="dismissRejectedVersion({{ $doc->id }})" 
                                                class="shrink-0 text-gray-400 hover:text-gray-700 transition-colors">
                                            <svg class="w-4 h-4" fill="none" stroke="currentColor" viewBox="0 0 24 24">
                                                <path stroke-linecap="round" stroke-linejoin="round" stroke-width="2" d="M6 18L18 6M6 6l12 12"/>
                                            </svg>
                                        </button>
                                    </div>
                                </div>
                            @endif

                            @if($doc->status === 'approved' && $doc->pendingVersion)
                                <div class="mt-2 p-2.5 bg-blue-50 border border-blue-200 rounded-xl flex items-center justify-between">
                                    <div class="flex-1 flex items-start gap-2">
                                        <svg class="w-4 h-4 text-blue-500 shrink-0 mt-0.5" fill="none" stroke="currentColor" viewBox="0 0 24 24">
                                            <path stroke-linecap="round" stroke-linejoin="round" stroke-width="2" d="M13 16h-1v-4h-1m1-4h.01M21 12a9 9 0 11-18 0 9 9 0 0118 0z"/>
                                        </svg>
                                        <div class="flex-1 text-[11px] min-w-0 leading-snug">
                                            <span class="font-bold text-blue-800">Đang chờ duyệt bản cập nhật</span>
                                        </div>
                                    </div>
                                    <button wire:click.prevent="cancelUpdate({{ $doc->id }})" class="ml-2 px-2 py-1 text-[10px] font-semibold text-rose-600 bg-white border border-rose-200 rounded-md hover:bg-rose-50 transition-colors shrink-0" onclick="confirm('Bạn có chắc muốn hủy yêu cầu cập nhật này không?') || event.stopImmediatePropagation()">Hủy</button>
                                </div>
                            @endif

                        </div>

                        <!-- Price tag -->
                        <div class="shrink-0 text-right">
                            @if($doc->product && $doc->product->price > 0)
                                @if($doc->product->sale_price)
                                    <span class="block text-sm font-bold text-blue-600">{{ number_format($doc->product->sale_price) }}đ</span>
                                    <span class="block text-xs font-medium text-gray-400 line-through">{{ number_format($doc->product->price) }}đ</span>
                                @else
                                    <span class="block text-sm font-bold text-gray-900">{{ number_format($doc->product->price) }}đ</span>
                                @endif
                            @else
                                <span class="inline-flex items-center rounded-md bg-emerald-50 px-2 py-1 text-xs font-medium text-emerald-700 ring-1 ring-inset ring-emerald-600/20">Miễn phí</span>
                            @endif
                        </div>
                    </div>

                    <!-- Actions & Status -->
                    <div class="flex flex-col sm:flex-row items-start sm:items-center justify-between gap-3 pt-3 border-t border-gray-100">
                        <div class="flex flex-wrap items-center gap-2">
                            <span class="inline-flex items-center rounded-md px-2 py-1 text-[10px] font-semibold ring-1 ring-inset {{ $doc->visibility === 'public' ? 'bg-blue-50 text-blue-700 ring-blue-600/20' : 'bg-gray-50 text-gray-600 ring-gray-500/10' }}">
                                {{ $doc->visibility === 'public' ? 'Công khai' : 'Riêng tư' }}
                            </span>
                            @if($doc->trashed())
                                <span class="inline-flex items-center rounded-md bg-gray-50 px-2 py-1 text-[10px] font-medium text-gray-600 ring-1 ring-inset ring-gray-500/10">Đã xóa mềm</span>
                            @elseif($doc->status === 'approved')
                                <span class="inline-flex items-center rounded-md bg-emerald-50 px-2 py-1 text-[10px] font-medium text-emerald-700 ring-1 ring-inset ring-emerald-600/20">Đã duyệt</span>
                            @elseif($doc->status === 'pending')
                                <span class="inline-flex items-center rounded-md bg-amber-50 px-2 py-1 text-[10px] font-medium text-amber-800 ring-1 ring-inset ring-amber-600/20">Chờ duyệt</span>
                            @elseif($doc->status === 'rejected')
                                <span class="inline-flex items-center rounded-md bg-rose-50 px-2 py-1 text-[10px] font-medium text-rose-700 ring-1 ring-inset ring-rose-600/10" title="Lý do: {{ $doc->rejected_reason }}">Bị từ chối</span>
                            @elseif($doc->status === 'unpublished')
                                <span class="inline-flex items-center rounded-md bg-gray-50 px-2 py-1 text-[10px] font-medium text-gray-600 ring-1 ring-inset ring-gray-500/10">Đã gỡ</span>
                            @endif

                            @if($doc->status === 'approved' && $doc->pendingVersion)
                                <span class="inline-flex items-center rounded-md bg-blue-50 px-2 py-1 text-[10px] font-medium text-blue-700 ring-1 ring-inset ring-blue-600/20">Cập nhật chờ duyệt</span>
                            @endif
                            
                            @if($doc->status === 'approved' && $doc->rejectedVersion)
                                <span class="inline-flex items-center rounded-md bg-amber-50 px-2 py-1 text-[10px] font-medium text-amber-800 ring-1 ring-inset ring-amber-600/20">Cập nhật bị từ chối</span>
                            @endif
                        </div>

                        <div class="flex flex-wrap items-center gap-2 w-full sm:w-auto justify-end">
                            <button wire:click="showHistory({{ $doc->id }})" class="inline-flex items-center rounded-lg bg-white px-2.5 py-1.5 text-xs font-semibold text-gray-700 shadow-sm ring-1 ring-inset ring-gray-300 hover:bg-gray-50 transition-colors">
                                Nhật ký
                            </button>
                            
                            @if(!$doc->trashed())
                                <a href="{{ route('contributor.documents.edit', ['id' => $doc->id]) }}" class="inline-flex items-center rounded-lg bg-white px-2.5 py-1.5 text-xs font-semibold text-gray-700 shadow-sm ring-1 ring-inset ring-gray-300 hover:bg-gray-50 transition-colors">
                                    Sửa
                                </a>
                                <button wire:click="confirmDelete({{ $doc->id }})" 
                                        class="inline-flex items-center rounded-lg bg-white px-2.5 py-1.5 text-xs font-semibold text-rose-600 shadow-sm ring-1 ring-inset ring-rose-300 hover:bg-rose-50 transition-colors">
                                    Xóa
                                </button>
                            @endif
                        </div>
                    </div>
                </div>
            @empty
                <div class="p-12 text-center">
                    <svg class="mx-auto h-12 w-12 text-gray-300" fill="none" viewBox="0 0 24 24" stroke="currentColor" aria-hidden="true">
                        <path stroke-linecap="round" stroke-linejoin="round" stroke-width="1.5" d="M19 11H5m14 0a2 2 0 012 2v6a2 2 0 01-2 2H5a2 2 0 01-2-2v-6a2 2 0 012-2m14 0V9a2 2 0 00-2-2M5 11V9a2 2 0 012-2m0 0V5a2 2 0 012-2h6a2 2 0 012 2v2M7 7h10" />
                    </svg>
                    <h3 class="mt-2 text-sm font-semibold text-gray-900">Không có dữ liệu</h3>
                    <p class="mt-1 text-sm text-gray-500">Không tìm thấy tài liệu nào khớp với bộ lọc hiện tại.</p>
                </div>
            @endforelse
            </div>

            <!-- Desktop Table View (hidden on mobile) -->
            <div class="hidden md:block overflow-x-auto pb-4">
                <table class="w-full divide-y divide-gray-200 min-w-[1000px]">
                    <thead class="bg-slate-50/50">
                        <tr>
                            <th scope="col" class="py-3.5 pl-6 pr-3 text-left text-sm font-semibold text-gray-900 cursor-pointer hover:bg-slate-100/50 transition-colors w-[32%]" wire:click="sortBy('title')">
                                <div class="flex items-center gap-2">
                                    Tài liệu
                                    @if($sortField === 'title')
                                        <svg class="w-4 h-4 {{ $sortDirection === 'asc' ? 'rotate-180' : '' }} transition-transform text-blue-600" fill="none" viewBox="0 0 24 24" stroke="currentColor">
                                            <path stroke-linecap="round" stroke-linejoin="round" stroke-width="2" d="M19 9l-7 7-7-7" />
                                        </svg>
                                    @else
                                        <svg class="w-4 h-4 text-gray-400 opacity-0 group-hover:opacity-100 transition-opacity" fill="none" viewBox="0 0 24 24" stroke="currentColor"><path stroke-linecap="round" stroke-linejoin="round" stroke-width="2" d="M19 9l-7 7-7-7" /></svg>
                                    @endif
                                </div>
                            </th>
                            <th scope="col" class="px-3 py-3.5 text-left text-sm font-semibold text-gray-900 w-[15%]">
                                Giá bán
                            </th>
                            <th scope="col" class="px-3 py-3.5 text-left text-sm font-semibold text-gray-900 w-[12%]">
                                Trạng thái
                            </th>
                            <th scope="col" class="px-3 py-3.5 text-left text-sm font-semibold text-gray-900 w-[9%]">Hiển thị</th>
                            <th scope="col" class="px-3 py-3.5 text-left text-sm font-semibold text-gray-900 cursor-pointer hover:bg-slate-100/50 transition-colors w-[12%]" wire:click="sortBy('created_at')">
                                <div class="flex items-center gap-2">
                                    Ngày tải lên
                                    @if($sortField === 'created_at')
                                        <svg class="w-4 h-4 {{ $sortDirection === 'asc' ? 'rotate-180' : '' }} transition-transform text-blue-600" fill="none" viewBox="0 0 24 24" stroke="currentColor">
                                            <path stroke-linecap="round" stroke-linejoin="round" stroke-width="2" d="M19 9l-7 7-7-7" />
                                        </svg>
                                    @else
                                        <svg class="w-4 h-4 text-gray-400 opacity-0 group-hover:opacity-100 transition-opacity" fill="none" viewBox="0 0 24 24" stroke="currentColor"><path stroke-linecap="round" stroke-linejoin="round" stroke-width="2" d="M19 9l-7 7-7-7" /></svg>
                                    @endif
                                </div>
                            </th>
                            <th scope="col" class="relative py-3.5 px-3 text-center text-sm font-semibold text-gray-900 w-[20%]">Thao tác</th>
                        </tr>
                    </thead>
                    <tbody class="divide-y divide-gray-200 bg-white">
                        @forelse($documents as $doc)
                            <tr class="transition hover:bg-gray-50/50">
                                <td class="py-4 pl-6 pr-3 align-top">
                                    <div class="flex flex-col gap-1">
                                        <a href="{{ route('documents.show', [$doc->id, Str::slug($doc->currentVersion?->title ?? $doc->title ?? 'tai-lieu')]) }}" target="_blank" class="text-sm font-semibold text-gray-900 hover:text-blue-600 truncate max-w-[280px] lg:max-w-[350px]" title="{{ $doc->currentVersion?->title ?? $doc->title }}">
                                            {{ \Illuminate\Support\Str::limit($doc->title, 55) }}
                                        </a>
                                        
                                        <div class="flex items-center gap-2 mt-1">
                                            <span class="inline-flex items-center rounded-md bg-gray-50 px-1.5 py-0.5 text-[10px] font-medium text-gray-600 ring-1 ring-inset ring-gray-500/10 uppercase">
                                                {{ $doc->file_type }}
                                            </span>
                                            <span class="text-xs text-gray-500">{{ number_format($doc->file_size / 1024 / 1024, 2) }} MB</span>
                                            <span class="inline-flex items-center gap-1 rounded-md px-1.5 py-0.5 text-[10px] font-medium ring-1 ring-inset bg-blue-50 text-blue-700 ring-blue-600/20">
                                                {{ $doc->category?->name ?? 'Mặc định' }}
                                            </span>
                                        </div>

                                        @if(!$doc->trashed() && (($doc->status === 'rejected' && $doc->rejected_reason) || ($doc->status === 'approved' && $doc->rejectedVersion)))
                                            @php 
                                                $isEditRejection = $doc->status === 'approved' && $doc->rejectedVersion;
                                                $rejectedV = $doc->rejectedVersion;
                                                if ($isEditRejection) {
                                                    $reason = $rejectedV->rejected_reason;
                                                    $title = 'Cập nhật bị từ chối';
                                                    $bgColor = 'bg-amber-50';
                                                    $borderColor = 'border-amber-200';
                                                    $textColor = 'text-amber-800';
                                                    $iconColor = 'text-amber-500';
                                                } else {
                                                    $reason = $doc->rejected_reason;
                                                    $title = 'Bị từ chối';
                                                    $bgColor = 'bg-rose-50';
                                                    $borderColor = 'border-rose-200';
                                                    $textColor = 'text-rose-800';
                                                    $iconColor = 'text-rose-500';
                                                }
                                            @endphp
                                            <div class="mt-3 p-3 {{ $bgColor }} border {{ $borderColor }} rounded-xl flex items-start gap-2">
                                                <svg class="w-4 h-4 {{ $iconColor }} shrink-0 mt-0.5" fill="none" stroke="currentColor" viewBox="0 0 24 24">
                                                    <path stroke-linecap="round" stroke-linejoin="round" stroke-width="2" d="M12 9v2m0 4h.01m-6.938 4h13.856c1.54 0 2.502-1.667 1.732-3L13.732 4c-.77-1.333-2.694-1.333-3.464 0L3.34 16c-.77 1.333.192 3 1.732 3z"/>
                                                </svg>
                                                <div class="flex-1 text-xs min-w-0">
                                                    <span class="font-bold {{ $textColor }}">{{ $title }}:</span>
                                                    <span class="text-gray-700 ml-1 break-words line-clamp-2" title="{{ $reason }}">{{ $reason }}</span>
                                                </div>
                                                <button wire:click="dismissRejectedVersion({{ $doc->id }})" class="shrink-0 ml-1 text-gray-400 hover:text-gray-700 mt-0.5">
                                                    <svg class="w-4 h-4" fill="none" stroke="currentColor" viewBox="0 0 24 24"><path stroke-linecap="round" stroke-linejoin="round" stroke-width="2" d="M6 18L18 6M6 6l12 12"/></svg>
                                                </button>
                                            </div>
                                        @endif
                                        
                                        @if($doc->status === 'approved' && $doc->pendingVersion)
                                            <div class="mt-3 p-3 bg-blue-50 border border-blue-200 rounded-xl flex items-center justify-between">
                                                <div class="flex items-center gap-2">
                                                    <svg class="w-4 h-4 text-blue-500 shrink-0" fill="none" stroke="currentColor" viewBox="0 0 24 24">
                                                        <path stroke-linecap="round" stroke-linejoin="round" stroke-width="2" d="M13 16h-1v-4h-1m1-4h.01M21 12a9 9 0 11-18 0 9 9 0 0118 0z"/>
                                                    </svg>
                                                    <span class="font-bold text-xs text-blue-800">Đang chờ duyệt bản cập nhật</span>
                                                </div>
                                                <button wire:click.prevent="cancelUpdate({{ $doc->id }})" class="ml-2 px-3 py-1.5 text-xs font-semibold text-rose-600 bg-white border border-rose-200 rounded-lg hover:bg-rose-50 transition-colors" onclick="confirm('Hủy yêu cầu cập nhật?') || event.stopImmediatePropagation()">Hủy</button>
                                            </div>
                                        @endif
                                    </div>
                                </td>
                                <td class="whitespace-nowrap px-3 py-4 text-sm text-gray-500">
                                    @if($doc->product && $doc->product->price > 0)
                                        @if($doc->product->sale_price)
                                            <span class="font-semibold text-blue-600">{{ number_format($doc->product->sale_price) }}đ</span>
                                            <span class="block text-xs text-gray-400 line-through">{{ number_format($doc->product->price) }}đ</span>
                                        @else
                                            <span class="font-semibold text-gray-900">{{ number_format($doc->product->price) }}đ</span>
                                        @endif
                                    @else
                                        <span class="inline-flex items-center rounded-md bg-emerald-50 px-2 py-1 text-xs font-medium text-emerald-700 ring-1 ring-inset ring-emerald-600/20">Miễn phí</span>
                                    @endif
                                </td>
                                <td class="whitespace-nowrap px-3 py-4 text-sm text-gray-500">
                                    <div class="flex flex-col gap-1.5 items-start">
                                        @if($doc->trashed())
                                            <span class="inline-flex items-center rounded-md bg-gray-50 px-2 py-1 text-xs font-medium text-gray-600 ring-1 ring-inset ring-gray-500/10">Đã xóa mềm</span>
                                        @elseif($doc->status === 'approved')
                                            <span class="inline-flex items-center rounded-md bg-emerald-50 px-2 py-1 text-xs font-medium text-emerald-700 ring-1 ring-inset ring-emerald-600/20">Đã duyệt</span>
                                        @elseif($doc->status === 'pending')
                                            <span class="inline-flex items-center rounded-md bg-amber-50 px-2 py-1 text-xs font-medium text-amber-800 ring-1 ring-inset ring-amber-600/20">Chờ duyệt</span>
                                        @elseif($doc->status === 'rejected')
                                            <span class="inline-flex items-center rounded-md bg-rose-50 px-2 py-1 text-xs font-medium text-rose-700 ring-1 ring-inset ring-rose-600/10" title="Lý do: {{ $doc->rejected_reason }}">Bị từ chối</span>
                                        @elseif($doc->status === 'unpublished')
                                            <span class="inline-flex items-center rounded-md bg-gray-50 px-2 py-1 text-xs font-medium text-gray-600 ring-1 ring-inset ring-gray-500/10">Đã gỡ</span>
                                        @else
                                            <span class="inline-flex items-center rounded-md bg-gray-50 px-2 py-1 text-xs font-medium text-gray-600 ring-1 ring-inset ring-gray-500/10">Bản nháp</span>
                                        @endif
                                    </div>
                                </td>
                                <td class="whitespace-nowrap px-3 py-4 text-sm text-gray-500">
                                    <span class="inline-flex items-center rounded-md px-2 py-1 text-xs font-medium ring-1 ring-inset {{ $doc->visibility === 'public' ? 'bg-blue-50 text-blue-700 ring-blue-600/20' : 'bg-gray-50 text-gray-600 ring-gray-500/10' }}">
                                        {{ $doc->visibility === 'public' ? 'Công khai' : 'Riêng tư' }}
                                    </span>
                                </td>
                                <td class="whitespace-nowrap px-3 py-4 text-sm text-gray-500">
                                    <div class="text-gray-900">{{ $doc->created_at->format('d/m/Y') }}</div>
                                    <div class="text-xs text-gray-500">{{ $doc->created_at->format('H:i') }}</div>
                                </td>
                                <td class="relative whitespace-nowrap py-4 px-3 text-center text-sm font-medium">
                                    <div class="flex items-center justify-center gap-2">
                                        <button wire:click="showHistory({{ $doc->id }})" class="inline-flex items-center justify-center rounded-lg bg-white px-3 py-1.5 text-xs font-semibold text-gray-900 shadow-sm ring-1 ring-inset ring-gray-300 hover:bg-gray-50 transition-colors">
                                            Nhật ký
                                        </button>
                                        @if(!$doc->trashed())
                                            <a href="{{ route('contributor.documents.edit', ['id' => $doc->id]) }}" class="inline-flex items-center justify-center rounded-lg bg-white px-3 py-1.5 text-xs font-semibold text-gray-900 shadow-sm ring-1 ring-inset ring-gray-300 hover:bg-gray-50 transition-colors">
                                                Sửa
                                            </a>
                                            <button wire:click="confirmDelete({{ $doc->id }})" 
                                                    class="inline-flex items-center justify-center rounded-lg bg-white px-3 py-1.5 text-xs font-semibold text-rose-600 shadow-sm ring-1 ring-inset ring-rose-300 hover:bg-rose-50 transition-colors">
                                                Xóa
                                            </button>
                                        @endif
                                    </div>
                                </td>
                            </tr>
                        @empty
                            <tr>
                                <td colspan="6" class="py-12 px-4 text-center">
                                    <svg class="mx-auto h-12 w-12 text-gray-300" fill="none" viewBox="0 0 24 24" stroke="currentColor" aria-hidden="true">
                                        <path stroke-linecap="round" stroke-linejoin="round" stroke-width="1.5" d="M19 11H5m14 0a2 2 0 012 2v6a2 2 0 01-2 2H5a2 2 0 01-2-2v-6a2 2 0 012-2m14 0V9a2 2 0 00-2-2M5 11V9a2 2 0 012-2m0 0V5a2 2 0 012-2h6a2 2 0 012 2v2M7 7h10" />
                                    </svg>
                                    <h3 class="mt-2 text-sm font-semibold text-gray-900">Không có dữ liệu</h3>
                                    <p class="mt-1 text-sm text-gray-500">Không tìm thấy tài liệu nào khớp với bộ lọc hiện tại.</p>
                                </td>
                            </tr>
                        @endforelse
                    </tbody>
                </table>
            </div>
        </div>

        <!-- Pagination -->
        <div class="mt-6 border-t border-gray-100 pt-6">
            {{ $documents->links(data: ['scrollTo' => '#contributor-document-list']) }}
        </div>
    </div>

    <!-- Delete Confirmation Modal -->
    <template x-teleport="body">
        <div x-data="{ show: @entangle('confirmDeleteId').live }" x-show="show" class="fixed inset-0 z-[100] overflow-y-auto flex items-center justify-center p-4 sm:p-0" style="display: none;">
            <div x-show="show" x-transition:enter="ease-out duration-300" x-transition:enter-start="opacity-0" x-transition:enter-end="opacity-100" x-transition:leave="ease-in duration-200" x-transition:leave-start="opacity-100" x-transition:leave-end="opacity-0" class="fixed inset-0 bg-gray-900/50 backdrop-blur-sm transition-opacity" aria-hidden="true" wire:click="$set('confirmDeleteId', null)"></div>

            <div x-show="show" x-transition:enter="ease-out duration-300" x-transition:enter-start="opacity-0 translate-y-4 sm:translate-y-0 sm:scale-95" x-transition:enter-end="opacity-100 translate-y-0 sm:scale-100" x-transition:leave="ease-in duration-200" x-transition:leave-start="opacity-100 translate-y-0 sm:scale-100" x-transition:leave-end="opacity-0 translate-y-4 sm:translate-y-0 sm:scale-95" class="relative inline-block align-bottom bg-white rounded-2xl text-left overflow-hidden shadow-xl transform transition-all sm:my-8 sm:align-middle sm:max-w-lg sm:w-full">
                <div class="bg-white px-4 pt-5 pb-4 sm:p-6 sm:pb-4">
                    <div class="sm:flex sm:items-start">
                        <div class="mx-auto flex-shrink-0 flex items-center justify-center h-12 w-12 rounded-full bg-rose-100 sm:mx-0 sm:h-10 sm:w-10">
                            <svg class="h-6 w-6 text-rose-600" xmlns="http://www.w3.org/2000/svg" fill="none" viewBox="0 0 24 24" stroke="currentColor" aria-hidden="true">
                                <path stroke-linecap="round" stroke-linejoin="round" stroke-width="2" d="M12 9v2m0 4h.01m-6.938 4h13.856c1.54 0 2.502-1.667 1.732-3L13.732 4c-.77-1.333-2.694-1.333-3.464 0L3.34 16c-.77 1.333.192 3 1.732 3z" />
                            </svg>
                        </div>
                        <div class="mt-3 text-center sm:mt-0 sm:ml-4 sm:text-left">
                            <h3 class="text-lg leading-6 font-bold text-gray-900" id="modal-title">Xóa tài liệu</h3>
                            <div class="mt-2">
                                <p class="text-sm text-gray-500">Bạn có chắc chắn muốn xóa tài liệu này? Hành động này không thể hoàn tác.</p>
                            </div>
                        </div>
                    </div>
                </div>
                <div class="bg-gray-50 px-4 py-3 sm:px-6 sm:flex sm:flex-row-reverse gap-3">
                    <button type="button" wire:click="delete" class="w-full inline-flex justify-center rounded-xl border border-transparent shadow-sm px-4 py-2.5 bg-rose-600 text-base font-semibold text-white hover:bg-rose-500 focus:outline-none focus:ring-2 focus:ring-offset-2 focus:ring-rose-500 sm:w-auto sm:text-sm">Xóa tài liệu</button>
                    <button type="button" wire:click="$set('confirmDeleteId', null)" class="mt-3 w-full inline-flex justify-center rounded-xl border border-gray-300 shadow-sm px-4 py-2.5 bg-white text-base font-semibold text-gray-700 hover:bg-gray-50 focus:outline-none focus:ring-2 focus:ring-offset-2 focus:ring-indigo-500 sm:mt-0 sm:w-auto sm:text-sm">Hủy bỏ</button>
                </div>
            </div>
        </div>
    </template>

    <!-- History Modal -->
    @if($showHistoryModal)
    <div class="fixed inset-0 z-[110] overflow-y-auto flex items-center justify-center p-4 bg-gray-900/60 backdrop-blur-sm">
        <div class="bg-white rounded-2xl max-w-4xl w-full border border-gray-200 shadow-2xl p-6 space-y-4 max-h-[90vh] overflow-y-auto 
                    [&::-webkit-scrollbar]:w-2 
                    [&::-webkit-scrollbar-track]:bg-gray-100 
                    [&::-webkit-scrollbar-track]:rounded-full
                    [&::-webkit-scrollbar-thumb]:bg-gray-300 
                    [&::-webkit-scrollbar-thumb]:rounded-full
                    [&::-webkit-scrollbar-thumb]:hover:bg-gray-400">
            <div class="flex items-center justify-between border-b border-gray-100 pb-3 sticky top-0 bg-white z-10 shadow-sm">
                <h3 class="text-lg font-bold text-gray-900">📋 Lịch sử gửi & duyệt tài liệu</h3>
                <button wire:click="closeHistoryModal" class="text-gray-400 hover:text-gray-600 text-2xl">&times;</button>
            </div>
            
            @if($submissionHistory && count($submissionHistory) > 0)
                <div class="space-y-0">
                    @foreach($submissionHistory as $index => $event)
                        <div class="relative pl-8 pb-6 {{ $index < count($submissionHistory) - 1 ? 'border-l-2 border-gray-200' : '' }}">
                            <div class="absolute left-0 top-0 -ml-[7px] h-3.5 w-3.5 rounded-full border-2 
                                {{ $event->type === 'submission' ? 'bg-indigo-500 border-indigo-600' : '' }}
                                {{ $event->type === 'review' && $event->status === 'approved' ? 'bg-emerald-500 border-emerald-600' : '' }}
                                {{ $event->type === 'review' && $event->status === 'rejected' ? 'bg-rose-500 border-rose-600' : '' }}
                                {{ $event->type === 'deleted' ? 'bg-gray-500 border-gray-600' : '' }}">
                            </div>
                            
                            <div class="space-y-1">
                                <div class="flex items-center justify-between">
                                    <span class="text-sm font-bold text-gray-800">
                                        @if($event->type === 'submission')
                                            @if(isset($event->version_number) && $event->version_number > 1)
                                                {{ $event->user && $event->user->hasRole('admin') ? '🔄 Cập nhật tài liệu' : '🔄 Gửi bản cập nhật' }}
                                            @else
                                                {{ $event->user && $event->user->hasRole('admin') ? '📝 Đăng tài liệu' : '📤 Gửi tài liệu mới' }}
                                            @endif
                                        @elseif($event->type === 'review')
                                            @if($event->status === 'approved')
                                                ✅ Phê duyệt
                                            @elseif($event->status === 'rejected')
                                                ❌ Từ chối
                                            @endif
                                        @elseif($event->type === 'deleted')
                                            🗑️ Đã xóa
                                        @endif
                                    </span>
                                    <span class="text-xs text-gray-400 whitespace-nowrap ml-2">
                                        {{ $event->timestamp instanceof \Carbon\Carbon ? $event->timestamp->format('d/m/Y H:i') : (is_string($event->timestamp) ? date('d/m/Y H:i', strtotime($event->timestamp)) : '') }}
                                    </span>
                                </div>
                                
                                <div class="text-xs text-gray-500">
                                    @if($event->user)
                                        <div class="break-words">
                                            <span class="font-medium text-gray-600">{{ $event->user->name ?? 'Unknown' }}</span>
                                            @if($event->type === 'submission')
                                                @if(isset($event->version_number) && $event->version_number > 1)
                                                    <span>{{ $event->user && $event->user->hasRole('admin') ? 'đã cập nhật tài liệu' : 'đã gửi bản cập nhật mới' }}</span>
                                                @else
                                                    <span>{{ $event->user && $event->user->hasRole('admin') ? 'đã đăng tài liệu' : 'đã gửi tài liệu' }}</span>
                                                @endif
                                            @elseif($event->type === 'review')
                                                <span>đã {{ $event->status === 'approved' ? 'phê duyệt' : 'từ chối' }}</span>
                                            @elseif($event->type === 'deleted')
                                                <span>đã xóa tài liệu</span>
                                            @endif
                                        </div>
                                    @endif
                                    
                                    @if(isset($event->details) && $event->details)
                                        @if($event->status === 'rejected')
                                            <div class="mt-1.5 p-2 bg-rose-50 border-l-2 border-rose-400 rounded text-[11px]">
                                                <span class="font-bold text-rose-800">Lý do từ chối:</span>
                                                <span class="text-rose-700">"{{ $event->details }}"</span>
                                            </div>
                                        @elseif($event->type === 'deleted')
                                            <div class="mt-1.5 p-2 bg-gray-50 border-l-2 border-gray-400 rounded text-[11px]">
                                                <span class="font-bold text-gray-800">Thông báo:</span>
                                                <span class="text-gray-700">"{{ $event->details }}"</span>
                                            </div>
                                        @else
                                            <div class="mt-1.5 p-2 bg-emerald-50 border-l-2 border-emerald-400 rounded text-[11px]">
                                                <span class="font-bold text-emerald-800">Ghi chú:</span>
                                                <span class="text-emerald-700">"{{ $event->details }}"</span>
                                            </div>
                                        @endif
                                    @endif
                                </div>
                            </div>
                        </div>
                    @endforeach
                </div>
            @else
                <div class="text-center py-8 text-gray-400">
                    <svg class="w-12 h-12 mx-auto mb-3 text-gray-300" fill="none" stroke="currentColor" viewBox="0 0 24 24">
                        <path stroke-linecap="round" stroke-linejoin="round" stroke-width="1.5" d="M9 12h6m-6 4h6m2 5H7a2 2 0 01-2-2V5a2 2 0 012-2h5.586a1 1 0 01.707.293l5.414 5.414a1 1 0 01.293.707V19a2 2 0 01-2 2z"/>
                    </svg>
                    Chưa có lịch sử gửi & duyệt
                </div>
            @endif
            
            <div class="pt-3 border-t border-gray-100">
                <button wire:click="closeHistoryModal" class="w-full rounded-xl bg-gray-100 hover:bg-gray-200 text-gray-700 px-4 py-2.5 text-sm font-bold transition-colors">
                    Đóng
                </button>
            </div>
        </div>
    </div>
    @endif

</div>
