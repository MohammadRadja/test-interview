<?php

namespace Database\Seeders;

use Illuminate\Database\Seeder;
use Spatie\Permission\Models\Role;
use Spatie\Permission\Models\Permission;

class RoleSeeder extends Seeder
{
    public function run(): void
    {
        // Daftar role
        $roles = ['admin', 'staff', 'manager', 'hrd'];

        // Daftar semua permission yang mungkin
        $permissions = [
            'manage users',
            'export data',
            'manage roles',
            'assign permissions',
        ];

        // Buat permission jika belum ada
        foreach ($permissions as $permissionName) {
            Permission::firstOrCreate(['name' => $permissionName]);
        }

        // Buat role jika belum ada (tanpa assign permission)
        foreach ($roles as $roleName) {
            $role = Role::firstOrCreate(['name' => $roleName]);

            // Jika role adalah admin, beri semua permission
            if ($roleName === 'admin') {
                $role->syncPermissions(Permission::all());
            }
        }
    }
}
