<?php

namespace Database\Seeders;

use App\Models\ActivityLog;
use App\Models\Project;
use App\Models\Task;
use App\Models\User;
use Illuminate\Database\Seeder;

class ActivityLogSeeder extends Seeder
{
    public function run(): void
    {
        $users    = User::all();
        $tasks    = Task::all();
        $projects = Project::all();

        $actions = [
            'created',
            'updated',
            'deleted',
            'commented',
            'assigned',
        ];

        foreach ($tasks->take(10) as $task) {
            ActivityLog::create([
                'user_id'      => $users->random()->id,
                'action'       => $actions[array_rand($actions)],
                'subject_type' => Task::class,
                'subject_id'   => $task->id,
            ]);
        }

        foreach ($projects as $project) {
            ActivityLog::create([
                'user_id'      => $users->random()->id,
                'action'       => 'updated',
                'subject_type' => Project::class,
                'subject_id'   => $project->id,
            ]);
        }
    }
}