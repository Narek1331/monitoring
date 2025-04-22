<?php

namespace App\Filament\Resources;

use App\Filament\Resources\TaskResource\Pages;
use App\Filament\Resources\TaskResource\RelationManagers;
use App\Models\{
    Task,
    InspectionCost
};
use Filament\Forms;
use Filament\Forms\Form;
use Filament\Resources\Resource;
use Filament\Tables;
use Filament\Tables\Table;
use Illuminate\Database\Eloquent\Builder;
use Illuminate\Database\Eloquent\SoftDeletingScope;
use Filament\Forms\Components\{
    Wizard,
    Radio,
    TextInput,
    Grid,
    Card,
    Select,
    Toggle,
    Textarea,
    CheckboxList
};
use Filament\Tables\Columns\{
    TextColumn,
    ToggleColumn
};
use App\Models\VerificationMethod;
use Filament\Forms\Components\Actions\Action;
class TaskResource extends Resource
{
    use \App\Traits\User\GetHelper;

    protected static ?string $model = Task::class;

    protected static ?string $navigationIcon = 'heroicon-o-rectangle-stack';


    protected static ?string $title = 'Задания';

    // protected static ?string $navigationGroup = '';

    protected static ?string $navigationLabel = 'Задания';

    protected static ?string $pluralLabel = 'Задания';

    protected static ?string $navigationLabelName = 'Задания';

    protected static ?int $navigationSort = 1;

