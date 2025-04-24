<?php

use Illuminate\Support\Facades\Route;
use App\Http\Controllers\Webhook\TelegramController;

Route::get('/', function () {
    return view('welcome');
});

Route::get('/check', function (\App\Services\CheckService $checkService) {
    $checkService->index();
});

Route::get('/notification', function (\App\Services\NotificationService $notificationService) {
    $notificationService->send();
});

Route::group(['prefix'=>'webhook'], function () {
    Route::post('/telegram', [TelegramController::class, 'index'])->name('webhook.telegram');
});
