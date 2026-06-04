<?php

namespace Database\Seeders;

use App\Models\User;
use App\Models\Department;
use Illuminate\Database\Seeder;
use Illuminate\Support\Facades\Hash;

class UserSeeder extends Seeder
{
    public function run()
    {
        // Admin
        User::create([
            'name' => 'Administrator',
            'email' => 'admin@example.com',
            'password' => Hash::make('password'),
            'role' => 'admin',
            'department_id' => null,
            'is_active' => true,
        ]);

        // Manager
        User::create([
            'name' => 'Manager',
            'email' => 'manager@example.com',
            'password' => Hash::make('password'),
            'role' => 'manager',
            'department_id' => null,
            'is_active' => true,
        ]);

        // Department Users
        // $departments = Department::all();

        // foreach ($departments as $dept) {
        //     User::create([
        //         'name' => $dept->name . ' Supervisor',
        //         'email' => strtolower($dept->code) . '@example.com',
        //         'password' => Hash::make('password'),
        //         'role' => 'user',
        //         'department_id' => $dept->id,
        //         'is_active' => true,
        //     ]);
        // }
    }
}