    public static function form(Form $form): Form
    {
        return $form
            ->schema([
                Wizard::make([
                    Wizard\Step::make(' Метод проверки задания')
                        ->icon('heroicon-m-document-check')
                        ->schema([
                            Radio::make('verification_method_id')
                            ->label('')
                            ->required()
                            ->options(VerificationMethod::pluck('title','id')->toArray())
                            ->descriptions(VerificationMethod::pluck('description','id')->toArray())
                            ->reactive()

                        ]),
                    Wizard\Step::make('Сайт или сервер для проверки')
                        ->icon('heroicon-m-computer-desktop')
                        ->schema(function($get){
                            $verificationMethodId = $get('verification_method_id');

                            if($verificationMethodId == 1)
                            {
                                return [...self::siteCheckFirstTemplate()];
                            }else if($verificationMethodId == 2)
                            {
                                return [...self::siteCheckSecondTemplate()];
                            }else if($verificationMethodId == 3)
                            {
                                return [...self::siteCheckThirdTemplate()];
                            }

                            return [];

                        }),
                    Wizard\Step::make('Периодичность проверки')
                        ->icon('heroicon-m-clock')
                        ->schema([
                            Select::make('frequency_of_inspection')
                                ->label('Периодичность проверки')
                                ->required()
                                ->columnSpan(1)
                                ->options([
                                    '* * * * *'         => 'Раз в 1 минуту',
                                    '*/2 * * * *'       => 'Раз в 2 минуты',
                                    '*/3 * * * *'       => 'Раз в 3 минуты',
                                    '*/5 * * * *'       => 'Раз в 5 минут',
                                    '*/10 * * * *'      => 'Раз в 10 минут',
                                    '*/15 * * * *'      => 'Раз в 15 минут',
                                    '*/20 * * * *'      => 'Раз в 20 минут',
                                    '*/30 * * * *'      => 'Раз в 30 минут',
                                    '0 * * * *'         => 'Раз в 1 час',
                                    '0 */3 * * *'       => 'Раз в 3 часа',
                                    '0 */6 * * *'       => 'Раз в 6 часов',
                                    '0 */12 * * *'      => 'Раз в 12 часов',
                                    '0 0 * * *'         => 'Раз в сутки',
                                    '0 0 */3 * *'       => 'Раз в 3 дня',
                                    '0 0 * * 0'         => 'Раз в неделю',
                                    '0 0 1 * *'         => 'Раз в 30 дней',
                                ])
                                ->disablePlaceholderSelection(true)
                                ->default('*/5 * * * *')
                                ->helperText('Нужно выбрать, как часто мы должны проверять ваш сайт или сервер. Оптимальным интервалом считается проверка раз в 5 минут.'),

                            Select::make('error_check_interval')
                                ->label('Периодичность проверки в момент, когда случается ошибка в работе вашего сайта (сервера)')
                                ->required()
                                ->columnSpan(1)
                                ->options([
                                    '* * * * *'         => 'Раз в 1 минуту',
                                    '*/2 * * * *'       => 'Раз в 2 минуты',
                                    '*/3 * * * *'       => 'Раз в 3 минуты',
                                    '*/5 * * * *'       => 'Раз в 5 минут',
                                    '*/10 * * * *'      => 'Раз в 10 минут',
                                    '*/15 * * * *'      => 'Раз в 15 минут',
                                    '*/20 * * * *'      => 'Раз в 20 минут',
                                    '*/30 * * * *'      => 'Раз в 30 минут',
                                    '0 * * * *'         => 'Раз в 1 час',
                                    '0 */3 * * *'       => 'Раз в 3 часа',
                                    '0 */6 * * *'       => 'Раз в 6 часов',
                                    '0 */12 * * *'      => 'Раз в 12 часов',
                                    '0 0 * * *'         => 'Раз в сутки',
                                    '0 0 */3 * *'       => 'Раз в 3 дня',
                                    '0 0 * * 0'         => 'Раз в неделю',
                                    '0 0 1 * *'         => 'Раз в 30 дней',
                                ])
                                ->disablePlaceholderSelection(true)
                                ->default('*/2 * * * *')
                                ->helperText('Здесь необходимо выбрать, как часто мы будем проверять ваш сайт или сервер во время возникновения ошибки в его работе. Нужно сделать чаще, чем обычный интервал - тогда как только работа восстановится, мы вас сразу уведомим. Выберите в два-три раза меньше, чем обычная периодичность, или еще чаще.')
                        ]),
                        Wizard\Step::make('Настройки уведомлений')
                        ->icon('heroicon-m-speaker-wave')
                        ->schema(function($get){
                            $verificationMethodId = $get('verification_method_id');

                            if($verificationMethodId == 1)
                            {
                                return [...self::notificationSettingsFirstTemplate()];
                            }else if($verificationMethodId == 2)
                            {
                                return [...self::notificationSettingsSecondTemplate()];
                            }else if($verificationMethodId == 3)
                            {
                                return [...self::notificationSettingsThirdTemplate()];
                            }

                            return [];

                        }),
                        Wizard\Step::make('Дополнительные настройки')
                        ->icon('heroicon-m-adjustments-horizontal')
                        ->schema(function($get){
                            $verificationMethodId = $get('verification_method_id');

                            if($verificationMethodId == 1)
                            {
                                return [...self::additionalSettingsFirstTemplate()];
                            }else if($verificationMethodId == 2)
                            {
                                return [...self::additionalSettingsSecondTemplate()];
                            }else if($verificationMethodId == 3)
                            {
                                return [...self::additionalSettingsSecondTemplate()];
                            }

                            return [];

                        }),
                        Wizard\Step::make('Отчеты о работе вашего сайта/сервера')
                        ->icon('heroicon-m-list-bullet')
                        ->schema([
                            CheckboxList::make('reportFrequencies')
                                ->label('Выберите периодичность отправки отчетов:')
                                ->relationship('reportFrequencies', 'name') 
                                ->helperText('Если вы хотите получать на свой e-mail отчеты о работе своего сайта или сервера, выберите как часто мы будем вам их присылать.'),

                            Select::make('errorNotificationContacts')
                                ->disablePlaceholderSelection(true)
                                ->multiple()
                                ->relationship('errorNotificationContacts','id')
                                ->label('Выберите контакты для отправки отчетов:')
                                ->preload()
                                ->columnSpan(1)
                                ->helperText('Выберите контакты, на которые мы будем отправлять отчеты. Не более 10 контактов.')
        
                        ]),
                        Wizard\Step::make('Стоимость проверок')
                        ->icon('heroicon-m-banknotes')
                        ->schema([
                            Radio::make('inspection_cost_id')
                            ->label('')
                            ->required()
                            ->options(InspectionCost::pluck('title','id')->toArray())
                            ->descriptions(InspectionCost::pluck('description','id')->toArray())
                            ->reactive()
                        ])
                ])
                ->nextAction(
                    fn (Action $action) => $action->label('Следующий'),
                )
                ->columnSpan('full')

                    ]);
    }

