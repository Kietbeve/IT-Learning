<?php

namespace Modules\Learning\Http\Livewire\Admin;

use Livewire\Component;
use Livewire\WithFileUploads;
use Modules\Learning\Models\Project;
use Modules\Learning\Models\ProjectSubmissionStep;

class ManageProjectSteps extends Component
{
    use WithFileUploads;

    public $project;
    public $steps = [];
    
    // Form fields
    public $step_name;
    public $step_order;
    public $submission_type = 'file';
    public $instructions;
    public $resource_file;
    public $existing_resource_file_path;
    public $existing_resource_file_name;
    public $requirements;
    public $allowed_file_types = [];
    public $max_file_size_mb = 100;
    public $link_placeholder;
    public $is_required = true;
    public $is_active = true;
    public $max_resubmissions = 5;
    
    public $editingStepId = null;
    public $showModal = false;

    protected $rules = [
        'step_name' => 'required|string|max:100',
        'step_order' => 'required|integer|min:1',
        'submission_type' => 'required|in:file,link,both',
        'instructions' => 'nullable|string',
        'resource_file' => 'nullable|file|max:10240', // 10MB max for requirements file
        'requirements' => 'nullable|string',
        'allowed_file_types' => 'nullable|array',
        'max_file_size_mb' => 'required|integer|min:1|max:500',
        'link_placeholder' => 'nullable|string|max:255',
        'is_required' => 'boolean',
        'is_active' => 'boolean',
        'max_resubmissions' => 'required|integer|min:1|max:20',
    ];

    public function mount($projectId)
    {
        $this->project = Project::findOrFail($projectId);
        $this->loadSteps();
    }

    public function loadSteps()
    {
        $this->steps = ProjectSubmissionStep::where('project_id', $this->project->id)
            ->orderBy('step_order')
            ->get();
    }

    public function openCreateModal()
    {
        $this->resetForm();
        $this->editingStepId = null;
        $this->existing_resource_file_path = null;
        $this->existing_resource_file_name = null;
        $this->resource_file = null;
        $this->showModal = true;
        
        // Auto-set next step order
        $maxOrder = $this->steps->max('step_order') ?? 0;
        $this->step_order = $maxOrder + 1;
    }

    public function openEditModal($stepId)
    {
        $step = ProjectSubmissionStep::findOrFail($stepId);
        
        $this->editingStepId = $step->id;
        $this->step_name = $step->step_name;
        $this->step_order = $step->step_order;
        $this->submission_type = $step->submission_type;
        $this->instructions = $step->instructions;
        $this->existing_resource_file_path = $step->resource_file_path;
        $this->existing_resource_file_name = $step->resource_file_name;
        $this->resource_file = null;
        $this->requirements = $step->requirements;
        $this->allowed_file_types = $step->allowed_file_types ?? [];
        $this->max_file_size_mb = $step->max_file_size_mb;
        $this->link_placeholder = $step->link_placeholder;
        $this->is_required = $step->is_required;
        $this->is_active = $step->is_active;
        $this->max_resubmissions = $step->max_resubmissions;
        
        $this->showModal = true;
    }

    public function save()
    {
        $this->validate();

        try {
            $data = [
                'project_id' => $this->project->id,
                'step_name' => $this->step_name,
                'step_order' => $this->step_order,
                'submission_type' => $this->submission_type,
                'instructions' => $this->instructions,
                'requirements' => $this->requirements,
                'allowed_file_types' => $this->allowed_file_types,
                'max_file_size_mb' => $this->max_file_size_mb,
                'link_placeholder' => $this->link_placeholder,
                'is_required' => $this->is_required,
                'is_active' => $this->is_active,
                'max_resubmissions' => $this->max_resubmissions,
            ];

            if ($this->resource_file) {
                $path = $this->resource_file->store('projects/steps/resources', 'public');
                $data['resource_file_path'] = $path;
                $data['resource_file_name'] = $this->resource_file->getClientOriginalName();
            }

            if ($this->editingStepId) {
                $step = ProjectSubmissionStep::findOrFail($this->editingStepId);
                $step->update($data);
                session()->flash('success', 'Đã cập nhật bước thành công');
            } else {
                ProjectSubmissionStep::create($data);
                session()->flash('success', 'Đã tạo bước mới thành công');
            }

            $this->loadSteps();
            $this->closeModal();
            
        } catch (\Exception $e) {
            $this->notification()->error('Lỗi: ' . $e->getMessage());
        }
    }

