<?php

namespace App\Console\Commands;

use App\Services\TorontoScraper;
use Illuminate\Console\Command;

class ScrapeTorontoApplication extends Command
{
    protected $signature = 'scrape:toronto {url}';
    protected $description = 'Scrape Toronto planning application details';

    public function handle(TorontoScraper $scraper)
    {
        $url = $this->argument('url');

        $this->info("Scraping data from: {$url}");

        try {
            $data = $scraper->scrape($url);

            // Store the data in your database
            // You can use your existing models here

            $this->info('Data scraped successfully:');
            $this->table(
                ['Field', 'Value'],
                collect($data)->map(fn($value, $key) => [$key, $value])
            );

        } catch (\Exception $e) {
            $this->error("Failed to scrape data: {$e->getMessage()}");
        }
    }
}
