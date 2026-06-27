<?php

namespace Modules\Exam\Exports;

use App\Models\Category;
use Maatwebsite\Excel\Concerns\FromArray;
use Maatwebsite\Excel\Concerns\WithTitle;

class CategorySheet implements FromArray, WithTitle
{
    public function array(): array
    {
        $rows = [
            ['Tên danh mục'],
        ];

        Category::query()
            ->orderBy('name')
            ->pluck('name')
            ->each(function ($name) use (&$rows) {

                $rows[] = [$name];
            });

        return $rows;
    }

    public function title(): string
    {
        return 'Categories';
    }
}