<?php

namespace Database\Seeders;

use Illuminate\Database\Seeder;
use Spatie\Permission\Models\Role;
use Spatie\Permission\Models\Permission;

class RolePermissionSeeder extends Seeder
{
    public function run(): void
    {
        // Clear cached roles and permissions
        app()[\Spatie\Permission\PermissionRegistrar::class]->forgetCachedPermissions();

        $permissions = [
            'view_records',
            'create_records',
            'edit_records',
            'delete_records',
            'view_analytics',
        ];

        // ✅ firstOrCreate instead of create
        foreach ($permissions as $permission) {
            Permission::firstOrCreate(['name' => $permission]);
        }

        $roles = [
            'admin',
            'accountant',
            'viewer',
        ];

        foreach ($roles as $role) {
            Role::firstOrCreate(['name' => $role]);
        }

        // Assign permissions per role
        Role::findByName('admin')->syncPermissions(Permission::all());

        Role::findByName('accountant')->syncPermissions([
            'view_records',
            'create_records',
            'edit_records',
            'view_analytics',
        ]);

        Role::findByName('viewer')->syncPermissions([
            'view_records',
            'view_analytics',
        ]);
    }
}
