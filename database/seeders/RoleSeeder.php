<?php

namespace Database\Seeders;

use Illuminate\Database\Seeder;
use App\Models\Role;
use App\Models\Permission;

class RoleSeeder extends Seeder
{
    /**
     * Run the database seeds.
     */
    public function run(): void
    {
        $roles = [
            [
                'name' => 'Пользователь',
                'slug' => 'customer',
                'permissions' => [
                    'view_task', 'create_task', 'edit_task', 'delete_task',
                    'view_contact', 'create_contact', 'edit_contact', 'delete_contact',
                ]
            ],
            [
                'name' => 'Администратор',
                'slug' => 'admin',
                'permissions' => [
                     'view_task', 'create_task', 'edit_task', 'delete_task',
                    'view_contact', 'create_contact', 'edit_contact', 'delete_contact',
                    'view_report', 'create_report', 'edit_report', 'delete_report',
                    'view_user', 'create_user', 'edit_user', 'delete_user'
                ]
            ],
            [
                'name' => 'SEO-Специалист',
                'slug' => 'seo_specialist',
                'permissions' => [
                    'view_task', 'view_contact', 'create_contact', 'edit_contact', 'delete_contact',
                    'view_report', 'create_report', 'edit_report', 'delete_report',
                ]
            ],
            [
                'name' => 'Менеджер проектов',
                'slug' => 'project_manager',
                 'permissions' => [
                    'view_task', 'create_task', 'edit_task', 'delete_task',
                    'view_contact', 'create_contact', 'edit_contact', 'delete_contact',
                ]
            ],
        ];

        foreach ($roles as $roleData) {

            $permissions = $roleData['permissions'] ?? [];
            unset($roleData['permissions']);

            $role = Role::updateOrCreate(
                ['slug' => $roleData['slug']],
                ['name' => $roleData['name']]
            );

            if (!empty($permissions)) {
                $permissionIds = Permission::whereIn('slug', $permissions)->pluck('id');
                $role->permissions()->sync($permissionIds);
            }
        }
    }
}
