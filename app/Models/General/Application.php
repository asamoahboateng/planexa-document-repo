<?php

namespace App\Models\General;

use App\Services\AnalyzerTwo;
use Illuminate\Database\Eloquent\Model;
use Illuminate\Database\Eloquent\Relations\BelongsTo;
use Illuminate\Database\Eloquent\Relations\HasMany;
use Illuminate\Database\Eloquent\SoftDeletes;
use Illuminate\Support\Str;
use App\Services\TranscriptAnalyzer;

class Application extends Model
{
    use SoftDeletes;

    protected $table = 'applications';

    protected $fillable = [
        'location_id',
        'meeting_id',
        'title',
        'slug',
        'file_number',
        'application_number',
        'related_application',
        'type','url',
        'status',
        'description'
    ];

    public static function boot() {
        parent::boot();

        static::creating(function (Model $item) {
            $item->slug = Str::slug($item->title);
            $item->user_id = auth()->id();
        });
    }

    public function location(): BelongsTo
    {
        return $this->belongsTo(Location::class, 'location_id');
    }

    public function meeting(): BelongsTo
    {
        return $this->belongsTo(Meeting::class, 'meeting_id');
    }

    public function applicationVideo(): array
    {
        $location = $this->location;

        $location_address = explode(' ', $location->location);
        unset($location_address[0]);
        array_pop($location_address);
        $newLocation = implode(' ', $location_address);

        $meetings = $this->meeting;
        $matches = [];
        foreach ($meetings->videos as $video) {
            // fetch transcript
            $meetingsTanscript = $video->fetchvideotranscript();
            foreach ($meetingsTanscript as $segment) {
                if (stripos($segment['text'], $newLocation) !== false) {
                    $matches[] = [
                        'video_id' => $video->id,
                        'timestamp' => $segment['start'],
                        'duration' => $segment['duration'],
                        'text' => $segment['text']
                    ];
                }
            }
//            dd([$newLocation, $matches]);
            // search for name in transript

        }
        if(count($matches)) {
            $firstMathc = reset($matches);
            $video = MeetingVideo::find($firstMathc['video_id']);
            return [
                'video' => $video,
                'timestamp' => floor($firstMathc['timestamp']),
                'updated_video' => $video->update_video_time(floor($firstMathc['timestamp']))
            ];
        }

        $video = MeetingVideo::find($video->id);
        return [
            'video' => $video,
            'timestamp' => floor('0.00'),
            'updated_video' => $video->embeddedurl()
        ];
//        return [];
    }

    public function ai_summary()
    {
//        $ai_transcitp = new TranscriptAnalyzer();

//        $result = $ai_transcitp->analyzeTranscript($this);


        $transripAi = new AnalyzerTwo($this);

        $result = $transripAi->analyze();

//        $result = $ai_transcitp->testConnection($this);

        return $result;

    }
}
