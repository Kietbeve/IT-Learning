<div class="max-w-4xl mx-auto py-6">
    <div class="bg-white shadow-sm rounded-lg border border-gray-200">
        <!-- Header -->
        <div class="px-6 py-4 border-b border-gray-200">
            <h2 class="text-xl font-bold text-gray-900">{{ $assignment->title }}</h2>
            <p class="mt-1 text-sm text-gray-600">{{ $assignment->description }}</p>
            
            <!-- Deadline Status -->
            @php $deadlineStatus = $this->getDeadlineStatus(); @endphp
            <div class="mt-3 flex items-center gap-4">
                <span class="text-sm {{ $deadlineStatus['class'] }}">
                    <svg class="w-5 h-5 inline mr-1" fill="none" stroke="currentColor" viewBox="0 0 24 24">
                        <path stroke-linecap="round" stroke-linejoin="round" stroke-width="2" 
                            d="M12 8v4l3 3m6-3a9 9 0 11-18 0 9 9 0 0118 0z" />
                    </svg>
                    Deadline: {{ $submission->deadline?->format('d/m/Y H:i') }} ({{ $deadlineStatus['text'] }})
                </span>
                
                @if($submission->is_late)
                    <span class="px-2 py-1 bg-red-100 text-red-800 text-xs font-semibold rounded">
                        Nộp trễ {{ $submission->days_late }} ngày
                    </span>
                @endif
            </div>
        </div>

        <!-- Form -->
        <form wire:submit.prevent="submit" class="p-6 space-y-6">
            <!-- Instructions -->
            @if($assignment->instructions)
                <div class="bg-blue-50 border border-blue-200 rounded-lg p-4">
                    <h3 class="text-sm font-semibold text-blue-900 mb-2">📋 Hướng dẫn</h3>
                    <div class="text-sm text-blue-800 prose prose-sm max-w-none">
                        {!! nl2br(e($assignment->instructions)) !!}
                    </div>
                </div>
            @endif

            <!-- Requirements -->
            @if($assignment->requirements)
                <div class="bg-yellow-50 border border-yellow-200 rounded-lg p-4">
                    <h3 class="text-sm font-semibold text-yellow-900 mb-2">✅ Yêu cầu</h3>
                    <ul class="text-sm text-yellow-800 list-disc list-inside space-y-1">
                        @foreach($assignment->requirements as $requirement)
                            <li>{{ $requirement }}</li>
                        @endforeach
                    </ul>
                </div>
            @endif

            <!-- Submission Type Info -->
            <div class="bg-gray-50 border border-gray-200 rounded-lg p-4">
                <p class="text-sm text-gray-700">
                    <strong>Loại nộp bài:</strong> {{ $this->getSubmissionTypeText() }}
                </p>
                @if($assignment->allowed_file_types)
                    <p class="text-sm text-gray-700 mt-1">
                        <strong>File được phép:</strong> {{ $this->getAllowedFileTypesText() }}
                    </p>
                    <p class="text-sm text-gray-700 mt-1">
                        <strong>Kích thước tối đa:</strong> {{ $assignment->max_file_size_mb }}MB
                    </p>
                @endif
            </div>

            <!-- GitHub URL -->
            @if(in_array($assignment->submission_type, ['github', 'both']))
                <div>
                    <label class="block text-sm font-medium text-gray-700 mb-2">
                        GitHub Repository URL
                        @if($assignment->submission_type === 'github')
                            <span class="text-red-500">*</span>
                        @endif
                    </label>
                    <input 
                        type="url" 
                        wire:model.blur="githubUrl"
                        class="w-full px-4 py-2 border border-gray-300 rounded-lg focus:ring-2 focus:ring-blue-500 focus:border-transparent"
                        placeholder="https://github.com/username/repository"
                    >
                    @error('githubUrl')
                        <p class="mt-1 text-sm text-red-600">{{ $message }}</p>
                    @enderror
                </div>
            @endif

            <!-- Live Demo URL -->
            @if(in_array($assignment->submission_type, ['github', 'both']))
                <div>
                    <label class="block text-sm font-medium text-gray-700 mb-2">
                        Live Demo URL (Optional)
                    </label>
                    <input 
                        type="url" 
                        wire:model.blur="liveDemoUrl"
                        class="w-full px-4 py-2 border border-gray-300 rounded-lg focus:ring-2 focus:ring-blue-500 focus:border-transparent"
                        placeholder="https://your-demo.com"
                    >
                    @error('liveDemoUrl')
                        <p class="mt-1 text-sm text-red-600">{{ $message }}</p>
                    @enderror
                </div>
            @endif

            <!-- File Upload - Enhanced Component -->
            @if(in_array($assignment->submission_type, ['file', 'both']))
                <div>
                    <label class="block text-sm font-medium text-gray-700 mb-3">
                        Upload Files
                        @if($assignment->submission_type === 'file')
                            <span class="text-red-500">*</span>
                        @endif
                    </label>
                    
                    @livewire('learning::components.file-upload-zone', [
                        'assignmentId' => $assignment->id,
                        'submissionId' => $submission->id,
                        'allowedFileTypes' => $assignment->allowed_file_types,
                        'maxFileSize' => $assignment->max_file_size_mb,
                        'multiple' => true,
                        'existingFiles' => $existingAttachments,
                    ])
                </div>
            @endif

            <!-- Notes -->
            <div>
                <label class="block text-sm font-medium text-gray-700 mb-2">
                    Ghi chú / Mô tả (Optional)
                </label>
                <textarea 
                    wire:model.blur="notes"
                    rows="4"
                    class="w-full px-4 py-2 border border-gray-300 rounded-lg focus:ring-2 focus:ring-blue-500 focus:border-transparent"
                    placeholder="Thêm ghi chú về bài nộp của bạn..."
                ></textarea>
                @error('notes')
                    <p class="mt-1 text-sm text-red-600">{{ $message }}</p>
                @enderror
            </div>

            <!-- Action Buttons -->
            <div class="flex items-center justify-between pt-6 border-t border-gray-200">
                <div class="flex gap-3">
                    <button 
                        type="button"
                        wire:click="saveDraft"
                        class="px-6 py-2 bg-gray-200 hover:bg-gray-300 text-gray-700 font-medium rounded-lg transition-colors"
                        wire:loading.attr="disabled"
                    >
                        Lưu nháp
                    </button>
                </div>

                <button 
                    type="submit"
                    class="px-6 py-2 bg-blue-600 hover:bg-blue-700 text-white font-medium rounded-lg transition-colors disabled:opacity-50 disabled:cursor-not-allowed"
                    wire:loading.attr="disabled"
                    :disabled="isSubmitting"
                >
                    <span wire:loading.remove wire:target="submit">Nộp bài</span>
                    <span wire:loading wire:target="submit">
                        <svg class="animate-spin h-5 w-5 inline mr-2" fill="none" viewBox="0 0 24 24">
                            <circle class="opacity-25" cx="12" cy="12" r="10" stroke="currentColor" stroke-width="4"></circle>
                            <path class="opacity-75" fill="currentColor" d="M4 12a8 8 0 018-8V0C5.373 0 0 5.373 0 12h4zm2 5.291A7.962 7.962 0 014 12H0c0 3.042 1.135 5.824 3 7.938l3-2.647z"></path>
                        </svg>
                        Đang nộp...
                    </span>
                </button>
            </div>

            <!-- Warning for late submission -->
            @if($deadlineStatus['status'] === 'overdue')
                <div class="bg-red-50 border border-red-200 rounded-lg p-4 mt-4">
                    <p class="text-sm text-red-800">
                        ⚠️ <strong>Cảnh báo:</strong> Bài nộp của bạn sẽ bị đánh dấu là nộp trễ. Điều này có thể ảnh hưởng đến điểm số của bạn.
                    </p>
                </div>
            @endif
        </form>
    </div>
</div>
