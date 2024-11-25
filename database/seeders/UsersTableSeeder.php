<?php

namespace Database\Seeders;

use Illuminate\Database\Seeder;
use App\Models\User;
use Illuminate\Support\Facades\Hash;

class UsersTableSeeder extends Seeder
{
    /**
     * Run the database seeds.
     */
    public function run(): void
    {
        // Créer des utilisateurs et leur attribuer des rôles
        $users = [
            [
                'name' => 'Admin User',
                'email' => 'admin@xal.com',
                'password' => Hash::make('password'),
                'is_active' => true,
                // 'role' => 'Admin',
            ],
            [
                'name' => 'Responsable User',
                'email' => 'responsable@xal.com',
                'password' => Hash::make('password'),
                'is_active' => true,
                // 'role' => 'Responsable',
            ],
            [
                'name' => 'Gestionnaire User',
                'email' => 'gestionnaire@xal.com',
                'password' => Hash::make('password'),
                'is_active' => true,
                // 'role' => 'Gestionnaire',
            ],
        ];

        foreach ($users as $userData) {
            $user = User::firstOrCreate(
                ['email' => $userData['email']],
                [
                    'name' => $userData['name'],
                    'password' => $userData['password'],
                    'is_active' => $userData['is_active'],
                ]
            );

            // Utiliser la table pivot model_has_roles
            $user->assignRole($userData['role']);
        }
    }
}