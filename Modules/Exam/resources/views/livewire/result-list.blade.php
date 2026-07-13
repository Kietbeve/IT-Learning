<div class="max-w-6xl mx-auto px-4 py-8">
    {{-- Header --}}
    <div class="mb-8">
        <h1 class="text-2xl font-bold text-gray-900 sm:text-3xl">Kết quả học tập</h1>
        <p class="mt-1 text-sm text-gray-500">Xem và theo dõi lịch sử làm bài thi, điểm số và kết quả của bạn.</p>
    </div>

    {{-- Stats Cards --}}
    <div class="grid grid-cols-2 md:grid-cols-4 gap-4 mb-8">
        <div class="bg-slate-50 p-5 rounded-2xl border border-gray-100 shadow-sm transition-all hover:shadow-md">
            <div class="text-xs font-semibold text-gray-500 uppercase tracking-wider">Đã làm</div>
            <div class="mt-2 text-3xl font-extrabold text-gray-900">{{ $stats['total'] }}</div>
            <div class="mt-1 text-xs text-gray-400">lượt làm bài</div>
        </div>

        <div class="bg-green-50 p-5 rounded-2xl border border-gray-100 shadow-sm transition-all hover:shadow-md">
            <div class="text-xs font-semibold text-green-600 uppercase tracking-wider">Đã đạt</div>
            <div class="mt-2 text-3xl font-extrabold text-green-600">{{ $stats['passed'] }}</div>
            <div class="mt-1 text-xs text-green-500">vượt qua yêu cầu</div>
        </div>

        <div class="bg-red-50 p-5 rounded-2xl border border-gray-100 shadow-sm transition-all hover:shadow-md">
            <div class="text-xs font-semibold text-red-600 uppercase tracking-wider">Chưa đạt</div>
            <div class="mt-2 text-3xl font-extrabold text-red-600">{{ $stats['failed'] }}</div>
            <div class="mt-1 text-xs text-red-500">chưa đủ điểm đạt</div>
        </div>

        <div class="bg-indigo-50 p-5 rounded-2xl border border-gray-100 shadow-sm transition-all hover:shadow-md">
            <div class="text-xs font-semibold text-indigo-600 uppercase tracking-wider">Điểm TB</div>
            <div class="mt-2 text-3xl font-extrabold text-indigo-600">{{ $stats['average_score'] }}%</div>
            <div class="mt-1 text-xs text-indigo-500">tất cả bài thi</div>
        </div>
    </div>

    {{-- Filter Panel --}}
    <div class="bg-white p-4 rounded-2xl border border-gray-100 shadow-sm mb-8">
        <div class="grid grid-cols-2 sm:grid-cols-12 gap-4 items-center">
            <div class="col-span-2 sm:col-span-6 relative">
                <input type="text"
                    class="w-full pl-4 pr-10 py-2.5 bg-gray-50 border border-gray-200 rounded-xl text-sm focus:outline-none focus:ring-2 focus:ring-indigo-500 focus:border-transparent transition-all"
                    placeholder="Tìm tên đề thi..." wire:model.live.debounce.500ms="search">
                <div class="absolute inset-y-0 right-0 pr-3 flex items-center pointer-events-none">
                    <svg class="h-5 w-5 text-gray-400" fill="none" stroke="currentColor" viewBox="0 0 24 24">
                        <path stroke-linecap="round" stroke-linejoin="round" stroke-width="2"
                            d="M21 21l-6-6m2-5a7 7 0 11-14 0 7 7 0 0114 0z" />
                    </svg>
                </div>
            </div>

            <div class="col-span-1 sm:col-span-3">
                <select
                    class="w-full px-3 py-2.5 bg-gray-50 border border-gray-200 rounded-xl text-sm focus:outline-none focus:ring-2 focus:ring-indigo-500 focus:border-transparent transition-all"
                    wire:model.live="result">
                    <option value="">Tất cả trạng thái</option>
                    <option value="passed">Đạt</option>
                    <option value="failed">Không đạt</option>
                </select>
            </div>

            <div class="col-span-1 sm:col-span-3">
                <select
                    class="w-full px-3 py-2.5 bg-gray-50 border border-gray-200 rounded-xl text-sm focus:outline-none focus:ring-2 focus:ring-indigo-500 focus:border-transparent transition-all"
                    wire:model.live="sort">
                    <option value="latest">Mới nhất</option>
                    <option value="oldest">Cũ nhất</option>
                    <option value="score_desc">Điểm cao nhất</option>
                    <option value="score_asc">Điểm thấp nhất</option>
                </select>
            </div>
        </div>
    </div>

    {{-- Attempt Cards --}}
    <div class="grid grid-cols-1 md:grid-cols-2 gap-6">
        @forelse($attempts as $attempt)
            <div
                class="bg-white rounded-2xl border border-gray-100 shadow-sm hover:shadow-md transition-all flex flex-col justify-between overflow-hidden">
                <div class="p-6">
                    <div class="flex justify-between items-start gap-4 mb-4">
                        <h3 class="text-base font-bold text-gray-900 leading-snug line-clamp-2 flex-1">
                            {{ $attempt->exam->title }}
                        </h3>
                        @if ($attempt->status == 'completed' || $attempt->status == 'submitted')
                            <span
                                class="inline-flex items-center px-2.5 py-1 rounded-full text-xs font-bold bg-indigo-50 text-indigo-700 border border-indigo-200">
                                TN: {{ number_format($attempt->getMultipleChoiceScore(), 0) }} điểm
                            </span>
                        @endif

                        @if ($attempt->status == 'completed')
                            <span
                                class="inline-flex items-center px-2.5 py-1 rounded-full text-xs font-bold bg-indigo-50 text-indigo-700 border border-indigo-200">
                                TL: {{ number_format($attempt->getEssayScore(), 0) }} điểm
                            </span>
                        @else
                          <span
                                class="inline-flex items-center px-2.5 py-1 rounded-full text-xs font-bold bg-indigo-50 text-indigo-700 border border-indigo-200">
                                TL: chờ chấm
                            </span>
                        @endif

                        @if($attempt->is_passed && $attempt->status == 'completed')
                            <span
                                class="inline-flex items-center px-2.5 py-1 rounded-full text-xs font-semibold bg-green-50 text-green-700 border border-green-200/50 shrink-0">
                                Đạt
                            </span>
                        @elseif ($attempt->status == 'completed')
                            <span
                                class="inline-flex items-center px-2.5 py-1 rounded-full text-xs font-semibold bg-red-50 text-red-700 border border-red-200/50 shrink-0">
                                Không đạt
                            </span>
                        @endif
                    </div>

                    <div class="grid grid-cols-2 gap-y-3 gap-x-4 text-sm text-gray-600">
                        <div class="flex items-center gap-2">
                            <svg class="w-4 h-4 text-gray-400" fill="none" stroke="currentColor" viewBox="0 0 24 24">
                                <path stroke-linecap="round" stroke-linejoin="round" stroke-width="2"
                                    d="M9 12l2 2 4-4m6 2a9 9 0 11-18 0 9 9 0 0118 0z" />
                            </svg>
                            <span>Đúng: <strong
                                    class="text-gray-900 font-semibold">{{ $attempt->correct_answers }}/{{ $attempt->total_questions }}</strong></span>
                        </div>

                        <div class="flex items-center gap-2">
                            <svg class="w-4 h-4 text-gray-400" fill="none" stroke="currentColor" viewBox="0 0 24 24">
                                <path stroke-linecap="round" stroke-linejoin="round" stroke-width="2"
                                    d="M13 10V3L4 14h7v7l9-11h-7z" />
                            </svg>
                            <span>Điểm: <strong
                                    class="text-indigo-600 font-bold text-base">{{ round($attempt->percent_score, 1) }}%</strong></span>
                            <span>
                                Yêu cầu: <strong
                                    class="text-indigo-600 font-bold text-base">{{ round($attempt->exam->pass_percent, 1) }}%</strong>
                            </span>
                        </div>

                        <div class="flex items-center gap-2">
                            <svg class="w-4 h-4 text-gray-400" fill="none" stroke="currentColor" viewBox="0 0 24 24">
                                <path stroke-linecap="round" stroke-linejoin="round" stroke-width="2"
                                    d="M12 8v4l3 3m6-3a9 9 0 11-18 0 9 9 0 0118 0z" />
                            </svg>
                            <span>Thời gian: <strong
                                    class="text-gray-900 font-semibold">{{ (int) $attempt->started_at?->diffInMinutes($attempt->submitted_at) }}
                                    /{{ $attempt->exam->duration_minutes }} phút</strong></span>
                        </div>

                        <div class="flex items-center gap-2">
                            <svg class="w-4 h-4 text-gray-400" fill="none" stroke="currentColor" viewBox="0 0 24 24">
                                <path stroke-linecap="round" stroke-linejoin="round" stroke-width="2"
                                    d="M8 7V3m8 4V3m-9 8h10M5 21h14a2 2 0 002-2V7a2 2 0 00-2-2H5a2 2 0 00-2 2v12a2 2 0 002 2z" />
                            </svg>
                            Bắt đầu: 
                            <span class="truncate"><strong
                                    class="text-gray-900 font-semibold text-xs">{{ $attempt->started_at?->format('d/m/Y H:i') }}</strong></span>
                        </div>

                        <div class="flex items-center gap-2">
                            <svg class="w-4 h-4 text-gray-400" fill="none" stroke="currentColor" viewBox="0 0 24 24">
                                <path stroke-linecap="round" stroke-linejoin="round" stroke-width="2"
                                    d="M8 7V3m8 4V3m-9 8h10M5 21h14a2 2 0 002-2V7a2 2 0 00-2-2H5a2 2 0 00-2 2v12a2 2 0 002 2z" />
                            </svg>
                            Nộp: 
                            <span class="truncate"><strong
                                    class="text-gray-900 font-semibold text-xs">{{ $attempt->submitted_at?->format('d/m/Y H:i') }}</strong></span>
                        </div>

                        
                        <div class="flex items-center gap-2">
                            <svg class="w-4 h-4 text-gray-400" fill="none" stroke="currentColor" viewBox="0 0 24 24">
                                <path stroke-linecap="round" stroke-linejoin="round" stroke-width="2"
                                    d="M8 7V3m8 4V3m-9 8h10M5 21h14a2 2 0 002-2V7a2 2 0 00-2-2H5a2 2 0 00-2 2v12a2 2 0 002 2z" />
                            </svg>
                            Chế độ:
                            <span class="truncate">
                                <strong class="text-gray-900 font-semibold text-xs">
                                    {{ match($attempt->exam->mode) {
                                        'practice' => 'Luyện tập',
                                        'official' => 'Chính thức',
                                        default => $attempt->exam->mode,
                                    } }}
                                </strong>
                            </span>
                        </div>
                    </div>
                </div>

                <div class="px-6 py-4 bg-gray-50 border-t border-gray-100 flex items-center justify-between">
                    {{-- @if($attempt->status == 'completed' || $attempt->exam->mode == 'practice') --}}
                    <div>
                        @if($attempt->status === 'completed')
                            <span
                                class="inline-flex items-center px-2.5 py-1 rounded-full text-xs font-semibold bg-green-50 text-green-700 border border-green-200">
                                Đã chấm
                            </span>
                        @else
                            <span
                                class="inline-flex items-center px-2.5 py-1 rounded-full text-xs font-semibold bg-amber-50 text-amber-700 border border-amber-200">
                                Đang chờ chấm
                            </span>
                        @endif
                    </div>

                    <a href="{{ route('exam.attempt.result', $attempt) }}"
                        class="inline-flex items-center gap-2 text-sm font-bold text-indigo-600 hover:text-indigo-800 transition-colors">
                        <span>Chi tiết bài làm</span>
                        <svg class="w-4 h-4" fill="none" stroke="currentColor" viewBox="0 0 24 24">
                            <path stroke-linecap="round" stroke-linejoin="round" stroke-width="2" d="M9 5l7 7-7 7" />
                        </svg>
                    </a>
                </div>
            </div>
        @empty
            <div
                class="col-span-full py-12 flex flex-col items-center justify-center bg-white rounded-2xl border border-gray-100 shadow-sm">
                <svg class="w-16 h-16 text-gray-300 mb-4" fill="none" stroke="currentColor" viewBox="0 0 24 24">
                    <path stroke-linecap="round" stroke-linejoin="round" stroke-width="2"
                        d="M9.172 16.172a4 4 0 015.656 0M9 10h.01M15 10h.01M21 12a9 9 0 11-18 0 9 9 0 0118 0z" />
                </svg>
                <h4 class="text-lg font-semibold text-gray-900 text-center">
                    Không tìm thấy kết quả nào
                </h4>
                <p class="mt-2 max-w-sm text-center text-sm text-gray-500">
                    Vui lòng thử điều chỉnh bộ lọc hoặc từ khóa tìm kiếm.
                </p>
            </div>
        @endforelse
    </div>

    {{-- Pagination --}}
    @if($attempts->hasPages())
        <div class="mt-8">
            {{ $attempts->links() }}
        </div>
    @endif
</div>