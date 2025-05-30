<?php

namespace App\Services;

use Carbon\Carbon;
use App\Models\Task;
use App\Exports\TaskMessageExport;
use Maatwebsite\Excel\Facades\Excel;
use App\Exports\ReportExport;
use Illuminate\Support\Str;
use App\Models\TelegramUser;
use App\Notifications\SendEmailNotification;
use Illuminate\Support\Facades\Notification;
class BackupService
{
    public $telegramService;

    public function __construct(TelegramService $telegramService)
    {
        $this->telegramService = $telegramService;
    }
    public function dailyBackupTaskMessages()
    {
        $this->backupTaskMessages('every_day');
    }

    public function weeklyBackupTaskMessages()
    {
        $this->backupTaskMessages('once_a_week');
    }

    public function monthlyBackupTaskMessages()
    {
        $this->backupTaskMessages('once_a_month');
    }

    /**
     * General method to backup task messages based on frequency.
     *
     * @param string $frequencySlug
     * @return \Illuminate\Database\Eloquent\Collection
     */
    private function backupTaskMessages(string $frequencySlug)
    {
        $tasks = Task::where('status', true)
        ->where('sample', 0)
        ->get();
        $now = Carbon::now();
        $frequency = null;

        switch ($frequencySlug) {
            case 'every_day':
                $frequency = Carbon::yesterday()->setTime($now->hour, $now->minute);
                break;
            case 'once_a_week':
                $frequency = Carbon::now()->subWeek()->setTime($now->hour, $now->minute);
                break;
            case 'once_a_month':
                $frequency = Carbon::now()->subMonth()->setTime($now->hour, $now->minute);
                break;
        }

        foreach ($tasks as $task) {
            if ($task->reportFrequencies()->where('slug', $frequencySlug)->exists()) {

                $data = $task->messages()->whereBetween('created_at', [$frequency, $now])->get();

                if($task->report_date_from && $task->report_date_to)
                {
                    $data = $task->messages()->whereBetween('created_at', [$task->report_date_from, $task->report_date_to])->get();

                }

                $randomFileName = 'exports/data-' . Str::random(10) . '.xlsx';
                $excel = Excel::store(new ReportExport($data), $randomFileName,'public');

               $textMessage = "Резервное копирование: <a href='" . env('APP_URL') . "/storage/$randomFileName'>Скачать резерв</a>";

                if($excel)
                {
                    foreach($task->errorNotificationContacts as $reportContact)
                    {
                        if($reportContact->type->slug == 'email')
                        {
                            $this->sendEmailNotification($reportContact->email,$textMessage);
                        }else if($reportContact->type->slug == 'telegram' && $reportContact->status)
                        {
                            if($telegramUser = TelegramUser::where('token',$reportContact->tg_verification_code)->first())
                            {
                                $this->telegramService->sendMessage($telegramUser->chat_id,$textMessage);
                            }
                        }
                    }
                }
            }
        }

    }

    private function sendEmailNotification($email,$message)
    {
        Notification::route('mail', $email)->notify(new SendEmailNotification($message));

    }
}
