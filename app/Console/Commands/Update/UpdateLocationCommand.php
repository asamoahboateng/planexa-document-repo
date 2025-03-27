<?php

namespace App\Console\Commands\Update;

use App\Models\General\Location;
use Illuminate\Console\Command;
use App\Services\OpenStreetMapFetcher;

class UpdateLocationCommand extends Command
{
    /**
     * Update the postcode, long and late  on the location
     *
     * @var string
     */
    protected $signature = 'location:update-details';

    /**
     * The console command description.
     *
     * @var string
     */
    protected $description = 'Update the location details';

    /**
     * Execute the console command.
     */
    public function handle(OpenStreetMapFetcher $fetcher)
    {
        $all_locations = Location::whereNull('postal_code')->get();
        $countt = 0;
        foreach ($all_locations as $location) {

            try {
                $details = $fetcher->getAddressDetails($location->location .' ,Canada');
                if($details) {
                    #echo  $details['postcode'] .' - '. $details['address'] . "\n";

                    $countt++;
                    $location->update([
                        'postal_code' => $details['postcode'],
                        'lat' => $details['latitude'],
                        'long' => $details['longitude'],
                    ]);
                }
            }
            catch (\Exception $e) {
                $this->error($e->getMessage());
            }
            sleep(1);
        }

        $this->info($countt . ' Locations updated successfully!');
    }
}
