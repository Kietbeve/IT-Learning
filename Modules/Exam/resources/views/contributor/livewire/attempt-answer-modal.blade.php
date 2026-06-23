<div>
    <x-notifications z-index="z-50" />
    
    {{-- Modal: Chi tiết câu trả lời --}}
    <x-modal-card 
        title="Chi tiết câu trả lời" 
        blur 
        wire:model="showModal" 
        max-width="3xl">
        
        @if ($answer && $answer->question)
            @php
                $question = $answer->question;
                $statusBadge = $this->getStatusBadge();
                $borderColor = $this->getBorderColorClass();
                $sortOrder = $this->getQuestionSortOrder();
                $score = $this->getQuestionScore();
                $maxScore = $this->getQuestionMaxScore();
            @endphp

            <div class="space-y-4">
                {{-- Question Header with Status and Score --}}
                <div class="flex items-start justify-between border-l-4 {{ $borderColor }} pl-4 py-2">
                    <div class="flex items-center gap-3 flex-wrap">
                        <x-badge flat gray label="Câu {{ $sortOrder }}" class="shrink-0" />
                        
                        <x-badge flat :color="$statusBadge['color']">
                            <x-icon name="{{ $statusBadge['icon'] }}" class="w-4 h-4 mr-1 inline" />
                            {{ $statusBadge['label'] }}
                        </x-badge>

                        {{-- Score Display --}}
                        <div class="flex items-center gap-2">
                            <span class="text-sm font-medium text-gray-600">Điểm:</span>
                            <span class="text-base font-bold {{ $answer->is_correct ? 'text-green-600' : 'text-red-600' }}">
                                {{ number_format($score, 1) }}
                            </span>
                            <span class="text-sm text-gray-500">/ {{ number_format($maxScore, 1) }}</span>
                        </div>
                    </div>
                </div>

                {{-- Question Content --}}
                <div class="rounded-lg border border-slate-200 bg-slate-50 p-4">
                    <h3 class="text-base font-semibold text-gray-900">
                        {!! $question->content !!}
                    </h3>
                </div>

                {{-- Question Meta Info --}}
                <div class="grid grid-cols-2 gap-3 text-sm">
                    <div class="rounded-lg bg-gray-50 p-3">
                        <span class="text-gray-600">Loại câu hỏi:</span>
                        <span class="font-medium text-gray-900">
                            @switch($question->type)
                                @case('single_choice')
                                    Trắc nghiệm một đáp án
                                    @break
                                @case('multiple_choice')
                                    Trắc nghiệm nhiều đáp án
                                    @break
                                @case('essay')
                                    Tự luận
                                    @break
                            @endswitch
                        </span>
                    </div>
                    
                    <div class="rounded-lg bg-gray-50 p-3">
                        <span class="text-gray-600">Độ khó:</span>
                        <span class="font-medium text-gray-900">
                            @switch($question->difficulty)
                                @case('easy')
                                    Dễ
                                    @break
                                @case('medium')
                                    Trung bình
                                    @break
                                @case('hard')
                                    Khó
                                    @break
                            @endswitch
                        </span>
                    </div>
                </div>

                {{-- Options Display for Choice Questions --}}
                @if(in_array($question->type, ['single_choice', 'multiple_choice']))
                    <div class="space-y-3">
                        <h4 class="text-sm font-semibold text-gray-700">Các đáp án:</h4>
                        
                        @foreach($question->options as $option)
                            @php
                                $classes = $this->getOptionClasses($option);
                            @endphp

                            <div class="flex items-start gap-3 p-3 rounded-lg {{ $classes['container'] }}">
                                <div class="shrink-0 mt-1">
                                    <div class="w-5 h-5 rounded-full {{ $classes['icon_container'] }} flex items-center justify-center">
                                        @if($classes['icon_type'])
                                            <x-icon name="{{ $classes['icon_type'] }}" class="w-3 h-3 text-white" />
                                        @endif
                                    </div>
                                </div>
                                
                                <div class="flex-1">
                                    <div class="text-sm {{ $classes['text'] }}">
                                        <span class="font-semibold">{{ $option->option_key }}.</span>
                                        {!! $option->content !!}
                                        
                                        @if($classes['show_user_badge'] && $classes['badge_color'] === 'positive')
                                            <x-badge flat positive label="Đáp án của bạn" class="ml-2" />
                                        @elseif($classes['show_user_badge'] && $classes['badge_color'] === 'negative')
                                            <x-badge flat negative label="Đáp án của bạn" class="ml-2" />
                                        @endif
                                        
                                        @if($classes['show_correct_badge'])
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
                    {{-- User's Answer --}}
                    <div class="rounded-lg border-l-4 border-blue-500 bg-blue-50 p-4">
                        <h4 class="font-semibold text-blue-900 text-sm mb-2 flex items-center gap-2">
                            <x-icon name="pencil" class="w-4 h-4" />
                            Câu trả lời của học viên:
                        </h4>
                        <div class="text-sm text-blue-800 whitespace-pre-wrap">
                            {{ $answer->answer_text ?: 'Chưa trả lời' }}
                        </div>
                    </div>

                    {{-- Sample Answer --}}
                    @if($question->answer_text)
                        <div class="rounded-lg border-l-4 border-green-500 bg-green-50 p-4">
                            <h4 class="font-semibold text-green-900 text-sm mb-2 flex items-center gap-2">
                                <x-icon name="check-badge" class="w-4 h-4" />
                                Đáp án mẫu:
                            </h4>
                            <div class="text-sm text-green-800 whitespace-pre-wrap">
                                {!! $question->answer_text !!}
                            </div>
                        </div>
                    @endif
                @endif

                {{-- Skipped Question Message --}}
                @if($answer->answered_at === null)
                    <div class="rounded-lg border-l-4 border-gray-400 bg-gray-50 p-4">
                        <p class="text-sm text-gray-600 italic flex items-center gap-2">
                            <x-icon name="information-circle" class="w-5 h-5" />
                            Học viên đã bỏ qua câu hỏi này.
                        </p>
                    </div>
                @endif

                {{-- Question Explanation --}}
                @if($question->explanation)
                    <div class="rounded-lg border-l-4 border-blue-500 bg-blue-50 p-4">
                        <div class="flex gap-3">
                            <x-icon name="information-circle" class="w-5 h-5 text-blue-600 shrink-0 mt-0.5" />
                            <div>
                                <h4 class="font-semibold text-blue-900 text-sm mb-1">Giải thích đáp án:</h4>
                                <p class="text-sm text-blue-800">{!! $question->explanation !!}</p>
                            </div>
                        </div>
                    </div>
                @endif

                {{-- Additional Info --}}
                @if($answer->answered_at)
                    <div class="rounded-lg bg-gray-50 p-3 text-sm text-gray-600">
                        <div class="flex items-center gap-2">
                            <x-icon name="clock" class="w-4 h-4" />
                            <span>Thời gian trả lời:</span>
                            <span class="font-medium text-gray-900">
                                {{ $answer->answered_at->format('d/m/Y H:i:s') }}
                            </span>
                        </div>
                    </div>
                @endif
            </div>

        @else
            <div class="py-8 text-center">
                <x-icon name="document-text" class="w-12 h-12 mx-auto text-gray-400 mb-3" />
                <p class="text-gray-600">Không tìm thấy dữ liệu câu trả lời.</p>
            </div>
        @endif

        <x-slot name="footer">
            <div class="flex justify-end">
                <x-button flat label="Đóng" wire:click="closeModal" />
            </div>
        </x-slot>
    </x-modal-card>
</div>
