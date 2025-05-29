<?php

namespace App\Filament\Resources\StatisticsResource\Pages;

use App\Filament\Resources\StatisticsResource;
use Filament\Resources\Pages\ViewRecord;
use App\Models\{
    Task,
    TaskMessage
};
use Carbon\Carbon;
use GuzzleHttp\Client;
use Illuminate\Support\Facades\Http;


class ViewStatistics extends ViewRecord
{
    protected static string $resource = StatisticsResource::class;
    protected static string $view = 'filament.resources.statistics-resource.pages.view';

    public function getTitle(): string
    {
        return $this->record->name;
    }

    protected function setUp(): void
    {
        parent::setUp();
    }

    public function loadSslExpiration()
    {
        $host = parse_url($this->record->address_ip, PHP_URL_HOST) ?? $this->record->address_ip;

        $context = stream_context_create([
            "ssl" => [
                "capture_peer_cert" => true,
                "verify_peer" => false,
                "verify_peer_name" => false,
            ],
        ]);

        try {
            $client = @stream_socket_client("ssl://{$host}:443", $errno, $errstr, 5, STREAM_CLIENT_CONNECT, $context);

            if (!$client) {
                return 'Ошибка';
            }

            $params = stream_context_get_params($client);
            $cert = openssl_x509_parse($params["options"]["ssl"]["peer_certificate"]);

            if (isset($cert['validTo_time_t'])) {
                return Carbon::createFromTimestamp($cert['validTo_time_t'])->format('Y-m-d');
            }

            return 'Ошибка';
        } catch (\Exception $e) {
            return 'Ошибка';
        }
    }

    public function isSiteClean(): bool
    {
        try {
            $domain = $this->record->address_ip;
            $client = new Client();
            $url = "https://sitecheck.sucuri.net/api/v3/?scan=$domain";

            $response = $client->get($url);
            $data = json_decode($response->getBody()->getContents(), true);

            if (!isset($data['scan'])) {
                return false;
            }

            $scan = $data['scan'];

            if (($scan['malware'] ?? 0) > 0 || ($scan['blacklist'] ?? 0) > 0) {
                return false;
            }

            if (($scan['status'] ?? '') === 'ok') {
                return true;
            }

            return false;

        } catch (\Exception $e) {
            return false;
        }
    }

     public function getLoadSpeedMs()
    {
        $url = $this->record->address_ip;

        try {
            $start = microtime(true);
            Http::timeout(5)->get($url);
            $end = microtime(true);

            return intval(($end - $start) * 1000);
        } catch (\Exception $e) {
            return null;
        }
    }

    public function getTaskMessages()
    {
        return TaskMessage::where('task_id',$this->record->id)->get();
    }

}
