<?php

namespace Modules\Learning\Models;

use Illuminate\Database\Eloquent\Model;
use Illuminate\Database\Eloquent\Relations\BelongsTo;
use Illuminate\Database\Eloquent\Relations\MorphTo;
use Modules\Auth\Models\User;

class ForumReport extends Model
{
    protected $table = 'forum_reports';

    protected $fillable = [
        'user_id',
        'reportable_id',
        'reportable_type',
        'reason',
        'description',
        'status',
        'reviewed_by',
        'admin_note',
        'reviewed_at',
    ];

    protected function casts(): array
    {
        return [
            'reviewed_at' => 'datetime',
            'created_at' => 'datetime',
            'updated_at' => 'datetime',
        ];
    }

    /**
     * Get the user who made the report
     */
    public function user(): BelongsTo
    {
        return $this->belongsTo(User::class, 'user_id');
    }

    /**
     * Get the admin who reviewed the report
     */
    public function reviewer(): BelongsTo
    {
        return $this->belongsTo(User::class, 'reviewed_by');
    }

    /**
     * Get the parent reportable model (thread or post)
     */
    public function reportable(): MorphTo
    {
        return $this->morphTo();
    }

    /**
     * Available report reasons
     */
    public const REASONS = [
        'spam' => 'Spam hoặc quảng cáo',
        'inappropriate' => 'Nội dung không phù hợp',
        'harassment' => 'Quấy rối hoặc bắt nạt',
        'misinformation' => 'Thông tin sai lệch',
        'offensive' => 'Ngôn từ xúc phạm',
        'other' => 'Lý do khác',
    ];

    /**
     * Scope: Pending reports
     */
    public function scopePending($query)
    {
        return $query->where('status', 'pending');
    }

    /**
     * Scope: Resolved reports
     */
    public function scopeResolved($query)
    {
        return $query->where('status', 'resolved');
    }
}
