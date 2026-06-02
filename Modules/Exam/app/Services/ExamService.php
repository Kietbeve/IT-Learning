<?php

namespace Modules\Exam\Services;

use Modules\Exam\Models\Question;

class ExamService
{
    public function __construct(
        //Inject Model vào constructor
        protected Question $questionModel,
    ){}
    
}
