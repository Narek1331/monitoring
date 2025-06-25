<?php

namespace App\Filament\Resources;

use App\Filament\Resources\ContactResource\Pages;
use App\Filament\Resources\ContactResource\RelationManagers;
use App\Models\{
    Contact,
    ContactType,
    Task
};
use Filament\Forms;
use Filament\Forms\Form;
use Filament\Resources\Resource;
use Filament\Tables;
use Filament\Tables\Table;
use Illuminate\Database\Eloquent\Builder;
use Illuminate\Database\Eloquent\SoftDeletingScope;
use Filament\Forms\Components\{
    TextInput,
    Grid,
    Card,
    Select,
    Toggle,
    Section,
    MultiSelect
};
use Filament\Tables\Columns\{
    TextColumn,
    ToggleColumn
};
use Filament\Tables\Filters\SelectFilter;


class ContactResource extends Resource
{
    use \App\Traits\User\GetHelper;

    protected static ?string $model = Contact::class;

    protected static ?string $navigationIcon = 'heroicon-o-user';

    protected static ?string $title = 'Контакты';

    // protected static ?string $navigationGroup = '';

    protected static ?string $navigationLabel = 'Контакты';

    protected static ?string $pluralLabel = 'Контакты';

    protected static ?string $navigationLabelName = 'Контакты';

    protected static ?int $navigationSort = 5;

    protected static ?string $recordTitleAttribute = 'name';

    public static function getGloballySearchableAttributes(): array
    {
        return ['name', 'email', 'phone'];
    }

    public static function form(Form $form): Form
    {
        return $form
            ->schema([
                Card::make('')
                ->schema([
                    Select::make('type_id')
                        ->label('Тип')
                        ->options(ContactType::pluck('name','id')->toArray())
                        ->default(ContactType::first()->id)
                        ->required()
                        ->columnSpan(1)
                        ->disablePlaceholderSelection(true)
                        ->reactive(),
                    Section::make('')
                        ->schema(function($get){

                            if($contactType = ContactType::find($get('type_id')))
                            {
                                if($contactType->slug == 'email')
                                {
                                    return [...self::emailTemplate()];
                                }else if($contactType->slug == 'phone_call' || $contactType->slug == 'phone_sms')
                                {
                                    return [...self::phoneTemplate()];
                                }else if($contactType->slug == 'telegram')
                                {
                                    return [...self::telegramTemplate()];
                                }else if($contactType->slug == 'http_script')
                                {
                                    return [...self::httpCodeTemplate()];
                                }

                            }

                            return [];

                        })
                        ->reactive(),
                    MultiSelect::make('reportContacts')
                        ->label('Задачи для отправки уведомлений об ошибках')
                        ->preload()
                        ->relationship('reportContacts')
                        ->options(Task::get()->pluck('name','id')),
                    MultiSelect::make('errorNotificationContacts')
                        ->label('Задачи для автоматической отправки отчётов')
                        ->preload()
                        ->relationship('errorNotificationContacts')
                        ->options(Task::get()->pluck('name','id'))


                ])
            ]);
    }

    public static function emailTemplate()
    {
        return [
            TextInput::make('name')
                ->label('Имя для контакта')
                ->columnSpan(1),
            TextInput::make('email')
                ->label('Ваш e-mail')
                ->required()
                ->columnSpan(1)
        ];
    }

    public static function phoneTemplate()
    {
        return [
            TextInput::make('name')
                ->label('Имя для контакта')
                ->columnSpan(1),
            TextInput::make('phone')
                ->label('Ваш телефон')
                ->columnSpan(1),

        ];
    }

    public static function telegramTemplate()
    {
        return [
            TextInput::make('name')
                ->label('Имя для контакта')
                ->columnSpan(1),
            TextInput::make('tg_verification_code')
                ->label('Проверочный код:')
                ->columnSpan(1),

        ];
    }

    public static function httpCodeTemplate()
    {
        return [
            TextInput::make('name')
                ->label('Имя для контакта')
                ->columnSpan(1),
            TextInput::make('http_url')
                ->label('URL скрипта')
                ->columnSpan(1),
            TextInput::make('http_password')
                ->label('Key (пароль)')
                ->columnSpan(1),

        ];
    }

    public static function table(Table $table): Table
    {
        return $table
            ->columns([
                TextColumn::make('id')
                    ->label('ID')
                    ->sortable(),
                ToggleColumn::make('status')
                    ->label('Статус')
                    ->sortable(),
                TextColumn::make('type.name')
                    ->label('Тип')
                    ->searchable()
                    ->sortable(),
                TextColumn::make('name')
                    ->label('Имя для контакта')
                    ->searchable()
                    ->sortable(),
            ])
            ->filters([
                 SelectFilter::make('type_id')
                ->label('Тип аккаунта')
                ->options(ContactType::get()->pluck('name','id'))

            ])
            ->actions([
                Tables\Actions\EditAction::make(),
            ])
            ->bulkActions([
                Tables\Actions\BulkActionGroup::make([
                    Tables\Actions\DeleteBulkAction::make()
                    ->modalHeading('Удалить отмеченное')
                ]),
            ]);
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
            'index' => Pages\ListContacts::route('/'),
            'create' => Pages\CreateContact::route('/create'),
            'edit' => Pages\EditContact::route('/{record}/edit'),
        ];
    }

    public static function canAccess(): bool
    {
        return auth()->user()->hasPermission('view_contact');
    }

    public static function shouldRegisterNavigation(): bool
    {
       return auth()->user()->hasPermission('view_contact');
    }


}
