<?php

namespace App\Livewire\Consultant\Message;

use App\Models\Message;
use App\Models\User;
use Illuminate\Support\Collection;
use Illuminate\Support\Facades\Auth;
use Illuminate\View\View;
use Livewire\Component;

class ConversationList extends Component
{
    public ?int $selectedUserId = null;

    public function mount(?int $userId = null): void
    {
        $this->selectedUserId = $userId;
    }

    public function selectConversation(int $userId): void
    {
        $this->selectedUserId = $userId;
        $this->dispatch('conversation-selected', userId: $userId);
    }

    public function refreshConversations(): void
    {
        // This will trigger a re-render
    }

    /**
     * @return array<string, string>
     */
    public function getListeners(): array
    {
        $userId = Auth::id();

        if ($userId) {
            return [
                'message-sent' => 'refreshConversations',
                'messages-read' => 'refreshConversations',
                "echo-private:messages.{$userId},MessageSent" => 'refreshConversations',
            ];
        }

        return [
            'message-sent' => 'refreshConversations',
            'messages-read' => 'refreshConversations',
        ];
    }

    public function getConversations(): Collection
    {
        $userId = Auth::id();

        // Get all users that have exchanged messages with the current user
        $conversations = Message::query()
            ->where('sender_id', $userId)
            ->orWhere('receiver_id', $userId)
            ->get()
            ->map(function ($message) use ($userId) {
                return $message->sender_id === $userId
                    ? $message->receiver_id
                    : $message->sender_id;
            })
            ->unique()
            ->values();

        // Get users with their last message and unread count
        return User::whereIn('id', $conversations)
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

                return (object) [
                    'user' => $user,
                    'lastMessage' => $lastMessage,
                    'unreadCount' => $unreadCount,
                ];
            })
            ->sortByDesc(fn ($conv) => $conv->lastMessage?->created_at)
            ->values();
    }

    public function render(): View
    {
        return view('livewire.consultant.message.conversation-list', [
            'conversations' => $this->getConversations(),
        ]);
    }
}
