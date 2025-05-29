<?php

namespace App\Filament\Resources;

use App\Filament\Resources\UserResource\Pages;
use App\Models\Role;
use App\Models\User;
use Filament\Forms;
use Filament\Forms\Form;
use Filament\Resources\Resource;
use Filament\Tables;
use Filament\Tables\Table;
use Illuminate\Database\Eloquent\Builder;
use Illuminate\Database\Eloquent\SoftDeletingScope;
use Filament\Tables\Filters\SelectFilter;

class UserResource extends Resource
{
    protected static ?string $model = User::class;

    protected static ?string $navigationIcon = 'heroicon-o-user';
    protected static ?string $navigationLabel = 'Пользователи';
    protected static ?string $pluralLabel = 'Пользователи';
    protected static ?string $label = 'Пользователь';

    public static function form(Form $form): Form
    {
        return $form
            ->schema([
                Forms\Components\Card::make()->schema([

                Forms\Components\TextInput::make('name')
                    ->label('Имя')
                    ->required()
                    ->maxLength(255),

                Forms\Components\TextInput::make('email')
                    ->label('Email')
                    ->email()
                    ->required()
                    ->maxLength(255),

                Forms\Components\Select::make('role_id')
                    ->label('Роль')
                    ->relationship('role', 'name')
                    ->required(),

                Forms\Components\Select::make('time_zone')
                    ->label('Часовой пояс')
                    ->options([
                    'Europe/Kaliningrad' => 'GMT+02:00 — Калининград',
                    'Europe/Moscow' => 'GMT+03:00 — Москва, Санкт-Петербург',
                    'Europe/Volgograd' => 'GMT+03:00 — Волгоград',
                    'Europe/Samara' => 'GMT+04:00 — Самара, Ульяновск',
                    'Asia/Yekaterinburg' => 'GMT+05:00 — Екатеринбург, Пермь, Челябинск',
                    'Asia/Omsk' => 'GMT+06:00 — Омск',
                    'Asia/Novosibirsk' => 'GMT+07:00 — Новосибирск, Томск',
                    'Asia/Barnaul' => 'GMT+07:00 — Барнаул, Алтайский край',
                    'Asia/Krasnoyarsk' => 'GMT+07:00 — Красноярск',
                    'Asia/Novokuznetsk' => 'GMT+07:00 — Новокузнецк, Кемерово',
                    'Asia/Irkutsk' => 'GMT+08:00 — Иркутск, Улан-Удэ',
                    'Asia/Chita' => 'GMT+09:00 — Чита, Забайкальский край',
                    'Asia/Yakutsk' => 'GMT+09:00 — Якутск, Нерюнгри',
                    'Asia/Khandyga' => 'GMT+09:00 — Хандыга',
                    'Asia/Vladivostok' => 'GMT+10:00 — Владивосток, Хабаровск',
                    'Asia/Ust-Nera' => 'GMT+10:00 — Усть-Нера',
                    'Asia/Sakhalin' => 'GMT+11:00 — Южно-Сахалинск',
                    'Asia/Magadan' => 'GMT+11:00 — Магадан',
                    'Asia/Srednekolymsk' => 'GMT+11:00 — Среднеколымск',
                    'Asia/Kamchatka' => 'GMT+12:00 — Петропавловск-Камчатский',
                    'Asia/Anadyr' => 'GMT+12:00 — Анадырь',
                    ]),

                Forms\Components\TextInput::make('password')
                    ->label('Пароль')
                    ->password()
                    ->dehydrateStateUsing(fn ($state) => !empty($state) ? bcrypt($state) : null)
                    ->dehydrated(fn ($state) => filled($state))
                    ->maxLength(255),
                    ])
            ]);
    }

    public static function table(Table $table): Table
    {
        return $table
            ->columns([
                Tables\Columns\TextColumn::make('name')
                    ->label('Имя')
                    ->searchable(),

                Tables\Columns\TextColumn::make('email')
                    ->label('Email')
                    ->searchable(),

                Tables\Columns\TextColumn::make('role.name')
                    ->label('Роль')
                    ->sortable(),

                Tables\Columns\TextColumn::make('time_zone')
                    ->label('Часовой пояс'),

                Tables\Columns\TextColumn::make('created_at')
                    ->label('Создано')
                    ->dateTime('d.m.Y H:i')
                    ->sortable(),
            ])
            ->filters([
                SelectFilter::make('role_id')
                    ->label('Фильтр по роли')
                    ->relationship('role', 'name')
                    ->placeholder('Все роли'),
            ])
            ->actions([
                Tables\Actions\EditAction::make()->label('Редактировать'),
            ])
            ->bulkActions([
                Tables\Actions\BulkActionGroup::make([
                    Tables\Actions\DeleteBulkAction::make()->label('Удалить'),
                ]),
            ]);
    }

    public static function getRelations(): array
    {
        return [
            // Можно добавить RelationManagers, если нужно
        ];
    }

    public static function getPages(): array
    {
        return [
            'index' => Pages\ListUsers::route('/'),
            'create' => Pages\CreateUser::route('/create'),
            'edit' => Pages\EditUser::route('/{record}/edit'),
        ];
    }

    public static function canAccess(): bool
    {
        return auth()->user()->hasPermission('view_user');
    }

    public static function shouldRegisterNavigation(): bool
    {
       return auth()->user()->hasPermission('view_user');
    }
}
