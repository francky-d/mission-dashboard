<?php

namespace Tests\Feature\Auth;

use Livewire\Volt\Volt;

test('registration screen can be rendered', function () {
    $response = $this->get('/register');

    $response
        ->assertOk()
        ->assertSeeVolt('pages.auth.register');
});

test('registration screen shows role selection', function () {
    $response = $this->get('/register');

    $response
        ->assertOk()
        ->assertSee('Consultant')
        ->assertSee('Commercial');
});

test('new consultant users can register', function () {
    $component = Volt::test('pages.auth.consultant.register')
        ->set('name', 'Test User')
        ->set('email', 'test@example.com')
        ->set('password', 'password')
        ->set('password_confirmation', 'password');

    $component->call('register');

    $component->assertRedirect(route('consultant.dashboard', absolute: false));

    $this->assertAuthenticated();
});

test('new commercial users can register', function () {
    $component = Volt::test('pages.auth.commercial.register')
        ->set('name', 'Test Commercial')
        ->set('email', 'commercial@example.com')
        ->set('password', 'password')
        ->set('password_confirmation', 'password');

    $component->call('register');

    $component->assertRedirect(route('commercial.dashboard', absolute: false));

    $this->assertAuthenticated();
});
