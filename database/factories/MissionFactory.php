<?php

namespace Database\Factories;

use App\Enums\MissionStatus;
use App\Models\User;
use Illuminate\Database\Eloquent\Factories\Factory;

/**
 * @extends \Illuminate\Database\Eloquent\Factories\Factory<\App\Models\Mission>
 */
class MissionFactory extends Factory
{
    /**
     * Define the model's default state.
     *
     * @return array<string, mixed>
     */
    public function definition(): array
    {
        return [
            'commercial_id' => User::factory()->commercial(),
            'title' => fake()->jobTitle().' - '.fake()->randomElement(['Junior', 'Confirmé', 'Senior', 'Lead', 'Expert']),
            'description' => fake()->paragraphs(3, true),
            'location' => fake()->city().', France',
            'status' => MissionStatus::Active,
        ];
    }

    /**
     * Indicate that the mission is active.
     */
    public function active(): static
    {
        return $this->state(fn (array $attributes) => [
            'status' => MissionStatus::Active,
        ]);
    }

    /**
     * Indicate that the mission is archived.
     */
    public function archived(): static
    {
        return $this->state(fn (array $attributes) => [
            'status' => MissionStatus::Archived,
        ]);
    }
}
