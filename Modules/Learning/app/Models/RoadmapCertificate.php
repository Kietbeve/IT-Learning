<?php

namespace Modules\Learning\Models;

use Illuminate\Database\Eloquent\Model;
use Illuminate\Database\Eloquent\Relations\BelongsTo;
use Modules\Auth\Models\User;
use Illuminate\Support\Str;

class RoadmapCertificate extends Model
{
    protected $table = 'roadmap_certificates';

    protected $fillable = [
        'certificate_number',
        'user_id',
        'roadmap_id',
        'user_name',
        'roadmap_title',
        'final_score',
        'completion_percent',
        'total_hours',
        'issued_at',
        'expires_at',
        'certificate_path',
        'verification_code',
        'metadata',
        'status',
        'revoked_reason',
        'revoked_at',
    ];

    protected function casts(): array
    {
        return [
            'final_score' => 'decimal:2',
            'completion_percent' => 'decimal:2',
            'issued_at' => 'datetime',
            'expires_at' => 'datetime',
            'revoked_at' => 'datetime',
            'metadata' => 'array',
        ];
    }

    /**
     * User nhận certificate
     */
    public function user(): BelongsTo
    {
        return $this->belongsTo(User::class);
    }

    /**
     * Roadmap được cấp certificate
     */
    public function roadmap(): BelongsTo
    {
        return $this->belongsTo(Roadmap::class);
    }

    /**
     * Generate certificate number
     */
    public static function generateCertificateNumber(): string
    {
        return 'CERT-' . strtoupper(Str::random(10)) . '-' . date('Y');
    }

    /**
     * Generate verification code
     */
    public static function generateVerificationCode(): string
    {
        return strtoupper(Str::random(16));
    }

    /**
     * Thu hồi certificate
     */
    public function revoke(string $reason): void
    {
        $this->status = 'revoked';
        $this->revoked_reason = $reason;
        $this->revoked_at = now();
        $this->save();
    }

    /**
     * Kiểm tra certificate còn hiệu lực không
     */
    public function isValid(): bool
    {
        if ($this->status !== 'active') {
            return false;
        }

        if ($this->expires_at && $this->expires_at->isPast()) {
            return false;
        }

        return true;
    }

    /**
     * Scope - active certificates
     */
    public function scopeActive($query)
    {
        return $query->where('status', 'active');
    }

    /**
     * Scope - expired certificates
     */
    public function scopeExpired($query)
    {
        return $query->where('expires_at', '<', now());
    }

    /**
     * Get public verification URL
     */
    public function getVerificationUrlAttribute(): string
    {
        return url('/verify-certificate/' . $this->verification_code);
    }
}
