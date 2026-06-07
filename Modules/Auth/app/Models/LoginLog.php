<?php

namespace Modules\Auth\Models;

use Illuminate\Database\Eloquent\Model;

class LoginLog extends Model
{


    /**
     * The attributes that are mass assignable.
     */
    protected $table = 'login_logs';

    protected $fillable = [
        'user_id',
        'email',
        'ip_address',
        'status',
        'user_agent',
    ];

    /*
    |-----------------------------------------
    | Relationships
    |-----------------------------------------
    */

    public function user()
    {
        return $this->belongsTo(User::class);
    }

    /*
    |-----------------------------------------
    | Scopes
    |-----------------------------------------
    */

    public function scopeSuccess($query)
    {
        return $query->where('status', 'success');
    }

    public function scopeFailed($query)
    {
        return $query->where('status', 'failed');
    }

    public function scopeByEmail($query, string $email)
    {
        return $query->where('email', $email);
    }

    public function scopeByIp($query, string $ip)
    {
        return $query->where('ip_address', $ip);
    }

    public function scopeLastMinutes($query, int $minutes)
    {
        return $query->where('created_at', '>=', now()->subMinutes($minutes));
    }

    /*
    |-----------------------------------------
    | Helpers
    |-----------------------------------------
    */

    public function isSuccess(): bool
    {
        return $this->status === 'success';
    }

    public function isFailed(): bool
    {
        return $this->status === 'failed';
    }
}
