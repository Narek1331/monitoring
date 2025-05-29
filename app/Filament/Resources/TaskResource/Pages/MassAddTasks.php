<?php

namespace App\Filament\Resources\TaskResource\Pages;

use App\Filament\Resources\TaskResource;
use Filament\Resources\Pages\Page;
use Filament\Forms;
use App\Models\Task;
use Filament\Notifications\Notification;
class MassAddTasks extends Page implements Forms\Contracts\HasForms{
    protected static string $resource = TaskResource::class;

    protected static string $view = 'filament.resources.task-resource.pages.mass-add-tasks';
    public static string|null $title = 'Массовое добавление новых заданий';

    public $task_id;
    public $urls;

     protected function getFormSchema(): array
    {
        return [
            Forms\Components\Select::make('task_id')
            ->label('Задание, откуда копировать настройки')
            ->options(function(){
                return Task::where('sample',true)->get()->pluck('name','id');
            }),
            // Forms\Components\Repeater::make('tasks')
            //     ->label('URL или IP адреса для новых заданий')
            //     ->schema([
            //         Forms\Components\TextInput::make('url')
            //         ->label('')
            //         ->helperText('Пример https://iqm-tools.ru')
            //     ])
            //     ->minItems(1)
            //     ->columns(1)
            //     ->addActionLabel('Добавить')
            Forms\Components\Textarea::make('urls')
            ->label('URL или IP адреса для новых заданий')
            ->helperText('Обратите внимание: по одному на строчку!')

        ];
    }

     public static function canAccess(array $parameters = []): bool
    {
         return auth()->user()->hasPermission('create_task');
    }

    public function create()
    {
        $taskId = $this->task_id;
        $urls = $this->urls;

        $urlDatas = explode("\n", $urls);


        if($taskId && $urlDatas && count($urlDatas))
        {
            foreach($urlDatas as $data)
            {
                if ($parts = parse_url($data)) {


                    $task = Task::find($taskId);
                    $newTask = $task->replicate();
                    $newTask->protocol = isset($parts['scheme']) ? $parts['scheme'] . '://' : 'https://';
                    $newTask->address_ip = $parts['host'] ?? '';

                    if(isset($parts['path']))
                    {
                        $newTask->address_ip = $parts['path'];
                    }

                    $newTask->name = $newTask->address_ip . ' ' . $task->verificationMethod->short_title;
                    $newTask->sample = false;
                    $newTask->save();

                    foreach($task->reportContacts as $reportContact)
                    {
                        $newTask->reportContacts()->attach($reportContact->id);

                    }

                    foreach($task->errorNotificationContacts as $errorNotificationContact)
                    {
                        $newTask->errorNotificationContacts()->attach($errorNotificationContact->id);

                    }

                    foreach($task->reportFrequencies as $reportFrequency)
                    {
                        $newTask->reportFrequencies()->attach($reportFrequency->id);

                    }

                }
            }


        }

          Notification::make()
            ->title('Успешно создан')
            ->success()
            ->send();

        return redirect('/account/tasks');

    }
}
