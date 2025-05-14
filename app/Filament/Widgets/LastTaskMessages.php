<?php

namespace App\Filament\Widgets;

use App\Models\TaskMessage;
use Filament\Widgets\Widget;
use Illuminate\Support\Facades\Auth;

class LastTaskMessages extends Widget
{
    protected static string $view = 'filament.widgets.last-task-messages';

    // protected int|string|array $columnSpan = '5';

        protected static ?int $sort = 4;


    public function getViewData(): array
    {
        return [
            'messages' => TaskMessage::with('task')
            ->whereHas('task', function ($query) {
                $query->where('user_id', Auth::id());
            })
            ->latest()
            ->take(5)
            ->get(),
        ];
    }
}
