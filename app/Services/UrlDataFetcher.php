<?php

namespace App\Services;

use GuzzleHttp\Client;
use GuzzleHttp\Exception\ConnectException;
use GuzzleHttp\Exception\RequestException;
use Illuminate\Support\Facades\Log;

class UrlDataFetcher
{
    public function fetch(string $url): array
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
//            $this->error($errorMessage);
            Log::error($errorMessage);
            return null;
        } catch (RequestException $e) {
            $errorMessage = "HTTP request error: " . $e->getMessage();
//            $this->error($errorMessage);
//            dd($e->getMessage());
            Log::error($errorMessage);
            return null;
        }
    }

}
