<?php

namespace Database\Seeders;

use App\Models\User;
use Illuminate\Database\Seeder;
use Illuminate\Support\Facades\Hash;

class UserSeeder extends Seeder
{
    public function run()
    {
        // Création de l'admin principal
        $admin = User::create([
            'name' => 'Admin',
            'email' => 'admin@mail.fr',
            'password' => Hash::make('password'),
            'is_active' => true
        ]);

        // Création des autres utilisateurs principaux
        $users = [
            [
                'name' => 'Admin 2',
                'email' => 'admin@xal.com',
                'password' => Hash::make('password'),
                'is_active' => true
            ],
            [
                'name' => 'Responsable User',
                'email' => 'responsable@xal.com',
                'password' => Hash::make('password'),
                'is_active' => true
            ],
            [
                'name' => 'Gestionnaire User',
                'email' => 'gestionnaire@xal.com',
                'password' => Hash::make('password'),
                'is_active' => true
            ]
        ];

        foreach ($users as $userData) {
            User::create($userData);
        }
    }
}