    public static function table(Table $table): Table
    {
        return $table
            ->columns([
                TextColumn::make('address_ip')
                    ->label('Задание')
                    ->searchable()
                    ->sortable(),
                ToggleColumn::make('status')
                    ->label('Статус')
                    ->sortable(),
                TextColumn::make('created_at')
                    ->label('Дата')
                    ->sortable()
                    ->dateTime(),
            ])
            ->filters([
                //
            ])
            ->actions([
                Tables\Actions\EditAction::make(),
            ])
            ->bulkActions([
                Tables\Actions\BulkActionGroup::make([
                    Tables\Actions\DeleteBulkAction::make(),
                ]),
            ]);
    }

    public static function siteCheckFirstTemplate()
    {
        $form = [];


        $form[] = TextInput::make('name')
            ->label('Название задания')
            ->required()
            ->columnSpan(1)
            ->helperText('Это название вы будете видеть в списке всех заданий, а также получать в уведомлениях об ошибках.');

        $form[] = Grid::make(3)
            ->schema([
                Select::make('protocol')
                    ->label('Протокол')
                    ->required()
                    ->columnSpan(1)
                    ->options([
                        'http://' => 'http://',
                        'https://' => 'https://',
                        'ip:' => 'ip:',
                    ])
                    ->disablePlaceholderSelection(true)
                    ->default('http://'),

                TextInput::make('address_ip')
                    ->label('Адрес сайта или IP сервера')
                    ->required()
                    ->columnSpan(2)
            ]);

        $form[] = TextInput::make('port')
            ->label('Порт (в случае необходимости проверки определенного порта):')
            ->columnSpan(1)
            ->helperText('Вы можете указать порт для проверки, если он отличный от стандартного. Если вы не знаете что это такое, значит можете ничего не указывать.');

        $form[] = Toggle::make('control_domain')
            ->label('Контролировать домен')
            ->default(false)
            ->columnSpan(1)
            ->helperText('Если поставить здесь галочку, то мы будем следить за сроком регистрации вашего домена и как только он будет подходить к концу - пришлем уведомление, чтобы вы не забыли его продлить. Стоимость - 30 рублей в год. Списывается одной суммой в момент включения.');

        $form[] = Toggle::make('site_virus_check')
            ->label('Проверять сайт на вирусы и на наличие в запрещенных базах TOP')
            ->default(false)
            ->columnSpan(1)
            ->helperText('Если поставить здесь галочку, то мы будем проверять отсутствие на вашем сайте вирусных вставок, редиректов (мобильных, поисковых и др.), проверять ваш сайт по базам VirusTotal, Роскомнадзора, антивирусов, черным спискам Яндекса и Google. В случае любых отклонений от нормы уведомляем вас.');

        return $form;
    }

    public static function siteCheckSecondTemplate()
    {
        $form = [];


        $form[] = TextInput::make('name')
            ->label('Название задания')
            ->required()
            ->columnSpan(1)
            ->helperText('Это название вы будете видеть в списке всех заданий, а также получать в уведомлениях об ошибках.');

        $form[] = Grid::make(3)
            ->schema([
                Select::make('protocol')
                    ->label('Протокол')
                    ->required()
                    ->columnSpan(1)
                    ->options([
                        'http://' => 'http://',
                        'https://' => 'https://',
                        'ip:' => 'ip:',
                    ])
                    ->disablePlaceholderSelection(true)
                    ->default('http://'),

                TextInput::make('address_ip')
                    ->label('Адрес сайта или IP сервера')
                    ->required()
                    ->columnSpan(2)
            ]);
        $form[] = Toggle::make('control_domain')
            ->label('Контролировать домен')
            ->default(false)
            ->columnSpan(1)
            ->helperText('Если поставить здесь галочку, то мы будем следить за сроком регистрации вашего домена и как только он будет подходить к концу - пришлем уведомление, чтобы вы не забыли его продлить. Стоимость - 30 рублей в год. Списывается одной суммой в момент включения.');

        return $form;
    }

