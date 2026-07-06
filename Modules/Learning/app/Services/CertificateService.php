<?php

namespace Modules\Learning\Services;

use Modules\Learning\Models\RoadmapCertificate;
use Modules\Learning\Models\Roadmap;
use Modules\Learning\Models\RoadmapEnrollment;
use Modules\Auth\Models\User;
use Illuminate\Support\Str;
use Illuminate\Support\Facades\Storage;
use Carbon\Carbon;

class CertificateService
{
    /**
     * Tạo certificate cho user khi hoàn thành roadmap
     */
    public function issueCertificate(int $userId, int $roadmapId): ?RoadmapCertificate
    {
        // Check if user đã hoàn thành roadmap
        $sectionProgressService = app(SectionProgressService::class);
        if (!$sectionProgressService->isRoadmapCompleted($userId, $roadmapId)) {
            return null;
        }

        // Check if certificate đã tồn tại
        $existingCertificate = RoadmapCertificate::where('user_id', $userId)
            ->where('roadmap_id', $roadmapId)
            ->where('status', 'active')
            ->first();

        if ($existingCertificate) {
            return $existingCertificate;
        }

        $user = User::find($userId);
        $roadmap = Roadmap::find($roadmapId);
        $enrollment = RoadmapEnrollment::where('user_id', $userId)
            ->where('roadmap_id', $roadmapId)
            ->first();

        if (!$user || !$roadmap || !$enrollment) {
            return null;
        }

        // Tính toán metrics
        $finalScore = $this->calculateFinalScore($userId, $roadmapId);
        $totalHours = $this->calculateTotalHours($userId, $roadmapId);
        $completionPercent = $enrollment->progress_percent ?? 100;

        // Tạo certificate
        $certificate = RoadmapCertificate::create([
            'certificate_number' => RoadmapCertificate::generateCertificateNumber(),
            'user_id' => $userId,
            'roadmap_id' => $roadmapId,
            'user_name' => $user->name,
            'roadmap_title' => $roadmap->title,
            'final_score' => $finalScore,
            'completion_percent' => $completionPercent,
            'total_hours' => $totalHours,
            'issued_at' => now(),
            'expires_at' => null, // Không hết hạn mặc định
            'verification_code' => RoadmapCertificate::generateVerificationCode(),
            'status' => 'active',
            'metadata' => [
                'instructor' => $roadmap->author->name ?? null,
                'enrollment_date' => $enrollment->created_at->format('Y-m-d'),
                'completion_date' => now()->format('Y-m-d'),
            ],
        ]);

        // Generate PDF (sẽ làm sau qua Job)
        // dispatch(new GenerateCertificatePdfJob($certificate->id));

        // Send notification
        app(NotificationService::class)->sendCertificateEarnedNotification($certificate);

        // Fire event
        event(new \Modules\Learning\Events\CertificateIssued($certificate));

        return $certificate;
    }

    /**
     * Tính điểm trung bình cuối khóa
     */
    protected function calculateFinalScore(int $userId, int $roadmapId): ?float
    {
        $scores = [];

        // Điểm từ assignments
        $assignmentScores = \Modules\Learning\Models\AssignmentSubmission::where('user_id', $userId)
            ->whereHas('assignment.lesson', function ($query) use ($roadmapId) {
                $query->where('roadmap_id', $roadmapId);
            })
            ->whereNotNull('score')
            ->get()
            ->map(function ($submission) {
                $maxScore = $submission->assignment->max_score ?? 100;
                return ($submission->score / $maxScore) * 100;
            });

        if ($assignmentScores->isNotEmpty()) {
            $scores[] = $assignmentScores->average();
        }

        // Điểm từ quizzes
        $quizScores = \Modules\Learning\Models\LessonQuizAttempt::where('user_id', $userId)
            ->whereHas('quiz.lesson', function ($query) use ($roadmapId) {
                $query->where('roadmap_id', $roadmapId);
            })
            ->whereNotNull('score')
            ->get()
            ->map(function ($attempt) {
                return $attempt->score; // Already in percentage
            });

        if ($quizScores->isNotEmpty()) {
            $scores[] = $quizScores->average();
        }

        // Điểm từ projects
        $projectScores = \Modules\Learning\Models\ProjectSubmission::where('user_id', $userId)
            ->whereHas('project', function ($query) use ($roadmapId) {
                $query->where('roadmap_id', $roadmapId);
            })
            ->whereNotNull('score')
            ->get()
            ->map(function ($submission) {
                $maxScore = $submission->project->max_score ?? 100;
                return ($submission->score / $maxScore) * 100;
            });

        if ($projectScores->isNotEmpty()) {
            $scores[] = $projectScores->average();
        }

        return !empty($scores) ? round(array_sum($scores) / count($scores), 2) : null;
    }

