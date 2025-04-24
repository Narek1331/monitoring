<?php

namespace App\Http\Controllers\Webhook;

use App\Http\Controllers\Controller;
use Illuminate\Http\Request;
use App\Models\TelegramUser;
use Illuminate\Support\Str;
use App\Services\TelegramService;

class TelegramController extends Controller
{
    public $telegramService;

    public function __construct(TelegramService $telegramService)
    {
        $this->telegramService = $telegramService;
    }

    public function index(Request $request)
    {
        $data = $request->all();

        $chatId = $data['message']['chat']['id'] ?? null;
        if (!$chatId) {
            return response()->json(['error' => 'Chat ID not found'], 400);
        }

        $telegramUser = TelegramUser::where('chat_id', $chatId)->first();

        if (!$telegramUser) {
            $token = Str::random(40);

            $telegramUser = TelegramUser::create([
                'chat_id' => $chatId,
                'user_id' => null,
                'token' => $token,
            ]);
        }

        $this->telegramService->sendMessage($chatId,$token);

        echo 1;
    }
}
