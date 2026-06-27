<script src="https://cdnjs.cloudflare.com/ajax/libs/pdf.js/3.11.174/pdf.min.js"></script>
use Illuminate\Database\Eloquent\Relations\BelongsToMany;
use Modules\Learning\Models\Roadmap;
public function roadmaps(): BelongsToMany
{
    return $this->belongsToMany(
        Roadmap::class,
        'roadmap_enrollments',
        'user_id',
        'roadmap_id'
    )
    ->withPivot([
        'status',
        'progress_percent',
        'started_at'
    ]);
}