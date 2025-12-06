<?php

use App\Enums\UserRole;
use App\Models\User;

describe('Suspended User Access', function () {
    test('suspended consultant is logged out and redirected to login', function () {
        $user = User::factory()->create([
            'role' => UserRole::Consultant,
            'suspended_at' => now(),
        ]);

        $response = $this->actingAs($user)->get(route('consultant.dashboard'));

        $response->assertRedirect(route('login'));
        $this->assertGuest();
    });

    test('suspended commercial is logged out and redirected to login', function () {
        $user = User::factory()->create([
            'role' => UserRole::Commercial,
            'suspended_at' => now(),
        ]);

        $response = $this->actingAs($user)->get(route('commercial.dashboard'));

        $response->assertRedirect(route('login'));
        $this->assertGuest();
    });

    test('suspended user sees error message on login page', function () {
        $user = User::factory()->create([
            'role' => UserRole::Consultant,
            'suspended_at' => now(),
        ]);

        $response = $this->actingAs($user)->get(route('consultant.dashboard'));

        $response->assertSessionHasErrors(['email']);
    });

    test('suspended consultant cannot access missions list', function () {
        $user = User::factory()->create([
            'role' => UserRole::Consultant,
            'suspended_at' => now(),
        ]);

        $response = $this->actingAs($user)->get(route('consultant.missions.index'));

        $response->assertRedirect(route('login'));
        $this->assertGuest();
    });

    test('suspended commercial cannot access missions list', function () {
        $user = User::factory()->create([
            'role' => UserRole::Commercial,
            'suspended_at' => now(),
        ]);

        $response = $this->actingAs($user)->get(route('commercial.missions.index'));

        $response->assertRedirect(route('login'));
        $this->assertGuest();
    });

    test('suspended consultant cannot access messages', function () {
        $user = User::factory()->create([
            'role' => UserRole::Consultant,
            'suspended_at' => now(),
        ]);

        $response = $this->actingAs($user)->get(route('consultant.messages.index'));

        $response->assertRedirect(route('login'));
        $this->assertGuest();
    });

    test('suspended commercial cannot access messages', function () {
        $user = User::factory()->create([
            'role' => UserRole::Commercial,
            'suspended_at' => now(),
        ]);

        $response = $this->actingAs($user)->get(route('commercial.messages.index'));

        $response->assertRedirect(route('login'));
        $this->assertGuest();
    });

    test('non-suspended consultant can access dashboard', function () {
        $user = User::factory()->create([
            'role' => UserRole::Consultant,
            'suspended_at' => null,
        ]);

        $response = $this->actingAs($user)->get(route('consultant.dashboard'));

        $response->assertOk();
    });

    test('non-suspended commercial can access dashboard', function () {
        $user = User::factory()->create([
            'role' => UserRole::Commercial,
            'suspended_at' => null,
        ]);

        $response = $this->actingAs($user)->get(route('commercial.dashboard'));

        $response->assertOk();
    });

    test('unsuspended user can access application again', function () {
        $user = User::factory()->create([
            'role' => UserRole::Consultant,
            'suspended_at' => now(),
        ]);

        // User is suspended, cannot access
        $this->actingAs($user)->get(route('consultant.dashboard'));
        $this->assertGuest();

        // Admin unsuspends the user
        $user->unsuspend();

        // User can now access
        $response = $this->actingAs($user)->get(route('consultant.dashboard'));
        $response->assertOk();
    });
});
