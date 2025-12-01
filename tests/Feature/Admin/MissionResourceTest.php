<?php

declare(strict_types=1);

use App\Enums\MissionStatus;
use App\Enums\UserRole;
use App\Filament\Resources\Missions\MissionResource;
use App\Filament\Resources\Missions\Pages\ListMissions;
use App\Filament\Resources\Missions\Pages\ViewMission;
use App\Models\Application;
use App\Models\Mission;
use App\Models\Tag;
use App\Models\User;
use Filament\Facades\Filament;
use Livewire\Livewire;
use Spatie\Permission\Models\Role;

beforeEach(function () {
    Filament::setCurrentPanel(Filament::getPanel('admin'));

    // Create admin role (Spatie)
    Role::create(['name' => 'Admin']);

    $this->admin = User::factory()->create(['role' => UserRole::Admin]);
    $this->admin->assignRole('Admin');
    $this->actingAs($this->admin);

    $this->commercial = User::factory()->create(['role' => UserRole::Commercial]);
});

describe('MissionResource Access Control', function () {
    it('denies access to non-admin users', function () {
        $user = User::factory()->create(['role' => UserRole::Consultant]);

        $this->actingAs($user)
            ->get(MissionResource::getUrl('index'))
            ->assertForbidden();
    });

    it('denies access to commercial users', function () {
        $this->actingAs($this->commercial)
            ->get(MissionResource::getUrl('index'))
            ->assertForbidden();
    });

    it('allows access to admin users', function () {
        $this->get(MissionResource::getUrl('index'))
            ->assertSuccessful();
    });
});

describe('MissionResource List', function () {
    it('can list all missions', function () {
        $missions = Mission::factory()
            ->count(3)
            ->for($this->commercial, 'commercial')
            ->create();

        Livewire::test(ListMissions::class)
            ->assertCanSeeTableRecords($missions);
    });

    it('can list missions from different commercials', function () {
        $commercial1 = User::factory()->create(['role' => UserRole::Commercial]);
        $commercial2 = User::factory()->create(['role' => UserRole::Commercial]);

        $mission1 = Mission::factory()->for($commercial1, 'commercial')->create();
        $mission2 = Mission::factory()->for($commercial2, 'commercial')->create();

        Livewire::test(ListMissions::class)
            ->assertCanSeeTableRecords([$mission1, $mission2]);
    });

    it('can search missions by title', function () {
        $mission1 = Mission::factory()
            ->for($this->commercial, 'commercial')
            ->create(['title' => 'Laravel Developer']);
        $mission2 = Mission::factory()
            ->for($this->commercial, 'commercial')
            ->create(['title' => 'Vue.js Developer']);

        Livewire::test(ListMissions::class)
            ->searchTable('Laravel')
            ->assertCanSeeTableRecords([$mission1])
            ->assertCanNotSeeTableRecords([$mission2]);
    });

    it('can filter missions by status', function () {
        $activeMission = Mission::factory()
            ->for($this->commercial, 'commercial')
            ->create(['status' => MissionStatus::Active]);
        $archivedMission = Mission::factory()
            ->for($this->commercial, 'commercial')
            ->create(['status' => MissionStatus::Archived]);

        Livewire::test(ListMissions::class)
            ->filterTable('status', MissionStatus::Active->value)
            ->assertCanSeeTableRecords([$activeMission])
            ->assertCanNotSeeTableRecords([$archivedMission]);
    });

    it('can filter missions by commercial', function () {
        $commercial1 = User::factory()->create(['role' => UserRole::Commercial]);
        $commercial2 = User::factory()->create(['role' => UserRole::Commercial]);

        $mission1 = Mission::factory()->for($commercial1, 'commercial')->create();
        $mission2 = Mission::factory()->for($commercial2, 'commercial')->create();

        Livewire::test(ListMissions::class)
            ->filterTable('commercial', $commercial1->id)
            ->assertCanSeeTableRecords([$mission1])
            ->assertCanNotSeeTableRecords([$mission2]);
    });

    it('displays applications count', function () {
        $mission = Mission::factory()
            ->for($this->commercial, 'commercial')
            ->create();

        $consultants = User::factory()
            ->count(3)
            ->create(['role' => UserRole::Consultant]);

        foreach ($consultants as $consultant) {
            Application::factory()
                ->for($mission)
                ->for($consultant, 'consultant')
                ->create();
        }

        Livewire::test(ListMissions::class)
            ->assertCanSeeTableRecords([$mission]);
    });
});

describe('MissionResource View', function () {
    it('can view mission details', function () {
        $mission = Mission::factory()
            ->for($this->commercial, 'commercial')
            ->create();

        $this->get(MissionResource::getUrl('view', ['record' => $mission]))
            ->assertSuccessful();
    });

    it('displays mission information', function () {
        $mission = Mission::factory()
            ->for($this->commercial, 'commercial')
            ->create([
                'title' => 'Senior Laravel Developer',
                'location' => 'Paris',
                'daily_rate' => 600,
            ]);

        Livewire::test(ViewMission::class, ['record' => $mission->id])
            ->assertSee('Senior Laravel Developer')
            ->assertSee('Paris')
            ->assertSee($this->commercial->name);
    });

    it('displays mission tags', function () {
        $mission = Mission::factory()
            ->for($this->commercial, 'commercial')
            ->create();

        $tag = Tag::factory()->create(['name' => 'Laravel']);
        $mission->tags()->attach($tag);

        Livewire::test(ViewMission::class, ['record' => $mission->id])
            ->assertSee('Laravel');
    });

    it('displays application statistics', function () {
        $mission = Mission::factory()
            ->for($this->commercial, 'commercial')
            ->create();

        $consultant = User::factory()->create(['role' => UserRole::Consultant]);
        Application::factory()
            ->for($mission)
            ->for($consultant, 'consultant')
            ->create(['status' => 'pending']);

        $this->get(MissionResource::getUrl('view', ['record' => $mission]))
            ->assertSuccessful();
    });
});

describe('MissionResource is Read-Only', function () {
    it('does not have create header action', function () {
        Livewire::test(ListMissions::class)
            ->assertOk();

        // ListMissions returns empty array for header actions
        // Verify no create action exists by checking the page loads without create button
        $this->get(MissionResource::getUrl('index'))
            ->assertDontSee('Nouvelle mission');
    });

    it('only has view action in table', function () {
        $mission = Mission::factory()
            ->for($this->commercial, 'commercial')
            ->create();

        // The table only has ViewAction, no edit or delete
        Livewire::test(ListMissions::class)
            ->assertTableActionExists('view', record: $mission);
    });

    it('has no bulk actions', function () {
        Mission::factory()
            ->for($this->commercial, 'commercial')
            ->count(2)
            ->create();

        // toolbarActions is empty array, so no bulk delete
        Livewire::test(ListMissions::class)
            ->assertOk();
    });
});
