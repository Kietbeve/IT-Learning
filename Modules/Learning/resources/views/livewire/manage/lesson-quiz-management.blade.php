<div class="container mx-auto px-4 py-6">
    {{-- Header --}}
    <div class="mb-6">
        <div class="flex items-center justify-between">
            <div>
                <h1 class="text-2xl font-bold text-gray-900">Quản lý Quiz</h1>
                <p class="mt-1 text-sm text-gray-600">
                    Bài học: <span class="font-semibold">{{ $lesson->title }}</span>
                </p>
            </div>
            <div class="flex space-x-3">
                <a href="{{ route('manage.detail', ['roadmap_id' => $lesson->roadmap_id]) }}" 
                   class="px-4 py-2 border border-gray-300 rounded-md text-sm font-medium text-gray-700 hover:bg-gray-50">
                    ← Quay lại
                </a>
                <button wire:click="openCreateModal" 
                        class="px-4 py-2 bg-blue-600 text-white rounded-md text-sm font-medium hover:bg-blue-700">
                    + Tạo Quiz Mới
                </button>
            </div>
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

    {{-- Quiz List --}}
    <div class="bg-white shadow-md rounded-lg overflow-hidden">
        @if($quizzes->count() > 0)
            <table class="min-w-full divide-y divide-gray-200">
                <thead class="bg-gray-50">
                    <tr>
                        <th class="px-6 py-3 text-left text-xs font-medium text-gray-500 uppercase tracking-wider">Tiêu đề</th>
                        <th class="px-6 py-3 text-left text-xs font-medium text-gray-500 uppercase tracking-wider">Thời gian</th>
                        <th class="px-6 py-3 text-left text-xs font-medium text-gray-500 uppercase tracking-wider">Điểm đạt</th>
                        <th class="px-6 py-3 text-left text-xs font-medium text-gray-500 uppercase tracking-wider">Câu hỏi</th>
                        <th class="px-6 py-3 text-left text-xs font-medium text-gray-500 uppercase tracking-wider">Trạng thái</th>
                        <th class="px-6 py-3 text-right text-xs font-medium text-gray-500 uppercase tracking-wider">Thao tác</th>
                    </tr>
                </thead>
                <tbody class="bg-white divide-y divide-gray-200">
                    @foreach($quizzes as $quiz)
                        <tr>
                            <td class="px-6 py-4">
                                <div class="text-sm font-medium text-gray-900">{{ $quiz->title }}</div>
                                @if($quiz->description)
                                    <div class="text-sm text-gray-500">{{ Str::limit($quiz->description, 50) }}</div>
                                @endif
                            </td>
                            <td class="px-6 py-4 text-sm text-gray-900">
                                {{ $quiz->duration_minutes ? $quiz->duration_minutes . ' phút' : 'Không giới hạn' }}
                            </td>
                            <td class="px-6 py-4 text-sm text-gray-900">
                                {{ $quiz->pass_score }}%
                            </td>
                            <td class="px-6 py-4 text-sm text-gray-900">
                                {{ $quiz->questions_count ?? 0 }} câu
                            </td>
                            <td class="px-6 py-4">
                                <span class="px-2 inline-flex text-xs leading-5 font-semibold rounded-full {{ $quiz->is_published ? 'bg-green-100 text-green-800' : 'bg-gray-100 text-gray-800' }}">
                                    {{ $quiz->is_published ? 'Công khai' : 'Ẩn' }}
                                </span>
                            </td>
                            <td class="px-6 py-4 text-right text-sm font-medium space-x-2">
                                <button wire:click="goToQuestionManagement({{ $quiz->id }})" 
                                        class="text-blue-600 hover:text-blue-900">
                                    Câu hỏi
                                </button>
                                <button wire:click="openEditModal({{ $quiz->id }})" 
                                        class="text-indigo-600 hover:text-indigo-900">
                                    Sửa
                                </button>
                                <button wire:click="togglePublished({{ $quiz->id }})" 
                                        class="text-yellow-600 hover:text-yellow-900">
                                    {{ $quiz->is_published ? 'Ẩn' : 'Hiện' }}
                                </button>
                                <button wire:click="openDeleteModal({{ $quiz->id }})" 
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
                    <path stroke-linecap="round" stroke-linejoin="round" stroke-width="2" d="M9 12h6m-6 4h6m2 5H7a2 2 0 01-2-2V5a2 2 0 012-2h5.586a1 1 0 01.707.293l5.414 5.414a1 1 0 01.293.707V19a2 2 0 01-2 2z" />
                </svg>
                <h3 class="mt-2 text-sm font-medium text-gray-900">Chưa có quiz</h3>
                <p class="mt-1 text-sm text-gray-500">Bắt đầu bằng cách tạo quiz đầu tiên.</p>
            </div>
        @endif
    </div>

    {{-- Create Modal --}}
    @if($showCreateModal)
        <div class="fixed inset-0 bg-gray-500 bg-opacity-75 flex items-center justify-center z-50">
            <div class="bg-white rounded-lg shadow-xl max-w-2xl w-full max-h-[90vh] overflow-y-auto">
                <div class="px-6 py-4 border-b border-gray-200">
                    <h3 class="text-lg font-medium text-gray-900">Tạo Quiz Mới</h3>
                </div>
                
                <form wire:submit.prevent="createQuiz" class="px-6 py-4 space-y-4">
                    {{-- Title --}}
                    <div>
                        <label class="block text-sm font-medium text-gray-700 mb-1">
                            Tiêu đề <span class="text-red-500">*</span>
                        </label>
                        <input type="text" wire:model="title" 
                               class="w-full px-3 py-2 border border-gray-300 rounded-md focus:outline-none focus:ring-2 focus:ring-blue-500">
                        @error('title') <span class="text-sm text-red-600">{{ $message }}</span> @enderror
                    </div>

                    {{-- Description --}}
                    <div>
                        <label class="block text-sm font-medium text-gray-700 mb-1">Mô tả</label>
                        <textarea wire:model="description" rows="3"
                                  class="w-full px-3 py-2 border border-gray-300 rounded-md focus:outline-none focus:ring-2 focus:ring-blue-500"></textarea>
                    </div>

                    {{-- Duration and Pass Score --}}
                    <div class="grid grid-cols-2 gap-4">
                        <div>
                            <label class="block text-sm font-medium text-gray-700 mb-1">Thời gian (phút)</label>
                            <input type="number" wire:model="duration_minutes" 
                                   class="w-full px-3 py-2 border border-gray-300 rounded-md focus:outline-none focus:ring-2 focus:ring-blue-500"
                                   placeholder="Để trống = không giới hạn">
                            @error('duration_minutes') <span class="text-sm text-red-600">{{ $message }}</span> @enderror
                        </div>

                        <div>
                            <label class="block text-sm font-medium text-gray-700 mb-1">
                                Điểm đạt (%) <span class="text-red-500">*</span>
                            </label>
                            <input type="number" wire:model="pass_score" min="0" max="100"
                                   class="w-full px-3 py-2 border border-gray-300 rounded-md focus:outline-none focus:ring-2 focus:ring-blue-500">
                            @error('pass_score') <span class="text-sm text-red-600">{{ $message }}</span> @enderror
                        </div>
                    </div>

                    {{-- Max Attempts --}}
                    <div>
                        <label class="block text-sm font-medium text-gray-700 mb-1">Số lần làm tối đa</label>
                        <input type="number" wire:model="max_attempts" 
                               class="w-full px-3 py-2 border border-gray-300 rounded-md focus:outline-none focus:ring-2 focus:ring-blue-500"
                               placeholder="Để trống = không giới hạn">
                    </div>

                    {{-- Options --}}
                    <div class="space-y-3">
                        <div class="flex items-center">
                            <input type="checkbox" wire:model="shuffle_questions" id="create-shuffle-questions"
                                   class="h-4 w-4 text-blue-600 focus:ring-blue-500 border-gray-300 rounded">
                            <label for="create-shuffle-questions" class="ml-2 block text-sm text-gray-700">
                                Trộn thứ tự câu hỏi
                            </label>
                        </div>

                        <div class="flex items-center">
                            <input type="checkbox" wire:model="shuffle_options" id="create-shuffle-options"
                                   class="h-4 w-4 text-blue-600 focus:ring-blue-500 border-gray-300 rounded">
                            <label for="create-shuffle-options" class="ml-2 block text-sm text-gray-700">
                                Trộn thứ tự đáp án
                            </label>
                        </div>

                        <div class="flex items-center">
                            <input type="checkbox" wire:model="show_correct_answers" id="create-show-answers"
                                   class="h-4 w-4 text-blue-600 focus:ring-blue-500 border-gray-300 rounded">
                            <label for="create-show-answers" class="ml-2 block text-sm text-gray-700">
                                Hiển thị đáp án đúng sau khi nộp bài
                            </label>
                        </div>

                        <div class="flex items-center">
                            <input type="checkbox" wire:model="allow_review" id="create-allow-review"
                                   class="h-4 w-4 text-blue-600 focus:ring-blue-500 border-gray-300 rounded">
                            <label for="create-allow-review" class="ml-2 block text-sm text-gray-700">
                                Cho phép xem lại bài làm
                            </label>
                        </div>

                        <div class="flex items-center">
                            <input type="checkbox" wire:model="is_required" id="create-is-required"
                                   class="h-4 w-4 text-blue-600 focus:ring-blue-500 border-gray-300 rounded">
                            <label for="create-is-required" class="ml-2 block text-sm text-gray-700">
                                Bắt buộc hoàn thành
                            </label>
                        </div>

                        <div class="flex items-center">
                            <input type="checkbox" wire:model="is_published" id="create-is-published"
                                   class="h-4 w-4 text-blue-600 focus:ring-blue-500 border-gray-300 rounded">
                            <label for="create-is-published" class="ml-2 block text-sm text-gray-700">
                                Công khai ngay
                            </label>
                        </div>
                    </div>

                    {{-- Buttons --}}
                    <div class="flex justify-end space-x-3 pt-4">
                        <button type="button" wire:click="closeCreateModal"
                                class="px-4 py-2 border border-gray-300 rounded-md text-sm font-medium text-gray-700 hover:bg-gray-50">
                            Hủy
                        </button>
                        <button type="submit"
                                class="px-4 py-2 bg-blue-600 text-white rounded-md text-sm font-medium hover:bg-blue-700">
                            Tạo Quiz
                        </button>
                    </div>
                </form>
            </div>
        </div>
    @endif

    {{-- Edit Modal --}}
    @if($showEditModal)
        <div class="fixed inset-0 bg-gray-500 bg-opacity-75 flex items-center justify-center z-50">
            <div class="bg-white rounded-lg shadow-xl max-w-2xl w-full max-h-[90vh] overflow-y-auto">
                <div class="px-6 py-4 border-b border-gray-200">
                    <h3 class="text-lg font-medium text-gray-900">Chỉnh sửa Quiz</h3>
                </div>
                
                <form wire:submit.prevent="updateQuiz" class="px-6 py-4 space-y-4">
                    {{-- Title --}}
                    <div>
                        <label class="block text-sm font-medium text-gray-700 mb-1">
                            Tiêu đề <span class="text-red-500">*</span>
                        </label>
                        <input type="text" wire:model="title" 
                               class="w-full px-3 py-2 border border-gray-300 rounded-md focus:outline-none focus:ring-2 focus:ring-blue-500">
                        @error('title') <span class="text-sm text-red-600">{{ $message }}</span> @enderror
                    </div>

                    {{-- Description --}}
                    <div>
                        <label class="block text-sm font-medium text-gray-700 mb-1">Mô tả</label>
                        <textarea wire:model="description" rows="3"
                                  class="w-full px-3 py-2 border border-gray-300 rounded-md focus:outline-none focus:ring-2 focus:ring-blue-500"></textarea>
                    </div>

                    {{-- Duration and Pass Score --}}
                    <div class="grid grid-cols-2 gap-4">
                        <div>
                            <label class="block text-sm font-medium text-gray-700 mb-1">Thời gian (phút)</label>
                            <input type="number" wire:model="duration_minutes" 
                                   class="w-full px-3 py-2 border border-gray-300 rounded-md focus:outline-none focus:ring-2 focus:ring-blue-500"
                                   placeholder="Để trống = không giới hạn">
                            @error('duration_minutes') <span class="text-sm text-red-600">{{ $message }}</span> @enderror
                        </div>

                        <div>
                            <label class="block text-sm font-medium text-gray-700 mb-1">
                                Điểm đạt (%) <span class="text-red-500">*</span>
                            </label>
                            <input type="number" wire:model="pass_score" min="0" max="100"
                                   class="w-full px-3 py-2 border border-gray-300 rounded-md focus:outline-none focus:ring-2 focus:ring-blue-500">
                            @error('pass_score') <span class="text-sm text-red-600">{{ $message }}</span> @enderror
                        </div>
                    </div>

                    {{-- Max Attempts --}}
                    <div>
                        <label class="block text-sm font-medium text-gray-700 mb-1">Số lần làm tối đa</label>
                        <input type="number" wire:model="max_attempts" 
                               class="w-full px-3 py-2 border border-gray-300 rounded-md focus:outline-none focus:ring-2 focus:ring-blue-500"
                               placeholder="Để trống = không giới hạn">
                    </div>

                    {{-- Options --}}
                    <div class="space-y-3">
                        <div class="flex items-center">
                            <input type="checkbox" wire:model="shuffle_questions" id="edit-shuffle-questions"
                                   class="h-4 w-4 text-blue-600 focus:ring-blue-500 border-gray-300 rounded">
                            <label for="edit-shuffle-questions" class="ml-2 block text-sm text-gray-700">
                                Trộn thứ tự câu hỏi
                            </label>
                        </div>

                        <div class="flex items-center">
                            <input type="checkbox" wire:model="shuffle_options" id="edit-shuffle-options"
                                   class="h-4 w-4 text-blue-600 focus:ring-blue-500 border-gray-300 rounded">
                            <label for="edit-shuffle-options" class="ml-2 block text-sm text-gray-700">
                                Trộn thứ tự đáp án
                            </label>
                        </div>

                        <div class="flex items-center">
                            <input type="checkbox" wire:model="show_correct_answers" id="edit-show-answers"
                                   class="h-4 w-4 text-blue-600 focus:ring-blue-500 border-gray-300 rounded">
                            <label for="edit-show-answers" class="ml-2 block text-sm text-gray-700">
                                Hiển thị đáp án đúng sau khi nộp bài
                            </label>
                        </div>

                        <div class="flex items-center">
                            <input type="checkbox" wire:model="allow_review" id="edit-allow-review"
                                   class="h-4 w-4 text-blue-600 focus:ring-blue-500 border-gray-300 rounded">
                            <label for="edit-allow-review" class="ml-2 block text-sm text-gray-700">
                                Cho phép xem lại bài làm
                            </label>
                        </div>

                        <div class="flex items-center">
                            <input type="checkbox" wire:model="is_required" id="edit-is-required"
                                   class="h-4 w-4 text-blue-600 focus:ring-blue-500 border-gray-300 rounded">
                            <label for="edit-is-required" class="ml-2 block text-sm text-gray-700">
                                Bắt buộc hoàn thành
                            </label>
                        </div>

                        <div class="flex items-center">
                            <input type="checkbox" wire:model="is_published" id="edit-is-published"
                                   class="h-4 w-4 text-blue-600 focus:ring-blue-500 border-gray-300 rounded">
                            <label for="edit-is-published" class="ml-2 block text-sm text-gray-700">
                                Công khai
                            </label>
                        </div>
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
                        Bạn có chắc chắn muốn xóa quiz này? Hành động này không thể hoàn tác.
                    </p>
                </div>

                <div class="px-6 py-4 bg-gray-50 flex justify-end space-x-3">
                    <button type="button" wire:click="closeDeleteModal"
                            class="px-4 py-2 border border-gray-300 rounded-md text-sm font-medium text-gray-700 hover:bg-gray-50">
                        Hủy
                    </button>
                    <button type="button" wire:click="deleteQuiz"
                            class="px-4 py-2 bg-red-600 text-white rounded-md text-sm font-medium hover:bg-red-700">
                        Xóa
                    </button>
                </div>
            </div>
        </div>
    @endif
</div>
