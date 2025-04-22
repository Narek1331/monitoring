<?php

namespace Database\Seeders;

use Illuminate\Database\Console\Seeds\WithoutModelEvents;
use Illuminate\Database\Seeder;
use App\Models\ReportFrequency;

class ReportFrequencySeeder extends Seeder
{
    /**
     * Run the database seeds.
     */
    public function run(): void
    {
        $datas = [
            [
                'slug' => 'every_day',
                'name' => 'Каждый день'
            ],
            [
                'slug' => 'once_a_week',
                'name' => 'Раз в неделю'
            ],
            [
                'slug' => 'once_a_month',
                'name' => 'Раз в месяц'
            ]
        ];

        foreach($datas as $data)
        {
            ReportFrequency::create($data);
        }

    }
}
