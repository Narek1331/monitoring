<?php

namespace App\Exports;

use Illuminate\Support\Collection;
use Maatwebsite\Excel\Concerns\FromCollection;
use Maatwebsite\Excel\Concerns\WithHeadings;

class TaskMessagesExport implements FromCollection, WithHeadings
{
    protected Collection $messages;

    public function __construct(Collection $messages)
    {
        $this->messages = $messages;
    }

    public function collection(): Collection
    {
        return $this->messages->map(function ($item) {
            return [
                'ID' => $item->id,
                'ID задачи' => $item->task_id,
                'Статус' => $item->status,
                'Текст' => $item->text,
                'Код статуса' => $item->status_code,
                'Отправлено' => $item->sended,
                'Попыток отправки' => $item->sended_count,
                'Дата создания' => optional($item->created_at)->format('Y-m-d H:i:s'),
            ];
        });
    }

    public function headings(): array
    {
        return [
            'ID',
            'ID задачи',
            'Статус',
            'Текст',
            'Код статуса',
            'Отправлено',
            'Попыток отправки',
            'Дата создания',
        ];
    }
}
