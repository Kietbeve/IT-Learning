<?php

namespace Modules\Learning\Http\Livewire\Admin;

use Livewire\Component;
use Livewire\WithPagination;
use Modules\Learning\Models\Project;
use Modules\Learning\Models\ProjectReviewer;
use Modules\Learning\Services\ProjectReviewService;
use Modules\Auth\Models\User;
use Illuminate\Support\Facades\Auth;

/**
 * ManageProjectReviewers Component
 * 
 * Admin interface để phân công CTV/Reviewer cho projects.
 * Cho phép:
 * - Xem tất cả projects và reviewers được assign
 * - Thêm reviewer mới cho project
 * - Xóa reviewer khỏi project
 * - Cấp quyền chấm điểm cuối cùng (can_final_grade)
 */
class ManageProjectReviewers extends Component
{
    use WithPagination;

    // Modal state
    public $showAssignModal = false;
    public $selectedProject = null;
    public $selectedProjectId = null;
    
    // Form fields
    public $selectedUserId = null;
    public $canFinalGrade = false;
    
    // Filters
    public $searchProject = '';
    public $filterHasReviewers = 'all'; // all, yes, no

    protected $reviewService;

    protected $rules = [
        'selectedUserId' => 'required|exists:users,id',
        'canFinalGrade' => 'boolean',
    ];

    protected $messages = [
        'selectedUserId.required' => 'Vui lòng chọn reviewer',
        'selectedUserId.exists' => 'Người dùng không tồn tại',
    ];

    public function boot(ProjectReviewService $reviewService)
    {
        $this->reviewService = $reviewService;
    }

    public function updatingSearchProject()
    {
        $this->resetPage();
    }

    public function updatingFilterHasReviewers()
    {
        $this->resetPage();
    }

    /**
     * Lấy danh sách projects với reviewers
     */
    public function getProjectsWithReviewersProperty()
    {
        $query = Project::with(['roadmap', 'reviewers.user'])
            ->orderBy('created_at', 'desc');

        // Apply search
        if (!empty($this->searchProject)) {
            $query->where(function($q) {
                $q->where('title', 'like', '%' . $this->searchProject . '%')
                  ->orWhere('description', 'like', '%' . $this->searchProject . '%');
            });
        }

        // Apply filter
        if ($this->filterHasReviewers === 'yes') {
            $query->has('reviewers');
        } elseif ($this->filterHasReviewers === 'no') {
            $query->doesntHave('reviewers');
        }

        return $query->paginate(10);
    }

    /**
     * Lấy danh sách Contributors để assign
     */
    public function getAvailableReviewersProperty()
    {
        if (!$this->selectedProjectId) {
            return collect();
        }

        // Lấy tất cả users có role Admin hoặc Contributor
        $allReviewers = User::whereHas('roles', function($q) {
            $q->whereIn('name', ['Admin', 'Contributor']);
        })->get();

        // Lọc ra những người chưa được assign cho project này
        $assignedUserIds = ProjectReviewer::where('project_id', $this->selectedProjectId)
            ->pluck('user_id')
            ->toArray();

        return $allReviewers->reject(function($user) use ($assignedUserIds) {
            return in_array($user->id, $assignedUserIds);
        });
    }

    /**
     * Mở modal assign reviewer
     */
    public function openAssignModal($projectId)
    {
        $this->selectedProjectId = $projectId;
        $this->selectedProject = Project::findOrFail($projectId);
        $this->resetForm();
        $this->showAssignModal = true;
    }

    /**
     * Assign reviewer cho project
     */
    public function assignReviewer()
    {
        $this->validate();

        try {
            $this->reviewService->assignReviewer(
                $this->selectedProjectId,
                $this->selectedUserId,
                Auth::id(),
                $this->canFinalGrade
            );

            session()->flash('success', 'Đã phân công reviewer thành công');
            
            $this->closeAssignModal();
            $this->dispatch('reviewer-assigned');

        } catch (\Exception $e) {
            session()->flash('error', 'Lỗi: ' . $e->getMessage());
        }
    }

    /**
     * Remove reviewer khỏi project
     */
    public function removeReviewer($projectId, $userId)
    {
        try {
            $this->reviewService->removeReviewer($projectId, $userId);
            
            session()->flash('success', 'Đã xóa reviewer thành công');
            
        } catch (\Exception $e) {
            session()->flash('error', 'Lỗi: ' . $e->getMessage());
        }
    }

    /**
     * Toggle quyền final grade
     */
    public function toggleFinalGrade($reviewerId)
    {
        try {
            $reviewer = ProjectReviewer::findOrFail($reviewerId);
            
            if ($reviewer->can_final_grade) {
                $reviewer->revokeFinalGradePermission();
                session()->flash('success', 'Đã thu hồi quyền chấm điểm cuối');
            } else {
                $reviewer->grantFinalGradePermission();
                session()->flash('success', 'Đã cấp quyền chấm điểm cuối');
            }
            
        } catch (\Exception $e) {
            session()->flash('error', 'Lỗi: ' . $e->getMessage());
        }
    }

    /**
     * Toggle active status
     */
    public function toggleActive($reviewerId)
    {
        try {
            $reviewer = ProjectReviewer::findOrFail($reviewerId);
            
            if ($reviewer->is_active) {
                $reviewer->deactivate();
                session()->flash('success', 'Đã vô hiệu hóa reviewer');
            } else {
                $reviewer->activate();
                session()->flash('success', 'Đã kích hoạt reviewer');
            }
            
        } catch (\Exception $e) {
            session()->flash('error', 'Lỗi: ' . $e->getMessage());
        }
    }

    /**
     * Close modal và reset form
     */
    public function closeAssignModal()
    {
        $this->showAssignModal = false;
        $this->resetForm();
    }

    protected function resetForm()
    {
        $this->selectedUserId = null;
        $this->canFinalGrade = false;
        $this->resetValidation();
    }

    /**
     * Clear filters
     */
    public function clearFilters()
    {
        $this->searchProject = '';
        $this->filterHasReviewers = 'all';
        $this->resetPage();
    }

    /**
     * Lấy statistics tổng quan
     */
    public function getOverallStatsProperty()
    {
        $totalProjects = Project::count();
        $projectsWithReviewers = Project::has('reviewers')->count();
        $projectsWithoutReviewers = $totalProjects - $projectsWithReviewers;
        $totalAssignments = ProjectReviewer::where('is_active', true)->count();

        return [
            'total_projects' => $totalProjects,
            'with_reviewers' => $projectsWithReviewers,
            'without_reviewers' => $projectsWithoutReviewers,
            'total_assignments' => $totalAssignments,
        ];
    }

    public function render()
    {
        return view('learning::livewire.admin.manage-project-reviewers', [
            'projects' => $this->projectsWithReviewers,
            'availableReviewers' => $this->availableReviewers,
            'overallStats' => $this->overallStats,
        ])->layout('layouts.user');
    }
}
