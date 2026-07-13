<?php

namespace Modules\Learning\Services;

use Modules\Learning\Models\Project;
use Modules\Learning\Models\ProjectSubmission;
use Modules\Learning\Models\ProjectSubmissionStep;
use Modules\Learning\Models\ProjectStepSubmission;
use Illuminate\Support\Facades\DB;
use Illuminate\Support\Facades\Storage;
use Illuminate\Http\UploadedFile;

/**
 * Service ProjectSubmissionService
 * 
 * Xử lý logic nộp bài từ phía học viên:
 * - Submit bước mới
 * - Resubmit khi bị reject
 * - Upload files
 * - Validate submissions
 * - Check completion status
 */
class ProjectSubmissionService
{
    /**
     * Tạo submission mới cho project (lần đầu tiên học viên bắt đầu làm)
     */
    public function createProjectSubmission(int $projectId, int $userId): ProjectSubmission
    {
        // Kiểm tra đã có submission chưa
        $existing = ProjectSubmission::where('project_id', $projectId)
            ->where('user_id', $userId)
            ->first();

        if ($existing) {
            return $existing;
        }

        // Tạo submission mới
        return ProjectSubmission::create([
            'project_id' => $projectId,
            'user_id' => $userId,
            'status' => 'submitted', // Đã bắt đầu làm
            'progress_percent' => 0,
            'started_at' => now(),
        ]);
    }

    /**
     * Submit một bước cụ thể
     * 
     * @param int $stepId ID của bước cần nộp
     * @param int $userId ID học viên
     * @param int $projectSubmissionId ID submission tổng
     * @param array $data ['file' => UploadedFile, 'link_url' => string, 'notes' => string]
     * @return ProjectStepSubmission
     * @throws \Exception
     */
    public function submitStep(
        int $stepId,
        int $userId,
        int $projectSubmissionId,
        array $data
    ): ProjectStepSubmission {
        DB::beginTransaction();
        
        try {
            $step = ProjectSubmissionStep::findOrFail($stepId);
            
            // Validate dữ liệu nộp
            $this->validateStepSubmission($step, $data);
            
            // Kiểm tra bước trước đã approved chưa (nếu không phải bước đầu tiên)
            if ($step->step_order > 1) {
                $this->ensurePreviousStepApproved($step, $userId, $projectSubmissionId);
            }
            
            // Kiểm tra có submission cũ không (để xác định submission_number)
            $currentSubmission = ProjectStepSubmission::where('step_id', $stepId)
                ->where('user_id', $userId)
                ->where('project_submission_id', $projectSubmissionId)
                ->where('is_current', true)
                ->first();
            
            $submissionNumber = 1;
            
            if ($currentSubmission) {
                // Đánh dấu submission cũ không còn current
                $currentSubmission->markAsNotCurrent();
                $submissionNumber = $currentSubmission->submission_number + 1;
                
                // Kiểm tra số lần nộp có vượt quá max không
                if ($submissionNumber > $step->max_resubmissions) {
                    throw new \Exception("Bạn đã vượt quá số lần nộp lại cho phép ({$step->max_resubmissions} lần)");
                }
            }
            
            // Tạo submission mới
            $submission = new ProjectStepSubmission([
                'project_submission_id' => $projectSubmissionId,
                'step_id' => $stepId,
                'user_id' => $userId,
                'submission_number' => $submissionNumber,
                'notes' => $data['notes'] ?? null,
                'status' => 'submitted',
                'submitted_at' => now(),
                'is_current' => true,
            ]);
            
            // Xử lý file upload nếu có
            if (isset($data['file']) && $data['file'] instanceof UploadedFile) {
                $fileData = $this->handleFileUpload($data['file'], $userId, $stepId);
                $submission->file_path = $fileData['path'];
                $submission->file_name = $fileData['name'];
                $submission->file_size_kb = $fileData['size_kb'];
            }
            
            // Lưu link URL nếu có
            if (!empty($data['link_url'])) {
                $submission->link_url = $data['link_url'];
            }
            
            $submission->save();
            
            // Update progress của project submission tổng
            $this->updateProjectProgress($projectSubmissionId);
            
            DB::commit();
            
            return $submission;
            
        } catch (\Exception $e) {
            DB::rollBack();
            throw $e;
        }
    }

