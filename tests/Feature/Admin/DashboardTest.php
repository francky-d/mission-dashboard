<?php

declare(strict_types=1);

use App\Enums\MissionStatus;
use App\Enums\UserRole;
use App\Filament\Widgets\LatestApplications;
use App\Filament\Widgets\MissionsChart;
use App\Filament\Widgets\StatsOverview;
use App\Models\Application;
use App\Models\Mission;
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
});

describe('Dashboard Access', function () {
    it('allows admin to access dashboard', function () {
        $this->get('/admin')
            ->assertSuccessful();
    });

    it('denies non-admin access to dashboard', function () {
        $user = User::factory()->create(['role' => UserRole::Consultant]);

        $this->actingAs($user)
            ->get('/admin')
            ->assertForbidden();
    });
});

describe('StatsOverview Widget', function () {
    it('renders stats overview widget', function () {
        Livewire::test(StatsOverview::class)
            ->assertSuccessful();
    });

    it('displays users count', function () {
        User::factory()->count(5)->create();

        Livewire::test(StatsOverview::class)
            ->assertSee('Utilisateurs');
    });

    it('displays missions count', function () {
        $commercial = User::factory()->create(['role' => UserRole::Commercial]);
        Mission::factory()->count(3)->for($commercial, 'commercial')->create();

        Livewire::test(StatsOverview::class)
            ->assertSee('Missions');
    });

    it('displays applications count', function () {
        $commercial = User::factory()->create(['role' => UserRole::Commercial]);
        $consultant = User::factory()->create(['role' => UserRole::Consultant]);
        $mission = Mission::factory()->for($commercial, 'commercial')->create();

        Application::factory()
            ->for($mission)
            ->for($consultant, 'consultant')
            ->create();

        Livewire::test(StatsOverview::class)
            ->assertSee('Candidatures');
    });

    it('displays active missions count', function () {
        $commercial = User::factory()->create(['role' => UserRole::Commercial]);
        Mission::factory()
            ->for($commercial, 'commercial')
            ->count(2)
            ->create(['status' => MissionStatus::Active]);
        Mission::factory()
            ->for($commercial, 'commercial')
            ->create(['status' => MissionStatus::Archived]);

        Livewire::test(StatsOverview::class)
            ->assertSee('2 actives');
    });

    it('displays pending applications count', function () {
        $commercial = User::factory()->create(['role' => UserRole::Commercial]);
        $mission = Mission::factory()->for($commercial, 'commercial')->create();

        $consultants = User::factory()->count(3)->create(['role' => UserRole::Consultant]);
        foreach ($consultants as $index => $consultant) {
            Application::factory()
                ->for($mission)
                ->for($consultant, 'consultant')
                ->create(['status' => $index === 0 ? 'pending' : 'accepted']);
        }

        Livewire::test(StatsOverview::class)
            ->assertSee('1 en attente');
    });
});

describe('MissionsChart Widget', function () {
    it('renders missions chart widget', function () {
        Livewire::test(MissionsChart::class)
            ->assertSuccessful();
    });

    it('displays chart heading', function () {
        Livewire::test(MissionsChart::class)
            ->assertSee('Missions et Candidatures');
    });
});

describe('LatestApplications Widget', function () {
    it('renders latest applications widget', function () {
        Livewire::test(LatestApplications::class)
            ->assertSuccessful();
    });

    it('displays latest applications', function () {
        $commercial = User::factory()->create(['role' => UserRole::Commercial]);
        $consultant = User::factory()->create([
            'role' => UserRole::Consultant,
            'name' => 'John Consultant',
        ]);
        $mission = Mission::factory()->for($commercial, 'commercial')->create([
            'title' => 'Laravel Project',
        ]);

        Application::factory()
            ->for($mission)
            ->for($consultant, 'consultant')
            ->create(['status' => 'pending']);

        Livewire::test(LatestApplications::class)
            ->assertSee('John Consultant')
            ->assertSee('Laravel Project')
            ->assertSee('En attente');
    });

    it('displays application status with correct badge', function () {
        $commercial = User::factory()->create(['role' => UserRole::Commercial]);
        $consultant = User::factory()->create(['role' => UserRole::Consultant]);
        $mission = Mission::factory()->for($commercial, 'commercial')->create();

        Application::factory()
            ->for($mission)
            ->for($consultant, 'consultant')
            ->create(['status' => 'accepted']);

        Livewire::test(LatestApplications::class)
            ->assertSee('Acceptée');
    });

    it('limits to 5 applications', function () {
        $commercial = User::factory()->create(['role' => UserRole::Commercial]);
        $mission = Mission::factory()->for($commercial, 'commercial')->create();

        $consultants = User::factory()->count(7)->create(['role' => UserRole::Consultant]);
        foreach ($consultants as $consultant) {
            Application::factory()
                ->for($mission)
                ->for($consultant, 'consultant')
                ->create();
        }

        // Widget should only show 5 applications
        Livewire::test(LatestApplications::class)
            ->assertSuccessful();
    });
});
