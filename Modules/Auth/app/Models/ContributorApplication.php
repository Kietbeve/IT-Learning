<?php

namespace Modules\Auth\Models;

use Illuminate\Database\Eloquent\Model;


class ContributorApplication extends Model
{
    protected $table = 'contributor_applications';

    protected $fillable = [
        'user_id',
        'expertise',
        'reason',
        'cv_url',
        'status',
        'rejected_reason',
        'reviewed_by',
        'reviewed_at',
        'bank_name',
        'bank_account_number',
        'bank_account_name',
        'id_card_number',
        'address',
    ];

    protected $casts = [
        'reviewed_at' => 'datetime',
    ];

    /*
    |-----------------------------------------
    | Relationships
    |-----------------------------------------
    */

    // Người nộp đơn
    public function user()
    {
        return $this->belongsTo(User::class, 'user_id');
    }

    // Admin/Reviewer
    public function reviewer()
    {
        return $this->belongsTo(User::class, 'reviewed_by');
    }

    /*
    |-----------------------------------------
    | Scopes (lọc nhanh)
    |-----------------------------------------
    */

    public function scopePending($query)
    {
        return $query->where('status', 'pending');
    }

    public function scopeApproved($query)
    {
        return $query->where('status', 'approved');
    }

    public function scopeRejected($query)
    {
        return $query->where('status', 'rejected');
    }

    /*
    |-----------------------------------------
    | Helpers
    |-----------------------------------------
    */

    public function isPending(): bool
    {
        return $this->status === 'pending';
    }

    public function isApproved(): bool
    {
        return $this->status === 'approved';
    }

    public function isRejected(): bool
    {
        return $this->status === 'rejected';
    }
}
