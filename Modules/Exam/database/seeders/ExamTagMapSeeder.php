<?php

namespace Modules\Exam\database\seeders;
use Modules\Exam\Models\Exam;
use App\Models\Tag;
use Illuminate\Database\Console\Seeds\WithoutModelEvents;
use Illuminate\Database\Seeder;

class ExamTagMapSeeder extends Seeder
{
    /**
     * Run the database seeds.
     */
    public function run(): void
    {
        //Tim id bai kiem tra = 1
        $exam1 = Exam::find(1);
        //Gan tag co id la 5, 6
        $exam1->tags()->sync([5, 6]);

        $exam3 = Exam::find(3);
        $exam3->tags()->sync([7 ]);
    }
}



