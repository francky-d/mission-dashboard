<?php

namespace Database\Factories;

use App\Models\User;
use Illuminate\Database\Eloquent\Factories\Factory;

/**
 * @extends \Illuminate\Database\Eloquent\Factories\Factory<\App\Models\ConsultantProfile>
 */
class ConsultantProfileFactory extends Factory
{
    /**
     * Define the model's default state.
     *
     * @return array<string, mixed>
     */
    public function definition(): array
    {
        return [
            'user_id' => User::factory()->consultant(),
            'bio' => fake()->paragraphs(2, true),
            'cv_url' => null,
            'experience_years' => fake()->numberBetween(0, 20),
        ];
    }

    /**
     * Indicate that the consultant has a CV.
     */
    public function withCv(): static
    {
        return $this->state(fn (array $attributes) => [
            'cv_url' => 'cvs/'.fake()->uuid().'.pdf',
        ]);
    }
}
