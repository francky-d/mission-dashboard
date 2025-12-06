<?php

namespace App\Livewire\Commercial\Message;

use App\Events\MessageSent;
use App\Models\Application;
use App\Models\Message;
use App\Models\User;
use App\Notifications\NewMessageReceived;
use Illuminate\Support\Collection;
use Illuminate\Support\Facades\Auth;
use Illuminate\View\View;
use Livewire\Attributes\Computed;
use Livewire\Attributes\Url;
use Livewire\Attributes\Validate;
use Livewire\Component;

class MessageCenter extends Component
{
    #[Url]
    public ?int $consultant = null;

    #[Validate('required|string|max:1000')]
    public string $newMessage = '';

    public bool $showProfileModal = false;

    public function mount(?int $consultant = null): void
    {
        $this->consultant = $consultant;

        if ($this->consultant) {
            $this->markMessagesAsRead();
        }
    }

    public function openProfileModal(): void
    {
        $this->showProfileModal = true;
    }

    public function closeProfileModal(): void
    {
        $this->showProfileModal = false;
    }

    public function selectConversation(int $userId): void
    {
        $this->consultant = $userId;
        $this->newMessage = '';
        $this->markMessagesAsRead();
    }

    public function markMessagesAsRead(): void
    {
        if (! $this->consultant) {
            return;
        }

        $updated = Message::query()
            ->where('sender_id', $this->consultant)
            ->where('receiver_id', Auth::id())
            ->whereNull('read_at')
            ->update(['read_at' => now()]);

        // Dispatch event to update unread count in navigation
        if ($updated > 0) {
            $this->dispatch('messages-read');
        }
    }

    #[Computed]
    public function receiver(): ?User
    {
        if (! $this->consultant) {
            return null;
        }

        return User::find($this->consultant);
    }

    #[Computed]
    public function chatMessages(): Collection
    {
        if (! $this->consultant) {
            return collect();
        }

        $userId = Auth::id();

        return Message::query()
            ->with('sender')
            ->where(function ($q) use ($userId) {
                $q->where('sender_id', $userId)->where('receiver_id', $this->consultant);
            })
            ->orWhere(function ($q) use ($userId) {
                $q->where('sender_id', $this->consultant)->where('receiver_id', $userId);
            })
            ->orderBy('created_at', 'asc')
            ->get();
    }

    #[Computed]
    public function consultantApplications(): Collection
    {
        if (! $this->consultant) {
            return collect();
        }

        return Application::query()
            ->with('mission')
            ->where('consultant_id', $this->consultant)
            ->whereHas('mission', fn ($q) => $q->where('commercial_id', Auth::id()))
            ->latest()
            ->get();
    }

    public function getConversations(): Collection
    {
        $userId = Auth::id();

        // Get consultants who have applied to commercial's missions OR have exchanged messages
        $consultantIds = Application::query()
            ->whereHas('mission', fn ($q) => $q->where('commercial_id', $userId))
            ->pluck('consultant_id')
            ->unique();

        // Also include users from existing messages
        $messageUserIds = Message::query()
            ->where('sender_id', $userId)
            ->orWhere('receiver_id', $userId)
            ->get()
            ->map(fn ($message) => $message->sender_id === $userId ? $message->receiver_id : $message->sender_id)
            ->unique();

        $allUserIds = $consultantIds->merge($messageUserIds)->unique();

        return User::whereIn('id', $allUserIds)
            ->get()
            ->map(function ($user) use ($userId) {
                $lastMessage = Message::query()
                    ->where(function ($q) use ($userId, $user) {
                        $q->where('sender_id', $userId)->where('receiver_id', $user->id);
                    })
                    ->orWhere(function ($q) use ($userId, $user) {
                        $q->where('sender_id', $user->id)->where('receiver_id', $userId);
                    })
                    ->latest()
                    ->first();

                $unreadCount = Message::query()
                    ->where('sender_id', $user->id)
                    ->where('receiver_id', $userId)
                    ->whereNull('read_at')
                    ->count();

                // Get application info if exists
                $application = Application::query()
                    ->whereHas('mission', fn ($q) => $q->where('commercial_id', $userId))
                    ->where('consultant_id', $user->id)
                    ->with('mission')
                    ->latest()
                    ->first();

                return (object) [
                    'user' => $user,
                    'lastMessage' => $lastMessage,
                    'unreadCount' => $unreadCount,
                    'application' => $application,
                ];
            })
            ->sortByDesc(fn ($conv) => $conv->lastMessage?->created_at ?? $conv->application?->created_at)
            ->values();
    }

    public function sendMessage(): void
    {
        $this->validate();

        if (! $this->consultant) {
            return;
        }

        $message = Message::create([
            'sender_id' => Auth::id(),
            'receiver_id' => $this->consultant,
            'message' => $this->newMessage,
        ]);

        $this->newMessage = '';

        // Broadcast the message to the receiver
        broadcast(new MessageSent($message))->toOthers();

        // Send notification to the consultant
        $receiver = User::find($this->consultant);
        if ($receiver) {
            $receiver->notify(new NewMessageReceived($message));
        }
    }

    public function receiveMessage(array $data): void
    {
        // Mark as read if we're viewing this conversation
        if (isset($data['message']['sender_id']) && $data['message']['sender_id'] === $this->consultant) {
            $this->markMessagesAsRead();
        }

        // Dispatch event to refresh navigation unread count
        $this->dispatch('messages-read');
    }

    /**
     * @return array<string, string>
     */
    public function getListeners(): array
    {
        $userId = Auth::id();

        if ($userId) {
            return [
                "echo-private:messages.{$userId},MessageSent" => 'receiveMessage',
                'messages-read' => '$refresh',
            ];
        }

        return [];
    }

    public function render(): View
    {
        return view('livewire.commercial.message.message-center', [
            'conversations' => $this->getConversations(),
        ]);
    }
}