    public static function siteCheckThirdTemplate()
    {
        $form = [];


        $form[] = TextInput::make('name')
            ->label('Название задания')
            ->required()
            ->columnSpan(1)
            ->helperText('Это название вы будете видеть в списке всех заданий, а также получать в уведомлениях об ошибках.');

        $form[] = Grid::make(3)
            ->schema([
                Select::make('protocol')
                    ->label('Протокол')
                    ->required()
                    ->columnSpan(1)
                    ->options([
                        'http://' => 'http://',
                        'https://' => 'https://',
                        'ip:' => 'ip:',
                    ])
                    ->disablePlaceholderSelection(true)
                    ->default('http://'),

                TextInput::make('address_ip')
                    ->label('Адрес сайта или IP сервера')
                    ->required()
                    ->columnSpan(2)
            ]);

        $form[] = TextInput::make('port')
            ->label('Порт (в случае необходимости проверки определенного порта):')
            ->columnSpan(1)
            ->helperText('Вы можете указать порт для проверки, если он отличный от стандартного. Если вы не знаете что это такое, значит можете ничего не указывать.');

        $form[] = Toggle::make('control_domain')
            ->label('Контролировать домен')
            ->default(false)
            ->columnSpan(1)
            ->helperText('Если поставить здесь галочку, то мы будем следить за сроком регистрации вашего домена и как только он будет подходить к концу - пришлем уведомление, чтобы вы не забыли его продлить. Стоимость - 30 рублей в год. Списывается одной суммой в момент включения.');

        $form[] = Toggle::make('site_virus_check')
            ->label('Проверять сайт на вирусы и на наличие в запрещенных базах TOP')
            ->default(false)
            ->columnSpan(1)
            ->helperText('Если поставить здесь галочку, то мы будем проверять отсутствие на вашем сайте вирусных вставок, редиректов (мобильных, поисковых и др.), проверять ваш сайт по базам VirusTotal, Роскомнадзора, антивирусов, черным спискам Яндекса и Google. В случае любых отклонений от нормы уведомляем вас.');

        return $form;
    }
    
    public static function notificationSettingsFirstTemplate()
    {
        $form = [];

        $form[] = Select::make('reportContacts')
            ->disablePlaceholderSelection(true)
            ->multiple()
            ->relationship('reportContacts','id')
            ->label('Контакты для отправки уведомлений об ошибках')
            ->columnSpan(1)
            ->preload()
            ->helperText('Выберите контакты, на которые мы будем отправлять уведомления об ошибках в работе задания и о восстановлении после ошибки. Не более 30 контактов.');
        

        $form[] = TextInput::make('error_notification_threshold')
            ->label('После скольких ошибок отправлять уведомления')
            ->columnSpan(1)
            ->helperText('Для предотвращения ложных срабатываний системы, рекомендуем установить отправку уведомлений после 2 ошибок (подряд). Это предотвратит ложные срабатывания, и в тоже время вы узнаете об ошибке.');
        
        $form[] = TextInput::make('remind_on_error')
            ->label('Напоминать ли об ошибке')
            ->columnSpan(1)
            ->helperText('Вы можете указать нужно ли повторять уведомления об ошибке (если она долго не устраняется) и как часто это делать. Данный период не может быть меньше периода проверок во время ошибки. Если выбран меньший период, он автоматически будет приравнен к периоду проверок во время ошибки. Обратите внимание, повторные уведомления отправляются по всем указанным вами контактам, кроме СМС и звонков (в связи с ограничением мобильных операторов на отправку одинаковых сообщений).');

        $form[] = Toggle::make('notify_on_recovery')
            ->label('Отправлять уведомление о возобновлении работы сайта или сервера')
            ->default(false)
            ->columnSpan(1)
            ->helperText('Если выбрать, то как только работа вашего сайта или сервера восстановится - мы вам сразу отправим уведомление об этом. Если не выбирать - отправлять не будем.');

        $form[] = Toggle::make('task_status_rss')
            ->label('Создать RSS ленту со статусами задания')
            ->default(false)
            ->columnSpan(1)
            ->helperText('Вы можете подписаться на RSS ленту со статусами своего задания и получать уведомления об ошибках через RSS. Если хотите - поставьте галочку.');

        $form[] = Textarea::make('error_message')
            ->label('Информация для письма с уведомлением об ошибке')
            ->columnSpan(1)
            ->helperText('Вы можете указать какую информацию включать в письмо на e-mail с уведомлением об ошибке. Например, команду для перезагрузки сервера или информацию о том, в каком дата-центре сервер (или у какого хостинг-провайдера куплен хостинг для сайта) и как связаться со службой поддержки. Не более 300 символов. Необязательно для заполнения.');
        
            return $form;
    }

