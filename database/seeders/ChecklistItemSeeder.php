<?php

namespace Database\Seeders;

use App\Models\ChecklistItem;
use App\Models\Task;
use Illuminate\Database\Seeder;

class ChecklistItemSeeder extends Seeder
{
    public function run(): void
    {
        $titles = [
            'Écrire les tests',
            'Faire la review',
            'Mettre à jour la doc',
            'Valider avec le client',
            'Déployer en staging',
            'Refactoriser le code',
            'Ajouter les logs',
        ];

        foreach (Task::all() as $task) {
            $nbItems = rand(1, 4);
            for ($i = 0; $i < $nbItems; $i++) {
                ChecklistItem::create([
                    'task_id'     => $task->id,
                    'title'       => $titles[array_rand($titles)],
                    'is_completed'=> (bool) rand(0, 1),
                ]);
            }
        }
    }
}