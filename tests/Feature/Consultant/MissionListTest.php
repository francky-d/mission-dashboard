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
    ]);

    Livewire::actingAs($consultant)
        ->test(MissionList::class)
        ->assertSee('Test Mission')
        ->assertSee('Paris');
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

// Tag Filter Tests

it('displays available tags for filtering', function () {
    $consultant = User::factory()->consultant()->create();
    $commercial = User::factory()->commercial()->create();

    $tag = Tag::factory()->create(['name' => 'PHP']);
    $mission = Mission::factory()->active()->create([
        'commercial_id' => $commercial->id,
    ]);
    $mission->tags()->attach($tag);

    Livewire::actingAs($consultant)
        ->test(MissionList::class)
        ->assertSee('Filtrer par compétences')
        ->assertSee('PHP');
});

it('only shows tags that are used by active missions', function () {
    $consultant = User::factory()->consultant()->create();
    $commercial = User::factory()->commercial()->create();

    $activeTag = Tag::factory()->create(['name' => 'ActiveTag']);
    $archivedTag = Tag::factory()->create(['name' => 'ArchivedTag']);
    $unusedTag = Tag::factory()->create(['name' => 'UnusedTag']);

    $activeMission = Mission::factory()->active()->create([
        'commercial_id' => $commercial->id,
    ]);
    $activeMission->tags()->attach($activeTag);

    $archivedMission = Mission::factory()->archived()->create([
        'commercial_id' => $commercial->id,
    ]);
    $archivedMission->tags()->attach($archivedTag);

    Livewire::actingAs($consultant)
        ->test(MissionList::class)
        ->assertSee('ActiveTag')
        ->assertDontSee('ArchivedTag')
        ->assertDontSee('UnusedTag');
});

it('filters missions by selected tag', function () {
    $consultant = User::factory()->consultant()->create();
    $commercial = User::factory()->commercial()->create();

    $phpTag = Tag::factory()->create(['name' => 'PHP']);
    $jsTag = Tag::factory()->create(['name' => 'JavaScript']);

    $phpMission = Mission::factory()->active()->create([
        'commercial_id' => $commercial->id,
        'title' => 'PHP Developer Mission',
    ]);
    $phpMission->tags()->attach($phpTag);

    $jsMission = Mission::factory()->active()->create([
        'commercial_id' => $commercial->id,
        'title' => 'JavaScript Developer Mission',
    ]);
    $jsMission->tags()->attach($jsTag);

    Livewire::actingAs($consultant)
        ->test(MissionList::class)
        ->set('selectedTags', [$phpTag->id])
        ->assertSee('PHP Developer Mission')
        ->assertDontSee('JavaScript Developer Mission');
});

it('can toggle tag selection', function () {
    $consultant = User::factory()->consultant()->create();
    $commercial = User::factory()->commercial()->create();

    $tag = Tag::factory()->create(['name' => 'PHP']);
    $mission = Mission::factory()->active()->create([
        'commercial_id' => $commercial->id,
    ]);
    $mission->tags()->attach($tag);

    Livewire::actingAs($consultant)
        ->test(MissionList::class)
        ->assertSet('selectedTags', [])
        ->call('toggleTag', $tag->id)
        ->assertSet('selectedTags', [$tag->id])
        ->call('toggleTag', $tag->id)
        ->assertSet('selectedTags', []);
});

it('can filter by multiple tags', function () {
    $consultant = User::factory()->consultant()->create();
    $commercial = User::factory()->commercial()->create();

    $phpTag = Tag::factory()->create(['name' => 'PHP']);
    $laravelTag = Tag::factory()->create(['name' => 'Laravel']);
    $jsTag = Tag::factory()->create(['name' => 'JavaScript']);

    $phpLaravelMission = Mission::factory()->active()->create([
        'commercial_id' => $commercial->id,
        'title' => 'PHP Laravel Mission',
    ]);
    $phpLaravelMission->tags()->attach([$phpTag->id, $laravelTag->id]);

    $phpOnlyMission = Mission::factory()->active()->create([
        'commercial_id' => $commercial->id,
        'title' => 'PHP Only Mission',
    ]);
    $phpOnlyMission->tags()->attach($phpTag);

    $jsMission = Mission::factory()->active()->create([
        'commercial_id' => $commercial->id,
        'title' => 'JavaScript Mission',
    ]);
    $jsMission->tags()->attach($jsTag);

    // Filtering by PHP should show both PHP missions
    Livewire::actingAs($consultant)
        ->test(MissionList::class)
        ->set('selectedTags', [$phpTag->id])
        ->assertSee('PHP Laravel Mission')
        ->assertSee('PHP Only Mission')
        ->assertDontSee('JavaScript Mission');
});

it('resets pagination when toggling tags', function () {
    $consultant = User::factory()->consultant()->create();
    $commercial = User::factory()->commercial()->create();

    $tag = Tag::factory()->create(['name' => 'PHP']);

    Mission::factory(15)->active()->create([
        'commercial_id' => $commercial->id,
    ])->each(fn ($mission) => $mission->tags()->attach($tag));

    $component = Livewire::actingAs($consultant)
        ->test(MissionList::class)
        ->call('gotoPage', 2)
        ->call('toggleTag', $tag->id);

    expect($component->get('paginators.page'))->toBe(1);
});

it('can clear all filters', function () {
    $consultant = User::factory()->consultant()->create();
    $commercial = User::factory()->commercial()->create();

    $tag = Tag::factory()->create(['name' => 'PHP']);
    $mission = Mission::factory()->active()->create([
        'commercial_id' => $commercial->id,
    ]);
    $mission->tags()->attach($tag);

    Livewire::actingAs($consultant)
        ->test(MissionList::class)
        ->set('search', 'test')
        ->set('selectedTags', [$tag->id])
        ->call('clearFilters')
        ->assertSet('search', '')
        ->assertSet('selectedTags', []);
});

it('can combine search and tag filters', function () {
    $consultant = User::factory()->consultant()->create();
    $commercial = User::factory()->commercial()->create();

    $phpTag = Tag::factory()->create(['name' => 'PHP']);

    $seniorPhpMission = Mission::factory()->active()->create([
        'commercial_id' => $commercial->id,
        'title' => 'Senior PHP Developer',
    ]);
    $seniorPhpMission->tags()->attach($phpTag);

    $juniorPhpMission = Mission::factory()->active()->create([
        'commercial_id' => $commercial->id,
        'title' => 'Junior PHP Developer',
    ]);
    $juniorPhpMission->tags()->attach($phpTag);

    $seniorJsMission = Mission::factory()->active()->create([
        'commercial_id' => $commercial->id,
        'title' => 'Senior JavaScript Developer',
    ]);

    Livewire::actingAs($consultant)
        ->test(MissionList::class)
        ->set('search', 'Senior')
        ->set('selectedTags', [$phpTag->id])
        ->assertSee('Senior PHP Developer')
        ->assertDontSee('Junior PHP Developer')
        ->assertDontSee('Senior JavaScript Developer');
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
