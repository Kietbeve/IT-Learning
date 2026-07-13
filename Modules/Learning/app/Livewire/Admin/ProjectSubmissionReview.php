<?php

namespace Modules\Learning\Livewire\Admin;

use Livewire\Component;
use Modules\Learning\Models\ProjectSubmission;
use Modules\Learning\Models\ProjectRubricCriteria;
use Modules\Learning\Models\SubmissionRubricScore;
use Modules\Learning\Services\ProjectSubmissionService;
use Illuminate\Support\Facades\Auth;
use Illuminate\Support\Facades\DB;

class ProjectSubmissionReview extends Component
{
    public ProjectSubmission $submission;
    public string $status = '';
    public string $feedback = '';
    public ?float $score = null;
    public array $gradingNotes = [];
    public array $rubricScores = []; // ['criteria_id' => ['score' => X, 'notes' => 'Y']]
    public bool $showReviewForm = false;
    public bool $useRubric = false;

    protected $rules = [
        'status' => 'required|in:in_review,passed,failed',
        'feedback' => 'nullable|string|max:2000',
        'score' => 'nullable|numeric|min:0',
        'gradingNotes' => 'nullable|array',
        'rubricScores.*.score' => 'nullable|numeric|min:0',
        'rubricScores.*.notes' => 'nullable|string|max:500',
    ];

    public function mount($submissionId)
    {
        $this->submission = ProjectSubmission::with([
            'project.rubricCriteria',
            'user',
            'reviewer',
            'enrollment.roadmap',
            'rubricScores.criteria'
        ])->findOrFail($submissionId);
        
        $this->status = $this->submission->status;
        $this->feedback = $this->submission->feedback ?? '';
        $this->score = $this->submission->score;
        $this->gradingNotes = $this->submission->grading_notes ?? [];
        
        // Check if project has rubrics
        $this->useRubric = $this->submission->project->rubricCriteria->isNotEmpty();
        
        // Load existing rubric scores
        if ($this->useRubric) {
            foreach ($this->submission->rubricScores as $rubricScore) {
                $this->rubricScores[$rubricScore->rubric_criteria_id] = [
                    'score' => $rubricScore->score,
                    'notes' => $rubricScore->notes,
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
        foreach ($this->rubricScores as $criteriaId => $data) {
            if (isset($data['score']) && is_numeric($data['score'])) {
                $total += (float) $data['score'];
            }
        }

        $this->score = $total;
    }

    public function submitReview()
    {
        $this->validate();

        $submissionService = app(ProjectSubmissionService::class);

        try {
            DB::transaction(function () use ($submissionService) {
                // Save main review
                $submissionService->reviewSubmission(
                    submissionId: $this->submission->id,
                    reviewerId: Auth::id(),
                    status: $this->status,
                    feedback: $this->feedback,
                    score: $this->score,
                    gradingNotes: $this->gradingNotes
                );

                // Save rubric scores if using rubric
                if ($this->useRubric) {
                    $this->saveRubricScores();
                }
            });

            session()->flash('success', 'Đã đánh giá project thành công!');
            
            $this->submission->refresh();
            $this->showReviewForm = false;

        } catch (\Exception $e) {
            session()->flash('error', $e->getMessage());
        }
    }

    protected function saveRubricScores()
    {
        foreach ($this->rubricScores as $criteriaId => $data) {
            $criteria = $this->submission->project->rubricCriteria->find($criteriaId);
            
            if (!$criteria) {
                continue;
            }

            SubmissionRubricScore::updateOrCreate(
                [
                    'submission_id' => $this->submission->id,
                    'rubric_criteria_id' => $criteriaId,
                ],
                [
                    'score' => $data['score'] ?? 0,
                    'notes' => $data['notes'] ?? null,
                ]
            );
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
        // Detect layout based on current route
        $layout = request()->is('admin/*') ? 'layouts.admin' : 'layouts.contributor';
        
        return view('learning::livewire.admin.project-submission-review')->layout($layout);
    }
}
