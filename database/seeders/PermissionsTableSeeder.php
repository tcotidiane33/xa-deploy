<?php

namespace Database\Seeders;

use Illuminate\Database\Console\Seeds\WithoutModelEvents;
use Illuminate\Database\Seeder;
use Spatie\Permission\Models\Permission;
use Spatie\Permission\Models\Role;

class PermissionsTableSeeder extends Seeder
{
    public function run()
    {
        // Liste des permissions
        $permissions = [
            'view users',
            'edit users',
            'delete users',
            'create users',
            'view roles',
            'edit roles',
            'delete roles',
            'create roles',
            'view permissions',
            'edit permissions',
            'delete permissions',
            'create permissions',
            // Ajoutez toutes les autres permissions nécessaires ici
        ];

        // Créer les permissions
        foreach ($permissions as $permission) {
            Permission::firstOrCreate(['name' => $permission]);
        }

        // Récupérer le rôle Admin
        $adminRole = Role::firstOrCreate(['name' => 'Admin']);

        // Attribuer toutes les permissions au rôle Admin
        $adminRole->syncPermissions(Permission::all());
    }
}