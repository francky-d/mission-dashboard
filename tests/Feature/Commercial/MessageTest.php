<?php

use App\Livewire\Commercial\Message\MessageCenter;
use App\Models\Application;
use App\Models\Message;
use App\Models\Mission;
use App\Models\User;
use App\Notifications\NewMessageReceived;
use Illuminate\Support\Facades\Notification;
use Livewire\Livewire;

beforeEach(function () {
    $this->commercial = User::factory()->commercial()->create();
    $this->consultant = User::factory()->consultant()->create();
});

describe('Access Control', function () {
    it('redirects unauthenticated users to login from messages page', function () {
        $this->get(route('commercial.messages.index'))
            ->assertRedirect(route('login'));
    });

    it('denies access to messages page for non-commercial users', function () {
        $this->actingAs($this->consultant)
            ->get(route('commercial.messages.index'))
            ->assertForbidden();
    });

    it('allows commercials to access the messages page', function () {
        $this->actingAs($this->commercial)
            ->get(route('commercial.messages.index'))
            ->assertOk()
            ->assertSeeLivewire(MessageCenter::class);
    });
});

describe('Conversation List', function () {
    it('displays empty state when no conversations exist', function () {
        Livewire::actingAs($this->commercial)
            ->test(MessageCenter::class)
            ->assertSee(__('Aucun consultant à contacter.'));
    });

    it('displays consultants who have applied to commercials missions', function () {
        $mission = Mission::factory()->create([
            'commercial_id' => $this->commercial->id,
        ]);
        Application::factory()->create([
            'mission_id' => $mission->id,
            'consultant_id' => $this->consultant->id,
        ]);

        Livewire::actingAs($this->commercial)
            ->test(MessageCenter::class)
            ->assertSee($this->consultant->name);
    });

    it('displays unread message count in conversation list', function () {
        // Create messages from consultant to commercial
        Message::factory()->count(3)->create([
            'sender_id' => $this->consultant->id,
            'receiver_id' => $this->commercial->id,
            'read_at' => null,
        ]);

        Livewire::actingAs($this->commercial)
            ->test(MessageCenter::class)
            ->assertSee('3');
    });

    it('can select a conversation', function () {
        $mission = Mission::factory()->create([
            'commercial_id' => $this->commercial->id,
        ]);
        Application::factory()->create([
            'mission_id' => $mission->id,
            'consultant_id' => $this->consultant->id,
        ]);

        Livewire::actingAs($this->commercial)
            ->test(MessageCenter::class)
            ->call('selectConversation', $this->consultant->id)
            ->assertSet('consultant', $this->consultant->id);
    });
});

describe('Chat Functionality', function () {
    it('displays empty state when no conversation is selected', function () {
        Livewire::actingAs($this->commercial)
            ->test(MessageCenter::class)
            ->assertSee(__('Sélectionnez un consultant'));
    });

    it('displays messages when conversation is selected', function () {
        Message::factory()->create([
            'sender_id' => $this->commercial->id,
            'receiver_id' => $this->consultant->id,
            'message' => 'Hello consultant!',
        ]);

        Livewire::actingAs($this->commercial)
            ->test(MessageCenter::class, ['consultant' => $this->consultant->id])
            ->assertSee('Hello consultant!');
    });

    it('displays receiver name in chat header', function () {
        Livewire::actingAs($this->commercial)
            ->test(MessageCenter::class, ['consultant' => $this->consultant->id])
            ->assertSee($this->consultant->name);
    });

    it('can send a message', function () {
        Notification::fake();

        Livewire::actingAs($this->commercial)
            ->test(MessageCenter::class, ['consultant' => $this->consultant->id])
            ->set('newMessage', 'Test message from commercial')
            ->call('sendMessage');

        $this->assertDatabaseHas('messages', [
            'sender_id' => $this->commercial->id,
            'receiver_id' => $this->consultant->id,
            'message' => 'Test message from commercial',
        ]);
    });

    it('validates message is required', function () {
        Livewire::actingAs($this->commercial)
            ->test(MessageCenter::class, ['consultant' => $this->consultant->id])
            ->set('newMessage', '')
            ->call('sendMessage')
            ->assertHasErrors(['newMessage']);
    });

    it('validates message max length', function () {
        Livewire::actingAs($this->commercial)
            ->test(MessageCenter::class, ['consultant' => $this->consultant->id])
            ->set('newMessage', str_repeat('a', 1001))
            ->call('sendMessage')
            ->assertHasErrors(['newMessage']);
    });

    it('marks messages as read when loading conversation', function () {
        Message::factory()->create([
            'sender_id' => $this->consultant->id,
            'receiver_id' => $this->commercial->id,
            'read_at' => null,
        ]);

        Livewire::actingAs($this->commercial)
            ->test(MessageCenter::class, ['consultant' => $this->consultant->id]);

        $this->assertDatabaseMissing('messages', [
            'sender_id' => $this->consultant->id,
            'receiver_id' => $this->commercial->id,
            'read_at' => null,
        ]);
    });

    it('sends notification to consultant when message is sent', function () {
        Notification::fake();

        Livewire::actingAs($this->commercial)
            ->test(MessageCenter::class, ['consultant' => $this->consultant->id])
            ->set('newMessage', 'Hello!')
            ->call('sendMessage');

        Notification::assertSentTo($this->consultant, NewMessageReceived::class);
    });
});

describe('URL Query Parameter', function () {
    it('loads conversation from URL parameter', function () {
        $mission = Mission::factory()->create([
            'commercial_id' => $this->commercial->id,
        ]);
        Application::factory()->create([
            'mission_id' => $mission->id,
            'consultant_id' => $this->consultant->id,
        ]);

        $this->actingAs($this->commercial)
            ->get(route('commercial.messages.index', ['consultant' => $this->consultant->id]))
            ->assertOk();

        Livewire::actingAs($this->commercial)
            ->test(MessageCenter::class, ['consultant' => $this->consultant->id])
            ->assertSet('consultant', $this->consultant->id)
            ->assertSee($this->consultant->name);
    });
});
