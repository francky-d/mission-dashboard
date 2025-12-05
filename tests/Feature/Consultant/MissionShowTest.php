<?php

use App\Enums\ApplicationStatus;
use App\Livewire\Consultant\Mission\MissionShow;
use App\Models\Application;
use App\Models\Mission;
use App\Models\Tag;
use App\Models\User;
use Livewire\Livewire;

// Access Tests

it('redirects unauthenticated users to login from mission show', function () {
    $mission = Mission::factory()->create();

    $this->get(route('consultant.missions.show', $mission))
        ->assertRedirect(route('login'));
});

it('denies access to mission show for non-consultant users', function () {
    $commercial = User::factory()->commercial()->create();
    $mission = Mission::factory()->create();

    $this->actingAs($commercial)
        ->get(route('consultant.missions.show', $mission))
        ->assertForbidden();
});

it('allows consultants to access the mission show page', function () {
    $consultant = User::factory()->consultant()->create();
    $mission = Mission::factory()->create();

    $this->actingAs($consultant)
        ->get(route('consultant.missions.show', $mission))
        ->assertOk()
        ->assertSeeLivewire(MissionShow::class);
});

// Display Tests

it('displays mission title', function () {
    $consultant = User::factory()->consultant()->create();
    $commercial = User::factory()->commercial()->create();

    $mission = Mission::factory()->active()->create([
        'commercial_id' => $commercial->id,
        'title' => 'Senior Laravel Developer',
    ]);

    Livewire::actingAs($consultant)
        ->test(MissionShow::class, ['mission' => $mission])
        ->assertSee('Senior Laravel Developer');
});

it('displays mission description', function () {
    $consultant = User::factory()->consultant()->create();
    $commercial = User::factory()->commercial()->create();

    $mission = Mission::factory()->active()->create([
        'commercial_id' => $commercial->id,
        'description' => 'This is a detailed mission description with important requirements.',
    ]);

    Livewire::actingAs($consultant)
        ->test(MissionShow::class, ['mission' => $mission])
        ->assertSee('This is a detailed mission description with important requirements.');
});

it('displays mission location', function () {
    $consultant = User::factory()->consultant()->create();
    $commercial = User::factory()->commercial()->create();

    $mission = Mission::factory()->active()->create([
        'commercial_id' => $commercial->id,
        'location' => 'Paris, France',
    ]);

    Livewire::actingAs($consultant)
        ->test(MissionShow::class, ['mission' => $mission])
        ->assertSee('Paris, France');
});

it('displays mission tags', function () {
    $consultant = User::factory()->consultant()->create();
    $commercial = User::factory()->commercial()->create();

    $mission = Mission::factory()->active()->create([
        'commercial_id' => $commercial->id,
    ]);

    $tags = Tag::factory()->count(2)->sequence(
        ['name' => 'PHP'],
        ['name' => 'Laravel'],
    )->create();
    $mission->tags()->attach($tags);

    Livewire::actingAs($consultant)
        ->test(MissionShow::class, ['mission' => $mission])
        ->assertSee('PHP')
        ->assertSee('Laravel');
});

it('displays commercial name', function () {
    $consultant = User::factory()->consultant()->create();
    $commercial = User::factory()->commercial()->create(['name' => 'Jean Dupont']);

    $mission = Mission::factory()->active()->create([
        'commercial_id' => $commercial->id,
    ]);

    Livewire::actingAs($consultant)
        ->test(MissionShow::class, ['mission' => $mission])
        ->assertSee('Jean Dupont');
});

it('displays mission status', function () {
    $consultant = User::factory()->consultant()->create();
    $commercial = User::factory()->commercial()->create();

    $mission = Mission::factory()->active()->create([
        'commercial_id' => $commercial->id,
    ]);

    Livewire::actingAs($consultant)
        ->test(MissionShow::class, ['mission' => $mission])
        ->assertSee('Active');
});

it('displays back to list link', function () {
    $consultant = User::factory()->consultant()->create();
    $mission = Mission::factory()->create();

    Livewire::actingAs($consultant)
        ->test(MissionShow::class, ['mission' => $mission])
        ->assertSee('Retour aux missions');
});

// Application Tests

it('displays apply button when consultant has not applied', function () {
    $consultant = User::factory()->consultant()->create();
    $mission = Mission::factory()->create();

    Livewire::actingAs($consultant)
        ->test(MissionShow::class, ['mission' => $mission])
        ->assertSee('Postuler à cette mission');
});

it('can submit application to a mission', function () {
    $consultant = User::factory()->consultant()->create();
    $mission = Mission::factory()->create();

    Livewire::actingAs($consultant)
        ->test(MissionShow::class, ['mission' => $mission])
        ->call('apply')
        ->assertDispatched('application-submitted');

    $this->assertDatabaseHas('applications', [
        'mission_id' => $mission->id,
        'consultant_id' => $consultant->id,
        'status' => ApplicationStatus::Pending->value,
    ]);
});

it('shows application status after applying', function () {
    $consultant = User::factory()->consultant()->create();
    $mission = Mission::factory()->create();

    Livewire::actingAs($consultant)
        ->test(MissionShow::class, ['mission' => $mission])
        ->call('apply')
        ->assertSee('Candidature envoyée')
        ->assertDontSee('Postuler à cette mission');
});

