<?php

namespace Modules\Learning\Livewire\Admin;

use Livewire\Component;
use Modules\Learning\Models\Project;
use Modules\Learning\Models\Roadmap;
use Modules\Learning\Models\RoadmapSection;
use Modules\Learning\Models\ProjectRubricCriteria;
use Illuminate\Foundation\Auth\Access\AuthorizesRequests;
use Illuminate\Support\Facades\DB;

class ProjectForm extends Component
{
    use AuthorizesRequests;

    public $projectId = null;
    public $project = null;
    
    // Form fields
    public $roadmap_id = '';
    public $section_id = '';
    public $title = '';
    public $description = '';
    public $starter_code_url = '';
    public $deadline_at = '';
    public $max_resubmissions = 3;
    public $max_score = 100;
    public $passing_score = 60;
    public $required_completion_percentage = 70;
    public $sort_order = 0;
    
    // Grading criteria (JSON)
    public $grading_criteria = [];
    public $newCriterion = '';
    
    // Rubric criteria
    public $rubricCriteria = [];

    protected function rules()
    {
        return [
            'roadmap_id' => 'required|exists:roadmaps,id',
            'section_id' => 'nullable|exists:roadmap_sections,id',
            'title' => 'required|string|max:255',
            'description' => 'required|string',
            'starter_code_url' => 'nullable|url',
            'deadline_at' => 'nullable|date',
            'max_resubmissions' => 'required|integer|min:1|max:10',
            'max_score' => 'required|numeric|min:1|max:1000',
            'passing_score' => 'required|numeric|min:1|max:1000',
            'required_completion_percentage' => 'required|numeric|min:0|max:100',
            'sort_order' => 'required|integer|min:0',
        ];
    }

    protected $messages = [
        'roadmap_id.required' => 'Vui lòng chọn lộ trình',
        'title.required' => 'Vui lòng nhập tên project',
        'description.required' => 'Vui lòng nhập mô tả project',
        'max_score.required' => 'Vui lòng nhập điểm tối đa',
        'passing_score.required' => 'Vui lòng nhập điểm đạt',
    ];

    public function mount($projectId = null)
    {
        $this->authorize('manage_projects');
        
        if ($projectId) {
            $this->projectId = $projectId;
            $this->project = Project::with('rubricCriteria')->findOrFail($projectId);
            $this->loadProjectData();
        }
    }

    protected function loadProjectData()
    {
        $this->roadmap_id = $this->project->roadmap_id;
        $this->section_id = $this->project->section_id;
        $this->title = $this->project->title;
        $this->description = $this->project->description;
        $this->starter_code_url = $this->project->starter_code_url;
        $this->deadline_at = $this->project->deadline_at ? $this->project->deadline_at->format('Y-m-d\TH:i') : '';
        $this->max_resubmissions = $this->project->max_resubmissions;
        $this->max_score = $this->project->max_score;
        $this->passing_score = $this->project->passing_score;
        $this->required_completion_percentage = $this->project->required_completion_percentage;
        $this->sort_order = $this->project->sort_order;
        $this->grading_criteria = $this->project->grading_criteria ?? [];
        
        // Load rubric criteria
        $this->rubricCriteria = $this->project->rubricCriteria->map(function($criterion) {
            return [
                'id' => $criterion->id,
                'title' => $criterion->title,
                'description' => $criterion->description,
                'max_points' => $criterion->max_points,
                'sort_order' => $criterion->sort_order,
            ];
        })->toArray();
    }

    public function addCriterion()
    {
        if (trim($this->newCriterion) === '') {
            return;
        }

        $this->grading_criteria[] = trim($this->newCriterion);
        $this->newCriterion = '';
    }

    public function removeCriterion($index)
    {
        unset($this->grading_criteria[$index]);
        $this->grading_criteria = array_values($this->grading_criteria);
    }

    public function addRubricCriterion()
    {
        $this->rubricCriteria[] = [
            'id' => null,
            'title' => '',
            'description' => '',
            'max_points' => 10,
            'sort_order' => count($this->rubricCriteria),
        ];
    }

    public function removeRubricCriterion($index)
    {
        unset($this->rubricCriteria[$index]);
        $this->rubricCriteria = array_values($this->rubricCriteria);
    }

    public function updatedRoadmapId()
    {
        $this->section_id = '';
    }

    public function save()
    {
        $this->validate();

        try {
            DB::transaction(function () {
                $data = [
                    'roadmap_id' => $this->roadmap_id,
                    'section_id' => $this->section_id ?: null,
                    'title' => $this->title,
                    'description' => $this->description,
                    'starter_code_url' => $this->starter_code_url ?: null,
                    'deadline_at' => $this->deadline_at ? \Carbon\Carbon::parse($this->deadline_at) : null,
                    'max_resubmissions' => $this->max_resubmissions,
                    'max_score' => $this->max_score,
                    'passing_score' => $this->passing_score,
                    'required_completion_percentage' => $this->required_completion_percentage,
                    'sort_order' => $this->sort_order,
                    'grading_criteria' => $this->grading_criteria,
                ];

                if ($this->projectId) {
                    // Update existing project
                    $this->project->update($data);
                    $project = $this->project;
                    $message = 'Đã cập nhật project thành công!';
                } else {
                    // Create new project
                    $project = Project::create($data);
                    $message = 'Đã tạo project mới thành công!';
                }

                // Sync rubric criteria
                $this->syncRubricCriteria($project);

                session()->flash('success', $message);
                return redirect()->route('admin.projects.index');
            });

        } catch (\Exception $e) {
            session()->flash('error', 'Lỗi: ' . $e->getMessage());
        }
    }

    protected function syncRubricCriteria($project)
    {
        // Get existing criteria IDs
        $existingIds = collect($this->rubricCriteria)
            ->pluck('id')
            ->filter()
            ->toArray();

        // Delete criteria not in the list
        ProjectRubricCriteria::where('project_id', $project->id)
            ->whereNotIn('id', $existingIds)
            ->delete();

        // Create or update criteria
        foreach ($this->rubricCriteria as $index => $criterion) {
            if (empty($criterion['title'])) {
                continue;
            }

            $data = [
                'project_id' => $project->id,
                'title' => $criterion['title'],
                'description' => $criterion['description'] ?? '',
                'max_points' => $criterion['max_points'] ?? 10,
                'sort_order' => $index,
            ];

            if ($criterion['id']) {
                ProjectRubricCriteria::where('id', $criterion['id'])->update($data);
            } else {
                ProjectRubricCriteria::create($data);
            }
        }
    }

    public function getRoadmapsProperty()
    {
        return Roadmap::where('status', 'approved')
            ->orderBy('title')
            ->get();
    }

    public function getSectionsProperty()
    {
        if (!$this->roadmap_id) {
            return collect();
        }

        return RoadmapSection::where('roadmap_id', $this->roadmap_id)
            ->orderBy('sort_order')
            ->get();
    }

    public function render()
    {
        return view('learning::livewire.admin.project-form', [
            'roadmaps' => $this->roadmaps,
            'sections' => $this->sections,
        ])->layout('learning::layouts.admin-layout');
    }
}
