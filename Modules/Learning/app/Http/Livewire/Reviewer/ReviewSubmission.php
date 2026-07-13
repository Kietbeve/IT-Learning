<?php

namespace Modules\Learning\Http\Livewire\Reviewer;

use Livewire\Component;
use Modules\Learning\Models\ProjectStepSubmission;
use Modules\Learning\Services\ProjectReviewService;
use Illuminate\Support\Facades\Auth;
use WireUi\Traits\WireUiActions;

class ReviewSubmission extends Component
{
    use WireUiActions;

    public $submission;
    public $step;
    public $project;
    public $student;
    
    // Review form
    public $decision = 'approved'; // approved or rejected
    public $feedback = '';
    public $review_notes = '';
    
    // UI state
    public $showReviewModal = false;
    public $isSubmitting = false;
    
    // Final Grading
    public $projectScore;
    public $projectFeedback = '';
    
    protected $reviewService;

    protected $rules = [
        'decision' => 'required|in:approved,rejected',
        'feedback' => 'required_if:decision,rejected|nullable|string|min:10',
        'review_notes' => 'nullable|string',
    ];

    protected $messages = [
        'feedback.required_if' => 'Vui lòng nhập feedback khi từ chối bài nộp',
        'feedback.min' => 'Feedback phải có ít nhất 10 ký tự',
    ];

    public function boot(ProjectReviewService $reviewService)
    {
        $this->reviewService = $reviewService;
    }

    public function mount($submissionId)
    {
        $this->loadSubmissionData($submissionId);
    }

    public function loadSubmission($submissionId)
    {
        $this->loadSubmissionData($submissionId);
    }

    protected function loadSubmissionData($submissionId)
    {
        $this->submission = ProjectStepSubmission::with([
            'step',
            'user',
            'projectSubmission.project',
            'reviews.reviewer'
        ])->findOrFail($submissionId);

        $this->step = $this->submission->step;
        $this->project = $this->submission->projectSubmission->project;
        $this->student = $this->submission->user;

        // Kiểm tra quyền review
        if (!$this->canReview()) {
            abort(403, 'Bạn không có quyền chấm bài này');
        }

        // Tự động chuyển status sang under_review nếu đang submitted
        if ($this->submission->status === 'submitted') {
            $this->submission->update(['status' => 'under_review']);
        }
    }

    public function canReview(): bool
    {
        $user = Auth::user();
        
        // Admin có thể review tất cả
        if ($user->hasRole('admin')) {
            return true;
        }
        
        // CTV phải được assign cho project này
        return \Modules\Learning\Models\ProjectReviewer::isReviewerForProject(
            $this->project->id,
            $user->id
        );
    }

    public function openReviewModal($decision = 'approved')
    {
        $this->resetReviewForm();
        $this->decision = $decision;
        $this->showReviewModal = true;
    }

    public function submitReview($redirect = true)
    {
        \Log::info('submitReview called', ['decision' => $this->decision, 'feedback' => $this->feedback, 'isSubmitting' => $this->isSubmitting]);
        $redirect = is_bool($redirect) ? $redirect : true;

        $this->validate();
        \Log::info('Validation passed');

        if ($this->isSubmitting) {
            \Log::info('isSubmitting is true, returning early');
            return;
        }

        $this->isSubmitting = true;

        try {
            \Log::info('Starting review transaction');
            $reviewerId = Auth::id();

            if ($this->decision === 'approved') {
                $this->reviewService->approveSubmission(
                    $this->submission->id,
                    $reviewerId,
                    $this->feedback
                );
                
                $this->notification()->success('Thành công', 'Bạn đã duyệt bài nộp này.');
                session()->flash('success', 'Đã duyệt bài nộp. Học viên có thể tiếp tục bước tiếp theo.');
            } else {
                $this->reviewService->rejectSubmission(
                    $this->submission->id,
                    $reviewerId,
                    $this->feedback
                );
                
                $this->notification()->warning('Đã từ chối', 'Bạn vừa mới từ chối bài nộp này.');
                session()->flash('warning', 'Đã từ chối bài nộp. Học viên cần làm lại bước này.');
            }

            // Reload submission
            $this->submission->refresh();
            $this->closeReviewModal();

            if ($redirect) {
                // Redirect back to project pending list
                $routeName = Auth::user()->hasRole('admin') 
                    ? 'admin.reviewer.project.submissions' 
                    : 'contributor.reviewer.project.submissions';
                
                return redirect()->route($routeName, ['projectId' => $this->project->id])
                    ->with('success', 'Đã review thành công');
            }

        } catch (\Exception $e) {
            session()->flash('error', 'Lỗi: ' . $e->getMessage());
        } finally {
            $this->isSubmitting = false;
        }
    }

