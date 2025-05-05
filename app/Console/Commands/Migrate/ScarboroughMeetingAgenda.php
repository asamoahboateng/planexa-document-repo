<?php

namespace App\Console\Commands\Migrate;

use App\Models\General\Application;
use App\Models\General\Location;
use App\Models\General\Meeting;
use App\Models\User;
use GuzzleHttp\Client;
use GuzzleHttp\Exception\ConnectException;
use GuzzleHttp\Exception\RequestException;
use Illuminate\Console\Command;
use Illuminate\Support\Facades\Log;
use DOMDocument;
use DOMXPath;
use Carbon\Carbon;

class ScarboroughMeetingAgenda extends Command
{
    /**
     * The name and signature of the console command.
     *
     * @var string
     */
    protected $signature = 'meeting:scarborough-agenda {url? :  url of the json}';

    /**
     * The console command description.
     *
     * @var string
     */
    protected $description = 'Get All the meeting Agendas';

    /**
    * Execute the console command.
    */
    public function handle()
    {
        try {
            $url = $this->argument('url');
            if(!isset($url)){
                $url = "https://static.asamoahboateng.com/jsons/ex_json/j_scb_extracted_meetings_20250501_151537.json";
            }
            $data = $this->fetchJsonFromRemote($url);

            if(count($data)) {
                foreach ($data as $meeting_json) {
//                    echo $meeting_json['date'].PHP_EOL;
                    $meeting = Meeting::where('name', $meeting_json['date'])->first();

                    if(isset($meeting->id)) {
                        $location = Location::where('location', 'like', ucwords($meeting_json['address']).'%')->first();
//                        echo '--'.$meeting_json['address']. PHP_EOL;
                        if(isset($location->id)) {
                            $this->info("Application for meeting: ". $meeting->name .' for location: '. $location->location );
                            Application::firstOrCreate([
                                'meeting_id' => $meeting->id,
                                'location_id'   => $location->id,
                                'file_number'   => $meeting_json['file_number']
                            ]);
                        }

                    }
                }
            }
        }
        catch (\Exception $e) {
            $this->error('Error: ' . $e->getMessage());
            Log::error('Error processing URL: ' . $e->getMessage());
            return [];
        }
    }

    protected function fetchJsonFromRemote($url)
    {
        $client = new Client();

        try {
            $response = $client->get($url);
            $json = $response->getBody()->getContents();

            $data = json_decode($json, true);

            if (json_last_error() !== JSON_ERROR_NONE) {
                $errorMessage = "Error decoding JSON data: " . json_last_error_msg();
                $this->error($errorMessage);
                Log::error($errorMessage);
                return null;
            }

            return $data;
        } catch (ConnectException $e) {
            $errorMessage = "Connection error: " . $e->getMessage();
            $this->error($errorMessage);
            Log::error($errorMessage);
            return null;
        } catch (RequestException $e) {
            $errorMessage = "HTTP request error: " . $e->getMessage();
            $this->error($errorMessage);
            Log::error($errorMessage);
            return null;
        }
    }
}
