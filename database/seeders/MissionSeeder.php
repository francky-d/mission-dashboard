<?php

namespace Database\Seeders;

use App\Enums\UserRole;
use App\Models\Mission;
use App\Models\Tag;
use App\Models\User;
use Illuminate\Database\Seeder;

class MissionSeeder extends Seeder
{
    /**
     * Run the database seeds.
     */
    public function run(): void
    {
        $commercials = User::where('role', UserRole::Commercial)->get();
        $tags = Tag::all();

        if ($commercials->isEmpty()) {
            $this->command->warn('No commercial users found. Run UserSeeder first.');

            return;
        }

        if ($tags->isEmpty()) {
            $this->command->warn('No tags found. Run TagSeeder first.');

            return;
        }

        // Create active missions
        Mission::factory()
            ->count(12)
            ->active()
            ->create([
                'commercial_id' => fn () => $commercials->random()->id,
            ])
            ->each(fn ($mission) => $mission->tags()->attach($tags->random(rand(2, 5))));

        // Create archived missions
        Mission::factory()
            ->count(6)
            ->archived()
            ->create([
                'commercial_id' => fn () => $commercials->random()->id,
            ])
            ->each(fn ($mission) => $mission->tags()->attach($tags->random(rand(2, 4))));
    }
}
