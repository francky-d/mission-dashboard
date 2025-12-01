<?php

use App\Enums\ApplicationStatus;
use App\Livewire\Consultant\Application\ApplicationList;
use App\Models\Application;
use App\Models\Mission;
use App\Models\User;
use Livewire\Livewire;

// Access Tests

it('redirects unauthenticated users to login from applications list', function () {
    $this->get(route('consultant.applications.index'))
        ->assertRedirect(route('login'));
});

it('denies access to applications list for non-consultant users', function () {
    $commercial = User::factory()->commercial()->create();

    $this->actingAs($commercial)
        ->get(route('consultant.applications.index'))
        ->assertForbidden();
});

it('allows consultants to access the applications list', function () {
    $consultant = User::factory()->consultant()->create();

    $this->actingAs($consultant)
        ->get(route('consultant.applications.index'))
        ->assertOk()
        ->assertSeeLivewire(ApplicationList::class);
});

// Display Tests

it('displays consultant applications', function () {
    $consultant = User::factory()->consultant()->create();
    $mission = Mission::factory()->create(['title' => 'Test Mission Title']);

    Application::create([
        'mission_id' => $mission->id,
        'consultant_id' => $consultant->id,
        'status' => ApplicationStatus::Pending,
    ]);

    Livewire::actingAs($consultant)
        ->test(ApplicationList::class)
        ->assertSee('Test Mission Title');
});

it('does not display other consultants applications', function () {
    $consultant = User::factory()->consultant()->create();
    $otherConsultant = User::factory()->consultant()->create();
    $mission = Mission::factory()->create(['title' => 'Other Consultant Mission']);

    Application::create([
        'mission_id' => $mission->id,
        'consultant_id' => $otherConsultant->id,
        'status' => ApplicationStatus::Pending,
    ]);

    Livewire::actingAs($consultant)
        ->test(ApplicationList::class)
        ->assertDontSee('Other Consultant Mission');
});

it('displays empty state when no applications exist', function () {
    $consultant = User::factory()->consultant()->create();

    Livewire::actingAs($consultant)
        ->test(ApplicationList::class)
        ->assertSee('Aucune candidature');
});

it('displays application date', function () {
    $consultant = User::factory()->consultant()->create();
    $mission = Mission::factory()->create();

    Application::create([
        'mission_id' => $mission->id,
        'consultant_id' => $consultant->id,
        'status' => ApplicationStatus::Pending,
        'created_at' => now()->setDate(2025, 12, 1)->setTime(10, 30),
    ]);

    Livewire::actingAs($consultant)
        ->test(ApplicationList::class)
        ->assertSee('01/12/2025');
});

it('displays application status', function () {
    $consultant = User::factory()->consultant()->create();
    $mission = Mission::factory()->create();

    Application::create([
        'mission_id' => $mission->id,
        'consultant_id' => $consultant->id,
        'status' => ApplicationStatus::Pending,
    ]);

    Livewire::actingAs($consultant)
        ->test(ApplicationList::class)
        ->assertSee('En attente');
});

it('displays mission details in application', function () {
    $consultant = User::factory()->consultant()->create();
    $commercial = User::factory()->commercial()->create(['name' => 'Commercial Name']);

    $mission = Mission::factory()->create([
        'commercial_id' => $commercial->id,
        'location' => 'Paris',
        'daily_rate' => 500,
    ]);

    Application::create([
        'mission_id' => $mission->id,
        'consultant_id' => $consultant->id,
        'status' => ApplicationStatus::Pending,
    ]);

    Livewire::actingAs($consultant)
        ->test(ApplicationList::class)
        ->assertSee('Paris')
        ->assertSee('500')
        ->assertSee('Commercial Name');
});

it('orders applications by most recent first', function () {
    $consultant = User::factory()->consultant()->create();

    $oldMission = Mission::factory()->create(['title' => 'Old Application Mission']);
    $newMission = Mission::factory()->create(['title' => 'New Application Mission']);

    // Create old application first
    $oldApplication = Application::factory()->create([
        'mission_id' => $oldMission->id,
        'consultant_id' => $consultant->id,
        'status' => ApplicationStatus::Pending,
    ]);

    // Create new application
    $newApplication = Application::factory()->create([
        'mission_id' => $newMission->id,
        'consultant_id' => $consultant->id,
        'status' => ApplicationStatus::Pending,
    ]);

    // Update created_at using DB query to bypass fillable
    \Illuminate\Support\Facades\DB::table('applications')
        ->where('id', $oldApplication->id)
        ->update(['created_at' => now()->subDays(2)]);

    Livewire::actingAs($consultant)
        ->test(ApplicationList::class)
        ->assertSeeInOrder(['New Application Mission', 'Old Application Mission']);
});

