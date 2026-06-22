<div>
    <x-notifications z-index="z-50" />

    {{-- Modal: Thêm câu hỏi vào đề thi --}}
    <x-modal-card title="Thêm câu hỏi vào đề thi" wire:model="showAddQuestionModal" max-width="7xl">
        {{-- Search & Filters --}}
        <div class="mb-4 space-y-3">
            <div class="flex gap-3">
                {{-- Search Input --}}
                <div class="flex-1">
                    <input type="text" wire:model.live.debounce.300ms="searchTerm" placeholder="Tìm kiếm câu hỏi..."
                        class="w-full rounded-lg border-gray-300 shadow-sm focus:border-blue-500 focus:ring-blue-500" />
                </div>

                {{-- Reset Button --}}
                <button wire:click="resetFilters"
                    class="px-4 py-2 text-sm font-medium text-gray-700 bg-white border border-gray-300 rounded-lg hover:bg-gray-50">
                    🔄 Reset
                </button>
            </div>

            <div class="grid grid-cols-3 gap-3">
                {{-- Filter: Độ khó --}}
                <div>
                    <select wire:model.live="filterDifficulty"
                        class="w-full rounded-lg border-gray-300 shadow-sm focus:border-blue-500 focus:ring-blue-500">
                        <option value="">Tất cả độ khó</option>
                        <option value="easy">Dễ</option>
                        <option value="medium">Trung bình</option>
                        <option value="hard">Khó</option>
                    </select>
                </div>

                {{-- Filter: Loại câu hỏi --}}
                <div>
                    <select wire:model.live="filterType"
                        class="w-full rounded-lg border-gray-300 shadow-sm focus:border-blue-500 focus:ring-blue-500">
                        <option value="">Tất cả loại</option>
                        <option value="single_choice">Trắc nghiệm một đáp án</option>
                        <option value="multiple_choice">Trắc nghiệm nhiều đáp án</option>
                        <option value="essay">Tự luận</option>
                    </select>
                </div>

                {{-- Filter: Danh mục --}}
                <div>
                    <select wire:model.live="filterCategoryId"
                        class="w-full rounded-lg border-gray-300 shadow-sm focus:border-blue-500 focus:ring-blue-500">
                        <option value="">Tất cả danh mục</option>
                        @foreach($categories as $category)
                            <option value="{{ $category['id'] }}">{{ $category['name'] }}</option>
                        @endforeach
                    </select>
                </div>
            </div>
        </div>

        {{-- Two-column Grid --}}
        <div class="grid grid-cols-5 gap-6 min-h-[600px]">

            {{-- LEFT COLUMN: Available Questions --}}
            <div class="col-span-3 flex flex-col">
                <h3 class="mb-3 text-sm font-semibold text-gray-700">
                    Danh sách câu hỏi
                    <span class="text-gray-500">({{ $this->availableQuestions['total'] }} câu)</span>
                </h3>

                {{-- Questions List --}}
                <div class="flex-1 overflow-y-auto border border-gray-200 rounded-lg">
                    @if($this->availableQuestions['total'] > 0)
                        <div class="divide-y divide-gray-200">
                            @foreach($this->availableQuestions['data'] as $question)
                                <div class="p-3 hover:bg-gray-50 transition">
                                    <div class="flex items-start gap-3">
                                        {{-- Question Content --}}
                                        <div class="flex-1 min-w-0">
                                            <p class="text-sm text-gray-900 line-clamp-2">
                                                {{ $question->content }}
                                            </p>

                                            {{-- Meta info --}}
                                            <div class="mt-2 flex items-center gap-2 text-xs">
                                                {{-- Type Badge --}}
                                                <span class="inline-flex items-center px-2 py-0.5 rounded 
                                                            @if($question->type === 'single_choice') bg-blue-100 text-blue-700
                                                            @elseif($question->type === 'multiple_choice') bg-purple-100 text-purple-700
                                                            @else bg-gray-100 text-gray-700
                                                            @endif">
                                                    @if($question->type === 'single_choice') Một đáp án
                                                    @elseif($question->type === 'multiple_choice') Nhiều đáp án
                                                    @else Tự luận
                                                    @endif
                                                </span>

                                                {{-- Difficulty Badge --}}
                                                <span class="inline-flex items-center px-2 py-0.5 rounded
                                                            @if($question->difficulty === 'easy') bg-green-100 text-green-700
                                                            @elseif($question->difficulty === 'medium') bg-yellow-100 text-yellow-700
                                                            @else bg-red-100 text-red-700
                                                            @endif">
                                                    @if($question->difficulty === 'easy') Dễ
                                                    @elseif($question->difficulty === 'medium') Trung bình
                                                    @else Khó
                                                    @endif
                                                </span>

                                                {{-- Category --}}
                                                @if($question->category)
                                                    <span class="text-gray-500">
                                                        📁 {{ $question->category->name }}
                                                    </span>
                                                @endif
                                            </div>
                                        </div>

                                        {{-- Add Button --}}
                                        <button wire:click="addQuestion({{ $question->id }})"
                                            class="px-3 py-1.5 text-xs font-semibold text-white bg-blue-600 rounded-lg hover:bg-blue-700 transition whitespace-nowrap">
                                            + Thêm
                                        </button>
                                    </div>
                                </div>
                            @endforeach
                        </div>
                    @else
                        {{-- Empty State --}}
                        <div class="flex items-center justify-center h-full p-8 text-center">
                            <div>
                                <div class="text-4xl mb-2">📝</div>
                                <p class="text-sm text-gray-500">Không còn câu hỏi nào để thêm.</p>
                                <p class="text-xs text-gray-400 mt-1">Tất cả câu hỏi đã được thêm vào đề thi hoặc chưa có
                                    câu hỏi phù hợp.</p>
                            </div>
                        </div>
                    @endif
                </div>

                {{-- Pagination --}}
                @if($this->availableQuestions['totalPages'] > 1)
                    <div class="mt-3 flex items-center justify-between">
                        <div class="text-sm text-gray-500">
                            Trang {{ $currentPage }} / {{ $this->availableQuestions['totalPages'] }}
                        </div>

                        <div class="flex gap-2">
                            <button wire:click="previousPage" @if($currentPage <= 1) disabled @endif
                                class="px-3 py-1 text-sm border rounded hover:bg-gray-50 disabled:opacity-50 disabled:cursor-not-allowed">
                                ← Trước
                            </button>

                            <button wire:click="nextPage" @if($currentPage >= $this->availableQuestions['totalPages'])
                            disabled @endif
                                class="px-3 py-1 text-sm border rounded hover:bg-gray-50 disabled:opacity-50 disabled:cursor-not-allowed">
                                Sau →
                            </button>
                        </div>
                    </div>
                @endif
            </div>

            {{-- RIGHT COLUMN: All Questions (Existing + New) --}}
            <div class="col-span-2 flex flex-col border-l border-gray-200 pl-6">
                <h3 class="mb-3 text-sm font-semibold text-gray-700">
                    Câu hỏi trong đề
                    <span class="text-gray-600">({{ count($existingQuestionIds) + count($selectedQuestionIds) }})</span>
                </h3>

                <div class="flex-1 overflow-y-auto space-y-4">
                    {{-- Section 1: Existing Questions (Already in Exam) --}}
                    @if($this->existingQuestions->count() > 0)
                        <div>
                            <p class="text-xs font-semibold text-gray-500 mb-2 uppercase">
                                Đã có trong đề ({{ count($existingQuestionIds) }})
                            </p>
                            <div class="space-y-2">
                                @foreach($this->existingQuestions as $question)
                                    <div class="p-2.5 border border-gray-300 rounded-lg bg-gray-50">
                                        <div class="flex items-start gap-2">
                                            <div class="flex-1 min-w-0">
                                                <p class="text-xs text-gray-900 line-clamp-2">
                                                    {{ $question->content }}
                                                </p>
                                                <div class="mt-1 flex flex-wrap gap-1 text-xs">
                                                    <span
                                                        class="inline-flex items-center px-1.5 py-0.5 rounded bg-gray-200 text-gray-700">
                                                        @if($question->type === 'single_choice') 1 đáp án
                                                        @elseif($question->type === 'multiple_choice') Nhiều đáp án
                                                        @else Tự luận
                                                        @endif
                                                    </span>
                                                    <span class="inline-flex items-center px-1.5 py-0.5 rounded
                                                                @if($question->difficulty === 'easy') bg-green-100 text-green-700
                                                                @elseif($question->difficulty === 'medium') bg-yellow-100 text-yellow-700
                                                                @else bg-red-100 text-red-700
                                                                @endif">
                                                        @if($question->difficulty === 'easy') Dễ
                                                        @elseif($question->difficulty === 'medium') TB
                                                        @else Khó
                                                        @endif
                                                    </span>
                                                </div>
                                            </div>
                                            <div class="flex items-center gap-2">
                                                <div class="flex flex-col items-end">
                                                    <label class="text-xs text-gray-500 mb-1">Điểm</label>
                                                    <input type="number" 
                                                        wire:model.blur="existingQuestionScores.{{ $question->id }}"
                                                        step="0.5"
                                                        min="0.5"
                                                        max="50"
                                                        class="w-16 px-2 py-1 text-xs border-gray-300 rounded focus:border-blue-500 focus:ring-blue-500"
                                                    />
                                                </div>
                                                <button wire:click="removeExistingQuestion({{ $question->id }})"
                                                    class="text-red-500 hover:text-red-700 text-lg font-bold mt-5" 
                                                    title="Xóa câu hỏi">
                                                    ×
                                                </button>
                                            </div>
                                        </div>
                                    </div>
                                @endforeach
                            </div>
                        </div>
                    @endif

                    {{-- Section 2: Newly Selected Questions --}}
                    @if(count($selectedQuestionIds) > 0)
                        <div>
                            <p class="text-xs font-semibold text-blue-600 mb-2 uppercase">
                                Mới thêm ({{ count($selectedQuestionIds) }})
                            </p>
                            <div class="space-y-2">
                                @foreach($this->selectedQuestions as $question)
                                    <div class="p-2.5 border border-blue-300 rounded-lg bg-blue-50">
                                        <div class="flex items-start justify-between gap-2">
                                            <div class="flex-1 min-w-0">
                                                <p class="text-xs text-gray-900 line-clamp-2">
                                                    {{ $question->content }}
                                                </p>
                                                <div class="mt-1 flex flex-wrap gap-1 text-xs">
                                                    <span class="inline-flex items-center px-1.5 py-0.5 rounded
                                                                @if($question->type === 'single_choice') bg-blue-200 text-blue-800
                                                                @elseif($question->type === 'multiple_choice') bg-purple-200 text-purple-800
                                                                @else bg-gray-200 text-gray-800
                                                                @endif">
                                                        @if($question->type === 'single_choice') 1 đáp án
                                                        @elseif($question->type === 'multiple_choice') Nhiều đáp án
                                                        @else Tự luận
                                                        @endif
                                                    </span>
                                                    <span class="inline-flex items-center px-1.5 py-0.5 rounded
                                                                @if($question->difficulty === 'easy') bg-green-200 text-green-800
                                                                @elseif($question->difficulty === 'medium') bg-yellow-200 text-yellow-800
                                                                @else bg-red-200 text-red-800
                                                                @endif">
                                                        @if($question->difficulty === 'easy') Dễ
                                                        @elseif($question->difficulty === 'medium') TB
                                                        @else Khó
                                                        @endif
                                                    </span>
                                                </div>
                                            </div>
                                            <div class="flex items-center gap-2">
                                                <div class="flex flex-col items-end">
                                                    <label class="text-xs text-blue-600 mb-1">Điểm</label>
                                                    <input type="number" 
                                                        wire:model.blur="questionScores.{{ $question->id }}"
                                                        step="0.5"
                                                        min="0.5"
                                                        max="50"
                                                        class="w-16 px-2 py-1 text-xs border-blue-300 rounded focus:border-blue-500 focus:ring-blue-500"
                                                    />
                                                </div>
                                                <button wire:click="removeNewQuestion({{ $question->id }})"
                                                    class="text-red-500 hover:text-red-700 text-lg font-bold mt-5" title="Bỏ chọn">
                                                    ×
                                                </button>
                                            </div>
                                        </div>
                                    </div>
                                @endforeach
                            </div>
                        </div>
                    @endif

                    {{-- Empty State --}}
                    @if($this->existingQuestions->count() === 0 && count($selectedQuestionIds) === 0)
                        <div class="flex items-center justify-center h-full text-center">
                            <div>
                                <div class="text-4xl mb-2">📝</div>
                                <p class="text-sm text-gray-500">Chưa có câu hỏi nào</p>
                                <p class="text-xs text-gray-400 mt-1">Click "Thêm" bên trái để chọn câu hỏi</p>
                            </div>
                        </div>
                    @endif
                </div>
            </div>
        </div>

        <x-slot name="footer">
            <div class="flex justify-between items-center w-full">
                <div class="text-sm text-gray-600">
                    <div class="space-y-1">
                        {{-- Total questions --}}
                        <div>
                            <span class="text-gray-700">Tổng câu hỏi: </span>
                            <strong class="text-gray-900">{{ count($existingQuestionIds) + count($selectedQuestionIds) }}</strong>
                            <span class="text-gray-500">
                                (Đã có: {{ count($existingQuestionIds) }}
                                @if(count($selectedQuestionIds) > 0)
                                    + Mới: <span class="text-blue-600">{{ count($selectedQuestionIds) }}</span>
                                @endif
                                )
                            </span>
                        </div>
                        
                        {{-- Total score --}}
                        <div>
                            <span class="text-gray-700">Tổng điểm: </span>
                            <strong class="text-green-600 text-base">{{ $this->totalScore }}</strong>
                            <span class="text-gray-500">điểm</span>
                        </div>
                    </div>
                </div>
                <div class="flex gap-2">
                    <x-button flat label="Hủy" wire:click="closeModal" />
                    <x-button primary 
                        label="Lưu{{ count($selectedQuestionIds) > 0 ? ' (' . count($selectedQuestionIds) . ' câu mới)' : '' }}"
                        wire:click="saveQuestionsToExam" />
                </div>
            </div>
        </x-slot>
    </x-modal-card>

    {{-- Modal: Remove Single Question --}}
    <x-modal-card title="Xác nhận gỡ câu hỏi" wire:model="showRemoveModal" max-width="xl">
        @if($questionToRemove)
            <div class="space-y-3">
                <p class="text-sm text-gray-700">Bạn có chắc muốn gỡ câu hỏi này khỏi đề thi?</p>

                <div class="p-3 bg-gray-50 rounded border">
                    <p class="text-xs text-gray-500">Thứ tự: {{ $questionToRemove['sort_order'] }}</p>
                    <p class="text-sm text-gray-900 mt-1">{{ Str::limit($questionToRemove['content'], 150) }}</p>
                </div>
            </div>
        @endif

        <x-slot name="footer">
            <div class="flex gap-2 justify-end">
                <x-button flat label="Hủy" wire:click="$set('showRemoveModal', false)" />
                <x-button negative label="Gỡ khỏi đề" wire:click="removeQuestion" />
            </div>
        </x-slot>
    </x-modal-card>

    {{-- Modal: Bulk Remove --}}
    <x-modal-card title="Xác nhận gỡ nhiều câu hỏi" wire:model="showBulkRemoveModal" max-width="lg">
        <p class="text-sm text-gray-700">
            Bạn có chắc muốn gỡ
            <strong class="text-red-600">{{ count($questionIdsToRemove) }} câu hỏi</strong>
            khỏi đề thi?
        </p>

        <x-slot name="footer">
            <div class="flex gap-2 justify-end">
                <x-button flat label="Hủy" wire:click="$set('showBulkRemoveModal', false)" />
                <x-button negative label="Gỡ tất cả" wire:click="bulkRemoveQuestions" />
            </div>
        </x-slot>
    </x-modal-card>
</div>