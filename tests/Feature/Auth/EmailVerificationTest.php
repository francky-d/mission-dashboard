<?php

use App\Enums\UserRole;
use App\Models\User;
use Illuminate\Auth\Events\Verified;
use Illuminate\Support\Facades\Event;
use Illuminate\Support\Facades\URL;

test('email verification screen can be rendered', function () {
    $user = User::factory()->unverified()->create();

    $response = $this->actingAs($user)->get('/verify-email');

    $response->assertStatus(200);
});

test('email can be verified', function () {
    $user = User::factory()->unverified()->create();

    Event::fake();

    $verificationUrl = URL::temporarySignedRoute(
        'verification.verify',
        now()->addMinutes(60),
        ['id' => $user->id, 'hash' => sha1($user->email)]
    );

    $response = $this->actingAs($user)->get($verificationUrl);

    Event::assertDispatched(Verified::class);
    expect($user->fresh()->hasVerifiedEmail())->toBeTrue();
    $response->assertRedirect(route('dashboard', absolute: false).'?verified=1');
});

test('email is not verified with invalid hash', function () {
    $user = User::factory()->unverified()->create();

    $verificationUrl = URL::temporarySignedRoute(
        'verification.verify',
        now()->addMinutes(60),
        ['id' => $user->id, 'hash' => sha1('wrong-email')]
    );

    $this->actingAs($user)->get($verificationUrl);

    expect($user->fresh()->hasVerifiedEmail())->toBeFalse();
});

test('unverified consultant is redirected to verification page', function () {
    $user = User::factory()->unverified()->create([
        'role' => UserRole::Consultant,
    ]);

    $response = $this->actingAs($user)->get(route('consultant.dashboard'));

    $response->assertRedirect(route('verification.notice'));
});

test('unverified commercial is redirected to verification page', function () {
    $user = User::factory()->unverified()->create([
        'role' => UserRole::Commercial,
    ]);

    $response = $this->actingAs($user)->get(route('commercial.dashboard'));

    $response->assertRedirect(route('verification.notice'));
});

test('verified consultant can access dashboard', function () {
    $user = User::factory()->create([
        'role' => UserRole::Consultant,
    ]);

    $response = $this->actingAs($user)->get(route('consultant.dashboard'));

    $response->assertOk();
});

test('verified commercial can access dashboard', function () {
    $user = User::factory()->create([
        'role' => UserRole::Commercial,
    ]);

    $response = $this->actingAs($user)->get(route('commercial.dashboard'));

    $response->assertOk();
});
