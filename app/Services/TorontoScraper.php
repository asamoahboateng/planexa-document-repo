<?php

namespace App\Services;

use Nesk\Puphpeteer\Puppeteer;
use Exception;

class TorontoScraper
{
    protected $puppeteer;
    protected $browser;
    protected $page;

    public function __construct()
    {
        $this->puppeteer = new Puppeteer;
    }

    public function scrape(string $url)
    {
        try {
            $this->browser = $this->puppeteer->launch([
                'headless' => true,
                'args' => ['--no-sandbox', '--disable-setuid-sandbox']
            ]);

            $this->page = $this->browser->newPage();

            // Navigate to URL and wait for content to load
            $this->page->goto($url, ['waitUntil' => 'networkidle0']);

            // Wait for specific elements to be present
            $this->page->waitForSelector('.application-details');

            // Extract the data
            $data = $this->page->evaluate(<<<JS
                () => {
                    const getData = () => {
                        const details = document.querySelector('.application-details');

                        return {
                            title: document.querySelector('h1')?.textContent?.trim(),
                            applicationNumber: document.querySelector('[data-application-number]')?.textContent?.trim(),
                            status: document.querySelector('[data-status]')?.textContent?.trim(),
                            type: document.querySelector('[data-type]')?.textContent?.trim(),
                            description: document.querySelector('[data-description]')?.textContent?.trim(),
                            address: document.querySelector('[data-address]')?.textContent?.trim()
                        };
                    };

                    return getData();
                }
            JS);

            $this->browser->close();

            return $data;

        } catch (Exception $e) {
            if (isset($this->browser)) {
                $this->browser->close();
            }
            throw $e;
        }
    }
}
