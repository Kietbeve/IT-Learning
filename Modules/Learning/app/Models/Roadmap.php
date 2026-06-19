<?php

namespace Modules\Learning\Models;

use Illuminate\Database\Eloquent\Model;

class Roadmap extends Model
{
    protected $table = 'roadmaps';

    /**
     * Liên kết tới các Chặng học (Bảng roadmap_sections)
     */
    public function sections()
    {
        return $this->hasMany(RoadmapSection::class, 'roadmap_id');
    }

    /**
     * Liên kết trực tiếp tới Bài học (Bảng roadmap_lessons) để phục vụ việc tính toán đếm số bài
     */
    public function lessons()
    {
        return $this->hasMany(RoadmapLesson::class, 'roadmap_id');
    }
}