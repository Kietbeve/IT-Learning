<div class="min-h-screen bg-slate-50 pb-12 font-sans text-slate-900">
    {{-- Header Section --}}
    <div class="relative mb-8 overflow-hidden bg-gradient-to-br from-indigo-600 via-purple-600 to-fuchsia-600 pb-16 pt-12 shadow-xl text-white">
        <div class="absolute inset-0 bg-white opacity-[0.03]" style="background-image: radial-gradient(#fff 1px, transparent 1px); background-size: 20px 20px;"></div>
        <div class="container mx-auto px-4 relative z-10 max-w-7xl">
            <div class="mb-8">
                <div class="inline-flex items-center gap-2 rounded-full bg-white/20 px-3 py-1 text-sm font-medium backdrop-blur-md mb-4 border border-white/20 shadow-sm">
                    <svg class="h-4 w-4" fill="none" stroke="currentColor" viewBox="0 0 24 24">
                        <path stroke-linecap="round" stroke-linejoin="round" stroke-width="2" d="M19 11H5m14 0a2 2 0 012 2v6a2 2 0 01-2 2H5a2 2 0 01-2-2v-6a2 2 0 012-2m14 0V9a2 2 0 00-2-2M5 11V9a2 2 0 012-2m0 0V5a2 2 0 012-2h6a2 2 0 012 2v2M7 7h10"/>
                    </svg>
                    Nộp Bài Tập
                </div>
                <h1 class="text-3xl md:text-4xl font-extrabold tracking-tight drop-shadow-md">{{ $project->title }}</h1>
                <p class="mt-3 text-indigo-100 max-w-2xl leading-relaxed text-base">Hoàn thành từng bước dưới đây để hoàn tất dự án của bạn.</p>
            </div>

            {{-- Progress Bar --}}
            <div class="rounded-2xl bg-white/10 p-5 backdrop-blur-md border border-white/20 shadow-inner">
                <div class="flex items-center justify-between mb-2">
                    <span class="text-sm font-bold text-white">Tiến độ hoàn thành</span>
                    <span class="text-xl font-extrabold text-white">{{ $projectSubmission->progress_percent }}%</span>
                </div>
                <div class="h-3 w-full rounded-full bg-black/20 overflow-hidden shadow-inner">
                    <div class="h-full rounded-full bg-gradient-to-r from-emerald-300 to-emerald-400 transition-all duration-1000 ease-out shadow-[0_0_10px_rgba(52,211,153,0.5)]" style="width: {{ $projectSubmission->progress_percent }}%"></div>
                </div>
            </div>
        </div>
    </div>

    <div class="container mx-auto px-4 max-w-7xl -mt-12 relative z-20">
        {{-- Step Wizard --}}
        <div class="mb-8 rounded-3xl bg-white p-6 shadow-lg border border-slate-100">
            <div class="flex items-center justify-between relative">
                @foreach($stepProgress as $index => $item)
                    <div class="flex flex-1 items-center relative z-10">
                        <div class="flex flex-col items-center group cursor-default w-full">
                            <div class="flex h-14 w-14 items-center justify-center rounded-full border-4 transition-all duration-300 {{ $item['is_current'] ? 'border-indigo-500 bg-indigo-50 shadow-md scale-110' : ($item['status'] === 'approved' ? 'border-emerald-500 bg-emerald-50 shadow-sm' : ($item['status'] === 'rejected' ? 'border-rose-500 bg-rose-50 shadow-sm' : 'border-slate-200 bg-white')) }}">
                                @if($item['status'] === 'approved')
                                    <svg class="h-7 w-7 text-emerald-600" fill="currentColor" viewBox="0 0 20 20">
                                        <path fill-rule="evenodd" d="M16.707 5.293a1 1 0 010 1.414l-8 8a1 1 0 01-1.414 0l-4-4a1 1 0 011.414-1.414L8 12.586l7.293-7.293a1 1 0 011.414 0z"/>
                                    </svg>
                                @elseif($item['status'] === 'rejected')
                                    <svg class="h-7 w-7 text-rose-600" fill="currentColor" viewBox="0 0 20 20">
                                        <path fill-rule="evenodd" d="M4.293 4.293a1 1 0 011.414 0L10 8.586l4.293-4.293a1 1 0 111.414 1.414L11.414 10l4.293 4.293a1 1 0 01-1.414 1.414L10 11.414l-4.293 4.293a1 1 0 01-1.414-1.414L8.586 10 4.293 5.707a1 1 0 010-1.414z"/>
                                    </svg>
                                @else
                                    <span class="text-lg font-extrabold {{ $item['is_current'] ? 'text-indigo-600' : 'text-slate-400' }}">{{ $item['step']->step_order }}</span>
                                @endif
                            </div>
                            <span class="mt-3 text-[11px] uppercase tracking-wider font-bold text-center {{ $item['is_current'] ? 'text-indigo-600' : 'text-slate-500' }}">{{ $item['step']->step_name }}</span>
                        </div>
                        @if($index < count($stepProgress) - 1)
                            <div class="absolute top-7 left-1/2 w-full -mt-[2px] h-1 {{ $item['status'] === 'approved' ? 'bg-emerald-400' : 'bg-slate-200' }}" style="z-index: -1;"></div>
                        @endif
                    </div>
                @endforeach
            </div>
        </div>

    @if($currentStep)
        <div class="grid grid-cols-1 lg:grid-cols-3 gap-6">
            <div class="lg:col-span-2 flex flex-col gap-6">
                {{-- Step Details Card --}}
                <div class="rounded-3xl bg-white p-8 shadow-sm border border-slate-200 relative overflow-hidden group/step hover:shadow-md transition-shadow">
                    <div class="absolute top-0 left-0 w-1.5 h-full bg-gradient-to-b from-indigo-400 to-purple-400 opacity-80 group-hover/step:opacity-100 transition-opacity"></div>
                    <div class="mb-8">
                        <h2 class="text-2xl font-extrabold text-slate-900 mb-2">{{ $currentStep->step_name }}</h2>
                        @if($currentStep->instructions)
                            <div class="mt-4 rounded-2xl bg-indigo-50/50 p-5 border border-indigo-100">
                                <p class="text-sm text-indigo-900 leading-relaxed">{{ $currentStep->instructions }}</p>
                            </div>
                        @endif
                        
                        @if($currentStep->resource_file_path)
                            <div class="mt-6 rounded-2xl border border-slate-200 bg-white p-5 flex items-center justify-between shadow-sm hover:shadow-md transition-shadow group">
                                <div class="flex items-center gap-4">
                                    <div class="flex h-12 w-12 items-center justify-center rounded-xl bg-rose-50 text-rose-500">
                                        <svg class="h-6 w-6" fill="none" stroke="currentColor" viewBox="0 0 24 24">
                                            <path stroke-linecap="round" stroke-linejoin="round" stroke-width="2" d="M9 12h6m-6 4h6m2 5H7a2 2 0 01-2-2V5a2 2 0 012-2h5.586a1 1 0 01.707.293l5.414 5.414a1 1 0 01.293.707V19a2 2 0 01-2 2z"/>
                                        </svg>
                                    </div>
                                    <div>
                                        <h3 class="text-sm font-bold text-slate-800">Tài liệu đính kèm từ Giáo viên</h3>
                                        <p class="text-xs font-medium text-slate-500 mt-1">{{ $currentStep->resource_file_name }}</p>
                                    </div>
                                </div>
                                <a href="{{ $currentStep->resource_file_url }}" target="_blank" class="inline-flex items-center gap-2 rounded-xl bg-white px-4 py-2.5 text-sm font-bold text-slate-700 shadow-sm ring-1 ring-inset ring-slate-300 hover:bg-slate-50 hover:text-indigo-600 transition-colors">
                                    <svg class="h-4 w-4" fill="none" stroke="currentColor" viewBox="0 0 24 24">
                                        <path stroke-linecap="round" stroke-linejoin="round" stroke-width="2" d="M4 16v1a3 3 0 003 3h10a3 3 0 003-3v-1m-4-4l-4 4m0 0l-4-4m4 4V4"/>
                                    </svg>
                                    Tải về
                                </a>
                            </div>
                        @endif
                    </div>

                    @if($currentStepSubmission && in_array($currentStepSubmission->status, ['submitted', 'under_review']))
                        <div class="rounded-2xl border border-amber-200 bg-amber-50 p-5 shadow-inner">
                            <div class="flex items-center gap-3">
                                <div class="flex h-10 w-10 items-center justify-center rounded-full bg-amber-100 text-amber-600">
                                    <svg class="h-5 w-5" fill="currentColor" viewBox="0 0 20 20">
                                        <path fill-rule="evenodd" d="M10 18a8 8 0 100-16 8 8 0 000 16zm1-12a1 1 0 10-2 0v4a1 1 0 00.293.707l2.828 2.829a1 1 0 101.415-1.415L11 9.586V6z"/>
                                    </svg>
                                </div>
                                <div>
                                    <span class="font-bold text-amber-900 block text-base">Đang chờ giáo viên chấm bài</span>
                                    <p class="mt-1 text-sm font-medium text-amber-700">Bạn đã nộp bài lần {{ $currentStepSubmission->submission_number }}. Vui lòng chờ phản hồi từ giáo viên.</p>
                                </div>
                            </div>
                        </div>
                    @elseif($currentStepSubmission && $currentStepSubmission->status === 'rejected')
                        <div class="rounded-2xl border border-rose-200 bg-rose-50 p-6 shadow-inner">
                            <div class="flex items-start gap-3">
                                <div class="flex h-10 w-10 shrink-0 items-center justify-center rounded-full bg-rose-100 text-rose-600 mt-1">
                                    <svg class="h-5 w-5" fill="currentColor" viewBox="0 0 20 20">
                                        <path fill-rule="evenodd" d="M10 18a8 8 0 100-16 8 8 0 000 16zM8.707 7.293a1 1 0 00-1.414 1.414L8.586 10l-1.293 1.293a1 1 0 101.414 1.414L10 11.414l1.293 1.293a1 1 0 001.414-1.414L11.414 10l1.293-1.293a1 1 0 00-1.414-1.414L10 8.586 8.707 7.293z"/>
                                    </svg>
                                </div>
                                <div>
                                    <span class="font-bold text-rose-900 block text-base">Bài nộp bị từ chối - Vui lòng làm lại</span>
                                    @if($currentStepSubmission->feedback)
                                        <div class="mt-4 rounded-xl bg-white p-4 border border-rose-100 shadow-sm">
                                            <p class="text-xs font-bold uppercase tracking-wider text-rose-500 mb-2">Feedback từ giáo viên:</p>
                                            <p class="text-sm font-medium text-slate-700 leading-relaxed">{{ $currentStepSubmission->feedback }}</p>
                                        </div>
                                    @endif
                                </div>
                            </div>
                        </div>
                    @elseif($currentStepSubmission && $currentStepSubmission->status === 'approved')
                        <div class="rounded-2xl border border-emerald-200 bg-emerald-50 p-5 shadow-inner">
                            <div class="flex items-center gap-3">
                                <div class="flex h-10 w-10 items-center justify-center rounded-full bg-emerald-100 text-emerald-600">
                                    <svg class="h-5 w-5" fill="currentColor" viewBox="0 0 20 20">
                                        <path fill-rule="evenodd" d="M10 18a8 8 0 100-16 8 8 0 000 16zm3.707-9.293a1 1 0 00-1.414-1.414L9 10.586 7.707 9.293a1 1 0 00-1.414 1.414l2 2a1 1 0 001.414 0l4-4z"/>
                                    </svg>
                                </div>
                                <span class="font-bold text-emerald-900 text-base">Bước này đã hoàn thành</span>
                            </div>
                        </div>
                    @endif

                    @if(!$currentStepSubmission || in_array($currentStepSubmission->status, ['draft', 'rejected']))
                        <div class="mt-8 border-t border-slate-100 pt-8">
                            <h3 class="text-lg font-bold text-slate-900 mb-6">Nộp bài của bạn</h3>
                            <form wire:submit.prevent="submit" class="space-y-6">
                                @if($currentStep->requiresFile())
                                    <div class="rounded-2xl bg-slate-50 p-6 border border-slate-200 border-dashed hover:border-indigo-400 transition-colors">
                                        <label class="block text-sm font-bold text-slate-700 mb-2">
                                            Upload File {{ $currentStep->submission_type === 'file' ? '*' : '(Tùy chọn)' }}
                                        </label>
                                        <div class="mt-2">
                                            <input type="file" wire:model="file" class="block w-full text-sm text-slate-500 file:mr-4 file:rounded-xl file:border-0 file:bg-indigo-100 file:px-6 file:py-3 file:text-sm file:font-bold file:text-indigo-700 hover:file:bg-indigo-200 transition-colors cursor-pointer" />
                                        </div>
                                        @if($currentStep->allowed_file_types)
                                            <p class="mt-3 text-xs font-medium text-slate-500">Loại file: <span class="text-slate-700">{{ $currentStep->getAllowedFileTypesString() }}</span> &bull; Tối đa: <span class="text-slate-700">{{ $currentStep->max_file_size_mb }} MB</span></p>
                                        @else
                                            <p class="mt-3 text-xs font-medium text-slate-500">Tối đa: <span class="text-slate-700">{{ $currentStep->max_file_size_mb }} MB</span></p>
                                        @endif
                                        @error('file') <p class="mt-2 text-sm font-medium text-rose-600">{{ $message }}</p> @enderror
                                        @if($file)
                                            <div class="mt-4 rounded-xl bg-emerald-50 p-4 border border-emerald-100 text-sm font-bold text-emerald-700 flex items-center gap-2 shadow-sm">
                                                <svg class="h-5 w-5" fill="none" stroke="currentColor" viewBox="0 0 24 24">
                                                    <path stroke-linecap="round" stroke-linejoin="round" stroke-width="2" d="M9 12l2 2 4-4m6 2a9 9 0 11-18 0 9 9 0 0118 0z"/>
                                                </svg>
                                                Đã chọn: {{ $file->getClientOriginalName() }}
                                            </div>
                                        @endif
                                    </div>
                                @endif

                                @if($currentStep->requiresLink())
                                    <div>
                                        <label class="block text-sm font-bold text-slate-700 mb-2">
                                            Link {{ $currentStep->submission_type === 'link' ? '*' : '(Tùy chọn)' }}
                                        </label>
                                        <input type="text" wire:model.defer="link_url" placeholder="{{ $currentStep->link_placeholder ?? 'Nhập link (ví dụ: https://github.com/...)' }}" class="w-full rounded-xl border-slate-200 px-4 py-3 bg-white focus:border-indigo-500 focus:ring-indigo-500 transition-colors shadow-sm" />
                                        @error('link_url') <p class="mt-1 text-sm font-medium text-rose-600">{{ $message }}</p> @enderror
                                    </div>
                                @endif

                                <div>
                                    <label class="block text-sm font-bold text-slate-700 mb-2">
                                        Ghi chú (Tùy chọn)
                                    </label>
                                    <textarea wire:model.defer="notes" rows="3" placeholder="Ghi chú thêm về bài nộp của bạn để giáo viên lưu ý..." class="w-full rounded-xl border-slate-200 px-4 py-3 bg-white focus:border-indigo-500 focus:ring-indigo-500 transition-colors shadow-sm"></textarea>
                                </div>

                                <div class="flex justify-end gap-3 pt-4">
                                    <button type="button" wire:click="resetForm" class="rounded-xl px-6 py-3 text-sm font-bold text-slate-600 hover:bg-slate-100 transition-colors">Hủy</button>
                                    <button type="submit" class="rounded-xl bg-indigo-600 px-8 py-3 text-sm font-bold text-white shadow-md hover:bg-indigo-700 hover:shadow-lg transition-all" @if($isSubmitting) disabled @endif>
                                        Nộp bài
                                    </button>
                                </div>
                            </form>
                        </div>
                    @endif
                </div>

                {{-- Final Grade Results if Project is Completed and Graded --}}
                @if($projectSubmission->score !== null)
                <div class="rounded-3xl bg-white p-8 shadow-sm relative overflow-hidden border border-slate-200 group">
                    <div class="absolute inset-0 bg-gradient-to-br from-indigo-50/50 to-purple-50/50 opacity-0 group-hover:opacity-100 transition-opacity duration-500"></div>
                    <div class="absolute -right-12 -top-12 opacity-5 text-indigo-600">
                        <svg class="w-64 h-64" fill="currentColor" viewBox="0 0 20 20">
                            <path d="M9.049 2.927c.3-.921 1.603-.921 1.902 0l1.07 3.292a1 1 0 00.95.69h3.462c.969 0 1.371 1.24.588 1.81l-2.8 2.034a1 1 0 00-.364 1.118l1.07 3.292c.3.921-.755 1.688-1.54 1.118l-2.8-2.034a1 1 0 00-1.175 0l-2.8 2.034c-.784.57-1.838-.197-1.539-1.118l1.07-3.292a1 1 0 00-.364-1.118L2.98 8.72c-.783-.57-.38-1.81.588-1.81h3.461a1 1 0 00.951-.69l1.07-3.292z"></path>
                        </svg>
                    </div>
                    <div class="relative z-10 flex flex-col md:flex-row gap-8 items-center md:items-start">
                        <div class="flex flex-col items-center justify-center shrink-0">
                            <div class="w-28 h-28 rounded-full border-[6px] border-indigo-100 bg-white flex items-center justify-center shadow-lg relative">
                                <div class="absolute inset-0 rounded-full border-[6px] border-indigo-500 rounded-full" style="clip-path: polygon(0 0, 100% 0, 100% 100%, 0% 100%);"></div>
                                <span class="text-4xl font-black text-indigo-600">{{ $projectSubmission->score }}</span>
                            </div>
                            <span class="mt-4 text-[11px] font-bold uppercase tracking-widest text-slate-500">Điểm tổng kết</span>
                        </div>
                        <div class="flex-1 w-full pt-2">
                            <div class="flex items-center gap-2 mb-4">
                                <h3 class="text-xl font-extrabold text-slate-800">Đánh giá từ giáo viên</h3>
                                <svg class="h-5 w-5 text-indigo-500" fill="none" stroke="currentColor" viewBox="0 0 24 24">
                                    <path stroke-linecap="round" stroke-linejoin="round" stroke-width="2" d="M9 12l2 2 4-4m6 2a9 9 0 11-18 0 9 9 0 0118 0z"/>
                                </svg>
                            </div>
                            @if($projectSubmission->feedback)
                                <div class="bg-indigo-50/80 rounded-2xl p-5 border border-indigo-100">
                                    <p class="text-indigo-900 leading-relaxed font-medium italic">"{{ $projectSubmission->feedback }}"</p>
                                </div>
                            @else
                                <p class="text-slate-400 italic">Không có nhận xét chi tiết.</p>
                            @endif
                        </div>
                    </div>
                </div>
                @endif
            </div>

            <div class="space-y-6">
                @if($currentStepSubmission)
                    <div class="rounded-3xl bg-white p-6 shadow-sm border border-slate-100 hover:shadow-md transition-shadow">
                        <h3 class="text-lg font-extrabold text-slate-900 mb-5 flex items-center gap-2">
                            <svg class="h-5 w-5 text-indigo-500" fill="none" stroke="currentColor" viewBox="0 0 24 24">
                                <path stroke-linecap="round" stroke-linejoin="round" stroke-width="2" d="M9 12h6m-6 4h6m2 5H7a2 2 0 01-2-2V5a2 2 0 012-2h5.586a1 1 0 01.707.293l5.414 5.414a1 1 0 01.293.707V19a2 2 0 01-2 2z"/>
                            </svg>
                            Bài nộp hiện tại
                        </h3>
                        <div class="space-y-4">
                            <div class="rounded-xl bg-slate-50 p-4 border border-slate-100">
                                <label class="text-[10px] font-bold uppercase tracking-wider text-slate-500 block mb-2">Trạng thái</label>
                                <div>
                                    <x-badge :label="$currentStepSubmission->getStatusLabel()" :color="$currentStepSubmission->getStatusColor()" class="shadow-sm" />
                                </div>
                            </div>
                            @if($currentStepSubmission->hasFile())
                                <div class="rounded-xl bg-slate-50 p-4 border border-slate-100">
                                    <label class="text-[10px] font-bold uppercase tracking-wider text-slate-500 block mb-2">File đính kèm</label>
                                    <div class="flex items-center gap-3">
                                        <div class="flex h-8 w-8 items-center justify-center rounded-lg bg-indigo-100 text-indigo-600">
                                            <svg class="h-4 w-4" fill="currentColor" viewBox="0 0 20 20">
                                                <path fill-rule="evenodd" d="M4 4a2 2 0 012-2h4.586A2 2 0 0112 2.586L15.414 6A2 2 0 0116 7.414V16a2 2 0 01-2 2H6a2 2 0 01-2-2V4z"/>
                                            </svg>
                                        </div>
                                        <span class="text-sm font-medium text-slate-900 truncate" title="{{ $currentStepSubmission->file_name }}">{{ Str::limit($currentStepSubmission->file_name, 25) }}</span>
                                    </div>
                                </div>
                            @endif
                            @if($currentStepSubmission->hasLink())
                                <div class="rounded-xl bg-slate-50 p-4 border border-slate-100">
                                    <label class="text-[10px] font-bold uppercase tracking-wider text-slate-500 block mb-2">Link</label>
                                    <a href="{{ $currentStepSubmission->link_url }}" target="_blank" class="inline-flex items-center gap-2 text-sm font-bold text-indigo-600 hover:text-indigo-800 transition-colors group">
                                        <svg class="h-4 w-4" fill="none" stroke="currentColor" viewBox="0 0 24 24">
                                            <path stroke-linecap="round" stroke-linejoin="round" stroke-width="2" d="M13.828 10.172a4 4 0 00-5.656 0l-4 4a4 4 0 105.656 5.656l1.102-1.101m-.758-4.899a4 4 0 005.656 0l4-4a4 4 0 00-5.656-5.656l-1.1 1.1"/>
                                        </svg>
                                        <span class="group-hover:underline underline-offset-2">{{ Str::limit($currentStepSubmission->link_url, 25) }}</span>
                                    </a>
                                </div>
                            @endif
                            @if($currentStepSubmission->submitted_at)
                                <div class="rounded-xl bg-slate-50 p-4 border border-slate-100 flex items-center justify-between">
                                    <label class="text-[10px] font-bold uppercase tracking-wider text-slate-500">Thời gian nộp</label>
                                    <p class="text-sm font-bold text-slate-900">{{ $currentStepSubmission->submitted_at->format('d/m/Y H:i') }}</p>
                                </div>
                            @endif
                        </div>
                    </div>
                @endif

                <div class="rounded-3xl bg-white p-6 shadow-sm border border-slate-100 hover:shadow-md transition-shadow">
                    <h3 class="text-lg font-extrabold text-slate-900 mb-5 flex items-center gap-2">
                        <svg class="h-5 w-5 text-purple-500" fill="none" stroke="currentColor" viewBox="0 0 24 24">
                            <path stroke-linecap="round" stroke-linejoin="round" stroke-width="2" d="M4 6h16M4 10h16M4 14h16M4 18h16"/>
                        </svg>
                        Các bước khác
                    </h3>
                    <div class="space-y-3">
                        @foreach($stepProgress as $item)
                            @if(!$item['is_current'])
                                <button wire:click="switchStep({{ $item['step']->id }})" class="group w-full rounded-2xl border border-slate-100 bg-white p-4 text-left transition-all hover:border-indigo-200 hover:bg-indigo-50 hover:shadow-sm {{ in_array($item['status'], ['approved', 'rejected', 'submitted', 'under_review']) ? '' : 'opacity-50 cursor-not-allowed' }}">
                                    <div class="flex items-center justify-between">
                                        <div class="flex items-center gap-3">
                                            <div class="flex h-8 w-8 shrink-0 items-center justify-center rounded-full bg-slate-50 text-xs font-bold text-slate-400 group-hover:bg-white group-hover:text-indigo-600 transition-colors">
                                                {{ $item['step']->step_order }}
                                            </div>
                                            <span class="text-sm font-bold text-slate-700 group-hover:text-indigo-900 transition-colors">{{ $item['step']->step_name }}</span>
                                        </div>
                                        @if($item['status'] === 'approved')
                                            <svg class="h-5 w-5 text-emerald-500" fill="currentColor" viewBox="0 0 20 20">
                                                <path fill-rule="evenodd" d="M10 18a8 8 0 100-16 8 8 0 000 16zm3.707-9.293a1 1 0 00-1.414-1.414L9 10.586 7.707 9.293a1 1 0 00-1.414 1.414l2 2a1 1 0 001.414 0l4-4z"/>
                                            </svg>
                                        @endif
                                    </div>
                                </button>
                            @endif
                        @endforeach
                    </div>
                </div>
            </div>
        </div>
    @else
        @if($allSteps->isEmpty())
            <div class="rounded-3xl border border-amber-200 bg-amber-50 p-12 text-center shadow-inner">
                <div class="mx-auto flex h-24 w-24 items-center justify-center rounded-full bg-amber-100 text-amber-600 mb-6">
                    <svg class="h-12 w-12" fill="currentColor" viewBox="0 0 20 20">
                        <path fill-rule="evenodd" d="M8.257 3.099c.765-1.36 2.722-1.36 3.486 0l5.58 9.92c.75 1.334-.213 2.98-1.742 2.98H4.42c-1.53 0-2.493-1.646-1.743-2.98l5.58-9.92zM11 13a1 1 0 11-2 0 1 1 0 012 0zm-1-8a1 1 0 00-1 1v3a1 1 0 002 0V6a1 1 0 00-1-1z"/>
                    </svg>
                </div>
                <h3 class="text-2xl font-extrabold text-amber-900">Project chưa được cấu hình</h3>
                <p class="mt-3 text-base font-medium text-amber-700">Giáo viên chưa thiết lập các bước nộp bài cho project này. Vui lòng liên hệ giáo viên.</p>
            </div>
        @else
            <div class="rounded-3xl border border-emerald-200 bg-gradient-to-b from-emerald-50 to-white p-12 text-center shadow-lg relative overflow-hidden">
                <div class="absolute -right-12 -top-12 h-48 w-48 rounded-full bg-emerald-100 opacity-50 blur-3xl"></div>
                <div class="absolute -left-12 -bottom-12 h-48 w-48 rounded-full bg-emerald-100 opacity-50 blur-3xl"></div>
                
                <div class="relative z-10 mx-auto flex h-24 w-24 items-center justify-center rounded-full bg-emerald-100 text-emerald-600 mb-6 shadow-sm border border-emerald-200">
                    <svg class="h-12 w-12" fill="currentColor" viewBox="0 0 20 20">
                        <path fill-rule="evenodd" d="M10 18a8 8 0 100-16 8 8 0 000 16zm3.707-9.293a1 1 0 00-1.414-1.414L9 10.586 7.707 9.293a1 1 0 00-1.414 1.414l2 2a1 1 0 001.414 0l4-4z"/>
                    </svg>
                </div>
                <h3 class="text-3xl font-extrabold text-emerald-900 tracking-tight">Chúc mừng! Bạn đã hoàn thành tất cả các bước</h3>
                
                @if($projectSubmission->score !== null)
                    <div class="mt-10 mx-auto max-w-2xl rounded-3xl bg-white p-8 shadow-sm relative overflow-hidden border border-slate-200 text-left group hover:shadow-md transition-all">
                        <div class="absolute inset-0 bg-gradient-to-br from-emerald-50/50 to-teal-50/50 opacity-0 group-hover:opacity-100 transition-opacity duration-500"></div>
                        <div class="absolute -right-12 -top-12 opacity-5 text-emerald-600">
                            <svg class="w-64 h-64" fill="currentColor" viewBox="0 0 20 20">
                                <path d="M9.049 2.927c.3-.921 1.603-.921 1.902 0l1.07 3.292a1 1 0 00.95.69h3.462c.969 0 1.371 1.24.588 1.81l-2.8 2.034a1 1 0 00-.364 1.118l1.07 3.292c.3.921-.755 1.688-1.54 1.118l-2.8-2.034a1 1 0 00-1.175 0l-2.8 2.034c-.784.57-1.838-.197-1.539-1.118l1.07-3.292a1 1 0 00-.364-1.118L2.98 8.72c-.783-.57-.38-1.81.588-1.81h3.461a1 1 0 00.951-.69l1.07-3.292z"></path>
                            </svg>
                        </div>
                        <div class="relative z-10 flex flex-col md:flex-row gap-8 items-center md:items-start">
                            <div class="flex flex-col items-center justify-center shrink-0">
                                <div class="w-28 h-28 rounded-full border-[6px] border-emerald-50 bg-white flex items-center justify-center shadow-md relative">
                                    <div class="absolute inset-0 rounded-full border-[6px] border-emerald-400 rounded-full" style="clip-path: polygon(0 0, 100% 0, 100% 100%, 0% 100%);"></div>
                                    <span class="text-4xl font-black text-emerald-600">{{ $projectSubmission->score }}</span>
                                </div>
                                <span class="mt-4 text-[11px] font-bold uppercase tracking-widest text-slate-500">Điểm tổng kết</span>
                            </div>
                            <div class="flex-1 w-full pt-2">
                                <div class="flex items-center gap-2 mb-4">
                                    <h3 class="text-xl font-extrabold text-slate-800">Đánh giá từ giáo viên</h3>
                                    <svg class="h-5 w-5 text-emerald-500" fill="none" stroke="currentColor" viewBox="0 0 24 24">
                                        <path stroke-linecap="round" stroke-linejoin="round" stroke-width="2" d="M9 12l2 2 4-4m6 2a9 9 0 11-18 0 9 9 0 0118 0z"/>
                                    </svg>
                                </div>
                                @if($projectSubmission->feedback)
                                    <div class="bg-emerald-50/80 rounded-2xl p-5 border border-emerald-100">
                                        <p class="text-emerald-900 leading-relaxed font-medium italic text-sm">"{{ $projectSubmission->feedback }}"</p>
                                    </div>
                                @else
                                    <p class="text-slate-400 italic text-sm">Không có nhận xét chi tiết.</p>
                                @endif
                            </div>
                        </div>
                    </div>
                @else
                    <p class="mt-4 text-lg font-medium text-emerald-700">Vui lòng chờ giáo viên chấm điểm cuối cùng cho project của bạn.</p>
                @endif
            </div>
        @endif
    @endif
</div>
