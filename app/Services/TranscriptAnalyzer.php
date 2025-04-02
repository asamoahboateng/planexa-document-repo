<?php

namespace App\Services;

use App\Models\General\Application;
use Illuminate\Support\Facades\Http;
use Illuminate\Support\Facades\Log;
use Illuminate\Support\Facades\Cache;

class TranscriptAnalyzer
{
    private const CACHE_TTL = 3600;
    private const OLLAMA_URL = 'http://192.168.0.119:11434/api/generate';
    private const MODEL = 'gemma:2b';
    private const CHUNK_SIZE = 4000;
    private const MAX_RETRIES = 3;
    private const RETRY_DELAY = 2;

    private array $progress;

    public function __construct()
    {
        $this->progress = [
            'status' => 'initializing',
            'last_updated' => now()->toDateTimeString(),
            'chunks_processed' => 0,
            'total_chunks' => 0
        ];
    }

    public function analyzeTranscript(Application $application): array
    {
        $cacheKey = "transcript_analysis_{$application->id}";

        if (Cache::has($cacheKey)) {
            return Cache::get($cacheKey);
        }

        $location = $application->location->location;
        $video = $application->applicationVideo();

        if (empty($video)) {
            return $this->errorResponse('No video found for analysis');
        }

        $transcript = $video['video']->fetchvideotranscript();
        if (empty($transcript)) {
            return $this->errorResponse('Empty transcript');
        }

        $this->progress['status'] = 'processing';
        $this->updateProgress();

        $summary = $this->processTranscript($transcript, $location);

        if (!$summary) {
            return $this->errorResponse('Failed to generate summary');
        }

        $this->progress['status'] = 'completed';
        $this->updateProgress();

        $result = [
            'success' => true,
            'summary' => $summary,
            'progress' => $this->progress
        ];

        Cache::put($cacheKey, $result, self::CACHE_TTL);

        return $result;
    }

    private function processTranscript(array $transcript, string $location): ?string
    {
        try {
            $text = $this->formatTranscript($transcript);
            $chunks = str_split($text, self::CHUNK_SIZE);

            $this->progress['total_chunks'] = count($chunks);
            $this->updateProgress();

            $summaries = $this->processChunks($chunks, $location);

            if (empty($summaries)) {
                throw new \Exception('No valid responses from any chunks');
            }

            return $this->combineSummaries($summaries, $location);

        } catch (\Exception $e) {
            Log::error('Transcript analysis failed', [
                'error' => $e->getMessage(),
                'location' => $location,
                'trace' => $e->getTraceAsString()
            ]);
            return null;
        }
    }

    private function processChunks(array $chunks, string $location): array
    {
        $summaries = [];

        foreach ($chunks as $index => $chunk) {
            $result = $this->processChunk($chunk, $location, $index + 1);
            if ($result) {
                $summaries[] = $result;
            }

            $this->progress['chunks_processed'] = $index + 1;
            $this->updateProgress();

            sleep(self::RETRY_DELAY);
        }

        return $summaries;
    }

    private function processChunk(string $chunk, string $location, int $chunkNumber): ?string
    {
        $retries = 0;

        while ($retries < self::MAX_RETRIES) {
            try {
                $payload = [
                    'model' => self::MODEL,
                    'prompt' => "Analyze part {$chunkNumber} of the meeting transcript. Focus on key information about {$location}.",
                    'context' => $chunk,
                    'stream' => false,
                    'options' => [
                        'num_gpu' => 0,
                        'num_thread' => 4,
                        'temperature' => 0.7,
                        'top_k' => 40
                    ]
                ];

                $response = Http::acceptJson()
                    ->timeout(180)
                    ->post(self::OLLAMA_URL, $payload);

                if (!$response->successful()) {
                    throw new \Exception("HTTP Error: {$response->status()}");
                }

                $result = $response->json('response');
                if (empty($result)) {
                    throw new \Exception('Empty response');
                }

                return $result;

            } catch (\Exception $e) {
                $attempt = $retries + 1;
                Log::warning("Chunk {$chunkNumber} attempt {$attempt} failed", [
                    'error' => $e->getMessage()
                ]);
                $retries++;
                if ($retries < self::MAX_RETRIES) {
                    sleep(self::RETRY_DELAY);
                }
            }
        }

        return null;
    }
    private function combineSummaries(array $summaries, string $location): ?string
    {
        if (count($summaries) === 1) {
            return $summaries[0];
        }

        try {
            $response = Http::acceptJson()
                ->timeout(180)
                ->post(self::OLLAMA_URL, [
                    'model' => self::MODEL,
                    'prompt' => "Combine these summaries about {$location} into one coherent summary:",
                    'context' => implode("\n\n", $summaries),
                    'stream' => false,
                    'options' => [
                        'num_gpu' => 0,
                        'num_thread' => 4,
                        'temperature' => 0.7
                    ]
                ]);

            return $response->json('response');

        } catch (\Exception $e) {
            Log::error('Failed to combine summaries', [
                'error' => $e->getMessage()
            ]);
            return implode("\n\n", $summaries);
        }
    }

    private function formatTranscript(array $transcript): string
    {
        return collect($transcript)
            ->map(fn($segment) => $segment['text'])
            ->join(' ');
    }

    private function updateProgress(): void
    {
        $this->progress['last_updated'] = now()->toDateTimeString();
    }

    private function errorResponse(string $message): array
    {
        return [
            'success' => false,
            'error' => $message,
            'progress' => $this->progress
        ];
    }

    public function getProgress(): array
    {
        return $this->progress;
    }

    public function testConnection(): array
    {
        try {
            $response = Http::acceptJson()
                ->timeout(30)
                ->post(self::OLLAMA_URL, [
                    'model' => self::MODEL,
                    'prompt' => 'Return "OK" if you can read this message.',
                    'stream' => false,
                    'options' => [
                        'num_gpu' => 0,
                        'num_thread' => 4
                    ]
                ]);

            if (!$response->successful()) {
                return [
                    'success' => false,
                    'error' => "HTTP Error: {$response->status()}",
                    'response' => $response->body()
                ];
            }

            return [
                'success' => true,
                'response' => $response->json('response'),
                'status' => $response->status()
            ];

        } catch (\Exception $e) {
            return [
                'success' => false,
                'error' => $e->getMessage()
            ];
        }
    }
}
