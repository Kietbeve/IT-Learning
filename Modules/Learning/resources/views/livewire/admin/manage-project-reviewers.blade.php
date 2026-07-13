<div class="min-h-screen bg-slate-50 py-8 font-sans">
    <div class="container mx-auto px-4 max-w-7xl">
        {{-- Header --}}
        <div class="mb-8 relative overflow-hidden rounded-3xl bg-gradient-to-br from-indigo-600 via-purple-600 to-fuchsia-600 p-8 text-white shadow-xl">
            <div class="absolute inset-0 bg-white opacity-[0.05]" style="background-image: radial-gradient(#fff 1px, transparent 1px); background-size: 24px 24px;"></div>
            <div class="relative z-10 flex items-center justify-between">
                <div>
                    <a href="{{ route('admin.dashboard') }}" class="mb-5 inline-flex items-center gap-2 rounded-full bg-white/20 px-4 py-1.5 text-sm font-bold text-white hover:bg-white/30 backdrop-blur-md border border-white/10 transition-all shadow-sm w-max">
                        <svg class="h-4 w-4" fill="none" stroke="currentColor" viewBox="0 0 24 24">
                            <path stroke-linecap="round" stroke-linejoin="round" stroke-width="2" d="M10 19l-7-7m0 0l7-7m-7 7h18"/>
                        </svg>
                        Quay lại Trang chủ
                    </a>
                    <h1 class="text-3xl md:text-4xl font-extrabold tracking-tight drop-shadow-md">Phân Công Người Chấm</h1>
                    <p class="mt-3 text-indigo-100 max-w-2xl leading-relaxed text-base">Quản lý và phân bổ người chấm điểm cho từng đồ án một cách hiệu quả.</p>
                </div>
            </div>
        </div>

        {{-- Statistics Cards --}}
        {{-- Statistics Cards --}}
        <div class="mb-10 grid grid-cols-1 gap-6 md:grid-cols-4">
            <div class="rounded-3xl bg-white p-6 shadow-sm border border-slate-100 relative overflow-hidden group hover:shadow-md transition-shadow">
                <div class="absolute top-0 right-0 p-4 opacity-5 group-hover:opacity-10 transition-opacity">
                    <svg class="h-24 w-24 text-indigo-600" fill="none" stroke="currentColor" viewBox="0 0 24 24"><path stroke-linecap="round" stroke-linejoin="round" stroke-width="2" d="M9 12h6m-6 4h6m2 5H7a2 2 0 01-2-2V5a2 2 0 012-2h5.586a1 1 0 01.707.293l5.414 5.414a1 1 0 01.293.707V19a2 2 0 01-2 2z"/></svg>
                </div>
                <div class="flex items-center justify-between relative z-10">
                    <div>
                        <p class="text-[11px] font-bold uppercase tracking-wider text-slate-500 mb-1">Tổng đồ án</p>
                        <p class="text-4xl font-black text-slate-800">{{ $overallStats['total_projects'] }}</p>
                    </div>
                    <div class="rounded-2xl bg-indigo-50 p-4 border border-indigo-100 shadow-sm">
                        <svg class="h-7 w-7 text-indigo-600" fill="none" stroke="currentColor" viewBox="0 0 24 24">
                            <path stroke-linecap="round" stroke-linejoin="round" stroke-width="2" d="M9 12h6m-6 4h6m2 5H7a2 2 0 01-2-2V5a2 2 0 012-2h5.586a1 1 0 01.707.293l5.414 5.414a1 1 0 01.293.707V19a2 2 0 01-2 2z"/>
                        </svg>
                    </div>
                </div>
            </div>

            <div class="rounded-3xl bg-white p-6 shadow-sm border border-slate-100 relative overflow-hidden group hover:shadow-md transition-shadow">
                <div class="absolute top-0 right-0 p-4 opacity-5 group-hover:opacity-10 transition-opacity">
                    <svg class="h-24 w-24 text-emerald-600" fill="currentColor" viewBox="0 0 20 20"><path fill-rule="evenodd" d="M10 18a8 8 0 100-16 8 8 0 000 16zm3.707-9.293a1 1 0 00-1.414-1.414L9 10.586 7.707 9.293a1 1 0 00-1.414 1.414l2 2a1 1 0 001.414 0l4-4z"/></svg>
                </div>
                <div class="flex items-center justify-between relative z-10">
                    <div>
                        <p class="text-[11px] font-bold uppercase tracking-wider text-slate-500 mb-1">Đã phân công</p>
                        <p class="text-4xl font-black text-slate-800">{{ $overallStats['with_reviewers'] }}</p>
                    </div>
                    <div class="rounded-2xl bg-emerald-50 p-4 border border-emerald-100 shadow-sm">
                        <svg class="h-7 w-7 text-emerald-600" fill="currentColor" viewBox="0 0 20 20">
                            <path fill-rule="evenodd" d="M10 18a8 8 0 100-16 8 8 0 000 16zm3.707-9.293a1 1 0 00-1.414-1.414L9 10.586 7.707 9.293a1 1 0 00-1.414 1.414l2 2a1 1 0 001.414 0l4-4z"/>
                        </svg>
                    </div>
                </div>
            </div>

            <div class="rounded-3xl bg-white p-6 shadow-sm border border-slate-100 relative overflow-hidden group hover:shadow-md transition-shadow">
                <div class="absolute top-0 right-0 p-4 opacity-5 group-hover:opacity-10 transition-opacity">
                    <svg class="h-24 w-24 text-amber-600" fill="currentColor" viewBox="0 0 20 20"><path fill-rule="evenodd" d="M8.257 3.099c.765-1.36 2.722-1.36 3.486 0l5.58 9.92c.75 1.334-.213 2.98-1.742 2.98H4.42c-1.53 0-2.493-1.646-1.743-2.98l5.58-9.92zM11 13a1 1 0 11-2 0 1 1 0 012 0zm-1-8a1 1 0 00-1 1v3a1 1 0 002 0V6a1 1 0 00-1-1z"/></svg>
                </div>
                <div class="flex items-center justify-between relative z-10">
                    <div>
                        <p class="text-[11px] font-bold uppercase tracking-wider text-slate-500 mb-1">Chưa phân công</p>
                        <p class="text-4xl font-black text-slate-800">{{ $overallStats['without_reviewers'] }}</p>
                    </div>
                    <div class="rounded-2xl bg-amber-50 p-4 border border-amber-100 shadow-sm">
                        <svg class="h-7 w-7 text-amber-600" fill="currentColor" viewBox="0 0 20 20">
                            <path fill-rule="evenodd" d="M8.257 3.099c.765-1.36 2.722-1.36 3.486 0l5.58 9.92c.75 1.334-.213 2.98-1.742 2.98H4.42c-1.53 0-2.493-1.646-1.743-2.98l5.58-9.92zM11 13a1 1 0 11-2 0 1 1 0 012 0zm-1-8a1 1 0 00-1 1v3a1 1 0 002 0V6a1 1 0 00-1-1z"/>
                        </svg>
                    </div>
                </div>
            </div>

            <div class="rounded-3xl bg-white p-6 shadow-sm border border-slate-100 relative overflow-hidden group hover:shadow-md transition-shadow">
                <div class="absolute top-0 right-0 p-4 opacity-5 group-hover:opacity-10 transition-opacity">
                    <svg class="h-24 w-24 text-purple-600" fill="currentColor" viewBox="0 0 20 20"><path d="M9 6a3 3 0 11-6 0 3 3 0 016 0zM17 6a3 3 0 11-6 0 3 3 0 016 0zM12.93 17c.046-.327.07-.66.07-1a6.97 6.97 0 00-1.5-4.33A5 5 0 0119 16v1h-6.07zM6 11a5 5 0 015 5v1H1v-1a5 5 0 015-5z"/></svg>
                </div>
                <div class="flex items-center justify-between relative z-10">
                    <div>
                        <p class="text-[11px] font-bold uppercase tracking-wider text-slate-500 mb-1">Tổng phân công</p>
                        <p class="text-4xl font-black text-slate-800">{{ $overallStats['total_assignments'] }}</p>
                    </div>
                    <div class="rounded-2xl bg-purple-50 p-4 border border-purple-100 shadow-sm">
                        <svg class="h-7 w-7 text-purple-600" fill="currentColor" viewBox="0 0 20 20">
                            <path d="M9 6a3 3 0 11-6 0 3 3 0 016 0zM17 6a3 3 0 11-6 0 3 3 0 016 0zM12.93 17c.046-.327.07-.66.07-1a6.97 6.97 0 00-1.5-4.33A5 5 0 0119 16v1h-6.07zM6 11a5 5 0 015 5v1H1v-1a5 5 0 015-5z"/>
                        </svg>
                    </div>
                </div>
            </div>
        </div>

        {{-- Filters --}}
        {{-- Filters --}}
        <div class="mb-8 rounded-3xl bg-white p-6 shadow-sm border border-slate-100">
            <div class="flex flex-wrap items-center gap-4">
                <div class="flex-1 min-w-[300px] relative">
                    <div class="absolute inset-y-0 left-0 flex items-center pl-4 pointer-events-none">
                        <svg class="w-5 h-5 text-slate-400" fill="none" stroke="currentColor" viewBox="0 0 24 24"><path stroke-linecap="round" stroke-linejoin="round" stroke-width="2" d="M21 21l-6-6m2-5a7 7 0 11-14 0 7 7 0 0114 0z"/></svg>
                    </div>
                    <input 
                        type="text" 
                        wire:model.live.debounce.300ms="searchProject"
                        placeholder="Tìm kiếm đồ án..."
                        class="w-full rounded-2xl border-slate-200 pl-11 pr-4 py-3 text-sm focus:border-indigo-500 focus:ring-indigo-500 bg-slate-50/50 hover:bg-slate-50 transition-colors"
                    />
                </div>

                <select wire:model.live="filterHasReviewers" class="rounded-2xl border-slate-200 px-5 py-3 text-sm focus:border-indigo-500 focus:ring-indigo-500 bg-slate-50/50 hover:bg-slate-50 transition-colors cursor-pointer font-medium text-slate-700">
                    <option value="all">Tất cả đồ án</option>
                    <option value="yes">Đã có người chấm</option>
                    <option value="no">Chưa có người chấm</option>
                </select>

                @if($searchProject || $filterHasReviewers !== 'all')
                    <button wire:click="clearFilters" class="rounded-2xl bg-rose-50 px-5 py-3 text-sm font-bold text-rose-600 hover:bg-rose-100 transition-colors">
                        Xóa bộ lọc
                    </button>
                @endif
            </div>
        </div>

        {{-- Projects List --}}
        {{-- Projects List --}}
        <div class="grid grid-cols-1 gap-6 lg:grid-cols-2">
            @forelse($projects as $project)
                <div class="rounded-3xl bg-white p-6 shadow-sm border border-slate-100 hover:shadow-lg transition-all duration-300 group flex flex-col">
                    <div class="flex items-start justify-between mb-4">
                        <div class="flex-1 pr-4">
                            <div class="inline-flex items-center gap-1.5 rounded-full bg-indigo-50 px-2.5 py-1 text-[10px] font-bold text-indigo-600 uppercase tracking-wider mb-3">
                                <svg class="h-3.5 w-3.5" fill="none" stroke="currentColor" viewBox="0 0 24 24">
                                    <path stroke-linecap="round" stroke-linejoin="round" stroke-width="2" d="M19 11H5m14 0a2 2 0 012 2v6a2 2 0 01-2 2H5a2 2 0 01-2-2v-6a2 2 0 012-2m14 0V9a2 2 0 00-2-2M5 11V9a2 2 0 012-2m0 0V5a2 2 0 012-2h6a2 2 0 012 2v2M7 7h10"/>
                                </svg>
                                Đồ án
                            </div>
                            <h3 class="text-xl font-extrabold text-slate-800 line-clamp-1 group-hover:text-indigo-600 transition-colors">{{ $project->title }}</h3>
                            <p class="mt-2 text-sm text-slate-500 line-clamp-2 leading-relaxed">{{ $project->description }}</p>
                            
                            @if($project->roadmap)
                                <div class="mt-4 flex items-center gap-2 text-xs font-semibold text-slate-600 bg-slate-50 w-max px-3 py-1.5 rounded-xl border border-slate-100">
                                    <svg class="h-4 w-4 text-emerald-500" fill="none" stroke="currentColor" viewBox="0 0 24 24">
                                        <path stroke-linecap="round" stroke-linejoin="round" stroke-width="2" d="M9 20l-5.447-2.724A1 1 0 013 16.382V5.618a1 1 0 011.447-.894L9 7m0 13l6-3m-6 3V7m6 10l4.553 2.276A1 1 0 0021 18.382V7.618a1 1 0 00-.553-.894L15 4m0 13V4m0 0L9 7"/>
                                    </svg>
                                    <span>Lộ trình: {{ $project->roadmap->title }}</span>
                                </div>
                            @endif
                        </div>
                    </div>

                    {{-- Assigned Reviewers --}}
                    <div class="mt-auto pt-4 border-t border-slate-100">
                        <div class="flex items-center justify-between mb-4">
                            <h4 class="text-xs font-bold uppercase tracking-wider text-slate-500">Người chấm được phân công ({{ $project->reviewers->where('is_active', true)->count() }})</h4>
                            <button 
                                wire:click="openAssignModal({{ $project->id }})"
                                class="flex items-center gap-1.5 rounded-xl bg-indigo-50 px-3 py-1.5 text-xs font-bold text-indigo-600 hover:bg-indigo-600 hover:text-white transition-all shadow-sm"
                            >
                                <svg class="h-4 w-4" fill="none" stroke="currentColor" viewBox="0 0 24 24">
                                    <path stroke-linecap="round" stroke-linejoin="round" stroke-width="2" d="M12 6v6m0 0v6m0-6h6m-6 0H6"/>
                                </svg>
                                Thêm người chấm
                            </button>
                        </div>
                        
                        @if($project->reviewers->where('is_active', true)->count() > 0)
                            <div class="flex flex-col gap-3">
                                @foreach($project->reviewers->where('is_active', true) as $reviewer)
                                    <div class="flex items-center justify-between rounded-2xl bg-slate-50 border border-slate-100 p-3 hover:border-indigo-200 transition-colors">
                                        <div class="flex items-center gap-3">
                                            <div class="h-10 w-10 rounded-full bg-gradient-to-br from-indigo-500 to-purple-500 flex items-center justify-center shadow-inner text-white font-bold text-sm">
                                                {{ strtoupper(substr($reviewer->user->name, 0, 2)) }}
                                            </div>
                                            <div>
                                                <p class="font-bold text-slate-800 text-sm">{{ $reviewer->user->name }}</p>
                                                <div class="flex items-center gap-2 mt-0.5">
                                                    <span class="text-[11px] font-medium text-slate-500 bg-white px-2 py-0.5 rounded-lg border border-slate-200">{{ $reviewer->reviews_count }} reviews</span>
                                                    @if($reviewer->can_final_grade)
                                                        <span class="inline-flex items-center gap-1 rounded-lg bg-emerald-100 px-2 py-0.5 text-[11px] font-bold text-emerald-700">
                                                            <svg class="h-3 w-3" fill="currentColor" viewBox="0 0 20 20">
                                                                <path d="M9.049 2.927c.3-.921 1.603-.921 1.902 0l1.07 3.292a1 1 0 00.95.69h3.462c.969 0 1.371 1.24.588 1.81l-2.8 2.034a1 1 0 00-.364 1.118l1.07 3.292c.3.921-.755 1.688-1.54 1.118l-2.8-2.034a1 1 0 00-1.175 0l-2.8 2.034c-.784.57-1.838-.197-1.539-1.118l1.07-3.292a1 1 0 00-.364-1.118L2.98 8.72c-.783-.57-.38-1.81.588-1.81h3.461a1 1 0 00.951-.69l1.07-3.292z"/>
                                                            </svg>
                                                            Chấm cuối
                                                        </span>
                                                    @endif
                                                </div>
                                            </div>
                                        </div>
                                        <div class="flex items-center gap-1.5">
                                            <button 
                                                wire:click="toggleFinalGrade({{ $reviewer->id }})"
                                                class="rounded-xl p-2 {{ $reviewer->can_final_grade ? 'bg-emerald-50 text-emerald-600 border border-emerald-100' : 'bg-white text-slate-400 border border-slate-200' }} hover:shadow-sm transition-all"
                                                title="{{ $reviewer->can_final_grade ? 'Thu hồi quyền chấm điểm cuối' : 'Cấp quyền chấm điểm cuối' }}"
                                            >
                                                <svg class="h-4 w-4" fill="currentColor" viewBox="0 0 20 20">
                                                    <path d="M9.049 2.927c.3-.921 1.603-.921 1.902 0l1.07 3.292a1 1 0 00.95.69h3.462c.969 0 1.371 1.24.588 1.81l-2.8 2.034a1 1 0 00-.364 1.118l1.07 3.292c.3.921-.755 1.688-1.54 1.118l-2.8-2.034a1 1 0 00-1.175 0l-2.8 2.034c-.784.57-1.838-.197-1.539-1.118l1.07-3.292a1 1 0 00-.364-1.118L2.98 8.72c-.783-.57-.38-1.81.588-1.81h3.461a1 1 0 00.951-.69l1.07-3.292z"/>
                                                </svg>
                                            </button>
                                            <button 
                                                wire:click="removeReviewer({{ $project->id }}, {{ $reviewer->user_id }})"
                                                wire:confirm="Bạn có chắc muốn xóa người chấm này?"
                                                class="rounded-xl bg-white border border-slate-200 p-2 text-rose-500 hover:bg-rose-50 hover:border-rose-200 hover:text-rose-600 transition-all shadow-sm"
                                                title="Xóa người chấm"
                                            >
                                                <svg class="h-4 w-4" fill="none" stroke="currentColor" viewBox="0 0 24 24">
                                                    <path stroke-linecap="round" stroke-linejoin="round" stroke-width="2" d="M19 7l-.867 12.142A2 2 0 0116.138 21H7.862a2 2 0 01-1.995-1.858L5 7m5 4v6m4-6v6m1-10V4a1 1 0 00-1-1h-4a1 1 0 00-1 1v3M4 7h16"/>
                                                </svg>
                                            </button>
                                        </div>
                                    </div>
                                @endforeach
                            </div>
                        @else
                            <div class="rounded-2xl border-2 border-dashed border-slate-200 bg-slate-50/50 p-6 text-center">
                                <div class="mx-auto flex h-12 w-12 items-center justify-center rounded-full bg-indigo-50 mb-3">
                                    <svg class="h-6 w-6 text-indigo-400" fill="none" stroke="currentColor" viewBox="0 0 24 24">
                                        <path stroke-linecap="round" stroke-linejoin="round" stroke-width="2" d="M12 4.354a4 4 0 110 5.292M15 21H3v-1a6 6 0 0112 0v1zm0 0h6v-1a6 6 0 00-9-5.197M13 7a4 4 0 11-8 0 4 4 0 018 0z"/>
                                    </svg>
                                </div>
                                <p class="text-sm font-bold text-slate-700">Chưa có người chấm nào</p>
                                <p class="text-xs text-slate-500 mt-1">Bấm nút thêm để phân công người chấm cho đồ án này.</p>
                            </div>
                        @endif
                    </div>
                </div>
            @empty
                <div class="rounded-xl bg-white p-12 text-center shadow-sm">
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
            {{ $projects->links() }}
        </div>
    </div>

    {{-- Assign Reviewer Modal --}}
    @if($showAssignModal)
        <div class="fixed inset-0 z-50 overflow-y-auto" x-data x-init="document.body.style.overflow = 'hidden'; $nextTick(() => { $el.querySelector('.modal-content').scrollIntoView({ behavior: 'smooth', block: 'center' }); })" x-destroy="document.body.style.overflow = 'auto'">
            <div class="flex min-h-screen items-center justify-center px-4 py-8 text-center sm:p-0">
                <div class="fixed inset-0 bg-slate-900/60 backdrop-blur-sm transition-opacity" wire:click="closeAssignModal"></div>

                <div class="modal-content inline-block transform overflow-hidden rounded-3xl bg-white text-left align-bottom shadow-2xl transition-all sm:my-8 sm:w-full sm:max-w-lg sm:align-middle border border-slate-100" style="position: relative; z-index: 51;">
                    <form wire:submit.prevent="assignReviewer">
                        <div class="bg-white px-8 pt-8 pb-6 border-b border-slate-100">
                            <div class="sm:flex sm:items-start">
                                <div class="mx-auto flex h-12 w-12 flex-shrink-0 items-center justify-center rounded-full bg-indigo-50 sm:mx-0 sm:h-12 sm:w-12 border border-indigo-100">
                                    <svg class="h-6 w-6 text-indigo-600" fill="none" stroke="currentColor" viewBox="0 0 24 24">
                                        <path stroke-linecap="round" stroke-linejoin="round" stroke-width="2" d="M18 9v3m0 0v3m0-3h3m-3 0h-3m-2-5a4 4 0 11-8 0 4 4 0 018 0zM3 20a6 6 0 0112 0v1H3v-1z"/>
                                    </svg>
                                </div>
                                <div class="mt-3 w-full text-center sm:ml-4 sm:mt-0 sm:text-left">
                                    <h3 class="text-2xl font-extrabold text-slate-800">Phân công người chấm</h3>
                                    @if($selectedProject)
                                        <p class="mt-2 text-sm text-gray-600">{{ $selectedProject->title }}</p>
                                    @endif

                                    <div class="mt-6 space-y-5">
                                        <div>
                                            <label class="block text-sm font-bold text-slate-700 mb-2">Chọn Người chấm *</label>
                                            <select wire:model="selectedUserId" class="mt-1 block w-full rounded-xl border-slate-200 px-4 py-3 bg-slate-50 focus:border-indigo-500 focus:ring-indigo-500 transition-colors font-medium text-slate-700">
                                                <option value="">-- Chọn người chấm --</option>
                                                @foreach($availableReviewers as $user)
                                                    <option value="{{ $user->id }}">{{ $user->name }} ({{ $user->email }})</option>
                                                @endforeach
                                            </select>
                                            @error('selectedUserId') <span class="mt-2 block text-xs font-semibold text-rose-600">{{ $message }}</span> @enderror
                                        </div>

                                        <div class="flex items-start rounded-xl border border-slate-200 p-4 bg-slate-50">
                                            <div class="flex h-5 items-center">
                                                <input type="checkbox" wire:model="canFinalGrade" class="h-5 w-5 rounded border-slate-300 text-indigo-600 focus:ring-indigo-500 cursor-pointer">
                                            </div>
                                            <div class="ml-3 text-sm">
                                                <label class="font-bold text-slate-700 block mb-1">Có quyền chấm điểm cuối cùng</label>
                                                <p class="text-slate-500 text-xs leading-relaxed">Người chấm này có thể chấm điểm tổng kết sau khi học viên hoàn thành tất cả các bước của dự án.</p>
                                            </div>
                                        </div>
                                    </div>
                                </div>
                            </div>
                        </div>
                        <div class="bg-slate-50/50 px-8 py-5 flex flex-row-reverse gap-3 rounded-b-3xl">
                            <button type="submit" class="inline-flex w-full justify-center rounded-xl bg-indigo-600 px-6 py-2.5 text-sm font-bold text-white shadow-sm hover:bg-indigo-700 transition-colors sm:w-auto">
                                Phân công
                            </button>
                            <button type="button" wire:click="closeAssignModal" class="inline-flex w-full justify-center rounded-xl bg-white border border-slate-200 px-6 py-2.5 text-sm font-bold text-slate-700 shadow-sm hover:bg-slate-50 hover:text-slate-900 transition-colors sm:w-auto">
                                Hủy
                            </button>
                        </div>
                    </form>
                </div>
            </div>
        </div>
    @endif
</div>
