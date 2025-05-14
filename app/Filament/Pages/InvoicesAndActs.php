<?php

namespace App\Filament\Pages;

use Filament\Pages\Page;
use Filament\Notifications\Notification;

class InvoicesAndActs extends Page
{
    protected static ?string $navigationIcon = 'heroicon-o-currency-dollar';
    protected static ?string $navigationGroup = 'Финансы';

    protected static string $view = 'filament.pages.finance';
    protected static ?string $pluralLabel = 'Счета и акты';

    protected static ?string $navigationLabelName = 'Счета и акты';

    protected static ?string $title = 'Счета и акты';

    protected static ?int $navigationSort = 2;



}
