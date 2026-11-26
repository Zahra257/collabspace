<?php

namespace Database\Seeders;

use App\Models\Meeting;
use App\Models\Project;
use App\Models\User;
use Illuminate\Database\Seeder;

class MeetingSeeder extends Seeder
{
    public function run(): void
    {
        $project1 = Project::where('name', 'E-commerce Platform')->first();
        $project2 = Project::where('name', 'Mobile App')->first();
        $project3 = Project::where('name', 'Marketing Website')->first();

        $manager1 = User::where('email', 'manager1@tasksapp.test')->first();
        $manager2 = User::where('email', 'manager2@tasksapp.test')->first();
        $members  = User::where('role', 'member')->get();

        // Meeting 1 — E-commerce kickoff
        $meeting1 = Meeting::create([
            'subject'          => 'E-commerce Kickoff',
            'date'             => now()->addDays(1)->toDateString(),
            'time'             => '10:00:00',
            'duration_minutes' => 60,
            'note'             => 'Réunion de lancement du projet e-commerce',
            'project_id'       => $project1->id,
        ]);
        $meeting1->users()->attach([
            $manager1->id,
            ...$members->whereIn('email', [
                'member1@tasksapp.test',
                'member2@tasksapp.test',
                'member3@tasksapp.test',
            ])->pluck('id'),
        ]);

        // Meeting 2 — Mobile app design review
        $meeting2 = Meeting::create([
            'subject'          => 'Mobile App Design Review',
            'date'             => now()->addDays(3)->toDateString(),
            'time'             => '14:30:00',
            'duration_minutes' => 90,
            'note'             => 'Revue des maquettes mobile',
            'project_id'       => $project2->id,
        ]);
        $meeting2->users()->attach([
            $manager2->id,
            ...$members->whereIn('email', [
                'member5@tasksapp.test',
                'member6@tasksapp.test',
                'member3@tasksapp.test',
            ])->pluck('id'),
        ]);

        // Meeting 3 — Marketing weekly
        $meeting3 = Meeting::create([
            'subject'          => 'Marketing Weekly Sync',
            'date'             => now()->addDays(7)->toDateString(),
            'time'             => '09:00:00',
            'duration_minutes' => 30,
            'note'             => 'Point hebdomadaire marketing',
            'project_id'       => $project3->id,
        ]);
        $meeting3->users()->attach([
            $manager2->id,
            ...$members->whereIn('email', [
                'member5@tasksapp.test',
                'member6@tasksapp.test',
            ])->pluck('id'),
        ]);
    }
}