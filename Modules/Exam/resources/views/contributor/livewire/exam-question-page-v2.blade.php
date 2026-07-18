<div class="min-h-screen bg-gray-50">
    <x-notifications z-index="z-50" />

    {{-- Page Container --}}
    <div class="max-w-[1800px] mx-auto px-6 py-8">
        
        {{-- Breadcrumb Navigation --}}
        <nav class="flex mb-6" aria-label="Breadcrumb">
            <ol class="inline-flex items-center space-x-1 md:space-x-3">
                <li class="inline-flex items-center">
                    <a href="{{ route('contributor.exams') }}" class="inline-flex items-center text-sm font-medium text-gray-700 hover:text-blue-600">
                        <svg class="w-4 h-4 mr-2" fill="currentColor" viewBox="0 0 20 20">
                            <path d="M10.707 2.293a1 1 0 00-1.414 0l-7 7a1 1 0 001.414 1.414L4 10.414V17a1 1 0 001 1h2a1 1 0 001-1v-2a1 1 0 011-1h2a1 1 0 011 1v2a1 1 0 001 1h2a1 1 0 001-1v-6.586l.293.293a1 1 0 001.414-1.414l-7-7z"></path>
                        </svg>
                        Quản lý đề thi
                    </a>
                </li>
                <li>
                    <div class="flex items-center">
                        <svg class="w-6 h-6 text-gray-400" fill="currentColor" viewBox="0 0 20 20">
                            <path fill-rule="evenodd" d="M7.293 14.707a1 1 0 010-1.414L10.586 10 7.293 6.707a1 1 0 011.414-1.414l4 4a1 1 0 010 1.414l-4 4a1 1 0 01-1.414 0z" clip-rule="evenodd"></path>
                        </svg>
                        <a href="{{ route('contributor.exams.detail', ['examId' => $examId]) }}" class="ml-1 text-sm font-medium text-gray-700 hover:text-blue-600">
                            {{ Str::limit($exam->title ?? 'Chi tiết đề thi', 40) }}
                        </a>
                    </div>
                </li>
                <li aria-current="page">
                    <div class="flex items-center">
                        <svg class="w-6 h-6 text-gray-400" fill="currentColor" viewBox="0 0 20 20">
                            <path fill-rule="evenodd" d="M7.293 14.707a1 1 0 010-1.414L10.586 10 7.293 6.707a1 1 0 011.414-1.414l4 4a1 1 0 010 1.414l-4 4a1 1 0 01-1.414 0z" clip-rule="evenodd"></path>
                        </svg>
                        <span class="ml-1 text-sm font-medium text-blue-600">Thêm câu hỏi (V2)</span>
                    </div>
                </li>
            </ol>
        </nav>

        {{-- Page Header --}}
        <div class="mb-6">
            <h1 class="text-3xl font-bold text-gray-900 mb-2">Quản lý câu hỏi cho đề thi</h1>
            <p class="text-gray-600">Thêm và quản lý câu hỏi cho đề thi: <span class="font-semibold text-blue-600">{{ $exam->title ?? '' }}</span></p>
        </div>

        {{-- Main Grid Layout: 55% Left | 45% Right --}}
        <div class="grid grid-cols-12 gap-6 mb-6">
            
            {{-- LEFT COLUMN (55%) - Question Bank --}}
            <div class="col-span-7">
                <div class="bg-white rounded-xl shadow-sm border border-gray-200 flex flex-col">
                    
                    {{-- Header --}}
                    <div class="bg-white rounded-t-xl border-b border-gray-200">
                        <div class="px-6 py-4">
                            <h2 class="text-xl font-bold text-gray-900 mb-4">Ngân hàng câu hỏi</h2>
                            
                            {{-- Search & Reset --}}
                            <div class="flex gap-3 mb-4">
                                <div class="flex-1">
                                    <input type="text" 
                                        wire:model.live.debounce.300ms="searchTerm" 
                                        placeholder="🔍 Tìm kiếm câu hỏi..."
                                        class="w-full px-4 py-2.5 rounded-lg border-gray-300 focus:border-blue-500 focus:ring-blue-500 shadow-sm" />
                                </div>
                                <button wire:click="resetFilters"
                                    class="px-5 py-2.5 text-sm font-medium text-gray-700 bg-white border border-gray-300 rounded-lg hover:bg-gray-50 transition-colors shadow-sm">
                                    🔄 Reset
                                </button>
                            </div>

                            {{-- Filters Grid --}}
                            <div class="grid grid-cols-3 gap-3 mb-4">
                                {{-- Difficulty Filter --}}
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

                                {{-- Type Filter --}}
                                @if($this->exam->type!='essay')
                                <x-multi-filter
                                    title="Loại"
                                    wire:model.live="filterType"
                                    :selected="$filterType"
                                    :options="$this->exam->type=='multiple_choice'?['single_choice' => 'Một đáp án','multiple_choice' => 'Nhiều đáp án']
                                                            :['single_choice' => 'Một đáp án','multiple_choice' => 'Nhiều đáp án','essay' => 'Tự luận']"
                                />
                                @endif

                                {{-- Category Filter --}}
                                <x-multi-filter
                                    title="Danh mục"
                                    wire:model.live="filterCategoryId"
                                    :selected="$filterCategoryId"
                                    :options="$categories"
                                />
                            </div>

                            {{-- Random Selection Banner --}}
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

                                {{-- Random Banner Content --}}
                                @if($showRandomBanner)
                                    <div class="p-4">
                                        {{-- Mode Tabs --}}
                                        <div class="flex gap-2 mb-4">
                                            <button wire:click="$set('randomMode', 'total')" type="button"
                                                class="flex-1 px-4 py-2 text-sm font-semibold rounded-lg transition
                                                    @if($randomMode === 'total') bg-blue-600 text-white shadow-md
                                                    @else bg-white text-gray-700 border border-gray-300 hover:bg-gray-50 @endif">
                                                📊 Theo tổng số lượng
                                            </button>
                                            <button wire:click="$set('randomMode', 'by_difficulty')" type="button"
                                                class="flex-1 px-4 py-2 text-sm font-semibold rounded-lg transition
                                                    @if($randomMode === 'by_difficulty') bg-blue-600 text-white shadow-md
                                                    @else bg-white text-gray-700 border border-gray-300 hover:bg-gray-50 @endif">
                                                🎯 Theo độ khó
                                            </button>
                                        </div>

                                        {{-- Mode 1: Total Count --}}
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
                                                        TB: {{ $this->availableCountsByDifficulty['medium'] }}, 
                                                        Khó: {{ $this->availableCountsByDifficulty['hard'] }})
                                                    </p>
                                                </div>
                                            </div>
                                        @endif

                                        {{-- Mode 2: By Difficulty --}}
                                        @if($randomMode === 'by_difficulty')
                                            <div class="grid grid-cols-3 gap-3">
                                                {{-- Easy --}}
                                                @if($this->availableCountsByDifficulty['easy'] > 0)
                                                <div class="bg-white rounded-lg p-3 border border-green-200">
                                                    <label class="block text-sm font-medium text-green-700 mb-2">😊 Dễ</label>
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
                                                @endif
                                                

                                                {{-- Medium --}}
                                                @if($this->availableCountsByDifficulty['medium'] > 0)
                                                <div class="bg-white rounded-lg p-3 border border-yellow-200">
                                                    <label class="block text-sm font-medium text-yellow-700 mb-2">😐 Trung bình</label>
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
                                                @endif

                                                {{-- Hard --}}
                                                @if($this->availableCountsByDifficulty['hard'] > 0)
                                                <div class="bg-white rounded-lg p-3 border border-red-200">
                                                    <label class="block text-sm font-medium text-red-700 mb-2">😰 Khó</label>
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
                                                @endif
                                            </div>
                                        @endif

                                        {{-- Random Button --}}
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

                            {{-- Question Source Tabs --}}
                            <div class="flex gap-2">
                                <button wire:click="$set('questionSourceTab', 'personal')"
                                    class="flex-1 flex items-center justify-center gap-2 px-3 py-2.5 text-sm font-medium rounded-lg transition
                                        {{ $questionSourceTab === 'personal'
                                            ? 'bg-blue-600 text-white shadow-md'
                                            : 'bg-white border border-gray-300 text-gray-700 hover:bg-gray-50' }}">
                                    <svg class="w-4 h-4" fill="none" stroke="currentColor" viewBox="0 0 24 24">
                                        <path stroke-linecap="round" stroke-linejoin="round" stroke-width="2" d="M16 7a4 4 0 11-8 0 4 4 0 018 0zM12 14a7 7 0 00-7 7h14a7 7 0 00-7-7z"></path>
                                    </svg>
                                    <span>Cá nhân</span>
                                </button>

                                <button wire:click="$set('questionSourceTab', 'shared')"
                                    class="flex-1 flex items-center justify-center gap-2 px-3 py-2.5 text-sm font-medium rounded-lg transition
                                        {{ $questionSourceTab === 'shared'
                                            ? 'bg-blue-600 text-white shadow-md'
                                            : 'bg-white border border-gray-300 text-gray-700 hover:bg-gray-50' }}">
                                    <svg class="w-4 h-4" fill="none" stroke="currentColor" viewBox="0 0 24 24">
                                        <path stroke-linecap="round" stroke-linejoin="round" stroke-width="2" d="M17 20h5v-2a3 3 0 00-5.356-1.857M17 20H7m10 0v-2c0-.656-.126-1.283-.356-1.857M7 20H2v-2a3 3 0 015.356-1.857M7 20v-2c0-.656.126-1.283.356-1.857m0 0a5.002 5.002 0 019.288 0M15 7a3 3 0 11-6 0 3 3 0 016 0zm6 3a2 2 0 11-4 0 2 2 0 014 0zM7 10a2 2 0 11-4 0 2 2 0 014 0z"></path>
                                    </svg>
                                    <span>Dùng chung</span>
                                </button>
                            </div>

                            {{-- Question Count --}}
                            <div class="mt-3 text-sm text-gray-600">
                                <span class="font-semibold text-blue-600">{{ $this->availableQuestions['total'] }}</span> câu hỏi khả dụng
                            </div>
                        </div>
                    </div>

                    {{-- Question List (Full Page Scroll) --}}
                    <div class="px-6 py-4">
                        @if($this->availableQuestions['total'] > 0)
                            <div class="space-y-3">
                                @foreach($this->availableQuestions['data'] as $question)
                                    <div class="p-4 bg-gray-50 rounded-lg border border-gray-200 hover:border-blue-300 hover:shadow-sm transition">
                                        <div class="flex items-start gap-3">
                                            <div class="flex-1 min-w-0">
                                                <p class="text-sm text-gray-900 mb-2 line-clamp-2">
                                                    {{ Str::limit(strip_tags($question->content), 150) }}
                                                </p>
                                                <div class="flex flex-wrap items-center gap-2 text-xs">
                                                    <span class="inline-flex items-center px-2 py-1 rounded font-medium
                                                        @if($question->type === 'single_choice') bg-blue-100 text-blue-700
                                                        @elseif($question->type === 'multiple_choice') bg-purple-100 text-purple-700
                                                        @else bg-gray-100 text-gray-700 @endif">
                                                        @if($question->type === 'single_choice') Một đáp án
                                                        @elseif($question->type === 'multiple_choice') Nhiều đáp án
                                                        @else Tự luận @endif
                                                    </span>
                                                    <span class="inline-flex items-center px-2 py-1 rounded font-medium
                                                        @if($question->difficulty === 'easy') bg-green-100 text-green-700
                                                        @elseif($question->difficulty === 'medium') bg-yellow-100 text-yellow-700
                                                        @else bg-red-100 text-red-700 @endif">
                                                        @if($question->difficulty === 'easy') Dễ
                                                        @elseif($question->difficulty === 'medium') Trung bình
                                                        @else Khó @endif
                                                    </span>
                                                    @if($question->category)
                                                        <span class="text-gray-600">📁 {{ $question->category->name }}</span>
                                                    @endif
                                                </div>
                                            </div>
                                            <button wire:click="addQuestion({{ $question->id }})"
                                                class="px-4 py-2 text-sm font-semibold text-white bg-blue-600 rounded-lg hover:bg-blue-700 transition shadow-sm hover:shadow">
                                                + Thêm
                                            </button>
                                        </div>
                                    </div>
                                @endforeach
                            </div>
                        @else
                            <div class="flex items-center justify-center h-full text-center">
                                <div>
                                    <div class="text-5xl mb-3">📝</div>
                                    <p class="text-base text-gray-600 font-medium">Không còn câu hỏi nào</p>
                                    <p class="text-sm text-gray-400 mt-1">Tất cả câu hỏi đã được thêm hoặc chưa có câu hỏi phù hợp</p>
                                </div>
                            </div>
                        @endif
                    </div>

                    {{-- Pagination Footer --}}
                    @if($this->availableQuestions['totalPages'] > 1)
                        <div class="px-6 py-4 border-t border-gray-200 bg-gray-50 rounded-b-xl">
                            <div class="flex items-center justify-between">
                                <div class="text-sm text-gray-600">
                                    Trang <span class="font-semibold">{{ $currentPage }}</span> / <span class="font-semibold">{{ $this->availableQuestions['totalPages'] }}</span>
                                </div>
                                <div class="flex gap-2">
                                    <button wire:click="previousPage" @if($currentPage <= 1) disabled @endif
                                        class="px-4 py-2 text-sm font-medium border border-gray-300 rounded-lg hover:bg-white transition disabled:opacity-50 disabled:cursor-not-allowed">
                                        ← Trước
                                    </button>
                                    <button wire:click="nextPage" @if($currentPage >= $this->availableQuestions['totalPages']) disabled @endif
                                        class="px-4 py-2 text-sm font-medium border border-gray-300 rounded-lg hover:bg-white transition disabled:opacity-50 disabled:cursor-not-allowed">
                                        Sau →
                                    </button>
                                </div>
                            </div>
                        </div>
                    @endif
                </div>
            </div>

            {{-- RIGHT COLUMN (45%) - Selected Questions --}}
            <div class="col-span-5 sticky top-8 self-start">
                <div class="bg-white rounded-xl shadow-sm border border-gray-200 h-[calc(100vh-160px)] flex flex-col">
                    
                    {{-- Sticky Header với Buttons --}}
                    <div class="sticky top-0 bg-white rounded-t-xl border-b border-gray-200 px-6 py-4 z-10 space-y-3">
                        <div class="flex items-center justify-between">
                            <h2 class="text-xl font-bold text-gray-900">
                                Câu hỏi trong đề
                                <span class="text-blue-600">({{ count($existingQuestionIds) + count($selectedQuestionIds) }})</span>
                            </h2>
                        </div>
                        
                        {{-- Tổng kết nhanh --}}
                        <div class="flex items-center justify-between text-sm bg-blue-50 border border-blue-200 rounded-lg px-4 py-2">
                            <div class="flex gap-6">
                                <div>
                                    <span class="text-gray-600">Tổng câu hỏi:</span>
                                    <strong class="text-gray-900 ml-1">{{ count($existingQuestionIds) + count($selectedQuestionIds) }}</strong>
                                    <span class="text-gray-500 text-xs ml-1">
                                        (Đã có: {{ count($existingQuestionIds) }}
                                        @php
                                            $totalChange = count($selectedQuestionIds) - count($questionsToDelete);
                                        @endphp
                                        @if($totalChange != 0)
                                            + Thay đổi: <span class="{{ $totalChange > 0 ? 'text-blue-600' : 'text-red-600' }} font-semibold">
                                                            {{ $totalChange > 0 ? '+' : '-' }}{{ abs($totalChange) }}
                                                        </span>
                                        @endif)
                                    </span>
                                </div>
                                <div>
                                    <span class="text-gray-600">Tổng điểm:</span>
                                    <strong class="text-green-600 ml-1">{{ $this->totalScore }}</strong>
                                    <span class="text-gray-500 text-xs">điểm</span>
                                </div>
                            </div>
                        </div>
                        
                        {{-- Action Buttons --}}
                        <div class="flex gap-3">
                            <a href="{{ route('contributor.exams.detail', ['examId' => $examId]) }}"
                                class="flex-1 px-4 py-2.5 text-sm font-medium text-gray-700 bg-white border border-gray-300 rounded-lg hover:bg-gray-50 transition shadow-sm text-center">
                                ← Quay lại
                            </a>
                            <button wire:click="saveQuestionsToExam"
                                class="flex-1 px-4 py-2.5 text-sm font-bold text-white bg-gradient-to-r from-blue-600 to-indigo-600 rounded-lg hover:from-blue-700 hover:to-indigo-700 transition shadow-md hover:shadow-lg">
                                💾 Lưu{{ count($selectedQuestionIds) > 0 ? ' (' . count($selectedQuestionIds) . ' câu mới)' : '' }}
                            </button>
                        </div>
                    </div>

                    {{-- Scrollable Questions List --}}
                    <div class="flex-1 overflow-y-auto px-6 py-4 space-y-4">
                        
                        {{-- Existing Questions --}}
                        @if($this->existingQuestions->count() > 0)
                            <div>
                                <p class="text-xs font-semibold text-gray-500 mb-3 uppercase">
                                    Đã có trong đề ({{ count($existingQuestionIds) }})
                                </p>
                                <div class="space-y-2">
                                    @foreach($this->existingQuestions as $question)
                                        <div wire:key="exist-q-{{ $question->id }}" class="p-3 border rounded-lg transition
                                            {{ in_array($question->id, $questionsToDelete) 
                                                ? 'border-red-300 bg-red-50 opacity-60' 
                                                : 'border-gray-300 bg-gray-50 hover:border-gray-400' }}">
                                            <div class="flex items-start gap-2">
                                                <div class="flex-1 min-w-0">
                                                    <div class="flex items-center gap-2 mb-2">
                                                        <p class="text-xs text-gray-900 line-clamp-2 {{ in_array($question->id, $questionsToDelete) ? 'line-through' : '' }}">
                                                            {{ Str::limit(strip_tags($question->content), 100) }}
                                                        </p>
                                                        @if(in_array($question->id, $questionsToDelete))
                                                            <span class="inline-flex items-center px-2 py-0.5 rounded-full bg-red-100 text-red-700 text-xs font-medium whitespace-nowrap">
                                                                Sẽ xóa
                                                            </span>
                                                        @endif
                                                    </div>
                                                    <div class="flex flex-wrap gap-1 text-xs">
                                                        <span class="inline-flex items-center px-2 py-0.5 rounded bg-gray-200 text-gray-700">
                                                            @if($question->type === 'single_choice') 1 đáp án
                                                            @elseif($question->type === 'multiple_choice') Nhiều đáp án
                                                            @else Tự luận @endif
                                                        </span>
                                                        <span class="inline-flex items-center px-2 py-0.5 rounded
                                                            @if($question->difficulty === 'easy') bg-green-100 text-green-700
                                                            @elseif($question->difficulty === 'medium') bg-yellow-100 text-yellow-700
                                                            @else bg-red-100 text-red-700 @endif">
                                                            @if($question->difficulty === 'easy') Dễ
                                                            @elseif($question->difficulty === 'medium') TB
                                                            @else Khó @endif
                                                        </span>
                                                    </div>
                                                </div>
                                                <div class="flex items-center gap-2">
                                                    <div class="flex flex-col items-end">
                                                        <label class="text-xs text-gray-500 mb-1">Điểm</label>
                                                        <input type="number" 
                                                            wire:model.live="existingQuestionScores.{{ $question->id }}"
                                                            step="0.5" min="0.5" max="50"
                                                            class="w-16 px-2 py-1 text-xs border-gray-300 rounded focus:border-blue-500 focus:ring-blue-500" />
                                                    </div>
                                                    <button wire:click="removeExistingQuestion({{ $question->id }})"
                                                        class="text-red-500 hover:text-red-700 text-xl font-bold mt-5" title="Xóa câu hỏi">
                                                        ×
                                                    </button>
                                                </div>
                                            </div>
                                        </div>
                                    @endforeach
                                </div>
                            </div>
                        @endif

                        {{-- Newly Selected Questions --}}
                        @if(count($selectedQuestionIds) > 0)
                            <div>
                                <p class="text-xs font-semibold text-blue-600 mb-3 uppercase">
                                    Mới thêm ({{ count($selectedQuestionIds) }})
                                </p>
                                <div class="space-y-2">
                                    @foreach($this->selectedQuestions as $question)
                                        <div wire:key="new-q-{{ $question->id }}" class="p-3 border rounded-lg transition
                                            {{ in_array($question->id, $questionsToDelete) 
                                                ? 'border-red-300 bg-red-50 opacity-60' 
                                                : 'border-blue-300 bg-blue-50 hover:border-blue-400' }}">
                                            <div class="flex items-start gap-2">
                                                <div class="flex-1 min-w-0">
                                                    <div class="flex items-center gap-2 mb-2">
                                                        <p class="text-xs text-gray-900 line-clamp-2 {{ in_array($question->id, $questionsToDelete) ? 'line-through' : '' }}">
                                                            {{ Str::limit(strip_tags($question->content), 100) }}
                                                        </p>
                                                        @if(in_array($question->id, $questionsToDelete))
                                                            <span class="inline-flex items-center px-2 py-0.5 rounded-full bg-red-100 text-red-700 text-xs font-medium whitespace-nowrap">
                                                                Sẽ xóa
                                                            </span>
                                                        @endif
                                                    </div>
                                                    <div class="flex flex-wrap gap-1 text-xs">
                                                        <span class="inline-flex items-center px-2 py-0.5 rounded
                                                            @if($question->type === 'single_choice') bg-blue-200 text-blue-800
                                                            @elseif($question->type === 'multiple_choice') bg-purple-200 text-purple-800
                                                            @else bg-gray-200 text-gray-800 @endif">
                                                            @if($question->type === 'single_choice') 1 đáp án
                                                            @elseif($question->type === 'multiple_choice') Nhiều đáp án
                                                            @else Tự luận @endif
                                                        </span>
                                                        <span class="inline-flex items-center px-2 py-0.5 rounded
                                                            @if($question->difficulty === 'easy') bg-green-200 text-green-800
                                                            @elseif($question->difficulty === 'medium') bg-yellow-200 text-yellow-800
                                                            @else bg-red-200 text-red-800 @endif">
                                                            @if($question->difficulty === 'easy') Dễ
                                                            @elseif($question->difficulty === 'medium') TB
                                                            @else Khó @endif
                                                        </span>
                                                    </div>
                                                </div>
                                                <div class="flex items-center gap-2">
                                                    <div class="flex flex-col items-end">
                                                        <label class="text-xs text-blue-600 mb-1">Điểm</label>
                                                        <input type="number" 
                                                            wire:model.live="questionScores.{{ $question->id }}"
                                                            step="0.5" min="0.5" max="50"
                                                            class="w-16 px-2 py-1 text-xs border-blue-300 rounded focus:border-blue-500 focus:ring-blue-500" />
                                                    </div>
                                                    <button wire:click="removeNewQuestion({{ $question->id }})"
                                                        class="text-red-500 hover:text-red-700 text-xl font-bold mt-5" title="Bỏ chọn">
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
                                    <div class="text-5xl mb-3">📝</div>
                                    <p class="text-base text-gray-600 font-medium">Chưa có câu hỏi nào</p>
                                    <p class="text-sm text-gray-400 mt-1">Click "Thêm" bên trái để chọn câu hỏi</p>
                                </div>
                            </div>
                        @endif
                    </div>
                </div>
            </div>
        </div>
    </div>

    {{-- Floating Back to Top Button (Alpine.js) --}}
    <div x-data="{ showButton: false }" 
         @scroll.window="showButton = (window.pageYOffset > 900)"
         x-show="showButton"
         x-transition
         x-cloak
         class="fixed top-24 left-1/2 -translate-x-1/2 z-50">
        <button @click="window.scrollTo({ top: 250, behavior: 'smooth' })"
                class="px-4 py-3 bg-gradient-to-r from-blue-600 to-indigo-600 text-white rounded-full shadow-lg hover:from-blue-700 hover:to-indigo-700 transition-all duration-300 flex items-center gap-2 font-medium">
            <svg class="w-5 h-5" fill="none" stroke="currentColor" viewBox="0 0 24 24">
                <path stroke-linecap="round" stroke-linejoin="round" stroke-width="2" d="M5 10l7-7m0 0l7 7m-7-7v18"></path>
            </svg>
            <span class="text-sm">Quay lại cấu hình</span>
        </button>
    </div>
</div>
