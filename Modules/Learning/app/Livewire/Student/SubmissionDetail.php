<?php

namespace Modules\Learning\Livewire\Student;

use Livewire\Component;
use Modules\Learning\Models\ProjectSubmission;
use Modules\Learning\Models\AssignmentSubmission;
use Illuminate\Support\Facades\Auth;

class SubmissionDetail extends Component
{
    public $submissionType; // 'project' or 'assignment'
    public $submissionId;
    public $submission;

    public function mount($type, $id)
    {
        $this->submissionType = $type;
        $this->submissionId = $id;

        if ($type === 'project') {
            $this->submission = ProjectSubmission::with([
                'project.rubricCriteria',
                'reviewer',
                'rubricScores.criteria'
            ])
            ->where('user_id', Auth::id())
            ->findOrFail($id);
        } elseif ($type === 'assignment') {
            $this->submission = AssignmentSubmission::with([
                'assignment.rubrics',
                'grader',
                'rubricItems.rubric',
                'attachments'
            ])
            ->where('user_id', Auth::id())
            ->findOrFail($id);
        } else {
            abort(404);
        }
    }

    public function downloadAttachment($attachmentId)
    {
        if ($this->submissionType !== 'assignment') {
            return;
        }

        $attachment = $this->submission->attachments()->findOrFail($attachmentId);
        return response()->download(storage_path('app/' . $attachment->file_path));
    }

    public function render()
    {
        return view('learning::livewire.student.submission-detail');
    }
}
