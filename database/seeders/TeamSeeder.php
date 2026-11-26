<?php

namespace Database\Seeders;

use App\Models\Team;
use App\Models\User;
use Illuminate\Database\Seeder;

class TeamSeeder extends Seeder
{
    public function run(): void
    {
        $admin    = User::where('email', 'admin@tasksapp.test')->first();
        $manager1 = User::where('email', 'manager1@tasksapp.test')->first();
        $manager2 = User::where('email', 'manager2@tasksapp.test')->first();

        $backendMembers  = User::whereIn('email', [
            'member1@tasksapp.test',
            'member2@tasksapp.test',
        ])->get();

        $frontendMembers = User::whereIn('email', [
            'member3@tasksapp.test',
            'member4@tasksapp.test',
        ])->get();

        $designMembers = User::whereIn('email', [
            'member5@tasksapp.test',
            'member6@tasksapp.test',
            'member7@tasksapp.test',
        ])->get();

        // TEAM 1 : Backend Team
        $backendTeam = Team::create([
            'name'        => 'Backend Team',
            'description' => 'Équipe spécialisée dans les APIs et la logique métier',
        ]);

        // TEAM 2 : Frontend Team
        $frontendTeam = Team::create([
            'name'        => 'Frontend Team',
            'description' => 'Équipe spécialisée dans les interfaces utilisateurs',
        ]);

     

        // TEAM 3 : Design Team
        $designTeam = Team::create([
            'name'        => 'Design Team',
            'description' => 'Équipe spécialisée dans le design UI/UX',
        ]);

    }
}