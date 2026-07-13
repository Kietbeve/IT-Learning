<?php

namespace Modules\Learning\Http\Livewire\Student;

use Livewire\Component;
use Livewire\WithFileUploads;
use Modules\Learning\Models\Project;
use Modules\Learning\Models\ProjectSubmission;
use Modules\Learning\Models\ProjectSubmissionStep;
use Modules\Learning\Models\ProjectStepSubmission;
use Modules\Learning\Services\ProjectSubmissionService;
use Illuminate\Support\Facades\Auth;

class SubmitProject extends Component
{
    use WithFileUploads;

    public $project;
    public $projectSubmission;
    public $currentStep;
    public $allSteps = [];
    public $currentStepSubmission;
    
    // Form fields
    public $file;
    public $link_url = '';
    public $notes = '';
    
    // UI state
    public $isSubmitting = false;
    public $uploadProgress = 0;
    
    protected $submissionService;

    public function boot(ProjectSubmissionService $submissionService)
    {
        $this->submissionService = $submissionService;
    }

    public function mount($projectId)
    {
        $this->project = Project::findOrFail($projectId);
        
        // Tạo hoặc lấy project submission
        $this->projectSubmission = $this->submissionService->createProjectSubmission(
            $this->project->id,
            Auth::id()
        );

        // Load tất cả steps
        $this->allSteps = ProjectSubmissionStep::where('project_id', $this->project->id)
            ->where('is_active', true)
            ->ordered()
            ->get();

        // Tìm bước hiện tại cần làm
        $this->currentStep = $this->submissionService->getNextStep($this->projectSubmission->id);
        
        if (!$this->currentStep && $this->allSteps->isNotEmpty()) {
            $this->currentStep = $this->allSteps->first();
        }

        // Load submission hiện tại của step này (nếu có)
        if ($this->currentStep) {
            $this->loadCurrentStepSubmission();
        }
    }

    protected function loadCurrentStepSubmission()
    {
        $this->currentStepSubmission = $this->currentStep->getCurrentSubmission(
            Auth::id(),
            $this->projectSubmission->id
        );

        if ($this->currentStepSubmission) {
            $this->link_url = $this->currentStepSubmission->link_url ?? '';
            $this->notes = $this->currentStepSubmission->notes ?? '';
        }
    }

    public function switchStep($stepId)
    {
        $step = ProjectSubmissionStep::findOrFail($stepId);
        
        // Chỉ cho phép switch đến step đã làm hoặc step tiếp theo
        $submission = $step->getCurrentSubmission(Auth::id(), $this->projectSubmission->id);
        
        if (!$submission && $step->step_order > 1) {
            // Kiểm tra step trước đã approved chưa
            $previousStep = ProjectSubmissionStep::where('project_id', $this->project->id)
                ->where('step_order', $step->step_order - 1)
                ->first();
            
            if ($previousStep) {
                $previousSubmission = $previousStep->getCurrentSubmission(Auth::id(), $this->projectSubmission->id);
                if (!$previousSubmission || $previousSubmission->status !== 'approved') {
                    session()->flash('error', 'Bạn cần hoàn thành bước trước');
                    return;
                }
            }
        }

        $this->currentStep = $step;
        $this->resetForm();
        $this->loadCurrentStepSubmission();
    }

    public function submit()
    {
        if ($this->isSubmitting) {
            return;
        }

        $this->isSubmitting = true;

        try {
            // Validate based on submission_type
            $this->validateSubmission();

            // Prepare data
            $data = [
                'notes' => $this->notes,
            ];

            if ($this->file) {
                $data['file'] = $this->file;
            }

            if (!empty($this->link_url)) {
                $data['link_url'] = $this->link_url;
            }

            // Submit through service
            $submission = $this->submissionService->submitStep(
                $this->currentStep->id,
                Auth::id(),
                $this->projectSubmission->id,
                $data
            );

            session()->flash('success', 'Đã nộp bài thành công. Vui lòng chờ giáo viên chấm bài.');

            // Reset form
            $this->resetForm();
            
            // Reload data
            $this->projectSubmission->refresh();
            $this->currentStep = $this->submissionService->getNextStep($this->projectSubmission->id);
            
            if ($this->currentStep) {
                $this->loadCurrentStepSubmission();
            } else {
                // Đã hoàn thành tất cả steps
                session()->flash('success', 'Chúc mừng! Bạn đã hoàn thành tất cả các bước. Vui lòng chờ giáo viên chấm điểm cuối cùng.');
            }

        } catch (\Exception $e) {
            session()->flash('error', 'Lỗi: ' . $e->getMessage());
        } finally {
            $this->isSubmitting = false;
        }
    }

