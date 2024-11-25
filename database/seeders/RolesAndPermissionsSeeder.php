<?php

namespace Database\Seeders;

use Illuminate\Database\Seeder;
use Spatie\Permission\Models\Role;
use Spatie\Permission\Models\Permission;

class RolesAndPermissionsSeeder extends Seeder
{
    /**
     * Run the database seeds.
     */
    public function run(): void
    {
        // Créer les permissions
        $permissions = [
            'manage forms',
            'manage fields',
            'manage actions',
        ];

        foreach ($permissions as $permission) {
            if (!Permission::where('name', $permission)->where('guard_name', 'web')->exists()) {
                Permission::create(['name' => $permission, 'guard_name' => 'web']);
            }
        }

        // Créer les rôles et leur attribuer des permissions
        $roles = [
            'Admin' => ['manage forms', 'manage fields', 'manage actions'],
            'Responsable' => ['manage forms', 'manage fields'],
            'Gestionnaire' => ['manage forms'],
        ];

        foreach ($roles as $roleName => $rolePermissions) {
            $role = Role::firstOrCreate(['name' => $roleName, 'guard_name' => 'web']);
            $role->syncPermissions($rolePermissions);
        }
    }
}