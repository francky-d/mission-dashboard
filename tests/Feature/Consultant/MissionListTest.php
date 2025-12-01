<?php

use App\Livewire\Consultant\Mission\MissionList;
use App\Models\Mission;
use App\Models\Tag;
use App\Models\User;
use Livewire\Livewire;

// Access Tests

it('redirects unauthenticated users to login from missions list', function () {
    $this->get(route('consultant.missions.index'))
        ->assertRedirect(route('login'));
});

it('denies access to missions list for non-consultant users', function () {
    $commercial = User::factory()->commercial()->create();

    $this->actingAs($commercial)
        ->get(route('consultant.missions.index'))
        ->assertForbidden();
});

it('allows consultants to access the missions list', function () {
    $consultant = User::factory()->consultant()->create();

    $this->actingAs($consultant)
        ->get(route('consultant.missions.index'))
        ->assertOk()
        ->assertSeeLivewire(MissionList::class);
});

// Display Tests

it('displays active missions', function () {
    $consultant = User::factory()->consultant()->create();
    $commercial = User::factory()->commercial()->create();

    $activeMission = Mission::factory()->active()->create([
        'commercial_id' => $commercial->id,
        'title' => 'Active Mission Title',
    ]);

    Livewire::actingAs($consultant)
        ->test(MissionList::class)
        ->assertSee('Active Mission Title');
});

it('does not display archived missions', function () {
    $consultant = User::factory()->consultant()->create();
    $commercial = User::factory()->commercial()->create();

    $archivedMission = Mission::factory()->archived()->create([
        'commercial_id' => $commercial->id,
        'title' => 'Archived Mission Title',
    ]);

    Livewire::actingAs($consultant)
        ->test(MissionList::class)
        ->assertDontSee('Archived Mission Title');
});

it('displays mission details correctly', function () {
    $consultant = User::factory()->consultant()->create();
    $commercial = User::factory()->commercial()->create();

    $mission = Mission::factory()->active()->create([
        'commercial_id' => $commercial->id,
        'title' => 'Test Mission',
        'location' => 'Paris',
        'daily_rate' => 500,
    ]);

    Livewire::actingAs($consultant)
        ->test(MissionList::class)
        ->assertSee('Test Mission')
        ->assertSee('Paris')
        ->assertSee('500');
});

it('displays mission tags', function () {
    $consultant = User::factory()->consultant()->create();
    $commercial = User::factory()->commercial()->create();

    $tag = Tag::factory()->create(['name' => 'Laravel']);
    $mission = Mission::factory()->active()->create([
        'commercial_id' => $commercial->id,
    ]);
    $mission->tags()->attach($tag);

    Livewire::actingAs($consultant)
        ->test(MissionList::class)
        ->assertSee('Laravel');
});

it('displays empty state when no missions exist', function () {
    $consultant = User::factory()->consultant()->create();

    Livewire::actingAs($consultant)
        ->test(MissionList::class)
        ->assertSee('Aucune mission disponible');
});

it('orders missions by most recent first', function () {
    $consultant = User::factory()->consultant()->create();
    $commercial = User::factory()->commercial()->create();

    $oldMission = Mission::factory()->active()->create([
        'commercial_id' => $commercial->id,
        'title' => 'Old Mission',
        'created_at' => now()->subDays(2),
    ]);

    $newMission = Mission::factory()->active()->create([
        'commercial_id' => $commercial->id,
        'title' => 'New Mission',
        'created_at' => now(),
    ]);

    Livewire::actingAs($consultant)
        ->test(MissionList::class)
        ->assertSeeInOrder(['New Mission', 'Old Mission']);
});

// Search Tests

it('filters missions by title', function () {
    $consultant = User::factory()->consultant()->create();
    $commercial = User::factory()->commercial()->create();

    Mission::factory()->active()->create([
        'commercial_id' => $commercial->id,
        'title' => 'Laravel Developer',
    ]);

    Mission::factory()->active()->create([
        'commercial_id' => $commercial->id,
        'title' => 'React Developer',
    ]);

    Livewire::actingAs($consultant)
        ->test(MissionList::class)
        ->set('search', 'Laravel')
        ->assertSee('Laravel Developer')
        ->assertDontSee('React Developer');
});

it('filters missions by description', function () {
    $consultant = User::factory()->consultant()->create();
    $commercial = User::factory()->commercial()->create();

    Mission::factory()->active()->create([
        'commercial_id' => $commercial->id,
        'title' => 'Developer Position',
        'description' => 'Working with PHP and Laravel framework',
    ]);

    Mission::factory()->active()->create([
        'commercial_id' => $commercial->id,
        'title' => 'Frontend Position',
        'description' => 'Working with JavaScript and Vue.js',
    ]);

    Livewire::actingAs($consultant)
        ->test(MissionList::class)
        ->set('search', 'PHP')
        ->assertSee('Developer Position')
        ->assertDontSee('Frontend Position');
});

it('filters missions by location', function () {
    $consultant = User::factory()->consultant()->create();
    $commercial = User::factory()->commercial()->create();

    Mission::factory()->active()->create([
        'commercial_id' => $commercial->id,
        'title' => 'Mission Paris',
        'location' => 'Paris',
    ]);

    Mission::factory()->active()->create([
        'commercial_id' => $commercial->id,
        'title' => 'Mission Lyon',
        'location' => 'Lyon',
    ]);

    Livewire::actingAs($consultant)
        ->test(MissionList::class)
        ->set('search', 'Paris')
        ->assertSee('Mission Paris')
        ->assertDontSee('Mission Lyon');
});

it('resets pagination when searching', function () {
    $consultant = User::factory()->consultant()->create();
    $commercial = User::factory()->commercial()->create();

    Mission::factory(15)->active()->create([
        'commercial_id' => $commercial->id,
    ]);

    $component = Livewire::actingAs($consultant)
        ->test(MissionList::class)
        ->call('gotoPage', 2)
        ->set('search', 'test');

    expect($component->get('paginators.page'))->toBe(1);
});

// Pagination Tests

it('paginates missions with 10 per page', function () {
    $consultant = User::factory()->consultant()->create();
    $commercial = User::factory()->commercial()->create();

    Mission::factory(15)->active()->create([
        'commercial_id' => $commercial->id,
    ]);

    $component = Livewire::actingAs($consultant)
        ->test(MissionList::class);

    $missions = $component->viewData('missions');
    expect($missions->count())->toBe(10);
});
