<?php

use App\Enums\UserRole;
use App\Models\SiteSettings;
use App\Models\User;
use Livewire\Volt\Volt;

beforeEach(function () {
    // Ensure SiteSettings exists with default values
    SiteSettings::instance();
});

describe('Consultant Authentication', function () {
    test('consultant login screen can be rendered', function () {
        $response = $this->get('/consultant/login');

        $response->assertOk();
        $response->assertSee('Connexion Consultant');
        $response->assertSee('Espace Consultant');
    });

    test('consultant register screen can be rendered', function () {
        $response = $this->get('/consultant/register');

        $response->assertOk();
        $response->assertSee('Inscription Consultant');
        $response->assertSee('votre compte consultant');
    });

    test('consultants can authenticate using the consultant login screen', function () {
        $user = User::factory()->create([
            'role' => UserRole::Consultant,
        ]);

        $component = Volt::test('pages.auth.consultant.login')
            ->set('form.email', $user->email)
            ->set('form.password', 'password');

        $component->call('login');

        $component
            ->assertHasNoErrors()
            ->assertRedirect(route('dashboard', absolute: false));

        $this->assertAuthenticated();
    });

    test('new consultants can register with consultant role', function () {
        $component = Volt::test('pages.auth.consultant.register')
            ->set('name', 'Test Consultant')
            ->set('email', 'consultant@example.com')
            ->set('password', 'password')
            ->set('password_confirmation', 'password');

        $component->call('register');

        $component
            ->assertHasNoErrors()
            ->assertRedirect(route('dashboard', absolute: false));

        $this->assertAuthenticated();

        $user = User::where('email', 'consultant@example.com')->first();
        expect($user)->not->toBeNull();
        expect($user->role)->toBe(UserRole::Consultant);
    });

    test('consultant login page displays consultant theme', function () {
        $response = $this->get('/consultant/login');

        $response->assertOk();
        $response->assertSee('--theme-primary:', false);
    });
});

describe('Commercial Authentication', function () {
    test('commercial login screen can be rendered', function () {
        $response = $this->get('/commercial/login');

        $response->assertOk();
        $response->assertSee('Connexion Commercial');
        $response->assertSee('Espace Commercial');
    });

    test('commercial register screen can be rendered', function () {
        $response = $this->get('/commercial/register');

        $response->assertOk();
        $response->assertSee('Inscription Commercial');
        $response->assertSee('votre compte commercial');
    });

    test('commercials can authenticate using the commercial login screen', function () {
        $user = User::factory()->create([
            'role' => UserRole::Commercial,
        ]);

        $component = Volt::test('pages.auth.commercial.login')
            ->set('form.email', $user->email)
            ->set('form.password', 'password');

        $component->call('login');

        $component
            ->assertHasNoErrors()
            ->assertRedirect(route('dashboard', absolute: false));

        $this->assertAuthenticated();
    });

    test('new commercials can register with commercial role', function () {
        $component = Volt::test('pages.auth.commercial.register')
            ->set('name', 'Test Commercial')
            ->set('email', 'commercial@example.com')
            ->set('password', 'password')
            ->set('password_confirmation', 'password');

        $component->call('register');

        $component
            ->assertHasNoErrors()
            ->assertRedirect(route('dashboard', absolute: false));

        $this->assertAuthenticated();

        $user = User::where('email', 'commercial@example.com')->first();
        expect($user)->not->toBeNull();
        expect($user->role)->toBe(UserRole::Commercial);
    });

    test('commercial login page displays commercial theme', function () {
        $response = $this->get('/commercial/login');

        $response->assertOk();
        $response->assertSee('--theme-primary:', false);
    });
});

describe('Landing Page', function () {
    test('landing page can be rendered', function () {
        $response = $this->get('/');

        $response->assertOk();
        $response->assertSee('Mission Dashboard');
    });

    test('landing page shows role selection cards', function () {
        $response = $this->get('/');

        $response->assertOk();
        $response->assertSee('Je suis Consultant');
        $response->assertSee('Je suis Commercial');
    });

    test('landing page has links to role-specific auth pages', function () {
        $response = $this->get('/');

        $response->assertOk();
        $response->assertSee(route('consultant.register'));
        $response->assertSee(route('consultant.login'));
        $response->assertSee(route('commercial.register'));
        $response->assertSee(route('commercial.login'));
    });

    test('landing page displays site settings colors', function () {
        $response = $this->get('/');

        $response->assertOk();
        $response->assertSee('--consultant-primary', false);
        $response->assertSee('--commercial-primary', false);
    });
});
