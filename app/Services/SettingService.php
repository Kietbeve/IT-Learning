<?php

namespace App\Services;

use Illuminate\Support\Facades\Cache;
use Illuminate\Support\Facades\DB;

    class SettingService
    {
        public static function get(string $key, mixed $default = null): mixed
        {
            return Cache::remember(
                "setting.{$key}",
                3600,
                fn () => DB::table('settings')->where('key', $key)->value('value')
            ) ?? $default;
        }

        public static function set(string $key, mixed $value, string $group = 'general'): void
        {
            DB::table('settings')->updateOrInsert(
                ['key' => $key],
                [
                    'value' => (string) $value,
                    'group' => $group,
                    'updated_at' => now(),
                    'created_at' => now(),
                ]
            );

            Cache::forget("setting.{$key}");
        }
    }
