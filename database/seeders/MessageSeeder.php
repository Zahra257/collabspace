<?php

namespace Database\Seeders;

use App\Models\Message;
use App\Models\Project;
use App\Models\User;
use Illuminate\Database\Seeder;

class MessageSeeder extends Seeder
{
    public function run(): void
    {
        $projects = Project::all();
        $users    = User::all();

        $messages = [
            'Salut, tu as un moment pour discuter du projet ?',
            'Ok je regarde ça cet après-midi.',
            'Tu peux me partager le dernier mockup ?',
            'Bien reçu 👍',
            'On se sync demain matin ?',
            'Le déploiement est passé en prod ✅',
            'Il y a un bug sur la page checkout, je regarde.',
        ];

        foreach ($projects as $project) {
            $projectMembers = $project->users;

            if ($projectMembers->count() < 2) {
                continue;
            }

            for ($i = 0; $i < 3; $i++) {
                $sender   = $projectMembers->random();
                $receiver = $projectMembers->where('id', '!=', $sender->id)->random();

                Message::create([
                    'sender_id'   => $sender->id,
                    'receiver_id' => $receiver->id,
                    'project_id'  => $project->id,
                    'message'     => $messages[array_rand($messages)],
                ]);
            }
        }
    }
}