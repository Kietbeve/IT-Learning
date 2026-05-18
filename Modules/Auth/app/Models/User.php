<?php

namespace Modules\Auth\Models;

use Illuminate\Database\Eloquent\SoftDeletes;
use Illuminate\Foundation\Auth\User as Authenticatable;
use Illuminate\Notifications\Notifiable;
use Spatie\Permission\Traits\HasRoles;
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
}