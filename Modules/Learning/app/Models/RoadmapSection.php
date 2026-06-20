<?php

namespace Modules\Learning\Models;

use Illuminate\Database\Eloquent\Model;

class RoadmapSection extends Model
{
    protected $table = 'roadmap_sections';

    // Một chặng thì có nhiều bài học
    public function lessons()
    {
        return $this->hasMany(RoadmapLesson::class, 'section_id');
    }
}