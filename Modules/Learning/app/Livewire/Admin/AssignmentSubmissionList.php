<?php

namespace Modules\Learning\Livewire\Admin;

use Livewire\Component;
use Livewire\WithPagination;
use Modules\Learning\Models\AssignmentSubmission;
use Modules\Learning\Services\AssignmentService;

class AssignmentSubmissionList extends Component
{
    use WithPagination;

    public string $statusFilter = 'all';
    public string $search = '';
    public string $sortField = 'submitted_at';
    public string $sortDirection = 'desc';

    protected $queryString = [
        'statusFilter' => ['except' => 'all'],
        'search' => ['except' => ''],
    ];

    public function updatingSearch()
    {
        $this->resetPage();
    }

    public function updatingStatusFilter()
    {
        $this->resetPage();
    }

    public function sortBy($field)
    {
        if ($this->sortField === $field) {
            $this->sortDirection = $this->sortDirection === 'asc' ? 'desc' : 'asc';
        } else {
            $this->sortField = $field;
            $this->sortDirection = 'asc';
        }
    }

    public function render()
    {
        $query = AssignmentSubmission::with(['assignment.lesson', 'student', 'grader'])
            ->whereIn('status', ['submitted', 'in_review', 'graded', 'needs_revision']) // Exclude drafts
            ->when($this->statusFilter !== 'all', function ($q) {
                $q->where('status', $this->statusFilter);
            })
            ->when($this->search, function ($q) {
                $q->whereHas('student', function ($userQuery) {
                    $userQuery->where('name', 'like', '%' . $this->search . '%')
                        ->orWhere('email', 'like', '%' . $this->search . '%');
                })
                ->orWhereHas('assignment', function ($assignmentQuery) {
                    $assignmentQuery->where('title', 'like', '%' . $this->search . '%');
                })
                ->orWhere('github_url', 'like', '%' . $this->search . '%');
            })
            ->orderBy($this->sortField, $this->sortDirection);

        $submissions = $query->paginate(15);
        
        $assignmentService = app(AssignmentService::class);
        
        // Calculate stats
        $stats = [
            'total' => AssignmentSubmission::whereIn('status', ['submitted', 'in_review', 'graded', 'needs_revision'])->count(),
            'pending' => AssignmentSubmission::where('status', 'submitted')->count(),
            'in_review' => AssignmentSubmission::where('status', 'in_review')->count(),
            'graded' => AssignmentSubmission::where('status', 'graded')->count(),
            'needs_revision' => AssignmentSubmission::where('status', 'needs_revision')->count(),
            'late' => AssignmentSubmission::where('is_late', true)->whereIn('status', ['submitted', 'in_review'])->count(),
        ];

        // Detect layout based on current route
        $layout = request()->is('admin/*') ? 'layouts.admin' : 'layouts.contributor';

        return view('learning::livewire.admin.assignment-submission-list', [
            'submissions' => $submissions,
            'stats' => $stats,
        ])->layout($layout);
    }
}
