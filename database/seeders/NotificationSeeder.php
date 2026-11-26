<?php

namespace Database\Seeders;

use App\Models\Notification;
use App\Models\User;
use Illuminate\Database\Seeder;

class NotificationSeeder extends Seeder
{
    public function run(): void
    {
        $users = User::where('role', 'member')->get();

        $templates = [
            ['title' => 'Nouvelle tâche assignée', 'message' => 'Une nouvelle tâche vous a été assignée.', 'type' => 'task'],
            ['title' => 'Projet mis à jour',       'message' => 'Le projet a été modifié.',          'type' => 'project'],
            ['title' => 'Nouvelle réunion',        'message' => 'Vous êtes invité à une réunion.',   'type' => 'meeting'],
            ['title' => 'Nouveau commentaire',     'message' => 'Quelqu\'un a commenté votre tâche.', 'type' => 'info'],
        ];

        foreach ($users as $user) {
            foreach ($templates as $template) {
                Notification::create([
                    'user_id'  => $user->id,
                    'title'    => $template['title'],
                    'message'  => $template['message'],
                    'type'     => $template['type'],
                    'is_read'  => (bool) rand(0, 1),
                ]);
            }
        }
    }
}