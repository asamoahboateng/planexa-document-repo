<?php

namespace App\Models\General;

use Illuminate\Database\Eloquent\Model;
use Illuminate\Database\Eloquent\Relations\BelongsTo;
use Illuminate\Database\Eloquent\Relations\HasMany;
use Illuminate\Database\Eloquent\SoftDeletes;
use Illuminate\Support\Str;
use App\Services\UrlDataFetcher;

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

    public function embeddedurl(): string
    {
        $exploded_url = explode("=",$this->url);
        $video_id = end($exploded_url);
        $newUrl = "https://www.youtube-nocookie.com/embed/".$video_id."?start=0&rel=0";

        return html_entity_decode($newUrl);
    }

    public function fulltranscriptUrl()
    {
        if(!isset($this->video_transcript)){
            return [];
        }
        return 'http://192.3.155.50/prg/'.$this->video_transcript;
    }
    public function fetchvideotranscript(): array
    {
        $fetcher = new UrlDataFetcher();
        if(!isset($this->video_transcript)){
            return [];
        }
        $transcript = $fetcher->fetch($this->fulltranscriptUrl());

//        dd($transcript);
        return $transcript;
    }

    public function update_video_time($start_time): string
    {
        $url = $this->embeddedurl();

        // Parse the URL
        $parsedUrl = parse_url($url);

        $queryParams = [];

        if (isset($parsedUrl['query'])) {
            parse_str($parsedUrl['query'], $queryParams);
        }

        // Parse query parameters
        parse_str($parsedUrl['query'], $queryParams);

        // Set the new start value
        $queryParams['start'] = floor($start_time); // Set to your desired value
        $queryParams['autoplay'] = 1; // Enable autoplay

        // Build the new query string
        $newQuery = http_build_query($queryParams);

        // Construct the new URL
        $newUrl = $parsedUrl['scheme'] . "://" . $parsedUrl['host'] . $parsedUrl['path'] . "?" . $newQuery;

        //echo "Updated URL: " . $newUrl;

        return  $newUrl;
    }
}
