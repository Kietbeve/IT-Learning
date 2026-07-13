<div class="min-h-screen bg-slate-50 py-8 font-sans">
    <div class="container mx-auto px-4 max-w-7xl">
        {{-- Header --}}
        <div class="mb-8 relative overflow-hidden rounded-3xl bg-gradient-to-br from-blue-600 via-indigo-600 to-purple-600 p-8 text-white shadow-xl">
            <div class="absolute inset-0 bg-white opacity-[0.05]" style="background-image: radial-gradient(#fff 1px, transparent 1px); background-size: 24px 24px;"></div>
            <div class="relative z-10">
                <a href="{{ route('admin.dashboard') }}" class="mb-5 inline-flex items-center gap-2 rounded-full bg-white/20 px-4 py-1.5 text-sm font-bold text-white hover:bg-white/30 backdrop-blur-md border border-white/10 transition-all shadow-sm w-max">
                    <svg class="h-4 w-4" fill="none" stroke="currentColor" viewBox="0 0 24 24">
                        <path stroke-linecap="round" stroke-linejoin="round" stroke-width="2" d="M10 19l-7-7m0 0l7-7m-7 7h18"/>
                    </svg>
                    Quay lại Trang chủ
                </a>
                <h1 class="text-3xl md:text-4xl font-extrabold tracking-tight drop-shadow-md">Chấm Đồ Án</h1>
                <p class="mt-3 text-blue-100 max-w-2xl leading-relaxed text-base">Quản lý và đánh giá các dự án được giao cho bạn.</p>
            </div>
        </div>

        {{-- Statistics Cards --}}
        <div class="mb-10 grid grid-cols-1 gap-6 md:grid-cols-4">
            <div class="rounded-3xl bg-white p-6 shadow-sm border border-slate-100 relative overflow-hidden group hover:shadow-md transition-shadow">
                <div class="absolute top-0 right-0 p-4 opacity-5 group-hover:opacity-10 transition-opacity">
                    <svg class="h-24 w-24 text-blue-600" fill="none" stroke="currentColor" viewBox="0 0 24 24"><path stroke-linecap="round" stroke-linejoin="round" stroke-width="2" d="M9 12h6m-6 4h6m2 5H7a2 2 0 01-2-2V5a2 2 0 012-2h5.586a1 1 0 01.707.293l5.414 5.414a1 1 0 01.293.707V19a2 2 0 01-2 2z"/></svg>
                </div>
                <div class="flex items-center justify-between relative z-10">
                    <div>
                        <p class="text-[11px] font-bold uppercase tracking-wider text-slate-500 mb-1">Đồ án được giao</p>
                        <p class="text-4xl font-black text-slate-800">{{ $totalAssigned }}</p>
                    </div>
                    <div class="rounded-2xl bg-blue-50 p-4 border border-blue-100 shadow-sm">
                        <svg class="h-7 w-7 text-blue-600" fill="none" stroke="currentColor" viewBox="0 0 24 24">
                            <path stroke-linecap="round" stroke-linejoin="round" stroke-width="2" d="M9 12h6m-6 4h6m2 5H7a2 2 0 01-2-2V5a2 2 0 012-2h5.586a1 1 0 01.707.293l5.414 5.414a1 1 0 01.293.707V19a2 2 0 01-2 2z"/>
                        </svg>
                    </div>
                </div>
            </div>

            <div class="rounded-3xl bg-white p-6 shadow-sm border border-slate-100 relative overflow-hidden group hover:shadow-md transition-shadow">
                <div class="absolute top-0 right-0 p-4 opacity-5 group-hover:opacity-10 transition-opacity">
                    <svg class="h-24 w-24 text-amber-500" fill="none" stroke="currentColor" viewBox="0 0 24 24"><path stroke-linecap="round" stroke-linejoin="round" stroke-width="2" d="M12 8v4l3 3m6-3a9 9 0 11-18 0 9 9 0 0118 0z"/></svg>
                </div>
                <div class="flex items-center justify-between relative z-10">
                    <div>
                        <p class="text-[11px] font-bold uppercase tracking-wider text-slate-500 mb-1">Đang chờ chấm</p>
                        <p class="text-4xl font-black text-slate-800">{{ $totalPending }}</p>
                    </div>
                    <div class="rounded-2xl bg-amber-50 p-4 border border-amber-100 shadow-sm">
                        <svg class="h-7 w-7 text-amber-500" fill="none" stroke="currentColor" viewBox="0 0 24 24">
                            <path stroke-linecap="round" stroke-linejoin="round" stroke-width="2" d="M12 8v4l3 3m6-3a9 9 0 11-18 0 9 9 0 0118 0z"/>
                        </svg>
                    </div>
                </div>
            </div>

            <div class="rounded-3xl bg-white p-6 shadow-sm border border-slate-100 relative overflow-hidden group hover:shadow-md transition-shadow">
                <div class="absolute top-0 right-0 p-4 opacity-5 group-hover:opacity-10 transition-opacity">
                    <svg class="h-24 w-24 text-emerald-500" fill="none" stroke="currentColor" viewBox="0 0 24 24"><path stroke-linecap="round" stroke-linejoin="round" stroke-width="2" d="M9 12l2 2 4-4m6 2a9 9 0 11-18 0 9 9 0 0118 0z"/></svg>
                </div>
                <div class="flex items-center justify-between relative z-10">
                    <div>
                        <p class="text-[11px] font-bold uppercase tracking-wider text-slate-500 mb-1">Đã chấm</p>
                        <p class="text-4xl font-black text-slate-800">{{ $reviewerStats['total_reviews'] ?? 0 }}</p>
                    </div>
                    <div class="rounded-2xl bg-emerald-50 p-4 border border-emerald-100 shadow-sm">
                        <svg class="h-7 w-7 text-emerald-500" fill="none" stroke="currentColor" viewBox="0 0 24 24">
                            <path stroke-linecap="round" stroke-linejoin="round" stroke-width="2" d="M9 12l2 2 4-4m6 2a9 9 0 11-18 0 9 9 0 0118 0z"/>
                        </svg>
                    </div>
                </div>
            </div>

            <div class="rounded-3xl bg-white p-6 shadow-sm border border-slate-100 relative overflow-hidden group hover:shadow-md transition-shadow">
                <div class="absolute top-0 right-0 p-4 opacity-5 group-hover:opacity-10 transition-opacity">
                    <svg class="h-24 w-24 text-indigo-500" fill="none" stroke="currentColor" viewBox="0 0 24 24"><path stroke-linecap="round" stroke-linejoin="round" stroke-width="2" d="M9 19v-6a2 2 0 00-2-2H5a2 2 0 00-2 2v6a2 2 0 002 2h2a2 2 0 002-2zm0 0V9a2 2 0 012-2h2a2 2 0 012 2v10m-6 0a2 2 0 002 2h2a2 2 0 002-2m0 0V5a2 2 0 012-2h2a2 2 0 012 2v14a2 2 0 01-2 2h-2a2 2 0 01-2-2z"/></svg>
                </div>
                <div class="flex items-center justify-between relative z-10">
                    <div>
                        <p class="text-[11px] font-bold uppercase tracking-wider text-slate-500 mb-1">Tỷ lệ duyệt</p>
                        <p class="text-4xl font-black text-slate-800">{{ $reviewerStats['approval_rate'] ?? 0 }}%</p>
                    </div>
                    <div class="rounded-2xl bg-indigo-50 p-4 border border-indigo-100 shadow-sm">
                        <svg class="h-7 w-7 text-indigo-500" fill="none" stroke="currentColor" viewBox="0 0 24 24">
                            <path stroke-linecap="round" stroke-linejoin="round" stroke-width="2" d="M9 19v-6a2 2 0 00-2-2H5a2 2 0 00-2 2v6a2 2 0 002 2h2a2 2 0 002-2zm0 0V9a2 2 0 012-2h2a2 2 0 012 2v10m-6 0a2 2 0 002 2h2a2 2 0 002-2m0 0V5a2 2 0 012-2h2a2 2 0 012 2v14a2 2 0 01-2 2h-2a2 2 0 01-2-2z"/>
                        </svg>
                    </div>
                </div>
            </div>
        </div>

        {{-- Filters --}}
        <div class="mb-8 rounded-3xl bg-white p-6 shadow-sm border border-slate-100">
            <div class="flex flex-wrap items-center gap-4">
                {{-- Search --}}
                <div class="flex-1 min-w-[300px] relative">
                    <div class="absolute inset-y-0 left-0 flex items-center pl-4 pointer-events-none">
                        <svg class="w-5 h-5 text-slate-400" fill="none" stroke="currentColor" viewBox="0 0 24 24"><path stroke-linecap="round" stroke-linejoin="round" stroke-width="2" d="M21 21l-6-6m2-5a7 7 0 11-14 0 7 7 0 0114 0z"/></svg>
                    </div>
                    <input 
                        type="text" 
                        wire:model.live.debounce.300ms="search"
                        placeholder="Tìm kiếm đồ án..."
                        class="w-full rounded-2xl border-slate-200 pl-11 pr-4 py-3 text-sm focus:border-indigo-500 focus:ring-indigo-500 bg-slate-50/50 hover:bg-slate-50 transition-colors"
                    />
                </div>

                {{-- Filter by Status --}}
                <select wire:model.live="filterStatus" class="rounded-2xl border-slate-200 px-5 py-3 text-sm focus:border-indigo-500 focus:ring-indigo-500 bg-slate-50/50 hover:bg-slate-50 transition-colors cursor-pointer font-medium text-slate-700">
                    <option value="all">Tất cả đồ án</option>
                    <option value="assigned">Được phân công</option>
                    <option value="not_assigned">Chưa phân công</option>
                </select>

                {{-- Filter by Pending --}}
                <label class="flex items-center gap-3 cursor-pointer bg-slate-50/50 hover:bg-slate-50 border border-slate-200 px-5 py-3 rounded-2xl transition-colors">
                    <input type="checkbox" wire:model.live="filterPending" class="h-5 w-5 rounded border-slate-300 text-indigo-600 focus:ring-indigo-500">
                    <span class="text-sm font-bold text-slate-700">Chỉ hiện bài chờ chấm</span>
                </label>

                {{-- Clear Filters --}}
                @if($search || $filterStatus !== 'all' || $filterPending)
                    <button wire:click="clearFilters" class="rounded-2xl bg-rose-50 px-5 py-3 text-sm font-bold text-rose-600 hover:bg-rose-100 transition-colors">
                        Xóa bộ lọc
                    </button>
                @endif
            </div>
        </div>

        {{-- Projects Grid --}}
        <div class="grid grid-cols-1 gap-6 md:grid-cols-2 lg:grid-cols-3">
            @forelse($projectsData as $item)
                @php
                    $project = $item['project'];
                    $isAssigned = $item['is_assigned'];
                    $canAccess = $item['can_access'];
                    $pendingCount = $item['pending_count'];
                    $reviewerInfo = $item['reviewer_info'];
                @endphp

                <div 
                    class="group relative overflow-hidden rounded-3xl bg-white shadow-sm border border-slate-100 transition-all duration-300 flex flex-col {{ $canAccess ? 'cursor-pointer hover:shadow-lg hover:-translate-y-1 hover:border-blue-200' : 'cursor-not-allowed opacity-60' }}"
                    @if($canAccess)
                        wire:click="viewProjectSubmissions({{ $project->id }})"
                    @endif
                >
                    {{-- Status Indicator --}}
                    <div class="absolute right-4 top-4 z-10">
                        @if($isAssigned)
                            <span class="inline-flex items-center gap-1.5 rounded-xl bg-emerald-50 px-3 py-1.5 text-[10px] font-bold uppercase tracking-wider text-emerald-600 border border-emerald-100 shadow-sm">
                                <svg class="h-3.5 w-3.5" fill="currentColor" viewBox="0 0 20 20">
                                    <path fill-rule="evenodd" d="M16.707 5.293a1 1 0 010 1.414l-8 8a1 1 0 01-1.414 0l-4-4a1 1 0 011.414-1.414L8 12.586l7.293-7.293a1 1 0 011.414 0z"/>
                                </svg>
                                Được phân công
                            </span>
                        @else
                            <span class="inline-flex items-center gap-1.5 rounded-xl bg-slate-100 px-3 py-1.5 text-[10px] font-bold uppercase tracking-wider text-slate-500 border border-slate-200">
                                <svg class="h-3.5 w-3.5" fill="currentColor" viewBox="0 0 20 20">
                                    <path fill-rule="evenodd" d="M5 9V7a5 5 0 0110 0v2a2 2 0 012 2v5a2 2 0 01-2 2H5a2 2 0 01-2-2v-5a2 2 0 012-2zm8-2v2H7V7a3 3 0 016 0z"/>
                                </svg>
                                Không có quyền
                            </span>
                        @endif
                    </div>

                    {{-- Card Content --}}
                    <div class="p-6 flex flex-col flex-1 mt-6">
                        <h3 class="mb-2 text-xl font-extrabold text-slate-800 line-clamp-2 {{ $canAccess ? 'group-hover:text-blue-600 transition-colors' : '' }}">
                            {{ $project->title }}
                        </h3>
                        
                        <p class="mb-5 line-clamp-2 text-sm text-slate-500 leading-relaxed">
                            {{ Str::limit($project->description, 100) }}
                        </p>

                        {{-- Roadmap Info --}}
                        @if($project->roadmap)
                            <div class="mb-5 flex items-center gap-2 text-xs font-semibold text-slate-600 bg-slate-50 w-max px-3 py-1.5 rounded-xl border border-slate-100">
                                <svg class="h-4 w-4 text-emerald-500" fill="none" stroke="currentColor" viewBox="0 0 24 24">
                                    <path stroke-linecap="round" stroke-linejoin="round" stroke-width="2" d="M9 20l-5.447-2.724A1 1 0 013 16.382V5.618a1 1 0 011.447-.894L9 7m0 13l6-3m-6 3V7m6 10l4.553 2.276A1 1 0 0021 18.382V7.618a1 1 0 00-.553-.894L15 4m0 13V4m0 0L9 7"/>
                                </svg>
                                <span>{{ $project->roadmap->title }}</span>
                            </div>
                        @endif

                        {{-- Stats --}}
                        @if($isAssigned)
                            <div class="flex items-center justify-between border-t border-slate-100 pt-4 mt-auto">
                                <div class="flex items-center gap-4 text-sm font-bold">
                                    <div class="flex items-center gap-1.5 text-amber-500 bg-amber-50 px-2.5 py-1 rounded-lg border border-amber-100" title="Đang chờ chấm">
                                        <svg class="h-4 w-4" fill="currentColor" viewBox="0 0 20 20">
                                            <path fill-rule="evenodd" d="M10 18a8 8 0 100-16 8 8 0 000 16zm1-12a1 1 0 10-2 0v4a1 1 0 00.293.707l2.828 2.829a1 1 0 101.415-1.415L11 9.586V6z"/>
                                        </svg>
                                        <span>{{ $pendingCount }} chờ</span>
                                    </div>
                                    <div class="flex items-center gap-1.5 text-slate-600 bg-slate-50 px-2.5 py-1 rounded-lg border border-slate-200">
                                        <svg class="h-4 w-4" fill="currentColor" viewBox="0 0 20 20">
                                            <path d="M9 6a3 3 0 11-6 0 3 3 0 016 0zM17 6a3 3 0 11-6 0 3 3 0 016 0zM12.93 17c.046-.327.07-.66.07-1a6.97 6.97 0 00-1.5-4.33A5 5 0 0119 16v1h-6.07zM6 11a5 5 0 015 5v1H1v-1a5 5 0 015-5z"/>
                                        </svg>
                                        <span>{{ $project->submissions->count() }} học viên</span>
                                    </div>
                                </div>

                                @if($reviewerInfo && $reviewerInfo->can_final_grade)
                                    <span class="inline-flex items-center gap-1 text-[10px] font-bold uppercase tracking-wider text-indigo-600 bg-indigo-50 px-2 py-1 rounded-lg border border-indigo-100">
                                        <svg class="h-3 w-3" fill="currentColor" viewBox="0 0 20 20">
                                            <path d="M9.049 2.927c.3-.921 1.603-.921 1.902 0l1.07 3.292a1 1 0 00.95.69h3.462c.969 0 1.371 1.24.588 1.81l-2.8 2.034a1 1 0 00-.364 1.118l1.07 3.292c.3.921-.755 1.688-1.54 1.118l-2.8-2.034a1 1 0 00-1.175 0l-2.8 2.034c-.784.57-1.838-.197-1.539-1.118l1.07-3.292a1 1 0 00-.364-1.118L2.98 8.72c-.783-.57-.38-1.81.588-1.81h3.461a1 1 0 00.951-.69l1.07-3.292z"/>
                                        </svg>
                                        Chấm cuối
                                    </span>
                                @endif
                            </div>
                        @else
                            <div class="border-t border-slate-100 pt-4 mt-auto text-center text-sm font-medium text-slate-400">
                                Bạn chưa được phân công đồ án này
                            </div>
                        @endif
                    </div>

                    {{-- Hover Effect Border --}}
                    @if($canAccess)
                        <div class="absolute inset-0 border-2 border-transparent group-hover:border-blue-500 rounded-3xl pointer-events-none transition-colors"></div>
                    @endif
                </div>
            @empty
                <div class="col-span-full py-12 text-center">
                    <svg class="mx-auto h-12 w-12 text-gray-400" fill="none" stroke="currentColor" viewBox="0 0 24 24">
                        <path stroke-linecap="round" stroke-linejoin="round" stroke-width="2" d="M9 12h6m-6 4h6m2 5H7a2 2 0 01-2-2V5a2 2 0 012-2h5.586a1 1 0 01.707.293l5.414 5.414a1 1 0 01.293.707V19a2 2 0 01-2 2z"/>
                    </svg>
                    <p class="mt-4 text-lg font-medium text-gray-900">Không tìm thấy đồ án nào</p>
                    <p class="mt-2 text-sm text-gray-600">Thử điều chỉnh bộ lọc hoặc tìm kiếm với từ khóa khác</p>
                </div>
            @endforelse
        </div>

        {{-- Pagination --}}
        <div class="mt-8">
            {{ $projectsData->links() }}
        </div>
    </div>
</div>