    public static function notificationSettingsSecondTemplate()
    {
        $form = [];

        $form[] = Select::make('reportContacts')
            ->disablePlaceholderSelection(true)
            ->multiple()
            ->relationship('reportContacts','id')
            ->label('Контакты для отправки уведомлений об ошибках')
            ->columnSpan(1)
            ->preload()
            ->helperText('Выберите контакты, на которые мы будем отправлять уведомления об ошибках в работе задания и о восстановлении после ошибки. Не более 30 контактов.');
        

        $form[] = TextInput::make('error_notification_threshold')
            ->label('После скольких ошибок отправлять уведомления')
            ->columnSpan(1)
            ->helperText('Для предотвращения ложных срабатываний системы, рекомендуем установить отправку уведомлений после 2 ошибок (подряд). Это предотвратит ложные срабатывания, и в тоже время вы узнаете об ошибке.');
        
        $form[] = TextInput::make('remind_on_error')
            ->label('Напоминать ли об ошибке')
            ->columnSpan(1)
            ->helperText('Вы можете указать нужно ли повторять уведомления об ошибке (если она долго не устраняется) и как часто это делать. Данный период не может быть меньше периода проверок во время ошибки. Если выбран меньший период, он автоматически будет приравнен к периоду проверок во время ошибки. Обратите внимание, повторные уведомления отправляются по всем указанным вами контактам, кроме СМС и звонков (в связи с ограничением мобильных операторов на отправку одинаковых сообщений).');

        $form[] = Toggle::make('notify_on_recovery')
            ->label('Отправлять уведомление о возобновлении работы сайта или сервера')
            ->default(false)
            ->columnSpan(1)
            ->helperText('Если выбрать, то как только работа вашего сайта или сервера восстановится - мы вам сразу отправим уведомление об этом. Если не выбирать - отправлять не будем.');

        $form[] = Toggle::make('task_status_rss')
            ->label('Создать RSS ленту со статусами задания')
            ->default(false)
            ->columnSpan(1)
            ->helperText('Вы можете подписаться на RSS ленту со статусами своего задания и получать уведомления об ошибках через RSS. Если хотите - поставьте галочку.');

        $form[] = Textarea::make('error_message')
            ->label('Информация для письма с уведомлением об ошибке')
            ->columnSpan(1)
            ->helperText('Вы можете указать какую информацию включать в письмо на e-mail с уведомлением об ошибке. Например, команду для перезагрузки сервера или информацию о том, в каком дата-центре сервер (или у какого хостинг-провайдера куплен хостинг для сайта) и как связаться со службой поддержки. Не более 300 символов. Необязательно для заполнения.');
        
            return $form;
    }

    public static function notificationSettingsThirdTemplate()
    {
        $form = [];

        $form[] = Select::make('reportContacts')
            ->disablePlaceholderSelection(true)
            ->multiple()
            ->relationship('reportContacts','id')
            ->label('Контакты для отправки уведомлений об ошибках')
            ->columnSpan(1)
            ->preload()
            ->helperText('Выберите контакты, на которые мы будем отправлять уведомления об ошибках в работе задания и о восстановлении после ошибки. Не более 30 контактов.');
        

        $form[] = TextInput::make('error_notification_threshold')
            ->label('После скольких ошибок отправлять уведомления')
            ->columnSpan(1)
            ->helperText('Для предотвращения ложных срабатываний системы, рекомендуем установить отправку уведомлений после 2 ошибок (подряд). Это предотвратит ложные срабатывания, и в тоже время вы узнаете об ошибке.');
        
        $form[] = TextInput::make('remind_on_error')
            ->label('Напоминать ли об ошибке')
            ->columnSpan(1)
            ->helperText('Вы можете указать нужно ли повторять уведомления об ошибке (если она долго не устраняется) и как часто это делать. Данный период не может быть меньше периода проверок во время ошибки. Если выбран меньший период, он автоматически будет приравнен к периоду проверок во время ошибки. Обратите внимание, повторные уведомления отправляются по всем указанным вами контактам, кроме СМС и звонков (в связи с ограничением мобильных операторов на отправку одинаковых сообщений).');

        $form[] = Toggle::make('notify_on_recovery')
            ->label('Отправлять уведомление о возобновлении работы сайта или сервера')
            ->default(false)
            ->columnSpan(1)
            ->helperText('Если выбрать, то как только работа вашего сайта или сервера восстановится - мы вам сразу отправим уведомление об этом. Если не выбирать - отправлять не будем.');

        $form[] = Toggle::make('task_status_rss')
            ->label('Создать RSS ленту со статусами задания')
            ->default(false)
            ->columnSpan(1)
            ->helperText('Вы можете подписаться на RSS ленту со статусами своего задания и получать уведомления об ошибках через RSS. Если хотите - поставьте галочку.');

        $form[] = Textarea::make('error_message')
            ->label('Информация для письма с уведомлением об ошибке')
            ->columnSpan(1)
            ->helperText('Вы можете указать какую информацию включать в письмо на e-mail с уведомлением об ошибке. Например, команду для перезагрузки сервера или информацию о том, в каком дата-центре сервер (или у какого хостинг-провайдера куплен хостинг для сайта) и как связаться со службой поддержки. Не более 300 символов. Необязательно для заполнения.');
        
            return $form;
    }
    
