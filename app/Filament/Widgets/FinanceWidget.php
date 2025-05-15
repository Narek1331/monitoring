<?php

namespace App\Filament\Widgets;

use Filament\Widgets\Widget;
use Carbon\Carbon;

class FinanceWidget extends Widget
{
    protected static string $view = 'filament.widgets.finance-widget';
    // protected int | string | array $columnSpan = '2';

    protected static ?int $sort = 3;

    public function getViewData(): array
    {
        $balance = 1200.00;
        $taskPrice = 50.00;
        $dailyTasks = 10;

        $dailyExpense = $taskPrice * $dailyTasks;
        $daysLeft = $dailyExpense > 0 ? floor($balance / $dailyExpense) : 0;
        $renewalDate = Carbon::now()->addDays($daysLeft)->format('d.m.Y');

        return [
            'balance' => $balance,
            'dailyExpense' => $dailyExpense,
            'renewalDate' => $renewalDate,
        ];
    }
}