    /**
     * Tính tổng số giờ học
     */
    protected function calculateTotalHours(int $userId, int $roadmapId): int
    {
        $totalMinutes = \Modules\Learning\Models\SectionProgress::where('user_id', $userId)
            ->where('roadmap_id', $roadmapId)
            ->sum('time_spent_minutes');

        return (int) ceil($totalMinutes / 60);
    }

    /**
     * Thu hồi certificate
     */
    public function revokeCertificate(int $certificateId, string $reason): bool
    {
        $certificate = RoadmapCertificate::find($certificateId);
        if (!$certificate) {
            return false;
        }

        $certificate->revoke($reason);

        // Send notification to user
        app(NotificationService::class)->sendCourseUpdateNotification(
            $certificate->roadmap_id,
            'Chứng chỉ đã bị thu hồi',
            "Chứng chỉ của bạn cho khóa '{$certificate->roadmap_title}' đã bị thu hồi. Lý do: {$reason}"
        );

        return true;
    }

    /**
     * Verify certificate bằng verification code
     */
    public function verifyCertificate(string $verificationCode): ?array
    {
        $certificate = RoadmapCertificate::where('verification_code', $verificationCode)->first();

        if (!$certificate) {
            return null;
        }

        return [
            'valid' => $certificate->isValid(),
            'certificate' => $certificate,
            'user' => $certificate->user,
            'roadmap' => $certificate->roadmap,
            'issued_at' => $certificate->issued_at,
            'status' => $certificate->status,
            'revoked_reason' => $certificate->revoked_reason,
        ];
    }

    /**
     * Lấy tất cả certificates của user
     */
    public function getUserCertificates(int $userId)
    {
        return RoadmapCertificate::where('user_id', $userId)
            ->with(['roadmap'])
            ->orderBy('issued_at', 'desc')
            ->get();
    }

    /**
     * Generate PDF certificate (placeholder - sẽ implement với library như TCPDF/DomPDF)
     */
    public function generateCertificatePdf(int $certificateId): ?string
    {
        $certificate = RoadmapCertificate::find($certificateId);
        if (!$certificate) {
            return null;
        }

        // TODO: Implement PDF generation với DomPDF hoặc TCPDF
        // $pdf = PDF::loadView('learning::certificates.template', ['certificate' => $certificate]);
        // $filename = "certificate_{$certificate->certificate_number}.pdf";
        // $path = "certificates/{$certificate->user_id}/{$filename}";
        // Storage::put($path, $pdf->output());
        // 
        // $certificate->certificate_path = $path;
        // $certificate->save();
        // 
        // return $path;

        return null; // Placeholder
    }

    /**
     * Check if user eligible để nhận certificate
     */
    public function isEligibleForCertificate(int $userId, int $roadmapId): array
    {
        $sectionProgressService = app(SectionProgressService::class);
        $isCompleted = $sectionProgressService->isRoadmapCompleted($userId, $roadmapId);

        $completionPercent = $sectionProgressService->calculateRoadmapCompletionPercent($userId, $roadmapId);
        $finalScore = $this->calculateFinalScore($userId, $roadmapId);

        return [
            'eligible' => $isCompleted,
            'completion_percent' => $completionPercent,
            'final_score' => $finalScore,
            'requirements_met' => [
                'all_sections_completed' => $isCompleted,
                'minimum_score' => $finalScore >= 60, // Có thể config
            ],
        ];
    }

    /**
     * Renew expired certificate
     */
    public function renewCertificate(int $certificateId): ?RoadmapCertificate
    {
        $certificate = RoadmapCertificate::find($certificateId);
        if (!$certificate || $certificate->status !== 'expired') {
            return null;
        }

        $certificate->status = 'active';
        $certificate->expires_at = now()->addYear(); // Extend 1 năm
        $certificate->save();

        return $certificate;
    }
}
