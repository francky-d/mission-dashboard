<?php

namespace App\Livewire\Commercial\Notification;

use App\Notifications\NewApplicationReceived;
use App\Notifications\NewMessageReceived;
use Illuminate\Support\Collection;
use Illuminate\Support\Facades\Auth;
use Illuminate\View\View;
use Livewire\Component;

class NotificationDropdown extends Component
{
    public bool $isOpen = false;

    public function toggle(): void
    {
        $this->isOpen = ! $this->isOpen;
    }

    public function close(): void
    {
        $this->isOpen = false;
    }

    public function goToNotification(string $notificationId): void
    {
        $notification = Auth::user()->notifications()->find($notificationId);

        if (! $notification) {
            return;
        }

        // Marquer comme lu
        $notification->markAsRead();

        // Rediriger selon le type de notification
        $url = match ($notification->type) {
            NewMessageReceived::class => route('commercial.messages.index', [
                'user' => $notification->data['sender_id'] ?? null,
            ]),
            NewApplicationReceived::class => route('commercial.missions.show', [
                'mission' => $notification->data['mission_id'] ?? null,
            ]),
            default => null,
        };

        if ($url) {
            $this->redirect($url, navigate: true);
        }
    }

    public function markAsRead(string $notificationId): void
    {
        $notification = Auth::user()->notifications()->find($notificationId);

        if ($notification) {
            $notification->markAsRead();
        }
    }

    public function markAllAsRead(): void
    {
        Auth::user()->unreadNotifications->markAsRead();
    }

    public function getUnreadCountProperty(): int
    {
        return Auth::user()->unreadNotifications()->count();
    }

    public function getNotificationsProperty(): Collection
    {
        return Auth::user()
            ->notifications()
            ->latest()
            ->take(10)
            ->get();
    }

    /**
     * @return array<string, string>
     */
    public function getListeners(): array
    {
        $userId = Auth::id();

        if ($userId) {
            return [
                "echo-private:App.Models.User.{$userId},.Illuminate\\Notifications\\Events\\BroadcastNotificationCreated" => '$refresh',
            ];
        }

        return [];
    }

    public function render(): View
    {
        return view('livewire.commercial.notification.notification-dropdown');
    }
}
