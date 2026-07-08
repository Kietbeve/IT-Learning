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



    <!-- Statistics Cards -->
    <div class="grid gap-4 sm:gap-6 grid-cols-2 lg:grid-cols-4">
        <!-- Total -->
        <div class="rounded-2xl border border-gray-200 bg-white p-6 shadow-sm">
            <div class="flex items-center justify-between">
                <p class="text-sm font-medium text-gray-600">Tổng tài liệu</p>
                <span class="rounded-xl bg-gray-50 p-2">
                    <svg class="h-5 w-5 text-gray-600" fill="none" stroke="currentColor" viewBox="0 0 24 24">
                        <path stroke-linecap="round" stroke-linejoin="round" stroke-width="2" d="M9 12h6m-6 4h6m2 5H7a2 2 0 01-2-2V5a2 2 0 012-2h5.586a1 1 0 01.707.293l5.414 5.414a1 1 0 01.293.707V19a2 2 0 01-2 2z"/>
                    </svg>
                </span>
            </div>
            <p class="mt-4 text-3xl font-bold text-gray-900">{{ number_format($totalCount) }}</p>
            <p class="mt-1 text-sm text-gray-500">Tất cả tài liệu</p>
        </div>

        <!-- Approved -->
        <div class="rounded-2xl border border-gray-200 bg-white p-6 shadow-sm">
            <div class="flex items-center justify-between">
                <p class="text-sm font-medium text-gray-600">Đã duyệt</p>
                <span class="rounded-xl bg-green-50 p-2">
                    <svg class="h-5 w-5 text-green-600" fill="none" stroke="currentColor" viewBox="0 0 24 24">
                        <path stroke-linecap="round" stroke-linejoin="round" stroke-width="2" d="M9 12l2 2 4-4m6 2a9 9 0 11-18 0 9 9 0 0118 0z"/>
                    </svg>
                </span>
            </div>
            <p class="mt-4 text-3xl font-bold text-gray-900">{{ number_format($approvedCount) }}</p>
            <p class="mt-1 text-sm text-gray-500">Tài liệu đã phê duyệt</p>
        </div>

        <!-- Pending -->
        <div class="rounded-2xl border border-gray-200 bg-white p-6 shadow-sm">
            <div class="flex items-center justify-between">
                <p class="text-sm font-medium text-gray-600">Chờ duyệt</p>
                <span class="rounded-xl bg-amber-50 p-2">
                    <svg class="h-5 w-5 text-amber-600" fill="none" stroke="currentColor" viewBox="0 0 24 24">
                        <path stroke-linecap="round" stroke-linejoin="round" stroke-width="2" d="M12 8v4l3 3m6-3a9 9 0 11-18 0 9 9 0 0118 0z"/>
                    </svg>
                </span>
            </div>
            <p class="mt-4 text-3xl font-bold text-gray-900">{{ number_format($pendingCount) }}</p>
            <p class="mt-1 text-sm text-gray-500">Cần kiểm duyệt</p>
        </div>

        <!-- Downloads -->
        <div class="rounded-2xl border border-gray-200 bg-white p-6 shadow-sm">
            <div class="flex items-center justify-between">
                <p class="text-sm font-medium text-gray-600">Lượt tải</p>
                <span class="rounded-xl bg-blue-50 p-2">
                    <svg class="h-5 w-5 text-blue-600" fill="none" stroke="currentColor" viewBox="0 0 24 24">
                        <path stroke-linecap="round" stroke-linejoin="round" stroke-width="2" d="M4 16v1a3 3 0 003 3h10a3 3 0 003-3v-1m-4-4l-4 4m0 0l-4-4m4 4V4"/>
                    </svg>
                </span>
            </div>
            <p class="mt-4 text-3xl font-bold text-gray-900">{{ number_format($totalDownloads) }}</p>
            <p class="mt-1 text-sm text-gray-500">Tổng lượt tải</p>
        </div>
    </div>

    <!-- Main List Card -->
    <section class="rounded-2xl border border-gray-100 bg-white shadow-sm overflow-hidden">
        <!-- Filter Header -->
        <div class="p-6 border-b border-gray-200 bg-gray-50/50 space-y-4">
            <div class="flex flex-col md:flex-row md:items-center md:justify-between gap-4">
                <div>
                    <h2 class="text-lg font-semibold text-gray-900">Quản lý toàn bộ tài liệu</h2>
                    <p class="text-sm text-gray-500 mt-1">Tìm kiếm, lọc trạng thái, thay đổi chế độ hiển thị hoặc xóa tài liệu.</p>
                </div>
                <a href="{{ route('admin.documents.create') }}" class="inline-flex items-center justify-center gap-2 rounded-2xl bg-gray-900 hover:bg-gray-800 text-white px-4 py-2.5 text-xs font-bold shadow-lg transition-all shrink-0">
                    <svg class="w-4 h-4" fill="none" stroke="currentColor" viewBox="0 0 24 24"><path stroke-linecap="round" stroke-linejoin="round" stroke-width="2" d="M12 4v16m8-8H4"/></svg>
                    Đăng tài liệu
                </a>
            </div>

            <!-- Navigation Tabs -->
            <div class="flex flex-wrap items-center gap-6 border-b border-gray-200 pb-3">
                <button wire:click="$set('activeTab', 'pending')" 
                        class="inline-flex items-center gap-1.5 pb-2 text-sm font-semibold transition-all border-b-2 {{ $activeTab === 'pending' ? 'border-gray-900 text-gray-900' : 'border-transparent text-gray-500 hover:text-gray-700' }}">
                    Chờ duyệt
                    @if($pendingCount > 0)
                        <span class="inline-flex items-center justify-center px-1.5 py-0.5 text-[10px] font-bold bg-amber-100 text-amber-700 rounded-full">
                            {{ $pendingCount }}
                        </span>
                    @endif
                </button>
                <button wire:click="$set('activeTab', 'approved')" 
                        class="inline-flex items-center gap-1.5 pb-2 text-sm font-semibold transition-all border-b-2 {{ $activeTab === 'approved' ? 'border-gray-900 text-gray-900' : 'border-transparent text-gray-500 hover:text-gray-700' }}">
                    Đã duyệt
                </button>
                <button wire:click="$set('activeTab', 'rejected')" 
                        class="inline-flex items-center gap-1.5 pb-2 text-sm font-semibold transition-all border-b-2 {{ $activeTab === 'rejected' ? 'border-gray-900 text-gray-900' : 'border-transparent text-gray-500 hover:text-gray-700' }}">
                    Bị từ chối
                </button>
                <button wire:click="$set('activeTab', 'unpublished')" 
                        class="inline-flex items-center gap-1.5 pb-2 text-sm font-semibold transition-all border-b-2 {{ $activeTab === 'unpublished' ? 'border-gray-900 text-gray-900' : 'border-transparent text-gray-500 hover:text-gray-700' }}">
                    Đã gỡ
                </button>
                <button wire:click="$set('activeTab', 'deleted')" 
                        class="inline-flex items-center gap-1.5 pb-2 text-sm font-semibold transition-all border-b-2 {{ $activeTab === 'deleted' ? 'border-gray-900 text-gray-900' : 'border-transparent text-gray-500 hover:text-gray-700' }}">
                    Đã xóa
                </button>
            </div>

            <div class="flex flex-col sm:flex-row items-stretch sm:items-center gap-3 w-full pt-1">
                <!-- Category Filter -->
                <select wire:model.live="categoryFilter" aria-label="Lọc theo danh mục" class="w-full sm:w-auto rounded-2xl border border-gray-200 bg-white px-4 py-2.5 text-sm text-gray-900 focus:border-gray-400 focus:outline-none">
                    <option value="all">Tất cả danh mục</option>
                    @foreach($categories as $cat)
                        <option value="{{ $cat->id }}">{{ $cat->name }}</option>
                    @endforeach
                </select>

                <!-- Search Input -->
                <div class="relative w-full sm:flex-1">
                    <input type="search" 
                           id="search-admin-documents"
                           aria-label="Tìm kiếm tài liệu"
                           wire:model.live.debounce.300ms="search" 
                           placeholder="Tìm kiếm tài liệu..." 
                           class="w-full rounded-2xl border border-gray-200 bg-white px-4 py-2.5 pl-10 text-sm text-gray-900 focus:border-gray-400 focus:outline-none" />
                    <span class="absolute inset-y-0 left-3.5 inline-flex items-center text-gray-400">
                        <svg viewBox="0 0 24 24" class="h-4.5 w-4.5" fill="none" stroke="currentColor" stroke-width="2"><circle cx="11" cy="11" r="8" /><path d="M21 21l-4.35-4.35" /></svg>
                    </span>
                </div>
            </div>
        </div>

        <!-- Table Content with Loading State -->
        <div wire:loading.class="opacity-60 transition-opacity duration-200" class="transition-opacity duration-200">
            <!-- Mobile Card View (hidden on md and up) -->
            <div class="block md:hidden divide-y divide-slate-100">
            @forelse($documents as $doc)
                <div class="p-4 space-y-3">
                    <div class="flex items-start justify-between gap-4">
                        <div class="space-y-1.5 flex-1 min-w-0">
                            <a href="{{ route('admin.moderation.documents.show', ['id' => $doc->id, 'from' => 'list']) }}" class="hover:text-blue-600 font-bold text-gray-900 block leading-tight text-base truncate" title="{{ $doc->pending_version_data->title ?? $doc->title }}">
                                {{ \Illuminate\Support\Str::limit($doc->pending_version_data->title ?? $doc->title, 45) }}
                            </a>
                            
                            <div class="flex flex-wrap items-center gap-2 text-[10px] text-gray-400 font-semibold mt-2">
                                <span class="inline-flex items-center rounded bg-gray-100 px-1.5 py-0.5 uppercase text-gray-700 font-bold">
                                    {{ $doc->file_type }}
                                </span>
                                @if($activeTab === 'pending')
                                    @if(isset($doc->badge_type))
                                        @if($doc->badge_type === 'new')
                                            <span class="inline-flex items-center gap-1 rounded-lg bg-emerald-100 px-2 py-0.5 text-emerald-700 font-bold text-[10px]">
                                                🆕 Mới
                                            </span>
                                        @elseif($doc->badge_type === 'update')
                                            <span class="inline-flex items-center gap-1 rounded-lg bg-blue-100 px-2 py-0.5 text-blue-700 font-bold text-[10px]">
                                                📝 Cập nhật
                                            </span>
                                        @endif
                                    @endif
                                @endif
                                <span>{{ number_format($doc->file_size / 1024 / 1024, 2) }} MB</span>
                                <span>•</span>
                                <span class="text-gray-500">{{ $doc->category?->name ?? 'Mặc định' }}</span>
                            </div>

                        </div>

                        <!-- Price tag -->
                        <div class="shrink-0 text-right">
                            @if($doc->product)
                                @if($doc->product->sale_price)
                                    <span class="text-blue-600 font-bold text-sm">{{ number_format($doc->product->sale_price) }}đ</span>
                                    <div class="text-xs text-gray-400 line-through">{{ number_format($doc->product->price) }}đ</div>
                                @else
                                    <span class="text-blue-600 font-bold text-sm">{{ number_format($doc->product->price) }}đ</span>
                                @endif
                            @else
                                <span class="text-emerald-600 font-semibold bg-emerald-50 px-2 py-0.5 rounded-xl text-xs">Miễn phí</span>
                            @endif
                        </div>
                    </div>

                    <!-- Meta info (Author) -->
                    <div class="flex flex-wrap items-center justify-between gap-2 text-[11px] text-gray-500 bg-gray-50 p-2.5 rounded-2xl">
                        <div>Đăng bởi: <span class="font-semibold text-gray-700">{{ $doc->author?->name ?? 'Uploader' }}</span></div>
                    </div>

                    <!-- Actions & Status -->
                    <div class="flex items-center justify-between gap-4 pt-1">
                        <div>
                            @if($doc->trashed())
                                <span class="inline-flex rounded-full bg-gray-100 px-2.5 py-1 text-xs font-semibold text-gray-600">
                                    Đã xóa mềm
                                </span>
                            @elseif($activeTab === 'pending' && $doc->pendingVersion)
                                <span class="inline-flex rounded-full bg-amber-100 px-2.5 py-1 text-xs font-semibold text-amber-800">
                                    Chờ duyệt
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
                            @elseif($doc->status === 'unpublished')
                                <span class="inline-flex rounded-full bg-gray-100 px-2.5 py-1 text-xs font-semibold text-gray-800">
                                    Đã gỡ
                                </span>
                            @else
                                <span class="inline-flex rounded-full bg-gray-100 px-2.5 py-1 text-xs font-semibold text-gray-800">
                                    Bản nháp
                                </span>
                            @endif
                        </div>

                        <div class="flex flex-col items-stretch gap-1.5">
                            @if($activeTab === 'pending' && $doc->pendingVersion)
                                <!-- Approve button -->
                                <button wire:click="approve({{ $doc->id }})" 
                                        class="inline-flex items-center justify-center gap-1 rounded-lg bg-emerald-50 hover:bg-emerald-100 text-emerald-700 px-2.5 py-1.5 text-[11px] font-bold transition-all shadow-sm">
                                    <svg class="w-3 h-3" fill="none" stroke="currentColor" viewBox="0 0 24 24"><path stroke-linecap="round" stroke-linejoin="round" stroke-width="2.5" d="M5 13l4 4L19 7"/></svg>
                                    Duyệt
                                </button>
                                
                                <!-- Reject button -->
                                <button wire:click="openRejectionModal({{ $doc->id }})" 
                                        class="inline-flex items-center justify-center gap-1 rounded-lg bg-rose-50 hover:bg-rose-100 text-rose-600 px-2.5 py-1.5 text-[11px] font-bold transition-all shadow-sm">
                                    <svg class="w-3 h-3" fill="none" stroke="currentColor" viewBox="0 0 24 24"><path stroke-linecap="round" stroke-linejoin="round" stroke-width="2.5" d="M6 18L18 6M6 6l12 12"/></svg>
                                    Từ chối
                                </button>
                            @else
                                <button wire:click="toggleVisibility({{ $doc->id }})" 
                                        @if($doc->trashed()) disabled @endif
                                        class="inline-flex items-center gap-1 px-3 py-1.5 rounded-xl text-xs font-bold uppercase tracking-wider transition-colors {{ $doc->visibility === 'public' ? 'bg-blue-50 text-blue-600 hover:bg-blue-100' : 'bg-gray-50 text-gray-400 hover:bg-gray-100' }}">
                                    {{ $doc->visibility === 'public' ? 'Công khai' : 'Riêng tư' }}
                                </button>
                                @if(!$doc->trashed())
                                    <button onclick="confirm('Bạn có chắc chắn muốn xóa mềm tài liệu này không?') || event.stopImmediatePropagation()" 
                                            wire:click="deleteDocument({{ $doc->id }})" 
                                            class="inline-flex rounded-xl bg-rose-50 hover:bg-rose-100 text-rose-600 px-3 py-1.5 text-xs font-bold transition-all shadow-sm">
                                        Xóa
                                    </button>
                                @endif
                            @endif
                        </div>
                    </div>
                </div>
            @empty
                <div class="p-8 text-center text-gray-400">
                    Không tìm thấy tài liệu nào khớp với bộ lọc.
                </div>
            @endforelse
        </div>

        <!-- Desktop Table View (hidden on mobile) -->
        <div class="hidden md:block overflow-x-auto">
            <table class="w-full text-left border-collapse table-fixed">
                <thead>
                    <tr class="border-b border-gray-200 text-xs font-semibold uppercase tracking-wider text-gray-500 bg-gray-50/70">
                        <th class="px-4 py-4 cursor-pointer hover:bg-gray-100 transition-colors w-[32%]" wire:click="sortBy('title')">
                            Tài liệu
                            @if($sortField === 'title')
                                <span class="ml-1 text-[10px]">{{ $sortDirection === 'asc' ? '▲' : '▼' }}</span>
                            @endif
                        </th>
                        <th class="px-4 py-4 hidden md:table-cell w-[15%]">Tác giả</th>
                        <th class="px-4 py-4 w-[10%]">Hình thức</th>
                        <th class="px-4 py-4 w-[10%]">Trạng thái</th>
                        <th class="px-4 py-4 hidden sm:table-cell w-[10%]">Hiển thị</th>
                        <th class="px-4 py-4 hidden lg:table-cell cursor-pointer hover:bg-gray-100 transition-colors w-[10%]" wire:click="sortBy('created_at')">
                            Ngày tạo
                            @if($sortField === 'created_at')
                                <span class="ml-1 text-[10px]">{{ $sortDirection === 'asc' ? '▲' : '▼' }}</span>
                            @endif
                        </th>
                        <th class="px-4 py-4 text-right w-[13%]">Thao tác</th>
                    </tr>
                </thead>
                <tbody class="divide-y divide-slate-100 text-sm text-gray-700">
                    @forelse($documents as $doc)
                        <tr class="hover:bg-gray-50/50 transition-colors">
                            <td class="px-4 py-4 overflow-hidden">
                                <div class="space-y-1.5">
                                    <a href="{{ route('admin.moderation.documents.show', ['id' => $doc->id, 'from' => 'list']) }}" class="hover:text-blue-600 font-bold text-gray-900 block leading-tight truncate" title="{{ $doc->pending_version_data->title ?? $doc->title }}">
                                        {{ \Illuminate\Support\Str::limit($doc->pending_version_data->title ?? $doc->title, 45) }}
                                    </a>
                                    
                                    {{-- Show live version info below title only when approved live version exists --}}
                                    @if($activeTab === 'pending' && ($doc->badge_type ?? '') === 'update' && isset($doc->live_version))
                                        <a href="{{ route('admin.moderation.documents.show', ['id' => $doc->id, 'from' => 'list', 'version' => 'current']) }}" 
                                           target="_blank"
                                           class="inline-flex items-center gap-1.5 text-[10px] text-gray-400 bg-emerald-50 border border-emerald-200 rounded-lg px-2 py-1 mb-1 hover:bg-emerald-100 transition-colors group">
                                            <svg class="w-3 h-3 text-emerald-500 shrink-0" fill="none" stroke="currentColor" viewBox="0 0 24 24"><circle cx="12" cy="12" r="9" stroke-width="2"/><path stroke-linecap="round" stroke-linejoin="round" stroke-width="2" d="M9 12l2 2 4-4"/></svg>
                                            <span class="text-gray-500">Bản gốc đang live:</span>
                                            <span class="font-semibold text-emerald-700 truncate max-w-[180px] group-hover:underline" title="{{ $doc->live_version->title }}">{{ \Illuminate\Support\Str::limit($doc->live_version->title, 35) }}</span>
                                            <svg class="w-2.5 h-2.5 text-emerald-400 shrink-0" fill="none" stroke="currentColor" viewBox="0 0 24 24"><path stroke-linecap="round" stroke-linejoin="round" stroke-width="2" d="M10 6H6a2 2 0 00-2 2v10a2 2 0 002 2h10a2 2 0 002-2v-4M14 4h6m0 0v6m0-6L10 14"/></svg>
                                        </a>
                                    @endif

                                    <div class="flex flex-wrap items-center gap-2 text-[10px] text-gray-400 font-semibold mt-2">
                                        @if($activeTab === 'pending' && isset($doc->badge_type))
                                            <span class="inline-flex items-center gap-1 rounded-lg px-2 py-0.5 font-bold text-[10px] {{ $doc->badge_type === 'new' ? 'bg-emerald-100 text-emerald-700' : 'bg-blue-100 text-blue-700' }}">
                                                {{ $doc->badge_type === 'new' ? '🆕 Mới' : '📝 Cập nhật' }}
                                            </span>
                                        @endif
                                        <span class="text-gray-500">Danh mục: {{ $doc->category?->name ?? 'Mặc định' }}</span>
                                    </div>

                                    <!-- Author info shown on mobile/tablet instead of dedicated column -->
                                    <div class="md:hidden text-[10px] text-gray-400">
                                        Đăng bởi: <span class="font-medium text-gray-600">{{ $doc->author?->name ?? 'Uploader' }}</span>
                                    </div>
                                </div>
                            </td>
                            <td class="px-4 py-4 hidden md:table-cell">
                                <div class="font-medium text-gray-900 truncate max-w-[150px]" title="{{ $doc->author?->name ?? 'Uploader' }}">{{ $doc->author?->name ?? 'Uploader' }}</div>
                                <div class="text-[10px] text-gray-400 mt-0.5 truncate max-w-[150px]" title="{{ $doc->author?->email ?? '' }}">{{ $doc->author?->email ?? '' }}</div>
                            </td>
                            <td class="px-4 py-4 whitespace-nowrap">
                                @if($doc->product && $doc->product->price > 0)
                                    @if($doc->product->sale_price)
                                        <span class="text-blue-600 font-bold">{{ number_format($doc->product->sale_price) }}đ</span>
                                        <div class="text-xs text-gray-400 line-through">{{ number_format($doc->product->price) }}đ</div>
                                    @else
                                        <span class="text-blue-600 font-bold">{{ number_format($doc->product->price) }}đ</span>
                                    @endif
                                @else
                                    <span class="text-emerald-600 font-semibold bg-emerald-50 px-2 py-1 rounded-xl text-xs">Miễn phí</span>
                                @endif
                            </td>
                            <td class="px-4 py-4 whitespace-nowrap">
                                @if($doc->trashed())
                                    <span class="inline-flex rounded-full bg-gray-100 px-2.5 py-1 text-xs font-semibold text-gray-600">
                                        Đã xóa mềm
                                    </span>
                                @elseif($activeTab === 'pending' && $doc->pendingVersion)
                                    <span class="inline-flex rounded-full bg-amber-100 px-2.5 py-1 text-xs font-semibold text-amber-800">
                                        Chờ duyệt
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
                                @elseif($doc->status === 'unpublished')
                                    <span class="inline-flex rounded-full bg-gray-100 px-2.5 py-1 text-xs font-semibold text-gray-800">
                                        Đã gỡ
                                    </span>
                                @else
                                    <span class="inline-flex rounded-full bg-gray-100 px-2.5 py-1 text-xs font-semibold text-gray-800">
                                        Bản nháp
                                    </span>
                                @endif
                            </td>
                            <td class="px-4 py-4 hidden sm:table-cell whitespace-nowrap">
                                <button wire:click="toggleVisibility({{ $doc->id }})" 
                                        @if($doc->trashed()) disabled @endif
                                        class="inline-flex items-center gap-1.5 px-3 py-1.5 rounded-xl text-xs font-bold uppercase tracking-wider transition-colors {{ $doc->visibility === 'public' ? 'bg-blue-50 text-blue-600 hover:bg-blue-100' : 'bg-gray-50 text-gray-400 hover:bg-gray-100' }}">
                                    {{ $doc->visibility === 'public' ? 'Công khai' : 'Riêng tư' }}
                                </button>
                            </td>
                            <td class="px-4 py-4 hidden lg:table-cell text-gray-500 font-medium whitespace-nowrap">
                                {{ $doc->created_at->format('d/m/Y') }}
                                <span class="block text-[10px] text-gray-400 mt-0.5">{{ $doc->created_at->format('H:i') }}</span>
                            </td>
                            <td class="px-4 py-4 text-right whitespace-nowrap">
                                <div class="flex flex-col items-end justify-end gap-1.5">
                                    @if($activeTab === 'pending' && $doc->pendingVersion)
                                        <!-- Approve button -->
                                        <button wire:click="approve({{ $doc->id }})" 
                                                class="inline-flex items-center justify-center gap-1 rounded-lg bg-emerald-50 hover:bg-emerald-100 text-emerald-700 px-2.5 py-1.5 text-[11px] font-bold transition-all shadow-sm min-w-[90px]">
                                            <svg class="w-3 h-3" fill="none" stroke="currentColor" viewBox="0 0 24 24"><path stroke-linecap="round" stroke-linejoin="round" stroke-width="2.5" d="M5 13l4 4L19 7"/></svg>
                                            Duyệt
                                        </button>
                                        
                                        <!-- Reject button -->
                                        <button wire:click="openRejectionModal({{ $doc->id }})" 
                                                class="inline-flex items-center justify-center gap-1 rounded-lg bg-rose-50 hover:bg-rose-100 text-rose-600 px-2.5 py-1.5 text-[11px] font-bold transition-all shadow-sm min-w-[90px]">
                                            <svg class="w-3 h-3" fill="none" stroke="currentColor" viewBox="0 0 24 24"><path stroke-linecap="round" stroke-linejoin="round" stroke-width="2.5" d="M6 18L18 6M6 6l12 12"/></svg>
                                            Từ chối
                                        </button>
                                    @else
                                        <!-- Visibility toggle on smallest screens where the column is hidden -->
                                        <button wire:click="toggleVisibility({{ $doc->id }})" 
                                                @if($doc->trashed()) disabled @endif
                                                class="sm:hidden inline-flex rounded-xl bg-gray-100 hover:bg-gray-200 text-gray-700 px-2.5 py-2 text-xs font-semibold">
                                            {{ $doc->visibility === 'public' ? 'Ẩn' : 'Hiện' }}
                                        </button>

                                        @if(!$doc->trashed())
                                            <button onclick="confirm('Bạn có chắc chắn muốn xóa mềm tài liệu này không?') || event.stopImmediatePropagation()" 
                                                    wire:click="deleteDocument({{ $doc->id }})" 
                                                    class="inline-flex rounded-xl bg-rose-50 hover:bg-rose-100 text-rose-600 px-3.5 py-2 text-xs font-bold transition-all shadow-sm">
                                                Xóa
                                            </button>
                                        @endif
                                    @endif
                                </div>
                            </td>
                        </tr>
                    @empty
                        <tr>
                            <td colspan="7" class="px-4 py-12 text-center text-gray-400">
                                <svg class="w-12 h-12 text-gray-300 mx-auto mb-3" fill="none" stroke="currentColor" viewBox="0 0 24 24"><path stroke-linecap="round" stroke-linejoin="round" stroke-width="1.5" d="M20 13V6a2 2 0 00-2-2H6a2 2 0 00-2 2v7m16 0v5a2 2 0 01-2 2H6a2 2 0 01-2-2v-5m16 0h-2.586a1 1 0 00-.707.293l-2.414 2.414a1 1 0 01-.707.293h-3.172a1 1 0 01-.707-.293l-2.414-2.414A1 1 0 006.586 13H4"/></svg>
                                Không tìm thấy tài liệu nào khớp với bộ lọc.
                            </td>
                        </tr>
                    @endforelse
                </tbody>
            </table>
        </div>
        </div>

        <!-- Pagination -->
        @if($documents->hasPages())
            <div class="p-6 border-t border-gray-200 bg-gray-50/50">
                {{ $documents->links(data: ['scrollTo' => '#admin-document-list']) }}
            </div>
        @endif
    </section>

    <!-- Rejection Modal -->
    <div x-data="{ showRejectionModal: @entangle('showRejectionModal') }" x-effect="document.body.style.overflow = showRejectionModal ? 'hidden' : ''">
        <template x-teleport="body">
            <div x-show="showRejectionModal" 
                 class="fixed inset-0 z-[100] overflow-y-auto flex items-center justify-center p-4 bg-gray-900/60 backdrop-blur-sm" 
                 style="display: none;"
                 x-transition>
                <div class="bg-white rounded-3xl max-w-lg w-full border border-gray-200 shadow-2xl p-6 space-y-6" @click.away="showRejectionModal = false">
                <div class="flex items-center justify-between border-b border-gray-100 pb-3">
                    <h3 class="text-lg font-bold text-gray-900">Từ chối phê duyệt tài liệu</h3>
                    <button @click="showRejectionModal = false" class="text-gray-400 hover:text-gray-600">&times;</button>
                </div>
                
                <div class="space-y-2">
                    <label for="list-rejection-reason" class="block text-sm font-semibold text-gray-700">Lý do từ chối <span class="text-red-500">*</span> (tối thiểu 10 ký tự):</label>
                    <textarea id="list-rejection-reason"
                              wire:model="rejectionReason" 
                              rows="4" 
                              placeholder="Ví dụ: Tài liệu tải lên bị lỗi font chữ, tài liệu có bản quyền, file bị hỏng không giải nén được..."
                              class="w-full rounded-2xl border border-gray-200 bg-gray-50 px-4 py-3 text-sm text-gray-900 focus:border-gray-400 focus:outline-none"></textarea>
                    @error('rejectionReason')
                        <span class="text-xs text-red-500 font-medium">{{ $message }}</span>
                    @enderror
                </div>

                <div class="flex justify-end gap-3 pt-3 border-t border-gray-100">
                    <button type="button" @click="showRejectionModal = false" class="rounded-xl border border-gray-200 hover:bg-gray-50 px-4 py-2.5 text-xs font-semibold text-gray-700 transition-colors">
                        Hủy bỏ
                    </button>
                    <button type="button" wire:click="confirmRejection" class="rounded-xl bg-rose-600 hover:bg-rose-500 text-white px-5 py-2.5 text-xs font-semibold shadow-sm transition-colors">
                        Xác nhận từ chối
                    </button>
                </div>
            </div>
        </template>
    </div>
</div>