    public static function additionalSettingsFirstTemplate(): array
    {
        return [
            Select::make('site_timeout_duration')
                ->label('Сколько секунд ждать ответа от сайта или сервера')
                ->options([
                    '3' => '3 секунды', '4' => '4 секунды', '5' => '5 секунд', '6' => '6 секунд',
                    '7' => '7 секунд', '8' => '8 секунд', '9' => '9 секунд', '10' => '10 секунд',
                    '11' => '11 секунд', '12' => '12 секунд', '13' => '13 секунд', '14' => '14 секунд',
                    '15' => '15 секунд', '20' => '20 секунд', '25' => '25 секунд',
                ])
                ->disablePlaceholderSelection()
                ->default('8')
                ->columnSpan(1)
                ->helperText('При проверке мы будем ждать указанное количество секунд. Если ответа не будет — мы посчитаем это ошибкой и сообщим вам.'),
    
            TextInput::make('search_text_in_response')
                ->label('Текст для поиска на странице / в ответе сервера (который есть всегда и при пропаже которого считать ошибкой)')
                ->columnSpan(1)
                ->maxLength(200)
                ->helperText('Максимум 200 символов. Можно указать скрытый HTML-комментарий для отслеживания.'),
    
            TextInput::make('text_presence_error_check')
                ->label('Текст для поиска на странице / в ответе сервера (которого нет изначально и при появлении которого считать ошибкой)')
                ->columnSpan(1)
                ->maxLength(200)
                ->helperText('Максимум 200 символов. Например, "error" в случае ошибки на сайте.'),
    
            TextInput::make('header_for_request')
                ->label('Заголовок (Header), который необходимо отправлять к сайту / серверу')
                ->columnSpan(1)
                ->maxLength(250)
                ->helperText('Указывайте по одному на строчку: Header: Value. Разрешены латиница, цифры, двоеточие, тире, слэш.'),
    
            Select::make('timezone')
                ->label('Мое время')
                ->options([
                    '-12' => '(Москва -12 ч.)', '-11' => '(Москва -11 ч.)', '-10' => '(Москва -10 ч.)',
                    '-9' => '(Москва -9 ч.)', '-8' => '(Москва -8 ч.)', '-7' => '(Москва -7 ч.)',
                    '-6' => '(Москва -6 ч.)', '-5' => '(Москва -5 ч.)', '-4' => '(Москва -4 ч.)',
                    '-3' => '(Москва -3 ч.)', '-2' => '(Москва -2 ч.)', '-1' => '(Москва -1 ч.)',
                    '0' => '(Москва)', '1' => '(Москва +1 ч.)', '2' => '(Москва +2 ч.)',
                    '3' => '(Москва +3 ч.)', '4' => '(Москва +4 ч.)', '5' => '(Москва +5 ч.)',
                    '6' => '(Москва +6 ч.)', '7' => '(Москва +7 ч.)', '8' => '(Москва +8 ч.)',
                    '9' => '(Москва +9 ч.)', '10' => '(Москва +10 ч.)',
                ])
                ->disablePlaceholderSelection()
                ->default('0')
                ->columnSpan(1)
                ->helperText('Время в выбранном часовом поясе будет использоваться в логах и уведомлениях.'),
    
            TextInput::make('valid_response_code')
                ->label('Код ответа сервера, который нужно считать верным')
                ->columnSpan(1)
                ->maxLength(3)
                ->helperText('По умолчанию — 200. Укажите другой, если нужно.'),
    
            TextInput::make('ignored_error_codes')
                ->label('Коды ответа сервера (ошибки), которые нужно игнорировать')
                ->columnSpan(1)
                ->maxLength(100)
                ->helperText('Цифры через запятую, например: 404,503,504'),
    
            TextInput::make('alert_on_specific_codes')
                ->label('Коды, при которых слать уведомление и считать это ошибкой')
                ->columnSpan(1)
                ->maxLength(100)
                ->helperText('Только при этих кодах будут отправляться уведомления, например: 404,503'),
    
            Toggle::make('follow_redirects')
                ->label('Следовать редиректам')
                ->default(false)
                ->columnSpan(1)
                ->helperText('Поставьте галочку, если нужно следовать редиректам (максимум 5).'),
    
            TextInput::make('user_agent')
                ->label('Юзер-агент')
                ->columnSpan(1)
                ->helperText('Укажите пользовательский агент, если нужно.'),
    
            TextInput::make('referrer')
                ->label('Реферер')
                ->columnSpan(1)
                ->helperText('Без http://. Например: yandex.ru'),
    
            Grid::make(2)->schema([
                TextInput::make('login')
                    ->label('Логин')
                    ->required(),
    
                TextInput::make('password')
                    ->label('Пароль')
                    ->required(),
            ]),
    
            Grid::make(2)->schema([
                TextInput::make('response_number_range')
                    ->label('Диапазон для числа в ответе')
                    ->required()
                    ->helperText('Формат: 9.99-99.99'),
    
                TextInput::make('page_size_range')
                    ->label('Диапазон размера страницы (байт)')
                    ->required()
                    ->helperText('Формат: 21000-22000 (размер в байтах)'),
            ]),
    
            Toggle::make('save_response_time')
                ->label('Сохранять время ответа')
                ->default(false)
                ->columnSpan(1)
                ->helperText('Для построения графика по времени ответа.'),
    
            Toggle::make('notify_on_dns_error_9')
                ->label('Сообщать об ошибке DNS (код 9)')
                ->default(false)
                ->columnSpan(1),
    
            Toggle::make('notify_on_empty_response_6x')
                ->label('Сообщать о пустом ответе (коды 6x)')
                ->default(false)
                ->columnSpan(1),
    
            Toggle::make('set_as_template')
                ->label('Установить задание как образец')
                ->default(false)
                ->columnSpan(1),
        ];
    }

