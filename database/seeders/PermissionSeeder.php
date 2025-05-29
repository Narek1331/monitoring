<?php

namespace Database\Seeders;

use Illuminate\Database\Seeder;
use App\Models\Permission;

class PermissionSeeder extends Seeder
{
    /**
     * Run the database seeds.
     */
    public function run(): void
    {
        $permissions = [
            // Tasks
            ['name' => 'Просмотреть задачу', 'slug' => 'view_task'],
            ['name' => 'Создать задачу',      'slug' => 'create_task'],
            ['name' => 'Редактировать задачу','slug' => 'edit_task'],
            ['name' => 'Удалить задачу',      'slug' => 'delete_task'],

            // Contacts
            ['name' => 'Просмотреть контакт', 'slug' => 'view_contact'],
            ['name' => 'Создать контакт',     'slug' => 'create_contact'],
            ['name' => 'Редактировать контакт','slug' => 'edit_contact'],
            ['name' => 'Удалить контакт',     'slug' => 'delete_contact'],

            // Reports
            ['name' => 'Просмотреть отчет',   'slug' => 'view_report'],
            ['name' => 'Создать отчет',       'slug' => 'create_report'],
            ['name' => 'Редактировать отчет', 'slug' => 'edit_report'],
            ['name' => 'Удалить отчет',       'slug' => 'delete_report'],

            // Users
            [
                'name' => 'Просмотреть пользователя',
                'slug' => 'view_user',
            ],
            [
                'name' => 'Создать пользователя',
                'slug' => 'create_user',
            ],
            [
                'name' => 'Редактировать пользователя',
                'slug' => 'edit_user',
            ],
            [
                'name' => 'Удалить пользователя',
                'slug' => 'delete_user',
            ],
        ];

        foreach ($permissions as $permission) {
            Permission::updateOrCreate(
                ['slug' => $permission['slug']],
                ['name' => $permission['name']]
            );
        }
    }
}
