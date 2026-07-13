<?php

namespace Modules\Auth\Models;

// use Illuminate\Contracts\Auth\MustVerifyEmail;
use Illuminate\Database\Eloquent\Factories\HasFactory;
use Illuminate\Foundation\Auth\User as Authenticatable;
use Illuminate\Notifications\Notifiable;
use Spatie\Permission\Traits\HasRoles;
use Illuminate\Database\Eloquent\SoftDeletes;
use Illuminate\Database\Eloquent\Relations\BelongsToMany;
use Illuminate\Database\Eloquent\Relations\HasMany;
use Modules\Learning\Models\Roadmap;
use Modules\Exam\Models\Question;
use Modules\Exam\Models\Exam;
use Modules\Exam\Models\ExamAttempt;
use Modules\Learning\Models\ProjectSubmission;
use Modules\Payment\Models\WalletTransaction;

class User extends Authenticatable
{
    use HasFactory, Notifiable, HasRoles, SoftDeletes;

    protected $guard_name = 'web';
    protected $table = 'users';

    /**
     * The attributes that are mass assignable.
     *
     * @var array<int, string>
     */
    protected $fillable = [
        'name',
        'email',
        'password',
        'avatar',
        'phone',
        'bio',
        'google_id',
        'status',
        'blocked_reason',
        'blocked_at',
        'blocked_by',
        'contributor_balance',
        'vip_expires_at',
        'vip_download_quota',
    ];

    /**
     * Check if VIP is active, and expire it if it has passed.
     *
     * @return bool True if VIP is active, false otherwise
     */
    public function checkAndExpireVip(): bool
    {
        if ($this->vip_expires_at) {
            if ($this->vip_expires_at->isPast()) {
                $this->update([
                    'vip_expires_at' => null,
                    'vip_download_quota' => 0
                ]);
                return false;
            }
            return true;
        }
        return false;
    }

    /**
     * The attributes that should be hidden for serialization.
     *
     * @var array<int, string>
     */
    protected $hidden = [
        'password',
        'remember_token',
    ];

    /**
     * Get the attributes that should be cast.
     *
     * @return array<string, string>
     */
    protected function casts(): array
    {
        return [
            'email_verified_at' => 'datetime',
            'password' => 'hashed',
            'vip_expires_at' => 'datetime',
            'blocked_at' => 'datetime',
        ];
    }

    // --- Learning Module Relations ---

    public function roadmaps(): BelongsToMany
    {
        return $this->belongsToMany(
            Roadmap::class,
            'roadmap_enrollments',
            'user_id',
            'roadmap_id'
        )
        ->withPivot([
            'status',
            'progress_percent',
            'started_at'
        ]);
    }

    public function authoredRoadmaps(): HasMany
    {
        return $this->hasMany(
            Roadmap::class,
            'author_id'
        );
    }

    public function reviewedRoadmaps(): HasMany
    {
        return $this->hasMany(
            Roadmap::class,
            'reviewed_by'
        );
    }

    public function projectSubmissions(): HasMany
    {
        return $this->hasMany(
            ProjectSubmission::class,
            'user_id'
        );
    }

    public function reviewedProjectSubmissions(): HasMany
    {
        return $this->hasMany(
            ProjectSubmission::class,
            'reviewed_by'
        );
    }

    // --- Exam Module Relations ---

    public function questions()
    {
        return $this->hasMany(
            Question::class,
            'author_id'
        );
    }

    public function reviewedQuestions()
    {
        return $this->hasMany(
            Question::class,
            'reviewed_by'
        );
    }

    public function exams()
    {
        return $this->hasMany(
            Exam::class,
            'author_id'
        );
    }

    public function reviewedExams()
    {
        return $this->hasMany(
            Exam::class,
            'reviewed_by'
        );
    }

    public function examAttempts()
    {
        return $this->hasMany(
            ExamAttempt::class,
            'user_id'
        );
    }

    // --- Payment Module Relations ---

    public function walletTransactions(): HasMany
    {
        return $this->hasMany(
            WalletTransaction::class,
            'user_id'
        );
    }

    // --- Auth Module Relations ---

    /**
     * Người dùng đã thực hiện khóa tài khoản này
     * 
     * @return \Illuminate\Database\Eloquent\Relations\BelongsTo
     */
    public function blocker()
    {
        return $this->belongsTo(User::class, 'blocked_by');
    }
}
