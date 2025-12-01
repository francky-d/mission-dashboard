<?php

namespace Database\Seeders;

use App\Models\Tag;
use Illuminate\Database\Seeder;

class TagSeeder extends Seeder
{
    /**
     * Run the database seeds.
     */
    public function run(): void
    {
        $tags = [
            'PHP',
            'Laravel',
            'Symfony',
            'JavaScript',
            'TypeScript',
            'React',
            'Vue.js',
            'Angular',
            'Node.js',
            'Python',
            'Django',
            'DevOps',
            'Docker',
            'Kubernetes',
            'AWS',
            'Azure',
            'GCP',
            'CI/CD',
            'Machine Learning',
            'Data Science',
            'PostgreSQL',
            'MySQL',
            'MongoDB',
            'Redis',
            'Elasticsearch',
            'Java',
            'Spring Boot',
            '.NET',
            'C#',
            'Go',
        ];

        foreach ($tags as $tag) {
            Tag::firstOrCreate(['name' => $tag]);
        }
    }
}
