<?php

namespace Database\Seeders;

use App\Models\Project;
use App\Models\Task;
use App\Models\Team;
use App\Models\User;
use Illuminate\Database\Seeder;

class TaskSeeder extends Seeder
{
    public function run(): void
    {
        $project1 = Project::where('name', 'E-commerce Platform')->first();
        $project2 = Project::where('name', 'Mobile App')->first();
        $project3 = Project::where('name', 'Marketing Website')->first();

        $backendTeam  = Team::where('name', 'Backend Team')->first();
        $frontendTeam = Team::where('name', 'Frontend Team')->first();
        $designTeam   = Team::where('name', 'Design Team')->first();

        $admin    = User::where('email', 'admin@tasksapp.test')->first();
        $manager1 = User::where('email', 'manager1@tasksapp.test')->first();
        $manager2 = User::where('email', 'manager2@tasksapp.test')->first();
        $m1 = User::where('email', 'member1@tasksapp.test')->first();
        $m2 = User::where('email', 'member2@tasksapp.test')->first();
        $m3 = User::where('email', 'member3@tasksapp.test')->first();
        $m4 = User::where('email', 'member4@tasksapp.test')->first();
        $m5 = User::where('email', 'member5@tasksapp.test')->first();
        $m6 = User::where('email', 'member6@tasksapp.test')->first();
        $m7 = User::where('email', 'member7@tasksapp.test')->first();

        // ==========================================
        // PROJECT 1 : E-commerce Platform (5 tâches)
        // ==========================================
        $tasks1 = [
            [
                'name'    => 'Design database schema',
                'description' => 'Concevoir le schéma de la base de données pour le e-commerce',
                'status'   => 'in_progress',
                'priority' => 'high',
                'user_id'  => $m1->id,
                'project_id' => $project1->id,
                'guests'   => [$m2->id, $m7->id],
            ],
            [
                'name'    => 'Implement JWT authentication',
                'description' => 'Mettre en place l\'authentification JWT',
                'status'   => 'in_progress',
                'priority' => 'high',
                'user_id'  => $m2->id,
                'project_id' => $project1->id,
                'guests'   => [$m1->id],
            ],
            [
                'name'    => 'Create product listing page',
                'description' => 'Créer la page de listing des produits',
                'status'   => 'in_progress',
                'priority' => 'medium',
                'user_id'  => $m3->id,
                'project_id' => $project1->id,
                'guests'   => [$m4->id],
            ],
            [
                'name'    => 'Shopping cart component',
                'description' => 'Composant panier avec state management',
                'status'   => 'in_progress',
                'priority' => 'high',
                'user_id'  => $m4->id,
                'project_id' => $project1->id,
                'guests'   => [],
            ],
            [
                'name'    => 'Checkout integration',
                'description' => 'Intégration du paiement Stripe',
                'status'   => 'in_progress',
                'priority' => 'high',
                'user_id'  => $m7->id,
                'project_id' => $project2->id,
                'guests'   => [$m1->id, $m2->id],
            ],
        ];

        foreach ($tasks1 as $data) {
            $guests = $data['guests'];
            unset($data['guests']);

            $task = Task::create([
                ...$data,
                'project_id' => $project1->id,
                'start_date' => now()->subDays(rand(5, 25)),
                'due_date'   => now()->addDays(rand(5, 40)),
            ]);

            if (! empty($guests)) {
                $task->guests()->attach($guests);
            }
        }

        // ==========================================
        // PROJECT 2 : Mobile App (5 tâches)
        // ==========================================
        $tasks2 = [
            [
                'name'    => 'Setup React Native project',
                'description' => 'Initialisation du projet React Native',
                'status'   => 'in_progress',
                'priority' => 'high',
                'user_id'  => $m3->id,
                'project_id' => $project2->id,
                'guests'   => [],
            ],
            [
                'name'    => 'Design mobile mockups',
                'description' => 'Maquettes Figma pour iOS et Android',
                'status'   => 'in_progress',
                'priority' => 'high',
                'user_id'  => $m5->id,
                'project_id' => $project2->id,
                'guests'   => [$m6->id],
            ],
            [
                'name'    => 'User authentication screens',
                'description' => 'Écrans de login et register',
                'status'   => 'in_progress',
                'priority' => 'high',
                'user_id'  => $m4->id,
                'project_id' => $project2->id,
                'guests'   => [],
            ],
            [
                'name'    => 'Push notifications setup',
                'description' => 'Configuration Firebase Cloud Messaging',
                'status'   => 'in_progress',
                'priority' => 'medium',
                'user_id'  => $m7->id,
                'project_id' => $project2->id,
                'guests'   => [],
            ],
            [
                'name'    => 'App icon and splash screen',
                'description' => 'Icône de l\'app et splash screen',
                'status'   => 'todo',
                'priority' => 'low',
                'user_id'  => $m6->id,
                'project_id' => $project2->id,
                'guests'   => [],
            ],
        ];

        foreach ($tasks2 as $data) {
            $guests = $data['guests'];
            unset($data['guests']);

            $task = Task::create([
                ...$data,
                'project_id' => $project2->id,
                'start_date' => now()->subDays(rand(5, 15)),
                'due_date'   => now()->addDays(rand(10, 60)),
            ]);

            if (! empty($guests)) {
                $task->guests()->attach($guests);
            }
        }

        // ==========================================
        // PROJECT 3 : Marketing Website (5 tâches)
        // ==========================================
        $tasks3 = [
            [
                'name'    => 'Define brand guidelines',
                'description' => 'Charte graphique et guidelines',
                'status'   => 'in_progress',
                'priority' => 'high',
                'user_id'  => $m5->id,
                'project_id' => $project3->id,
                'guests'   => [],
            ],
            [
                'name'    => 'Landing page design',
                'description' => 'Design de la landing page',
                'status'   => 'in_progress',
                'priority' => 'high',
                'user_id'  => $m6->id,
                'project_id' => $project3->id,
                'guests'   => [$m5->id],
            ],
            [
                'name'    => 'Hero section animation',
                'description' => 'Animation CSS/Lottie pour le hero',
                'status'   => 'todo',
                'priority' => 'medium',
                'user_id'  => $m5->id,
                'project_id' => $project3->id,
                'guests'   => [],
            ],
            [
                'name'    => 'Write copy for about page',
                'description' => 'Rédaction du contenu pour la page About',
                'status'   => 'todo',
                'priority' => 'low',
                'user_id'  => $m6->id,
                'project_id' => $project3->id,
                'guests'   => [],
            ],
            [
                'name'    => 'SEO optimization',
                'description' => 'Optimisation SEO de toutes les pages',
                'status'   => 'todo',
                'priority' => 'medium',
                'user_id'  => $m7->id,
                'project_id' => $project3->id,
                'guests'   => [],
            ],
        ];

        foreach ($tasks3 as $data) {
            $guests = $data['guests'];
            unset($data['guests']);

            $task = Task::create([
                ...$data,
                'project_id' => $project3->id,
                'start_date' => now()->subDays(rand(2, 5)),
                'due_date'   => now()->addDays(rand(5, 30)),
            ]);

            if (! empty($guests)) {
                $task->guests()->attach($guests);
            }
        }
    }
}