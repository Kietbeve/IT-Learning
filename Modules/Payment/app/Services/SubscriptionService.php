<?php

namespace Modules\Payment\Services;

use Modules\Auth\Models\User;
use Carbon\Carbon;

class SubscriptionService
{
    /**
     * Activate VIP subscription for a user
     */
    public function activateVip(User $user, string $packageKey): void
    {
        $package = config("subscription.packages.{$packageKey}");
        
        if (!$package) {
            throw new \InvalidArgumentException("Invalid package key: {$packageKey}");
        }

        $expiresAt = $user->vip_expires_at && $user->vip_expires_at->isFuture()
            ? $user->vip_expires_at->addDays($package['duration_days'])
            : now()->addDays($package['duration_days']);

        $user->update([
            'vip_expires_at' => $expiresAt,
            'vip_download_quota' => $user->vip_download_quota + $package['download_quota'],
        ]);
    }

    /**
     * Check if user's VIP is active
     */
    public function isVipActive(User $user): bool
    {
        return $user->vip_expires_at && $user->vip_expires_at->isFuture();
    }

    /**
     * Get VIP status details for a user
     */
    public function getVipStatus(User $user): array
    {
        $isActive = $this->isVipActive($user);
        
        return [
            'is_active' => $isActive,
            'expires_at' => $user->vip_expires_at,
            'quota_remaining' => $user->vip_download_quota,
            'days_remaining' => $isActive ? now()->diffInDays($user->vip_expires_at, false) : 0,
        ];
    }

    /**
     * Decrease download quota for VIP user
     */
    public function decreaseQuota(User $user): bool
    {
        if (!$this->isVipActive($user)) {
            return false;
        }

        if ($user->vip_download_quota <= 0) {
            return false;
        }

        $user->decrement('vip_download_quota');
        
        return true;
    }

    /**
     * Check if user can download premium document
     */
    public function canDownloadPremium(User $user): bool
    {
        if (!$this->isVipActive($user)) {
            return false;
        }

        return $user->vip_download_quota > 0;
    }

    /**
     * Get all available packages
     */
    public function getPackages(): array
    {
        return config('subscription.packages', []);
    }

    /**
     * Get a specific package by key
     */
    public function getPackage(string $packageKey): ?array
    {
        return config("subscription.packages.{$packageKey}");
    }

    /**
     * Calculate final price for a package
     */
    public function getFinalPrice(string $packageKey): int
    {
        $package = $this->getPackage($packageKey);
        
        if (!$package) {
            throw new \InvalidArgumentException("Invalid package key: {$packageKey}");
        }

        return $package['sale_price'] ?? $package['price'];
    }
}
