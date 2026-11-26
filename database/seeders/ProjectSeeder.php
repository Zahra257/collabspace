<?php

namespace Database\Seeders;

use App\Models\Project;
use App\Models\Team;
use App\Models\User;
use Illuminate\Database\Seeder;

class ProjectSeeder extends Seeder
{
    public function run(): void
    {
        $backendTeam  = Team::where('name', 'Backend Team')->first();
        $frontendTeam = Team::where('name', 'Frontend Team')->first();
        $designTeam   = Team::where('name', 'Design Team')->first();

        $manager1 = User::where('email', 'manager1@tasksapp.test')->first();
        $manager2 = User::where('email', 'manager2@tasksapp.test')->first();

        // ==========================================
        // PROJECT 1 : E-commerce Platform
        // Teams : Backend + Frontend
        // Manager : manager1
        // ==========================================
        $project1 = Project::create([
            'name'        => 'E-commerce Platform',
            'description' => 'Plateforme e-commerce complète avec paiement en ligne',
            'start_date'  => now()->subDays(30),
            'deadline'    => now()->addDays(60),
            'status'      => 'on_hold',
            'priority'    => 'high',
        ]);

        $project1->teams()->attach([$backendTeam->id, $frontendTeam->id]);
        $project1->users()->attach([$manager1->id]);

        // ==========================================
        // PROJECT 2 : Mobile App
        // Teams : Frontend + Design
        // Manager : manager2
        // ==========================================
        $project2 = Project::create([
            'name'        => 'Mobile App',
            'description' => 'Application mobile iOS et Android',
            'start_date'  => now()->subDays(15),
            'deadline'    => now()->addDays(90),
            'status'      => 'completed',
            'priority'    => 'medium',
        ]);

        $project2->teams()->attach([$frontendTeam->id, $designTeam->id]);
        $project2->users()->attach([$manager2->id]);

        // ==========================================
        // PROJECT 3 : Marketing Website
        // Teams : Design
        // Manager : manager2
        // ==========================================
        $project3 = Project::create([
            'name'        => 'Marketing Website',
            'description' => 'Site vitrine pour la nouvelle campagne marketing',
            'start_date'  => now()->subDays(5),
            'deadline'    => now()->addDays(30),
            'status'      => 'active',
            'priority'    => 'low',
        ]);

        $project3->teams()->attach([$designTeam->id]);
        $project3->users()->attach([$manager2->id]);
    }
}