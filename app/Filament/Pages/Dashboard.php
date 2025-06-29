<?php

namespace App\Filament\Pages;

class Dashboard extends \Filament\Pages\Dashboard
{
    public static function getNavigationIcon(): ?string
    {
        return '/svg/statistics.svg';
    }
}
