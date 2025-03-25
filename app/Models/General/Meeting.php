<?php

namespace App\Models\General;

use Illuminate\Database\Eloquent\Model;
use Illuminate\Database\Eloquent\Relations\HasMany;
use Illuminate\Database\Eloquent\SoftDeletes;
use Illuminate\Support\Str;

class Meeting extends Model
{
    use SoftDeletes;
    protected $fillable = [
        'slug',
        'date',
        'governing_committee',
        'district'
    ];

    public static function boot() {
        parent::boot();

        static::creating(function (Model $item) {
            $item->slug = Str::slug($item->date . ' '. $item->district);
            $item->user_id = auth()->id();
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
}
