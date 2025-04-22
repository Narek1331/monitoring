<?php

namespace App\Filament\Resources\TaskResource\Pages;

use App\Filament\Resources\TaskResource;
use Filament\Actions;
use Filament\Resources\Pages\CreateRecord;
use App\Traits\User\StoreHelper;
class CreateTask extends CreateRecord
{
    use StoreHelper;
    
    protected static string $resource = TaskResource::class;

    public function getTitle(): string
    {
        return 'Создать';
    }
}
