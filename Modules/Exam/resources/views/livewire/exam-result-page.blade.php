<div class="min-h-screen bg-gray-50 py-8">
    <div class="max-w-5xl mx-auto px-4 sm:px-6 lg:px-8 space-y-6">

        {{-- Score Display Card --}}
        <x-card padding="p-6 sm:p-8 lg:p-10">
            <div class="text-center">
                <div class="mb-4">
                    <x-badge flat gray label="Kết quả bài thi" class="text-sm" />
                </div>

                <h1 class="text-2xl sm:text-3xl font-bold text-gray-900 mb-4">
                    {{ $attempt->exam->title }}
                </h1>

                {{-- Score Circle --}}
                <div class="flex justify-center mb-6">
                    <div class="relative">
                        <div class="w-32 h-32 sm:w-40 sm:h-40 rounded-full border-8 flex items-center justify-center
                            {{ $attempt->is_passed ? 'border-green-500 bg-green-50' : 'border-red-500 bg-red-50' }}">
                            <div class="text-center">
                                <div class="text-4xl sm:text-5xl font-bold 
                                    {{ $attempt->is_passed ? 'text-green-600' : 'text-red-600' }}">
                                    {{ number_format($this->statistics['score'], 0) }}
                                </div>
                                <div class="text-sm sm:text-base text-gray-600">điểm</div>
                            </div>
                        </div>
                    </div>
                </div>

                {{-- Breakdown Score --}}
                <div class="flex flex-wrap justify-center gap-3 mb-6">
                    <div class="inline-flex items-center gap-2 rounded-lg border border-blue-200 bg-blue-50 px-4 py-2">
                        <x-icon name="academic-cap" class="w-5 h-5 text-blue-600" />
                        <div class="text-left">
                            <p class="text-xs text-blue-700">Trắc nghiệm</p>
                            <p class="font-bold text-blue-900">
                                {{ number_format($attempt->getMultipleChoiceScore(), 0) }} điểm
                            </p>
                        </div>
                    </div>

                    <div
                        class="inline-flex items-center gap-2 rounded-lg border border-purple-200 bg-purple-50 px-4 py-2">
                        <x-icon name="document-text" class="w-5 h-5 text-purple-600" />
                        <div class="text-left">
                            <p class="text-xs text-purple-700">Tự luận</p>
                            <p class="font-bold text-purple-900">
                                @if($attempt->status === 'completed')
                                    {{ number_format($attempt->getEssayScore(), 0) }} điểm
                                @else
                                    Đang chấm
                                @endif
                            </p>
                        </div>
                    </div>
                </div>

                {{-- Pass/Fail Badge --}}
                <div class="inline-flex items-center gap-2 px-6 py-3 rounded-full border-2 
                    {{ $attempt->is_passed ? 'bg-green-50 border-green-200' : 'bg-red-50 border-red-200' }}">
                    @if($attempt->is_passed)
                        <x-icon name="check-circle" class="w-6 h-6 text-green-600" />
                        <span class="font-bold text-lg text-green-600">{{ $this->getStatusText() }}</span>
                    @else
                        <x-icon name="x-circle" class="w-6 h-6 text-red-600" />
                        <span class="font-bold text-lg text-red-600">{{ $this->getStatusText() }}</span>
                    @endif
                </div>

                {{-- Progress Bar --}}
                <div class="mt-6 max-w-md mx-auto">
                    <div class="w-full bg-gray-200 rounded-full h-3 overflow-hidden">
                        <div class="h-full rounded-full transition-all duration-500 
                            {{ $attempt->is_passed ? 'bg-green-500' : 'bg-red-500' }}"
                            style="width: {{ $this->statistics['percent'] }}%">
                        </div>
                    </div>
                    <p class="text-sm text-gray-600 mt-2">
                        {{ number_format($this->statistics['percent'], 1) }}%
                        (Điểm đạt: {{ number_format($this->statistics['pass_percent'], 0) }}%)
                    </p>
                </div>
            </div>
        </x-card>

        {{-- Statistics Cards --}}
        <div class="grid grid-cols-3 gap-2 sm:gap-4">
            {{-- Correct Answers --}}
            <x-card padding="p-3 sm:p-5 lg:p-6"
                class="bg-gradient-to-br from-green-50 to-emerald-50 border-2 border-green-200">
                <div class="flex flex-col sm:flex-row items-center sm:items-start gap-2 sm:gap-4">
                    <div class="p-2 sm:p-3 bg-green-500 rounded-xl shrink-0">
                        <x-icon name="check-circle" class="w-5 h-5 sm:w-6 sm:h-6 text-white" />
                    </div>
                    <div class="flex-1 min-w-0 text-center sm:text-left">
                        <div class="text-xs sm:text-sm text-green-700 font-medium mb-1">Câu trả lời đúng</div>
                        <div class="text-2xl sm:text-3xl font-bold text-green-900">{{ $this->statistics['correct'] }}
                        </div>
                        <div class="text-xs text-green-600 mt-1">
                            {{ $this->calculatePercentage($this->statistics['correct']) }}% tổng số câu
                        </div>
                    </div>
                </div>
            </x-card>

            {{-- Wrong Answers --}}
            <x-card padding="p-3 sm:p-5 lg:p-6"
                class="bg-gradient-to-br from-red-50 to-rose-50 border-2 border-red-200">
                <div class="flex flex-col sm:flex-row items-center sm:items-start gap-2 sm:gap-4">
                    <div class="p-2 sm:p-3 bg-red-500 rounded-xl shrink-0">
                        <x-icon name="x-circle" class="w-5 h-5 sm:w-6 sm:h-6 text-white" />
                    </div>
                    <div class="flex-1 min-w-0 text-center sm:text-left">
                        <div class="text-xs sm:text-sm text-red-700 font-medium mb-1">Câu trả lời sai</div>
                        <div class="text-2xl sm:text-3xl font-bold text-red-900">{{ $this->statistics['wrong'] }}</div>
                        <div class="text-xs text-red-600 mt-1">
                            {{ $this->calculatePercentage($this->statistics['wrong']) }}% tổng số câu
                        </div>
                    </div>
                </div>
            </x-card>

            {{-- Skipped Answers --}}
            <x-card padding="p-3 sm:p-5 lg:p-6"
                class="bg-gradient-to-br from-gray-50 to-slate-50 border-2 border-gray-200">
                <div class="flex flex-col sm:flex-row items-center sm:items-start gap-2 sm:gap-4">
                    <div class="p-2 sm:p-3 bg-gray-500 rounded-xl shrink-0">
                        <x-icon name="minus-circle" class="w-5 h-5 sm:w-6 sm:h-6 text-white" />
                    </div>
                    <div class="flex-1 min-w-0 text-center sm:text-left">
                        <div class="text-xs sm:text-sm text-gray-700 font-medium mb-1">Câu bỏ qua</div>
                        <div class="text-2xl sm:text-3xl font-bold text-gray-900">{{ $this->statistics['skipped'] }}
                        </div>
                        <div class="text-xs text-gray-600 mt-1">
                            {{ $this->calculatePercentage($this->statistics['skipped']) }}% tổng số câu
                        </div>
                    </div>
                </div>
            </x-card>
        </div>

        {{-- Additional Info Card --}}
        <x-card padding="p-4 sm:p-5">
            <div class="flex flex-wrap items-center justify-center gap-4 sm:gap-6 text-sm text-gray-600">
                <div class="flex items-center gap-2">
                    <x-icon name="clock" class="w-5 h-5 text-gray-400" />
                    <span class="font-medium">Thời gian làm bài:</span>
                    <span class="text-gray-900 font-semibold">{{ $this->statistics['duration'] }}</span>
                </div>
                <div class="hidden sm:block w-px h-6 bg-gray-300"></div>
                <div class="flex items-center gap-2">
                    <x-icon name="calendar" class="w-5 h-5 text-gray-400" />
                    <span class="font-medium">Nộp bài lúc:</span>
                    <span class="text-gray-900 font-semibold">
                        {{ $attempt->submitted_at ? $attempt->submitted_at->format('d/m/Y H:i') : 'N/A' }}
                    </span>
                </div>
            </div>
        </x-card>
        {{-- Nhận xét chung của giáo viên --}}
        @if($attempt->teacher_comment)
            <div class="bg-blue-50 border-l-4 border-blue-500 p-4 rounded mb-4">
                <div class="flex gap-3">
                    <x-icon name="information-circle" class="w-5 h-5 text-blue-600 shrink-0 mt-0.5" />
                    <div>
                        <h4 class="font-semibold text-blue-900 text-sm mb-1">Nhận xét chung:</h4>
                        <div class="text-sm text-blue-800 ql-editor">{!! $attempt->teacher_comment !!}</div>
                    </div>
                </div>
            </div>
        @endif

        {{-- Filter Buttons --}}
        <x-card padding="p-4 sm:p-5">
            <div class="flex flex-col sm:flex-row sm:items-center gap-3">
                <div class="text-sm font-semibold text-gray-700">Lọc câu hỏi:</div>
                <div class="flex flex-wrap gap-2">
                    <x-button wire:click="setFilter('all')" label="Tất cả ({{ $this->statistics['total'] }})"
                        :outline="$filter !== 'all'" :primary="$filter === 'all'" sm />

                    <x-button wire:click="setFilter('correct')" label="Đúng ({{ $this->statistics['correct'] }})"
                        :outline="$filter !== 'correct'" :positive="$filter === 'correct'" sm />

                    <x-button wire:click="setFilter('wrong')" label="Sai ({{ $this->statistics['wrong'] }})"
                        :outline="$filter !== 'wrong'" :negative="$filter === 'wrong'" sm />

                    <x-button wire:click="setFilter('skipped')" label="Bỏ qua ({{ $this->statistics['skipped'] }})"
                        :outline="$filter !== 'skipped'" gray sm />
                </div>
            </div>
        </x-card>

        {{-- Pagination Info --}}
        @if($this->filteredAnswers->total() > 0)
            <div class="text-sm text-gray-600 text-center">
                Hiển thị {{ $this->filteredAnswers->firstItem() }} - {{ $this->filteredAnswers->lastItem() }}
                / {{ $this->filteredAnswers->total() }} câu
            </div>
        @else
            <div class="text-center py-8">
                <x-icon name="document-text" class="w-12 h-12 mx-auto text-gray-400 mb-3" />
                <p class="text-gray-600">Không có câu hỏi nào phù hợp với bộ lọc.</p>
            </div>
        @endif

        {{-- Question Review Cards --}}
        @if($attempt->exam->mode === 'official' && $attempt->status !== 'completed')
            <x-card padding="p-8" class="text-center">
                <x-icon name="exclamation-triangle" class="w-14 h-14 text-amber-500 mx-auto mb-4" />

                <h3 class="text-lg font-semibold text-gray-900 mb-2">
                    Chưa thể xem kết quả
                </h3>

                <p class="text-gray-600">
                    Đây là <strong>đề thi chính thức</strong>. Kết quả và đáp án chỉ được hiển thị sau khi bài thi đã được
                    chấm hoàn tất.
                    Vui lòng chờ giảng viên chấm bài.
                </p>
            </x-card>
        @else
            <div class="space-y-6">
                @foreach($this->filteredAnswers as $answer)
                    @php
                        $question = $answer->question;
                        $questionNumber = $this->getQuestionNumber($loop->iteration);
                        $borderColor = $answer->answered_at === null ? 'border-gray-400' :
                            ($answer->is_correct ? 'border-green-500' : 'border-red-500');
                    @endphp

                    <x-card padding="p-5 sm:p-6" class="border-l-4 {{ $borderColor }}">
                        {{-- Question Header --}}
                        <div class="flex items-start gap-3 mb-4">
                            <x-badge flat gray label="Câu {{ $this->getQuestionSortOrder($question->id) }}" class="shrink-0" />

                            @if($answer->answered_at === null)
                                <x-badge flat gray>
                                    <x-icon name="minus-circle" class="w-4 h-4 mr-1 inline" />
                                    Bỏ qua
                                </x-badge>
                            @elseif($answer->is_correct)
                                <x-badge flat positive>
                                    <x-icon name="check-circle" class="w-4 h-4 mr-1 inline" />
                                    Đúng
                                </x-badge>
                            @elseif($answer->status === 'incorrect')
                                <x-badge flat negative>
                                    <x-icon name="x-circle" class="w-4 h-4 mr-1 inline" />
                                    Sai
                                </x-badge>
                            @else
                                <x-badge flat gray>
                                    <x-icon name="minus-circle" class="w-4 h-4 mr-1 inline" />
                                    Chưa chấm
                                </x-badge>
                            @endif
                        </div>

                        {{-- Question Content --}}
                        <h3 class="text-base sm:text-lg font-semibold text-gray-900 mb-4">
                            <div class="ql-editor">{!! $question->content !!}</div>
                        </h3>

                        {{-- Options Display for Choice Questions --}}
                        @if(in_array($question->type, ['single_choice', 'multiple_choice']))
                            <div class="space-y-3 mb-4">
                                @foreach($question->options as $option)
                                    @php
                                        $isCorrect = $this->isCorrectOption($option);
                                        $isSelected = $this->isOptionSelected($answer, $option->id);

                                        // Determine styling based on correctness and selection
                                        if ($isCorrect && $isSelected) {
                                            // User selected the correct answer
                                            $bgClass = 'bg-green-50 border-2 border-green-500';
                                            $iconClass = 'bg-green-500';
                                            $iconType = 'check';
                                            $textClass = 'text-green-900 font-semibold';
                                            $showUserBadge = true;
                                            $showCorrectBadge = true;
                                            $badgeColor = 'positive';
                                        } elseif ($isCorrect && !$isSelected) {
                                            // Correct answer but user didn't select it
                                            $bgClass = 'bg-green-50 border-2 border-green-500';
                                            $iconClass = 'bg-green-500';
                                            $iconType = 'check';
                                            $textClass = 'text-green-900 font-semibold';
                                            $showUserBadge = false;
                                            $showCorrectBadge = true;
                                            $badgeColor = 'positive';
                                        } elseif (!$isCorrect && $isSelected) {
                                            // User selected wrong answer
                                            $bgClass = 'bg-red-50 border-2 border-red-500';
                                            $iconClass = 'bg-red-500';
                                            $iconType = 'x-mark';
                                            $textClass = 'text-red-900 font-semibold';
                                            $showUserBadge = true;
                                            $showCorrectBadge = false;
                                            $badgeColor = 'negative';
                                        } else {
                                            // Neither correct nor selected
                                            $bgClass = 'bg-gray-50 border border-gray-200';
                                            $iconClass = 'border-2 border-gray-300';
                                            $iconType = null;
                                            $textClass = 'text-gray-700';
                                            $showUserBadge = false;
                                            $showCorrectBadge = false;
                                            $badgeColor = null;
                                        }

                                        // Add opacity for skipped questions
                                        if ($answer->answered_at === null && !$isCorrect) {
                                            $bgClass .= ' opacity-60';
                                        }
                                    @endphp

                                    <div class="flex items-start gap-3 p-3 rounded-lg {{ $bgClass }}">
                                        <div class="shrink-0 mt-1">
                                            <div class="w-5 h-5 rounded-full {{ $iconClass }} flex items-center justify-center">
                                                @if($iconType)
                                                    <x-icon name="{{ $iconType }}" class="w-3 h-3 text-white" />
                                                @endif
                                            </div>
                                        </div>
                                        <div class="flex-1">
                                            <div class="text-sm sm:text-base {{ $textClass }}">
                                                {{ $option->option_key }}. {!! $option->content !!}

                                                @if($showUserBadge && $badgeColor === 'positive')
                                                    <x-badge flat positive label="Đáp án của bạn" class="ml-2" />
                                                @elseif($showUserBadge && $badgeColor === 'negative')
                                                    <x-badge flat negative label="Đáp án của bạn" class="ml-2" />
                                                @endif

                                                @if($showCorrectBadge)
                                                    <x-badge flat positive label="Đáp án đúng" class="ml-1" />
                                                @endif
                                            </div>
                                        </div>
                                    </div>
                                @endforeach
                            </div>
                        @endif

                        {{-- Essay Answer Display --}}
                        @if($question->type === 'essay')
                            <div class="bg-green-50 border-l-4 border-green-500 p-4 rounded mb-2">
                                <div class="flex gap-3">
                                    <x-icon name="check-circle" class="w-5 h-5 text-green-600 shrink-0 mt-0.5" />
                                    <div>
                                        <h4 class="font-semibold text-green-900 text-sm mb-1">Đáp án của bạn:</h4>
                                        <div class="text-sm text-green-800 ql-editor">{!! $answer->answer_text !!}</div>
                                    </div>
                                </div>
                            </div>
                            @if($question->answer_text)
                                <div class="bg-green-50 border-l-4 border-green-500 p-4 rounded mb-2">
                                    <div class="flex gap-3">
                                        <x-icon name="check-circle" class="w-5 h-5 text-green-600 shrink-0 mt-0.5" />
                                        <div>
                                            <h4 class="font-semibold text-green-900 text-sm mb-1">Đáp án mẫu:</h4>
                                            <div class="text-sm text-green-800 ql-editor">{!! $question->answer_text !!}</div>
                                        </div>
                                    </div>
                                </div>
                            @endif
                        @endif

                        {{-- Skipped Question Message --}}
                        @if($answer->answered_at === null)
                            <div class="bg-gray-50 border-l-4 border-gray-400 p-4 rounded mb-2">
                                <p class="text-sm text-gray-600 italic">Bạn đã bỏ qua câu hỏi này.</p>
                            </div>
                        @endif

                        {{-- Question Explanation --}}
                        @if($question->explanation)
                            <div class="bg-blue-50 border-l-4 border-blue-500 p-4 rounded mb-4">
                                <div class="flex gap-3">
                                    <x-icon name="information-circle" class="w-5 h-5 text-blue-600 shrink-0 mt-0.5" />
                                    <div>
                                        <h4 class="font-semibold text-blue-900 text-sm mb-1">Giải thích đáp án:</h4>
                                        <div class="text-sm text-blue-800 ql-editor">{!! $question->explanation !!}</div>
                                    </div>
                                </div>
                            </div>
                        @endif
                        {{-- Nhan xet cua giao vien --}}
                        @if($answer->teacher_comment)
                            <div class="bg-blue-50 border-l-4 border-blue-500 p-4 rounded mb-4">
                                <div class="flex gap-3">
                                    <x-icon name="information-circle" class="w-5 h-5 text-blue-600 shrink-0 mt-0.5" />
                                    <div>
                                        <h4 class="font-semibold text-blue-900 text-sm mb-1">Nhận xét:</h4>
                                        <div class="text-sm text-blue-800 ql-editor">{!! $answer->teacher_comment !!}</div>
                                    </div>
                                </div>
                            </div>
                        @endif
                    </x-card>
                @endforeach
            </div>
        @endif

        {{-- Pagination --}}
        @if($this->filteredAnswers->hasPages())
            <div class="mt-6">
                {{ $this->filteredAnswers->links() }}
            </div>
        @endif

        {{-- Action Buttons --}}
        <div class="flex flex-row gap-3 sm:gap-4 justify-center mt-8 pb-8">
            <x-button outline slate md lg:xl label="Về trang danh sách" icon="arrow-left"
                href="{{ route('exam.results') }}" />

            @if($attempt->exam->mode === 'practice')
                <x-button positive md lg:xl label="Làm lại bài thi" icon="arrow-path" href="#" />{{--route('exam.detail')
                }}?exam={{ $attempt->exam->slug --}}
            @endif
        </div>

    </div>
</div>