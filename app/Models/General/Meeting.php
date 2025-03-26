<?php

namespace App\Models\General;

use App\Models\User;
use Illuminate\Database\Eloquent\Model;
use Illuminate\Database\Eloquent\Relations\BelongsTo;
use Illuminate\Database\Eloquent\Relations\HasMany;
use Illuminate\Database\Eloquent\SoftDeletes;
use Illuminate\Support\Str;

class Meeting extends Model
{
    use SoftDeletes;
    protected $fillable = [
        'url',
        'slug',
        'name',
        'date',
        'governing_committee',
        'hearing_time',
        'hearing_location',
        'district',
        'updated_by'
    ];

    public static function boot() {
        parent::boot();

        static::creating(function (Model $item) {
            $item->slug = Str::slug($item->date . ' '. $item->district);
            $item->user_id = auth()->id();
        });

        static::updating(function (Model $item) {
           $item->updated_by = auth()->id();
        });
    }
    public function videos(): HasMany
    {
        return $this->hasMany(MeetingVideo::class);
    }

    public function applications(): HasMany
    {
        return $this->hasMany(Application::class);
    }

    public function createdby(): BelongsTo
    {
        return $this->belongsTo(User::class, 'user_id');
    }

    public function updatedby(): BelongsTo
    {
        return $this->belongsTo(User::class, 'updated_by');
    }
}
