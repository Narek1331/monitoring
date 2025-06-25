<?php

namespace App\Filament\Resources;

use App\Filament\Resources\ReportResource\Pages;
use App\Filament\Resources\ReportResource\RelationManagers;
use App\Models\{
    TaskMessage
};
use Filament\Forms;
use Filament\Forms\Form;
use Filament\Resources\Resource;
use Filament\Tables;
use Filament\Tables\Table;
use Illuminate\Database\Eloquent\Builder;
use Illuminate\Database\Eloquent\SoftDeletingScope;
use Filament\Tables\Columns\TextColumn;

class ReportResource extends Resource
{
    protected static ?string $model = TaskMessage::class;

    protected static ?string $navigationIcon = 'heroicon-o-rectangle-stack';

    protected static ?string $title = 'Отчеты';

    // protected static ?string $navigationGroup = '';

    protected static ?string $navigationLabel = 'Отчеты';

    protected static ?string $pluralLabel = 'Отчеты';

    protected static ?string $navigationLabelName = 'Отчеты';

    protected static ?string $recordTitleAttribute = 'text';

    public static function getGloballySearchableAttributes(): array
    {
        return ['text'];
    }
    public static function form(Form $form): Form
    {
        return $form
            ->schema([
                //
            ]);
    }

    public static function table(Table $table): Table
    {
        return $table
            ->columns([
                TextColumn::make('task_id')
                    ->label('ID задачи'),
                TextColumn::make('status')
                    ->label('Статус'),
                TextColumn::make('text')
                    ->label('Сообщение')
                    ->limit(50)
                    ->tooltip(fn ($record) => $record->text),
                TextColumn::make('status_code')
                    ->label('Код статуса'),
                TextColumn::make('created_at')
                    ->label('Создано')
                    ->dateTime('d.m.Y H:i'),
                TextColumn::make('updated_at')
                    ->label('Обновлено')
                    ->dateTime('d.m.Y H:i'),
            ])
            ->filters([])
            ->actions([])
            ->bulkActions([]);
    }


    public static function getRelations(): array
    {
        return [
            //
        ];
    }

    public static function getPages(): array
    {
        return [
            'index' => Pages\ListReports::route('/'),
        ];
    }

    public static function canAccess(): bool
    {
        return auth()->user()->hasPermission('view_report');
    }

    public static function shouldRegisterNavigation(): bool
    {
       return auth()->user()->hasPermission('view_report');
    }


}
