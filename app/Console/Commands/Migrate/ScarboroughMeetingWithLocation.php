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

class ScarboroughMeetingWithLocation extends Command
{
    /**
     * Scarborough Meeting Migration.
     *
     * @var string
     */
    protected $signature = 'meeting:scarborough-migrate {url :  url of the json}';

    /**
     * The console command description.
     *
     * @var string
     */
    protected $description = 'fetch the scarborough meetings with the locations';

    /**
     * Execute the console command.
     */
    public function handle()
    {
        // Example usage
        try {
            $meetingDetails = [
              'id'          => 'scb',
              'location'    => 'Scarborough',
              'province'    => 'ON',
              'authority'   => 'City of Toronto',
              'city'        => 'Toronto'
            ];
            $url = $this->argument('url');
            $data = $this->fetchJsonFromRemote($url);

            if(count($data)) {
                foreach ($data as $meeting) {
//                    echo $meeting['date']. PHP_EOL;
                    // create meeting
                    $singleMeeting = Meeting::firstOrCreate([
                        'url' => $meeting['meeting_link'],
                        'name' => $meeting['date'],
                        'date' => date('Y-m-d', strtotime($meeting['date'])),
                        'governing_committee' => $meetingDetails['authority'],
                        'district' => $meetingDetails['location'],
                    ],[
                        'hearing_time' => null,
                        'hearing_location' => null,
                        'user_id' => User::where('email','seed@mail.com')->first()->id
                    ]);

                    // creating the locations associated with the meeting
                    $singleLocation = Location::firstOrCreate([
                        'location' => $meeting['address'],
                        'province' => $meetingDetails['province'],
                        'ward' => $meeting['ward'],
                    ],[
                        'user_id' => User::where('email','seed@mail.com')->first()->id
                    ]);
                }
//                $innerData = reset($data);
//                $govCommittee = $innerData['authority'];
//                $location = $innerData['location'];
//
//                if (array_key_exists("meetings", $innerData)) {
//                    foreach ($innerData["meetings"] as $meeting) {
//
//                        // migrate the meeting data into the database
//                        $singleMeeting = Meeting::firstOrCreate([
//                            'url' => $meeting['url'],
//                            'name' => $meeting['date'],
//                            'date' => date('Y-m-d', strtotime($meeting['date'])),
//                            'governing_committee' => $govCommittee,
//                            'hearing_time' => $meeting['hearing_details']['Time'],
//                            'hearing_location' => $meeting['hearing_details']['Location'],
//                            'district' => $location,
//                        ],[
//                            'user_id' => User::where('email','seed@mail.com')->first()->id
//                        ]);
//
//                        // migration a location address
//                        if (array_key_exists("locations_address", $meeting)) {
//                            foreach ($meeting['locations_address'] as $index => $address) {
//
//                                $counting = $index;
//                                // locations
//                                $singleLocation = Location::firstOrCreate([
//                                    'location' => $address['address'],
//                                    'province' => $innerData['province'],
//                                    'ward' => $address['ward'],
//                                ],[
//                                    'user_id' => User::where('email','seed@mail.com')->first()->id
//                                ]);
//
//                                $locationUrl = $meeting['locations'][$counting] ?? '';
//                                //print($locationUrl . PHP_EOL);
//                                $application = Application::firstOrCreate([
//                                    'location_id' => $singleLocation->id,
//                                    'meeting_id' => $singleMeeting->id,
//                                    'file_number' => $address['file_number'],
//                                    'url' => $locationUrl,
//                                ],[
//                                    'user_id' => User::where('email','seed@mail.com')->first()->id
//                                ]);
//                            }
//                        }
//
//                    }
//                }
            }
            $this->info(count($data). " Meetings Data imported Successfully");
        } catch (Exception $e) {
            $meeting['locations_address'] = [];
            $innerData["meetings"] = [];
            $this->error('Error: ' . $e->getMessage());
            Log::error('Error fetching JSON: ' . $e->getMessage());
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
