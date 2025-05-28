<?php

namespace Database\Seeders;

use Illuminate\Database\Console\Seeds\WithoutModelEvents;
use Illuminate\Database\Seeder;
use Spatie\Permission\Models\Role;
use Spatie\Permission\Models\Permission;

class RoleSeeder extends Seeder
{
    /**
     * Run the database seeds.
     */
    public function run(): void
    {
        $admin = Role::create(['name' => 'admin']);
        $staff = Role::create(['name' => 'staff']);

        Permission::create(['name' => 'manage users']);
        Permission::create(['name' => 'export data']);

        $admin->givePermissionTo(['manage users', 'export data']);
        $staff->givePermissionTo(['export data']);
    }
}
