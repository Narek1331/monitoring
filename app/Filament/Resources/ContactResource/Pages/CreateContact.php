<?php

namespace App\Filament\Resources\ContactResource\Pages;

use App\Filament\Resources\ContactResource;
use Filament\Actions;
use Filament\Resources\Pages\CreateRecord;
use App\Traits\User\StoreHelper;

class CreateContact extends CreateRecord
{
    use StoreHelper;

    protected static string $resource = ContactResource::class;

    public function getTitle(): string
    {
        return 'Создать';
    }
    
}
