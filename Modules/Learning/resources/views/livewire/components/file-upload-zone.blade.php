<div class="file-upload-zone">
    <!-- Error Messages -->
    @if(!empty($errorMessages))
        <div class="mb-4 space-y-2">
            @foreach($errorMessages as $error)
                <div class="bg-red-50 border border-red-200 text-red-700 px-4 py-3 rounded-lg text-sm flex items-start gap-2">
                    <svg class="w-5 h-5 flex-shrink-0 mt-0.5" fill="currentColor" viewBox="0 0 20 20">
                        <path fill-rule="evenodd" d="M10 18a8 8 0 100-16 8 8 0 000 16zM8.707 7.293a1 1 0 00-1.414 1.414L8.586 10l-1.293 1.293a1 1 0 101.414 1.414L10 11.414l1.293 1.293a1 1 0 001.414-1.414L11.414 10l1.293-1.293a1 1 0 00-1.414-1.414L10 9.586 8.707 8.293z" clip-rule="evenodd"/>
                    </svg>
                    <span>{{ $error }}</span>
                </div>
            @endforeach
        </div>
    @endif

    <!-- Drag & Drop Zone -->
    <div 
        x-data="{ 
            isDragging: false,
            handleDragEnter(e) {
                e.preventDefault();
                e.stopPropagation();
                this.isDragging = true;
            },
            handleDragLeave(e) {
                e.preventDefault();
                e.stopPropagation();
                if (e.target === e.currentTarget) {
                    this.isDragging = false;
                }
            },
            handleDragOver(e) {
                e.preventDefault();
                e.stopPropagation();
            },
            handleDrop(e) {
                e.preventDefault();
                e.stopPropagation();
                this.isDragging = false;
                
                const files = Array.from(e.dataTransfer.files);
                if (files.length > 0) {
                    $refs.fileInput.files = e.dataTransfer.files;
                    $refs.fileInput.dispatchEvent(new Event('change', { bubbles: true }));
                }
            }
        }"
        @dragenter="handleDragEnter"
        @dragleave="handleDragLeave"
        @dragover="handleDragOver"
        @drop="handleDrop"
        :class="{ 'border-blue-500 bg-blue-50': isDragging }"
        class="border-2 border-dashed border-gray-300 rounded-lg p-8 text-center transition-all duration-200 hover:border-blue-400 hover:bg-gray-50 cursor-pointer"
    >
        <input 
            type="file" 
            wire:model="files"
            x-ref="fileInput"
            {{ $multiple ? 'multiple' : '' }}
            class="hidden"
            id="file-upload-{{ $submissionId ?? 'new' }}"
            accept="{{ $allowedFileTypes ? '.' . str_replace(',', ',.', $allowedFileTypes) : '' }}"
        >
        
        <label for="file-upload-{{ $submissionId ?? 'new' }}" class="cursor-pointer block">
            <div class="flex flex-col items-center">
                <!-- Upload Icon -->
                <svg class="w-16 h-16 text-gray-400 mb-4" fill="none" stroke="currentColor" viewBox="0 0 24 24">
                    <path stroke-linecap="round" stroke-linejoin="round" stroke-width="2" 
                        d="M7 16a4 4 0 01-.88-7.903A5 5 0 1115.9 6L16 6a5 5 0 011 9.9M15 13l-3-3m0 0l-3 3m3-3v12" />
                </svg>

                <!-- Instructions -->
                <p class="text-lg font-medium text-gray-700 mb-2">
                    Kéo thả file vào đây hoặc click để chọn
                </p>
                <p class="text-sm text-gray-500 mb-3">
                    {{ $multiple ? 'Có thể chọn nhiều file cùng lúc' : 'Chỉ chọn 1 file' }}
                </p>

                <!-- File Type & Size Info -->
                <div class="inline-flex items-center gap-4 text-xs text-gray-600 bg-gray-100 px-4 py-2 rounded-full">
                    <span>
                        <strong>Định dạng:</strong> {{ $this->getAllowedTypesText() }}
                    </span>
                    <span class="text-gray-300">|</span>
                    <span>
                        <strong>Tối đa:</strong> {{ $maxFileSize }}MB/file
                    </span>
                </div>
            </div>
        </label>
    </div>

    <!-- Upload Progress Indicator -->
    <div wire:loading wire:target="files" class="mt-4">
        <div class="bg-blue-50 border border-blue-200 rounded-lg p-4">
            <div class="flex items-center gap-3">
                <svg class="animate-spin h-6 w-6 text-blue-600" fill="none" viewBox="0 0 24 24">
                    <circle class="opacity-25" cx="12" cy="12" r="10" stroke="currentColor" stroke-width="4"></circle>
                    <path class="opacity-75" fill="currentColor" d="M4 12a8 8 0 018-8V0C5.373 0 0 5.373 0 12h4zm2 5.291A7.962 7.962 0 014 12H0c0 3.042 1.135 5.824 3 7.938l3-2.647z"></path>
                </svg>
                <div>
                    <p class="text-sm font-medium text-blue-900">Đang xử lý file...</p>
                    <p class="text-xs text-blue-700">Vui lòng đợi trong giây lát</p>
                </div>
            </div>
        </div>
    </div>

    <!-- Selected Files Preview -->
    @if(!empty($files))
        <div class="mt-6 space-y-3">
            <h4 class="text-sm font-semibold text-gray-700 flex items-center gap-2">
                <svg class="w-5 h-5 text-green-600" fill="currentColor" viewBox="0 0 20 20">
                    <path fill-rule="evenodd" d="M10 18a8 8 0 100-16 8 8 0 000 16zm3.707-9.293a1 1 0 00-1.414-1.414L9 10.586 7.707 9.293a1 1 0 00-1.414 1.414l2 2a1 1 0 001.414 0l4-4z" clip-rule="evenodd"/>
                </svg>
                File đã chọn ({{ count($files) }})
            </h4>

            @foreach($files as $index => $file)
                @php
                    $extension = $file->getClientOriginalExtension();
                    $fileName = $file->getClientOriginalName();
                    $fileSize = $file->getSize();
                @endphp
                
                <div class="bg-gradient-to-r from-green-50 to-blue-50 border border-green-200 rounded-lg p-4 transition-all hover:shadow-md">
                    <div class="flex items-center justify-between">
                        <!-- File Info -->
                        <div class="flex items-center gap-3 flex-1 min-w-0">
                            <!-- File Icon -->
                            <div class="flex-shrink-0 w-12 h-12 bg-white rounded-lg flex items-center justify-center text-2xl shadow-sm">
                                {{ $this->getFileIcon($extension) }}
                            </div>

                            <!-- File Details -->
                            <div class="flex-1 min-w-0">
                                <p class="text-sm font-medium text-gray-900 truncate">
                                    {{ $fileName }}
                                </p>
                                <div class="flex items-center gap-3 text-xs text-gray-600 mt-1">
                                    <span class="bg-white px-2 py-0.5 rounded">{{ strtoupper($extension) }}</span>
                                    <span>{{ $this->formatFileSize($fileSize) }}</span>
                                    @if($this->canPreview($extension))
                                        <span class="text-green-600 flex items-center gap-1">
                                            <svg class="w-3 h-3" fill="currentColor" viewBox="0 0 20 20">
                                                <path d="M10 12a2 2 0 100-4 2 2 0 000 4z"/>
                                                <path fill-rule="evenodd" d="M.458 10C1.732 5.943 5.522 3 10 3s8.268 2.943 9.542 7c-1.274 4.057-5.064 7-9.542 7S1.732 14.057.458 10zM14 10a4 4 0 11-8 0 4 4 0 018 0z" clip-rule="evenodd"/>
                                            </svg>
                                            Preview
                                        </span>
                                    @endif
                                </div>
                            </div>
                        </div>

                        <!-- Remove Button -->
                        <button 
                            type="button"
                            wire:click="removeFile({{ $index }})"
                            class="flex-shrink-0 ml-4 p-2 text-red-600 hover:bg-red-100 rounded-lg transition-colors"
                            title="Xóa file"
                        >
                            <svg class="w-5 h-5" fill="none" stroke="currentColor" viewBox="0 0 24 24">
                                <path stroke-linecap="round" stroke-linejoin="round" stroke-width="2" 
                                    d="M19 7l-.867 12.142A2 2 0 0116.138 21H7.862a2 2 0 01-1.995-1.858L5 7m5 4v6m4-6v6m1-10V4a1 1 0 00-1-1h-4a1 1 0 00-1 1v3M4 7h16" />
                            </svg>
                        </button>
                    </div>

                    <!-- Progress Bar (if uploading) -->
                    @if(isset($uploadProgress[$index]))
                        <div class="mt-3">
                            <div class="flex items-center justify-between text-xs text-gray-600 mb-1">
                                <span>{{ $uploadProgress[$index]['status'] === 'pending' ? 'Chờ upload' : 'Đang upload...' }}</span>
                                <span>{{ $uploadProgress[$index]['progress'] ?? 0 }}%</span>
                            </div>
                            <div class="w-full bg-gray-200 rounded-full h-2 overflow-hidden">
                                <div 
                                    class="bg-gradient-to-r from-green-500 to-blue-500 h-2 rounded-full transition-all duration-300"
                                    style="width: {{ $uploadProgress[$index]['progress'] ?? 0 }}%"
                                ></div>
                            </div>
                        </div>
                    @endif
                </div>
            @endforeach
        </div>
    @endif

    <!-- Existing Files (Already Uploaded) -->
    @if(!empty($existingFiles) && count($existingFiles) > 0)
        <div class="mt-6 space-y-3">
            <h4 class="text-sm font-semibold text-gray-700 flex items-center gap-2">
                <svg class="w-5 h-5 text-blue-600" fill="currentColor" viewBox="0 0 20 20">
                    <path fill-rule="evenodd" d="M3 17a1 1 0 011-1h12a1 1 0 110 2H4a1 1 0 01-1-1zm3.293-7.707a1 1 0 011.414 0L9 10.586V3a1 1 0 112 0v7.586l1.293-1.293a1 1 0 111.414 1.414l-3 3a1 1 0 01-1.414 0l-3-3a1 1 0 010-1.414z" clip-rule="evenodd"/>
                </svg>
                File đã upload ({{ count($existingFiles) }})
            </h4>

            @foreach($existingFiles as $file)
                <div class="bg-white border border-gray-200 rounded-lg p-4 hover:shadow-md transition-shadow">
                    <div class="flex items-center justify-between">
                        <!-- File Info -->
                        <div class="flex items-center gap-3 flex-1 min-w-0">
                            <div class="flex-shrink-0 w-10 h-10 bg-gray-50 rounded-lg flex items-center justify-center text-xl">
                                {{ $this->getFileIcon($file->file_type ?? 'file') }}
                            </div>
                            <div class="flex-1 min-w-0">
                                <p class="text-sm font-medium text-gray-900 truncate">
                                    {{ $file->file_name }}
                                </p>
                                <div class="flex items-center gap-3 text-xs text-gray-500 mt-1">
                                    <span>{{ strtoupper($file->file_type ?? 'FILE') }}</span>
                                    <span>{{ $this->formatFileSize($file->file_size) }}</span>
                                    @if(isset($file->download_count))
                                        <span>{{ $file->download_count }} lượt tải</span>
                                    @endif
                                </div>
                            </div>
                        </div>

                        <!-- Actions -->
                        <div class="flex items-center gap-2">
                            <a 
                                href="{{ asset('storage/' . $file->file_path) }}" 
                                target="_blank"
                                class="p-2 text-blue-600 hover:bg-blue-50 rounded-lg transition-colors"
                                title="Xem file"
                            >
                                <svg class="w-5 h-5" fill="none" stroke="currentColor" viewBox="0 0 24 24">
                                    <path stroke-linecap="round" stroke-linejoin="round" stroke-width="2" 
                                        d="M15 12a3 3 0 11-6 0 3 3 0 016 0z"/>
                                    <path stroke-linecap="round" stroke-linejoin="round" stroke-width="2" 
                                        d="M2.458 12C3.732 7.943 7.523 5 12 5c4.478 0 8.268 2.943 9.542 7-1.274 4.057-5.064 7-9.542 7-4.477 0-8.268-2.943-9.542-7z"/>
                                </svg>
                            </a>
                            <button 
                                type="button"
                                wire:click="removeExistingFile({{ $file->id }})"
                                class="p-2 text-red-600 hover:bg-red-50 rounded-lg transition-colors"
                                title="Xóa file"
                            >
                                <svg class="w-5 h-5" fill="none" stroke="currentColor" viewBox="0 0 24 24">
                                    <path stroke-linecap="round" stroke-linejoin="round" stroke-width="2" 
                                        d="M19 7l-.867 12.142A2 2 0 0116.138 21H7.862a2 2 0 01-1.995-1.858L5 7m5 4v6m4-6v6m1-10V4a1 1 0 00-1-1h-4a1 1 0 00-1 1v3M4 7h16" />
                                </svg>
                            </button>
                        </div>
                    </div>
                </div>
            @endforeach
        </div>
    @endif
</div>
