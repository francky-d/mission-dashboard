<?php

namespace Database\Factories;

use App\Enums\ApplicationStatus;
use App\Models\Mission;
use App\Models\User;
use Illuminate\Database\Eloquent\Factories\Factory;

/**
 * @extends \Illuminate\Database\Eloquent\Factories\Factory<\App\Models\Application>
 */
class ApplicationFactory extends Factory
{
    /**
     * Define the model's default state.
     *
     * @return array<string, mixed>
     */
    public function definition(): array
    {
        return [
            'mission_id' => Mission::factory(),
            'consultant_id' => User::factory()->consultant(),
            'status' => ApplicationStatus::Pending,
        ];
    }

    /**
     * Indicate that the application is viewed.
     */
    public function viewed(): static
    {
        return $this->state(fn (array $attributes) => [
            'status' => ApplicationStatus::Viewed,
        ]);
    }

    /**
     * Indicate that the application is accepted.
     */
    public function accepted(): static
    {
        return $this->state(fn (array $attributes) => [
            'status' => ApplicationStatus::Accepted,
        ]);
    }

    /**
     * Indicate that the application is rejected.
     */
    public function rejected(): static
    {
        return $this->state(fn (array $attributes) => [
            'status' => ApplicationStatus::Rejected,
        ]);
    }
}
