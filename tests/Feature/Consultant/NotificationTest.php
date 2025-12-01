<?php

use App\Enums\ApplicationStatus;
use App\Enums\UserRole;
use App\Livewire\Consultant\Notification\NotificationDropdown;
use App\Models\Application;
use App\Models\Mission;
use App\Models\User;
use App\Notifications\ApplicationStatusChanged;
use App\Notifications\NewMessageReceived;
use Illuminate\Support\Facades\Notification;
use Livewire\Livewire;

beforeEach(function () {
    $this->consultant = User::factory()->create(['role' => UserRole::Consultant]);
});

describe('NotificationDropdown Component', function () {
    it('displays no notifications when user has none', function () {
        Livewire::actingAs($this->consultant)
            ->test(NotificationDropdown::class)
            ->assertSee('Notifications')
            ->assertSee('Aucune notification');
    });

    it('displays unread count badge when user has unread notifications', function () {
        // Create notifications for the user
        $this->consultant->notify(
            new ApplicationStatusChanged(
                Application::factory()->for($this->consultant, 'consultant')->for(Mission::factory())->create(),
                ApplicationStatus::Pending,
                ApplicationStatus::Viewed
            )
        );

        Livewire::actingAs($this->consultant)
            ->test(NotificationDropdown::class)
            ->assertSet('unreadCount', 1);
    });

    it('can mark a single notification as read', function () {
        $application = Application::factory()
            ->for($this->consultant, 'consultant')
            ->for(Mission::factory())
            ->create();

        $this->consultant->notify(
            new ApplicationStatusChanged(
                $application,
                ApplicationStatus::Pending,
                ApplicationStatus::Accepted
            )
        );

        $notification = $this->consultant->notifications()->first();

        expect($notification->read_at)->toBeNull();

        Livewire::actingAs($this->consultant)
            ->test(NotificationDropdown::class)
            ->assertSet('unreadCount', 1)
            ->call('markAsRead', $notification->id)
            ->assertSet('unreadCount', 0);

        expect($this->consultant->notifications()->first()->read_at)->not->toBeNull();
    });

    it('can mark all notifications as read', function () {
        // Create multiple notifications with different missions
        for ($i = 0; $i < 3; $i++) {
            $application = Application::factory()
                ->for($this->consultant, 'consultant')
                ->for(Mission::factory())
                ->create();

            $this->consultant->notify(
                new ApplicationStatusChanged(
                    $application,
                    ApplicationStatus::Pending,
                    ApplicationStatus::Viewed
                )
            );
        }

        expect($this->consultant->unreadNotifications()->count())->toBe(3);

        Livewire::actingAs($this->consultant)
            ->test(NotificationDropdown::class)
            ->assertSet('unreadCount', 3)
            ->call('markAllAsRead')
            ->assertSet('unreadCount', 0);

        expect($this->consultant->unreadNotifications()->count())->toBe(0);
    });

    it('limits displayed notifications to 10', function () {
        // Create 15 notifications with different missions
        for ($i = 0; $i < 15; $i++) {
            $application = Application::factory()
                ->for($this->consultant, 'consultant')
                ->for(Mission::factory())
                ->create();

            $this->consultant->notify(
                new ApplicationStatusChanged(
                    $application,
                    ApplicationStatus::Pending,
                    ApplicationStatus::Viewed
                )
            );
        }

        $component = Livewire::actingAs($this->consultant)
            ->test(NotificationDropdown::class);

        expect($component->get('notifications'))->toHaveCount(10);
    });
});

describe('ApplicationStatusChanged Notification', function () {
    it('sends notification via database channel', function () {
        Notification::fake();

        $application = Application::factory()
            ->for($this->consultant, 'consultant')
            ->for(Mission::factory())
            ->create();

        $this->consultant->notify(
            new ApplicationStatusChanged(
                $application,
                ApplicationStatus::Pending,
                ApplicationStatus::Accepted
            )
        );

        Notification::assertSentTo(
            $this->consultant,
            ApplicationStatusChanged::class,
            function ($notification, $channels) {
                return in_array('database', $channels);
            }
        );
    });

    it('contains correct data for accepted status', function () {
        $mission = Mission::factory()->create(['title' => 'Mission Test']);
        $application = Application::factory()
            ->for($this->consultant, 'consultant')
            ->for($mission)
            ->create();

        $notification = new ApplicationStatusChanged(
            $application,
            ApplicationStatus::Pending,
            ApplicationStatus::Accepted
        );

        $data = $notification->toArray($this->consultant);

        expect($data)
            ->toHaveKey('type', 'application_status_changed')
            ->toHaveKey('title', 'Candidature acceptée')
            ->toHaveKey('application_id', $application->id)
            ->toHaveKey('mission_title', 'Mission Test')
            ->toHaveKey('new_status', ApplicationStatus::Accepted->value);

        expect($data['message'])->toContain('Félicitations');
    });

    it('contains correct data for rejected status', function () {
        $mission = Mission::factory()->create(['title' => 'Mission Test']);
        $application = Application::factory()
            ->for($this->consultant, 'consultant')
            ->for($mission)
            ->create();

        $notification = new ApplicationStatusChanged(
            $application,
            ApplicationStatus::Pending,
            ApplicationStatus::Rejected
        );

        $data = $notification->toArray($this->consultant);

        expect($data)
            ->toHaveKey('title', 'Candidature refusée')
            ->toHaveKey('new_status', ApplicationStatus::Rejected->value);

        expect($data['message'])->toContain("n'a pas été retenue");
    });
});

describe('NewMessageReceived Notification', function () {
    it('sends notification via database channel', function () {
        Notification::fake();

        $sender = User::factory()->create();
        $message = \App\Models\Message::factory()
            ->for($sender, 'sender')
            ->for($this->consultant, 'receiver')
            ->create();

        $this->consultant->notify(new NewMessageReceived($message));

        Notification::assertSentTo(
            $this->consultant,
            NewMessageReceived::class,
            function ($notification, $channels) {
                return in_array('database', $channels);
            }
        );
    });

    it('contains correct message data', function () {
        $sender = User::factory()->create(['name' => 'Jean Dupont']);
        $message = \App\Models\Message::factory()
            ->for($sender, 'sender')
            ->for($this->consultant, 'receiver')
            ->create(['message' => 'Bonjour, comment ça va ?']);

        $notification = new NewMessageReceived($message);
        $data = $notification->toArray($this->consultant);

        expect($data)
            ->toHaveKey('type', 'new_message')
            ->toHaveKey('title', 'Nouveau message')
            ->toHaveKey('sender_name', 'Jean Dupont')
            ->toHaveKey('message_id', $message->id);

        expect($data['message'])->toContain('Jean Dupont');
    });
});
