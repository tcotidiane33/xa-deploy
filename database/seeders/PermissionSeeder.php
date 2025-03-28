<?php

namespace Database\Seeders;

use Illuminate\Database\Seeder;
use Spatie\Permission\Models\Permission;

class PermissionSeeder extends Seeder
{
    public function run()
    {
        $permissions = [
            ['name' => 'manage forms', 'guard_name' => 'web'],
            ['name' => 'manage fields', 'guard_name' => 'web'],
            ['name' => 'manage actions', 'guard_name' => 'web'],
            ['name' => 'view users', 'guard_name' => 'web'],
            ['name' => 'edit users', 'guard_name' => 'web'],
            ['name' => 'delete users', 'guard_name' => 'web'],
            ['name' => 'create users', 'guard_name' => 'web'],
            ['name' => 'view roles', 'guard_name' => 'web'],
            ['name' => 'edit roles', 'guard_name' => 'web'],
            ['name' => 'delete roles', 'guard_name' => 'web'],
            ['name' => 'create roles', 'guard_name' => 'web'],
            ['name' => 'view permissions', 'guard_name' => 'web'],
            ['name' => 'edit permissions', 'guard_name' => 'web'],
            ['name' => 'delete permissions', 'guard_name' => 'web'],
            ['name' => 'create permissions', 'guard_name' => 'web'],
            ['name' => 'edit articles', 'guard_name' => 'web']
        ];

        foreach ($permissions as $permission) {
            Permission::create($permission);
        }
    }
}
