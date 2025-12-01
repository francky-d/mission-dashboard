<?php

namespace Database\Seeders;

use App\Models\ConsultantProfile;
use App\Models\User;
use Illuminate\Database\Seeder;

class UserSeeder extends Seeder
{
    /**
     * Run the database seeds.
     */
    public function run(): void
    {
        // Create Admin user
        $admin = User::factory()->admin()->create([
            'name' => 'Admin User',
            'email' => 'admin@example.com',
        ]);
        $admin->assignRole('Admin');

        // Create Commercial users
        $commercials = collect();
        $commercials->push(User::factory()->commercial()->create([
            'name' => 'Jean Commercial',
            'email' => 'commercial@example.com',
        ]));

        User::factory()
            ->commercial()
            ->count(4)
            ->create()
            ->each(fn ($user) => $commercials->push($user));

        $commercials->each(fn ($user) => $user->assignRole('Commercial'));

        // Create Consultant users with profiles
        $consultants = collect();
        $consultants->push(User::factory()->consultant()->create([
            'name' => 'Marie Consultant',
            'email' => 'consultant@example.com',
        ]));

        User::factory()
            ->consultant()
            ->count(14)
            ->create()
            ->each(fn ($user) => $consultants->push($user));

        $consultants->each(function ($user) {
            $user->assignRole('Consultant');
            ConsultantProfile::factory()
                ->for($user)
                ->withCv()
                ->create();
        });
    }
}
