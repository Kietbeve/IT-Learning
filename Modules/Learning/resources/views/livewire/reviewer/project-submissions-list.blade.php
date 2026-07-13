<div class="min-h-screen bg-slate-50 py-8 font-sans text-slate-900">
    <div class="container mx-auto px-4 max-w-7xl">
        {{-- Back Button --}}
        <div class="mb-6">
            <a href="{{ route(auth()->user()->hasRole('admin') ? 'admin.reviewer.submissions.index' : 'contributor.reviewer.submission.index') }}" 
               class="group inline-flex items-center gap-2 text-sm font-medium text-slate-500 hover:text-indigo-600 transition-colors">
                <span class="flex h-8 w-8 items-center justify-center rounded-full bg-white shadow-sm group-hover:shadow transition-shadow">
                    <svg class="h-4 w-4" fill="none" stroke="currentColor" viewBox="0 0 24 24">
                        <path stroke-linecap="round" stroke-linejoin="round" stroke-width="2" d="M15 19l-7-7 7-7"/>
                    </svg>
                </span>
                Quay lại danh sách đồ án
            </a>
        </div>

        {{-- Project Header --}}
        <div class="relative mb-8 overflow-hidden rounded-3xl bg-gradient-to-br from-indigo-600 via-purple-600 to-fuchsia-600 p-8 shadow-xl text-white">
            <div class="absolute inset-0 bg-white opacity-[0.03]" style="background-image: radial-gradient(#fff 1px, transparent 1px); background-size: 20px 20px;"></div>
            <div class="relative z-10 flex flex-col md:flex-row md:items-start md:justify-between gap-6">
                <div class="flex-1">
                    <div class="inline-flex items-center gap-2 rounded-full bg-white/20 px-3 py-1 text-sm font-medium backdrop-blur-md mb-4 border border-white/20 shadow-sm">
                        <svg class="h-4 w-4" fill="none" stroke="currentColor" viewBox="0 0 24 24">
                            <path stroke-linecap="round" stroke-linejoin="round" stroke-width="2" d="M19 11H5m14 0a2 2 0 012 2v6a2 2 0 01-2 2H5a2 2 0 01-2-2v-6a2 2 0 012-2m14 0V9a2 2 0 00-2-2M5 11V9a2 2 0 012-2m0 0V5a2 2 0 012-2h6a2 2 0 012 2v2M7 7h10"/>
                        </svg>
                        Project Submissions
                    </div>
                    <h1 class="text-3xl md:text-4xl font-extrabold tracking-tight drop-shadow-md">{{ $project->title }}</h1>
                    <p class="mt-3 text-indigo-50 max-w-2xl leading-relaxed text-base">{{ $project->description }}</p>
                    @if($project->roadmap)
                        <div class="mt-6 inline-flex items-center gap-2 rounded-xl bg-black/20 px-4 py-2.5 text-sm font-medium backdrop-blur-sm border border-white/10 shadow-inner">
                            <svg class="h-5 w-5 text-indigo-200" fill="none" stroke="currentColor" viewBox="0 0 24 24">
                                <path stroke-linecap="round" stroke-linejoin="round" stroke-width="2" d="M9 20l-5.447-2.724A1 1 0 013 16.382V5.618a1 1 0 011.447-.894L9 7m0 13l6-3m-6 3V7m6 10l4.553 2.276A1 1 0 0021 18.382V7.618a1 1 0 00-.553-.894L15 4m0 13V4m0 0L9 7"/>
                            </svg>
                            <span>Lộ trình: <span class="text-white font-bold">{{ $project->roadmap->title }}</span></span>
                        </div>
                    @endif
                </div>
                <div class="flex flex-col sm:flex-row items-center gap-3 shrink-0">
                    @if(auth()->user()->hasRole('admin'))
                        <a href="{{ route('admin.learning.projects.steps', $project->id) }}" 
                           class="group inline-flex items-center gap-2 rounded-xl bg-white px-5 py-3 text-sm font-bold text-indigo-600 shadow-lg hover:bg-indigo-50 hover:scale-105 hover:shadow-xl transition-all duration-300">
                            <svg class="h-5 w-5 transition-transform group-hover:rotate-90" fill="none" stroke="currentColor" viewBox="0 0 24 24">
                                <path stroke-linecap="round" stroke-linejoin="round" stroke-width="2" d="M10.325 4.317c.426-1.756 2.924-1.756 3.35 0a1.724 1.724 0 002.573 1.066c1.543-.94 3.31.826 2.37 2.37a1.724 1.724 0 001.065 2.572c1.756.426 1.756 2.924 0 3.35a1.724 1.724 0 00-1.066 2.573c.94 1.543-.826 3.31-2.37 2.37a1.724 1.724 0 00-2.572 1.065c-.426 1.756-2.924 1.756-3.35 0a1.724 1.724 0 00-2.573-1.066c-1.543.94-3.31-.826-2.37-2.37a1.724 1.724 0 00-1.065-2.572c-1.756-.426-1.756-2.924 0-3.35a1.724 1.724 0 001.066-2.573c-.94-1.543.826-3.31 2.37-2.37.996.608 2.296.07 2.572-1.065z"/>
                                <path stroke-linecap="round" stroke-linejoin="round" stroke-width="2" d="M15 12a3 3 0 11-6 0 3 3 0 016 0z"/>
                            </svg>
                            Cấu hình Steps
                        </a>
                    @endif
                    @if($reviewerAssignment && $reviewerAssignment->can_final_grade)
                        <span class="inline-flex items-center gap-2 rounded-xl bg-emerald-500/30 px-5 py-3 text-sm font-bold text-emerald-50 backdrop-blur-md border border-emerald-400/40 shadow-inner">
                            <svg class="h-5 w-5 text-emerald-300" fill="currentColor" viewBox="0 0 20 20">
                                <path d="M9.049 2.927c.3-.921 1.603-.921 1.902 0l1.07 3.292a1 1 0 00.95.69h3.462c.969 0 1.371 1.24.588 1.81l-2.8 2.034a1 1 0 00-.364 1.118l1.07 3.292c.3.921-.755 1.688-1.54 1.118l-2.8-2.034a1 1 0 00-1.175 0l-2.8 2.034c-.784.57-1.838-.197-1.539-1.118l1.07-3.292a1 1 0 00-.364-1.118L2.98 8.72c-.783-.57-.38-1.81.588-1.81h3.461a1 1 0 00.951-.69l1.07-3.292z"/>
                            </svg>
                            Có quyền chấm điểm cuối
                        </span>
                    @endif
                </div>
            </div>
        </div>

        {{-- Statistics Cards --}}
        <div class="mb-8 grid grid-cols-1 gap-6 md:grid-cols-4">
            {{-- Card 1 --}}
            <div class="group rounded-3xl bg-white p-6 shadow-sm border border-slate-100 hover:shadow-xl hover:border-blue-100 transition-all duration-300 relative overflow-hidden">
                <div class="absolute -right-6 -top-6 h-24 w-24 rounded-full bg-gradient-to-br from-blue-100 to-blue-50 opacity-50 group-hover:scale-150 transition-transform duration-500"></div>
                <div class="relative z-10 flex items-center justify-between">
                    <div>
                        <p class="text-sm font-semibold text-slate-500 uppercase tracking-wider">Tổng học viên</p>
                        <p class="mt-3 text-4xl font-extrabold text-slate-900 group-hover:text-blue-600 transition-colors">{{ $projectStats['total'] }}</p>
                    </div>
                    <div class="rounded-2xl bg-gradient-to-br from-blue-500 to-blue-600 p-4 shadow-lg shadow-blue-500/30">
                        <svg class="h-6 w-6 text-white" fill="currentColor" viewBox="0 0 20 20">
                            <path d="M9 6a3 3 0 11-6 0 3 3 0 016 0zM17 6a3 3 0 11-6 0 3 3 0 016 0zM12.93 17c.046-.327.07-.66.07-1a6.97 6.97 0 00-1.5-4.33A5 5 0 0119 16v1h-6.07zM6 11a5 5 0 015 5v1H1v-1a5 5 0 015-5z"/>
                        </svg>
                    </div>
                </div>
            </div>

            {{-- Card 2 --}}
            <div class="group rounded-3xl bg-white p-6 shadow-sm border border-slate-100 hover:shadow-xl hover:border-amber-100 transition-all duration-300 relative overflow-hidden">
                <div class="absolute -right-6 -top-6 h-24 w-24 rounded-full bg-gradient-to-br from-amber-100 to-amber-50 opacity-50 group-hover:scale-150 transition-transform duration-500"></div>
                <div class="relative z-10 flex items-center justify-between">
                    <div>
                        <p class="text-sm font-semibold text-slate-500 uppercase tracking-wider">Đang chờ chấm</p>
                        <p class="mt-3 text-4xl font-extrabold text-slate-900 group-hover:text-amber-500 transition-colors">{{ $projectStats['pending'] }}</p>
                    </div>
                    <div class="rounded-2xl bg-gradient-to-br from-amber-400 to-amber-500 p-4 shadow-lg shadow-amber-500/30">
                        <svg class="h-6 w-6 text-white" fill="currentColor" viewBox="0 0 20 20">
                            <path fill-rule="evenodd" d="M10 18a8 8 0 100-16 8 8 0 000 16zm1-12a1 1 0 10-2 0v4a1 1 0 00.293.707l2.828 2.829a1 1 0 101.415-1.415L11 9.586V6z"/>
                        </svg>
                    </div>
                </div>
            </div>

            {{-- Card 3 --}}
            <div class="group rounded-3xl bg-white p-6 shadow-sm border border-slate-100 hover:shadow-xl hover:border-purple-100 transition-all duration-300 relative overflow-hidden">
                <div class="absolute -right-6 -top-6 h-24 w-24 rounded-full bg-gradient-to-br from-purple-100 to-purple-50 opacity-50 group-hover:scale-150 transition-transform duration-500"></div>
                <div class="relative z-10 flex items-center justify-between">
                    <div>
                        <p class="text-sm font-semibold text-slate-500 uppercase tracking-wider">Đang làm</p>
                        <p class="mt-3 text-4xl font-extrabold text-slate-900 group-hover:text-purple-600 transition-colors">{{ $projectStats['in_progress'] }}</p>
                    </div>
                    <div class="rounded-2xl bg-gradient-to-br from-purple-500 to-purple-600 p-4 shadow-lg shadow-purple-500/30">
                        <svg class="h-6 w-6 text-white" fill="currentColor" viewBox="0 0 20 20">
                            <path fill-rule="evenodd" d="M10 18a8 8 0 100-16 8 8 0 000 16zm1-11a1 1 0 10-2 0v3.586L7.707 9.293a1 1 0 00-1.414 1.414l3 3a1 1 0 001.414 0l3-3a1 1 0 00-1.414-1.414L11 10.586V7z"/>
                        </svg>
                    </div>
                </div>
            </div>

            {{-- Card 4 --}}
            <div class="group rounded-3xl bg-white p-6 shadow-sm border border-slate-100 hover:shadow-xl hover:border-emerald-100 transition-all duration-300 relative overflow-hidden">
                <div class="absolute -right-6 -top-6 h-24 w-24 rounded-full bg-gradient-to-br from-emerald-100 to-emerald-50 opacity-50 group-hover:scale-150 transition-transform duration-500"></div>
                <div class="relative z-10 flex items-center justify-between">
                    <div>
                        <p class="text-sm font-semibold text-slate-500 uppercase tracking-wider">Hoàn thành</p>
                        <p class="mt-3 text-4xl font-extrabold text-slate-900 group-hover:text-emerald-500 transition-colors">{{ $projectStats['completed'] }}</p>
                    </div>
                    <div class="rounded-2xl bg-gradient-to-br from-emerald-400 to-emerald-500 p-4 shadow-lg shadow-emerald-500/30">
                        <svg class="h-6 w-6 text-white" fill="currentColor" viewBox="0 0 20 20">
                            <path fill-rule="evenodd" d="M10 18a8 8 0 100-16 8 8 0 000 16zm3.707-9.293a1 1 0 00-1.414-1.414L9 10.586 7.707 9.293a1 1 0 00-1.414 1.414l2 2a1 1 0 001.414 0l4-4z"/>
                        </svg>
                    </div>
                </div>
            </div>
        </div>

        {{-- Filters --}}
        <div class="mb-6 rounded-2xl bg-white p-5 shadow-sm border border-slate-200">
            <div class="flex flex-col md:flex-row md:items-center gap-4">
                {{-- Search --}}
                <div class="flex-1 relative">
                    <div class="absolute inset-y-0 left-0 pl-4 flex items-center pointer-events-none">
                        <svg class="h-5 w-5 text-slate-400" fill="none" stroke="currentColor" viewBox="0 0 24 24">
                            <path stroke-linecap="round" stroke-linejoin="round" stroke-width="2" d="M21 21l-6-6m2-5a7 7 0 11-14 0 7 7 0 0114 0z"/>
                        </svg>
                    </div>
                    <input 
                        type="text" 
                        wire:model.live.debounce.300ms="searchStudent"
                        placeholder="Tìm kiếm học viên qua tên hoặc email..."
                        class="w-full rounded-xl border-slate-200 pl-11 pr-4 py-3 bg-slate-50 focus:bg-white focus:border-indigo-500 focus:ring-indigo-500 transition-colors shadow-sm"
                    />
                </div>

                {{-- Filter by Status --}}
                <div class="relative min-w-[200px]">
                    <select wire:model.live="filterStatus" class="w-full appearance-none rounded-xl border-slate-200 bg-slate-50 focus:bg-white pl-4 pr-10 py-3 text-slate-700 focus:border-indigo-500 focus:ring-indigo-500 transition-colors shadow-sm font-medium">
                        <option value="all">Tất cả trạng thái</option>
                        <option value="pending">Chưa bắt đầu</option>
                        <option value="in_progress">Đang làm</option>
                        <option value="completed">Hoàn thành</option>
                    </select>
                </div>

                {{-- Clear Filters --}}
                @if($searchStudent || $filterStatus !== 'all')
                    <button wire:click="clearFilters" class="inline-flex items-center gap-2 rounded-xl bg-slate-100 px-5 py-3 text-sm font-semibold text-slate-600 hover:bg-slate-200 hover:text-slate-900 transition-colors">
                        <svg class="h-4 w-4" fill="none" stroke="currentColor" viewBox="0 0 24 24">
                            <path stroke-linecap="round" stroke-linejoin="round" stroke-width="2" d="M6 18L18 6M6 6l12 12"/>
                        </svg>
                        Xóa lọc
                    </button>
                @endif
            </div>
        </div>

        {{-- Steps Header (Table Header) --}}
        <div class="mb-4 rounded-2xl bg-white p-5 shadow-sm border border-slate-200">
            <div class="grid grid-cols-12 gap-6 items-center text-xs font-bold text-slate-500 uppercase tracking-wider">
                <div class="col-span-3 pl-2">Học viên</div>
                <div class="col-span-2">Tiến độ tổng quan</div>
                <div class="col-span-6 grid grid-cols-{{ count($steps) }} gap-3">
                    @foreach($steps as $step)
                        <div class="text-center group relative cursor-default">
                            <div class="mx-auto flex h-8 w-8 items-center justify-center rounded-full bg-slate-100 text-slate-600 font-bold mb-1 transition-colors group-hover:bg-indigo-100 group-hover:text-indigo-600">{{ $step->step_order }}</div>
                            <div class="truncate text-[10px]">{{ $step->step_name }}</div>
                            
                            <!-- Tooltip -->
                            <div class="absolute bottom-full left-1/2 -translate-x-1/2 mb-2 hidden w-max rounded bg-slate-800 px-2 py-1 text-xs text-white opacity-0 transition-opacity group-hover:block group-hover:opacity-100 z-10">
                                {{ $step->step_name }}
                            </div>
                        </div>
                    @endforeach
                </div>
                <div class="col-span-1 text-center">Action</div>
            </div>
        </div>

        {{-- Submissions List --}}
        <div class="space-y-2">
            @forelse($submissionsData as $item)
                @php
                    $submission = $item['project_submission'];
                    $student = $item['student'];
                    $stepProgress = $item['step_progress'];
                    $pendingSteps = $item['pending_steps'];
                @endphp

                <div class="group relative rounded-2xl bg-white p-5 shadow-sm border border-slate-100 hover:shadow-lg hover:border-indigo-100 transition-all duration-300">
                    <div class="grid grid-cols-12 gap-6 items-center">
                        {{-- Student Info --}}
                        <div class="col-span-3">
                            <div class="flex items-center gap-4">
                                <div class="relative h-12 w-12 rounded-full bg-gradient-to-tr from-indigo-500 to-purple-500 flex items-center justify-center text-white shadow-md group-hover:scale-110 transition-transform duration-300">
                                    <span class="text-sm font-bold">{{ strtoupper(substr($student->name, 0, 2)) }}</span>
                                    @if($pendingSteps > 0)
                                        <span class="absolute -top-1 -right-1 flex h-4 w-4 items-center justify-center rounded-full bg-amber-500 text-[10px] font-bold text-white ring-2 ring-white">
                                            {{ $pendingSteps }}
                                        </span>
                                    @endif
                                </div>
                                <div>
                                    <p class="font-bold text-slate-900">{{ $student->name }}</p>
                                    <p class="text-xs text-slate-500 truncate max-w-[150px]">{{ $student->email }}</p>
                                </div>
                            </div>
                        </div>

                        {{-- Progress Bar --}}
                        <div class="col-span-2 pr-4">
                            <div class="flex flex-col gap-1.5">
                                <div class="flex justify-between items-center text-xs font-bold text-slate-700">
                                    <span>Hoàn thành</span>
                                    <span>{{ round($submission->progress_percent) }}%</span>
                                </div>
                                <div class="h-2 rounded-full bg-slate-100 overflow-hidden">
                                    <div class="h-full rounded-full bg-gradient-to-r from-indigo-500 to-purple-500 transition-all duration-1000 ease-out" style="width: {{ $submission->progress_percent }}%"></div>
                                </div>
                            </div>
                        </div>

                        {{-- Steps Progress --}}
                        <div class="col-span-6 grid grid-cols-{{ count($steps) }} gap-3">
                            @foreach($stepProgress as $stepItem)
                                @php
                                    $stepSubmission = $stepItem['submission'];
                                    $status = $stepItem['status'];
                                    
                                    $bgColor = match($status) {
                                        'approved' => 'bg-emerald-100 group-hover/btn:bg-emerald-200',
                                        'submitted', 'under_review' => 'bg-amber-100 group-hover/btn:bg-amber-200 ring-2 ring-amber-400/50',
                                        'rejected' => 'bg-rose-100 group-hover/btn:bg-rose-200',
                                        default => 'bg-slate-100 group-hover/btn:bg-slate-200',
                                    };
                                    
                                    $iconColor = match($status) {
                                        'approved' => 'text-emerald-600',
                                        'submitted', 'under_review' => 'text-amber-600',
                                        'rejected' => 'text-rose-600',
                                        default => 'text-slate-400',
                                    };
                                @endphp

                                <div class="flex justify-center">
                                    @if($stepSubmission)
                                        <button 
                                            wire:click="reviewStepSubmission({{ $stepSubmission->id }})"
                                            class="group/btn h-10 w-10 rounded-xl {{ $bgColor }} flex items-center justify-center transition-all duration-300 hover:scale-110 hover:-translate-y-1 hover:shadow-md cursor-pointer relative"
                                            title="{{ $stepItem['step']->step_name }} - Click để xem"
                                        >
                                            @if($status === 'approved')
                                                <svg class="h-5 w-5 {{ $iconColor }}" fill="currentColor" viewBox="0 0 20 20">
                                                    <path fill-rule="evenodd" d="M16.707 5.293a1 1 0 010 1.414l-8 8a1 1 0 01-1.414 0l-4-4a1 1 0 011.414-1.414L8 12.586l7.293-7.293a1 1 0 011.414 0z"/>
                                                </svg>
                                            @elseif($status === 'rejected')
                                                <svg class="h-5 w-5 {{ $iconColor }}" fill="currentColor" viewBox="0 0 20 20">
                                                    <path fill-rule="evenodd" d="M4.293 4.293a1 1 0 011.414 0L10 8.586l4.293-4.293a1 1 0 111.414 1.414L11.414 10l4.293 4.293a1 1 0 01-1.414 1.414L10 11.414l-4.293 4.293a1 1 0 01-1.414-1.414L8.586 10 4.293 5.707a1 1 0 010-1.414z"/>
                                                </svg>
                                            @else
                                                <span class="absolute -top-1 -right-1 flex h-3 w-3">
                                                  <span class="animate-ping absolute inline-flex h-full w-full rounded-full bg-amber-400 opacity-75"></span>
                                                  <span class="relative inline-flex rounded-full h-3 w-3 bg-amber-500"></span>
                                                </span>
                                                <svg class="h-5 w-5 {{ $iconColor }}" fill="none" stroke="currentColor" viewBox="0 0 24 24">
                                                    <path stroke-linecap="round" stroke-linejoin="round" stroke-width="2" d="M15 12a3 3 0 11-6 0 3 3 0 016 0z"/>
                                                    <path stroke-linecap="round" stroke-linejoin="round" stroke-width="2" d="M2.458 12C3.732 7.943 7.523 5 12 5c4.478 0 8.268 2.943 9.542 7-1.274 4.057-5.064 7-9.542 7-4.477 0-8.268-2.943-9.542-7z"/>
                                                </svg>
                                            @endif
                                        </button>
                                    @else
                                        <div class="h-10 w-10 rounded-xl {{ $bgColor }} flex items-center justify-center opacity-50 border border-dashed border-slate-300">
                                            <svg class="h-4 w-4 {{ $iconColor }}" fill="currentColor" viewBox="0 0 20 20">
                                                <path fill-rule="evenodd" d="M10 18a8 8 0 100-16 8 8 0 000 16zM7 9a1 1 0 000 2h6a1 1 0 100-2H7z"/>
                                            </svg>
                                        </div>
                                    @endif
                                </div>
                            @endforeach
                        </div>

                        {{-- Action --}}
                        <div class="col-span-1 text-center">
                            @if($pendingSteps > 0)
                                <button class="rounded-full bg-indigo-50 p-2 text-indigo-600 hover:bg-indigo-100 hover:text-indigo-900 transition-colors" title="Có bài nộp cần chấm">
                                    <svg class="h-5 w-5" fill="none" stroke="currentColor" viewBox="0 0 24 24">
                                        <path stroke-linecap="round" stroke-linejoin="round" stroke-width="2" d="M13 10V3L4 14h7v7l9-11h-7z"/>
                                    </svg>
                                </button>
                            @else
                                <span class="text-slate-300">—</span>
                            @endif
                        </div>
                    </div>
                </div>
            @empty
                <div class="rounded-xl bg-white p-12 text-center shadow-sm">
                    <svg class="mx-auto h-12 w-12 text-gray-400" fill="none" stroke="currentColor" viewBox="0 0 24 24">
                        <path stroke-linecap="round" stroke-linejoin="round" stroke-width="2" d="M20 13V6a2 2 0 00-2-2H6a2 2 0 00-2 2v7m16 0v5a2 2 0 01-2 2H6a2 2 0 01-2-2v-5m16 0h-2.586a1 1 0 00-.707.293l-2.414 2.414a1 1 0 01-.707.293h-3.172a1 1 0 01-.707-.293l-2.414-2.414A1 1 0 006.586 13H4"/>
                    </svg>
                    <p class="mt-4 text-lg font-medium text-gray-900">Chưa có học viên nào nộp bài</p>
                    <p class="mt-2 text-sm text-gray-600">Khi có học viên nộp bài, danh sách sẽ hiển thị ở đây</p>
                </div>
            @endforelse
        </div>

        {{-- Pagination --}}
        <div class="mt-8">
            {{ $submissionsData->links() }}
        </div>
    </div>
</div>
