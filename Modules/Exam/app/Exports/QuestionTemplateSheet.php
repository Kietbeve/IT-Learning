<?php

namespace Modules\Exam\Exports;

use Maatwebsite\Excel\Concerns\FromArray;
use Maatwebsite\Excel\Concerns\WithEvents;
use Maatwebsite\Excel\Events\AfterSheet;
use PhpOffice\PhpSpreadsheet\Cell\DataValidation;

class QuestionTemplateSheet implements FromArray, WithEvents
{
    public function array(): array
    {
        return [
            [
                'category',
                'content',
                'explanation',
                'difficulty',
                'type',
                'answer_text',
                'options',
            ],

            [
                'Tin học đại cương',
                'CPU là gì?',
                'CPU là bộ xử lý trung tâm',
                'medium',
                'single_choice',
                '',
                'RAM;+CPU',
            ],

            [
                'Tin học đại cương',
                'Thiết bị nhập dữ liệu?',
                '',
                'medium',
                'multiple_choice',
                '',
                '+Bàn phím;+Chuột;Màn hình',
            ],

            [
                'Tin học đại cương',
                'Trình bày thuật toán',
                '',
                'medium',
                'essay',
                'Thuật toán là tập hữu hạn các bước...',
                '',
            ],
        ];
    }

    public function registerEvents(): array
    {
        return [
            AfterSheet::class => function (AfterSheet $event) {

                $sheet = $event->sheet->getDelegate();

                // category (cột A) - dropdown từ sheet Categories
                for ($row = 2; $row <= 1000; $row++) {

                    $validation = $sheet
                        ->getCell("A{$row}")
                        ->getDataValidation();

                    $validation->setType(
                        DataValidation::TYPE_LIST
                    );

                    $validation->setErrorStyle(
                        DataValidation::STYLE_STOP
                    );

                    $validation->setAllowBlank(false);

                    $validation->setShowDropDown(true);

                    $validation->setFormula1(
                        'Categories!$A$2:$A$1000'
                    );
                }

                // difficulty (cột D)
                for ($row = 2; $row <= 1000; $row++) {

                    $validation = $sheet
                        ->getCell("D{$row}")
                        ->getDataValidation();

                    $validation->setType(
                        DataValidation::TYPE_LIST
                    );

                    $validation->setErrorStyle(
                        DataValidation::STYLE_STOP
                    );

                    $validation->setAllowBlank(false);

                    $validation->setShowDropDown(true);

                    $validation->setFormula1(
                        '"easy,medium,hard"'
                    );
                }

                // type (cột E)
                for ($row = 2; $row <= 1000; $row++) {

                    $validation = $sheet
                        ->getCell("E{$row}")
                        ->getDataValidation();

                    $validation->setType(
                        DataValidation::TYPE_LIST
                    );

                    $validation->setErrorStyle(
                        DataValidation::STYLE_STOP
                    );

                    $validation->setAllowBlank(false);

                    $validation->setShowDropDown(true);

                    $validation->setFormula1(
                        '"single_choice,multiple_choice,essay"'
                    );
                }

                // Auto width
                foreach (range('A', 'G') as $column) {
                    $sheet
                        ->getColumnDimension($column)
                        ->setAutoSize(true);
                }
            },
        ];
    }
}