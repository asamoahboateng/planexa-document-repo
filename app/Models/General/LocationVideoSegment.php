<?php

namespace App\Models\General;

use Illuminate\Database\Eloquent\Model;
use Illuminate\Database\Eloquent\Relations\BelongsTo;
use Illuminate\Database\Eloquent\Relations\HasMany;
use Illuminate\Database\Eloquent\SoftDeletes;
use Illuminate\Support\Str;

class LocationVideoSegment extends Model
{
    use SoftDeletes;

    protected $table = 'location_video_segments';

    protected $fillable = [
        'video_id',
        'location_id',
        'video_start',
        'transcript',
        'ai_summary'
    ];

    public static function boot() {
        parent::boot();

        static::creating(function (Model $item) {
            $item->user_id = auth()->id();
        });
    }
    public function video(): BelongsTo
    {
        return $this->belongsTo(MeetingVideo::class, 'video_id');
    }

    public function location(): BelongsTo
    {
        return $this->belongsTo(Location::class);
    }
}
