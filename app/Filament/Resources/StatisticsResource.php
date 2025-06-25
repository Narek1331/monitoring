<?php

namespace App\Filament\Resources;

use App\Filament\Resources\StatisticsResource\Pages;
use App\Filament\Resources\StatisticsResource\RelationManagers;
use App\Models\{
    Task
};
use Filament\Forms;
use Filament\Forms\Form;
use Filament\Resources\Resource;
use Filament\Tables;
use Filament\Tables\Table;
use Illuminate\Database\Eloquent\Builder;
use Illuminate\Database\Eloquent\SoftDeletingScope;
use Filament\Tables\Columns\{
    TextColumn,
    ToggleColumn,
    BadgeColumn
};
use Illuminate\Support\Facades\Http;
use Carbon\Carbon;
class StatisticsResource extends Resource
{
    protected static ?string $model = Task::class;

    protected static ?string $navigationIcon = 'heroicon-o-rectangle-stack';

    protected static ?string $title = 'Статистика';

    // protected static ?string $navigationGroup = '';

    protected static ?string $navigationLabel = 'Статистика';

    protected static ?string $pluralLabel = 'Статистика';

    protected static ?string $navigationLabelName = 'Статистика';

    protected static ?string $recordTitleAttribute = 'name';

    public static function getGloballySearchableAttributes(): array
    {
        return ['name'];
    }

    public static function form(Form $form): Form
    {
        return $form
            ->schema([]);
    }

    public static function table(Table $table): Table
    {
        return $table
            ->columns([
                TextColumn::make('name')
                    ->label('Название')
                    ->searchable(),
                TextColumn::make('verificationMethod.title')
                    ->label('Метод проверки')
                    ->searchable(),
                TextColumn::make('last_message')
                    ->getStateUsing(function($record){
                        $message = $record->messages->last();

                        if($message)
                        {
                            return $message->text;
                        }
                    })
                    ->label('Последнее сообщение'),
                BadgeColumn::make('uptime')
                    ->label('Статус')
                    ->getStateUsing(function ($record) {
                        $url = $record->address_ip;

                        try {
                            $response = \Illuminate\Support\Facades\Http::timeout(5)->get($url);
                            return $response->successful() ? 'Доступен' : 'Недоступен';
                        } catch (\Exception $e) {
                            return 'Недоступен';
                        }
                    })
                    ->colors([
                        'success' => 'Доступен',
                        'danger' => 'Недоступен',
                    ]),
                BadgeColumn::make('response_time')
                    ->label('Скорость (мс)')
                    ->getStateUsing(function ($record) {
                        $url = $record->address_ip;

                        try {
                            $start = microtime(true);
                            Http::timeout(5)->get($url);
                            $end = microtime(true);

                            return intval(($end - $start) * 1000);
                        } catch (\Exception $e) {
                            return null;
                        }
                    })
                    ->colors([
                        'success' => fn ($state) => $state !== null && $state < 300,
                        'warning' => fn ($state) => $state !== null && $state >= 300 && $state < 1000,
                        'danger' => fn ($state) => $state !== null && $state >= 1000,
                        'secondary' => fn ($state) => $state === null,
                    ])
                    ->formatStateUsing(fn ($state) => $state !== null ? "{$state} мс" : 'Ошибка'),
                BadgeColumn::make('ssl_expiration')
                    ->label('SSL сертификат')
                    ->getStateUsing(function ($record) {
                        $host = parse_url($record->address_ip, PHP_URL_HOST) ?? $record->address_ip;

                        $context = stream_context_create([
                            "ssl" => [
                                "capture_peer_cert" => true,
                                "verify_peer" => false,
                                "verify_peer_name" => false,
                            ],
                        ]);

                        try {
                            $client = @stream_socket_client("ssl://{$host}:443", $errno, $errstr, 5, STREAM_CLIENT_CONNECT, $context);

                            if (!$client) {
                                return 'Ошибка';
                            }

                            $params = stream_context_get_params($client);
                            $cert = openssl_x509_parse($params["options"]["ssl"]["peer_certificate"]);

                            if (isset($cert['validTo_time_t'])) {
                                return Carbon::createFromTimestamp($cert['validTo_time_t'])->format('Y-m-d');
                            }

                            return 'Ошибка';
                        } catch (\Exception $e) {
                            return 'Ошибка';
                        }
                    })
                    ->colors([
                        'success' => fn ($state) => $state !== 'Ошибка' && Carbon::parse($state)->diffInDays(now()) > 30,
                        'warning' => fn ($state) => $state !== 'Ошибка' && Carbon::parse($state)->diffInDays(now()) <= 30 && Carbon::parse($state)->isFuture(),
                        'danger' => fn ($state) => $state !== 'Ошибка' && Carbon::parse($state)->isPast(),
                        'secondary' => fn ($state) => $state === 'Ошибка',
                    ])

            ])
            ->filters([
                //
            ])
            ->actions([
            ])
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
            'index' => Pages\ListStatistics::route('/'),
            'view' => Pages\ViewStatistics::route('/{record}/view'),

        ];
    }
}
