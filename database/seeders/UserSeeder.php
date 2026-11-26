<?php

namespace Database\Seeders;

use App\Models\User;
use Illuminate\Database\Seeder;
use Illuminate\Support\Facades\Hash;

class UserSeeder extends Seeder
{
    public function run(): void
    {
        // ==========================================
        // 1. ADMIN
        // ==========================================
        User::create([
            'name'              => 'Admin Super',
            'email'             => 'admin@tasksapp.test',
            'password'          => Hash::make('password'),
            'role'              => 'admin',
            'job_title'         => 'CTO',
            'phone'             => '+212600000001',
            'experience_years'  => 10,
            'availability_status' => 'available',
            'email_verified_at' => now(),
        ]);

        // ==========================================
        // 2. MANAGERS (Project Managers)
        // ==========================================
        $managers = [
            [
                'name'         => 'Youssef Manager',
                'email'        => 'manager1@tasksapp.test',
                'job_title'    => 'Project Manager',
                'experience_years' => 7,
            ],
            [
                'name'         => 'Sara Manager',
                'email'        => 'manager2@tasksapp.test',
                'job_title'    => 'Product Manager',
                'experience_years' => 5,
            ],
        ];

        foreach ($managers as $manager) {
            User::create([
                ...$manager,
                'password'          => Hash::make('password'),
                'role'              => 'manager',
                'phone'             => '+2126000000' . rand(10, 99),
                'availability_status' => 'available',
                'email_verified_at' => now(),
            ]);
        }

        // ==========================================
        // 3. MEMBERS (Team Members)
        // ==========================================
        $members = [
            ['name' => 'Karim Backend',    'job_title' => 'Backend Developer',  'experience_years' => 4],
            ['name' => 'Nadia Backend',    'job_title' => 'Backend Developer',  'experience_years' => 3],
            ['name' => 'Omar Frontend',    'job_title' => 'Frontend Developer', 'experience_years' => 5],
            ['name' => 'Imane Frontend',   'job_title' => 'Frontend Developer', 'experience_years' => 2],
            ['name' => 'Hassan Designer',  'job_title' => 'UI/UX Designer',     'experience_years' => 6],
            ['name' => 'Aya Designer',     'job_title' => 'Graphic Designer',   'experience_years' => 3],
            ['name' => 'Mehdi Fullstack',  'job_title' => 'Fullstack Developer','experience_years' => 4],
        ];

        foreach ($members as $i => $member) {
            User::create([
                ...$member,
                'email'             => 'member' . ($i + 1) . '@tasksapp.test',
                'password'          => Hash::make('password'),
                'role'              => 'member',
                'phone'             => '+2126000000' . rand(100, 999),
                'availability_status' => ['available', 'busy', 'offline'][rand(0, 2)],
                'email_verified_at' => now(),
            ]);
        }
    }
}