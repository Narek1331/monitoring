<?php

namespace App\Services;

use GuzzleHttp\Client;

class SiteScannerService
{
    protected Client $client;

    public function __construct()
    {
        $this->client = new Client(['timeout' => 15]);
    }

    public function scanWithSucuri(string $url)
    {
        $response = $this->client->get("https://sitecheck.sucuri.net/api/v3/?scan={$url}");
        return json_decode($response->getBody()->getContents(), true);
    }

    public function scanWithVirusTotal(string $url)
    {
        $apiKey = env('VIRUSTOTAL_API_KEY');

        // Encode URL (требуется для API)
        $encodedUrl = base64_encode($url);
        $encodedUrl = rtrim(strtr($encodedUrl, '+/', '-_'), '=');

        // Этап 1: Отправка URL
        $postResponse = $this->client->post('https://www.virustotal.com/api/v3/urls', [
            'headers' => [
                'x-apikey' => $apiKey,
                'Content-Type' => 'application/x-www-form-urlencoded',
            ],
            'form_params' => [
                'url' => $url,
            ]
        ]);

        $data = json_decode($postResponse->getBody()->getContents(), true);
        $scanId = $data['data']['id'] ?? null;

        // Этап 2: Получение результата
        if ($scanId) {
            $result = $this->client->get("https://www.virustotal.com/api/v3/analyses/{$scanId}", [
                'headers' => [
                    'x-apikey' => $apiKey,
                ]
            ]);

            return json_decode($result->getBody()->getContents(), true);
        }

        return ['error' => 'Scan ID not returned'];
    }

    public function checkGoogleBlacklist(string $url)
    {
        $apiKey = '';

        $response = $this->client->post("https://safebrowsing.googleapis.com/v4/threatMatches:find?key={$apiKey}", [
            'json' => [
                'client' => ['clientId' => 'your-client-id', 'clientVersion' => '1.0'],
                'threatInfo' => [
                    'threatTypes' => ['MALWARE', 'SOCIAL_ENGINEERING'],
                    'platformTypes' => ['ANY_PLATFORM'],
                    'threatEntryTypes' => ['URL'],
                    'threatEntries' => [['url' => $url]],
                ],
            ]
        ]);

        return json_decode($response->getBody()->getContents(), true);
    }
}
