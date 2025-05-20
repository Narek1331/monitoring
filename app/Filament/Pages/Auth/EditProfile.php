<?php

namespace App\Filament\Pages\Auth;

use Filament\Forms\Components\TextInput;
use Filament\Forms\Form;
use Filament\Pages\Auth\EditProfile as BaseEditProfile;
use Filament\Forms\Components\Select;
use Filament\Actions\Action;

class EditProfile extends BaseEditProfile
{
    public function form(Form $form): Form
    {
        return $form
            ->schema([
                $this->getNameFormComponent(),
                $this->getEmailFormComponent(),
                Select::make('time_zone')
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
                $this->getPasswordFormComponent(),
                $this->getPasswordConfirmationFormComponent()
            ]);
    }

     protected function getFormActions(): array
    {
        return [
            Action::make('save')
                ->label('Сохранить')
                ->submit('save'),

            Action::make('cancel')
                ->label('Назад')
                ->url('/account'),
        ];
    }
}
