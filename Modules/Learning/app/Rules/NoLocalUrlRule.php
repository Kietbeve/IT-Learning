<?php

namespace Modules\Learning\Rules;

use Illuminate\Contracts\Validation\Rule;

class NoLocalUrlRule implements Rule
{
    /**
     * Determine if the validation rule passes.
     *
     * @param  string  $attribute
     * @param  mixed  $value
     * @return bool
     */
    public function passes($attribute, $value)
    {
        if (empty($value)) {
            return true; // Allow empty values (handled by nullable)
        }

        $host = parse_url($value, PHP_URL_HOST);

        if (!$host) {
            return false; // Invalid URL
        }

        // Block localhost variations
        $blockedHosts = [
            'localhost',
            '127.0.0.1',
            '0.0.0.0',
            '::1',
        ];

        if (in_array(strtolower($host), $blockedHosts)) {
            return false;
        }

        // Block private IP ranges
        if (preg_match('/^192\.168\./', $host)) return false;
        if (preg_match('/^10\./', $host)) return false;
        if (preg_match('/^172\.(1[6-9]|2[0-9]|3[0-1])\./', $host)) return false;

        return true;
    }

    /**
     * Get the validation error message.
     *
     * @return string
     */
    public function message()
    {
        return 'URL không được trỏ đến localhost hoặc địa chỉ nội bộ.';
    }
}
