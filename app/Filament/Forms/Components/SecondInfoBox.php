<?php

namespace App\Filament\Forms\Components;

use Filament\Forms\Components\TextInput;

class SecondInfoBox extends TextInput
{
    protected string $view = 'components.second-info-box';
    protected function setUp(): void
    {
        parent::setUp();
    }
}