    public function deleteStep($stepId)
    {
        try {
            $step = ProjectSubmissionStep::findOrFail($stepId);
            $step->delete();
            
            $this->loadSteps();
            session()->flash('success', 'Đã xóa bước thành công');
            
        } catch (\Exception $e) {
            session()->flash('error', 'Lỗi: ' . $e->getMessage());
        }
    }

    public function toggleActive($stepId)
    {
        try {
            $step = ProjectSubmissionStep::findOrFail($stepId);
            $step->is_active = !$step->is_active;
            $step->save();
            
            $this->loadSteps();
            session()->flash('success', 'Đã cập nhật trạng thái');
            
        } catch (\Exception $e) {
            session()->flash('error', 'Lỗi: ' . $e->getMessage());
        }
    }

    public function createDefaultSteps()
    {
        try {
            $defaultSteps = [
                [
                    'step_name' => 'Phân tích yêu cầu',
                    'step_order' => 1,
                    'submission_type' => 'file',
                    'instructions' => 'Phân tích yêu cầu của project và đưa ra giải pháp',
                    'allowed_file_types' => ['doc', 'docx', 'txt', 'pdf'],
                    'max_file_size_mb' => 100,
                ],
                [
                    'step_name' => 'Thiết kế Database',
                    'step_order' => 2,
                    'submission_type' => 'file',
                    'instructions' => 'Thiết kế cơ sở dữ liệu cho project',
                    'allowed_file_types' => ['sql', 'png', 'jpg', 'pdf'],
                    'max_file_size_mb' => 100,
                ],
                [
                    'step_name' => 'Thiết kế giao diện',
                    'step_order' => 3,
                    'submission_type' => 'link',
                    'instructions' => 'Thiết kế giao diện trên Figma',
                    'link_placeholder' => 'Nhập link Figma...',
                    'max_file_size_mb' => 100,
                ],
                [
                    'step_name' => 'Code',
                    'step_order' => 4,
                    'submission_type' => 'link',
                    'instructions' => 'Nộp source code qua GitHub',
                    'link_placeholder' => 'Nhập link GitHub repository...',
                    'max_file_size_mb' => 100,
                ],
                [
                    'step_name' => 'Demo Video',
                    'step_order' => 5,
                    'submission_type' => 'both',
                    'instructions' => 'Video demo dự án (YouTube hoặc upload file)',
                    'link_placeholder' => 'Nhập link YouTube (hoặc upload file)...',
                    'allowed_file_types' => ['mp4', 'avi', 'mov'],
                    'max_file_size_mb' => 200,
                ],
            ];

            foreach ($defaultSteps as $stepData) {
                ProjectSubmissionStep::create(array_merge($stepData, [
                    'project_id' => $this->project->id,
                    'is_required' => true,
                    'is_active' => true,
                    'max_resubmissions' => 5,
                ]));
            }

            $this->loadSteps();
            session()->flash('success', 'Đã tạo 5 bước mặc định thành công');
            
        } catch (\Exception $e) {
            session()->flash('error', 'Lỗi: ' . $e->getMessage());
        }
    }

    public function closeModal()
    {
        $this->showModal = false;
        $this->resetForm();
    }

    protected function resetForm()
    {
        $this->step_name = '';
        $this->step_order = 1;
        $this->submission_type = 'file';
        $this->instructions = '';
        $this->requirements = '';
        $this->allowed_file_types = [];
        $this->max_file_size_mb = 100;
        $this->link_placeholder = '';
        $this->is_required = true;
        $this->is_active = true;
        $this->max_resubmissions = 5;
        $this->resetValidation();
    }

    public function render()
    {
        return view('learning::livewire.admin.manage-project-steps')->layout('layouts.user');
    }
}
