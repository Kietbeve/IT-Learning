<?php

namespace Modules\Learning\Http\Livewire\Reviewer;

use Livewire\Component;
use Livewire\WithPagination;
use Modules\Learning\Models\Project;
use Modules\Learning\Models\ProjectReviewer;
use Modules\Learning\Models\ProjectStepSubmission;
use Modules\Learning\Services\ProjectReviewService;
use Illuminate\Support\Facades\Auth;

/**
 * ReviewerSubmissionsList Component
 * 
 * Hiển thị danh sách tất cả projects:
 * - Projects được assign: màu xanh, có thể click vào
 * - Projects không được assign: màu xám, không thể click
 * - Hiển thị số lượng submissions đang chờ review
 */
class ReviewerSubmissionsList extends Component
{
    use WithPagination;

    public $search = '';
    public $filterStatus = 'all'; // all, assigned, not_assigned
    public $filterPending = false; // Chỉ show projects có submissions pending

    protected $queryString = [
        'search' => ['except' => ''],
        'filterStatus' => ['except' => 'all'],
        'filterPending' => ['except' => false],
    ];

    protected $reviewService;

    public function boot(ProjectReviewService $reviewService)
    {
        $this->reviewService = $reviewService;
    }

    public function mount()
    {
        // Load initial data
    }

    public function updatingSearch()
    {
        $this->resetPage();
    }

    public function updatingFilterStatus()
    {
        $this->resetPage();
    }

    public function updatingFilterPending()
    {
        $this->resetPage();
    }

    /**
     * Lấy danh sách projects với thông tin access
     */
    public function getProjectsWithAccessProperty()
    {
        $userId = Auth::id();
        $user = Auth::user();
        $isAdmin = $user->hasRole('admin');
        
        // Lấy tất cả project IDs mà user được assign
        $assignedProjectIds = ProjectReviewer::where('user_id', $userId)
            ->where('is_active', true)
            ->pluck('project_id')
            ->toArray();

        // Query projects
        $query = Project::with(['roadmap', 'submissions'])
            ->orderBy('created_at', 'desc');

        // Apply search
        if (!empty($this->search)) {
            $query->where(function($q) {
                $q->where('title', 'like', '%' . $this->search . '%')
                  ->orWhere('description', 'like', '%' . $this->search . '%');
            });
        }

        // Apply filter
        // Admin có thể thấy tất cả projects, filter theo assigned/not_assigned dựa trên ProjectReviewer
        if ($this->filterStatus === 'assigned') {
            if (!$isAdmin) {
                $query->whereIn('id', $assignedProjectIds);
            } else {
                // Admin filter "assigned" = projects có trong ProjectReviewer của admin
                $query->whereIn('id', $assignedProjectIds);
            }
        } elseif ($this->filterStatus === 'not_assigned') {
            $query->whereNotIn('id', $assignedProjectIds);
        }

        if ($this->filterPending) {
            $query->whereExists(function ($subquery) {
                $subquery->select(\Illuminate\Support\Facades\DB::raw(1))
                      ->from('project_step_submissions')
                      ->join('project_submissions', 'project_submissions.id', '=', 'project_step_submissions.project_submission_id')
                      ->whereColumn('project_submissions.project_id', 'projects.id')
                      ->whereIn('project_step_submissions.status', ['submitted', 'under_review'])
                      ->where('project_step_submissions.is_current', true);
            });
        }

        $projects = $query->paginate(12);

        // Transform data với access info
        return $projects->through(function ($project) use ($assignedProjectIds, $isAdmin) {
            $isAssigned = in_array($project->id, $assignedProjectIds);
            
            // Admin có thể access TẤT CẢ projects
            $canAccess = $isAdmin ? true : $isAssigned;
            
            $pendingCount = 0;
            if ($canAccess) {
                $pendingCount = $this->getPendingSubmissionsCount($project->id);
            }

            return [
                'project' => $project,
                'is_assigned' => $isAdmin ? true : $isAssigned,  // Admin shows as "assigned" to all projects
                'can_access' => $canAccess,  // Admin: always true, Contributor: only if assigned
                'pending_count' => $pendingCount,
                'reviewer_info' => $isAssigned ? $this->getReviewerInfo($project->id, Auth::id()) : null,
            ];
        });
    }

    /**
     * Đếm số submissions đang chờ review cho project này
     */
    protected function getPendingSubmissionsCount($projectId): int
    {
        return ProjectStepSubmission::whereHas('projectSubmission', function ($query) use ($projectId) {
                $query->where('project_id', $projectId);
            })
            ->whereIn('status', ['submitted', 'under_review'])
            ->where('is_current', true)
            ->count();
    }

    /**
     * Lấy thông tin reviewer assignment
     */
    protected function getReviewerInfo($projectId, $userId)
    {
        return ProjectReviewer::where('project_id', $projectId)
            ->where('user_id', $userId)
            ->first();
    }

    /**
     * Xem chi tiết submissions của project (chỉ nếu được assign)
     */
    public function viewProjectSubmissions($projectId)
    {
        $project = Project::findOrFail($projectId);
        
        // Check access
        if (!$this->canAccessProject($projectId)) {
            session()->flash('error', 'Bạn không có quyền truy cập project này');
            return;
        }

        $routeName = Auth::user()->hasRole('admin') 
            ? 'admin.reviewer.project.submissions' 
            : 'contributor.reviewer.project.submissions';
            
        return redirect()->route($routeName, ['projectId' => $projectId]);
    }

    /**
     * Kiểm tra user có quyền access project không
     */
    public function canAccessProject($projectId): bool
    {
        $user = Auth::user();
        
        // Admin có thể access tất cả
        if ($user->hasRole('admin')) {
            return true;
        }
        
        // CTV phải được assign
        return ProjectReviewer::isReviewerForProject($projectId, $user->id);
    }

    /**
     * Clear filters
     */
    public function clearFilters()
    {
        $this->search = '';
        $this->filterStatus = 'all';
        $this->filterPending = false;
        $this->resetPage();
    }

    /**
     * Lấy statistics cho reviewer
     */
    public function getReviewerStatsProperty()
    {
        return $this->reviewService->getReviewerStatistics(Auth::id());
    }

    /**
     * Lấy tổng số projects được assign
     */
    public function getTotalAssignedProjectsProperty()
    {
        return ProjectReviewer::where('user_id', Auth::id())
            ->where('is_active', true)
            ->count();
    }

    /**
     * Lấy tổng số submissions đang chờ review của user này
     */
    public function getTotalPendingSubmissionsProperty()
    {
        $assignedProjectIds = ProjectReviewer::where('user_id', Auth::id())
            ->where('is_active', true)
            ->pluck('project_id');

        return ProjectStepSubmission::whereHas('projectSubmission', function ($query) use ($assignedProjectIds) {
                $query->whereIn('project_id', $assignedProjectIds);
            })
            ->whereIn('status', ['submitted', 'under_review'])
            ->where('is_current', true)
            ->count();
    }

    public function render()
    {
        return view('learning::livewire.reviewer.reviewer-submissions-list', [
            'projectsData' => $this->projectsWithAccess,
            'reviewerStats' => $this->reviewerStats,
            'totalAssigned' => $this->totalAssignedProjects,
            'totalPending' => $this->totalPendingSubmissions,
        ])->layout('layouts.user');
    }
}
