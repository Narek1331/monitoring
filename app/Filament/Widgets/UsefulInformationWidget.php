<?php

namespace App\Filament\Widgets;

use Filament\Widgets\Widget;
use Illuminate\Contracts\View\View;

class UsefulInformationWidget extends Widget
{
    protected static string $view = 'filament.widgets.useful-information-widget';

    public function render(): View
    {
        return view('filament.widgets.useful-information-widget');
    }

    // protected int | string | array $columnSpan = '6';

    protected static ?int $sort = 3;

}
