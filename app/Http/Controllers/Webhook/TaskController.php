<?php

namespace App\Http\Controllers\Webhook;

use App\Http\Controllers\Controller;
use App\Models\Task;
use Illuminate\Http\Request;
use Illuminate\Http\Response;

class TaskController extends Controller
{
    public function index($token, Request $request): Response
    {
        $changeType = $request->input('change_type');
        $file = $request->input('file');

        if (!$changeType || !$file) {
            return response(['message' => 'Invalid request.'], 400);
        }

        $task = Task::where('token', $token)->first();

        if (!$task) {
            return response(['message' => 'Task not found.'], 404);
        }

        $message = match ($changeType) {
            'created'  => "Создан новый файл {$file}",
            'modified' => "Файл был изменён {$file}",
            'deleted'  => "Файл был удален {$file}",
            default    => null,
        };

        if (!$message) {
            return response(['message' => 'Unsupported change_type.'], 422);
        }

        $task->messages()->create([
            'status' => false,
            'text'   => $message,
        ]);

        return response(['message' => 'Message logged.'], 200);
    }
}
