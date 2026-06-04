<?php

namespace App\Models;

use Illuminate\Database\Eloquent\Model;
use Illuminate\Database\Eloquent\SoftDeletes;
use Illuminate\Database\Eloquent\Relations\BelongsToMany;

use Modules\Exam\Models\Question;
use Modules\Exam\Models\Exam;
class Tag extends Model
{
    use SoftDeletes;

    protected $fillable = [
        'name',
        'slug',
    ];
   public function questions(): BelongsToMany
    {
        return $this->belongsToMany(
            Question::class,
            'question_tag_maps',
            'tag_id',
            'question_id'
        );
    }

    public function exams(): BelongsToMany
    {
        return $this->belongsToMany(
            Exam::class,
            'exam_tag_maps',
            'tag_id',
            'exam_id'
        );
    }
}