<?php

namespace Modules\Learning\Livewire\Admin;

use Livewire\Component;
use Modules\Learning\Models\AssignmentSubmission;
use Modules\Learning\Models\AssignmentRubric;
use Modules\Learning\Models\AssignmentRubricItem;
use Modules\Learning\Services\AssignmentService;
use Illuminate\Support\Facades\Auth;
use Illuminate\Support\Facades\DB;

class AssignmentSubmissionReview extends Component
{
    public AssignmentSubmission $submission;
    public string $status = '';
    public string $feedback = '';
    public ?int $score = null;
    public array $rubricScores = []; // ['rubric_item_id' => ['points' => X, 'feedback' => 'Y']]
    public bool $showReviewForm = false;
    public bool $useRubric = false;

    protected $rules = [
        'status' => 'required|in:in_review,graded,needs_revision',
        'feedback' => 'nullable|string|max:5000',
        'score' => 'required|integer|min:0',
        'rubricScores.*.points' => 'nullable|integer|min:0',
        'rubricScores.*.feedback' => 'nullable|string|max:500',
    ];

    protected $messages = [
        'score.required' => 'Vui lòng nhập điểm',
        'score.integer' => 'Điểm phải là số nguyên',
        'score.min' => 'Điểm không được âm',
    ];

    public function mount($submissionId)
    {
        $this->submission = AssignmentSubmission::with([
            'assignment.lesson.roadmap',
            'assignment.rubrics',
            'student',
            'grader',
            'attachments',
            'rubricItems'
        ])->findOrFail($submissionId);
        
        $this->status = $this->submission->status;
        $this->feedback = $this->submission->feedback ?? '';
        $this->score = $this->submission->score;
        
        // Check if assignment has rubrics
        $this->useRubric = $this->submission->assignment->has_rubric && $this->submission->assignment->rubrics->isNotEmpty();
        
        // Load existing rubric scores
        if ($this->useRubric) {
            foreach ($this->submission->rubricItems as $item) {
                $this->rubricScores[$item->rubric_id] = [
                    'points' => $item->points_earned,
                    'feedback' => $item->feedback,
                    'is_checked' => $item->is_checked,
                ];
            }
        }
    }

    public function toggleReviewForm()
    {
        $this->showReviewForm = !$this->showReviewForm;
    }

    public function calculateTotalFromRubric()
    {
        if (!$this->useRubric) {
            return;
        }

        $total = 0;
        foreach ($this->rubricScores as $rubricId => $data) {
            if (isset($data['points']) && is_numeric($data['points'])) {
                $total += (int) $data['points'];
            }
        }

        $this->score = $total;
    }

    public function submitReview()
    {
        // Validate score against max_score
        $this->rules['score'] = 'required|integer|min:0|max:' . $this->submission->assignment->max_score;
        
        $this->validate();

        $assignmentService = app(AssignmentService::class);

        try {
            DB::transaction(function () use ($assignmentService) {
                // Grade the submission
                $assignmentService->gradeSubmission(
                    submissionId: $this->submission->id,
                    graderId: Auth::id(),
                    score: $this->score,
                    feedback: $this->feedback,
                    status: $this->status
                );

                // Save rubric scores if using rubric
                if ($this->useRubric) {
                    $this->saveRubricScores();
                }
            });

            session()->flash('success', 'Đã chấm bài thành công!');
            
            $this->submission->refresh();
            $this->showReviewForm = false;

        } catch (\Exception $e) {
            session()->flash('error', 'Lỗi: ' . $e->getMessage());
        }
    }

    protected function saveRubricScores()
    {
        foreach ($this->rubricScores as $rubricId => $data) {
            $rubric = $this->submission->assignment->rubrics->find($rubricId);
            
            if (!$rubric) {
                continue;
            }

            AssignmentRubricItem::updateOrCreate(
                [
                    'rubric_id' => $rubricId,
                    'submission_id' => $this->submission->id,
                ],
                [
                    'criterion' => $rubric->title,
                    'max_points' => $rubric->max_points,
                    'points_earned' => $data['points'] ?? 0,
                    'feedback' => $data['feedback'] ?? null,
                    'is_checked' => $data['is_checked'] ?? true,
                ]
            );
        }
    }

    public function markAsInReview()
    {
        try {
            $this->submission->update([
                'status' => 'in_review',
                'graded_by' => Auth::id(),
            ]);

            session()->flash('success', 'Đã đánh dấu đang chấm!');
            $this->submission->refresh();

        } catch (\Exception $e) {
            session()->flash('error', 'Lỗi: ' . $e->getMessage());
        }
    }

    public function downloadAttachment($attachmentId)
    {
        $attachment = $this->submission->attachments()->findOrFail($attachmentId);
        return response()->download(storage_path('app/' . $attachment->file_path));
    }

    public function render()
    {
        // Detect layout based on current route
        $layout = request()->is('admin/*') ? 'layouts.admin' : 'layouts.contributor';
        
        return view('learning::livewire.admin.assignment-submission-review')->layout($layout);
    }
}