    public static function additionalSettingsSecondTemplate(): array
    {
        return [

            Toggle::make('dangerous_sites_detection')
                ->label('Считать наличие ссылок на опасные сайты ошибкой')
                ->default(false)
                ->columnSpan(1)
                ->helperText(''),
            
            Toggle::make('send_critical_error_alerts')
                ->label('Присылать уведомления только о критичных ошибках')
                ->default(false)
                ->columnSpan(1)
                ->helperText(''),
            
            Toggle::make('ignore_error_recovery')
                ->label('Игнорировать восстановление после ошибки')
                ->default(false)
                ->columnSpan(1)
                ->helperText(''),
            
            
            Toggle::make('notify_on_rkn_domain_detection')
                ->label('Присылать уведомления только о том, когда в базе РКН будет обнаружен именно домен')
                ->default(false)
                ->columnSpan(1)
                ->helperText(''),
    
            Select::make('timezone')
                ->label('Мое время')
                ->options([
                    '-12' => '(Москва -12 ч.)', '-11' => '(Москва -11 ч.)', '-10' => '(Москва -10 ч.)',
                    '-9' => '(Москва -9 ч.)', '-8' => '(Москва -8 ч.)', '-7' => '(Москва -7 ч.)',
                    '-6' => '(Москва -6 ч.)', '-5' => '(Москва -5 ч.)', '-4' => '(Москва -4 ч.)',
                    '-3' => '(Москва -3 ч.)', '-2' => '(Москва -2 ч.)', '-1' => '(Москва -1 ч.)',
                    '0' => '(Москва)', '1' => '(Москва +1 ч.)', '2' => '(Москва +2 ч.)',
                    '3' => '(Москва +3 ч.)', '4' => '(Москва +4 ч.)', '5' => '(Москва +5 ч.)',
                    '6' => '(Москва +6 ч.)', '7' => '(Москва +7 ч.)', '8' => '(Москва +8 ч.)',
                    '9' => '(Москва +9 ч.)', '10' => '(Москва +10 ч.)',
                ])
                ->disablePlaceholderSelection()
                ->default('0')
                ->columnSpan(1)
                ->helperText('Время в выбранном часовом поясе будет использоваться в логах и уведомлениях.'),
    
    
            Toggle::make('set_as_template')
                ->label('Установить задание как образец')
                ->default(false)
                ->columnSpan(1),
        ];
    }

