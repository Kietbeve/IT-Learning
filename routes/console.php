<?php

use Illuminate\Foundation\Inspiring;
use Illuminate\Support\Facades\Artisan;
use Illuminate\Support\Facades\Schedule;

Artisan::command('inspire', function () {
    $this->comment(Inspiring::quote());
})->purpose('Display an inspiring quote')->hourly();

Schedule::command('vip:send-expiry-reminders')->dailyAt('09:00');

Schedule::call(function () {
    $disk = Illuminate\Support\Facades\Storage::disk('local');
    $files = $disk->files('livewire-tmp');
    $now = \Carbon\Carbon::now();

    foreach ($files as $file) {
        $lastModified = \Carbon\Carbon::createFromTimestamp($disk->lastModified($file));
        if ($now->diffInHours($lastModified) >= 24) {
            $disk->delete($file);
        }
    }
})->dailyAt('02:00')->description('Xóa các file rác trong livewire-tmp cũ hơn 24 giờ');
