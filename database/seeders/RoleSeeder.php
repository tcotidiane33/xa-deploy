<?php

namespace Database\Seeders;

use Illuminate\Database\Seeder;
use Spatie\Permission\Models\Role;

class RoleSeeder extends Seeder
{
    public function run()
    {
        // Création des rôles principaux
        $roles = [
            ['name' => 'Admin', 'guard_name' => 'web'],
            ['name' => 'Responsable', 'guard_name' => 'web'],
            ['name' => 'Gestionnaire', 'guard_name' => 'web']
        ];

        foreach ($roles as $role) {
            Role::create($role);
        }
    }
}
