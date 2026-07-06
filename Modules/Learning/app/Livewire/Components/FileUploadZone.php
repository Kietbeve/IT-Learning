<?php

namespace Modules\Learning\Livewire\Components;

use Livewire\Component;
use Livewire\WithFileUploads;
use Illuminate\Support\Collection;

class FileUploadZone extends Component
{
    use WithFileUploads;

    // Props từ parent component
    public $assignmentId;
    public $submissionId;
    public $allowedFileTypes = null; // e.g., "pdf,docx,zip"
    public $maxFileSize = 100; // MB
    public $multiple = true;
    public $existingFiles = [];

    // Internal state
    public $files = [];
    public $uploadProgress = [];
    public $isDragging = false;
    
    // Validation messages
    public $errorMessages = [];

    protected $listeners = [
        'fileDropped' => 'handleFileDrop',
        'clearFiles' => 'clearAllFiles',
    ];

    public function mount(
        $assignmentId = null,
        $submissionId = null,
        $allowedFileTypes = null,
        $maxFileSize = 100,
        $multiple = true,
        $existingFiles = []
    ) {
        $this->assignmentId = $assignmentId;
        $this->submissionId = $submissionId;
        $this->allowedFileTypes = $allowedFileTypes;
        $this->maxFileSize = $maxFileSize;
        $this->multiple = $multiple;
        $this->existingFiles = $existingFiles;
    }

    public function updatedFiles()
    {
        $this->errorMessages = [];
        $validFiles = [];

        foreach ($this->files as $index => $file) {
            $validation = $this->validateFile($file, $index);
            
            if ($validation['valid']) {
                $validFiles[] = $file;
                $this->uploadProgress[$index] = [
                    'name' => $file->getClientOriginalName(),
                    'size' => $file->getSize(),
                    'progress' => 0,
                    'status' => 'pending',
                ];
            } else {
                $this->errorMessages[] = $validation['error'];
            }
        }

        $this->files = $validFiles;

        // Emit event to parent component
        if (!empty($validFiles)) {
            $this->dispatch('filesSelected', count($validFiles));
        }
    }

    protected function validateFile($file, $index): array
    {
        $fileName = $file->getClientOriginalName();
        $fileSize = $file->getSize();
        $fileExtension = strtolower($file->getClientOriginalExtension());

        // Check file type
        if ($this->allowedFileTypes) {
            $allowed = array_map('trim', explode(',', strtolower($this->allowedFileTypes)));
            if (!in_array($fileExtension, $allowed)) {
                return [
                    'valid' => false,
                    'error' => "File '{$fileName}': Định dạng không được phép. Chỉ chấp nhận: {$this->allowedFileTypes}"
                ];
            }
        }

        // Check file size
        $maxBytes = $this->maxFileSize * 1024 * 1024;
        if ($fileSize > $maxBytes) {
            $sizeMB = round($fileSize / (1024 * 1024), 2);
            return [
                'valid' => false,
                'error' => "File '{$fileName}': Kích thước {$sizeMB}MB vượt quá giới hạn {$this->maxFileSize}MB"
            ];
        }

        return ['valid' => true];
    }

    public function removeFile($index)
    {
        if (isset($this->files[$index])) {
            unset($this->files[$index]);
            unset($this->uploadProgress[$index]);
            $this->files = array_values($this->files); // Re-index array
            $this->uploadProgress = array_values($this->uploadProgress);
        }

        $this->dispatch('fileRemoved', $index);
    }

    public function removeExistingFile($fileId)
    {
        // Emit event to parent to handle deletion
        $this->dispatch('removeExistingFile', $fileId);
    }

    public function clearAllFiles()
    {
        $this->files = [];
        $this->uploadProgress = [];
        $this->errorMessages = [];
    }

    public function getFiles()
    {
        return $this->files;
    }

    public function getAllowedTypesText(): string
    {
        if (!$this->allowedFileTypes) {
            return 'Tất cả các loại file';
        }

        return strtoupper(str_replace(',', ', ', $this->allowedFileTypes));
    }

    public function formatFileSize($bytes): string
    {
        if ($bytes >= 1073741824) {
            return number_format($bytes / 1073741824, 2) . ' GB';
        } elseif ($bytes >= 1048576) {
            return number_format($bytes / 1048576, 2) . ' MB';
        } elseif ($bytes >= 1024) {
            return number_format($bytes / 1024, 2) . ' KB';
        } else {
            return $bytes . ' bytes';
        }
    }

    public function getFileIcon($extension): string
    {
        $iconMap = [
            'pdf' => '📄',
            'doc' => '📝',
            'docx' => '📝',
            'xls' => '📊',
            'xlsx' => '📊',
            'ppt' => '📊',
            'pptx' => '📊',
            'zip' => '📦',
            'rar' => '📦',
            '7z' => '📦',
            'jpg' => '🖼️',
            'jpeg' => '🖼️',
            'png' => '🖼️',
            'gif' => '🖼️',
            'svg' => '🖼️',
            'mp4' => '🎥',
            'avi' => '🎥',
            'mov' => '🎥',
            'txt' => '📋',
            'md' => '📋',
            'json' => '📋',
            'xml' => '📋',
            'html' => '🌐',
            'css' => '🎨',
            'js' => '⚙️',
            'php' => '⚙️',
            'py' => '⚙️',
            'java' => '⚙️',
        ];

        return $iconMap[strtolower($extension)] ?? '📁';
    }

    public function canPreview($extension): bool
    {
        $previewable = ['jpg', 'jpeg', 'png', 'gif', 'svg', 'pdf'];
        return in_array(strtolower($extension), $previewable);
    }

    public function render()
    {
        return view('learning::livewire.components.file-upload-zone');
    }
}
