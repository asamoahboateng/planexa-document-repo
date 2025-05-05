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
use PHPUnit\Event\Runtime\PHP;

class TestAgendaCommand extends Command
{
    /**
     * The name and signature of the console command.
     *
     * @var string
     */
    protected $signature = 'test:agenda {url? :  url of the json}';

    /**
     * The console command description.
     *
     * @var string
     */
    protected $description = 'Test Agenda';

    /**
     * Execute the console command.
     */
    public function handle()
    {
        try {
            $url = $this->argument('url');
            if (!isset($url)){
                $url = "https://static.asamoahboateng.com/jsons/meeting_data_json/";
            }

            // Validate URL
            if (!filter_var($url, FILTER_VALIDATE_URL)) {
                throw new \Exception('Invalid URL provided');
            }

            $client = new Client([
                'verify' => false, // Skip SSL verification for local URLs
                'timeout' => 30,
                'connect_timeout' => 30
            ]);

            $this->info('Fetching URL: ' . $url);

            $response = $client->get($url);
            $statusCode = $response->getStatusCode();

            if ($statusCode !== 200) {
                throw new \Exception("HTTP request failed with status code: {$statusCode}");
            }

            $html = $response->getBody()->getContents();

            // Create DOM document with error handling
            $dom = new \DOMDocument();
            libxml_use_internal_errors(true);
            $dom->loadHTML($html, LIBXML_NOERROR);
            libxml_clear_errors();

            $xpath = new \DOMXPath($dom);
            $links = $xpath->query('//a');
            $jsonLinks = [];

            if (!$links) {
                throw new \Exception('No links found in the HTML document');
            }

            foreach ($links as $link) {
                $href = $link->getAttribute('href');
                if (str_ends_with(strtolower($href), '.json')) {
                    if (!filter_var($href, FILTER_VALIDATE_URL)) {
                        $href = rtrim($url, '/') . '/' . ltrim($href, '/');
                    }
                    $jsonLinks[] = $href;
                    $this->info('Found JSON link: ' . $href);

                    $meeting_information = $this->fetchJsonFromRemote($href);
                    $meetingAddress = $meeting_information['address'];
                    $meetingTitle = $meeting_information['title'];
                    $meetingFileNumner = $meeting_information['application_info']['File Number'] ?? null;

//                    $this->info('Json address: '. $meetingAddress);
//                    $this->info('Json title: '. $meetingTitle);
//                    $this->info('Json FileNumber: '. $meetingFileNumner);
//                    $this->info('----');
                    if ($meetingFileNumner != null){
                        $this->info('Json address: '. $meetingAddress);
                        $this->info('Json title: '. $meetingTitle);
                        $this->info('Json FileNumber: '. $meetingFileNumner);
                        $this->info('----');
                        $app_count = Application::where('file_number','like' ,'%'.$meetingFileNumner.'%')->get();
                        $this->info('==>> Application founds: '. count($app_count));

                        if(count($app_count) > 0) {
                            $applicationList = Application::where('file_number','like' ,'%'.$meetingFileNumner.'%')
                                ->where('location_id' ,'like', Location::where('location', 'like', $meetingAddress.'%')->pluck('id')->toArray())
                                ->pluck('id')->toArray();

                            Application::whereIn('id', $applicationList)->update([
                                'title'                 => $meeting_information['title'],
                                'application_number'    => $meeting_information['application_info']['Application Number'],
                                'related_application'   => $meeting_information['related_applications'][0],
                                'type'          => $meeting_information['application_info']['Type'],
                                'url'           => $meeting_information['url'],
                                'status'        => $meeting_information['application_info']['Milestone Status'],
                                'description'   => $meeting_information['description']
                                ]);
                        }
                    }
//                    $searchLocation = Location::where('location', 'like' , ucwords($meetingAddress).'%')->get();
//                    if(count($searchLocation) > 0 ) {
//                        if (isset($meeting_information['community_meetings'])) {
//
//
//                            if (array_key_exists('Date', $meeting_information['community_meetings'])) {
//                                $this->info(' Application Meeting Date : '. $meeting_information['community_meetings']['Date']);
//
//                                $shortDate = $meeting_information['community_meetings']['Date'];
//                                // Parse the short month date format
//                                $date = Carbon::createFromFormat('M d, Y', $shortDate);
//
//                                // Format with full month name
//                                $longDate = $date->format('F d, Y');
//
//                                $searchMeeting = Meeting::where('name', 'like',$longDate)->get();
//                                $this->info('Meetings Found are: ' . count($searchMeeting) .' '. $longDate);
//                                if (count($searchMeeting) > 0){
////                                    dd($searchMeeting->first());
//                                    Application::firstOrCreate([
//                                        'location_id'   => $searchLocation->first()->id,
//                                        'meeting_id'    => $searchMeeting->first()->id,
//                                        'title'         => $meeting_information['title']
//                                    ],[
//                                        'file_number'   => $meeting_information['application_info']['File Number'],
//                                        'application_number'    => $meeting_information['application_info']['Application Number'],
//                                        'related_application'   => $meeting_information['related_applications'][0],
//                                        'type'          => $meeting_information['application_info']['Type'],
//                                        'url'           => $meeting_information['url'],
//                                        'status'        => $meeting_information['application_info']['Milestone Status'],
//                                        'description'   => $meeting_information['description']
//                                    ]);
//                                    $this->info("Migrating Application to ". $searchMeeting->first()->name .' for location '. $searchLocation->first()->location );
//                                }
//                            }
//                        }
////                        $this->info('Title here is :' . $meeting_information['address']);
////                        $this->info('Location Found:' . count($searchLocation));
//                        $this->info('-------');
//
//                    }
                }
            }

            if (empty($jsonLinks)) {
                $this->warn('No JSON files found at the provided URL');
                return [];
            }

            $this->info('Total JSON files found: ' . count($jsonLinks));
            return $jsonLinks;

        } catch (\Exception $e) {
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