    public function quickApprove()
    {
        $this->decision = 'approved';
        $this->feedback = 'Bài làm tốt, đạt yêu cầu.';
        $this->submitReview(false);
    }

    public function closeReviewModal()
    {
        $this->showReviewModal = false;
        $this->resetReviewForm();
    }

    protected function resetReviewForm()
    {
        $this->decision = 'approved';
        $this->feedback = '';
        $this->review_notes = '';
        $this->resetValidation();
    }

    public function downloadFile()
    {
        if (!$this->submission->hasFile()) {
            session()->flash('error', 'Không có file để tải');
            return;
        }

        try {
            $filePath = storage_path('app/public/' . $this->submission->file_path);
            
            if (!file_exists($filePath)) {
                session()->flash('error', 'File không tồn tại');
                return;
            }

            return response()->download($filePath, $this->submission->file_name);
            
        } catch (\Exception $e) {
            session()->flash('error', 'Lỗi tải file: ' . $e->getMessage());
        }
    }

    public function viewPreviousSubmissions()
    {
        // Load all submissions for this step
        return redirect()->route('reviewer.submissions.history', [
            'projectSubmissionId' => $this->submission->project_submission_id,
            'stepId' => $this->step->id,
        ]);
    }

    public function getReviewHistoryProperty()
    {
        return $this->submission->reviews()
            ->with('reviewer')
            ->orderBy('reviewed_at', 'desc')
            ->get();
    }

    public function getAllSubmissionsForStepProperty()
    {
        return \Modules\Learning\Models\ProjectStepSubmission::where('step_id', $this->step->id)
            ->where('user_id', $this->student->id)
            ->where('project_submission_id', $this->submission->project_submission_id)
            ->with('reviewer')
            ->orderBy('submission_number', 'desc')
            ->get();
    }

    public function getStudentProgressProperty()
    {
        $projectSubmission = $this->submission->projectSubmission;
        
        $allSteps = \Modules\Learning\Models\ProjectSubmissionStep::where('project_id', $this->project->id)
            ->where('is_active', true)
            ->ordered()
            ->get();

        $progress = [];
        
        foreach ($allSteps as $step) {
            $stepSubmission = $step->getCurrentSubmission(
                $this->student->id,
                $projectSubmission->id
            );
            
            $progress[] = [
                'step' => $step,
                'submission' => $stepSubmission,
                'status' => $stepSubmission ? $stepSubmission->status : 'not_started',
            ];
        }

        return $progress;
    }

    public function getIsProjectCompletedProperty()
    {
        $progress = $this->studentProgress;
        foreach ($progress as $item) {
            if ($item['status'] !== 'approved') {
                return false;
            }
        }
        
        // If all steps are approved, verify if the project hasn't been graded yet
        // OR it's just completed but waiting for grade
        return true; // We show it if all steps approved
    }

    public function submitFinalGrade()
    {
        $this->validate([
            'projectScore' => 'required|numeric|min:0|max:10',
            'projectFeedback' => 'nullable|string',
        ]);

        $projectSubmission = $this->submission->projectSubmission;
        
        $projectSubmission->update([
            'score' => $this->projectScore,
            'feedback' => $this->projectFeedback,
            'status' => 'passed',
            'reviewed_by' => Auth::id(),
            'reviewed_at' => now(),
        ]);

        $this->notification()->success('Thành công', 'Đã lưu điểm tổng kết dự án');
        
        $routeName = Auth::user()->hasRole('admin') 
            ? 'admin.reviewer.project.submissions' 
            : 'contributor.reviewer.project.submissions';
        
        return redirect()->route($routeName, ['projectId' => $this->project->id])
            ->with('success', 'Đã lưu điểm tổng kết thành công!');
    }

    public function render()
    {
        return view('learning::livewire.reviewer.review-submission', [
            'reviewHistory' => $this->reviewHistory,
            'allSubmissions' => $this->allSubmissionsForStep,
            'studentProgress' => $this->studentProgress,
            'isProjectCompleted' => $this->isProjectCompleted,
        ])->layout('layouts.user');
    }
}
