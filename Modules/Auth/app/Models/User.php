<?php

namespace Modules\Auth\Models;

use Illuminate\Database\Eloquent\SoftDeletes;
use Illuminate\Foundation\Auth\User as Authenticatable;
use Illuminate\Notifications\Notifiable;
use Spatie\Permission\Traits\HasRoles;

use Illuminate\Database\Eloquent\Relations\HasMany;
use Modules\Exam\Models\Question;
use Modules\Exam\Models\Exam;
use Modules\Exam\Models\ExamAttempt;
use Modules\Learning\Models\Roadmap;
use Modules\Learning\Models\ProjectSubmission;
/**
 * Bảng users: quản lý toàn bộ tài khoản trong hệ thống.
 *
 * Cấu trúc chính:
 * - id: khóa chính.
 * - name: họ tên người dùng.
 * - email: email đăng nhập (Google/Admin), duy nhất.
 * - password: mật khẩu nội bộ, có thể null nếu đăng nhập Google.
 * - avatar: ảnh đại diện.
 * - phone: số điện thoại.
 * - bio: mô tả cá nhân.
 * - google_id: ID Google OAuth, duy nhất.
 *
 * Trạng thái tài khoản:
 * - status: active / blocked.
 * - blocked_reason: lý do khóa.
 * - blocked_at: thời điểm khóa.
 * - blocked_by: admin thực hiện khóa (FK -> users.id).
 *
 * Cộng tác viên:
 * - contributor_balance: số dư hiện có.
 *
 * Hệ thống:
 * - created_at, updated_at.
 * - deleted_at: soft delete.
 */
class User extends Authenticatable
{
    use Notifiable;
    use HasRoles;
    use SoftDeletes;

    protected $guard_name = 'web';
    protected $table = 'users';

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
    ];

    protected $hidden = [
        'password',
        'remember_token',
    ];
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

    public function roadmaps(): HasMany
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
}