<?php

namespace App\Services;

use GuzzleHttp\Client;
use GuzzleHttp\Exception\RequestException;
use GuzzleHttp\Exception\ConnectException;
use GuzzleHttp\Cookie\CookieJar;
class HttpService
{
    protected Client $client;

    public function __construct()
    {
        $this->client = new Client();
    }

    public function makeRequest(string $url, array $data = [], string $method = 'GET', float $timeout = 10.0)
    {
        try {
            $response = $this->client->request($method, $url, [
                'json' => $data,
                'timeout' => $timeout,
                'headers' => [
                    'Accept' => 'application/json',
                ],
            ]);

            return json_decode($response->getBody()->getContents(), true);
        } catch (RequestException $e) {
            return [
                'error' => true,
                'message' => $e->getMessage(),
                'response' => $e->hasResponse() ? $e->getResponse()->getBody()->getContents() : null,
            ];
        }
    }

    public function makeSimpleRequest(string $url, $task)
    {
        $timeout = $task->site_timeout_duration ?? 10.0;

        try {
            $response = $this->client->request('GET', $url, [
                'timeout' => $timeout,
                // 'allow_redirects' => true,
                'Referer' => $task->referer ?? '',
                'auth' => [$task->login ?? '', $task->password ?? ''],
                'headers' => [
                   ...$this->headersTextToArray($task->header_for_request)
                ],
            ]);

            return [
                'html' => $response->getBody()->getContents(),
                'status' => $response->getStatusCode()
            ];
        } catch (ConnectException $e) {
            // Логировать можно здесь, если нужно
            return null;
        } catch (RequestException $e) {
            return null;
        }
    }

    public function makeSimplePostRequest(string $url, $task)
    {
        $timeout = $task->site_timeout_duration ?? 10.0;
        $formFields = $task->form_fields ?? null;
        parse_str($formFields, $formFieldsData);


        try {
            $response = $this->client->request('POST', $url, [
                'timeout' => $timeout,
                // 'allow_redirects' => true,
                'Referer' => $task->referer ?? '',
                'auth' => [$task->login ?? '', $task->password ?? ''],
                'headers' => [
                   ...$this->headersTextToArray($task->header_for_request)
                ],
                'form_params' => $formFieldsData
            ]);

            return [
                'html' => $response->getBody()->getContents(),
                'status' => $response->getStatusCode()
            ];
        } catch (ConnectException $e) {
            // Логировать можно здесь, если нужно
            return null;
        } catch (RequestException $e) {
            return null;
        }
    }

    function headersTextToArray($input) {

        $pairs = explode(',', $input);

        $result = [];

        foreach ($pairs as $pair) {
            $pair = trim($pair);

            if (strpos($pair, ': ') !== false) {
                list($key, $value) = explode(': ', $pair, 2);
                $result[$key] = $value;
            } else {
                return [];
            }
        }

        return $result;
    }

    public function pmakeHttpCodeRequest(string $url)
    {
        try {
            $response = $this->client->request('GET', $url . '/code.php',[
                'cookies' => true
            ]);

            $data = json_decode($response->getBody()->getContents(), true);

            return $data;


        } catch (ConnectException $e) {
            return null;
        } catch (RequestException $e) {
            return null;
        }
    }

    public function makeHttpCodeRequest(string $url)
    {
        try {
            // Create a new CookieJar to store cookies across requests
            $cookieJar = CookieJar::fromArray([], parse_url($url, PHP_URL_HOST)); // Empty array for no initial cookies

            // Send GET request with the CookieJar to store and send cookies
            $response = $this->client->request('GET', $url . '/code.php', [
                'cookies' => $cookieJar,  // Use the cookie jar to store cookies from the first request
            ]);

            // Decode the JSON response from the PHP script
            $data = json_decode($response->getBody()->getContents(), true);

            return $data;

        } catch (ConnectException $e) {
            // Handle connection exception
            return null;
        } catch (RequestException $e) {
            // Handle request exception
            return null;
        }
    }

    public function makeHttpCheckResourcesRequest(string $url)
    {
        try {
            // Create a new CookieJar to store cookies across requests
            $cookieJar = CookieJar::fromArray([], parse_url($url, PHP_URL_HOST)); // Empty array for no initial cookies

            // Send GET request with the CookieJar to store and send cookies
            $response = $this->client->request('GET', $url . '/check-resources.php', [
                'cookies' => $cookieJar,  // Use the cookie jar to store cookies from the first request
            ]);

            // Decode the JSON response from the PHP script
            $data = json_decode($response->getBody()->getContents(), true);

            return $data;

        } catch (ConnectException $e) {
            // Handle connection exception
            return null;
        } catch (RequestException $e) {
            // Handle request exception
            return null;
        }
    }
}
