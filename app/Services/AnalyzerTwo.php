<?php

namespace App\Services;

use App\Models\General\Application;

class AnalyzerTwo {
    public $transcript, $location, $town;
    private $ollamaUrl = "http://192.168.0.119:11434/api/generate";
    private $model = "gemma3:4b"; // Replace with your model (e.g., 'grok')
//    private $model = "mistral"; // Replace with your model (e.g., 'grok')

    public function __construct(Application $application) {
        $this->location = $application->location->location; // "125 Applefield Drive"
        $transcript = [];
        foreach ($application->meeting->videos as $video) {
            $transcript = array_merge($transcript, $video->fetchvideotranscript());
        }
        $this->transcript = $transcript;

        // Extract the town (assuming "Applefield" is the town part)
        $locationParts = preg_split('/\s+/', $this->location);
        // Remove number and street type (e.g., "Drive"), take the middle part as town
        if (count($locationParts) >= 3) {
            $this->town = $locationParts[1]; // "Applefield"
        } else {
            $this->town = $locationParts[1] ?? $locationParts[0]; // Fallback to first non-number part
        }

        echo "Full Location: $this->location\n";
        echo "Extracted Town: $this->town\n";
        echo "Transcript entries: " . count($this->transcript) . "\n";

        // Check for town in transcript
        $townFound = false;
        foreach ($this->transcript as $entry) {
            $text = $entry['text'] ?? '';
            if (stripos($text, $this->town) !== false) {
                $townFound = true;
                echo "Town '$this->town' found at start time: {$entry['start']}\n";
            }
        }
        if (!$townFound) {
            echo "Town '$this->town' not found in transcript.\n";
        }
    }

    private function queryOllamaFullTranscript() {
        $town = $this->town; // "Applefield"
        $transcriptStr = "";
        foreach ($this->transcript as $entry) {
            if (!isset($entry["start"]) || !isset($entry["duration"]) || !isset($entry["text"])) {
                echo "Warning: Invalid transcript entry: " . json_encode($entry) . "\n";
                continue;
            }
            $transcriptStr .= sprintf(
                "[start: %.2f, duration: %.2f] %s\n",
                $entry["start"],
                $entry["duration"],
                $entry["text"]
            );
        }

        if (empty($transcriptStr)) {
            echo "Error: Transcript string is empty.\n";
            return null;
        }

        echo "Full transcript sent to model:\n$transcriptStr\n";

        $prompt = "Analyze the following full video transcript. Focus solely on finding the earliest start time where the town '$town' is explicitly mentioned in the text (case-insensitive). Return result: the earliest mention with a summary related to '$town' only. Do not include any unrelated summaries or breakdowns. If '$town' is not mentioned anywhere, return only this:\nStart Time: N/A\nSummary: Not found in the transcript.\n\n$transcriptStr\n\nReturn in this format:\nStart Time: <time in seconds or 'N/A'>\nSummary: <summary text or 'Not found in the transcript.'>";
//        $prompt = "Analyze the following full video transcript. Focus solely on finding the earliest start time where the town '$town' is  mentioned in the text (case-insensitive). Return a summary of what was said about the '$town' and the location `$this->location`";

        $data = [
            "model" => $this->model,
            "prompt" => $prompt,
            "stream" => false
        ];

        $ch = curl_init($this->ollamaUrl);
        curl_setopt($ch, CURLOPT_RETURNTRANSFER, true);
        curl_setopt($ch, CURLOPT_POST, true);
        curl_setopt($ch, CURLOPT_HTTPHEADER, ['Content-Type: application/json']);
        curl_setopt($ch, CURLOPT_POSTFIELDS, json_encode($data));

        $response = curl_exec($ch);
        if (curl_errno($ch)) {
            echo "cURL Error: " . curl_error($ch) . "\n";
            curl_close($ch);
            return null;
        }
        curl_close($ch);

        $result = json_decode($response, true);
        if (!$result || !isset($result['response'])) {
            echo "Error: Invalid response from Ollama: " . ($response ?: "No response") . "\n";
            return null;
        }

        $responseText = $result['response'];
        echo "Model Response:\n$responseText\n";

        $lines = explode("\n", $responseText);
        $startTime = null;
        $summary = "";

        foreach ($lines as $line) {
            if (preg_match('/Start Time: ([\d:.]+|N\/A)/', $line, $matches)) {
                $startTime = $matches[1] === 'N/A' ? null : $this->convertToSeconds($matches[1]);
            } elseif (preg_match('/Summary: (.*)/', $line, $matches)) {
                $summary = trim($matches[1]);
            }
        }

        if ($summary === "Not found in the transcript.") {
            $startTime = null;
        }

        return [
            "start_time" => $startTime,
            "location" => $this->location,
            "town" => $town,
            "summary" => $summary
        ];
    }

    private function convertToSeconds($timeStr) {
        if (strpos($timeStr, ':') !== false) {
            list($minutes, $seconds) = explode(':', $timeStr);
            return (float)($minutes * 60 + $seconds);
        }
        return (float)$timeStr;
    }

    public function analyze() {
        echo "Starting analysis for town: $this->town\n";
        $result = $this->queryOllamaFullTranscript();

        if ($result === null) {
            echo "Analysis failed due to an error.\n";
            return null;
        }

        echo "Final Result: Start Time: " . ($result['start_time'] ?? 'N/A') . ", Summary: {$result['summary']}\n";
        return $result['start_time'] !== null ? $result : null;
    }
}
