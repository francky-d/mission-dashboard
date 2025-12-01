<?php

namespace Database\Seeders;

use App\Models\AllowedEmailDomain;
use Illuminate\Database\Seeder;

class AllowedEmailDomainSeeder extends Seeder
{
    /**
     * Run the database seeds.
     */
    public function run(): void
    {
        $domains = [
            [
                'domain' => 'example.com',
                'description' => 'Example domain for testing',
                'is_active' => true,
            ],
            [
                'domain' => 'company.com',
                'description' => 'Company official domain',
                'is_active' => true,
            ],
            [
                'domain' => 'consultant.fr',
                'description' => 'Consultant partner domain',
                'is_active' => true,
            ],
            [
                'domain' => 'tech.io',
                'description' => 'Tech partner domain',
                'is_active' => true,
            ],
            [
                'domain' => 'old-domain.com',
                'description' => 'Deprecated domain',
                'is_active' => false,
            ],
        ];

        foreach ($domains as $domain) {
            AllowedEmailDomain::firstOrCreate(
                ['domain' => $domain['domain']],
                $domain
            );
        }
    }
}
