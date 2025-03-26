<?php

namespace App\Models\General;

use Illuminate\Database\Eloquent\Model;
use Illuminate\Database\Eloquent\Relations\BelongsTo;
use Illuminate\Database\Eloquent\Relations\HasMany;
use Illuminate\Database\Eloquent\SoftDeletes;
use Illuminate\Support\Str;

class MeetingVideo extends Model
{
    use SoftDeletes;

    protected $fillable = [
        'url',
        'slug',
        'meeting_id',
        'video_title',
        'video_duration',
        'video_transcript',
        'video_description'
    ];

    public static function boot() {
        parent::boot();

        static::creating(function (Model $item) {
            $item->slug = Str::slug($item->video_title);
            $item->user_id = auth()->id();
        });
    }
    public function meeting(): BelongsTo
    {
        return $this->belongsTo(Meeting::class);
    }

    public function videoSegments(): HasMany
    {
        return $this->hasMany(LocationVideoSegment::class, 'video_id');
    }
}
