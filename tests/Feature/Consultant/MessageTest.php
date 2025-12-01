<?php

use App\Enums\UserRole;
use App\Events\MessageSent;
use App\Livewire\Consultant\Message\ChatBox;
use App\Livewire\Consultant\Message\ConversationList;
use App\Models\Message;
use App\Models\User;
use Illuminate\Support\Facades\Event;
use Livewire\Livewire;

// Access Tests

it('redirects unauthenticated users to login from messages page', function () {
    $response = $this->get(route('consultant.messages.index'));

    $response->assertRedirect(route('login'));
});

it('denies access to messages page for non-consultant users', function () {
    $commercial = User::factory()->commercial()->create();

    $this->actingAs($commercial)
        ->get(route('consultant.messages.index'))
        ->assertForbidden();
});

it('allows consultants to access the messages page', function () {
    $consultant = User::factory()->consultant()->create();

    $this->actingAs($consultant)
        ->get(route('consultant.messages.index'))
        ->assertOk()
        ->assertSeeLivewire(ConversationList::class)
        ->assertSeeLivewire(ChatBox::class);
});

// ConversationList Tests

it('displays empty state when no conversations exist', function () {
    $consultant = User::factory()->consultant()->create();

    Livewire::actingAs($consultant)
        ->test(ConversationList::class)
        ->assertSee('Aucune conversation pour le moment');
});

it('displays conversations with users who have exchanged messages', function () {
    $consultant = User::factory()->consultant()->create();
    $commercial = User::factory()->commercial()->create();

    Message::factory()->create([
        'sender_id' => $commercial->id,
        'receiver_id' => $consultant->id,
        'message' => 'Bonjour consultant',
    ]);

    Livewire::actingAs($consultant)
        ->test(ConversationList::class)
        ->assertSee($commercial->name)
        ->assertSee('Bonjour consultant');
});

it('displays unread message count in conversation list', function () {
    $consultant = User::factory()->consultant()->create();
    $commercial = User::factory()->commercial()->create();

    Message::factory()->count(3)->create([
        'sender_id' => $commercial->id,
        'receiver_id' => $consultant->id,
        'read_at' => null,
    ]);

    Livewire::actingAs($consultant)
        ->test(ConversationList::class)
        ->assertSee('3');
});

it('orders conversations by most recent message', function () {
    $consultant = User::factory()->consultant()->create();
    $commercial1 = User::factory()->commercial()->create(['name' => 'Commercial One']);
    $commercial2 = User::factory()->commercial()->create(['name' => 'Commercial Two']);

    $oldMessage = Message::factory()->create([
        'sender_id' => $commercial1->id,
        'receiver_id' => $consultant->id,
    ]);

    $newMessage = Message::factory()->create([
        'sender_id' => $commercial2->id,
        'receiver_id' => $consultant->id,
    ]);

    \Illuminate\Support\Facades\DB::table('messages')
        ->where('id', $oldMessage->id)
        ->update(['created_at' => now()->subDays(2)]);

    Livewire::actingAs($consultant)
        ->test(ConversationList::class)
        ->assertSeeInOrder(['Commercial Two', 'Commercial One']);
});

it('can select a conversation', function () {
    $consultant = User::factory()->consultant()->create();
    $commercial = User::factory()->commercial()->create();

    Message::factory()->create([
        'sender_id' => $commercial->id,
        'receiver_id' => $consultant->id,
    ]);

    Livewire::actingAs($consultant)
        ->test(ConversationList::class)
        ->call('selectConversation', $commercial->id)
        ->assertSet('selectedUserId', $commercial->id)
        ->assertDispatched('conversation-selected', userId: $commercial->id);
});

// ChatBox Tests

it('displays empty state when no conversation is selected', function () {
    $consultant = User::factory()->consultant()->create();

    Livewire::actingAs($consultant)
        ->test(ChatBox::class)
        ->assertSee('Sélectionnez une conversation');
});

it('displays messages when conversation is selected', function () {
    $consultant = User::factory()->consultant()->create();
    $commercial = User::factory()->commercial()->create();

    Message::factory()->create([
        'sender_id' => $commercial->id,
        'receiver_id' => $consultant->id,
        'message' => 'Hello from commercial',
    ]);

    Livewire::actingAs($consultant)
        ->test(ChatBox::class, ['receiverId' => $commercial->id])
        ->assertSee('Hello from commercial')
        ->assertSee($commercial->name);
});

it('displays receiver name and role in chat header', function () {
    $consultant = User::factory()->consultant()->create();
    $commercial = User::factory()->commercial()->create(['name' => 'John Commercial']);

    Livewire::actingAs($consultant)
        ->test(ChatBox::class, ['receiverId' => $commercial->id])
        ->assertSee('John Commercial')
        ->assertSee(UserRole::Commercial->label());
});

