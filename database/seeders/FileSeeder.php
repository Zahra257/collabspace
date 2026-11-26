<?php

namespace Database\Seeders;

use App\Models\File;
use App\Models\Project;
use App\Models\Task;
use App\Models\User;
use Illuminate\Database\Seeder;

class FileSeeder extends Seeder
{
    public function run(): void
    {
        $users = User::whereIn('role', ['manager', 'member'])->get();

        $fileNames = [
            'requirements.pdf',
            'design-mockup.figma',
            'database-schema.sql',
            'api-documentation.md',
            'meeting-notes.txt',
            'logo.png',
        ];

        // Fichiers liés aux projets
        foreach (Project::all() as $project) {
            for ($i = 0; $i < rand(1, 3); $i++) {
                File::create([
                    'file_path'     => 'uploads/' . uniqid() . '.pdf',
                    'file_name'     => $fileNames[array_rand($fileNames)],
                    'user_id'       => $users->random()->id,
                    'fileable_id'   => $project->id,
                    'fileable_type' => Project::class,
                ]);
            }
        }

        // Fichiers liés aux tâches
        foreach (Task::all() as $task) {
            if (rand(0, 1)) {
                File::create([
                    'file_path'     => 'uploads/' . uniqid() . '.png',
                    'file_name'     => $fileNames[array_rand($fileNames)],
                    'user_id'       => $users->random()->id,
                    'fileable_id'   => $task->id,
                    'fileable_type' => Task::class,
                ]);
            }
        }
    }
}