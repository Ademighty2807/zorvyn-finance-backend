<?php

namespace Database\Seeders;

use Illuminate\Database\Console\Seeds\WithoutModelEvents;
use Illuminate\Database\Seeder;
use Spatie\Permission\Models\Permission;
use Spatie\Permission\Models\Role;

class RolePermissionSeeder extends Seeder
{
    /**
     * Run the database seeds.
     */
    public function run(): void
    {
        $roles = ['admin', 'accountant', 'viewer'];

        $permissions = [
            'view_records',
            'create_records',
            'edit_records',
            'delete_records',
            'view_analytics',
        ];

        foreach ($permissions as $permission) {
            Permission::create(['name' => $permission]);
        }

        foreach ($roles as $role) {
            Role::create(['name' => $role]);
        }

        // Assign permissions per role
        Role::findByName('admin')->givePermissionTo(Permission::all());
        Role::findByName('accountant')->givePermissionTo(['view_records', 'create_records', 'edit_records', 'view_analytics']);
        Role::findByName('viewer')->givePermissionTo(['view_records', 'view_analytics']);
    }
}
