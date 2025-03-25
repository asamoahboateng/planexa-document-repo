<?php

namespace App\Models\General;

use Illuminate\Database\Eloquent\Model;
use Illuminate\Database\Eloquent\Relations\BelongsTo;
use Illuminate\Database\Eloquent\Relations\HasMany;
use Illuminate\Database\Eloquent\SoftDeletes;
use Illuminate\Support\Str;

class SearchHistory extends Model
{
    use SoftDeletes;

    protected $fillable = [
        'location',
        'created_at',
        'user',
        'location_id'
    ];

    public static function boot() {
        parent::boot();

        static::creating(function (Model $item) {
            $item->user_id = auth()->id();
        });
    }


    public function location(): BelongsTo
    {
        return $this->belongsTo(Location::class);
    }
}
