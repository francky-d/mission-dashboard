<?php

namespace App\Notifications;

use App\Models\Application;
use Illuminate\Bus\Queueable;
use Illuminate\Contracts\Queue\ShouldQueue;
use Illuminate\Notifications\Messages\BroadcastMessage;
use Illuminate\Notifications\Messages\MailMessage;
use Illuminate\Notifications\Notification;

class NewApplicationReceived extends Notification implements ShouldQueue
{
    use Queueable;

    /**
     * Create a new notification instance.
     */
    public function __construct(
        public Application $application,
    ) {}

    /**
     * Get the notification's delivery channels.
     *
     * @return array<int, string>
     */
    public function via(object $notifiable): array
    {
        return ['database', 'broadcast'];
    }

    /**
     * Get the mail representation of the notification.
     */
    public function toMail(object $notifiable): MailMessage
    {
        $missionTitle = $this->application->mission->title;
        $consultantName = $this->application->consultant->name;

        return (new MailMessage)
            ->subject("Nouvelle candidature - {$missionTitle}")
            ->greeting("Bonjour {$notifiable->name},")
            ->line("Vous avez reçu une nouvelle candidature de **{$consultantName}** pour la mission \"{$missionTitle}\".")
            ->action('Voir les candidatures', route('commercial.missions.show', $this->application->mission))
            ->line('Merci de votre confiance !');
    }

    /**
     * Get the array representation of the notification.
     *
     * @return array<string, mixed>
     */
    public function toArray(object $notifiable): array
    {
        return [
            'type' => 'new_application_received',
            'title' => 'Nouvelle candidature',
            'application_id' => $this->application->id,
            'mission_id' => $this->application->mission_id,
            'mission_title' => $this->application->mission->title,
            'consultant_id' => $this->application->consultant_id,
            'consultant_name' => $this->application->consultant->name,
            'message' => $this->getMessage(),
        ];
    }

    /**
     * Get the broadcastable representation of the notification.
     */
    public function toBroadcast(object $notifiable): BroadcastMessage
    {
        return new BroadcastMessage($this->toArray($notifiable));
    }

    /**
     * Get the notification message.
     */
    protected function getMessage(): string
    {
        $missionTitle = $this->application->mission->title;
        $consultantName = $this->application->consultant->name;

        return "{$consultantName} a postulé à votre mission \"{$missionTitle}\".";
    }
}
