<?php

namespace Database\Seeders;

use App\Models\User;
use Illuminate\Database\Seeder;
use Spatie\Permission\Models\Permission;

class ModelHasPermissionsSeeder extends Seeder
{
    public function run()
    {
        // Trouver l'utilisateur admin (id: 1)
        $adminUser = User::find(1);

        if ($adminUser) {
            // Permission 16 correspond à 'edit articles'
            $permission = Permission::find(16);

            if ($permission) {
                $adminUser->givePermissionTo($permission);
            }
        }
    }
}
