<?php

namespace Modules\Learning\Livewire\Admin;

use Livewire\Component;
use Livewire\WithPagination;
use Modules\Learning\Models\Project;
use Modules\Learning\Models\Roadmap;
use Modules\Learning\Models\RoadmapSection;
use Illuminate\Foundation\Auth\Access\AuthorizesRequests;

class ProjectCrud extends Component
{
    use WithPagination, AuthorizesRequests;

    public $search = '';
    public $filterRoadmap = '';
    public $filterSection = '';
    public $sortField = 'created_at';
    public $sortDirection = 'desc';
    
    public $showDeleteModal = false;
    public $projectToDelete = null;

    protected $queryString = [
        'search' => ['except' => ''],
        'filterRoadmap' => ['except' => ''],
        'filterSection' => ['except' => ''],
    ];

    public function mount()
    {
        // Check permission
        $this->authorize('manage_projects');
    }

    public function updatingSearch()
    {
        $this->resetPage();
    }

    public function updatingFilterRoadmap()
    {
        $this->filterSection = '';
        $this->resetPage();
    }

    public function updatingFilterSection()
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

    public function confirmDelete($projectId)
    {
        $this->projectToDelete = Project::findOrFail($projectId);
        $this->showDeleteModal = true;
    }

    public function cancelDelete()
    {
        $this->showDeleteModal = false;
        $this->projectToDelete = null;
    }

    public function deleteProject()
    {
        if (!$this->projectToDelete) {
            return;
        }

        try {
            // Check if project has submissions
            $submissionsCount = $this->projectToDelete->submissions()->count();
            
            if ($submissionsCount > 0) {
                session()->flash('error', "Không thể xóa project này vì đã có {$submissionsCount} submissions. Vui lòng xóa submissions trước.");
                $this->cancelDelete();
                return;
            }

            // Check if project is linked to any lesson
            $linkedLesson = \Modules\Learning\Models\RoadmapLesson::where('project_id', $this->projectToDelete->id)->first();
            
            if ($linkedLesson) {
                session()->flash('error', "Không thể xóa project này vì đang được liên kết với lesson: {$linkedLesson->title}. Vui lòng gỡ liên kết trước.");
                $this->cancelDelete();
                return;
            }

            // Delete rubric criteria
            $this->projectToDelete->rubricCriteria()->delete();
            
            // Delete project
            $projectTitle = $this->projectToDelete->title;
            $this->projectToDelete->delete();

            session()->flash('success', "Đã xóa project: {$projectTitle}");
            $this->cancelDelete();
            
        } catch (\Exception $e) {
            session()->flash('error', 'Lỗi khi xóa project: ' . $e->getMessage());
            $this->cancelDelete();
        }
    }

    public function getRoadmapsProperty()
    {
        return Roadmap::select('id', 'title')
            ->where('status', 'approved')
            ->orderBy('title')
            ->get();
    }

    public function getSectionsProperty()
    {
        if (!$this->filterRoadmap) {
            return collect();
        }

        return RoadmapSection::where('roadmap_id', $this->filterRoadmap)
            ->orderBy('sort_order')
            ->get();
    }

    public function getProjectsProperty()
    {
        $query = Project::with(['roadmap', 'section', 'submissions'])
            ->when($this->search, function ($q) {
                $q->where(function ($query) {
                    $query->where('title', 'like', '%' . $this->search . '%')
                          ->orWhere('description', 'like', '%' . $this->search . '%');
                });
            })
            ->when($this->filterRoadmap, function ($q) {
                $q->where('roadmap_id', $this->filterRoadmap);
            })
            ->when($this->filterSection, function ($q) {
                $q->where('section_id', $this->filterSection);
            });

        // Apply sorting
        $query->orderBy($this->sortField, $this->sortDirection);

        return $query->paginate(15);
    }

    public function render()
    {
        return view('learning::livewire.admin.project-crud', [
            'projects' => $this->projects,
            'roadmaps' => $this->roadmaps,
            'sections' => $this->sections,
        ])->layout('learning::layouts.admin-layout');
    }
}
