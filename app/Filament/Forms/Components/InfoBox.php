<?php

namespace App\Filament\Forms\Components;

use Filament\Forms\Components\TextInput;

class InfoBox extends TextInput
{
    protected string $view = 'components.info-box';
    protected function setUp(): void
    {
        parent::setUp();

        // $this->label('Custom Text Field')
        //      ->placeholder('Enter something here...')
        //      ->helperText('This is a custom form field');
    }
}
