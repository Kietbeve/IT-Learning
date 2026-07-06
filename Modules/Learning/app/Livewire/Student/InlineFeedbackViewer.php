<?php

namespace Modules\Learning\Livewire\Student;

use Livewire\Component;
use Modules\Learning\Models\AssignmentSubmission;
use Illuminate\Support\Facades\Auth;

class InlineFeedbackViewer extends Component
{
    public $submissionId;
    public $submission;
    public $feedbacks = [];
    public $groupedFeedbacks = [];
    public $selectedFile = null;
    public $showResolved = false;

    public function mount($submissionId)
    {
        $this->submissionId = $submissionId;
        $this->loadSubmission();
        $this->loadFeedbacks();
    }

    public function loadSubmission()
    {
        $this->submission = AssignmentSubmission::with([
            'assignment',
            'student',
            'grader',
            'attachments'
        ])->findOrFail($this->submissionId);
    }

    public function loadFeedbacks()
    {
        $query = $this->submission->feedback();

        if (!$this->showResolved) {
            $query->where('is_resolved', false);
        }

        $this->feedbacks = $query->with(['grader', 'rubricItem'])
            ->orderBy('created_at', 'desc')
            ->get();

        $this->groupFeedbacksByType();
    }

    protected function groupFeedbacksByType()
    {
        $this->groupedFeedbacks = [
            'general' => [],
            'criterion' => [],
            'inline' => [],
        ];

        foreach ($this->feedbacks as $feedback) {
            $this->groupedFeedbacks[$feedback->feedback_type][] = $feedback;
        }

        // Group inline feedbacks by file
        $inlineByFile = [];
        foreach ($this->groupedFeedbacks['inline'] as $feedback) {
            $file = $feedback->reference_file ?? 'Unknown';
            if (!isset($inlineByFile[$file])) {
                $inlineByFile[$file] = [];
            }
            $inlineByFile[$file][] = $feedback;
        }
        $this->groupedFeedbacks['inline_by_file'] = $inlineByFile;
    }

    public function toggleResolved()
    {
        $this->showResolved = !$this->showResolved;
        $this->loadFeedbacks();
    }

    public function selectFile($fileName)
    {
        $this->selectedFile = $fileName;
    }

    public function markFeedbackResolved($feedbackId)
    {
        $feedback = $this->feedbacks->find($feedbackId);
        
        if ($feedback) {
            $feedback->markAsResolved();
            $this->loadFeedbacks();
            session()->flash('success', 'Đã đánh dấu feedback đã xử lý');
        }
    }

    public function getFeedbackTypeIcon($type)
    {
        return match($type) {
            'general' => '💬',
            'criterion' => '📋',
            'inline' => '📍',
            default => '💬'
        };
    }

    public function getFeedbackTypeText($type)
    {
        return match($type) {
            'general' => 'Nhận xét chung',
            'criterion' => 'Đánh giá tiêu chí',
            'inline' => 'Nhận xét inline',
            default => 'Nhận xét'
        };
    }

    public function getFeedbackPriorityClass($score)
    {
        if ($score === null) {
            return 'border-gray-200';
        }

        $rubricItem = null;
        // Logic to determine priority based on score vs max score
        return 'border-blue-200';
    }

    public function getInlineFeedbacksForFile($fileName)
    {
        return $this->groupedFeedbacks['inline_by_file'][$fileName] ?? [];
    }

    public function hasInlineFeedbacksForLine($fileName, $lineNumber)
    {
        $fileFeedbacks = $this->getInlineFeedbacksForFile($fileName);
        
        foreach ($fileFeedbacks as $feedback) {
            if ($feedback->reference_line == $lineNumber) {
                return true;
            }
        }
        
        return false;
    }

    public function getInlineFeedbacksForLine($fileName, $lineNumber)
    {
        $fileFeedbacks = $this->getInlineFeedbacksForFile($fileName);
        $result = [];
        
        foreach ($fileFeedbacks as $feedback) {
            if ($feedback->reference_line == $lineNumber) {
                $result[] = $feedback;
            }
        }
        
        return $result;
    }

    public function getTotalFeedbackCount()
    {
        return $this->feedbacks->count();
    }

    public function getUnresolvedFeedbackCount()
    {
        return $this->feedbacks->where('is_resolved', false)->count();
    }

    public function render()
    {
        return view('learning::livewire.student.inline-feedback-viewer');
    }
}
