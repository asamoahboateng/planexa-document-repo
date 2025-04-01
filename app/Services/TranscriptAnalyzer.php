<?php

    namespace App\Services;

    use App\Models\General\Application;
    use Illuminate\Support\Facades\Http;
    use Illuminate\Support\Facades\Log;
    use Illuminate\Support\Facades\Cache;

    class TranscriptAnalyzer
    {
        private const CHUNK_SIZE = 10;
        private const MAX_RETRIES = 3;
        private const RETRY_DELAY = 1;
        private const MAX_TOKENS = 300;
        private const CACHE_TTL = 3600; // 1 hour

        private string $apiKey;
        private array $progress;

        public function __construct()
        {
            $this->apiKey = config('services.openai.api_key');
            $this->progress = [
                'total_chunks' => 0,
                'processed_chunks' => 0,
                'successful_chunks' => 0,
                'failed_chunks' => 0,
                'status' => 'initializing',
                'last_updated' => now()->toDateTimeString()
            ];
        }

        public function analyzeTranscript(Application $application): array
        {
            try {
                $location = $application->location->location;
                $meetings = $application->meeting;
                $transcriptFull = [];

                $this->updateProgress($application->id, 'collecting_transcripts');

                // Collect all video transcripts
                foreach ($meetings->videos as $video) {
                    $transcript = $video->fetchvideotranscript();
                    $transcriptFull = array_merge($transcriptFull, $transcript);
                }

                if (empty($transcriptFull)) {
                    $this->updateProgress($application->id, 'failed', 'No transcript found');
                    return [
                        'success' => false,
                        'error' => 'No transcript found',
                        'progress' => $this->progress
                    ];
                }

                $chunks = array_chunk($transcriptFull, self::CHUNK_SIZE);
                $this->progress['total_chunks'] = count($chunks);
                $this->updateProgress($application->id, 'processing');

                $summaries = [];
                foreach ($chunks as $index => $chunk) {
                    $summary = $this->processChunk($chunk, $location, $index);
                    $this->progress['processed_chunks']++;

                    if ($summary) {
                        $summaries[] = $summary;
                        $this->progress['successful_chunks']++;
                    } else {
                        $this->progress['failed_chunks']++;
                    }

                    $this->updateProgress($application->id);
                }

                if (empty($summaries)) {
                    $this->updateProgress($application->id, 'failed', 'Failed to analyze transcript');
                    return [
                        'success' => false,
                        'error' => 'Failed to analyze transcript',
                        'progress' => $this->progress
                    ];
                }

                $this->updateProgress($application->id, 'generating_final_summary');
                $finalSummary = $this->getFinalSummary($summaries, $location);

                $this->updateProgress($application->id, 'completed');
                return [
                    'success' => true,
                    'summary' => $finalSummary,
                    'partial_summaries' => $summaries,
                    'progress' => $this->progress
                ];

            } catch (\Exception $e) {
                Log::error('Transcript analysis failed: ' . $e->getMessage());
                $this->updateProgress($application->id, 'failed', $e->getMessage());
                return [
                    'success' => false,
                    'error' => 'Analysis failed: ' . $e->getMessage(),
                    'progress' => $this->progress
                ];
            }
        }

        private function processChunk(array $chunk, string $location, int $index): ?string
        {
            $retries = 0;
            $text = $this->formatChunk($chunk);

            echo "processing chunk".PHP_EOL;
            while ($retries < self::MAX_RETRIES) {
                try {
                    $response = Http::withHeaders([
                        'Authorization' => 'Bearer ' . $this->apiKey,
                        'Content-Type' => 'application/json',
                    ])->timeout(30)->post('https://api.openai.com/v1/chat/completions', [
                        'model' => 'gpt-4',
                        'messages' => [
                            [
                                'role' => 'system',
                                'content' => "You are analyzing part {$index} of a meeting transcript. Extract important information about {$location}."
                            ],
                            [
                                'role' => 'user',
                                'content' => $text
                            ]
                        ],
                        'temperature' => 0.7,
                        'max_tokens' => self::MAX_TOKENS
                    ]);

                    $responseData = $response->json();

                    if (!isset($responseData['choices'][0]['message']['content'])) {
                        throw new \Exception('Invalid API response format');
                    }

                    return $responseData['choices'][0]['message']['content'];

                } catch (\Exception $e) {
                    Log::error("Chunk {$index} analysis failed: " . $e->getMessage());
                    $retries++;
                    if ($retries < self::MAX_RETRIES) {
                        sleep(self::RETRY_DELAY * $retries);
                    }
                }
            }

            return null;
        }

        private function getFinalSummary(array $summaries, string $location): string
        {
            echo "ifnal summayr".PHP_EOL;
            try {
                $response = Http::withHeaders([
                    'Authorization' => 'Bearer ' . $this->apiKey,
                    'Content-Type' => 'application/json',
                ])->timeout(30)->post('https://api.openai.com/v1/chat/completions', [
                    'model' => 'gpt-4',
                    'messages' => [
                        [
                            'role' => 'system',
                            'content' => "Combine these transcript summaries into a concise final summary about {$location}."
                        ],
                        [
                            'role' => 'user',
                            'content' => implode("\n\n", $summaries)
                        ]
                    ],
                    'temperature' => 0.7,
                    'max_tokens' => self::MAX_TOKENS
                ]);

                $responseData = $response->json();

                if (!isset($responseData['choices'][0]['message']['content'])) {
                    throw new \Exception('Invalid API response format');
                }

                return $responseData['choices'][0]['message']['content'];

            } catch (\Exception $e) {
                Log::error('Final summary generation failed: ' . $e->getMessage());
                return implode("\n\n", $summaries);
            }
        }

        private function formatChunk(array $chunk): string
        {
            return collect($chunk)
                ->map(fn($segment) => $segment['text'])
                ->join(' ');
        }

        private function updateProgress(int $applicationId, string $status = null, string $error = null): void
        {
            if ($status) {
                $this->progress['status'] = $status;
            }
            if ($error) {
                $this->progress['error'] = $error;
            }
            $this->progress['last_updated'] = now()->toDateTimeString();

            Cache::put(
                "transcript_analysis_progress_{$applicationId}",
                $this->progress,
                now()->addSeconds(self::CACHE_TTL)
            );
        }

        public static function getProgress(int $applicationId): array
        {
            return Cache::get("transcript_analysis_progress_{$applicationId}", [
                'status' => 'not_found',
                'processed_chunks' => 0,
                'total_chunks' => 0,
                'successful_chunks' => 0,
                'failed_chunks' => 0,
                'last_updated' => null
            ]);
        }
    }
