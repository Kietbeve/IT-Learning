<?php

namespace Modules\Document\Models;

use Illuminate\Database\Eloquent\Model;
use App\Models\User;

class DocumentRelationship extends Model
{
    protected $fillable = [
        'parent_document_id',
        'draft_document_id',
        'relationship_type',
        'status',
        'rejected_reason',
        'submitted_by',
        'reviewed_by',
        'submitted_at',
        'reviewed_at'
    ];

    protected $casts = [
        'submitted_at' => 'datetime',
        'reviewed_at' => 'datetime',
    ];

    /**
     * Get the parent document (original published document)
     * NULL for new submissions
     */
    public function parentDocument()
    {
        return $this->belongsTo(Document::class, 'parent_document_id')->withTrashed();
    }

    /**
     * Get the draft document (pending/rejected submission)
     */
    public function draftDocument()
    {
        return $this->belongsTo(Document::class, 'draft_document_id')->withTrashed();
    }

    /**
     * Get the user who submitted this document
     */
    public function submittedByUser()
    {
        return $this->belongsTo(User::class, 'submitted_by');
    }

    /**
     * Get the admin who reviewed this document
     */
    public function reviewedByUser()
    {
        return $this->belongsTo(User::class, 'reviewed_by');
    }

    /**
     * Check if this submission is pending review
     */
    public function isPending(): bool
    {
        return $this->status === 'pending';
    }

    /**
     * Check if this submission was approved
     */
    public function isApproved(): bool
    {
        return $this->status === 'approved';
    }

    /**
     * Check if this submission was rejected
     */
    public function isRejected(): bool
    {
        return $this->status === 'rejected';
    }

    /**
     * Check if this is a new submission (not an edit)
     */
    public function isNewSubmission(): bool
    {
        return $this->relationship_type === 'new_submission';
    }

    /**
     * Check if this is an edit submission
     */
    public function isEditSubmission(): bool
    {
        return $this->relationship_type === 'edit_submission';
    }

    /**
     * Scope to get pending relationships
     */
    public function scopePending($query)
    {
        return $query->where('status', 'pending');
    }

    /**
     * Scope to get approved relationships
     */
    public function scopeApproved($query)
    {
        return $query->where('status', 'approved');
    }

    /**
     * Scope to get rejected relationships
     */
    public function scopeRejected($query)
    {
        return $query->where('status', 'rejected');
    }
}