it('prevents duplicate applications', function () {
    $consultant = User::factory()->consultant()->create();
    $mission = Mission::factory()->create();

    // Create first application
    Application::create([
        'mission_id' => $mission->id,
        'consultant_id' => $consultant->id,
        'status' => ApplicationStatus::Pending,
    ]);

    // Try to apply again
    Livewire::actingAs($consultant)
        ->test(MissionShow::class, ['mission' => $mission])
        ->call('apply');

    // Should still only have one application
    expect(Application::where('mission_id', $mission->id)
        ->where('consultant_id', $consultant->id)
        ->count())->toBe(1);
});

it('displays existing application status', function () {
    $consultant = User::factory()->consultant()->create();
    $mission = Mission::factory()->create();

    Application::create([
        'mission_id' => $mission->id,
        'consultant_id' => $consultant->id,
        'status' => ApplicationStatus::Pending,
    ]);

    Livewire::actingAs($consultant)
        ->test(MissionShow::class, ['mission' => $mission])
        ->assertSee('Candidature envoyée')
        ->assertSee('En attente')
        ->assertDontSee('Postuler à cette mission');
});

it('displays viewed application status', function () {
    $consultant = User::factory()->consultant()->create();
    $mission = Mission::factory()->create();

    Application::create([
        'mission_id' => $mission->id,
        'consultant_id' => $consultant->id,
        'status' => ApplicationStatus::Viewed,
    ]);

    Livewire::actingAs($consultant)
        ->test(MissionShow::class, ['mission' => $mission])
        ->assertSee('Consulté');
});

it('displays accepted application status', function () {
    $consultant = User::factory()->consultant()->create();
    $mission = Mission::factory()->create();

    Application::create([
        'mission_id' => $mission->id,
        'consultant_id' => $consultant->id,
        'status' => ApplicationStatus::Accepted,
    ]);

    Livewire::actingAs($consultant)
        ->test(MissionShow::class, ['mission' => $mission])
        ->assertSee('Accepté');
});

it('displays rejected application status', function () {
    $consultant = User::factory()->consultant()->create();
    $mission = Mission::factory()->create();

    Application::create([
        'mission_id' => $mission->id,
        'consultant_id' => $consultant->id,
        'status' => ApplicationStatus::Rejected,
    ]);

    Livewire::actingAs($consultant)
        ->test(MissionShow::class, ['mission' => $mission])
        ->assertSee('Refusé');
});

// Withdraw Application Tests

it('can withdraw a pending application', function () {
    $consultant = User::factory()->consultant()->create();
    $mission = Mission::factory()->create();

    $application = Application::create([
        'mission_id' => $mission->id,
        'consultant_id' => $consultant->id,
        'status' => ApplicationStatus::Pending,
    ]);

    Livewire::actingAs($consultant)
        ->test(MissionShow::class, ['mission' => $mission])
        ->assertSee('Retirer')
        ->call('withdraw')
        ->assertDispatched('application-withdrawn');

    expect(Application::find($application->id))->toBeNull();
});

it('displays withdraw button only for pending applications', function () {
    $consultant = User::factory()->consultant()->create();
    $mission = Mission::factory()->create();

    Application::create([
        'mission_id' => $mission->id,
        'consultant_id' => $consultant->id,
        'status' => ApplicationStatus::Pending,
    ]);

    Livewire::actingAs($consultant)
        ->test(MissionShow::class, ['mission' => $mission])
        ->assertSee('Retirer');
});

it('does not display withdraw button for viewed applications', function () {
    $consultant = User::factory()->consultant()->create();
    $mission = Mission::factory()->create();

    Application::create([
        'mission_id' => $mission->id,
        'consultant_id' => $consultant->id,
        'status' => ApplicationStatus::Viewed,
    ]);

    Livewire::actingAs($consultant)
        ->test(MissionShow::class, ['mission' => $mission])
        ->assertDontSee('Retirer');
});

it('does not display withdraw button for accepted applications', function () {
    $consultant = User::factory()->consultant()->create();
    $mission = Mission::factory()->create();

    Application::create([
        'mission_id' => $mission->id,
        'consultant_id' => $consultant->id,
        'status' => ApplicationStatus::Accepted,
    ]);

    Livewire::actingAs($consultant)
        ->test(MissionShow::class, ['mission' => $mission])
        ->assertDontSee('Retirer');
});

it('cannot withdraw a non-pending application', function () {
    $consultant = User::factory()->consultant()->create();
    $mission = Mission::factory()->create();

    $application = Application::create([
        'mission_id' => $mission->id,
        'consultant_id' => $consultant->id,
        'status' => ApplicationStatus::Viewed,
    ]);

    Livewire::actingAs($consultant)
        ->test(MissionShow::class, ['mission' => $mission])
        ->call('withdraw');

    // Application should still exist
    expect(Application::find($application->id))->not->toBeNull();
});

it('can apply again after withdrawing', function () {
    $consultant = User::factory()->consultant()->create();
    $mission = Mission::factory()->create();

    Application::create([
        'mission_id' => $mission->id,
        'consultant_id' => $consultant->id,
        'status' => ApplicationStatus::Pending,
    ]);

    Livewire::actingAs($consultant)
        ->test(MissionShow::class, ['mission' => $mission])
        ->call('withdraw')
        ->assertSee('Postuler à cette mission')
        ->call('apply')
        ->assertSee('Candidature envoyée');

    expect(Application::where('mission_id', $mission->id)
        ->where('consultant_id', $consultant->id)
        ->count())->toBe(1);
});
