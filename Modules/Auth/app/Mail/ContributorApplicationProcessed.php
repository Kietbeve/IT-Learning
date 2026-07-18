<?php

namespace Modules\Auth\Mail;

use Illuminate\Bus\Queueable;
use Illuminate\Mail\Mailable;
use Illuminate\Mail\Mailables\Content;
use Illuminate\Mail\Mailables\Envelope;
use Illuminate\Queue\SerializesModels;
use Modules\Auth\Models\ContributorApplication;

/**
 * Email thông báo kết quả xử lý đơn đăng ký CTV
 */
class ContributorApplicationProcessed extends Mailable
{
    use Queueable, SerializesModels;

    public ContributorApplication $application;
    public string $status; // 'approved' hoặc 'rejected'
    public ?string $reason; // Lý do từ chối (nếu có)

    /**
     * Khởi tạo email
     *
     * @param ContributorApplication $application
     * @param string $status
     * @param string|null $reason
     */
    public function __construct(ContributorApplication $application, string $status, ?string $reason = null)
    {
        $this->application = $application;
        $this->status = $status;
        $this->reason = $reason;
    }

    /**
     * Thông tin envelope (subject, from)
     */
    public function envelope(): Envelope
    {
        $subject = $this->status === 'approved' 
            ? '🎉 Chúc mừng! Đơn đăng ký CTV của bạn đã được duyệt'
            : '📋 Thông báo về đơn đăng ký CTV của bạn';

        return new Envelope(
            subject: $subject,
        );
    }

    /**
     * Nội dung email
     */
    public function content(): Content
    {
        return new Content(
            view: 'emails.contributor-application-processed',
            with: [
                'application' => $this->application,
                'status' => $this->status,
                'reason' => $this->reason,
                'userName' => $this->application->user->name,
                'isApproved' => $this->status === 'approved',
                'isRejected' => $this->status === 'rejected',
            ],
        );
    }

    /**
     * Attachments (nếu có)
     */
    public function attachments(): array
    {
        return [];
    }
}
