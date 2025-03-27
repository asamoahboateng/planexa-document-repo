<?php

namespace App\Models\General;

use Illuminate\Database\Eloquent\Model;
use Illuminate\Database\Eloquent\SoftDeletes;
use Illuminate\Support\Str;
use Illuminate\Database\Eloquent\Relations\BelongsTo;
use Illuminate\Database\Eloquent\Relations\HasMany;

class Location extends Model
{
    use SoftDeletes;
    protected $fillable = [
        'slug',
        'location',
        'old_address',
        'province',
        'ward',
        'user_id',
        'lat',
        'long',
        'postal_code',
    ];

    public static function boot() {
        parent::boot();

        static::creating(function (Model $item) {
            $item->slug = Str::slug($item->location);
            $item->user_id = auth()->id();
        });
    }

    public function applications(): HasMany
    {
        return $this->hasMany(Application::class , 'location_id');
    }

    public function videoSegments(): HasMany
    {
        return $this->hasMany(LocationVideoSegment::class , 'location_id');
    }

    public function searchHistory(): HasMany
    {
        return $this->hasMany(SearchHistory::class, 'location_id');
    }
}
