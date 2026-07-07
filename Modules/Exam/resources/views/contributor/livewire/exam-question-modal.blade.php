<div>
    <x-notifications z-index="z-50" />

    {{-- Modal: Thêm câu hỏi vào đề thi --}}
    <x-modal-card title="Thêm câu hỏi vào đề thi" wire:model="showAddQuestionModal" max-width="full" class="mx-4">
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

            {{-- Filter Câu hỏi--}}
            <div class="grid grid-cols-1 md:grid-cols-3 gap-4">
                {{-- Độ khó --}}
                <x-multi-filter
                    title="Độ khó"
                    wire:model.live="filterDifficulty"
                    :selected="$filterDifficulty"
                    :options="[
                        'easy' => 'Dễ',
                        'medium' => 'Trung bình',
                        'hard' => 'Khó',
                    ]"
                />

                {{-- Loại --}}
                <x-multi-filter
                    title="Loại"
                    wire:model.live="filterType"
                    :selected="$filterType"
                    :options="[
                        'single_choice' => 'Một đáp án',
                        'multiple_choice' => 'Nhiều đáp án',
                        'essay' => 'Tự luận',
                    ]"
                />

                {{-- Danh mục --}}
                <x-multi-filter
                    title="Danh mục"
                    wire:model.live="filterCategoryId"
                    :selected="$filterCategoryId"
                    :options="$categories"
                />
            </div>
        </div>

        {{-- Random Selection Banner - Banner chọn ngẫu nhiên câu hỏi --}}
        <div class="mb-4 border-2 border-blue-300 rounded-lg bg-gradient-to-r from-blue-50 to-indigo-50 overflow-hidden">
            {{-- Header với toggle button --}}
            <div class="flex items-center justify-between px-4 py-3 bg-blue-100 border-b border-blue-200">
                <h3 class="text-sm font-bold text-blue-900 flex items-center gap-2">
                    <svg class="w-5 h-5" fill="none" stroke="currentColor" viewBox="0 0 24 24">
                        <path stroke-linecap="round" stroke-linejoin="round" stroke-width="2" d="M7 21a4 4 0 01-4-4V5a2 2 0 012-2h4a2 2 0 012 2v12a4 4 0 01-4 4zm0 0h12a2 2 0 002-2v-4a2 2 0 00-2-2h-2.343M11 7.343l1.657-1.657a2 2 0 012.828 0l2.829 2.829a2 2 0 010 2.828l-8.486 8.485M7 17h.01"></path>
                    </svg>
                    Chọn ngẫu nhiên câu hỏi
                </h3>
                <button wire:click="toggleRandomBanner" type="button"
                    class="text-blue-700 hover:text-blue-900 transition">
                    @if($showRandomBanner)
                        <svg class="w-5 h-5" fill="none" stroke="currentColor" viewBox="0 0 24 24">
                            <path stroke-linecap="round" stroke-linejoin="round" stroke-width="2" d="M5 15l7-7 7 7"></path>
                        </svg>
                    @else
                        <svg class="w-5 h-5" fill="none" stroke="currentColor" viewBox="0 0 24 24">
                            <path stroke-linecap="round" stroke-linejoin="round" stroke-width="2" d="M19 9l-7 7-7-7"></path>
                        </svg>
                    @endif
                </button>
            </div>

            {{-- Content - Hiển thị khi showRandomBanner = true --}}
            @if($showRandomBanner)
                <div class="p-4">
                    {{-- Tabs - Chọn chế độ random --}}
                    <div class="flex gap-2 mb-4">
                        <button wire:click="$set('randomMode', 'total')" type="button"
                            class="flex-1 px-4 py-2 text-sm font-semibold rounded-lg transition
                                @if($randomMode === 'total')
                                    bg-blue-600 text-white shadow-md
                                @else
                                    bg-white text-gray-700 border border-gray-300 hover:bg-gray-50
                                @endif">
                            📊 Theo tổng số lượng
                        </button>
                        <button wire:click="$set('randomMode', 'by_difficulty')" type="button"
                            class="flex-1 px-4 py-2 text-sm font-semibold rounded-lg transition
                                @if($randomMode === 'by_difficulty')
                                    bg-blue-600 text-white shadow-md
                                @else
                                    bg-white text-gray-700 border border-gray-300 hover:bg-gray-50
                                @endif">
                            🎯 Theo độ khó
                        </button>
                    </div>

                    {{-- Chế độ 1: Random theo tổng số lượng --}}
                    @if($randomMode === 'total')
                        <div class="space-y-3">
                            <div class="bg-white rounded-lg p-3 border border-blue-200">
                                <label class="block text-sm font-medium text-gray-700 mb-2">
                                    Số lượng câu hỏi cần random
                                </label>
                                <input type="number" 
                                    wire:model="randomTotalCount" 
                                    min="0"
                                    max="{{ $this->availableCountsByDifficulty['total'] }}"
                                    placeholder="Nhập số lượng..."
                                    class="w-full px-3 py-2 border-gray-300 rounded-lg focus:border-blue-500 focus:ring-blue-500">
                                <p class="text-xs text-gray-500 mt-2">
                                    💡 Có <strong class="text-blue-600">{{ $this->availableCountsByDifficulty['total'] }}</strong> câu hỏi khả dụng
                                    (Dễ: {{ $this->availableCountsByDifficulty['easy'] }}, 
                                    Trung bình: {{ $this->availableCountsByDifficulty['medium'] }}, 
                                    Khó: {{ $this->availableCountsByDifficulty['hard'] }})
                                </p>
                            </div>
                        </div>
                    @endif

                    {{-- Chế độ 2: Random theo từng độ khó --}}
                    @if($randomMode === 'by_difficulty')
                        <div class="grid grid-cols-3 gap-3">
                            {{-- Input Câu dễ --}}
                            <div class="bg-white rounded-lg p-3 border border-green-200">
                                <label class="block text-sm font-medium text-green-700 mb-2">
                                    😊 Dễ
                                </label>
                                <input type="number" 
                                    wire:model="randomEasyCount" 
                                    min="0"
                                    max="{{ $this->availableCountsByDifficulty['easy'] }}"
                                    placeholder="0"
                                    class="w-full px-3 py-2 border-green-300 rounded-lg focus:border-green-500 focus:ring-green-500">
                                <p class="text-xs text-gray-600 mt-2">
                                    Có <strong class="text-green-600">{{ $this->availableCountsByDifficulty['easy'] }}</strong> câu
                                </p>
                            </div>

                            {{-- Input Câu trung bình --}}
                            <div class="bg-white rounded-lg p-3 border border-yellow-200">
                                <label class="block text-sm font-medium text-yellow-700 mb-2">
                                    😐 Trung bình
                                </label>
                                <input type="number" 
                                    wire:model="randomMediumCount" 
                                    min="0"
                                    max="{{ $this->availableCountsByDifficulty['medium'] }}"
                                    placeholder="0"
                                    class="w-full px-3 py-2 border-yellow-300 rounded-lg focus:border-yellow-500 focus:ring-yellow-500">
                                <p class="text-xs text-gray-600 mt-2">
                                    Có <strong class="text-yellow-600">{{ $this->availableCountsByDifficulty['medium'] }}</strong> câu
                                </p>
                            </div>

                            {{-- Input Câu khó --}}
                            <div class="bg-white rounded-lg p-3 border border-red-200">
                                <label class="block text-sm font-medium text-red-700 mb-2">
                                    😰 Khó
                                </label>
                                <input type="number" 
                                    wire:model="randomHardCount" 
                                    min="0"
                                    max="{{ $this->availableCountsByDifficulty['hard'] }}"
                                    placeholder="0"
                                    class="w-full px-3 py-2 border-red-300 rounded-lg focus:border-red-500 focus:ring-red-500">
                                <p class="text-xs text-gray-600 mt-2">
                                    Có <strong class="text-red-600">{{ $this->availableCountsByDifficulty['hard'] }}</strong> câu
                                </p>
                            </div>
                        </div>
                    @endif

                    {{-- Button Chọn ngẫu nhiên --}}
                    <button wire:click="randomSelectQuestions" type="button"
                        class="w-full mt-4 px-4 py-2.5 text-sm font-bold text-white bg-gradient-to-r from-blue-600 to-indigo-600 rounded-lg hover:from-blue-700 hover:to-indigo-700 shadow-md hover:shadow-lg transition-all duration-200 flex items-center justify-center gap-2">
                        <svg class="w-5 h-5" fill="none" stroke="currentColor" viewBox="0 0 24 24">
                            <path stroke-linecap="round" stroke-linejoin="round" stroke-width="2" d="M14 10l-2 1m0 0l-2-1m2 1v2.5M20 7l-2 1m2-1l-2-1m2 1v2.5M14 4l-2-1-2 1M4 7l2-1M4 7l2 1M4 7v2.5M12 21l-2-1m2 1l2-1m-2 1v-2.5M6 18l-2-1v-2.5M18 18l2-1v-2.5"></path>
                        </svg>
                        Chọn ngẫu nhiên
                    </button>
                </div>
            @endif
        </div>

        {{-- Two-column Grid --}}
        <div class="grid grid-cols-5 gap-6 min-h-[600px]">

            {{-- LEFT COLUMN: Available Questions --}}
            <div class="col-span-3 flex flex-col">
                <h3 class="mb-3 text-sm font-semibold text-gray-700">
                    Ngân hàng câu hỏi
                    <span class="text-gray-500">({{ $this->availableQuestions['total'] }} câu)</span>
                </h3>

                    {{-- Question Source Tabs --}}
                <div class="mb-4">
                    <div class="flex gap-2 mb-3">
                        <button
                            wire:click="$set('questionSourceTab', 'personal')"
                            class="flex-1 flex items-center justify-center gap-2 px-3 py-2 text-sm font-medium rounded-md transition
                                {{ $questionSourceTab === 'personal'
                                    ? 'bg-blue-600 text-white'
                                    : 'bg-white border border-gray-300 text-gray-700 hover:bg-gray-50' }}">
                            <svg class="w-4 h-4 flex-shrink-0" fill="none" stroke="currentColor" viewBox="0 0 24 24">
                                <path stroke-linecap="round" stroke-linejoin="round" stroke-width="2" d="M16 7a4 4 0 11-8 0 4 4 0 018 0zM12 14a7 7 0 00-7 7h14a7 7 0 00-7-7z"></path>
                            </svg>
                            <span>Cá nhân</span>
                        </button>

                        <button
                            wire:click="$set('questionSourceTab', 'shared')"
                            class="flex-1 flex items-center justify-center gap-2 px-3 py-2 text-sm font-medium rounded-md transition
                                {{ $questionSourceTab === 'shared'
                                    ? 'bg-blue-600 text-white'
                                    : 'bg-white border border-gray-300 text-gray-700 hover:bg-gray-50' }}">
                            <svg class="w-4 h-4 flex-shrink-0" fill="none" stroke="currentColor" viewBox="0 0 24 24">
                                <path stroke-linecap="round" stroke-linejoin="round" stroke-width="2" d="M17 20h5v-2a3 3 0 00-5.356-1.857M17 20H7m10 0v-2c0-.656-.126-1.283-.356-1.857M7 20H2v-2a3 3 0 015.356-1.857M7 20v-2c0-.656.126-1.283.356-1.857m0 0a5.002 5.002 0 019.288 0M15 7a3 3 0 11-6 0 3 3 0 016 0zm6 3a2 2 0 11-4 0 2 2 0 014 0zM7 10a2 2 0 11-4 0 2 2 0 014 0z"></path>
                            </svg>
                            <span>Dùng chung</span>
                        </button>
                    </div>
                </div>

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
                                                {{ Str::limit(strip_tags($question->content), 150) }}
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
                                                    {{ Str::limit(strip_tags($question->content), 100) }}
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
                                                    {{ Str::limit(strip_tags($question->content), 100) }}
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
                    <p class="text-sm text-gray-900 mt-1">{{ Str::limit(strip_tags($questionToRemove['content']), 150) }}</p>
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