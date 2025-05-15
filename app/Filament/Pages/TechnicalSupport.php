<?php

namespace App\Filament\Pages;

use Filament\Pages\Page;
use App\Models\TechnicalSupport as TechnicalSupportModel;
use Filament\Notifications\Notification;

class TechnicalSupport extends Page
{
    public function __construct()
    {
        $this->email = auth()->user()->email;
    }
    protected static ?string $navigationIcon = 'heroicon-o-document-text';

    protected static string $view = 'filament.pages.technical-support';

    protected static ?string $pluralLabel = 'Техническая поддержка';

    protected static ?string $navigationLabelName = 'Техническая поддержка';

    protected static ?string $title = 'Техническая поддержка';

    protected static ?int $navigationSort = 6;


    public $email = '';
    public $name = '';
    public $subject = '';
    public $message = '';

    public function send()
    {

        if($this->email && $this->name && $this->subject && $this->message)
        {
            TechnicalSupportModel::create([
                'email' => $this->email,
                'name' => $this->name,
                'subject' => $this->subject,
                'message' => $this->message,
            ]);

            Notification::make()
                ->title('Ваше сообщение было успешно отправлено')
                ->success()
                ->send();

            $this->email = '';
            $this->name= '';
            $this->subject= '';
            $this->message= '';
        }else{
            Notification::make()
                ->title('Заполните все поля')
                ->warning()
                ->send();
        }


    }

}
