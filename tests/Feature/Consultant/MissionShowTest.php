<?php

use App\Livewire\Consultant\Mission\MissionShow;
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

it('displays mission daily rate', function () {
    $consultant = User::factory()->consultant()->create();
    $commercial = User::factory()->commercial()->create();

    $mission = Mission::factory()->active()->create([
        'commercial_id' => $commercial->id,
        'daily_rate' => 600,
    ]);

    Livewire::actingAs($consultant)
        ->test(MissionShow::class, ['mission' => $mission])
        ->assertSee('600');
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
