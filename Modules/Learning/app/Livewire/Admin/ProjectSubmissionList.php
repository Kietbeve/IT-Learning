<?php

namespace Modules\Learning\Livewire\Admin;

use Livewire\Component;
use Livewire\WithPagination;
use Modules\Learning\Models\ProjectSubmission;
use Modules\Learning\Services\ProjectSubmissionService;

class ProjectSubmissionList extends Component
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
        $query = ProjectSubmission::with(['project', 'user', 'reviewer'])
            ->when($this->statusFilter !== 'all', function ($q) {
                $q->where('status', $this->statusFilter);
            })
            ->when($this->search, function ($q) {
                $q->whereHas('user', function ($userQuery) {
                    $userQuery->where('name', 'like', '%' . $this->search . '%')
                        ->orWhere('email', 'like', '%' . $this->search . '%');
                })
                ->orWhereHas('project', function ($projectQuery) {
                    $projectQuery->where('title', 'like', '%' . $this->search . '%');
                })
                ->orWhere('github_url', 'like', '%' . $this->search . '%');
            })
            ->orderBy($this->sortField, $this->sortDirection);

        $submissions = $query->paginate(15);
        
        $submissionService = app(ProjectSubmissionService::class);
        $stats = $submissionService->getSubmissionStats();

        return view('learning::livewire.admin.project-submission-list', [
            'submissions' => $submissions,
            'stats' => $stats,
        ]);
    }
}
