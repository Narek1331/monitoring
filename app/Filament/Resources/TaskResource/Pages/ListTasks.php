<?php

namespace App\Filament\Resources\TaskResource\Pages;

use App\Filament\Resources\TaskResource;
use Filament\Actions;
use Filament\Resources\Pages\ListRecords;

class ListTasks extends ListRecords
{
    protected static string $resource = TaskResource::class;

    protected function getHeaderActions(): array
    {
        return [
            Actions\CreateAction::make(),
            Actions\CreateAction::make('create_as_sample')
            ->label('Создать шаблон ')
            ->url('/account/tasks/create?sample=true'),
            Actions\CreateAction::make('massAdditionOfNewTasks')
            ->label('Массовое добавление новых заданий ')
            ->url('/account/tasks/mass-add/create')
        ];
    }

    public static function canAccess(array $parameters = []): bool
    {
         return auth()->user()->hasPermission('view_task');
    }

}
