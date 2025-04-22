<?php

namespace Database\Seeders;

use Illuminate\Database\Console\Seeds\WithoutModelEvents;
use Illuminate\Database\Seeder;
use App\Models\InspectionCost;

class InspectionCostSeeder extends Seeder
{
    /**
     * Run the database seeds.
     */
    public function run(): void
    {
        $datas = [
            [
                'title' => 'Проверки дешевле, но платные SMS и звонки',
                'description' => 'Стоимость одной проверки 0.0100 руб (для проверки на вирусы 1.6000 руб), и при этом все SMS платные.'
            ],
            [
                'title' => 'Проверки дороже, но бесплатные SMS и звонки',
                'description' => 'Стоимость одной проверки 0.0150 руб (для проверки на вирусы 1.8000 руб), и при этом все SMS бесплатные.'
            ]
        ];

        foreach ($datas as $data) {
            InspectionCost::create($data);
        }
    }
}
