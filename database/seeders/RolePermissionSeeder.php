<?php

namespace Database\Seeders;

use Illuminate\Database\Seeder;
use Spatie\Permission\Models\Role;
use Spatie\Permission\Models\Permission;

class RolePermissionSeeder extends Seeder
{
    public function run()
    {
        // Attribuer toutes les permissions au super-admin
        $superAdmin = Role::findByName('Admin');
        $superAdmin->givePermissionTo(Permission::all());

        // Attribuer des permissions spécifiques à l'admin
        $admin = Role::findByName('Responsable');
        $admin->givePermissionTo([
            'manage forms',
            'manage fields',
            'manage actions',
            'view users',
            'edit users'
        ]);

        // Attribuer des permissions limitées au gestionnaire
        $gestionnaire = Role::findByName('Gestionnaire');
        $gestionnaire->givePermissionTo([
            'view users',
            'manage forms'
        ]);
    }
}
