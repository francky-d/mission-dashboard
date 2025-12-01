<?php

use App\Livewire\Commercial\Notification\NotificationDropdown;
use App\Livewire\Consultant\Mission\MissionShow;
use App\Models\Application;
use App\Models\Mission;
use App\Models\User;
use App\Notifications\NewApplicationReceived;
use Illuminate\Support\Facades\Notification;
use Livewire\Livewire;

beforeEach(function () {
    $this->commercial = User::factory()->commercial()->create();
    $this->consultant = User::factory()->consultant()->create();
});

describe('NotificationDropdown Component', function () {
    it('displays no notifications when user has none', function () {
        Livewire::actingAs($this->commercial)
            ->test(NotificationDropdown::class)
            ->assertSee(__('Aucune notification'));
    });

    it('displays unread count badge when user has unread notifications', function () {
        $this->commercial->notify(
            new NewApplicationReceived(
                Application::factory()->create([
                    'consultant_id' => $this->consultant->id,
                ])
            )
        );

        Livewire::actingAs($this->commercial)
            ->test(NotificationDropdown::class)
            ->assertSet('unreadCount', 1);
    });

    it('can mark a single notification as read', function () {
        $this->commercial->notify(
            new NewApplicationReceived(
                Application::factory()->create([
                    'consultant_id' => $this->consultant->id,
                ])
            )
        );

        $notification = $this->commercial->unreadNotifications->first();

        Livewire::actingAs($this->commercial)
            ->test(NotificationDropdown::class)
            ->call('markAsRead', $notification->id);

        expect($this->commercial->unreadNotifications()->count())->toBe(0);
    });

    it('can mark all notifications as read', function () {
        // Create multiple notifications
        for ($i = 0; $i < 3; $i++) {
            $this->commercial->notify(
                new NewApplicationReceived(
                    Application::factory()->create([
                        'consultant_id' => $this->consultant->id,
                    ])
                )
            );
        }

        expect($this->commercial->unreadNotifications()->count())->toBe(3);

        Livewire::actingAs($this->commercial)
            ->test(NotificationDropdown::class)
            ->call('markAllAsRead');

        expect($this->commercial->fresh()->unreadNotifications()->count())->toBe(0);
    });
});

describe('NewApplicationReceived Notification', function () {
    it('sends notification via database channel', function () {
        Notification::fake();

        $mission = Mission::factory()->create([
            'commercial_id' => $this->commercial->id,
        ]);

        Livewire::actingAs($this->consultant)
            ->test(MissionShow::class, ['mission' => $mission])
            ->call('apply');

        Notification::assertSentTo($this->commercial, NewApplicationReceived::class);
    });

    it('contains correct application data', function () {
        $mission = Mission::factory()->create([
            'commercial_id' => $this->commercial->id,
            'title' => 'PHP Developer Mission',
        ]);

        $application = Application::factory()->create([
            'mission_id' => $mission->id,
            'consultant_id' => $this->consultant->id,
        ]);

        $notification = new NewApplicationReceived($application);
        $data = $notification->toArray($this->commercial);

        expect($data)
            ->toHaveKey('type', 'new_application_received')
            ->toHaveKey('title', 'Nouvelle candidature')
            ->toHaveKey('application_id', $application->id)
            ->toHaveKey('mission_id', $mission->id)
            ->toHaveKey('mission_title', 'PHP Developer Mission')
            ->toHaveKey('consultant_id', $this->consultant->id)
            ->toHaveKey('consultant_name', $this->consultant->name);
    });
});
