<?php

namespace Database\Seeders;

use App\Models\User;
use Illuminate\Database\Seeder;
use Illuminate\Support\Facades\Hash;

class UserSeeder extends Seeder
{
    public function run(): void
    {
        $users = [
            [
                'name'     => 'Zorvyn Admin',
                'email'    => 'admin@zorvyn.com',
                'password' => Hash::make('password123'),
                'role'     => 'admin',
            ],
            [
                'name'     => 'Zorvyn Accountant',
                'email'    => 'accountant@zorvyn.com',
                'password' => Hash::make('password123'),
                'role'     => 'accountant',
            ],
            [
                'name'     => 'Zorvyn Viewer',
                'email'    => 'viewer@zorvyn.com',
                'password' => Hash::make('password123'),
                'role'     => 'viewer',
            ],
        ];

        foreach ($users as $data) {
            $role = $data['role'];
            unset($data['role']);

            $user = User::updateOrCreate(
                ['email' => $data['email']],
                $data
            );

            $user->assignRole($role);
        }
    }
}
