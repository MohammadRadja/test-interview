<?php

namespace Database\Seeders;

use App\Models\User;
use Illuminate\Database\Seeder;
use Illuminate\Support\Facades\Hash;
use Spatie\Permission\Models\Role;

class UserSeeder extends Seeder
{
    public function run(): void
    {
        // Daftar user beserta role-nya
        $users = [
            [
                'name' => 'Admin User',
                'email' => 'admin@example.com',
                'password' => 'admin123',
                'role' => 'admin',
            ],
            [
                'name' => 'Staff User',
                'email' => 'staff@example.com',
                'password' => 'staff123',
                'role' => 'staff',
            ],
            [
                'name' => 'Manager User',
                'email' => 'manager@example.com',
                'password' => 'manager123',
                'role' => 'manager',
            ],
            [
                'name' => 'HRD User',
                'email' => 'hrd@example.com',
                'password' => 'hrd123',
                'role' => 'hrd',
            ],
        ];

        foreach ($users as $data) {
            $user = User::firstOrCreate(
                ['email' => $data['email']],
                [
                    'name' => $data['name'],
                    'password' => Hash::make($data['password']),
                ]
            );

            // Assign role
            $user->assignRole($data['role']);
        }
    }
}
