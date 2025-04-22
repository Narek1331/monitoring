<?php

namespace Database\Seeders;

use Illuminate\Database\Console\Seeds\WithoutModelEvents;
use Illuminate\Database\Seeder;
use App\Models\ContactType;

class ContactTypeSeeder extends Seeder
{
    /**
     * Run the database seeds.
     */
    public function run(): void
    {
        $datas = [
            [
                'name' => 'E-mail адреса',
                'slug' => 'email',
            ],
            [
                'name' => ' Telegram аккаунты',
                'slug' => 'telegram',
            ],
            [
                'name' => 'Телефоны для СМС',
                'slug' => 'phone_sms',
            ],
            [
                'name' => 'Телефоны для звонков',
                'slug' => 'phone_call',
            ],
            [
                'name' => 'HTTP скрипты',
                'slug' => 'http_script',
            ],
        ];

        foreach($datas as $data)
        {
            ContactType::create($data);
        }
    }
}
