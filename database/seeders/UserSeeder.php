<?php

namespace Database\Seeders;

use App\Models\User;
use App\Models\Role;
use Illuminate\Database\Seeder;
use Illuminate\Support\Facades\Hash;

class UserSeeder extends Seeder
{
    public function run(): void
    {
        $admin = User::updateOrCreate(
            ['email' => 'admin@gmail.com'],
            [
                'name' => 'Admin',
                'password' => Hash::make('12345678'),
                'status' => 1,
            ]
        );

        $teacher = User::updateOrCreate(
            ['email' => 'teacher@gmail.com'],
            [
                'name' => 'Teacher',
                'password' => Hash::make('12345678'),
                'status' => 1,
            ]
        );

        $student = User::updateOrCreate(
            ['email' => 'student@gmail.com'],
            [
                'name' => 'Student',
                'password' => Hash::make('12345678'),
                'status' => 1,
            ]
        );

        $adminRole = Role::where('name', 'admin')->first();
        $teacherRole = Role::where('name', 'teacher')->first();
        $studentRole = Role::where('name', 'student')->first();

        $admin->roles()->syncWithoutDetaching([$adminRole->id]);
        $teacher->roles()->syncWithoutDetaching([$teacherRole->id]);
        $student->roles()->syncWithoutDetaching([$studentRole->id]);
    }
}