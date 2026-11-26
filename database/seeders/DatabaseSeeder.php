<?php

namespace Database\Seeders;

use Illuminate\Database\Seeder;

class DatabaseSeeder extends Seeder
{
    public function run(): void
    {
        $this->call([
            UserSeeder::class,
            TeamSeeder::class,
            ProjectSeeder::class,
            TaskSeeder::class,
            MeetingSeeder::class,
            CommentSeeder::class,
            ChecklistItemSeeder::class,
            MessageSeeder::class,
            FileSeeder::class,
            NotificationSeeder::class,
            ActivityLogSeeder::class,
        ]);

        $this->command->info('✅ Database seeded successfully!');
        $this->command->info('');
        $this->command->info('🔑 Test Accounts:');
        $this->command->info('   Admin    : admin@tasksapp.test    / password');
        $this->command->info('   Manager  : manager1@tasksapp.test / password');
        $this->command->info('   Manager  : manager2@tasksapp.test / password');
        $this->command->info('   Member   : member1@tasksapp.test  / password');
        $this->command->info('   Member   : member2@tasksapp.test  / password');
        $this->command->info('   ... etc (member1..member7)');
    }
}