// Status Filter Tests

it('displays status tabs with counts', function () {
    $consultant = User::factory()->consultant()->create();
    $mission = Mission::factory()->create();

    Application::create([
        'mission_id' => $mission->id,
        'consultant_id' => $consultant->id,
        'status' => ApplicationStatus::Pending,
    ]);

    Livewire::actingAs($consultant)
        ->test(ApplicationList::class)
        ->assertSee('Toutes')
        ->assertSee('En attente')
        ->assertSee('Consulté')
        ->assertSee('Accepté')
        ->assertSee('Refusé');
});

it('filters applications by pending status', function () {
    $consultant = User::factory()->consultant()->create();

    $pendingMission = Mission::factory()->create(['title' => 'Pending Mission']);
    $acceptedMission = Mission::factory()->create(['title' => 'Accepted Mission']);

    Application::create([
        'mission_id' => $pendingMission->id,
        'consultant_id' => $consultant->id,
        'status' => ApplicationStatus::Pending,
    ]);

    Application::create([
        'mission_id' => $acceptedMission->id,
        'consultant_id' => $consultant->id,
        'status' => ApplicationStatus::Accepted,
    ]);

    Livewire::actingAs($consultant)
        ->test(ApplicationList::class)
        ->set('status', ApplicationStatus::Pending->value)
        ->assertSee('Pending Mission')
        ->assertDontSee('Accepted Mission');
});

it('filters applications by accepted status', function () {
    $consultant = User::factory()->consultant()->create();

    $pendingMission = Mission::factory()->create(['title' => 'Pending Mission']);
    $acceptedMission = Mission::factory()->create(['title' => 'Accepted Mission']);

    Application::create([
        'mission_id' => $pendingMission->id,
        'consultant_id' => $consultant->id,
        'status' => ApplicationStatus::Pending,
    ]);

    Application::create([
        'mission_id' => $acceptedMission->id,
        'consultant_id' => $consultant->id,
        'status' => ApplicationStatus::Accepted,
    ]);

    Livewire::actingAs($consultant)
        ->test(ApplicationList::class)
        ->set('status', ApplicationStatus::Accepted->value)
        ->assertSee('Accepted Mission')
        ->assertDontSee('Pending Mission');
});

it('shows all applications when status filter is cleared', function () {
    $consultant = User::factory()->consultant()->create();

    $pendingMission = Mission::factory()->create(['title' => 'Pending Mission']);
    $acceptedMission = Mission::factory()->create(['title' => 'Accepted Mission']);

    Application::create([
        'mission_id' => $pendingMission->id,
        'consultant_id' => $consultant->id,
        'status' => ApplicationStatus::Pending,
    ]);

    Application::create([
        'mission_id' => $acceptedMission->id,
        'consultant_id' => $consultant->id,
        'status' => ApplicationStatus::Accepted,
    ]);

    Livewire::actingAs($consultant)
        ->test(ApplicationList::class)
        ->set('status', '')
        ->assertSee('Pending Mission')
        ->assertSee('Accepted Mission');
});

it('resets pagination when changing status filter', function () {
    $consultant = User::factory()->consultant()->create();

    Mission::factory(15)->create()->each(function ($mission) use ($consultant) {
        Application::create([
            'mission_id' => $mission->id,
            'consultant_id' => $consultant->id,
            'status' => ApplicationStatus::Pending,
        ]);
    });

    $component = Livewire::actingAs($consultant)
        ->test(ApplicationList::class)
        ->call('gotoPage', 2)
        ->set('status', ApplicationStatus::Pending->value);

    expect($component->get('paginators.page'))->toBe(1);
});

// Pagination Tests

it('paginates applications with 10 per page', function () {
    $consultant = User::factory()->consultant()->create();

    Mission::factory(15)->create()->each(function ($mission) use ($consultant) {
        Application::create([
            'mission_id' => $mission->id,
            'consultant_id' => $consultant->id,
            'status' => ApplicationStatus::Pending,
        ]);
    });

    $component = Livewire::actingAs($consultant)
        ->test(ApplicationList::class);

    $applications = $component->viewData('applications');
    expect($applications->count())->toBe(10);
});
