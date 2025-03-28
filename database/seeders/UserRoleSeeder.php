<?php

namespace Database\Seeders;

use App\Models\User;
use Illuminate\Database\Seeder;
use Spatie\Permission\Models\Role;

class UserRoleSeeder extends Seeder
{
    public function run()
    {
        // Assigner le rôle super-admin
        $superAdmin = User::where('email', 'admin@mail.fr')->first();
        if ($superAdmin) {
            $superAdmin->assignRole('Admin');
        }

        // Assigner le rôle admin
        $admin = User::where('email', 'admin@xal.com')->first();
        if ($admin) {
            $admin->assignRole('Responsable');
        }

        // Assigner le rôle responsable
        $responsable = User::where('email', 'responsable@xal.com')->first();
        if ($responsable) {
            $responsable->assignRole('Gestionnaire');
        }

        // Assigner le rôle gestionnaire
        $gestionnaire = User::where('email', 'gestionnaire@xal.com')->first();
        if ($gestionnaire) {
            $gestionnaire->assignRole('Gestionnaire');
        }
    }
}
