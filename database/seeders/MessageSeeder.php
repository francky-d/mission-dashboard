<?php

namespace Database\Seeders;

use App\Models\Application;
use App\Models\Message;
use Illuminate\Database\Seeder;

class MessageSeeder extends Seeder
{
    /**
     * Run the database seeds.
     */
    public function run(): void
    {
        $applications = Application::with(['mission.commercial', 'consultant'])->get();

        if ($applications->isEmpty()) {
            $this->command->warn('No applications found. Run ApplicationSeeder first.');

            return;
        }

        foreach ($applications->take(10) as $application) {
            $commercial = $application->mission->commercial;
            $consultant = $application->consultant;

            // Initial message from consultant
            Message::factory()->create([
                'sender_id' => $consultant->id,
                'receiver_id' => $commercial->id,
                'message' => fake()->randomElement([
                    'Bonjour, je suis très intéressé par cette mission. Pouvons-nous en discuter?',
                    'Je pense avoir le profil idéal pour cette mission. Quand seriez-vous disponible?',
                    'J\'ai une expérience significative dans ce domaine. Je serais ravi d\'en discuter.',
                ]),
            ]);

            // Response from commercial
            Message::factory()->read()->create([
                'sender_id' => $commercial->id,
                'receiver_id' => $consultant->id,
                'message' => fake()->randomElement([
                    'Merci pour votre candidature. Votre profil est intéressant.',
                    'Nous avons bien reçu votre candidature. Nous reviendrons vers vous rapidement.',
                    'Merci de votre intérêt. Pouvez-vous me donner plus de détails sur votre expérience?',
                ]),
            ]);

            // Additional messages for some conversations
            if (rand(0, 1)) {
                Message::factory()->create([
                    'sender_id' => $consultant->id,
                    'receiver_id' => $commercial->id,
                    'message' => 'Bien sûr, je suis disponible pour un appel cette semaine.',
                ]);
            }
        }
    }
}
