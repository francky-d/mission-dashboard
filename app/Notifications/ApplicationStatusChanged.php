<?php

namespace App\Notifications;

use App\Enums\ApplicationStatus;
use App\Models\Application;
use Illuminate\Bus\Queueable;
use Illuminate\Contracts\Queue\ShouldQueue;
use Illuminate\Notifications\Messages\BroadcastMessage;
use Illuminate\Notifications\Messages\MailMessage;
use Illuminate\Notifications\Notification;

class ApplicationStatusChanged extends Notification implements ShouldQueue
{
    use Queueable;

    /**
     * Create a new notification instance.
     */
    public function __construct(
        public Application $application,
        public ApplicationStatus $oldStatus,
        public ApplicationStatus $newStatus,
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
        $statusLabel = $this->newStatus->label();
        $missionTitle = $this->application->mission->title;

        return (new MailMessage)
            ->subject("Mise à jour de votre candidature - {$missionTitle}")
            ->greeting("Bonjour {$notifiable->name},")
            ->line("Le statut de votre candidature pour la mission \"{$missionTitle}\" a été mis à jour.")
            ->line("Nouveau statut : **{$statusLabel}**")
            ->action('Voir ma candidature', route('consultant.applications.index'))
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
            'type' => 'application_status_changed',
            'title' => $this->getTitle(),
            'application_id' => $this->application->id,
            'mission_id' => $this->application->mission_id,
            'mission_title' => $this->application->mission->title,
            'old_status' => $this->oldStatus->value,
            'new_status' => $this->newStatus->value,
            'new_status_label' => $this->newStatus->label(),
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
     * Get the notification message based on status.
     */
    protected function getMessage(): string
    {
        $missionTitle = $this->application->mission->title;

        return match ($this->newStatus) {
            ApplicationStatus::Viewed => "Votre candidature pour \"{$missionTitle}\" a été consultée.",
            ApplicationStatus::Accepted => "Félicitations ! Votre candidature pour \"{$missionTitle}\" a été acceptée.",
            ApplicationStatus::Rejected => "Votre candidature pour \"{$missionTitle}\" n'a pas été retenue.",
            default => "Le statut de votre candidature pour \"{$missionTitle}\" a été mis à jour.",
        };
    }

    /**
     * Get the notification title based on status.
     */
    protected function getTitle(): string
    {
        return match ($this->newStatus) {
            ApplicationStatus::Viewed => 'Candidature consultée',
            ApplicationStatus::Accepted => 'Candidature acceptée',
            ApplicationStatus::Rejected => 'Candidature refusée',
            default => 'Mise à jour de candidature',
        };
    }
}
