<?php

namespace Database\Seeders;

use Illuminate\Database\Seeder;
use Illuminate\Support\Facades\DB;

class ModelHasRolesSeeder extends Seeder
{
    public function run()
    {
        $modelHasRoles = [
            [
                'role_id' => 1,      // Admin
                'model_type' => 'App\\Models\\User',
                'model_id' => 1
            ],
            [
                'role_id' => 1,      // Admin
                'model_type' => 'App\\Models\\User',
                'model_id' => 2
            ],
            [
                'role_id' => 1,      // Admin
                'model_type' => 'App\\Models\\User',
                'model_id' => 18
            ],
            [
                'role_id' => 2,      // Responsable
                'model_type' => 'App\\Models\\User',
                'model_id' => 1
            ],
            [
                'role_id' => 3,      // Gestionnaire
                'model_type' => 'App\\Models\\User',
                'model_id' => 3
            ],
            [
                'role_id' => 3,      // Gestionnaire
                'model_type' => 'App\\Models\\User',
                'model_id' => 4
            ]
        ];

        foreach ($modelHasRoles as $roleAssignment) {
            DB::table('model_has_roles')->insert($roleAssignment);
        }
    }
}
