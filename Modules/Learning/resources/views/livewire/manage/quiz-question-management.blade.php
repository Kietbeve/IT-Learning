<div class="container mx-auto px-4 py-6">
    {{-- Header --}}
    <div class="mb-6">
        <div class="flex items-center justify-between">
            <div>
                <h1 class="text-2xl font-bold text-gray-900">Quản lý Câu hỏi Quiz</h1>
                <p class="mt-1 text-sm text-gray-600">
                    Quiz: <span class="font-semibold">{{ $quiz->title }}</span>
                </p>
                <p class="text-sm text-gray-500">
                    Bài học: {{ $quiz->lesson->title }}
                </p>
            </div>
            <div class="flex space-x-3">
                <a href="{{ route('manage.lessons.quiz.index', ['lessonId' => $lessonId]) }}" 
                   class="px-4 py-2 border border-gray-300 rounded-md text-sm font-medium text-gray-700 hover:bg-gray-50">
                    ← Quay lại
                </a>
                <button wire:click="openCreateModal" 
                        class="px-4 py-2 bg-blue-600 text-white rounded-md text-sm font-medium hover:bg-blue-700">
                    + Thêm Câu hỏi
                </button>
            </div>
        </div>
    </div>

    {{-- Quiz Stats --}}
    <div class="mb-4 p-4 bg-blue-50 border border-blue-200 rounded-md">
        <div class="flex items-center justify-between text-sm">
            <span class="text-gray-700">Tổng số câu hỏi: <strong>{{ $questions->count() }}</strong></span>
            <span class="text-gray-700">Tổng điểm: <strong>{{ $totalScore }}</strong></span>
        </div>
    </div>

    {{-- Flash Messages --}}
    @if (session()->has('success'))
        <div class="mb-4 p-4 bg-green-50 border border-green-200 rounded-md">
            <p class="text-sm text-green-800">{{ session('success') }}</p>
        </div>
    @endif

    @if (session()->has('error'))
        <div class="mb-4 p-4 bg-red-50 border border-red-200 rounded-md">
            <p class="text-sm text-red-800">{{ session('error') }}</p>
        </div>
    @endif

    {{-- Questions List --}}
    <div class="bg-white shadow-md rounded-lg overflow-hidden">
        @if($questions->count() > 0)
            <table class="min-w-full divide-y divide-gray-200">
                <thead class="bg-gray-50">
                    <tr>
                        <th class="px-6 py-3 text-left text-xs font-medium text-gray-500 uppercase tracking-wider">STT</th>
                        <th class="px-6 py-3 text-left text-xs font-medium text-gray-500 uppercase tracking-wider">Câu hỏi</th>
                        <th class="px-6 py-3 text-left text-xs font-medium text-gray-500 uppercase tracking-wider">Loại</th>
                        <th class="px-6 py-3 text-left text-xs font-medium text-gray-500 uppercase tracking-wider">Điểm</th>
                        <th class="px-6 py-3 text-right text-xs font-medium text-gray-500 uppercase tracking-wider">Thao tác</th>
                    </tr>
                </thead>
                <tbody class="bg-white divide-y divide-gray-200">
                    @foreach($questions as $index => $question)
                        <tr>
                            <td class="px-6 py-4 whitespace-nowrap text-sm text-gray-900">
                                {{ $index + 1 }}
                            </td>
                            <td class="px-6 py-4">
                                <div class="text-sm text-gray-900">{{ Str::limit($question->question_text, 80) }}</div>
                                <div class="text-xs text-gray-500 mt-1">
                                    {{ count($question->options) }} đáp án
                                </div>
                            </td>
                            <td class="px-6 py-4 whitespace-nowrap">
                                <span class="px-2 inline-flex text-xs leading-5 font-semibold rounded-full bg-gray-100 text-gray-800">
                                    {{ $this->getQuestionTypeLabel($question->type) }}
                                </span>
                            </td>
                            <td class="px-6 py-4 whitespace-nowrap text-sm text-gray-900">
                                {{ $question->score }} điểm
                            </td>
                            <td class="px-6 py-4 whitespace-nowrap text-right text-sm font-medium space-x-2">
                                @if($index > 0)
                                    <button wire:click="moveQuestionUp({{ $question->id }})" 
                                            class="text-gray-600 hover:text-gray-900">
                                        ↑
                                    </button>
                                @endif
                                @if($index < $questions->count() - 1)
                                    <button wire:click="moveQuestionDown({{ $question->id }})" 
                                            class="text-gray-600 hover:text-gray-900">
                                        ↓
                                    </button>
                                @endif
                                <button wire:click="openEditModal({{ $question->id }})" 
                                        class="text-indigo-600 hover:text-indigo-900">
                                    Sửa
                                </button>
                                <button wire:click="openDeleteModal({{ $question->id }})" 
                                        class="text-red-600 hover:text-red-900">
                                    Xóa
                                </button>
                            </td>
                        </tr>
                    @endforeach
                </tbody>
            </table>
        @else
            <div class="text-center py-12">
                <svg class="mx-auto h-12 w-12 text-gray-400" fill="none" stroke="currentColor" viewBox="0 0 24 24">
                    <path stroke-linecap="round" stroke-linejoin="round" stroke-width="2" d="M8.228 9c.549-1.165 2.03-2 3.772-2 2.21 0 4 1.343 4 3 0 1.4-1.278 2.575-3.006 2.907-.542.104-.994.54-.994 1.093m0 3h.01M21 12a9 9 0 11-18 0 9 9 0 0118 0z" />
                </svg>
                <h3 class="mt-2 text-sm font-medium text-gray-900">Chưa có câu hỏi</h3>
                <p class="mt-1 text-sm text-gray-500">Bắt đầu bằng cách thêm câu hỏi đầu tiên.</p>
            </div>
        @endif
    </div>

    {{-- Create Question Modal --}}
    @if($showCreateModal)
        <div class="fixed inset-0 bg-gray-500 bg-opacity-75 flex items-center justify-center z-50">
            <div class="bg-white rounded-lg shadow-xl max-w-3xl w-full max-h-[90vh] overflow-y-auto">
                <div class="px-6 py-4 border-b border-gray-200">
                    <h3 class="text-lg font-medium text-gray-900">Thêm Câu hỏi Mới</h3>
                </div>
                
                <form wire:submit.prevent="createQuestion" class="px-6 py-4 space-y-4">
                    {{-- Question Type --}}
                    <div>
                        <label class="block text-sm font-medium text-gray-700 mb-1">
                            Loại câu hỏi <span class="text-red-500">*</span>
                        </label>
                        <select wire:model="type" 
                                class="w-full px-3 py-2 border border-gray-300 rounded-md focus:outline-none focus:ring-2 focus:ring-blue-500">
                            <option value="single_choice">Một lựa chọn</option>
                            <option value="multiple_choice">Nhiều lựa chọn</option>
                            <option value="true_false">Đúng/Sai</option>
                        </select>
                        @error('type') <span class="text-sm text-red-600">{{ $message }}</span> @enderror
                    </div>

                    {{-- Question Text --}}
                    <div>
                        <label class="block text-sm font-medium text-gray-700 mb-1">
                            Nội dung câu hỏi <span class="text-red-500">*</span>
                        </label>
                        <textarea wire:model="question_text" rows="3"
                                  class="w-full px-3 py-2 border border-gray-300 rounded-md focus:outline-none focus:ring-2 focus:ring-blue-500"></textarea>
                        @error('question_text') <span class="text-sm text-red-600">{{ $message }}</span> @enderror
                    </div>

                    {{-- Options List --}}
                    <div>
                        <label class="block text-sm font-medium text-gray-700 mb-2">
                            Các đáp án <span class="text-red-500">*</span>
                        </label>
                        
                        @if(!empty($options))
                            <div class="space-y-2 mb-3">
                                @foreach($options as $index => $option)
                                    <div class="flex items-center space-x-2 p-3 bg-gray-50 rounded-md">
                                        <input type="checkbox" 
                                               wire:click="toggleOptionCorrect({{ $index }})"
                                               {{ $option['is_correct'] ? 'checked' : '' }}
                                               class="h-4 w-4 text-green-600 focus:ring-green-500 border-gray-300 rounded">
                                        <span class="font-semibold text-gray-700">{{ $option['key'] }}.</span>
                                        <input type="text" 
                                               wire:model="options.{{ $index }}.text"
                                               class="flex-1 px-3 py-2 border border-gray-300 rounded-md focus:outline-none focus:ring-2 focus:ring-blue-500">
                                        @if(count($options) > 2)
                                            <button type="button" 
                                                    wire:click="removeOption({{ $index }})"
                                                    class="px-3 py-2 text-red-600 hover:text-red-800">
                                                Xóa
                                            </button>
                                        @endif
                                    </div>
                                @endforeach
                            </div>
                        @endif

                        {{-- Add Option Form --}}
                        <div class="flex items-center space-x-2 p-3 bg-blue-50 border border-blue-200 rounded-md">
                            <input type="checkbox" 
                                   wire:model="newOptionIsCorrect"
                                   class="h-4 w-4 text-green-600 focus:ring-green-500 border-gray-300 rounded">
                            <input type="text" 
                                   wire:model="newOptionText"
                                   placeholder="Nhập nội dung đáp án mới..."
                                   class="flex-1 px-3 py-2 border border-gray-300 rounded-md focus:outline-none focus:ring-2 focus:ring-blue-500">
                            <button type="button" 
                                    wire:click="addOption"
                                    class="px-4 py-2 bg-blue-600 text-white rounded-md text-sm font-medium hover:bg-blue-700">
                                Thêm
                            </button>
                        </div>
                        <p class="mt-1 text-xs text-gray-500">Tích vào checkbox để đánh dấu đáp án đúng</p>
                        @error('options') <span class="text-sm text-red-600">{{ $message }}</span> @enderror
                    </div>

                    {{-- Score --}}
                    <div>
                        <label class="block text-sm font-medium text-gray-700 mb-1">
                            Điểm <span class="text-red-500">*</span>
                        </label>
                        <input type="number" wire:model="score" min="1"
                               class="w-full px-3 py-2 border border-gray-300 rounded-md focus:outline-none focus:ring-2 focus:ring-blue-500">
                        @error('score') <span class="text-sm text-red-600">{{ $message }}</span> @enderror
                    </div>

                    {{-- Explanation --}}
                    <div>
                        <label class="block text-sm font-medium text-gray-700 mb-1">Giải thích</label>
                        <textarea wire:model="explanation" rows="2"
                                  class="w-full px-3 py-2 border border-gray-300 rounded-md focus:outline-none focus:ring-2 focus:ring-blue-500"
                                  placeholder="Giải thích tại sao đáp án này đúng..."></textarea>
                    </div>

                    {{-- Buttons --}}
                    <div class="flex justify-end space-x-3 pt-4">
                        <button type="button" wire:click="closeCreateModal"
                                class="px-4 py-2 border border-gray-300 rounded-md text-sm font-medium text-gray-700 hover:bg-gray-50">
                            Hủy
                        </button>
                        <button type="submit"
                                class="px-4 py-2 bg-blue-600 text-white rounded-md text-sm font-medium hover:bg-blue-700">
                            Thêm Câu hỏi
                        </button>
                    </div>
                </form>
            </div>
        </div>
    @endif

    {{-- Edit Question Modal --}}
    @if($showEditModal)
        <div class="fixed inset-0 bg-gray-500 bg-opacity-75 flex items-center justify-center z-50">
            <div class="bg-white rounded-lg shadow-xl max-w-3xl w-full max-h-[90vh] overflow-y-auto">
                <div class="px-6 py-4 border-b border-gray-200">
                    <h3 class="text-lg font-medium text-gray-900">Chỉnh sửa Câu hỏi</h3>
                </div>
                
                <form wire:submit.prevent="updateQuestion" class="px-6 py-4 space-y-4">
                    {{-- Question Type --}}
                    <div>
                        <label class="block text-sm font-medium text-gray-700 mb-1">
                            Loại câu hỏi <span class="text-red-500">*</span>
                        </label>
                        <select wire:model="type" 
                                class="w-full px-3 py-2 border border-gray-300 rounded-md focus:outline-none focus:ring-2 focus:ring-blue-500">
                            <option value="single_choice">Một lựa chọn</option>
                            <option value="multiple_choice">Nhiều lựa chọn</option>
                            <option value="true_false">Đúng/Sai</option>
                        </select>
                    </div>

                    {{-- Question Text --}}
                    <div>
                        <label class="block text-sm font-medium text-gray-700 mb-1">
                            Nội dung câu hỏi <span class="text-red-500">*</span>
                        </label>
                        <textarea wire:model="question_text" rows="3"
                                  class="w-full px-3 py-2 border border-gray-300 rounded-md focus:outline-none focus:ring-2 focus:ring-blue-500"></textarea>
                    </div>

                    {{-- Options List --}}
                    <div>
                        <label class="block text-sm font-medium text-gray-700 mb-2">
                            Các đáp án <span class="text-red-500">*</span>
                        </label>
                        
                        @if(!empty($options))
                            <div class="space-y-2 mb-3">
                                @foreach($options as $index => $option)
                                    <div class="flex items-center space-x-2 p-3 bg-gray-50 rounded-md">
                                        <input type="checkbox" 
                                               wire:click="toggleOptionCorrect({{ $index }})"
                                               {{ $option['is_correct'] ? 'checked' : '' }}
                                               class="h-4 w-4 text-green-600 focus:ring-green-500 border-gray-300 rounded">
                                        <span class="font-semibold text-gray-700">{{ $option['key'] }}.</span>
                                        <input type="text" 
                                               wire:model="options.{{ $index }}.text"
                                               class="flex-1 px-3 py-2 border border-gray-300 rounded-md focus:outline-none focus:ring-2 focus:ring-blue-500">
                                        @if(count($options) > 2)
                                            <button type="button" 
                                                    wire:click="removeOption({{ $index }})"
                                                    class="px-3 py-2 text-red-600 hover:text-red-800">
                                                Xóa
                                            </button>
                                        @endif
                                    </div>
                                @endforeach
                            </div>
                        @endif

                        {{-- Add Option Form --}}
                        <div class="flex items-center space-x-2 p-3 bg-blue-50 border border-blue-200 rounded-md">
                            <input type="checkbox" 
                                   wire:model="newOptionIsCorrect"
                                   class="h-4 w-4 text-green-600 focus:ring-green-500 border-gray-300 rounded">
                            <input type="text" 
                                   wire:model="newOptionText"
                                   placeholder="Nhập nội dung đáp án mới..."
                                   class="flex-1 px-3 py-2 border border-gray-300 rounded-md focus:outline-none focus:ring-2 focus:ring-blue-500">
                            <button type="button" 
                                    wire:click="addOption"
                                    class="px-4 py-2 bg-blue-600 text-white rounded-md text-sm font-medium hover:bg-blue-700">
                                Thêm
                            </button>
                        </div>
                        <p class="mt-1 text-xs text-gray-500">Tích vào checkbox để đánh dấu đáp án đúng</p>
                    </div>

                    {{-- Score --}}
                    <div>
                        <label class="block text-sm font-medium text-gray-700 mb-1">
                            Điểm <span class="text-red-500">*</span>
                        </label>
                        <input type="number" wire:model="score" min="1"
                               class="w-full px-3 py-2 border border-gray-300 rounded-md focus:outline-none focus:ring-2 focus:ring-blue-500">
                    </div>

                    {{-- Explanation --}}
                    <div>
                        <label class="block text-sm font-medium text-gray-700 mb-1">Giải thích</label>
                        <textarea wire:model="explanation" rows="2"
                                  class="w-full px-3 py-2 border border-gray-300 rounded-md focus:outline-none focus:ring-2 focus:ring-blue-500"
                                  placeholder="Giải thích tại sao đáp án này đúng..."></textarea>
                    </div>

                    {{-- Buttons --}}
                    <div class="flex justify-end space-x-3 pt-4">
                        <button type="button" wire:click="closeEditModal"
                                class="px-4 py-2 border border-gray-300 rounded-md text-sm font-medium text-gray-700 hover:bg-gray-50">
                            Hủy
                        </button>
                        <button type="submit"
                                class="px-4 py-2 bg-blue-600 text-white rounded-md text-sm font-medium hover:bg-blue-700">
                            Cập nhật
                        </button>
                    </div>
                </form>
            </div>
        </div>
    @endif

    {{-- Delete Confirmation Modal --}}
    @if($showDeleteModal)
        <div class="fixed inset-0 bg-gray-500 bg-opacity-75 flex items-center justify-center z-50">
            <div class="bg-white rounded-lg shadow-xl max-w-md w-full">
                <div class="px-6 py-4 border-b border-gray-200">
                    <h3 class="text-lg font-medium text-gray-900">Xác nhận xóa</h3>
                </div>
                
                <div class="px-6 py-4">
                    <p class="text-sm text-gray-600">
                        Bạn có chắc chắn muốn xóa câu hỏi này? Hành động này không thể hoàn tác.
                    </p>
                </div>

                <div class="px-6 py-4 bg-gray-50 flex justify-end space-x-3">
                    <button type="button" wire:click="closeDeleteModal"
                            class="px-4 py-2 border border-gray-300 rounded-md text-sm font-medium text-gray-700 hover:bg-gray-50">
                        Hủy
                    </button>
                    <button type="button" wire:click="deleteQuestion"
                            class="px-4 py-2 bg-red-600 text-white rounded-md text-sm font-medium hover:bg-red-700">
                        Xóa
                    </button>
                </div>
            </div>
        </div>
    @endif
</div>
