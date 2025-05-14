<?php

namespace App\Filament\Widgets;

use App\Models\TaskMessage;
use Filament\Widgets\ChartWidget;
use Illuminate\Support\Carbon;

class LastErrorsChart extends ChartWidget
{
    protected static ?string $heading = 'Ошибки по дням';
    protected static ?int $sort = 2;

    protected function getData(): array
    {
        $userId = auth()->id();

        $errorsByDate = TaskMessage::whereHas('task', fn ($query) => $query->where('user_id', $userId))
            ->with('task:name')
            ->orderBy('created_at', 'desc')
            ->get()
            ->groupBy(fn ($message) => Carbon::parse($message->created_at)->toDateString());

        $labels = [];
        $data = [];
        $taskNames = [];
        $errors = [];

        foreach ($errorsByDate as $date => $messages) {
            $labels[] = $date;
            $data[] = $messages->count();

            $taskNames[] = $messages->first()->task ? $messages->first()->task->name : 'Нет задания'; // Fallback if no task

            $errors[] = $messages->map(fn ($message) => $message->text)->join(', ');
        }

        return [
            'labels' => $labels,
            'datasets' => [
                [
                    'label' => 'Количество ошибок',
                    'data' => $data,
                    'backgroundColor' => 'rgba(239, 68, 68, 0.6)',
                ],
            ],
            'taskNames' => $taskNames,
            'errors' => $errors,
        ];
    }

    protected function getType(): string
    {
        return 'bar';
    }

    protected function getColumns(): int
    {
        return 1;
    }
}
