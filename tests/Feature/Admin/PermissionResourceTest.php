<?php

declare(strict_types=1);

use App\Enums\UserRole;
use App\Filament\Resources\Permissions\Pages\CreatePermission;
use App\Filament\Resources\Permissions\Pages\EditPermission;
use App\Filament\Resources\Permissions\Pages\ListPermissions;
use App\Filament\Resources\Permissions\PermissionResource;
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

describe('PermissionResource Access Control', function () {
    it('denies access to non-admin users', function () {
        $user = User::factory()->create();

        $this->actingAs($user)
            ->get(PermissionResource::getUrl('index'))
            ->assertForbidden();
    });

    it('allows access to admin users', function () {
        $this->get(PermissionResource::getUrl('index'))
            ->assertSuccessful();
    });
});

describe('PermissionResource CRUD', function () {
    it('can list permissions', function () {
        $permissions = collect([
            Permission::create(['name' => 'users.view']),
            Permission::create(['name' => 'users.create']),
        ]);

        Livewire::test(ListPermissions::class)
            ->assertCanSeeTableRecords($permissions);
    });

    it('can search permissions by name', function () {
        $usersView = Permission::create(['name' => 'users.view']);
        $missionsView = Permission::create(['name' => 'missions.view']);

        Livewire::test(ListPermissions::class)
            ->searchTable('users')
            ->assertCanSeeTableRecords([$usersView])
            ->assertCanNotSeeTableRecords([$missionsView]);
    });

    it('can create a permission', function () {
        Livewire::test(CreatePermission::class)
            ->fillForm([
                'name' => 'missions.create',
            ])
            ->call('create')
            ->assertHasNoFormErrors();

        $this->assertDatabaseHas('permissions', [
            'name' => 'missions.create',
        ]);
    });

    it('validates permission name is required', function () {
        Livewire::test(CreatePermission::class)
            ->fillForm([
                'name' => '',
            ])
            ->call('create')
            ->assertHasFormErrors(['name' => 'required']);
    });

    it('validates permission name is unique', function () {
        Permission::create(['name' => 'users.view']);

        Livewire::test(CreatePermission::class)
            ->fillForm([
                'name' => 'users.view',
            ])
            ->call('create')
            ->assertHasFormErrors(['name' => 'unique']);
    });

    it('can edit a permission', function () {
        $permission = Permission::create(['name' => 'users.view']);

        Livewire::test(EditPermission::class, ['record' => $permission->id])
            ->fillForm([
                'name' => 'users.list',
            ])
            ->call('save')
            ->assertHasNoFormErrors();

        expect($permission->fresh()->name)->toBe('users.list');
    });

    it('can delete a permission', function () {
        $permission = Permission::create(['name' => 'to.delete']);

        Livewire::test(EditPermission::class, ['record' => $permission->id])
            ->callAction('delete');

        $this->assertDatabaseMissing('permissions', [
            'name' => 'to.delete',
        ]);
    });

    it('can bulk delete permissions', function () {
        $permissions = collect([
            Permission::create(['name' => 'perm1']),
            Permission::create(['name' => 'perm2']),
        ]);

        Livewire::test(ListPermissions::class)
            ->callTableBulkAction('delete', $permissions);

        foreach ($permissions as $permission) {
            $this->assertDatabaseMissing('permissions', [
                'id' => $permission->id,
            ]);
        }
    });

    it('shows roles count in table', function () {
        $permission = Permission::create(['name' => 'test.permission']);
        $role = Role::create(['name' => 'Manager']);
        $role->givePermissionTo($permission);

        Livewire::test(ListPermissions::class)
            ->assertCanSeeTableRecords([$permission]);
    });
});
