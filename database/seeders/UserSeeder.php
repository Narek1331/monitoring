<?php

namespace Database\Seeders;

use Illuminate\Database\Console\Seeds\WithoutModelEvents;
use Illuminate\Database\Seeder;
use App\Models\User;

class UserSeeder extends Seeder
{
    /**
     * Run the database seeds.
     */
    public function run(): void
    {
        $datas = [
            [
                'name' => 'test1212012@gmail.com',
                'email' => 'test1212012@gmail.com',
                'password' => 'test1212012@gmail.com',
                'role_id' => 1
            ],
            [
                'name' => 'admin@gmail.com',
                'email' => 'admin@gmail.com',
                'password' => 'admin@gmail.com',
                'role_id' => 2
            ]
        ];

        foreach($datas as $data)
        {
            User::create($data);
        }
    }
}
