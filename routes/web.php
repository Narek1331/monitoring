<?php

use Illuminate\Support\Facades\Route;
use App\Http\Controllers\Webhook\TelegramController;
use App\Http\Controllers\Webhook\TaskController;

Route::get('/', function () {
    return view('welcome');
});

Route::get('/check', function (\App\Services\CheckService $checkService) {
    $checkService->index();
});

Route::get('/notification', function (\App\Services\NotificationService $notificationService) {
    $notificationService->send();
});

Route::get('/backup', function (\App\Services\BackupService $backupService) {
    $backupService->dailyBackupTaskMessages();
});

Route::group(['prefix'=>'webhook'], function () {
    Route::post('/telegram', [TelegramController::class, 'index'])->name('webhook.telegram');
    Route::post('/task/{token}', [TaskController::class, 'index'])->name('webhook.task');
});

Route::get('/download/code', function(){
    $filePath = public_path('code.php');
    if (file_exists($filePath)) {
        return response()->download($filePath, 'code.php');
    }
    return abort(404, 'File not found');
})->name('download.code');

Route::get('/telegram', function () {
    $botToken = env('TELEGRAM_BOT_TOKEN');
        $webhookUrl = 'https://iqm-tools.ru/webhook/telegram';

        if ($botToken && $webhookUrl) {
            Illuminate\Support\Facades\Http::post("https://api.telegram.org/bot{$botToken}/setWebhook", [
                'url' => $webhookUrl,
            ]);
        }
});
