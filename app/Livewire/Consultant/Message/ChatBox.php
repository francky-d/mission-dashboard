<?php

namespace App\Livewire\Consultant\Message;

use App\Events\MessageSent;
use App\Models\Message;
use App\Models\User;
use Illuminate\Support\Facades\Auth;
use Illuminate\View\View;
use Livewire\Attributes\Computed;
use Livewire\Attributes\Validate;
use Livewire\Component;

class ChatBox extends Component
{
    public ?int $receiverId = null;

    #[Validate('required|string|max:1000')]
    public string $newMessage = '';

    public function mount(?int $receiverId = null): void
    {
        $this->receiverId = $receiverId;

        if ($this->receiverId) {
            $this->markMessagesAsRead();
        }
    }

    public function loadConversation(int $userId): void
    {
        $this->receiverId = $userId;
        $this->newMessage = '';
        $this->markMessagesAsRead();
    }

    public function markMessagesAsRead(): void
    {
        if (! $this->receiverId) {
            return;
        }

        Message::query()
            ->where('sender_id', $this->receiverId)
            ->where('receiver_id', Auth::id())
            ->whereNull('read_at')
            ->update(['read_at' => now()]);
    }

    #[Computed]
    public function receiver(): ?User
    {
        if (! $this->receiverId) {
            return null;
        }

        return User::find($this->receiverId);
    }

    #[Computed]
    public function chatMessages(): \Illuminate\Support\Collection
    {
        if (! $this->receiverId) {
            return collect();
        }

        $userId = Auth::id();

        return Message::query()
            ->with('sender')
            ->where(function ($q) use ($userId) {
                $q->where('sender_id', $userId)->where('receiver_id', $this->receiverId);
            })
            ->orWhere(function ($q) use ($userId) {
                $q->where('sender_id', $this->receiverId)->where('receiver_id', $userId);
            })
            ->orderBy('created_at', 'asc')
            ->get();
    }

    public function sendMessage(): void
    {
        $this->validate();

        if (! $this->receiverId) {
            return;
        }

        $message = Message::create([
            'sender_id' => Auth::id(),
            'receiver_id' => $this->receiverId,
            'message' => $this->newMessage,
        ]);

        $this->newMessage = '';

        // Broadcast the message to the receiver
        broadcast(new MessageSent($message))->toOthers();

        // Notify conversation list to refresh
        $this->dispatch('message-sent');
    }

    public function receiveMessage(array $data): void
    {
        // Mark as read if we're viewing this conversation
        if (isset($data['message']['sender_id']) && $data['message']['sender_id'] === $this->receiverId) {
            $this->markMessagesAsRead();
        }
    }

    /**
     * @return array<string, string>
     */
    public function getListeners(): array
    {
        $userId = Auth::id();

        if ($userId) {
            return [
                'conversation-selected' => 'loadConversation',
                "echo-private:messages.{$userId},MessageSent" => 'receiveMessage',
            ];
        }

        return [
            'conversation-selected' => 'loadConversation',
        ];
    }

    public function render(): View
    {
        return view('livewire.consultant.message.chat-box');
    }
}
