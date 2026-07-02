<?php

namespace Modules\Learning\Services;

class ProfanityFilter
{
    protected array $blacklist = [
        'fuck', 'shit', 'damn', 'ass', 'bitch', 'dick', 'cock', 'cunt',
        'địt', 'đụ', 'lồn', 'buồi', 'chó', 'đĩ', 'mẹ mày', 'cặc',
        'loz', 'lon', 'buoi', 'dit', 'du', 'cc', 'vl', 'vkl', 'dm',
        'dkm', 'dmm', 'cl', 'clgt', 'đmm', 'đcm', 'dcm', 'vc',
    ];

    protected string $replacement = '***';

    public function filter(string $text): string
    {
        $pattern = '/\b(' . implode('|', array_map('preg_quote', $this->blacklist)) . ')\b/i';

        $text = preg_replace($pattern, $this->replacement, $text);

        // Also catch without word boundaries for Vietnamese short forms
        foreach ($this->blacklist as $word) {
            if (strlen($word) <= 3) {
                $text = preg_replace('/' . preg_quote($word) . '/i', $this->replacement, $text);
            }
        }

        return $text;
    }

    public function addWords(array $words): void
    {
        $this->blacklist = array_merge($this->blacklist, $words);
    }

    public function setReplacement(string $replacement): void
    {
        $this->replacement = $replacement;
    }
}
