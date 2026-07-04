<?php

namespace Modules\Learning\Models;

use Illuminate\Database\Eloquent\Model;

class RoadmapSection extends Model
{
    protected $table = 'roadmap_sections';

    // Đổi $guarded thành $fillable
    protected $fillable = [
        'roadmap_id',
        'title', 
        'sort_order'
    ];

    public function lessons()
    {
        return $this->hasMany(RoadmapLesson::class, 'section_id');
    }
}