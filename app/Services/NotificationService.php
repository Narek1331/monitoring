<?php

namespace App\Services;

use GuzzleHttp\Client;
use GuzzleHttp\Exception\RequestException;
use GuzzleHttp\Exception\ConnectException;
use App\Models\{
    Task,
    TaskMessage
};
use App\Notifications\SendEmailNotification;
use Illuminate\Support\Facades\Notification;
use App\Models\TelegramUser;
class NotificationService
{

    public $telegramService;

    public function __construct(TelegramService $telegramService)
    {
        $this->telegramService = $telegramService;
    }

    public function send()
    {
        $taskMessages = TaskMessage::where('sended',false)->get();

        foreach($taskMessages as $taskMessage)
        {
            $task = $taskMessage->task;
            $errorNotificationThreshold = $task->error_notification_threshold ?? 1;

            if($taskMessage->status)
            {
                foreach($task->reportContacts as $reportContact)
                {
                    if($reportContact->type->slug == 'email')
                    {
                        $this->sendEmailNotification($reportContact->email,$taskMessage->text);
                    }else if($reportContact->type->slug == 'telegram')
                    {
                        if($telegramUser = TelegramUser::where('token',$reportContact->tg_verification_code)->first())
                        {
                            $this->telegramService->sendMessage($telegramUser->chat_id,$taskMessage->text);
                        }
                    }
                }

                $taskMessage->sended = 1;
                $taskMessage->save();

            } else {
                $checkQuery = TaskMessage::where('status',false)
                ->where('sended',false)
                ->where('text',$taskMessage->text);

                if($checkQuery->count() == $errorNotificationThreshold)
                {
                    foreach($task->reportContacts as $reportContact)
                    {
                        if($reportContact->type->slug == 'email')
                        {
                            $this->sendEmailNotification($reportContact->email,$taskMessage->text);
                        }
                        else if($reportContact->type->slug == 'telegram')
                        {
                            if($telegramUser = TelegramUser::where('token',$reportContact->tg_verification_code)->first())
                            {
                                $this->telegramService->sendMessage($telegramUser->chat_id,$taskMessage->text);
                            }
                        }
                    }

                    $checkQuery->update(['sended'=>true]);
                }
            }


        }
    }

    private function sendEmailNotification($email,$message)
    {
        Notification::route('mail', $email)->notify(new SendEmailNotification($message));

    }

}
