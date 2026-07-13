<div class="container mx-auto px-4 py-6 max-w-4xl">
    {{-- Header --}}
    <div class="mb-6">
        <div class="flex items-center gap-4 mb-2">
            <a href="{{ route('admin.projects.index') }}" class="text-gray-600 hover:text-gray-900">
                <svg class="w-6 h-6" fill="none" stroke="currentColor" viewBox="0 0 24 24">
                    <path stroke-linecap="round" stroke-linejoin="round" stroke-width="2" d="M10 19l-7-7m0 0l7-7m-7 7h18"/>
                </svg>
            </a>
            <h1 class="text-2xl font-bold text-gray-900">
                {{ $projectId ? 'Chỉnh sửa Project' : 'Tạo Project Mới' }}
            </h1>
        </div>
        <p class="text-sm text-gray-600">Tạo project assignment cho học viên</p>
    </div>

    <form wire:submit.prevent="save" class="space-y-6">
        {{-- Basic Info --}}
        <div class="bg-white rounded-lg shadow-sm border border-gray-200 p-6">
            <h2 class="text-lg font-semibold text-gray-900 mb-4">Thông tin cơ bản</h2>
            
            <div class="grid grid-cols-1 md:grid-cols-2 gap-6">
                {{-- Roadmap --}}
                <div>
                    <label class="block text-sm font-medium text-gray-700 mb-2">
                        Lộ trình <span class="text-red-500">*</span>
                    </label>
                    <select wire:model.live="roadmap_id" 
                            class="w-full px-4 py-2 border border-gray-300 rounded-lg focus:ring-2 focus:ring-blue-500 focus:border-transparent">
                        <option value="">Chọn lộ trình</option>
                        @foreach($roadmaps as $roadmap)
                            <option value="{{ $roadmap->id }}">{{ $roadmap->title }}</option>
                        @endforeach
                    </select>
                    @error('roadmap_id') <span class="text-red-500 text-sm mt-1">{{ $message }}</span> @enderror
                </div>

                {{-- Section --}}
                <div>
                    <label class="block text-sm font-medium text-gray-700 mb-2">Chương (không bắt buộc)</label>
                    <select wire:model="section_id"
                            class="w-full px-4 py-2 border border-gray-300 rounded-lg focus:ring-2 focus:ring-blue-500 focus:border-transparent"
                            {{ !$roadmap_id ? 'disabled' : '' }}>
                        <option value="">Không chọn chương cụ thể</option>
                        @foreach($sections as $section)
                            <option value="{{ $section->id }}">{{ $section->title }}</option>
                        @endforeach
                    </select>
                </div>
            </div>

            {{-- Title --}}
            <div class="mt-6">
                <label class="block text-sm font-medium text-gray-700 mb-2">
                    Tên Project <span class="text-red-500">*</span>
                </label>
                <input type="text" 
                       wire:model="title"
                       class="w-full px-4 py-2 border border-gray-300 rounded-lg focus:ring-2 focus:ring-blue-500 focus:border-transparent"
                       placeholder="VD: Xây dựng ứng dụng To-Do List">
                @error('title') <span class="text-red-500 text-sm mt-1">{{ $message }}</span> @enderror
            </div>

            {{-- Description --}}
            <div class="mt-6">
                <label class="block text-sm font-medium text-gray-700 mb-2">
                    Mô tả Project <span class="text-red-500">*</span>
                </label>
                <textarea wire:model="description"
                          rows="5"
                          class="w-full px-4 py-2 border border-gray-300 rounded-lg focus:ring-2 focus:ring-blue-500 focus:border-transparent"
                          placeholder="Mô tả chi tiết yêu cầu project, mục tiêu học tập..."></textarea>
                @error('description') <span class="text-red-500 text-sm mt-1">{{ $message }}</span> @enderror
            </div>

            {{-- Starter Code URL --}}
            <div class="mt-6">
                <label class="block text-sm font-medium text-gray-700 mb-2">
                    Starter Code URL (GitHub)
                </label>
                <input type="url" 
                       wire:model="starter_code_url"
                       class="w-full px-4 py-2 border border-gray-300 rounded-lg focus:ring-2 focus:ring-blue-500 focus:border-transparent"
                       placeholder="https://github.com/username/repo">
                <p class="text-xs text-gray-500 mt-1">Link GitHub repository chứa code mẫu cho học viên</p>
                @error('starter_code_url') <span class="text-red-500 text-sm mt-1">{{ $message }}</span> @enderror
            </div>
        </div>

        {{-- Settings --}}
        <div class="bg-white rounded-lg shadow-sm border border-gray-200 p-6">
            <h2 class="text-lg font-semibold text-gray-900 mb-4">Cài đặt Project</h2>
            
            <div class="grid grid-cols-1 md:grid-cols-2 gap-6">
                {{-- Deadline --}}
                <div>
                    <label class="block text-sm font-medium text-gray-700 mb-2">Deadline</label>
                    <input type="datetime-local" 
                           wire:model="deadline_at"
                           class="w-full px-4 py-2 border border-gray-300 rounded-lg focus:ring-2 focus:ring-blue-500 focus:border-transparent">
                    @error('deadline_at') <span class="text-red-500 text-sm mt-1">{{ $message }}</span> @enderror
                </div>

                {{-- Max Resubmissions --}}
                <div>
                    <label class="block text-sm font-medium text-gray-700 mb-2">Số lần nộp lại tối đa</label>
                    <input type="number" 
                           wire:model="max_resubmissions"
                           min="1"
                           max="10"
                           class="w-full px-4 py-2 border border-gray-300 rounded-lg focus:ring-2 focus:ring-blue-500 focus:border-transparent">
                    @error('max_resubmissions') <span class="text-red-500 text-sm mt-1">{{ $message }}</span> @enderror
                </div>

                {{-- Max Score --}}
                <div>
                    <label class="block text-sm font-medium text-gray-700 mb-2">Điểm tối đa</label>
                    <input type="number" 
                           wire:model="max_score"
                           min="1"
                           class="w-full px-4 py-2 border border-gray-300 rounded-lg focus:ring-2 focus:ring-blue-500 focus:border-transparent">
                    @error('max_score') <span class="text-red-500 text-sm mt-1">{{ $message }}</span> @enderror
                </div>

                {{-- Passing Score --}}
                <div>
                    <label class="block text-sm font-medium text-gray-700 mb-2">Điểm đạt</label>
                    <input type="number" 
                           wire:model="passing_score"
                           min="1"
                           class="w-full px-4 py-2 border border-gray-300 rounded-lg focus:ring-2 focus:ring-blue-500 focus:border-transparent">
                    @error('passing_score') <span class="text-red-500 text-sm mt-1">{{ $message }}</span> @enderror
                </div>

                {{-- Required Completion % --}}
                <div>
                    <label class="block text-sm font-medium text-gray-700 mb-2">% Hoàn thành lesson yêu cầu</label>
                    <input type="number" 
                           wire:model="required_completion_percentage"
                           min="0"
                           max="100"
                           class="w-full px-4 py-2 border border-gray-300 rounded-lg focus:ring-2 focus:ring-blue-500 focus:border-transparent">
                    <p class="text-xs text-gray-500 mt-1">Học viên phải hoàn thành bao nhiêu % lesson trước khi nộp</p>
                    @error('required_completion_percentage') <span class="text-red-500 text-sm mt-1">{{ $message }}</span> @enderror
                </div>

                {{-- Sort Order --}}
                <div>
                    <label class="block text-sm font-medium text-gray-700 mb-2">Thứ tự sắp xếp</label>
                    <input type="number" 
                           wire:model="sort_order"
                           min="0"
                           class="w-full px-4 py-2 border border-gray-300 rounded-lg focus:ring-2 focus:ring-blue-500 focus:border-transparent">
                    @error('sort_order') <span class="text-red-500 text-sm mt-1">{{ $message }}</span> @enderror
                </div>
            </div>
        </div>

        {{-- Grading Criteria (Simple List) --}}
        <div class="bg-white rounded-lg shadow-sm border border-gray-200 p-6">
            <h2 class="text-lg font-semibold text-gray-900 mb-4">Tiêu chí chấm điểm (Danh sách)</h2>
            <p class="text-sm text-gray-600 mb-4">Danh sách các tiêu chí đánh giá chung (hiển thị cho học viên)</p>
            
            <div class="space-y-3">
                @foreach($grading_criteria as $index => $criterion)
                    <div class="flex items-center gap-3">
                        <span class="text-gray-700 flex-1">{{ $index + 1 }}. {{ $criterion }}</span>
                        <button type="button" 
                                wire:click="removeCriterion({{ $index }})"
                                class="text-red-600 hover:text-red-800">
                            <svg class="w-5 h-5" fill="none" stroke="currentColor" viewBox="0 0 24 24">
                                <path stroke-linecap="round" stroke-linejoin="round" stroke-width="2" d="M6 18L18 6M6 6l12 12"/>
                            </svg>
                        </button>
                    </div>
                @endforeach
            </div>

            <div class="flex gap-3 mt-4">
                <input type="text" 
                       wire:model="newCriterion"
                       wire:keydown.enter.prevent="addCriterion"
                       class="flex-1 px-4 py-2 border border-gray-300 rounded-lg focus:ring-2 focus:ring-blue-500 focus:border-transparent"
                       placeholder="Nhập tiêu chí mới...">
                <button type="button" 
                        wire:click="addCriterion"
                        class="bg-blue-600 hover:bg-blue-700 text-white px-6 py-2 rounded-lg font-semibold">
                    Thêm
                </button>
            </div>
        </div>

        {{-- Rubric Criteria (Detailed Scoring) --}}
        <div class="bg-white rounded-lg shadow-sm border border-gray-200 p-6">
            <div class="flex items-center justify-between mb-4">
                <div>
                    <h2 class="text-lg font-semibold text-gray-900">Rubric Chi Tiết</h2>
                    <p class="text-sm text-gray-600">Tiêu chí chấm điểm chi tiết cho giảng viên</p>
                </div>
                <button type="button" 
                        wire:click="addRubricCriterion"
                        class="bg-green-600 hover:bg-green-700 text-white px-4 py-2 rounded-lg font-semibold text-sm">
                    + Thêm Tiêu Chí
                </button>
            </div>

            <div class="space-y-4">
                @foreach($rubricCriteria as $index => $criterion)
                    <div class="border border-gray-200 rounded-lg p-4 bg-gray-50">
                        <div class="flex items-start justify-between mb-3">
                            <h3 class="font-semibold text-gray-900">Tiêu chí {{ $index + 1 }}</h3>
                            <button type="button" 
                                    wire:click="removeRubricCriterion({{ $index }})"
                                    class="text-red-600 hover:text-red-800">
                                <svg class="w-5 h-5" fill="none" stroke="currentColor" viewBox="0 0 24 24">
                                    <path stroke-linecap="round" stroke-linejoin="round" stroke-width="2" d="M19 7l-.867 12.142A2 2 0 0116.138 21H7.862a2 2 0 01-1.995-1.858L5 7m5 4v6m4-6v6m1-10V4a1 1 0 00-1-1h-4a1 1 0 00-1 1v3M4 7h16"/>
                                </svg>
                            </button>
                        </div>

                        <div class="grid grid-cols-1 md:grid-cols-2 gap-4">
                            <div class="md:col-span-2">
                                <label class="block text-sm font-medium text-gray-700 mb-1">Tiêu đề</label>
                                <input type="text" 
                                       wire:model="rubricCriteria.{{ $index }}.title"
                                       class="w-full px-4 py-2 border border-gray-300 rounded-lg focus:ring-2 focus:ring-blue-500 focus:border-transparent"
                                       placeholder="VD: Code quality & structure">
                            </div>

                            <div class="md:col-span-2">
                                <label class="block text-sm font-medium text-gray-700 mb-1">Mô tả</label>
                                <textarea wire:model="rubricCriteria.{{ $index }}.description"
                                          rows="2"
                                          class="w-full px-4 py-2 border border-gray-300 rounded-lg focus:ring-2 focus:ring-blue-500 focus:border-transparent"
                                          placeholder="Mô tả chi tiết tiêu chí này..."></textarea>
                            </div>

                            <div>
                                <label class="block text-sm font-medium text-gray-700 mb-1">Điểm tối đa</label>
                                <input type="number" 
                                       wire:model="rubricCriteria.{{ $index }}.max_points"
                                       min="1"
                                       class="w-full px-4 py-2 border border-gray-300 rounded-lg focus:ring-2 focus:ring-blue-500 focus:border-transparent">
                            </div>
                        </div>
                    </div>
                @endforeach

                @if(empty($rubricCriteria))
                    <div class="text-center py-8 text-gray-500">
                        <p>Chưa có tiêu chí nào. Click "Thêm Tiêu Chí" để bắt đầu.</p>
                    </div>
                @endif
            </div>
        </div>

        {{-- Action Buttons --}}
        <div class="flex items-center justify-between pt-6 border-t border-gray-200">
            <a href="{{ route('admin.projects.index') }}" 
               class="text-gray-600 hover:text-gray-900 font-semibold">
                Hủy
            </a>
            <button type="submit" 
                    class="bg-blue-600 hover:bg-blue-700 text-white px-8 py-3 rounded-lg font-semibold flex items-center gap-2 transition-colors">
                <svg class="w-5 h-5" fill="none" stroke="currentColor" viewBox="0 0 24 24">
                    <path stroke-linecap="round" stroke-linejoin="round" stroke-width="2" d="M5 13l4 4L19 7"/>
                </svg>
                {{ $projectId ? 'Cập Nhật Project' : 'Tạo Project' }}
            </button>
        </div>
    </form>
</div>