    protected function validateSubmission()
    {
        $rules = [];
        $messages = [];

        if ($this->currentStep->submission_type === 'file' || $this->currentStep->submission_type === 'both') {
            if ($this->currentStep->submission_type === 'file') {
                $rules['file'] = 'required|file|max:' . ($this->currentStep->max_file_size_mb * 1024);
                $messages['file.required'] = 'Vui lòng upload file';
            } else {
                $rules['file'] = 'nullable|file|max:' . ($this->currentStep->max_file_size_mb * 1024);
            }
            
            $messages['file.max'] = 'File không được vượt quá ' . $this->currentStep->max_file_size_mb . ' MB';
        }

        if ($this->currentStep->submission_type === 'link' || $this->currentStep->submission_type === 'both') {
            if ($this->currentStep->submission_type === 'link') {
                $rules['link_url'] = 'required|url';
                $messages['link_url.required'] = 'Vui lòng nhập link';
            } else {
                $rules['link_url'] = 'nullable|url';
            }
            
            $messages['link_url.url'] = 'Link không hợp lệ';
        }

        if ($this->currentStep->submission_type === 'both') {
            $this->validate([
                'file' => 'required_without:link_url',
                'link_url' => 'required_without:file',
            ], [
                'file.required_without' => 'Vui lòng upload file hoặc nhập link',
                'link_url.required_without' => 'Vui lòng nhập link hoặc upload file',
            ]);
        }

        if (!empty($rules)) {
            $this->validate($rules, $messages);
        }
    }

    public function updatedFile()
    {
        if (!$this->file) {
            return;
        }

        // Validate file type
        $extension = $this->file->getClientOriginalExtension();
        
        if (!empty($this->currentStep->allowed_file_types)) {
            if (!$this->currentStep->isFileTypeAllowed($extension)) {
                $this->file = null;
                session()->flash('error', 'Loại file không hợp lệ. Chỉ chấp nhận: ' . $this->currentStep->getAllowedFileTypesString());
                return;
            }
        }

        // Validate size
        $sizeMB = $this->file->getSize() / 1024 / 1024;
        if ($sizeMB > $this->currentStep->max_file_size_mb) {
            $this->file = null;
            session()->flash('error', 'File quá lớn. Tối đa ' . $this->currentStep->max_file_size_mb . ' MB');
        }
    }

    protected function resetForm()
    {
        $this->file = null;
        $this->link_url = '';
        $this->notes = '';
        $this->resetValidation();
    }

    public function getStepProgressProperty()
    {
        $progress = [];
        
        foreach ($this->allSteps as $step) {
            $submission = $step->getCurrentSubmission(Auth::id(), $this->projectSubmission->id);
            
            $status = 'not_started';
            if ($submission) {
                $status = $submission->status;
            }

            $progress[] = [
                'step' => $step,
                'submission' => $submission,
                'status' => $status,
                'is_current' => $this->currentStep && $this->currentStep->id === $step->id,
            ];
        }

        return $progress;
    }

    public function downloadSubmittedFile()
    {
        if (!$this->currentStepSubmission || !$this->currentStepSubmission->hasFile()) {
            session()->flash('error', 'Không có file để tải');
            return;
        }

        try {
            $filePath = storage_path('app/public/' . $this->currentStepSubmission->file_path);
            
            if (!file_exists($filePath)) {
                session()->flash('error', 'File không tồn tại');
                return;
            }

            return response()->download($filePath, $this->currentStepSubmission->file_name);
            
        } catch (\Exception $e) {
            session()->flash('error', 'Lỗi tải file: ' . $e->getMessage());
        }
    }

    public function render()
    {
        return view('learning::livewire.student.submit-project', [
            'stepProgress' => $this->stepProgress,
        ])->layout('layouts.user');
    }
}
