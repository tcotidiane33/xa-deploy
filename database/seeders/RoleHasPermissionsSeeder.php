<?php

namespace Database\Seeders;

use Illuminate\Database\Seeder;
use Illuminate\Support\Facades\DB;
use Spatie\Permission\Models\Role;
use Spatie\Permission\Models\Permission;

class RoleHasPermissionsSeeder extends Seeder
{
    public function run()
    {
        // Configuration des permissions pour le rôle Admin
        $adminRole = Role::where('name', 'Admin')->first();
        $adminPermissions = [
            'manage forms',
            'manage fields',
            'manage actions',
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
            'create permissions'
        ];

        // Configuration des permissions pour le rôle Responsable
        $responsableRole = Role::where('name', 'Responsable')->first();
        $responsablePermissions = [
            'manage forms',
            'manage fields',
            'view users',
            'edit users'
        ];

        // Configuration des permissions pour le rôle Gestionnaire
        $gestionnaireRole = Role::where('name', 'Gestionnaire')->first();
        $gestionnairePermissions = [
            'manage forms',
            'view users'
        ];

        // Attribution des permissions aux rôles
        foreach ($adminPermissions as $permission) {
            $perm = Permission::where('name', $permission)->first();
            if ($perm) {
                DB::table('role_has_permissions')->insert([
                    'permission_id' => $perm->id,
                    'role_id' => $adminRole->id
                ]);
            }
        }

        foreach ($responsablePermissions as $permission) {
            $perm = Permission::where('name', $permission)->first();
            if ($perm) {
                DB::table('role_has_permissions')->insert([
                    'permission_id' => $perm->id,
                    'role_id' => $responsableRole->id
                ]);
            }
        }

        foreach ($gestionnairePermissions as $permission) {
            $perm = Permission::where('name', $permission)->first();
            if ($perm) {
                DB::table('role_has_permissions')->insert([
                    'permission_id' => $perm->id,
                    'role_id' => $gestionnaireRole->id
                ]);
            }
        }
    }
}