    /**
     * Upload file và trả về thông tin file
     */
    protected function handleFileUpload(UploadedFile $file, int $userId, int $stepId): array
    {
        // Validate file
        $extension = $file->getClientOriginalExtension();
        $step = ProjectSubmissionStep::findOrFail($stepId);
        
        if (!$step->isFileTypeAllowed($extension)) {
            throw new \Exception("Loại file .{$extension} không được phép. Chỉ chấp nhận: " . $step->getAllowedFileTypesString());
        }
        
        // Validate size (MB)
        $fileSizeMB = $file->getSize() / 1024 / 1024;
        if ($fileSizeMB > $step->max_file_size_mb) {
            throw new \Exception("File quá lớn. Tối đa {$step->max_file_size_mb} MB");
        }
        
        // Generate unique filename
        $filename = time() . '_' . $userId . '_' . uniqid() . '.' . $extension;
        $path = "submissions/projects/step_{$stepId}/" . $filename;
        
        // Store file
        Storage::disk('public')->putFileAs(
            "submissions/projects/step_{$stepId}",
            $file,
            $filename
        );
        
        return [
            'path' => $path,
            'name' => $file->getClientOriginalName(),
            'size_kb' => round($file->getSize() / 1024, 2),
        ];
    }

    /**
     * Validate dữ liệu nộp bài
     */
    protected function validateStepSubmission(ProjectSubmissionStep $step, array $data): void
    {
        // Kiểm tra theo submission_type của step
        if ($step->submission_type === 'file' || $step->submission_type === 'both') {
            if (!isset($data['file']) || !($data['file'] instanceof UploadedFile)) {
                if ($step->submission_type === 'file') {
                    throw new \Exception("Vui lòng upload file");
                }
            }
        }
        
        if ($step->submission_type === 'link' || $step->submission_type === 'both') {
            if (empty($data['link_url'])) {
                if ($step->submission_type === 'link') {
                    throw new \Exception("Vui lòng nhập link");
                }
            } else {
                // Validate URL format
                if (!filter_var($data['link_url'], FILTER_VALIDATE_URL)) {
                    throw new \Exception("Link không hợp lệ. Vui lòng nhập URL đầy đủ (bắt đầu bằng http:// hoặc https://)");
                }
            }
        }
        
        // Nếu submission_type = 'both', phải có ít nhất 1 trong 2
        if ($step->submission_type === 'both') {
            $hasFile = isset($data['file']) && ($data['file'] instanceof UploadedFile);
            $hasLink = !empty($data['link_url']);
            
            if (!$hasFile && !$hasLink) {
                throw new \Exception("Vui lòng upload file hoặc nhập link");
            }
        }
    }

    /**
     * Kiểm tra bước trước đã approved chưa
     */
    protected function ensurePreviousStepApproved(
        ProjectSubmissionStep $currentStep,
        int $userId,
        int $projectSubmissionId
    ): void {
        $previousStep = ProjectSubmissionStep::where('project_id', $currentStep->project_id)
            ->where('step_order', $currentStep->step_order - 1)
            ->where('is_active', true)
            ->first();
        
        if (!$previousStep) {
            return; // Không có bước trước, OK
        }
        
        if (!$previousStep->isApprovedForUser($userId, $projectSubmissionId)) {
            throw new \Exception("Bạn cần hoàn thành bước \"{$previousStep->step_name}\" trước khi nộp bước này");
        }
    }

    /**
     * Lấy project submission của user (nếu có)
     * Method này chỉ GET, không tạo mới
     */
    public function getUserSubmission(int $userId, int $projectId): ?ProjectSubmission
    {
        return ProjectSubmission::where('user_id', $userId)
            ->where('project_id', $projectId)
            ->first();
    }

