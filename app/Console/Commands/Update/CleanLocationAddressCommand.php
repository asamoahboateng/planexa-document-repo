<?php

namespace App\Console\Commands\Update;

use App\Models\General\Location;
use Illuminate\Console\Command;

class CleanLocationAddressCommand extends Command
{
    /**
     * Sanitaize the location address to clean unnecessary inputs
     *
     * @var string
     */
    protected $signature = 'location:clean-address';

    /**
     * The console command description.
     *
     * @var string
     */
    protected $description = 'clean up location address';

    /**
     * Execute the console command.
     */
    public function handle()
    {
        //
        $locations = Location::all();
        foreach ($locations as $location) {
            $address = $this->extractAddress($location->location);
            // Print extracted address
            if ($address) {
                Location::where('id', $location->id)->update([
                    'location' => $address,
                    'old_address' => $location->location ,
                ]);
            } else {
                echo "No address found.\n";
            }
        }

        $this->info( count($locations)." Locations have been cleaned");
    }

//    protected  function extractAddress($input) {
//        // Updated regex pattern to match both types of addresses (with and without ranges)
//        $pattern = '/(\d{1,5}-\d{1,5}|\d{1,5})\s([A-Za-z\s]+)(\s(?:Drive|Street|Road|Ave|Blvd|Lane|Crescent|Court|Place|Way|Terrace|Boulevard|Square))?(\s?and?\s?\d{1,5}-\d{1,5})?\s?([A-Za-z\s]+)?/';
//
//        // Perform regex match
//        if (preg_match($pattern, $input, $matches)) {
//            // Prepare the address components
//            $address = [
//                'street_number' => isset($matches[1]) ? $matches[1] : null,  // Street number (with or without a range)
//                'street_name' => isset($matches[2]) ? $matches[2] : null,    // Street name
//                'street_type' => isset($matches[3]) ? $matches[3] : null,    // Street type (Drive, Road, etc.)
////                'second_part' => isset($matches[4]) ? $matches[4] : null,    // Second address part (like 196-198)
////                'second_street_name' => isset($matches[5]) ? $matches[5] : null  // Second street name (if exists)
//            ];
//
//            return $address;
//        }
//
//        return null;
//    }
    function extractAddress($input) {
        // Updated regex pattern to handle addresses with single numbers and street names, and "and" in address numbers
        $pattern = '/(\d{1,5}(-\d{1,5})?|\d{1,5}\s?and?\s?\d{1,5})\s([A-Za-z\s]+)(\s(?:Drive|Street|Road|Ave|Blvd|Lane|Crescent|Court|Place|Way|Terrace|Boulevard|Square))?/';

        // Perform regex match
        if (preg_match($pattern, $input, $matches)) {
            // Prepare the address components
            $address = [
                'street_number' => isset($matches[1]) ? $matches[1] : null,  // Street number (with or without a range)
                'street_name' => isset($matches[3]) ? $matches[3] : null,    // Street name
                'street_type' => isset($matches[4]) ? $matches[4] : null,    // Street type (Drive, Road, etc.)
            ];

            // Implode the address components into a single string
            $fullAddress = implode(" ", array_filter($address));

            // Remove any unnecessary commas or "and" in the address (if necessary)
            $fullAddress = str_replace("and", "and", $fullAddress);

            // Optional: Trim any extra spaces after handling "and"
            $fullAddress = trim($fullAddress);

            return $fullAddress;
        }

        // If no match is found, return the original input (or null if desired)
        return $input;
    }
}
