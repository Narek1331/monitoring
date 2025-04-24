<?php

namespace App\Providers;

use Illuminate\Support\ServiceProvider;
use Illuminate\Support\Facades\Http;
class TelegramServiceProvider extends ServiceProvider
{
    /**
     * Register services.
     */
    public function register(): void
    {
        //
    }

    /**
     * Bootstrap services.
     */
    public function boot(): void
    {

        $botToken = env('TELEGRAM_BOT_TOKEN');
        $webhookUrl = 'https://9aa9-185-79-2-103.ngrok-free.app/webhook/telegram';

        if ($botToken && $webhookUrl) {
            Http::post("https://api.telegram.org/bot{$botToken}/setWebhook", [
                'url' => $webhookUrl,
            ]);
        }
    }
}
