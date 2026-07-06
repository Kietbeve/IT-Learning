<?php

namespace App\Models;

use Illuminate\Database\Eloquent\Model;
use Illuminate\Database\Eloquent\SoftDeletes;
use Illuminate\Database\Eloquent\Relations\BelongsToMany;

use Modules\Exam\Models\Question;
use Modules\Exam\Models\Exam;
use Modules\Document\Models\Document;
use Illuminate\Support\Str;

class Tag extends Model
{
    use SoftDeletes;

    protected $fillable = [
        'name',
        'slug',
    ];
   
    /**
     * Quan hệ với Questions (câu hỏi)
     */
    public function questions(): BelongsToMany
    {
        return $this->belongsToMany(
            Question::class,
            'question_tag_maps',
            'tag_id',
            'question_id'
        );
    }

    /**
     * Quan hệ với Exams (đề thi)
     */
    public function exams(): BelongsToMany
    {
        return $this->belongsToMany(
            Exam::class,
            'exam_tag_maps',
            'tag_id',
            'exam_id'
        );
    }

    /**
     * Quan hệ với Documents (tài liệu)
     */
    public function documents(): BelongsToMany
    {
        return $this->belongsToMany(
            Document::class,
            'document_tag_maps',
            'tag_id',
            'document_id'
        );
    }

    protected static function generateUniqueSlug(string $title): string
    {
        $slug = Str::slug($title);
        $originalSlug = $slug;
        $counter = 1;

        while (
            static::where('slug', $slug)->exists()
        ) {
            $slug = $originalSlug . '-' . $counter;
            $counter++;
        }

        return $slug;
    }
}