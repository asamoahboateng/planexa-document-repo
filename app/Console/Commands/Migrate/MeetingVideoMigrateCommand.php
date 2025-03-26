<?php

namespace App\Console\Commands\Migrate;

use App\Models\General\Application;
use App\Models\General\Location;
use App\Models\General\Meeting;
use App\Services\UrlDataFetcher;
use Illuminate\Console\Command;
use Illuminate\Support\Facades\Log;

class MeetingVideoMigrateCommand extends Command
{
    /**
     * Migrate the meeting video data.
     *
     * @var string
     */
    protected $signature = 'meeting-video:json-migrate';

    /**
     * The console command description.
     *
     * @var string
     */
    protected $description = 'migration of meeting video data';

    /**
     * Execute the console command.
     */
    public function handle(UrlDataFetcher $dataFetcher)
    {
        try {
            $url = 'http://192.3.155.50/prg/json/youtube_video_list.json';
            $data = $dataFetcher->fetch($url);

            echo(count($data) . ' records have been found.' . PHP_EOL);
            foreach ($data as $video) {
//                echo $video['transcript'] . PHP_EOL;
                $meeting = Meeting::where('name', $video['date'])->first();
                if ($meeting) {
                    $meeting->videos()->create([
                        'url' => $video['url'],
                        'video_title' => $video['title'],
                        'video_duration' => $video['duration'],
                        'video_transcript' => $video['transcript'],
                        'video_description' => $video['description']
                    ]);
                }
            }

        } catch (Exception $e) {
            $this->error('Error: ' . $e->getMessage());
            Log::error('Error fetching JSON: ' . $e->getMessage());
        }
    }
}
