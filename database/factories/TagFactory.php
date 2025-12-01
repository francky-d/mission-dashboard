<?php

namespace Database\Factories;

use Illuminate\Database\Eloquent\Factories\Factory;

/**
 * @extends \Illuminate\Database\Eloquent\Factories\Factory<\App\Models\Tag>
 */
class TagFactory extends Factory
{
    /**
     * Define the model's default state.
     *
     * @return array<string, mixed>
     */
    public function definition(): array
    {
        return [
            'name' => fake()->unique()->randomElement([
                'PHP', 'Laravel', 'Symfony', 'JavaScript', 'TypeScript',
                'React', 'Vue.js', 'Angular', 'Node.js', 'Python',
                'Django', 'DevOps', 'Docker', 'Kubernetes', 'AWS',
                'Azure', 'GCP', 'CI/CD', 'Machine Learning', 'Data Science',
                'PostgreSQL', 'MySQL', 'MongoDB', 'Redis', 'Elasticsearch',
                'Java', 'Spring Boot', '.NET', 'C#', 'Go',
                'Rust', 'Swift', 'Kotlin', 'Flutter', 'React Native',
            ]),
        ];
    }
}
