<?php

namespace Modules\Learning\Livewire\Student;

use Livewire\Component;
use Livewire\WithFileUploads;
use Modules\Learning\Models\Assignment;
use Modules\Learning\Models\AssignmentSubmission;
use Modules\Learning\Services\AssignmentService;
use Illuminate\Support\Facades\Auth;
use Illuminate\Support\Facades\DB;

class AssignmentSubmissionForm extends Component
{
    use WithFileUploads;

    public $assignmentId;
    public $assignment;
    public $submission;
    public $githubUrl;
    public $liveDemoUrl;
    public $notes;
    public $files = [];
    public $existingAttachments = [];
    public $isSubmitting = false;

    protected $listeners = [
        'filesSelected' => 'handleFilesSelected',
        'fileRemoved' => 'handleFileRemoved',
        'removeExistingFile' => 'removeFile',
    ];

    protected $rules = [
        'githubUrl' => 'nullable|url',
        'liveDemoUrl' => 'nullable|url',
        'notes' => 'nullable|string|max:5000',
    ];

    public function mount($assignmentId, $submissionId = null)
    {
        $this->assignmentId = $assignmentId;
        $this->assignment = Assignment::with(['lesson.roadmap'])->findOrFail($assignmentId);
        
        if ($submissionId) {
            $this->submission = AssignmentSubmission::with(['attachments'])->findOrFail($submissionId);
            $this->githubUrl = $this->submission->github_url;
            $this->liveDemoUrl = $this->submission->live_demo_url;
            $this->notes = $this->submission->notes;
            $this->existingAttachments = $this->submission->attachments;
        } else {
            $assignmentService = app(AssignmentService::class);
            $this->submission = $assignmentService->createSubmission(Auth::id(), $assignmentId, []);
        }
    }

    public function updated($propertyName)
    {
        $this->validateOnly($propertyName);
    }

    public function handleFilesSelected($count)
    {
        // Event from FileUploadZone component
        $this->dispatch('$refresh');
    }

    public function handleFileRemoved($index)
    {
        // Event from FileUploadZone component
        $this->dispatch('$refresh');
    }

    public function saveDraft()
    {
        $this->validate();

        $this->submission->update([
            'github_url' => $this->githubUrl,
            'live_demo_url' => $this->liveDemoUrl,
            'notes' => $this->notes,
        ]);

        session()->flash('success', 'Đã lưu nháp thành công');
    }

    public function submit()
    {
        $this->validate();
        $this->isSubmitting = true;

        try {
            DB::transaction(function () {
                $this->submission->update([
                    'github_url' => $this->githubUrl,
                    'live_demo_url' => $this->liveDemoUrl,
                    'notes' => $this->notes,
                ]);

                $assignmentService = app(AssignmentService::class);
                $assignmentService->submitAssignment($this->submission->id);
            });

            session()->flash('success', 'Đã nộp bài thành công!');
            return redirect()->route('learning.roadmap.show', $this->assignment->lesson->roadmap_id);
        } catch (\Exception $e) {
            session()->flash('error', 'Có lỗi xảy ra: ' . $e->getMessage());
            $this->isSubmitting = false;
        }
    }

    public function removeFile($attachmentId)
    {
        $assignmentService = app(AssignmentService::class);
        
        if ($assignmentService->deleteAttachment($attachmentId)) {
            $this->existingAttachments = $this->submission->fresh()->attachments;
            session()->flash('success', 'Đã xóa file');
        }
    }

    public function getDeadlineStatus()
    {
        if (!$this->submission->deadline) {
            return ['status' => 'none', 'text' => 'Không có deadline', 'class' => 'text-gray-500'];
        }

        $now = now();
        $deadline = $this->submission->deadline;
        $hoursRemaining = $now->diffInHours($deadline, false);

        if ($hoursRemaining < 0) {
            return ['status' => 'overdue', 'text' => 'Đã quá hạn', 'class' => 'text-red-600'];
        } elseif ($hoursRemaining <= 6) {
            return ['status' => 'urgent', 'text' => 'Còn ' . $hoursRemaining . ' giờ', 'class' => 'text-red-500'];
        } elseif ($hoursRemaining <= 24) {
            return ['status' => 'soon', 'text' => 'Còn ' . $hoursRemaining . ' giờ', 'class' => 'text-orange-500'];
        } else {
            $daysRemaining = floor($hoursRemaining / 24);
            return ['status' => 'ok', 'text' => 'Còn ' . $daysRemaining . ' ngày', 'class' => 'text-green-600'];
        }
    }

    public function getSubmissionTypeText()
    {
        return match($this->assignment->submission_type) {
            'file' => 'Chỉ upload file',
            'github' => 'Chỉ GitHub repository',
            'both' => 'File và GitHub',
            default => 'Không xác định'
        };
    }

    public function getAllowedFileTypesText()
    {
        if (!$this->assignment->allowed_file_types) {
            return 'Tất cả các loại file';
        }

        return strtoupper(str_replace(',', ', ', $this->assignment->allowed_file_types));
    }

    public function render()
    {
        return view('learning::livewire.student.assignment-submission-form');
    }
}
