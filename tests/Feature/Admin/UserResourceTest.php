<?php

use App\Enums\UserRole;
use App\Filament\Resources\Users\Pages\CreateUser;
use App\Filament\Resources\Users\Pages\EditUser;
use App\Filament\Resources\Users\Pages\ListUsers;
use App\Filament\Resources\Users\UserResource;
use App\Models\User;
use Filament\Facades\Filament;
use Livewire\Livewire;

beforeEach(function () {
    Filament::setCurrentPanel(Filament::getPanel('admin'));
});

describe('UserResource Access Control', function () {
    it('denies access to non-admin users', function () {
        $consultant = User::factory()->create(['role' => UserRole::Consultant]);

        $this->actingAs($consultant)
            ->get(UserResource::getUrl('index'))
            ->assertForbidden();
    });

    it('allows access to admin users', function () {
        $admin = User::factory()->create(['role' => UserRole::Admin]);

        $this->actingAs($admin)
            ->get(UserResource::getUrl('index'))
            ->assertSuccessful();
    });
});

describe('UserResource CRUD', function () {
    beforeEach(function () {
        $this->admin = User::factory()->create(['role' => UserRole::Admin]);
        $this->actingAs($this->admin);
    });

    it('can list users', function () {
        $users = User::factory()->count(5)->create();

        Livewire::test(ListUsers::class)
            ->assertCanSeeTableRecords($users);
    });

    it('can search users by name', function () {
        $user1 = User::factory()->create(['name' => 'John Doe']);
        $user2 = User::factory()->create(['name' => 'Jane Smith']);

        Livewire::test(ListUsers::class)
            ->searchTable('John')
            ->assertCanSeeTableRecords([$user1])
            ->assertCanNotSeeTableRecords([$user2]);
    });

    it('can filter users by role', function () {
        $consultant = User::factory()->create(['role' => UserRole::Consultant]);
        $commercial = User::factory()->create(['role' => UserRole::Commercial]);

        Livewire::test(ListUsers::class)
            ->filterTable('role', UserRole::Consultant->value)
            ->assertCanSeeTableRecords([$consultant])
            ->assertCanNotSeeTableRecords([$commercial]);
    });

    it('can create a user', function () {
        Livewire::test(CreateUser::class)
            ->fillForm([
                'name' => 'New User',
                'email' => 'newuser@example.com',
                'role' => UserRole::Consultant,
                'password' => 'password123',
                'password_confirmation' => 'password123',
            ])
            ->call('create')
            ->assertHasNoFormErrors();

        $this->assertDatabaseHas('users', [
            'name' => 'New User',
            'email' => 'newuser@example.com',
            'role' => UserRole::Consultant->value,
        ]);
    });

    it('validates required fields on create', function () {
        Livewire::test(CreateUser::class)
            ->fillForm([
                'name' => '',
                'email' => '',
                'password' => '',
            ])
            ->call('create')
            ->assertHasFormErrors(['name' => 'required', 'email' => 'required', 'password' => 'required']);
    });

    it('can edit a user', function () {
        $user = User::factory()->create(['name' => 'Old Name']);

        Livewire::test(EditUser::class, ['record' => $user->getRouteKey()])
            ->fillForm([
                'name' => 'New Name',
            ])
            ->call('save')
            ->assertHasNoFormErrors();

        expect($user->fresh()->name)->toBe('New Name');
    });

    it('can change user role', function () {
        $user = User::factory()->create(['role' => UserRole::Consultant]);

        Livewire::test(EditUser::class, ['record' => $user->getRouteKey()])
            ->fillForm([
                'role' => UserRole::Commercial,
            ])
            ->call('save')
            ->assertHasNoFormErrors();

        expect($user->fresh()->role)->toBe(UserRole::Commercial);
    });
});

describe('User Suspension', function () {
    beforeEach(function () {
        $this->admin = User::factory()->create(['role' => UserRole::Admin]);
        $this->actingAs($this->admin);
    });

    it('can suspend a user', function () {
        $user = User::factory()->create();

        Livewire::test(ListUsers::class)
            ->callTableAction('suspend', $user);

        expect($user->fresh()->isSuspended())->toBeTrue();
    });

    it('can unsuspend a user', function () {
        $user = User::factory()->create(['suspended_at' => now()]);

        Livewire::test(ListUsers::class)
            ->callTableAction('unsuspend', $user);

        expect($user->fresh()->isSuspended())->toBeFalse();
    });

    it('cannot suspend self', function () {
        Livewire::test(ListUsers::class)
            ->assertTableActionHidden('suspend', $this->admin);
    });

    it('can delete a user', function () {
        $user = User::factory()->create();

        Livewire::test(ListUsers::class)
            ->callTableAction('delete', $user);

        $this->assertDatabaseMissing('users', [
            'id' => $user->id,
        ]);
    });

    it('cannot delete self', function () {
        Livewire::test(ListUsers::class)
            ->assertTableActionHidden('delete', $this->admin);
    });
});

describe('Suspended User Access', function () {
    it('redirects suspended user on login', function () {
        $user = User::factory()->create(['suspended_at' => now()]);

        $this->actingAs($user)
            ->get('/dashboard')
            ->assertRedirect('/login');
    });

    it('shows error message for suspended user', function () {
        $user = User::factory()->create(['suspended_at' => now()]);

        $response = $this->actingAs($user)->get('/dashboard');

        $response->assertSessionHasErrors(['email']);
    });
});
