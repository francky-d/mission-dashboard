<?php

use App\Models\AllowedEmailDomain;
use App\Models\User;
use Livewire\Volt\Volt;

describe('Registration Email Domain Restriction', function () {
    it('allows registration with allowed email domain', function () {
        AllowedEmailDomain::factory()->create(['domain' => 'company.com', 'is_active' => true]);
        AllowedEmailDomain::clearCache();

        Volt::test('pages.auth.consultant.register')
            ->set('name', 'Test User')
            ->set('email', 'user@company.com')
            ->set('password', 'password')
            ->set('password_confirmation', 'password')
            ->call('register')
            ->assertHasNoErrors(['email']);

        $this->assertDatabaseHas('users', [
            'email' => 'user@company.com',
        ]);
    });

    it('blocks registration with disallowed email domain', function () {
        AllowedEmailDomain::factory()->create(['domain' => 'allowed.com', 'is_active' => true]);
        AllowedEmailDomain::clearCache();

        Volt::test('pages.auth.consultant.register')
            ->set('name', 'Test User')
            ->set('email', 'user@notallowed.com')
            ->set('password', 'password')
            ->set('password_confirmation', 'password')
            ->call('register')
            ->assertHasErrors(['email']);

        $this->assertDatabaseMissing('users', [
            'email' => 'user@notallowed.com',
        ]);
    });

    it('allows registration when no domains are configured', function () {
        AllowedEmailDomain::query()->delete();
        AllowedEmailDomain::clearCache();

        Volt::test('pages.auth.consultant.register')
            ->set('name', 'Test User')
            ->set('email', 'anyone@anycompany.com')
            ->set('password', 'password')
            ->set('password_confirmation', 'password')
            ->call('register')
            ->assertHasNoErrors(['email']);

        $this->assertDatabaseHas('users', [
            'email' => 'anyone@anycompany.com',
        ]);
    });

    it('blocks registration with inactive domain', function () {
        AllowedEmailDomain::factory()->create(['domain' => 'inactive.com', 'is_active' => false]);
        AllowedEmailDomain::factory()->create(['domain' => 'active.com', 'is_active' => true]);
        AllowedEmailDomain::clearCache();

        Volt::test('pages.auth.consultant.register')
            ->set('name', 'Test User')
            ->set('email', 'user@inactive.com')
            ->set('password', 'password')
            ->set('password_confirmation', 'password')
            ->call('register')
            ->assertHasErrors(['email']);
    });

    it('allows registration with multiple allowed domains', function () {
        AllowedEmailDomain::factory()->create(['domain' => 'company1.com', 'is_active' => true]);
        AllowedEmailDomain::factory()->create(['domain' => 'company2.com', 'is_active' => true]);
        AllowedEmailDomain::clearCache();

        Volt::test('pages.auth.consultant.register')
            ->set('name', 'User 1')
            ->set('email', 'user1@company1.com')
            ->set('password', 'password')
            ->set('password_confirmation', 'password')
            ->call('register')
            ->assertHasNoErrors(['email']);

        $this->assertDatabaseHas('users', ['email' => 'user1@company1.com']);

        Volt::test('pages.auth.consultant.register')
            ->set('name', 'User 2')
            ->set('email', 'user2@company2.com')
            ->set('password', 'password')
            ->set('password_confirmation', 'password')
            ->call('register')
            ->assertHasNoErrors(['email']);

        $this->assertDatabaseHas('users', ['email' => 'user2@company2.com']);
    });
});

describe('Login Email Domain Restriction', function () {
    it('allows login with allowed email domain', function () {
        AllowedEmailDomain::factory()->create(['domain' => 'company.com', 'is_active' => true]);
        AllowedEmailDomain::clearCache();

        $user = User::factory()->create([
            'email' => 'user@company.com',
            'password' => bcrypt('password'),
        ]);

        Volt::test('pages.auth.login')
            ->set('form.email', 'user@company.com')
            ->set('form.password', 'password')
            ->call('login')
            ->assertHasNoErrors(['form.email']);

        $this->assertAuthenticatedAs($user);
    });

    it('blocks login with disallowed email domain', function () {
        AllowedEmailDomain::factory()->create(['domain' => 'allowed.com', 'is_active' => true]);
        AllowedEmailDomain::clearCache();

        // User was created before domain restriction was added
        $user = User::factory()->create([
            'email' => 'user@blocked.com',
            'password' => bcrypt('password'),
        ]);

        Volt::test('pages.auth.login')
            ->set('form.email', 'user@blocked.com')
            ->set('form.password', 'password')
            ->call('login')
            ->assertHasErrors(['form.email']);

        $this->assertGuest();
    });

    it('allows login when no domains are configured', function () {
        AllowedEmailDomain::query()->delete();
        AllowedEmailDomain::clearCache();

        $user = User::factory()->create([
            'email' => 'anyone@anycompany.com',
            'password' => bcrypt('password'),
        ]);

        Volt::test('pages.auth.login')
            ->set('form.email', 'anyone@anycompany.com')
            ->set('form.password', 'password')
            ->call('login')
            ->assertHasNoErrors(['form.email']);

        $this->assertAuthenticatedAs($user);
    });

    it('blocks login when domain becomes inactive', function () {
        $domain = AllowedEmailDomain::factory()->create(['domain' => 'wasactive.com', 'is_active' => true]);
        // Add another active domain so the restriction is still active
        AllowedEmailDomain::factory()->create(['domain' => 'stillactive.com', 'is_active' => true]);
        AllowedEmailDomain::clearCache();

        $user = User::factory()->create([
            'email' => 'user@wasactive.com',
            'password' => bcrypt('password'),
        ]);

        // Deactivate the domain
        $domain->update(['is_active' => false]);
        AllowedEmailDomain::clearCache();

        Volt::test('pages.auth.login')
            ->set('form.email', 'user@wasactive.com')
            ->set('form.password', 'password')
            ->call('login')
            ->assertHasErrors(['form.email']);

        $this->assertGuest();
    });
});
