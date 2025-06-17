<?php

use Illuminate\Support\Facades\Route;
use App\Http\Controllers\{
    Webhook\TelegramController,
    Webhook\TaskController,
    CodeController
};
use Illuminate\Support\Facades\Storage;

Route::get('/', function () {
    return view('welcome');
});

Route::get('/code',[CodeController::class,'index']);

// Route::get('/check', function (\App\Services\CheckService $checkService) {
//     $checkService->index();
// });

// Route::get('/notification', function (\App\Services\NotificationService $notificationService) {
//     $notificationService->send();
// });

// Route::get('/backup', function (\App\Services\BackupService $backupService) {
//     $backupService->dailyBackupTaskMessages();
// });

Route::get('/storage/exports/{filename}', function ($filename) {
    $filePath = 'public/exports/' . $filename;

    if (!Storage::exists($filePath)) {
        abort(404, 'File not found');
    }

    return Storage::download($filePath);
})->where('filename', '.*');

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

Route::get('/download/check-resources', function(){
    $filePath = public_path('check-resources.php');
    if (file_exists($filePath)) {
        return response()->download($filePath, 'check-resources.php');
    }
    return abort(404, 'File not found');
})->name('download.check-resources');

Route::get('/telegram', function () {
    $botToken = env('TELEGRAM_BOT_TOKEN');
        $webhookUrl = 'https://iqm-tools.ru/webhook/telegram';

        if ($botToken && $webhookUrl) {
            Illuminate\Support\Facades\Http::post("https://api.telegram.org/bot{$botToken}/setWebhook", [
                'url' => $webhookUrl,
            ]);
        }
});
