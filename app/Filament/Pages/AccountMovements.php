<?php

namespace App\Filament\Pages;

use Filament\Pages\Page;
use App\Models\TechnicalSupport as TechnicalSupportModel;
use Filament\Notifications\Notification;

class AccountMovements extends Page
{
    protected static ?string $navigationIcon = 'heroicon-o-currency-dollar';

    protected static ?string $navigationGroup = 'Финансы';

    protected static string $view = 'filament.pages.finance';

    protected static ?string $pluralLabel = 'Движения по счету';

    protected static ?string $navigationLabelName = 'Движения по счету';

    protected static ?string $title = 'Движения по счету';

    protected static ?int $navigationSort = 4;



}
