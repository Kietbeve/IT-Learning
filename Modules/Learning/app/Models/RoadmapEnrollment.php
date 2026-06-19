<?php

namespace Modules\Learning\Models;

use Illuminate\Database\Eloquent\Model;

class RoadmapEnrollment extends Model
{
    // Khai báo các trường được phép ghi dữ liệu vào Database
    protected $fillable = [
        'user_id',
        'roadmap_id',
        'status',
        'progress_percent',
        'started_at',
        'completed_at'
    ];

    // Định nghĩa mối quan hệ với bảng Roadmap
    public function roadmap()
    {
        return $this->belongsTo(Roadmap::class);
    }
}