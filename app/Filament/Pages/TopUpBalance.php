<?php

namespace App\Filament\Pages;

use Filament\Pages\Page;
use Filament\Notifications\Notification;

class TopUpBalance extends Page
{
    protected static ?string $navigationIcon = 'heroicon-o-currency-dollar';
    protected static ?string $navigationGroup = 'Финансы';

    protected static string $view = 'filament.pages.finance';
    protected static ?string $pluralLabel = 'Пополнить баланс';

    protected static ?string $navigationLabelName = 'Пополнить баланс';

    protected static ?string $title = 'Пополнить баланс';

    protected static ?int $navigationSort = 3;



}
