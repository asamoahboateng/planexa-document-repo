<?php

namespace App\Services;

class OpenStreetMapFetcher
{
    private $url = "https://nominatim.openstreetmap.org/search.php";

    // Method to get address details by querying the Nominatim API
    public function getAddressDetails($address) {
        // Set the parameters for the API request
        $params = [
            'q' => $address,          // The address to search for
            'format' => 'jsonv2',     // The response format (jsonv2)
            'addressdetails' => 1,    // Include detailed address information
            'limit' => 1              // Limit to 1 result
        ];

        // Prepare the URL with query parameters
        $url = $this->url . '?' . http_build_query($params);

        // Send GET request to Nominatim API
        $response = $this->sendRequest($url);

        // Check if the response contains data
        if ($response) {
            // Extract relevant information
            $result = $response[0];

            // Construct and return the address data as an associative array
            return [
                "longitude" => $result['lon'],
                "latitude" => $result['lat'],
                "postcode" => isset($result['address']['postcode']) ? $result['address']['postcode'] : null,
                "city" => isset($result['address']['city']) ? $result['address']['city'] : null,
                "address" => $result['display_name']  // Full address
            ];
        } else {
            return null;
        }
    }

    // Method to send the request and return the response
    private function sendRequest($url) {

//        $applicationInformation = env('app_name') ?? 'planexa_app';
        $applicationInformation =  env('app_name','planexa_app');
        // Initialize cURL session
        $ch = curl_init($url);
        curl_setopt($ch, CURLOPT_RETURNTRANSFER, true);
        curl_setopt($ch, CURLOPT_USERAGENT, $applicationInformation);  // Set a User-Agent header

        // Execute the cURL request
        $response = curl_exec($ch);

        // Check if the request was successful
        if (curl_errno($ch)) {
            // If there's an error, print it
            echo 'Curl error: ' . curl_error($ch);
            return null;
        }

        // Close cURL session
        curl_close($ch);

        // Decode the JSON response into a PHP array
        return json_decode($response, true);
    }
}

// Example usage of the class
//$address = "227 Shaughnessy Blvd";
//$nominatimAPI = new OpenStreetMapFetcher();
//$addressDetails = $nominatimAPI->getAddressDetails($address);
//
//// Print the address details
//if ($addressDetails) {
//    print_r($addressDetails);
//} else {
//    echo "No results found or an error occurred.";
//}
//
