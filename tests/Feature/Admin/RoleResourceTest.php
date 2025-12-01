<?php

declare(strict_types=1);

use App\Enums\UserRole;
use App\Filament\Resources\Roles\Pages\CreateRole;
use App\Filament\Resources\Roles\Pages\EditRole;
use App\Filament\Resources\Roles\Pages\ListRoles;
use App\Filament\Resources\Roles\RoleResource;
use App\Models\User;
use Filament\Facades\Filament;
use Livewire\Livewire;
use Spatie\Permission\Models\Permission;
use Spatie\Permission\Models\Role;

beforeEach(function () {
    Filament::setCurrentPanel(Filament::getPanel('admin'));

    // Create admin role (Spatie)
    Role::create(['name' => 'Admin']);

    $this->admin = User::factory()->create(['role' => UserRole::Admin]);
    $this->admin->assignRole('Admin');
    $this->actingAs($this->admin);
});

describe('RoleResource Access Control', function () {
    it('denies access to non-admin users', function () {
        $user = User::factory()->create();

        $this->actingAs($user)
            ->get(RoleResource::getUrl('index'))
            ->assertForbidden();
    });

    it('allows access to admin users', function () {
        $this->get(RoleResource::getUrl('index'))
            ->assertSuccessful();
    });
});

describe('RoleResource CRUD', function () {
    it('can list roles', function () {
        $roles = collect([
            Role::create(['name' => 'Commercial']),
            Role::create(['name' => 'Consultant']),
        ]);

        Livewire::test(ListRoles::class)
            ->assertCanSeeTableRecords($roles);
    });

    it('can search roles by name', function () {
        $manager = Role::create(['name' => 'Manager']);
        $supervisor = Role::create(['name' => 'Supervisor']);

        Livewire::test(ListRoles::class)
            ->searchTable('Manager')
            ->assertCanSeeTableRecords([$manager])
            ->assertCanNotSeeTableRecords([$supervisor]);
    });

    it('can create a role', function () {
        Livewire::test(CreateRole::class)
            ->fillForm([
                'name' => 'Manager',
            ])
            ->call('create')
            ->assertHasNoFormErrors();

        $this->assertDatabaseHas('roles', [
            'name' => 'Manager',
        ]);
    });

    it('can create a role with permissions', function () {
        Permission::create(['name' => 'users.view']);

        Livewire::test(CreateRole::class)
            ->fillForm([
                'name' => 'Manager',
                'permissions' => [Permission::where('name', 'users.view')->first()->id],
            ])
            ->call('create')
            ->assertHasNoFormErrors();

        $role = Role::where('name', 'Manager')->first();
        expect($role)->not->toBeNull();
        expect($role->hasPermissionTo('users.view'))->toBeTrue();
    });

    it('validates role name is required', function () {
        Livewire::test(CreateRole::class)
            ->fillForm([
                'name' => '',
            ])
            ->call('create')
            ->assertHasFormErrors(['name' => 'required']);
    });

    it('validates role name is unique', function () {
        Role::create(['name' => 'Manager']);

        Livewire::test(CreateRole::class)
            ->fillForm([
                'name' => 'Manager',
            ])
            ->call('create')
            ->assertHasFormErrors(['name' => 'unique']);
    });

    it('can edit a role', function () {
        $role = Role::create(['name' => 'Manager']);

        Livewire::test(EditRole::class, ['record' => $role->id])
            ->fillForm([
                'name' => 'Supervisor',
            ])
            ->call('save')
            ->assertHasNoFormErrors();

        expect($role->fresh()->name)->toBe('Supervisor');
    });

    it('can update role permissions', function () {
        $role = Role::create(['name' => 'Manager']);
        $permission1 = Permission::create(['name' => 'users.create']);
        $permission2 = Permission::create(['name' => 'users.update']);

        $role->givePermissionTo($permission1);

        Livewire::test(EditRole::class, ['record' => $role->id])
            ->fillForm([
                'name' => 'Manager',
                'permissions' => [$permission2->id],
            ])
            ->call('save')
            ->assertHasNoFormErrors();

        $role->refresh();
        expect($role->hasPermissionTo('users.create'))->toBeFalse();
        expect($role->hasPermissionTo('users.update'))->toBeTrue();
    });

    it('can delete a role', function () {
        $role = Role::create(['name' => 'ToDelete']);

        Livewire::test(EditRole::class, ['record' => $role->id])
            ->callAction('delete');

        $this->assertDatabaseMissing('roles', [
            'name' => 'ToDelete',
        ]);
    });

    it('can bulk delete roles', function () {
        $roles = collect([
            Role::create(['name' => 'Role1']),
            Role::create(['name' => 'Role2']),
        ]);

        Livewire::test(ListRoles::class)
            ->callTableBulkAction('delete', $roles);

        foreach ($roles as $role) {
            $this->assertDatabaseMissing('roles', [
                'id' => $role->id,
            ]);
        }
    });
});