it('orders messages chronologically', function () {
    $consultant = User::factory()->consultant()->create();
    $commercial = User::factory()->commercial()->create();

    $oldMessage = Message::factory()->create([
        'sender_id' => $commercial->id,
        'receiver_id' => $consultant->id,
        'message' => 'First message',
    ]);

    $newMessage = Message::factory()->create([
        'sender_id' => $consultant->id,
        'receiver_id' => $commercial->id,
        'message' => 'Second message',
    ]);

    \Illuminate\Support\Facades\DB::table('messages')
        ->where('id', $oldMessage->id)
        ->update(['created_at' => now()->subHour()]);

    Livewire::actingAs($consultant)
        ->test(ChatBox::class, ['receiverId' => $commercial->id])
        ->assertSeeInOrder(['First message', 'Second message']);
});

it('can send a message', function () {
    Event::fake([MessageSent::class]);

    $consultant = User::factory()->consultant()->create();
    $commercial = User::factory()->commercial()->create();

    Livewire::actingAs($consultant)
        ->test(ChatBox::class, ['receiverId' => $commercial->id])
        ->set('newMessage', 'Hello commercial!')
        ->call('sendMessage')
        ->assertSet('newMessage', '');

    $this->assertDatabaseHas('messages', [
        'sender_id' => $consultant->id,
        'receiver_id' => $commercial->id,
        'message' => 'Hello commercial!',
    ]);

    Event::assertDispatched(MessageSent::class, function ($event) {
        return $event->message->message === 'Hello commercial!';
    });
});

it('validates message is required', function () {
    $consultant = User::factory()->consultant()->create();
    $commercial = User::factory()->commercial()->create();

    Livewire::actingAs($consultant)
        ->test(ChatBox::class, ['receiverId' => $commercial->id])
        ->set('newMessage', '')
        ->call('sendMessage')
        ->assertHasErrors(['newMessage' => 'required']);
});

it('validates message max length', function () {
    $consultant = User::factory()->consultant()->create();
    $commercial = User::factory()->commercial()->create();

    Livewire::actingAs($consultant)
        ->test(ChatBox::class, ['receiverId' => $commercial->id])
        ->set('newMessage', str_repeat('a', 1001))
        ->call('sendMessage')
        ->assertHasErrors(['newMessage' => 'max']);
});

it('marks messages as read when loading conversation', function () {
    $consultant = User::factory()->consultant()->create();
    $commercial = User::factory()->commercial()->create();

    $message = Message::factory()->create([
        'sender_id' => $commercial->id,
        'receiver_id' => $consultant->id,
        'read_at' => null,
    ]);

    expect($message->read_at)->toBeNull();

    Livewire::actingAs($consultant)
        ->test(ChatBox::class, ['receiverId' => $commercial->id]);

    expect($message->refresh()->read_at)->not->toBeNull();
});

it('loads conversation when conversation-selected event is received', function () {
    $consultant = User::factory()->consultant()->create();
    $commercial = User::factory()->commercial()->create();

    Message::factory()->create([
        'sender_id' => $commercial->id,
        'receiver_id' => $consultant->id,
        'message' => 'Test message',
    ]);

    Livewire::actingAs($consultant)
        ->test(ChatBox::class)
        ->assertDontSee('Test message')
        ->dispatch('conversation-selected', userId: $commercial->id)
        ->assertSee('Test message')
        ->assertSet('receiverId', $commercial->id);
});

it('dispatches message-sent event after sending message', function () {
    Event::fake([MessageSent::class]);

    $consultant = User::factory()->consultant()->create();
    $commercial = User::factory()->commercial()->create();

    Livewire::actingAs($consultant)
        ->test(ChatBox::class, ['receiverId' => $commercial->id])
        ->set('newMessage', 'Test message')
        ->call('sendMessage')
        ->assertDispatched('message-sent');
});

// MessageSent Event Tests

it('broadcasts message to receiver private channel', function () {
    $consultant = User::factory()->consultant()->create();
    $commercial = User::factory()->commercial()->create();

    $message = Message::factory()->create([
        'sender_id' => $consultant->id,
        'receiver_id' => $commercial->id,
        'message' => 'Test broadcast',
    ]);

    $event = new MessageSent($message);

    expect($event->broadcastOn())->toHaveCount(1);
    expect($event->broadcastOn()[0]->name)->toBe('private-messages.'.$commercial->id);
});

it('includes message data in broadcast', function () {
    $consultant = User::factory()->consultant()->create();
    $commercial = User::factory()->commercial()->create();

    $message = Message::factory()->create([
        'sender_id' => $consultant->id,
        'receiver_id' => $commercial->id,
        'message' => 'Test broadcast data',
    ]);

    $event = new MessageSent($message);
    $data = $event->broadcastWith();

    expect($data['message'])->toHaveKeys(['id', 'sender_id', 'sender_name', 'message', 'created_at']);
    expect($data['message']['message'])->toBe('Test broadcast data');
    expect($data['message']['sender_name'])->toBe($consultant->name);
});
