<?php

namespace Modules\Learning\Http\Livewire\Reviewer;

use Livewire\Component;
use Livewire\WithPagination;
use Modules\Learning\Models\Project;
use Modules\Learning\Models\ProjectSubmission;
use Modules\Learning\Models\ProjectSubmissionStep;
use Modules\Learning\Models\ProjectStepSubmission;
use Modules\Learning\Models\ProjectReviewer;
use Illuminate\Support\Facades\Auth;

/**
 * ProjectSubmissionsList Component
 * 
 * Hiển thị danh sách tất cả học viên đã nộp bài cho project này.
 * Reviewer có thể xem tiến độ từng học viên và click vào để review từng bước.
 */
class ProjectSubmissionsList extends Component
{
    use WithPagination;

    public $project;
    public $projectId;
    public $steps = [];
    
    // Filters
    public $searchStudent = '';
    public $filterStatus = 'all'; // all, pending, completed, in_progress

    protected $queryString = [
        'searchStudent' => ['except' => ''],
        'filterStatus' => ['except' => 'all'],
    ];

    public function mount($projectId)
    {
        $this->projectId = $projectId;
        $this->project = Project::with(['roadmap', 'section'])->findOrFail($projectId);
        
        // Kiểm tra quyền access
        if (!$this->canAccessProject()) {
            abort(403, 'Bạn không có quyền truy cập project này');
        }

        // Load tất cả steps của project
        $this->steps = ProjectSubmissionStep::where('project_id', $this->projectId)
            ->where('is_active', true)
            ->ordered()
            ->get();
    }

    public function canAccessProject(): bool
    {
        $user = Auth::user();
        
        // Admin có thể access tất cả
        if ($user->hasRole('admin')) {
            return true;
        }
        
        // CTV phải được assign
        return ProjectReviewer::isReviewerForProject($this->projectId, $user->id);
    }

    public function updatingSearchStudent()
    {
        $this->resetPage();
    }

    public function updatingFilterStatus()
    {
        $this->resetPage();
    }

    /**
     * Lấy danh sách submissions với thông tin chi tiết
     */
    public function getSubmissionsWithProgressProperty()
    {
        $query = ProjectSubmission::where('project_id', $this->projectId)
            ->with(['user']);

        // Apply search
        if (!empty($this->searchStudent)) {
            $query->whereHas('user', function($q) {
                $q->where('name', 'like', '%' . $this->searchStudent . '%')
                  ->orWhere('email', 'like', '%' . $this->searchStudent . '%');
            });
        }

        // Apply status filter
        if ($this->filterStatus !== 'all') {
            if ($this->filterStatus === 'pending') {
                $query->where('status', 'pending');
            } elseif ($this->filterStatus === 'completed') {
                $query->where('progress_percent', 100);
            } elseif ($this->filterStatus === 'in_progress') {
                $query->where('status', 'pending')
                      ->where('progress_percent', '>', 0)
                      ->where('progress_percent', '<', 100);
            }
        }

        $submissions = $query->orderBy('updated_at', 'desc')->paginate(20);

        // Transform data với progress info
        return $submissions->through(function ($submission) {
            $stepProgress = [];
            
            foreach ($this->steps as $step) {
                $stepSubmission = ProjectStepSubmission::where('step_id', $step->id)
                    ->where('user_id', $submission->user_id)
                    ->where('project_submission_id', $submission->id)
                    ->where('is_current', true)
                    ->first();
                
                $stepProgress[] = [
                    'step' => $step,
                    'submission' => $stepSubmission,
                    'status' => $stepSubmission ? $stepSubmission->status : 'not_started',
                ];
            }

            return [
                'project_submission' => $submission,
                'student' => $submission->user,
                'step_progress' => $stepProgress,
                'pending_steps' => $this->countPendingSteps($stepProgress),
            ];
        });
    }

    protected function countPendingSteps($stepProgress): int
    {
        return collect($stepProgress)->filter(function($item) {
            return in_array($item['status'], ['submitted', 'under_review']);
        })->count();
    }

    /**
     * View chi tiết submission của một step
     */
    public function reviewStepSubmission($stepSubmissionId)
    {
        $routeName = Auth::user()->hasRole('admin') 
            ? 'admin.reviewer.submissions.review' 
            : 'contributor.reviewer.submissions.review';
            
        return redirect()->route($routeName, ['submissionId' => $stepSubmissionId]);
    }

    /**
     * Lấy statistics cho project này
     */
    public function getProjectStatsProperty()
    {
        $totalSubmissions = ProjectSubmission::where('project_id', $this->projectId)->count();
        
        $pendingSubmissions = ProjectStepSubmission::whereHas('projectSubmission', function($q) {
                $q->where('project_id', $this->projectId);
            })
            ->whereIn('status', ['submitted', 'under_review'])
            ->where('is_current', true)
            ->count();
        
        $completedSubmissions = ProjectSubmission::where('project_id', $this->projectId)
            ->where('status', 'completed')
            ->count();

        $inProgressSubmissions = ProjectSubmission::where('project_id', $this->projectId)
            ->whereIn('status', ['pending', 'in_progress'])
            ->count();

        return [
            'total' => $totalSubmissions,
            'pending' => $pendingSubmissions,
            'completed' => $completedSubmissions,
            'in_progress' => $inProgressSubmissions,
        ];
    }

    /**
     * Lấy info reviewer assignment cho project này
     */
    public function getReviewerAssignmentProperty()
    {
        return ProjectReviewer::where('project_id', $this->projectId)
            ->where('user_id', Auth::id())
            ->first();
    }

    public function clearFilters()
    {
        $this->searchStudent = '';
        $this->filterStatus = 'all';
        $this->resetPage();
    }

    public function render()
    {
        return view('learning::livewire.reviewer.project-submissions-list', [
            'submissionsData' => $this->submissionsWithProgress,
            'projectStats' => $this->projectStats,
            'reviewerAssignment' => $this->reviewerAssignment,
        ])->layout('layouts.user');
    }
}