    /**
     * Kiểm tra user có thể submit project này không
     * Return array với can_submit và reason
     */
    public function canUserSubmit(int $userId, int $projectId): array
    {
        $project = Project::find($projectId);
        
        if (!$project) {
            return [
                'can_submit' => false,
                'reason' => 'Project không tồn tại'
            ];
        }
        
        // Kiểm tra user đã có submission chưa
        $submission = $this->getUserSubmission($userId, $projectId);
        
        // Nếu chưa có submission, cho phép tạo mới
        if (!$submission) {
            return [
                'can_submit' => true,
                'reason' => 'Bạn có thể bắt đầu nộp project'
            ];
        }
        
        // Nếu đã có submission và status là passed/failed, không cho submit lại
        if (in_array($submission->status, ['passed', 'failed'])) {
            return [
                'can_submit' => false,
                'reason' => 'Bạn đã hoàn thành project này'
            ];
        }
        
        // Các trường hợp khác (pending, in_progress) cho phép tiếp tục submit
        return [
            'can_submit' => true,
            'reason' => 'Bạn có thể tiếp tục nộp project'
        ];
    }

    /**
     * Update % hoàn thành của project submission
     */
    public function updateProjectProgress(int $projectSubmissionId): void
    {
        $projectSubmission = ProjectSubmission::findOrFail($projectSubmissionId);
        
        // Đếm tổng số steps của project
        $totalSteps = ProjectSubmissionStep::where('project_id', $projectSubmission->project_id)
            ->where('is_active', true)
            ->where('is_required', true)
            ->count();
        
        if ($totalSteps === 0) {
            return;
        }
        
        // Đếm số steps đã approved
        $approvedSteps = ProjectStepSubmission::where('project_submission_id', $projectSubmissionId)
            ->where('user_id', $projectSubmission->user_id)
            ->where('status', 'approved')
            ->where('is_current', true)
            ->count();
        
        // Tính %
        $progressPercent = round(($approvedSteps / $totalSteps) * 100, 2);
        
        $projectSubmission->update([
            'progress_percent' => $progressPercent,
        ]);
    }

    /**
     * Kiểm tra học viên đã hoàn thành tất cả các bước chưa
     */
    public function isProjectCompleted(int $projectSubmissionId): bool
    {
        $projectSubmission = ProjectSubmission::findOrFail($projectSubmissionId);
        
        $totalSteps = ProjectSubmissionStep::where('project_id', $projectSubmission->project_id)
            ->where('is_active', true)
            ->where('is_required', true)
            ->count();
        
        $approvedSteps = ProjectStepSubmission::where('project_submission_id', $projectSubmissionId)
            ->where('user_id', $projectSubmission->user_id)
            ->where('status', 'approved')
            ->where('is_current', true)
            ->count();
        
        return $totalSteps > 0 && $approvedSteps === $totalSteps;
    }

    /**
     * Lấy bước tiếp theo mà học viên cần làm
     */
    public function getNextStep(int $projectSubmissionId): ?ProjectSubmissionStep
    {
        $projectSubmission = ProjectSubmission::findOrFail($projectSubmissionId);
        
        $steps = ProjectSubmissionStep::where('project_id', $projectSubmission->project_id)
            ->where('is_active', true)
            ->ordered()
            ->get();
        
        foreach ($steps as $step) {
            $submission = $step->getCurrentSubmission($projectSubmission->user_id, $projectSubmissionId);
            
            // Nếu chưa nộp hoặc đang draft/rejected -> đây là bước cần làm
            if (!$submission || in_array($submission->status, ['draft', 'rejected'])) {
                return $step;
            }
            
            // Nếu đang pending review (submitted, under_review), vẫn trả về bước này để UI hiển thị trạng thái đang chờ
            if ($submission->status !== 'approved') {
                return $step;
            }
        }
        
        return null; // Đã hoàn thành tất cả
    }
}
