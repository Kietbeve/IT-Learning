<div id="admin-document-list" wire:poll.10s.keep-alive
     x-data="{ notification: null }" 
     x-on:notify.window="notification = $event.detail; setTimeout(() => notification = null, 3000)"
     class="space-y-6">

    <style>
        #admin-document-list, 
        #admin-document-list.wire-loading,
        #admin-document-list * { 
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
                <p class="text-sm font-medium text-gray-500">Tổng tài liệu</p>
                <span class="rounded-xl bg-gray-100 p-2.5 text-gray-600">
                    <svg class="h-5 w-5" fill="none" stroke="currentColor" viewBox="0 0 24 24">
                        <path stroke-linecap="round" stroke-linejoin="round" stroke-width="2" d="M9 12h6m-6 4h6m2 5H7a2 2 0 01-2-2V5a2 2 0 012-2h5.586a1 1 0 01.707.293l5.414 5.414a1 1 0 01.293.707V19a2 2 0 01-2 2z"/>
                    </svg>
                </span>
            </div>
            <p class="mt-4 text-3xl font-bold text-gray-900">{{ number_format($totalCount) }}</p>
            <p class="mt-1 text-xs text-gray-500">Tất cả tài liệu trên hệ thống</p>
        </div>

        <div class="rounded-2xl bg-white p-6 shadow-sm ring-1 ring-gray-200 transition hover:shadow-md">
            <div class="flex items-center justify-between">
                <p class="text-sm font-medium text-gray-500">Đã duyệt</p>
                <span class="rounded-xl bg-emerald-50 p-2.5 text-emerald-600">
                    <svg class="h-5 w-5" fill="none" stroke="currentColor" viewBox="0 0 24 24">
                        <path stroke-linecap="round" stroke-linejoin="round" stroke-width="2" d="M9 12l2 2 4-4m6 2a9 9 0 11-18 0 9 9 0 0118 0z"/>
                    </svg>
                </span>
            </div>
            <p class="mt-4 text-3xl font-bold text-gray-900">{{ number_format($approvedCount) }}</p>
            <p class="mt-1 text-xs text-gray-500">Tài liệu đã phê duyệt</p>
        </div>

        <div class="rounded-2xl bg-white p-6 shadow-sm ring-1 ring-gray-200 transition hover:shadow-md">
            <div class="flex items-center justify-between">
                <p class="text-sm font-medium text-gray-500">Chờ duyệt</p>
                <span class="rounded-xl bg-amber-50 p-2.5 text-amber-600">
                    <svg class="h-5 w-5" fill="none" stroke="currentColor" viewBox="0 0 24 24">
                        <path stroke-linecap="round" stroke-linejoin="round" stroke-width="2" d="M12 8v4l3 3m6-3a9 9 0 11-18 0 9 9 0 0118 0z"/>
                    </svg>
                </span>
            </div>
            <p class="mt-4 text-3xl font-bold text-gray-900">{{ number_format($pendingCount) }}</p>
            <p class="mt-1 text-xs text-gray-500">Cần kiểm duyệt</p>
        </div>

        <div class="rounded-2xl bg-white p-6 shadow-sm ring-1 ring-gray-200 transition hover:shadow-md">
            <div class="flex items-center justify-between">
                <p class="text-sm font-medium text-gray-500">Lượt tải</p>
                <span class="rounded-xl bg-blue-50 p-2.5 text-blue-600">
                    <svg class="h-5 w-5" fill="none" stroke="currentColor" viewBox="0 0 24 24">
                        <path stroke-linecap="round" stroke-linejoin="round" stroke-width="2" d="M4 16v1a3 3 0 003 3h10a3 3 0 003-3v-1m-4-4l-4 4m0 0l-4-4m4 4V4"/>
                    </svg>
                </span>
            </div>
            <p class="mt-4 text-3xl font-bold text-gray-900">{{ number_format($totalDownloads) }}</p>
            <p class="mt-1 text-xs text-gray-500">Tổng lượt tải thành công</p>
        </div>
    </div>

    {{-- Header - Thanh tìm kiếm và bộ lọc --}}
    <div class="flex flex-col gap-4 sm:flex-row sm:items-center sm:justify-between">
        <div class="flex-1 max-w-2xl flex flex-col sm:flex-row items-center gap-3">
            <div class="relative w-full sm:max-w-md">
                <div class="pointer-events-none absolute inset-y-0 left-0 flex items-center pl-3">
                    <svg class="h-5 w-5 text-gray-400" viewBox="0 0 20 20" fill="currentColor" aria-hidden="true">
                        <path fill-rule="evenodd" d="M9 3.5a5.5 5.5 0 100 11 5.5 5.5 0 000-11zM2 9a7 7 0 1112.452 4.391l3.328 3.329a.75.75 0 11-1.06 1.06l-3.329-3.328A7 7 0 012 9z" clip-rule="evenodd" />
                    </svg>
                </div>
                <input type="search" 
                       id="search-admin-documents"
                       wire:model.live.debounce.300ms="search" 
                       placeholder="Tìm kiếm tài liệu..." 
                       class="block w-full rounded-xl border-0 py-2.5 pl-10 pr-10 text-gray-900 shadow-sm ring-1 ring-inset ring-gray-300 placeholder:text-gray-400 focus:ring-2 focus:ring-inset focus:ring-blue-600 sm:text-sm sm:leading-6 transition-all" />
                
                <div wire:loading.flex wire:target="search" class="absolute inset-y-0 right-3 items-center">
                    <svg class="h-4 w-4 animate-spin text-blue-600" xmlns="http://www.w3.org/2000/svg" fill="none" viewBox="0 0 24 24">
                        <circle class="opacity-25" cx="12" cy="12" r="10" stroke="currentColor" stroke-width="4"></circle>
                        <path class="opacity-75" fill="currentColor" d="M4 12a8 8 0 018-8V0C5.373 0 0 5.373 0 12h4z"></path>
                    </svg>
                </div>
            </div>
            
            <select wire:model.live="categoryFilter" aria-label="Lọc theo danh mục" 
                    class="block w-full sm:w-auto rounded-xl border-0 py-2.5 pl-4 pr-10 text-gray-900 shadow-sm ring-1 ring-inset ring-gray-300 focus:ring-2 focus:ring-inset focus:ring-blue-600 sm:text-sm sm:leading-6 transition-all">
                <option value="all">Tất cả danh mục</option>
                @foreach($categories as $cat)
                    <option value="{{ $cat->id }}">{{ $cat->name }}</option>
                @endforeach
            </select>
        </div>

        <a href="{{ route('admin.documents.create') }}" 
           class="inline-flex items-center justify-center gap-2 rounded-xl bg-blue-600 px-4 py-2.5 text-sm font-semibold text-white shadow-sm hover:bg-blue-500 focus-visible:outline focus-visible:outline-2 focus-visible:outline-offset-2 focus-visible:outline-blue-600 transition-all shrink-0">
            <svg class="h-5 w-5" fill="none" stroke="currentColor" viewBox="0 0 24 24"><path stroke-linecap="round" stroke-linejoin="round" stroke-width="2" d="M12 4v16m8-8H4"/></svg>
            Đăng tài liệu
        </a>
    </div>

    {{-- Tabs --}}
    <div class="flex flex-wrap items-center gap-2 border-b border-gray-200 pb-4">
        <button wire:click="$set('activeTab', 'pending')" 
                class="inline-flex items-center gap-2 rounded-lg px-4 py-2 text-sm font-medium transition-colors {{ $activeTab === 'pending' ? 'bg-gray-900 text-white shadow-sm' : 'text-gray-500 hover:bg-gray-100 hover:text-gray-900' }}">
            Chờ duyệt
            @if($pendingCount > 0)
                <span class="inline-flex items-center justify-center rounded-full px-2 py-0.5 text-xs font-semibold {{ $activeTab === 'pending' ? 'bg-white/20 text-white' : 'bg-amber-100 text-amber-700' }}">
                    {{ $pendingCount }}
                </span>
            @endif
        </button>
        <button wire:click="$set('activeTab', 'approved')" 
                class="inline-flex items-center rounded-lg px-4 py-2 text-sm font-medium transition-colors {{ $activeTab === 'approved' ? 'bg-gray-900 text-white shadow-sm' : 'text-gray-500 hover:bg-gray-100 hover:text-gray-900' }}">
            Đã duyệt
        </button>
        <button wire:click="$set('activeTab', 'rejected')" 
                class="inline-flex items-center rounded-lg px-4 py-2 text-sm font-medium transition-colors {{ $activeTab === 'rejected' ? 'bg-gray-900 text-white shadow-sm' : 'text-gray-500 hover:bg-gray-100 hover:text-gray-900' }}">
            Bị từ chối
        </button>
        <button wire:click="$set('activeTab', 'unpublished')" 
                class="inline-flex items-center rounded-lg px-4 py-2 text-sm font-medium transition-colors {{ $activeTab === 'unpublished' ? 'bg-gray-900 text-white shadow-sm' : 'text-gray-500 hover:bg-gray-100 hover:text-gray-900' }}">
            Đã gỡ
        </button>
        <button wire:click="$set('activeTab', 'deleted')" 
                class="inline-flex items-center rounded-lg px-4 py-2 text-sm font-medium transition-colors {{ $activeTab === 'deleted' ? 'bg-gray-900 text-white shadow-sm' : 'text-gray-500 hover:bg-gray-100 hover:text-gray-900' }}">
            Đã xóa
        </button>
    </div>

    {{-- Bảng dữ liệu --}}
    <div class="overflow-hidden rounded-2xl bg-white shadow ring-1 ring-gray-200">
        <div wire:loading.class="opacity-50 pointer-events-none" class="transition-opacity duration-200">
            
            {{-- Mobile Card View --}}
            <div class="block md:hidden divide-y divide-gray-100">
            @forelse($documents as $doc)
                <div class="p-5 space-y-4 hover:bg-gray-50/50 transition-colors">
                    <div class="flex items-start justify-between gap-4">
                        <div class="space-y-2 flex-1 min-w-0">
                            <a href="{{ route('admin.moderation.documents.show', ['id' => $doc->id, 'from' => 'list']) }}" class="text-sm font-semibold text-gray-900 hover:text-blue-600 block leading-tight truncate" title="{{ $doc->pending_version_data->title ?? $doc->title }}">
                                {{ \Illuminate\Support\Str::limit($doc->pending_version_data->title ?? $doc->title, 50) }}
                            </a>
                            
                            <div class="flex flex-wrap items-center gap-2 mt-1">
                                <span class="inline-flex items-center rounded-md bg-gray-50 px-2 py-1 text-[10px] font-medium text-gray-600 ring-1 ring-inset ring-gray-500/10 uppercase">
                                    {{ $doc->file_type }}
                                </span>
                                @if($activeTab === 'pending' && isset($doc->badge_type))
                                    <span class="inline-flex items-center rounded-md px-2 py-1 text-[10px] font-medium ring-1 ring-inset {{ $doc->badge_type === 'new' ? 'bg-emerald-50 text-emerald-700 ring-emerald-600/20' : 'bg-blue-50 text-blue-700 ring-blue-600/20' }}">
                                        {{ $doc->badge_type === 'new' ? '✨ Mới' : '📝 Cập nhật' }}
                                    </span>
                                @endif
                                <span class="text-xs text-gray-500">{{ number_format($doc->file_size / 1024 / 1024, 2) }} MB</span>
                            </div>
                        </div>

                        <div class="shrink-0 text-right">
                            @if($doc->product)
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

                    <div class="flex flex-col gap-1.5 rounded-xl bg-gray-50 p-3 text-xs text-gray-600 ring-1 ring-inset ring-gray-200/50">
                        <div class="flex items-center justify-between">
                            <span class="text-gray-500">Đăng bởi:</span>
                            <span class="font-medium text-gray-900">{{ $doc->author?->name ?? 'Uploader' }}</span>
                        </div>
                        <div class="flex items-center justify-between">
                            <span class="text-gray-500">Danh mục:</span>
                            <span class="font-medium text-gray-900">{{ $doc->category?->name ?? 'Chưa phân loại' }}</span>
                        </div>
                    </div>

                    <div class="flex items-center justify-between gap-4 pt-1">
                        <div>
                            @if($doc->trashed())
                                <span class="inline-flex items-center rounded-md bg-gray-50 px-2 py-1 text-xs font-medium text-gray-600 ring-1 ring-inset ring-gray-500/10">Đã xóa mềm</span>
                            @elseif($activeTab === 'pending' && $doc->pendingVersion)
                                <span class="inline-flex items-center rounded-md bg-amber-50 px-2 py-1 text-xs font-medium text-amber-800 ring-1 ring-inset ring-amber-600/20">Chờ duyệt</span>
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

                        <div class="flex items-center gap-2">
                            @if($activeTab === 'pending' && $doc->pendingVersion)
                                <button wire:click="approve({{ $doc->id }})" class="inline-flex items-center justify-center gap-1.5 rounded-lg bg-emerald-600 px-3 py-1.5 text-xs font-semibold text-white shadow-sm hover:bg-emerald-500 transition-colors">
                                    <svg class="h-3.5 w-3.5" viewBox="0 0 20 20" fill="currentColor"><path fill-rule="evenodd" d="M16.704 4.153a.75.75 0 01.143 1.052l-8 10.5a.75.75 0 01-1.127.075l-4.5-4.5a.75.75 0 011.06-1.06l3.894 3.893 7.48-9.817a.75.75 0 011.05-.143z" clip-rule="evenodd" /></svg>
                                    Duyệt
                                </button>
                                <button wire:click="openRejectionModal({{ $doc->id }})" class="inline-flex items-center gap-1.5 rounded-lg bg-white px-3 py-1.5 text-xs font-semibold text-gray-900 shadow-sm ring-1 ring-inset ring-gray-300 hover:bg-gray-50 transition-colors">
                                    Từ chối
                                </button>
                            @else
                                <button wire:click="toggleVisibility({{ $doc->id }})" 
                                        @if($doc->trashed()) disabled @endif
                                        class="inline-flex items-center rounded-lg bg-white px-3 py-1.5 text-xs font-semibold shadow-sm ring-1 ring-inset transition-colors {{ $doc->visibility === 'public' ? 'text-blue-700 ring-blue-600/20 hover:bg-blue-50' : 'text-gray-700 ring-gray-300 hover:bg-gray-50' }}">
                                    {{ $doc->visibility === 'public' ? 'Công khai' : 'Riêng tư' }}
                                </button>
                                @if(!$doc->trashed())
                                    <button onclick="confirm('Bạn có chắc chắn muốn xóa mềm tài liệu này không?') || event.stopImmediatePropagation()" 
                                            wire:click="deleteDocument({{ $doc->id }})" 
                                            class="inline-flex rounded-lg bg-white px-3 py-1.5 text-xs font-semibold text-rose-600 shadow-sm ring-1 ring-inset ring-rose-300 hover:bg-rose-50 transition-colors">
                                        Xóa
                                    </button>
                                @endif
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

            {{-- Desktop Table View --}}
            <div class="hidden md:block overflow-x-auto">
                <table class="w-full table-fixed divide-y divide-gray-200">
                    <thead class="bg-slate-50/50">
                        <tr>
                            <th scope="col" class="py-3.5 pl-6 pr-3 text-left text-sm font-semibold text-gray-900 cursor-pointer hover:bg-slate-100/50 transition-colors w-[30%]" wire:click="sortBy('title')">
                                <div class="flex items-center gap-2">
                                    Tài liệu
                                    @if($sortField === 'title')
                                        <span class="text-gray-400 text-xs">{{ $sortDirection === 'asc' ? '▲' : '▼' }}</span>
                                    @endif
                                </div>
                            </th>
                            <th scope="col" class="px-3 py-3.5 text-left text-sm font-semibold text-gray-900 w-[15%]">Tác giả</th>
                            <th scope="col" class="px-3 py-3.5 text-left text-sm font-semibold text-gray-900 w-[12%]">Phân loại</th>
                            <th scope="col" class="px-3 py-3.5 text-left text-sm font-semibold text-gray-900 w-[12%]">Trạng thái</th>
                            <th scope="col" class="px-3 py-3.5 text-left text-sm font-semibold text-gray-900 w-[10%]">Hiển thị</th>
                            <th scope="col" class="px-3 py-3.5 text-left text-sm font-semibold text-gray-900 cursor-pointer hover:bg-slate-100/50 transition-colors w-[10%]" wire:click="sortBy('created_at')">
                                <div class="flex items-center gap-2">
                                    Ngày tạo
                                    @if($sortField === 'created_at')
                                        <span class="text-gray-400 text-xs">{{ $sortDirection === 'asc' ? '▲' : '▼' }}</span>
                                    @endif
                                </div>
                            </th>
                            <th scope="col" class="relative py-3.5 pl-3 pr-6 text-right text-sm font-semibold text-gray-900 w-[11%]">Thao tác</th>
                        </tr>
                    </thead>
                    <tbody class="divide-y divide-gray-200 bg-white">
                        @forelse($documents as $doc)
                            <tr class="transition hover:bg-gray-50/50">
                                <td class="whitespace-nowrap py-4 pl-6 pr-3">
                                    <div class="flex flex-col gap-1">
                                        <a href="{{ route('admin.moderation.documents.show', ['id' => $doc->id, 'from' => 'list']) }}" class="text-sm font-semibold text-gray-900 hover:text-blue-600 truncate max-w-[280px] lg:max-w-[350px]" title="{{ $doc->pending_version_data->title ?? $doc->title }}">
                                            {{ \Illuminate\Support\Str::limit($doc->pending_version_data->title ?? $doc->title, 55) }}
                                        </a>
                                        
                                        @if($activeTab === 'pending' && ($doc->badge_type ?? '') === 'update' && isset($doc->live_version))
                                            <a href="{{ route('admin.moderation.documents.show', ['id' => $doc->id, 'from' => 'list', 'version' => 'current']) }}" 
                                               target="_blank"
                                               class="inline-flex items-center gap-1.5 rounded-lg bg-gray-50 px-2 py-1 text-xs text-gray-500 hover:bg-gray-100 transition-colors w-fit group border border-gray-200/60">
                                                <span class="font-medium">Bản gốc đang live:</span>
                                                <span class="text-gray-700 truncate max-w-[150px] group-hover:underline" title="{{ $doc->live_version->title }}">{{ \Illuminate\Support\Str::limit($doc->live_version->title, 25) }}</span>
                                                <svg class="h-3 w-3 text-gray-400" fill="none" viewBox="0 0 24 24" stroke="currentColor"><path stroke-linecap="round" stroke-linejoin="round" stroke-width="2" d="M10 6H6a2 2 0 00-2 2v10a2 2 0 002 2h10a2 2 0 002-2v-4M14 4h6m0 0v6m0-6L10 14"/></svg>
                                            </a>
                                        @endif

                                        <div class="flex items-center gap-2 mt-1">
                                            @if($activeTab === 'pending' && isset($doc->badge_type))
                                                <span class="inline-flex items-center rounded-md px-1.5 py-0.5 text-[10px] font-medium ring-1 ring-inset {{ $doc->badge_type === 'new' ? 'bg-emerald-50 text-emerald-700 ring-emerald-600/20' : 'bg-blue-50 text-blue-700 ring-blue-600/20' }}">
                                                    {{ $doc->badge_type === 'new' ? '✨ Mới' : '📝 Cập nhật' }}
                                                </span>
                                            @endif
                                            <span class="inline-flex items-center rounded-md bg-gray-50 px-1.5 py-0.5 text-[10px] font-medium text-gray-600 ring-1 ring-inset ring-gray-500/10 uppercase">
                                                {{ $doc->file_type }}
                                            </span>
                                            <span class="text-xs text-gray-500">{{ number_format($doc->file_size / 1024 / 1024, 2) }} MB</span>
                                        </div>
                                    </div>
                                </td>
                                <td class="whitespace-nowrap px-3 py-4 text-sm text-gray-500">
                                    <div class="font-medium text-gray-900 truncate max-w-[150px]" title="{{ $doc->author?->name ?? 'Uploader' }}">{{ $doc->author?->name ?? 'Uploader' }}</div>
                                    <div class="text-xs text-gray-500 truncate max-w-[150px]" title="{{ $doc->author?->email ?? '' }}">{{ $doc->author?->email ?? '' }}</div>
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
                                    @if($doc->trashed())
                                        <span class="inline-flex items-center rounded-md bg-gray-50 px-2 py-1 text-xs font-medium text-gray-600 ring-1 ring-inset ring-gray-500/10">Đã xóa mềm</span>
                                    @elseif($activeTab === 'pending' && $doc->pendingVersion)
                                        <span class="inline-flex items-center rounded-md bg-amber-50 px-2 py-1 text-xs font-medium text-amber-800 ring-1 ring-inset ring-amber-600/20">Chờ duyệt</span>
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
                                </td>
                                <td class="whitespace-nowrap px-3 py-4 text-sm text-gray-500">
                                    <button wire:click="toggleVisibility({{ $doc->id }})" 
                                            @if($doc->trashed()) disabled @endif
                                            class="inline-flex items-center rounded-lg bg-white px-3 py-1.5 text-xs font-semibold shadow-sm ring-1 ring-inset transition-colors {{ $doc->visibility === 'public' ? 'text-blue-700 ring-blue-600/20 hover:bg-blue-50' : 'text-gray-700 ring-gray-300 hover:bg-gray-50' }}">
                                        {{ $doc->visibility === 'public' ? 'Công khai' : 'Riêng tư' }}
                                    </button>
                                </td>
                                <td class="whitespace-nowrap px-3 py-4 text-sm text-gray-500">
                                    <div class="text-gray-900">{{ $doc->created_at->format('d/m/Y') }}</div>
                                    <div class="text-xs">{{ $doc->created_at->format('H:i') }}</div>
                                </td>
                                <td class="relative whitespace-nowrap py-4 pl-3 pr-6 text-right text-sm font-medium">
                                    <div class="flex flex-col items-end justify-center gap-1.5">
                                        @if($activeTab === 'pending' && $doc->pendingVersion)
                                            <button wire:click="approve({{ $doc->id }})" class="inline-flex w-[85px] items-center justify-center gap-1.5 rounded-lg bg-emerald-600 px-3 py-1.5 text-xs font-semibold text-white shadow-sm hover:bg-emerald-500 transition-colors">
                                                <svg class="h-3.5 w-3.5" viewBox="0 0 20 20" fill="currentColor"><path fill-rule="evenodd" d="M16.704 4.153a.75.75 0 01.143 1.052l-8 10.5a.75.75 0 01-1.127.075l-4.5-4.5a.75.75 0 011.06-1.06l3.894 3.893 7.48-9.817a.75.75 0 011.05-.143z" clip-rule="evenodd" /></svg>
                                                Duyệt
                                            </button>
                                            <button wire:click="openRejectionModal({{ $doc->id }})" class="inline-flex w-[85px] items-center justify-center gap-1.5 rounded-lg bg-white px-3 py-1.5 text-xs font-semibold text-gray-900 shadow-sm ring-1 ring-inset ring-gray-300 hover:bg-gray-50 transition-colors">
                                                Từ chối
                                            </button>
                                        @else
                                            @if(!$doc->trashed())
                                                <button onclick="confirm('Bạn có chắc chắn muốn xóa mềm tài liệu này không?') || event.stopImmediatePropagation()" 
                                                        wire:click="deleteDocument({{ $doc->id }})" 
                                                        class="inline-flex w-[85px] justify-center rounded-lg bg-white px-3 py-1.5 text-xs font-semibold text-rose-600 shadow-sm ring-1 ring-inset ring-rose-300 hover:bg-rose-50 transition-colors">
                                                    Xóa
                                                </button>
                                            @endif
                                        @endif
                                    </div>
                                </td>
                            </tr>
                        @empty
                            <tr>
                                <td colspan="7" class="py-12 px-4 text-center">
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
        
        @if($documents->hasPages())
            <div class="mt-6">
                {{ $documents->links(data: ['scrollTo' => '#admin-document-list']) }}
            </div>
        @endif
    </div>

    {{-- Rejection Modal --}}
    <div x-data="{ showRejectionModal: @entangle('showRejectionModal') }" x-effect="document.body.style.overflow = showRejectionModal ? 'hidden' : ''">
        <template x-teleport="body">
            <div x-show="showRejectionModal" 
                 class="fixed inset-0 z-[100] overflow-y-auto flex items-center justify-center p-4 sm:p-0" 
                 style="display: none;">
                 
                <div x-show="showRejectionModal" 
                     x-transition:enter="ease-out duration-300" x-transition:enter-start="opacity-0" x-transition:enter-end="opacity-100" 
                     x-transition:leave="ease-in duration-200" x-transition:leave-start="opacity-100" x-transition:leave-end="opacity-0"
                     class="fixed inset-0 bg-gray-900/50 backdrop-blur-sm transition-opacity" @click="showRejectionModal = false"></div>

                <div x-show="showRejectionModal"
                     x-transition:enter="ease-out duration-300" x-transition:enter-start="opacity-0 translate-y-4 sm:translate-y-0 sm:scale-95" x-transition:enter-end="opacity-100 translate-y-0 sm:scale-100"
                     x-transition:leave="ease-in duration-200" x-transition:leave-start="opacity-100 translate-y-0 sm:scale-100" x-transition:leave-end="opacity-0 translate-y-4 sm:translate-y-0 sm:scale-95"
                     class="relative transform overflow-hidden rounded-2xl bg-white px-4 pb-4 pt-5 text-left shadow-xl transition-all sm:my-8 sm:w-full sm:max-w-lg sm:p-6">
                     
                    <div class="flex items-center justify-between border-b border-gray-100 pb-4 mb-4">
                        <h3 class="text-lg font-bold leading-6 text-gray-900">Từ chối phê duyệt tài liệu</h3>
                        <button @click="showRejectionModal = false" class="rounded-lg p-1 text-gray-400 hover:bg-gray-100 hover:text-gray-500 transition-colors">
                            <svg class="h-5 w-5" viewBox="0 0 20 20" fill="currentColor"><path fill-rule="evenodd" d="M4.293 4.293a1 1 0 011.414 0L10 8.586l4.293-4.293a1 1 0 111.414 1.414L11.414 10l4.293 4.293a1 1 0 01-1.414 1.414L10 11.414l-4.293 4.293a1 1 0 01-1.414-1.414L8.586 10 4.293 5.707a1 1 0 010-1.414z" clip-rule="evenodd" /></svg>
                        </button>
                    </div>
                    
                    <div class="space-y-3">
                        <label for="list-rejection-reason" class="block text-sm font-medium text-gray-700">Lý do từ chối <span class="text-rose-500">*</span> <span class="text-gray-400 font-normal">(tối thiểu 10 ký tự)</span></label>
                        <textarea id="list-rejection-reason"
                                  wire:model="rejectionReason" 
                                  rows="4" 
                                  placeholder="Ví dụ: Tài liệu tải lên bị lỗi font chữ, tài liệu vi phạm bản quyền..."
                                  class="block w-full rounded-xl border-0 py-2.5 text-gray-900 shadow-sm ring-1 ring-inset ring-gray-300 placeholder:text-gray-400 focus:ring-2 focus:ring-inset focus:ring-rose-600 sm:text-sm sm:leading-6"></textarea>
                        @error('rejectionReason')
                            <p class="text-sm text-rose-500 font-medium mt-1">{{ $message }}</p>
                        @enderror
                    </div>

                    <div class="mt-6 flex flex-col-reverse sm:flex-row sm:justify-end gap-3">
                        <button type="button" @click="showRejectionModal = false" class="inline-flex w-full sm:w-auto justify-center rounded-xl bg-white px-4 py-2.5 text-sm font-semibold text-gray-900 shadow-sm ring-1 ring-inset ring-gray-300 hover:bg-gray-50 transition-colors">
                            Hủy bỏ
                        </button>
                        <button type="button" wire:click="confirmRejection" class="inline-flex w-full sm:w-auto justify-center rounded-xl bg-rose-600 px-4 py-2.5 text-sm font-semibold text-white shadow-sm hover:bg-rose-500 focus-visible:outline focus-visible:outline-2 focus-visible:outline-offset-2 focus-visible:outline-rose-600 transition-colors">
                            Xác nhận từ chối
                        </button>
                    </div>
                </div>
            </div>
        </template>
    </div>
</div>