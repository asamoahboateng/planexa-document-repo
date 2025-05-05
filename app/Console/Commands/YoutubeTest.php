<?php

namespace App\Console\Commands;

use App\Models\General\Meeting;
use Illuminate\Console\Command;
use Illuminate\Support\Facades\Http;

class YoutubeTest extends Command
{
    /**
     * The name and signature of the console command.
     *
     * @var string
     */
    protected $signature = 'app:youtube-test';

    /**
     * The console command description.
     *
     * @var string
     */
    protected $description = 'Testing Youtube scrape';

    /**
     * Execute the console command.
     */
    public function handle()
    {
//        https://www.youtube.com/playlist?list=PL7fmmGTWt9qcSl0vq1em46vkVAS93aySw
        // 2024 = https://www.youtube.com/playlist?list=PL7fmmGTWt9qcSl0vq1em46vkVAS93aySw
        // 2025 = https://www.youtube.com/playlist?list=PL7fmmGTWt9qdB-TJU7c53j4Q81pYuAJ84
        $response = Http::get("https://www.youtube.com/playlist?list=PL7fmmGTWt9qcSl0vq1em46vkVAS93aySw");

        preg_match_all('/"videoId":"(.*?)"/', $response, $matches);

        $videoIds = array_unique($matches[1]);
        $count = 1;
        foreach ($videoIds as $id) {
            $videID = "https://www.youtube.com/watch?v=".$id;
            echo $count . ' '. $videID .PHP_EOL;

            $response = Http::get("https://www.youtube.com/oembed", [
                'url' => $videID,
                'format' => 'json',
            ]);

            $data = $response->json();
            $meetngDate = $this->fetchDate($data['title']);
            echo $data['title'].  ' - :'. $this->fetchDate($data['title']). PHP_EOL; // etc.
            $meeting = Meeting::where('name', $meetngDate)->where('district', 'Scarborough')->first();
            if ($meeting) {
                echo '--'.$meeting->id.'--';
                $meeting->videos()->firstOrCreate([
                    'url' => $videID,
                ], [
                    'video_title' => $data['title'],
                    'video_duration' => '100',
                    'video_transcript' => null,
                    'video_description' => null
                ]);
            }
            $count++;
        }
    }

    protected function fetchDate($title)
    {
        if (preg_match('/\b(January|February|March|April|May|June|July|August|September|October|November|December)\s+\d{1,2},\s+\d{4}\b/', $title, $matches)) {
            $date = $matches[0];
            return $date; // Outputs: January 23, 2025
        }
    }
}
