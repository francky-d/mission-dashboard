<?php

namespace Database\Seeders;

use App\Enums\ApplicationStatus;
use App\Enums\MissionStatus;
use App\Enums\UserRole;
use App\Models\Application;
use App\Models\Mission;
use App\Models\User;
use Illuminate\Database\Seeder;

class ApplicationSeeder extends Seeder
{
    /**
     * Run the database seeds.
     */
    public function run(): void
    {
        $consultants = User::where('role', UserRole::Consultant)->get();
        $activeMissions = Mission::where('status', MissionStatus::Active)->get();

        if ($consultants->isEmpty()) {
            $this->command->warn('No consultant users found. Run UserSeeder first.');

            return;
        }

        if ($activeMissions->isEmpty()) {
            $this->command->warn('No active missions found. Run MissionSeeder first.');

            return;
        }

        // Create pending applications
        foreach ($activeMissions->take(6) as $mission) {
            $applicants = $consultants->random(rand(2, 4));
            foreach ($applicants as $consultant) {
                Application::factory()->create([
                    'mission_id' => $mission->id,
                    'consultant_id' => $consultant->id,
                    'status' => ApplicationStatus::Pending,
                ]);
            }
        }

        // Create viewed applications
        foreach ($activeMissions->skip(6)->take(2) as $mission) {
            $applicants = $consultants->random(rand(1, 3));
            foreach ($applicants as $consultant) {
                Application::factory()->viewed()->create([
                    'mission_id' => $mission->id,
                    'consultant_id' => $consultant->id,
                ]);
            }
        }

        // Create accepted applications
        foreach ($activeMissions->skip(8)->take(1) as $mission) {
            $applicant = $consultants->random();
            Application::factory()->accepted()->create([
                'mission_id' => $mission->id,
                'consultant_id' => $applicant->id,
            ]);
        }

        // Create rejected applications
        foreach ($activeMissions->skip(9) as $mission) {
            $applicants = $consultants->random(rand(1, 2));
            foreach ($applicants as $consultant) {
                Application::factory()->rejected()->create([
                    'mission_id' => $mission->id,
                    'consultant_id' => $consultant->id,
                ]);
            }
        }
    }
}
