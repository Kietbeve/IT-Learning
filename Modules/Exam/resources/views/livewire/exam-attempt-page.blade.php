{{-- CHUNK 1: Main structure, header, timer, question area (lines 1-300) --}}
<div 
    x-data="{
        init() {
            this.$wire.on('scroll-to-top', () => {
                window.scrollTo({ top: 0, behavior: 'smooth' });
            });

            document.addEventListener(
                'visibilitychange',
                () => {

                    if (document.hidden) {
                        $wire.recordTabSwitch();
                    }

                }
            );

            document.addEventListener(
                'fullscreenchange',
                () => {
                    if (!document.fullscreenElement) {
                        $wire.handleFullscreenExit();
                    }
                }
            );

            this.$wire.on('enter-fullscreen', () => {
                document.documentElement.requestFullscreen?.();
            });
        }
    }"
    x-init="
        document.documentElement.requestFullscreen?.()
    "
    class="min-h-screen bg-gray-50">
    {{-- Modal cảnh báo chuyển tab --}}
    @if($showWarningModal)
        <div class="fixed inset-0 z-[99999] flex items-center justify-center bg-black/50 p-4">

            <div class="w-full max-w-md rounded-xl bg-white shadow-xl">

                {{-- Header --}}
                <div class="border-b px-6 py-4">
                    <h2 class="text-lg font-semibold text-red-600">
                        Cảnh báo
                    </h2>
                </div>

                {{-- Body --}}
                <div class="px-6 py-4 space-y-3">
                    <p>
                        Bạn đã rời khỏi màn hình thi
                        <strong>{{ $tabSwitchCount }}</strong>
                        lần.
                    </p>

                    <p class="text-sm text-gray-600">
                        Hệ thống đang ghi nhận các lần rời khỏi màn hình thi.
                        Việc chuyển tab nhiều lần có thể bị xem là hành vi không phù hợp.
                    </p>
                </div>

                {{-- Footer --}}
                <div class="flex justify-end gap-2 border-t px-6 py-4">
                    <button
                        type="button"
                        wire:click="$set('showWarningModal', false)"
                        class="rounded-lg bg-indigo-600 px-4 py-2 text-white hover:bg-indigo-700"
                    >
                        Tôi hiểu
                    </button>
                </div>

            </div>

        </div>
    @endif

    {{-- Modal yêu cầu fullscreen cho Official exam --}}
    @if($showFullscreenModal)
        <div class="fixed inset-0 z-[99999] flex items-center justify-center bg-black/50 p-4">

            <div class="w-full max-w-md rounded-xl bg-white shadow-xl">

                {{-- Header --}}
                <div class="border-b px-6 py-4">
                    <div class="flex items-center gap-3">
                        <svg class="w-6 h-6 text-orange-600" fill="none" stroke="currentColor" viewBox="0 0 24 24">
                            <path stroke-linecap="round" stroke-linejoin="round" stroke-width="2" d="M4 8V4m0 0h4M4 4l5 5m11-1V4m0 0h-4m4 0l-5 5M4 16v4m0 0h4m-4 0l5-5m11 5l-5-5m5 5v-4m0 4h-4"/>
                        </svg>
                        <h2 class="text-lg font-semibold text-orange-600">
                            Yêu cầu toàn màn hình
                        </h2>
                    </div>
                </div>

                {{-- Body --}}
                <div class="px-6 py-4 space-y-3">
                    <p class="text-gray-700">
                        Bài thi <strong class="text-orange-600">chính thức</strong> yêu cầu chế độ toàn màn hình để đảm bảo tính công bằng.
                    </p>

                    <p class="text-sm text-gray-600">
                        Vui lòng quay lại chế độ toàn màn hình để tiếp tục làm bài.
                    </p>

                    <div class="bg-orange-50 border border-orange-200 rounded-lg p-3">
                        <p class="text-sm text-orange-800">
                            ⚠️ Việc thoát chế độ toàn màn hình có thể bị ghi nhận là vi phạm.
                        </p>
                    </div>
                </div>

                {{-- Footer --}}
                <div class="flex justify-end gap-2 border-t px-6 py-4">
                    <button
                        type="button"
                        wire:click="requestFullscreen"
                        class="rounded-lg bg-orange-600 px-6 py-2.5 text-white hover:bg-orange-700 transition font-medium"
                    >
                        🖥️ Quay lại toàn màn hình
                    </button>
                </div>

            </div>

        </div>
    @endif

    {{-- Header --}}
    <header class="bg-white border-b border-gray-200 z-[60]">
        <div class="max-w-7xl mx-auto">
            {{-- Main content row --}}
            <div class="flex items-center justify-between gap-2 px-3 py-2 md:py-2.5">
                {{-- Timer --}}
                <div 
                    x-data="{
                        startedAt: new Date('{{ $attempt->started_at }}'),
                        durationMinutes: {{ $attempt->exam->duration_minutes }},
                        timeRemaining: 0,
                        
                        init() {
                            this.calculateTime();
                            setInterval(() => this.calculateTime(), 1000);
                        },
                        
                        calculateTime() {
                            const now = new Date();
                            const elapsed = Math.floor((now - this.startedAt) / 1000);
                            const total = this.durationMinutes * 60;
                            this.timeRemaining = Math.max(0, total - elapsed);
                        },
                        
                        formatTime() {
                            const hours = Math.floor(this.timeRemaining / 3600);
                            const minutes = Math.floor((this.timeRemaining % 3600) / 60);
                            const seconds = this.timeRemaining % 60;
                            return String(hours).padStart(2, '0') + ':' + 
                                   String(minutes).padStart(2, '0') + ':' + 
                                   String(seconds).padStart(2, '0');
                        }
                    }"
                    class="flex items-center gap-1.5 text-red-600 shrink-0">
                    <svg class="w-4 h-4" fill="none" stroke="currentColor" viewBox="0 0 24 24">
                        <path stroke-linecap="round" stroke-linejoin="round" stroke-width="2" d="M12 8v4l3 3m6-3a9 9 0 11-18 0 9 9 0 0118 0z"/>
                    </svg>
                    <span class="font-mono text-sm font-semibold" x-text="formatTime()">00:00:00</span>
                    <div class="text-xs text-amber-600">
                        ⚠ Rời màn hình: {{ $tabSwitchCount }}
                    </div>
                </div>

                {{-- Progress text --}}
                <div class="flex items-center gap-1.5 text-gray-700 shrink-0">
                    <span class="text-sm font-medium">
                        <span class="font-semibold text-indigo-600">{{ $this->getAnsweredCount() }}</span>/<span class="text-gray-500">{{ count($questionIds) }}</span>
                    </span>
                    <span class="hidden md:inline text-xs text-gray-500">câu</span>
                    <span class="hidden sm:inline text-xs text-gray-400">({{ $this->getProgress() }}%)</span>
                </div>

                {{-- Progress bar (desktop only) --}}
                <div class="hidden md:flex flex-1 items-center gap-2 max-w-xs">
                    <div class="flex-1 h-2 bg-gray-200 rounded-full overflow-hidden">
                        <div 
                            class="h-full bg-gradient-to-r from-indigo-500 to-indigo-600 rounded-full transition-all duration-300"
                            style="width: {{ $this->getProgress() }}%">
                        </div>
                    </div>
                    <span class="text-xs font-medium text-gray-600 w-8 text-right">{{ $this->getProgress() }}%</span>
                </div>

                {{-- Menu button --}}
                <button 
                    wire:click="$set('showQuestionModal', true)"
                    class="flex items-center justify-center w-9 h-9 rounded-lg hover:bg-gray-100 transition-colors shrink-0">
                    <svg class="w-5 h-5 text-gray-600" fill="none" stroke="currentColor" viewBox="0 0 24 24">
                        <path stroke-linecap="round" stroke-linejoin="round" stroke-width="2" d="M4 6h16M4 12h16M4 18h16"/>
                    </svg>
                </button>
            </div>

            {{-- Progress bar (mobile only - full width) --}}
            <div class="md:hidden h-1.5 bg-gray-200">
                <div 
                    class="h-full bg-gradient-to-r from-indigo-500 to-indigo-600 transition-all duration-300"
                    style="width: {{ $this->getProgress() }}%">
                </div>
            </div>
        </div>
    </header>

    {{-- Main Content --}}
    <main class="pt-14 md:pt-12 pb-8 px-4">
        <div class="max-w-4xl mx-auto">
            <x-card>
                @if ($attempt->status=='submitted')
                <div class="mb-6 p-4 bg-green-50 border border-green-200 rounded-lg">
                    <div class="flex items-center gap-3">
                        <x-icon name="check-circle" class="w-6 h-6 text-green-600" />
                        <div>
                            <h3 class="font-semibold text-green-900">Đã nộp bài thành công</h3>
                            <p class="text-sm text-green-700">Bài thi của bạn đã được lưu lại.</p>
                        </div>
                    </div>
                </div>
                @endif
                
                {{-- Question Display --}}
                @if($this->getCurrentQuestion())
                    @php
                        $question = $this->getCurrentQuestion();
                        $isAnswered = $this->isQuestionAnswered($currentQuestionIndex);
                    @endphp

                    {{-- Question Container with unique wire:key for proper DOM tracking --}}
                    <div wire:key="question-{{ $question->id }}-{{ $currentQuestionIndex }}">

                    {{-- Question Header --}}
                    <div class="flex items-start justify-between gap-4 mb-6 pb-4 border-b border-gray-100">
                        <div class="flex-1">
                            <div class="flex items-center gap-2 mb-3">
                                <x-badge flat primary label="Câu {{ $currentQuestionIndex + 1 }}" />
                                @if($question->difficulty)
                                    <x-badge flat gray>
                                        <span class="capitalize">{{ $question->difficulty }}</span>
                                    </x-badge>
                                @endif
                            </div>
                            <h2 class="text-lg md:text-xl font-semibold text-gray-900 leading-relaxed">
                                {{ $question->content }}
                            </h2>
                        </div>
                    </div>

                    {{-- Answer Area based on Type --}}
                    <div class="mb-6" x-bind:class="{ 'opacity-50 pointer-events-none':{{$attempt->status=='submitted'}} }">
                        @if($question->type === 'single_choice')
                            {{-- Single Choice: Radio buttons --}}
                            <div class="space-y-3">
                                @foreach($question->options as $option)
                                    <label 
                                        wire:key="option-{{ $question->id }}-{{ $option->id }}"
                                        class="flex items-start gap-3 p-4 border-2 rounded-lg cursor-pointer transition
                                               {{ isset($userAnswers[$question->id]) && in_array($option->id, $userAnswers[$question->id]) 
                                                  ? 'border-indigo-500 bg-indigo-50' 
                                                  : 'border-gray-200 hover:border-gray-300' }}">
                                        <input 
                                            type="radio" 
                                            name="question_{{ $question->id }}"
                                            value="{{ $option->id }}"
                                            wire:change="saveAnswer({{ $question->id }}, [{{ $option->id }}])"
                                            {{ isset($userAnswers[$question->id]) && in_array($option->id, $userAnswers[$question->id]) ? 'checked' : '' }}
                                            class="mt-1 w-4 h-4 text-indigo-600">
                                        <div class="flex-1">
                                            <div class="font-semibold text-gray-900">
                                                {{ $option->option_key }}. {{ $option->content }}
                                            </div>
                                        </div>
                                    </label>
                                @endforeach
                            </div>

                        @elseif($question->type === 'multiple_choice')
                            {{-- Multiple Choice: Checkboxes --}}
                            <div class="space-y-3">
                                @foreach($question->options as $option)
                                    <label 
                                        wire:key="option-{{ $question->id }}-{{ $option->id }}"
                                        class="flex items-start gap-3 p-4 border-2 rounded-lg cursor-pointer transition
                                               {{ isset($userAnswers[$question->id]) && in_array($option->id, $userAnswers[$question->id]) 
                                                  ? 'border-indigo-500 bg-indigo-50' 
                                                  : 'border-gray-200 hover:border-gray-300' }}">
                                        <input 
                                            type="checkbox" 
                                            value="{{ $option->id }}"
                                            wire:change="saveAnswer({{ $question->id }}, $event.target.checked, {{ $option->id }})"
                                            {{ isset($userAnswers[$question->id]) && in_array($option->id, $userAnswers[$question->id]) ? 'checked' : '' }}
                                            class="mt-1 w-4 h-4 text-indigo-600 rounded">
                                        <div class="flex-1">
                                            <div class="font-semibold text-gray-900">
                                                {{ $option->option_key }}. {{ $option->content }}
                                            </div>
                                        </div>
                                    </label>
                                @endforeach
                            </div>

                        @elseif($question->type === 'essay')
                            {{-- Essay: Textarea with character counter --}}
                            <div>
                                <x-textarea 
                                    wire:model.live.debounce.500ms="userAnswers.{{ $question->id }}"
                                    placeholder="Nhập câu trả lời của bạn (tối đa 100 ký tự)..."
                                    rows="6"
                                    maxlength="100" />
                                
                                <div class="mt-2 flex items-center justify-between text-xs">
                                    <span class="text-gray-500">
                                        <span class="font-medium">
                                            {{ strlen($userAnswers[$question->id] ?? '') }}
                                        </span>/100 ký tự
                                    </span>
                                    @if(strlen($userAnswers[$question->id] ?? '') >= 100)
                                        <span class="text-red-600 font-medium">Đã đạt giới hạn</span>
                                    @endif
                                </div>
                            </div>
                        @endif
                    </div>

                    {{-- Save Indicator --}}
                    <div class="mb-6">
                        <div 
                            wire:loading 
                            wire:target="saveAnswer"
                            class="text-sm text-gray-600 flex items-center gap-2">
                            <svg class="w-4 h-4 animate-spin" fill="none" viewBox="0 0 24 24">
                                <circle class="opacity-25" cx="12" cy="12" r="10" stroke="currentColor" stroke-width="4"></circle>
                                <path class="opacity-75" fill="currentColor" d="M4 12a8 8 0 018-8V0C5.373 0 0 5.373 0 12h4zm2 5.291A7.962 7.962 0 014 12H0c0 3.042 1.135 5.824 3 7.938l3-2.647z"></path>
                            </svg>
                            <span>Đang lưu...</span>
                        </div>
                        
                        <div 
                            wire:loading.remove 
                            wire:target="saveAnswer"
                            x-data="{ show: false }"
                            x-show="show"
                            x-init="
                                $wire.on('answer-saved', () => {
                                    show = true;
                                    setTimeout(() => show = false, 2000);
                                })
                            "
                            style="display: none;"
                            class="text-sm text-green-600 flex items-center gap-2">
                            <x-icon name="check-circle" class="w-4 h-4" />
                            <span>Đã lưu</span>
                        </div>
                    </div>

                    {{-- Navigation Buttons --}}
                    <div class="flex flex-row items-center justify-between gap-2 pt-6 border-t border-gray-100">
                        <x-button 
                            outline
                            gray
                            left-icon="arrow-left"
                            label="Trước"
                            wire:click="previousQuestion"
                            wire:loading.attr="disabled"
                            wire:loading.class="opacity-50"
                            :disabled="$currentQuestionIndex === 0"
                            class="flex-1 sm:flex-none" />
                        
                        <x-button 
                            outline
                            indigo
                            label="{{ $currentQuestionIndex + 1 }}/{{ count($questionIds) }}"
                            wire:click="$set('showQuestionModal', true)"
                            class="flex-1 sm:flex-none hidden sm:inline-flex" />
                        
                        {{-- Submit Button - Visible at footer --}}
                        <x-button 
                            primary
                            label="Nộp bài"
                            wire:click="$set('showSubmitModal', true)"
                            x-bind:disabled="examSubmitted"
                            class="flex-1 sm:flex-none" />
                        
                        <x-button 
                            outline
                            gray
                            right-icon="arrow-right"
                            label="Tiếp"
                            wire:click="nextQuestion"
                            wire:loading.attr="disabled"
                            wire:loading.class="opacity-50"
                            :disabled="$currentQuestionIndex === count($questionIds) - 1"
                            class="flex-1 sm:flex-none" />
                    </div>

                    </div>{{-- End Question Container --}}
                @else
                    {{-- No questions state --}}
                    <div class="text-center py-10">
                        <x-icon name="document-text" class="w-12 h-12 mx-auto text-gray-400" />
                        <h3 class="mt-2 text-sm font-semibold text-gray-900">Không có câu hỏi</h3>
                        <p class="mt-1 text-sm text-gray-500">Đề thi này chưa có câu hỏi nào.</p>
                    </div>
                @endif
            </x-card>
        </div>
    </main>

    {{-- Question Navigation Modal - Custom Alpine.js --}}
    <div 
        x-show="$wire.showQuestionModal"
        x-cloak
        class="fixed inset-0 z-50 overflow-y-auto"
        style="display: none;">
        
        {{-- Modal Container --}}
        <div class="flex min-h-full items-center justify-center p-4">
            <div 
                @click.stop
                class="relative bg-white rounded-lg shadow-xl max-w-3xl w-full p-6 z-50">
                
                {{-- Header --}}
                <div class="flex items-center justify-between mb-4 pb-4 border-b">
                    <h3 class="text-lg font-semibold text-gray-900">Danh sách câu hỏi</h3>
                    <button 
                        @click="$wire.set('showQuestionModal', false)"
                        class="text-gray-400 hover:text-gray-600 transition">
                        <svg class="w-6 h-6" fill="none" stroke="currentColor" viewBox="0 0 24 24">
                            <path stroke-linecap="round" stroke-linejoin="round" stroke-width="2" d="M6 18L18 6M6 6l12 12"/>
                        </svg>
                    </button>
                </div>
                
                {{-- Legend --}}
                <div class="flex flex-wrap items-center gap-4 mb-4 text-sm">
                    <div class="flex items-center gap-2">
                        <div class="w-3 h-3 rounded-full bg-green-500"></div>
                        <span class="text-gray-700">Đã làm</span>
                    </div>
                    <div class="flex items-center gap-2">
                        <div class="w-3 h-3 rounded-full bg-indigo-500"></div>
                        <span class="text-gray-700">Câu hiện tại</span>
                    </div>
                    <div class="flex items-center gap-2">
                        <div class="w-3 h-3 rounded-full bg-gray-300"></div>
                        <span class="text-gray-700">Chưa làm</span>
                    </div>
                </div>
                
                {{-- Question Grid - 10 số 1 hàng --}}
                <div class="grid grid-cols-10 gap-2 mb-6">
                    @foreach($this->questions() as $index => $question)
                        @php
                            $isAnswered = $this->isQuestionAnswered($index);
                            $isCurrent = $index === $currentQuestionIndex;
                        @endphp
                        
                        <button 
                            wire:click="goToQuestion({{ $index }})"
                            class="aspect-square rounded-lg font-semibold text-xs transition
                                   flex items-center justify-center
                                   {{ $isCurrent ? 'bg-indigo-500 text-white ring-2 ring-indigo-300' : '' }}
                                   {{ !$isCurrent && $isAnswered ? 'bg-green-500 text-white hover:bg-green-600' : '' }}
                                   {{ !$isCurrent && !$isAnswered ? 'bg-gray-200 text-gray-700 hover:bg-gray-300' : '' }}">
                            {{ $index + 1 }}
                        </button>
                    @endforeach
                </div>
                
                {{-- Progress --}}
                <div class="pt-4 border-t border-gray-100">
                    <div class="flex justify-between text-sm mb-2">
                        <span class="text-gray-600">Tiến độ</span>
                        <span class="font-semibold text-gray-900">
                            {{ $this->getAnsweredCount() }}/{{ count($questionIds) }}
                        </span>
                    </div>
                    <div class="w-full bg-gray-200 rounded-full h-2.5">
                        <div 
                            class="bg-green-500 h-2.5 rounded-full transition-all duration-300"
                            style="width: {{ $this->getProgress() }}%">
                        </div>
                    </div>
                    <p class="text-xs text-gray-500 mt-2">
                        {{ $this->getProgress() }}% hoàn thành
                    </p>
                </div>
                
                {{-- Footer --}}
                <div class="flex justify-end gap-3 mt-6">
                    <button
                        @click="$wire.set('showQuestionModal', false)"
                        class="px-4 py-2 text-gray-700 bg-gray-100 hover:bg-gray-200 rounded-lg transition">
                        Đóng
                    </button>
                </div>
            </div>
        </div>
    </div>

    {{-- Submit Confirmation Modal - Custom Alpine.js --}}
    <div 
        x-show="$wire.showSubmitModal"
        x-cloak
        @click="$wire.set('showSubmitModal', false)"
        class="fixed inset-0 z-50 overflow-y-auto"
        style="display: none;"
        x-data="{ confirmUnanswered: false }">
        
        {{-- Modal Container --}}
        <div class="flex min-h-full items-center justify-center p-4">
            <div 
                @click.stop
                class="relative bg-white rounded-lg shadow-xl max-w-md w-full p-6 z-50">
                
                {{-- Content --}}
                <div class="text-center">
                    <div class="mx-auto flex items-center justify-center h-16 w-16 rounded-full bg-yellow-100 mb-4">
                        <svg class="h-8 w-8 text-yellow-600" fill="none" stroke="currentColor" viewBox="0 0 24 24">
                            <path stroke-linecap="round" stroke-linejoin="round" stroke-width="2" d="M12 9v2m0 4h.01m-6.938 4h13.856c1.54 0 2.502-1.667 1.732-3L13.732 4c-.77-1.333-2.694-1.333-3.464 0L3.34 16c-.77 1.333.192 3 1.732 3z"/>
                        </svg>
                    </div>
                    
                    <h3 class="text-xl font-bold text-gray-900 mb-3">
                        Xác nhận nộp bài
                    </h3>
                    
                    <p class="text-base text-gray-600 mb-4">
                        Bạn đã hoàn thành <strong>{{ $this->getAnsweredCount() }}</strong>/<strong>{{ count($questionIds) }}</strong> câu hỏi.
                    </p>
                    
                    {{-- Unanswered questions warning --}}
                    @php
                        $unansweredNumbers = $this->getUnansweredQuestionNumbers();
                        $hasUnanswered = count($unansweredNumbers) > 0;
                    @endphp
                    
                    @if($hasUnanswered)
                        <div class="bg-red-50 border-2 border-red-300 rounded-lg p-4 mb-4 text-left">
                            <div class="flex items-start gap-2 mb-3">
                                <svg class="w-5 h-5 text-red-600 flex-shrink-0 mt-0.5" fill="none" stroke="currentColor" viewBox="0 0 24 24">
                                    <path stroke-linecap="round" stroke-linejoin="round" stroke-width="2" d="M12 9v2m0 4h.01m-6.938 4h13.856c1.54 0 2.502-1.667 1.732-3L13.732 4c-.77-1.333-2.694-1.333-3.464 0L3.34 16c-.77 1.333.192 3 1.732 3z"/>
                                </svg>
                                <div class="flex-1">
                                    <h4 class="font-bold text-red-800 mb-2">
                                        Còn {{ count($unansweredNumbers) }} câu chưa làm
                                    </h4>
                                    <p class="text-sm text-red-700 mb-3">
                                        Các câu chưa trả lời:
                                    </p>
                                    <div class="flex flex-wrap gap-1">
                                        @foreach($unansweredNumbers as $num)
                                            <span class="inline-block bg-red-200 text-red-800 px-2.5 py-1 rounded-md text-xs font-bold">
                                                Câu {{ $num }}
                                            </span>
                                        @endforeach
                                    </div>
                                </div>
                            </div>
                            
                            {{-- Confirmation checkbox --}}
                            <label class="flex items-start gap-2 mt-3 pt-3 border-t border-red-200 cursor-pointer">
                                <input 
                                    type="checkbox" 
                                    x-model="confirmUnanswered"
                                    class="mt-1 w-4 h-4 text-red-600 rounded focus:ring-red-500">
                                <span class="text-sm text-red-800 font-medium">
                                    Tôi xác nhận muốn nộp bài dù chưa hoàn thành tất cả câu hỏi
                                </span>
                            </label>
                        </div>
                    @endif
                    
                    <div class="bg-yellow-50 border border-yellow-200 rounded-lg p-4 mb-6">
                        <p class="text-sm text-yellow-800">
                            ⚠️ Sau khi nộp bài, bạn sẽ không thể quay lại chỉnh sửa.
                        </p>
                    </div>
                    
                    {{-- Time remaining info --}}
                    <div 
                        x-data="{
                            startedAt: new Date('{{ $attempt->started_at }}'),
                            durationMinutes: {{ $attempt->exam->duration_minutes }},
                            timeRemaining: 0,
                            
                            init() {
                                this.calculateTime();
                            },
                            
                            calculateTime() {
                                const now = new Date();
                                const elapsed = Math.floor((now - this.startedAt) / 1000);
                                const total = this.durationMinutes * 60;
                                this.timeRemaining = Math.max(0, total - elapsed);
                            },
                            
                            formatTime() {
                                const hours = Math.floor(this.timeRemaining / 3600);
                                const minutes = Math.floor((this.timeRemaining % 3600) / 60);
                                const seconds = this.timeRemaining % 60;
                                return String(hours).padStart(2, '0') + ':' + 
                                       String(minutes).padStart(2, '0') + ':' + 
                                       String(seconds).padStart(2, '0');
                            }
                        }"
                        class="bg-blue-50 border border-blue-200 rounded-lg p-3 mb-6">
                        <div class="flex items-center justify-center gap-2 text-blue-700">
                            <svg class="w-5 h-5" fill="none" stroke="currentColor" viewBox="0 0 24 24">
                                <path stroke-linecap="round" stroke-linejoin="round" stroke-width="2" d="M12 8v4l3 3m6-3a9 9 0 11-18 0 9 9 0 0118 0z"/>
                            </svg>
                            <span class="text-sm font-medium">
                                Thời gian còn lại: <span class="font-mono font-bold" x-text="formatTime()"></span>
                            </span>
                        </div>
                    </div>
                </div>
                
                {{-- Footer Buttons --}}
                <div class="flex flex-col sm:flex-row gap-3 justify-end mt-6">
                    <button
                        @click="$wire.set('showSubmitModal', false)"
                        class="px-4 py-2 text-gray-700 bg-gray-100 hover:bg-gray-200 rounded-lg transition w-full sm:w-auto">
                        Kiểm tra lại
                    </button>
                    <button
                        wire:click="submitExam"
                        wire:loading.attr="disabled"
                        @if($hasUnanswered)
                            x-bind:disabled="!confirmUnanswered"
                            x-bind:class="{ 'opacity-50 cursor-not-allowed': !confirmUnanswered }"
                        @endif
                        class="px-4 py-2 text-white bg-indigo-600 hover:bg-indigo-700 rounded-lg transition w-full sm:w-auto disabled:opacity-50 disabled:cursor-not-allowed">
                        <span wire:loading.remove wire:target="submitExam">Nộp bài ngay</span>
                        <span wire:loading wire:target="submitExam">Đang xử lý...</span>
                    </button>
                </div>
            </div>
        </div>
    </div>

</div>
