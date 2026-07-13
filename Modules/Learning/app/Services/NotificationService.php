<?php

namespace Modules\Learning\Services;

use Modules\Learning\Models\LearningNotification;
use Modules\Learning\Models\Assignment;
use Modules\Learning\Models\AssignmentSubmission;

use Modules\Learning\Models\RoadmapSection;
use Modules\Learning\Models\RoadmapCertificate;
use Carbon\Carbon;

class NotificationService
{
    /**
     * Tạo notification cho assignment deadline sắp đến
     */
    public function sendAssignmentDueNotification(int $userId, Assignment $assignment, AssignmentSubmission $submission): void
    {
        $hoursRemaining = now()->diffInHours($submission->deadline, false);
        
        if ($hoursRemaining <= $assignment->reminder_hours && $hoursRemaining > 0) {
            LearningNotification::create([
                'user_id' => $userId,
                'type' => 'assignment_due',
                'notifiable_type' => Assignment::class,
                'notifiable_id' => $assignment->id,
                'roadmap_id' => $assignment->lesson->roadmap_id,
                'title' => 'Sắp đến hạn nộp bài',
                'message' => "Bài tập '{$assignment->title}' sẽ hết hạn trong {$hoursRemaining} giờ nữa.",
                'action_url' => "/roadmaps/{$assignment->lesson->roadmap_id}/assignments/{$assignment->id}",
                'action_text' => 'Nộp bài ngay',
                'priority' => $hoursRemaining <= 6 ? 'high' : 'normal',
                'scheduled_at' => now(),
            ]);
        }
    }

    /**
     * Thông báo khi assignment được chấm xong
     */
    public function sendAssignmentGradedNotification(AssignmentSubmission $submission): void
    {
        LearningNotification::create([
            'user_id' => $submission->user_id,
            'type' => 'assignment_graded',
            'notifiable_type' => AssignmentSubmission::class,
            'notifiable_id' => $submission->id,
            'roadmap_id' => $submission->assignment->lesson->roadmap_id,
            'title' => 'Bài tập đã được chấm',
            'message' => "Bài tập '{$submission->assignment->title}' đã được chấm. Điểm: {$submission->score}/{$submission->assignment->max_score}",
            'action_url' => "/roadmaps/{$submission->assignment->lesson->roadmap_id}/submissions/{$submission->id}",
            'action_text' => 'Xem kết quả',
            'priority' => 'normal',
            'sent_at' => now(),
        ]);
    }



    /**
     * Thông báo khi section được unlock
     */
    public function sendSectionUnlockedNotification(int $userId, int $sectionId): void
    {
        $section = RoadmapSection::find($sectionId);
        if (!$section) {
            return;
        }

        LearningNotification::create([
            'user_id' => $userId,
            'type' => 'section_unlocked',
            'notifiable_type' => RoadmapSection::class,
            'notifiable_id' => $sectionId,
            'roadmap_id' => $section->roadmap_id,
            'title' => 'Chương mới đã mở khóa',
            'message' => "Chúc mừng! Chương '{$section->title}' đã được mở. Tiếp tục học nào!",
            'action_url' => "/roadmaps/{$section->roadmap_id}/sections/{$sectionId}",
            'action_text' => 'Bắt đầu học',
            'priority' => 'high',
            'sent_at' => now(),
        ]);
    }

    /**
     * Thông báo khi nhận được certificate
     */
    public function sendCertificateEarnedNotification(RoadmapCertificate $certificate): void
    {
        LearningNotification::create([
            'user_id' => $certificate->user_id,
            'type' => 'certificate_earned',
            'notifiable_type' => RoadmapCertificate::class,
            'notifiable_id' => $certificate->id,
            'roadmap_id' => $certificate->roadmap_id,
            'title' => 'Chúc mừng! Bạn đã nhận được chứng chỉ',
            'message' => "Bạn đã hoàn thành khóa học '{$certificate->roadmap_title}' và nhận được chứng chỉ.",
            'action_url' => "/certificates/{$certificate->verification_code}",
            'action_text' => 'Xem chứng chỉ',
            'priority' => 'high',
            'sent_at' => now(),
        ]);
    }

