<?php

namespace App\Services;

use Illuminate\Support\Facades\Http;

class VirusTotalService
{
    protected string $apiKey;
    protected string $submitUrl = 'https://www.virustotal.com/api/v3/urls';
    protected string $reportBaseUrl = 'https://www.virustotal.com/api/v3/urls/';

    public function __construct()
    {
        $this->apiKey = env('VIRUSTOTAL_API_KEY');
    }

    /**
     * Check if a URL is safe using VirusTotal.
     */
    public function checkUrl(string $url)
    {
        $submitResponse = Http::withHeaders([
            'x-apikey' => $this->apiKey,
        ])->asForm()->post($this->submitUrl, [
            'url' => $url,
        ]);

        if (!$submitResponse->successful()) {
            return ['error' => 'Unable to submit URL'];
        }

        // Step 2: Get the analysis report
        $encodedUrl = rtrim(strtr(base64_encode($url), '+/', '-_'), '=');
        $reportResponse = Http::withHeaders([
            'x-apikey' => $this->apiKey,
        ])->get($this->reportBaseUrl . $encodedUrl);

        if (!$reportResponse->successful()) {
            return ['error' => 'Unable to get report'];
        }

        $data = $reportResponse->json();
        $stats = $data['data']['attributes']['last_analysis_stats'] ?? [];


        return ($stats['malicious'] ?? 0) == 0 && ($stats['suspicious'] ?? 0) == 0;
    }
}
