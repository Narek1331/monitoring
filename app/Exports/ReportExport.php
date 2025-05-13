<?php

namespace App\Exports;

use Maatwebsite\Excel\Concerns\FromCollection;
use Maatwebsite\Excel\Concerns\WithHeadings;

class ReportExport implements FromCollection, WithHeadings
{
     protected $messages;

    public function __construct($messages)
    {
        $this->messages = $messages;
    }

    public function collection()
    {
        return $this->messages->map(function ($msg) {
            return [
                'text' => $msg->text,
                'created_at' => $msg->created_at->format('Y-m-d H:i:s'),
            ];
        });
    }

    public function headings(): array
    {
        return [
            'Сообщение',
            'Дата',
        ];
    }
}
