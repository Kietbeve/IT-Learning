<?php

namespace Modules\Learning\Models;

use Illuminate\Database\Eloquent\Model;
use Illuminate\Database\Eloquent\Relations\BelongsTo;
use Illuminate\Database\Eloquent\Relations\HasMany;

use Modules\Exam\Models\Exam;
use Modules\Document\Models\Document;

class RoadmapLesson extends Model
{
    protected $table = 'roadmap_lessons';

    
    protected $fillable = [
        'roadmap_id',
        'section_id',
        'title',
        'slug',
       
        'lesson_type',
        'content',
        'video_url',
        'document_id',
        'exam_id',
        'project_id',
        'is_preview',
        'is_required',
        'is_published',
        'sort_order',
    ];

    protected function casts(): array
    {
        return [
            'is_preview' => 'boolean',
            'is_required' => 'boolean',
            'is_published' => 'boolean',
        ];
    }

    public function roadmap(): BelongsTo
    {
        return $this->belongsTo(Roadmap::class);
    }

    public function section(): BelongsTo
    {
        return $this->belongsTo(RoadmapSection::class, 'section_id');
    }

    public function document(): BelongsTo
    {
        return $this->belongsTo(Document::class, 'document_id');
    }

    public function exam(): BelongsTo
    {
        return $this->belongsTo(Exam::class, 'exam_id');
    }

    public function project(): BelongsTo
    {
        return $this->belongsTo(Project::class, 'project_id');
    }

}
