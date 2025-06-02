<?php

namespace App\Filament\Resources\TaskResource\Pages;

use App\Filament\Resources\TaskResource;
use Filament\Actions;
use Filament\Resources\Pages\EditRecord;
use Filament\Forms\Components\Group;
use Filament\Actions\Action;
use Filament\Forms\Components\DatePicker;
use App\Exports\TaskMessagesExport;
use Maatwebsite\Excel\Facades\Excel;
class EditTask extends EditRecord
{
    protected static string $resource = TaskResource::class;

    protected function getHeaderActions(): array
    {
        return [
            Action::make('downloadReport')
                ->label('Скачать отчёт')
                ->icon('heroicon-o-arrow-down-tray')
                ->form([
                    DatePicker::make('from_date')
                        ->required()
                        ->label('Дата начала'),
                    DatePicker::make('to_date')
                        ->required()
                        ->label('Дата окончания'),
                ])
                ->modalHeading('Выберите период')
                ->modalSubmitActionLabel('Скачать')
                ->action(function (array $data, $record) {
                    $from = $data['from_date'];
                    $to = $data['to_date'];

                    $messages = $record->messages()
                        ->when($from, fn($q) => $q->whereDate('created_at', '>=', $from))
                        ->when($to, fn($q) => $q->whereDate('created_at', '<=', $to))
                        ->get();

                    if($messages && count($messages))
                    {
                        return Excel::download(new TaskMessagesExport($messages), 'Сообщения.xlsx');
                    }
                }),
        ];
    }


    public function getTitle(): string
    {
        return 'Редактирование';
    }

    protected function getRedirectUrl(): string
    {
        return static::getResource()::getUrl('index');
    }

    public static function canAccess(array $parameters = []): bool
    {
         return auth()->user()->hasPermission('edit_task');
    }

}