    public static function additionalSettingsThirdTemplate(): array
    {
        return [
            Select::make('site_timeout_duration')
                ->label('Сколько секунд ждать ответа от сайта или сервера')
                ->options([
                    '3' => '3 секунды', '4' => '4 секунды', '5' => '5 секунд', '6' => '6 секунд',
                    '7' => '7 секунд', '8' => '8 секунд', '9' => '9 секунд', '10' => '10 секунд',
                    '11' => '11 секунд', '12' => '12 секунд', '13' => '13 секунд', '14' => '14 секунд',
                    '15' => '15 секунд', '20' => '20 секунд', '25' => '25 секунд',
                ])
                ->disablePlaceholderSelection()
                ->default('8')
                ->columnSpan(1)
                ->helperText('При проверке мы будем ждать указанное количество секунд. Если ответа не будет — мы посчитаем это ошибкой и сообщим вам.'),
    
            Select::make('timezone')
                ->label('Мое время')
                ->options([
                    '-12' => '(Москва -12 ч.)', '-11' => '(Москва -11 ч.)', '-10' => '(Москва -10 ч.)',
                    '-9' => '(Москва -9 ч.)', '-8' => '(Москва -8 ч.)', '-7' => '(Москва -7 ч.)',
                    '-6' => '(Москва -6 ч.)', '-5' => '(Москва -5 ч.)', '-4' => '(Москва -4 ч.)',
                    '-3' => '(Москва -3 ч.)', '-2' => '(Москва -2 ч.)', '-1' => '(Москва -1 ч.)',
                    '0' => '(Москва)', '1' => '(Москва +1 ч.)', '2' => '(Москва +2 ч.)',
                    '3' => '(Москва +3 ч.)', '4' => '(Москва +4 ч.)', '5' => '(Москва +5 ч.)',
                    '6' => '(Москва +6 ч.)', '7' => '(Москва +7 ч.)', '8' => '(Москва +8 ч.)',
                    '9' => '(Москва +9 ч.)', '10' => '(Москва +10 ч.)',
                ])
                ->disablePlaceholderSelection()
                ->default('0')
                ->columnSpan(1)
                ->helperText('Время в выбранном часовом поясе будет использоваться в логах и уведомлениях.'),
    
            Toggle::make('follow_redirects')
                ->label('Следовать редиректам')
                ->default(false)
                ->columnSpan(1)
                ->helperText('Поставьте галочку, если нужно следовать редиректам (максимум 5).'),
    
            TextInput::make('user_agent')
                ->label('Юзер-агент')
                ->columnSpan(1)
                ->helperText('Укажите пользовательский агент, если нужно.'),
    
            TextInput::make('referrer')
                ->label('Реферер')
                ->columnSpan(1)
                ->helperText('Без http://. Например: yandex.ru'),
    
            Grid::make(2)->schema([
                TextInput::make('login')
                    ->label('Логин')
                    ->required(),
    
                TextInput::make('password')
                    ->label('Пароль')
                    ->required(),
            ]),
    
            Toggle::make('save_response_time')
                ->label('Сохранять время ответа')
                ->default(false)
                ->columnSpan(1)
                ->helperText('Для построения графика по времени ответа.'),
    
            Toggle::make('notify_on_dns_error_9')
                ->label('Сообщать об ошибке DNS (код 9)')
                ->default(false)
                ->columnSpan(1),
    
            Toggle::make('notify_on_empty_response_6x')
                ->label('Сообщать о пустом ответе (коды 6x)')
                ->default(false)
                ->columnSpan(1),
    
            Toggle::make('set_as_template')
                ->label('Установить задание как образец')
                ->default(false)
                ->columnSpan(1),
        ];
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
            'index' => Pages\ListTasks::route('/'),
            'create' => Pages\CreateTask::route('/create'),
            'edit' => Pages\EditTask::route('/{record}/edit'),
        ];
    }
}
