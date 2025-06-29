<?php

namespace App\Filament\Pages;

use Filament\Pages\Page;
use App\Models\TechnicalSupport as TechnicalSupportModel;
use Filament\Notifications\Notification;

class Finance extends Page
{
    // protected static ?string $navigationIcon = 'heroicon-o-currency-dollar';

    public static function getNavigationIcon(): ?string
    {
        return '/svg/wallet.svg';
    }

    protected static ?string $navigationGroup = 'Финансы';

    protected static string $view = 'filament.pages.finance';

    protected static ?string $pluralLabel = 'Финансы';

    protected static ?string $navigationLabelName = 'Финансы';

    protected static ?string $title = 'Финансы';

    protected static ?int $navigationSort = 1;



}
