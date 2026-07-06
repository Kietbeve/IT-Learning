<?php

namespace Modules\Learning\Livewire\Admin;

use Livewire\Component;
use Modules\Learning\Models\ProjectSubmission;
use Modules\Learning\Services\ProjectSubmissionService;
use Illuminate\Support\Facades\Auth;

class ProjectSubmissionReview extends Component
{
    public ProjectSubmission $submission;
    public string $status = '';
    public string $feedback = '';
    public ?float $score = null;
    public array $gradingNotes = [];
    public bool $showReviewForm = false;

    protected $rules = [
        'status' => 'required|in:in_review,passed,failed',
        'feedback' => 'nullable|string|max:2000',
        'score' => 'nullable|numeric|min:0',
        'gradingNotes' => 'nullable|array',
    ];

    public function mount($submissionId)
    {
        $this->submission = ProjectSubmission::with(['project', 'user', 'reviewer', 'enrollment.roadmap'])
            ->findOrFail($submissionId);
        
        $this->status = $this->submission->status;
        $this->feedback = $this->submission->feedback ?? '';
        $this->score = $this->submission->score;
        $this->gradingNotes = $this->submission->grading_notes ?? [];
    }

    public function toggleReviewForm()
    {
        $this->showReviewForm = !$this->showReviewForm;
    }

    public function submitReview()
    {
        $this->validate();

        $submissionService = app(ProjectSubmissionService::class);

        try {
            $submissionService->reviewSubmission(
                submissionId: $this->submission->id,
                reviewerId: Auth::id(),
                status: $this->status,
                feedback: $this->feedback,
                score: $this->score,
                gradingNotes: $this->gradingNotes
            );

            session()->flash('success', 'Đã đánh giá project thành công!');
            
            $this->submission->refresh();
            $this->showReviewForm = false;

        } catch (\Exception $e) {
            session()->flash('error', $e->getMessage());
        }
    }

    public function markAsInReview()
    {
        $submissionService = app(ProjectSubmissionService::class);

        try {
            $submissionService->reviewSubmission(
                submissionId: $this->submission->id,
                reviewerId: Auth::id(),
                status: 'in_review',
                feedback: null,
                score: null,
                gradingNotes: null
            );

            session()->flash('success', 'Đã đánh dấu đang review!');
            $this->submission->refresh();

        } catch (\Exception $e) {
            session()->flash('error', $e->getMessage());
        }
    }

    public function render()
    {
        return view('learning::livewire.admin.project-submission-review');
    }
}