    /**
     * Thông báo deadline reminder (scheduled)
     */
    public function scheduleDeadlineReminder(AssignmentSubmission $submission): void
    {
        $assignment = $submission->assignment;
        
        if (!$assignment->send_reminder) {
            return;
        }

        $reminderTime = $submission->deadline->subHours($assignment->reminder_hours);
        
        if ($reminderTime->isFuture()) {
            LearningNotification::create([
                'user_id' => $submission->user_id,
                'type' => 'deadline_reminder',
                'notifiable_type' => AssignmentSubmission::class,
                'notifiable_id' => $submission->id,
                'roadmap_id' => $assignment->lesson->roadmap_id,
                'title' => 'Nhắc nhở: Sắp đến hạn nộp bài',
                'message' => "Bài tập '{$assignment->title}' sẽ hết hạn vào {$submission->deadline->format('d/m/Y H:i')}",
                'action_url' => "/roadmaps/{$assignment->lesson->roadmap_id}/assignments/{$assignment->id}",
                'action_text' => 'Nộp bài',
                'priority' => 'urgent',
                'scheduled_at' => $reminderTime,
            ]);
        }
    }

    /**
     * Thông báo update khóa học
     */
    public function sendCourseUpdateNotification(int $roadmapId, string $title, string $message): void
    {
        $enrollments = \Modules\Learning\Models\RoadmapEnrollment::where('roadmap_id', $roadmapId)
            ->where('status', 'active')
            ->get();

        foreach ($enrollments as $enrollment) {
            LearningNotification::create([
                'user_id' => $enrollment->user_id,
                'type' => 'course_update',
                'notifiable_type' => null,
                'notifiable_id' => null,
                'roadmap_id' => $roadmapId,
                'title' => $title,
                'message' => $message,
                'action_url' => "/roadmaps/{$roadmapId}",
                'action_text' => 'Xem chi tiết',
                'priority' => 'normal',
                'sent_at' => now(),
            ]);
        }
    }

    /**
     * Gửi tất cả notification đã scheduled
     */
    public function sendScheduledNotifications(): int
    {
        $notifications = LearningNotification::pendingScheduled()->get();
        $count = 0;

        foreach ($notifications as $notification) {
            $notification->markAsSent();
            
            // TODO: Integrate with email/push notification service
            // Mail::to($notification->user->email)->send(new LearningNotificationMail($notification));
            
            $count++;
        }

        return $count;
    }

    /**
     * Đánh dấu notification đã đọc
     */
    public function markAsRead(int $notificationId): void
    {
        $notification = LearningNotification::find($notificationId);
        if ($notification) {
            $notification->markAsRead();
        }
    }

    /**
     * Đánh dấu tất cả notification của user đã đọc
     */
    public function markAllAsRead(int $userId): void
    {
        LearningNotification::where('user_id', $userId)
            ->where('is_read', false)
            ->update([
                'is_read' => true,
                'read_at' => now(),
            ]);
    }

    /**
     * Lấy notification chưa đọc của user
     */
    public function getUnreadNotifications(int $userId, int $limit = 10)
    {
        return LearningNotification::where('user_id', $userId)
            ->unread()
            ->orderBy('created_at', 'desc')
            ->limit($limit)
            ->get();
    }

    /**
     * Đếm số notification chưa đọc
     */
    public function getUnreadCount(int $userId): int
    {
        return LearningNotification::where('user_id', $userId)
            ->unread()
            ->count();
    }

    /**
     * Xóa notification cũ (tự động cleanup)
     */
    public function cleanupOldNotifications(int $daysOld = 90): int
    {
        $cutoffDate = now()->subDays($daysOld);
        
        return LearningNotification::where('created_at', '<', $cutoffDate)
            ->where('is_read', true)
            ->delete();
    }
}
