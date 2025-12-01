<?php

namespace Database\Seeders;

use Illuminate\Database\Seeder;
use Spatie\Permission\Models\Permission;
use Spatie\Permission\Models\Role;

class RoleAndPermissionSeeder extends Seeder
{
    /**
     * Run the database seeds.
     */
    public function run(): void
    {
        // Reset cached roles and permissions
        app()[\Spatie\Permission\PermissionRegistrar::class]->forgetCachedPermissions();

        // Create permissions
        $permissions = [
            // Users
            'users.view',
            'users.create',
            'users.update',
            'users.delete',
            'users.suspend',

            // Missions
            'missions.view',
            'missions.create',
            'missions.update',
            'missions.delete',
            'missions.assign',

            // Tags
            'tags.view',
            'tags.create',
            'tags.update',
            'tags.delete',

            // Roles
            'roles.view',
            'roles.create',
            'roles.update',
            'roles.delete',

            // Dashboard
            'dashboard.view',
            'dashboard.stats',
        ];

        foreach ($permissions as $permission) {
            Permission::firstOrCreate(['name' => $permission]);
        }

        // Create roles and assign permissions
        $adminRole = Role::firstOrCreate(['name' => 'Admin']);
        $adminRole->syncPermissions(Permission::all());

        $commercialRole = Role::firstOrCreate(['name' => 'Commercial']);
        $commercialRole->syncPermissions([
            'missions.view',
            'missions.create',
            'missions.update',
            'missions.assign',
            'tags.view',
            'dashboard.view',
        ]);

        $consultantRole = Role::firstOrCreate(['name' => 'Consultant']);
        $consultantRole->syncPermissions([
            'missions.view',
            'tags.view',
            'dashboard.view',
        ]);
    }
}
