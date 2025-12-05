<?php

use App\Enums\ApplicationStatus;
use App\Enums\MissionStatus;
use App\Livewire\Commercial\Mission\MissionForm;
use App\Livewire\Commercial\Mission\MissionList;
use App\Livewire\Commercial\Mission\MissionShow;
use App\Models\Application;
use App\Models\Mission;
use App\Models\Tag;
use App\Models\User;
use App\Notifications\ApplicationStatusChanged;
use Illuminate\Support\Facades\Notification;
use Livewire\Livewire;

beforeEach(function () {
    $this->commercial = User::factory()->commercial()->create();
    $this->consultant = User::factory()->consultant()->create();
});

describe('MissionList Component', function () {
    it('redirects unauthenticated users to login from missions list', function () {
        $this->get(route('commercial.missions.index'))
            ->assertRedirect(route('login'));
    });

    it('denies access to missions list for non-commercial users', function () {
        $this->actingAs($this->consultant)
            ->get(route('commercial.missions.index'))
            ->assertForbidden();
    });

    it('allows commercials to access the missions list', function () {
        $this->actingAs($this->commercial)
            ->get(route('commercial.missions.index'))
            ->assertOk()
            ->assertSeeLivewire(MissionList::class);
    });

    it('displays commercial own missions', function () {
        $mission = Mission::factory()->create([
            'commercial_id' => $this->commercial->id,
            'title' => 'My Mission Title',
        ]);

        Livewire::actingAs($this->commercial)
            ->test(MissionList::class)
            ->assertSee('My Mission Title');
    });

    it('does not display other commercials missions', function () {
        $otherCommercial = User::factory()->commercial()->create();
        $mission = Mission::factory()->create([
            'commercial_id' => $otherCommercial->id,
            'title' => 'Other Commercial Mission',
        ]);

        Livewire::actingAs($this->commercial)
            ->test(MissionList::class)
            ->assertDontSee('Other Commercial Mission');
    });

    it('filters missions by search term', function () {
        Mission::factory()->create([
            'commercial_id' => $this->commercial->id,
            'title' => 'PHP Developer Needed',
        ]);
        Mission::factory()->create([
            'commercial_id' => $this->commercial->id,
            'title' => 'Java Engineer',
        ]);

        Livewire::actingAs($this->commercial)
            ->test(MissionList::class)
            ->set('search', 'PHP')
            ->assertSee('PHP Developer Needed')
            ->assertDontSee('Java Engineer');
    });

    it('filters missions by status', function () {
        Mission::factory()->create([
            'commercial_id' => $this->commercial->id,
            'title' => 'Active Mission',
            'status' => MissionStatus::Active,
        ]);
        Mission::factory()->create([
            'commercial_id' => $this->commercial->id,
            'title' => 'Archived Mission',
            'status' => MissionStatus::Archived,
        ]);

        Livewire::actingAs($this->commercial)
            ->test(MissionList::class)
            ->set('status', MissionStatus::Active->value)
            ->assertSee('Active Mission')
            ->assertDontSee('Archived Mission');
    });

    it('can archive a mission', function () {
        $mission = Mission::factory()->create([
            'commercial_id' => $this->commercial->id,
            'status' => MissionStatus::Active,
        ]);

        Livewire::actingAs($this->commercial)
            ->test(MissionList::class)
            ->call('archive', $mission->id);

        expect($mission->fresh()->status)->toBe(MissionStatus::Archived);
    });

    it('can activate an archived mission', function () {
        $mission = Mission::factory()->create([
            'commercial_id' => $this->commercial->id,
            'status' => MissionStatus::Archived,
        ]);

        Livewire::actingAs($this->commercial)
            ->test(MissionList::class)
            ->call('activate', $mission->id);

        expect($mission->fresh()->status)->toBe(MissionStatus::Active);
    });

    it('cannot archive another commercials mission', function () {
        $otherCommercial = User::factory()->commercial()->create();
        $mission = Mission::factory()->create([
            'commercial_id' => $otherCommercial->id,
            'status' => MissionStatus::Active,
        ]);

        Livewire::actingAs($this->commercial)
            ->test(MissionList::class)
            ->call('archive', $mission->id);

        expect($mission->fresh()->status)->toBe(MissionStatus::Active);
    });

    it('displays applications count for each mission', function () {
        $mission = Mission::factory()->create([
            'commercial_id' => $this->commercial->id,
        ]);
        Application::factory()->count(3)->create(['mission_id' => $mission->id]);

        Livewire::actingAs($this->commercial)
            ->test(MissionList::class)
            ->assertSee('3');
    });

    it('paginates missions with 10 per page', function () {
        Mission::factory()->count(15)->create([
            'commercial_id' => $this->commercial->id,
        ]);

        Livewire::actingAs($this->commercial)
            ->test(MissionList::class)
            ->assertViewHas('missions', fn ($missions) => $missions->count() === 10);
    });
});

describe('MissionForm Component', function () {
    it('can create a new mission', function () {
        $tag = Tag::factory()->create();

        Livewire::actingAs($this->commercial)
            ->test(MissionForm::class)
            ->set('title', 'New Mission')
            ->set('description', 'Mission description')
            ->set('location', 'Paris')
            ->set('selectedTags', [$tag->id])
            ->call('save')
            ->assertRedirect(route('commercial.missions.index'));

        $this->assertDatabaseHas('missions', [
            'title' => 'New Mission',
            'description' => 'Mission description',
            'location' => 'Paris',
            'commercial_id' => $this->commercial->id,
        ]);
    });

    it('can edit an existing mission', function () {
        $mission = Mission::factory()->create([
            'commercial_id' => $this->commercial->id,
            'title' => 'Original Title',
        ]);

        Livewire::actingAs($this->commercial)
            ->test(MissionForm::class, ['mission' => $mission])
            ->assertSet('title', 'Original Title')
            ->set('title', 'Updated Title')
            ->call('save')
            ->assertRedirect(route('commercial.missions.index'));

        expect($mission->fresh()->title)->toBe('Updated Title');
    });

    it('validates required fields', function () {
        Livewire::actingAs($this->commercial)
            ->test(MissionForm::class)
            ->set('title', '')
            ->set('description', '')
            ->set('location', '')
            ->call('save')
            ->assertHasErrors(['title', 'description', 'location']);
    });

    it('can toggle tags', function () {
        $tag = Tag::factory()->create();

        Livewire::actingAs($this->commercial)
            ->test(MissionForm::class)
            ->assertSet('selectedTags', [])
            ->call('toggleTag', $tag->id)
            ->assertSet('selectedTags', [$tag->id])
            ->call('toggleTag', $tag->id)
            ->assertSet('selectedTags', []);
    });

    it('loads existing tags when editing', function () {
        $tags = Tag::factory()->count(2)->create();
        $mission = Mission::factory()->create([
            'commercial_id' => $this->commercial->id,
        ]);
        $mission->tags()->attach($tags);

        Livewire::actingAs($this->commercial)
            ->test(MissionForm::class, ['mission' => $mission])
            ->assertSet('selectedTags', $tags->pluck('id')->toArray());
    });
});

describe('MissionShow Component', function () {
    it('displays mission details', function () {
        $mission = Mission::factory()->create([
            'commercial_id' => $this->commercial->id,
            'title' => 'My Mission',
            'location' => 'Lyon',
        ]);

        Livewire::actingAs($this->commercial)
            ->test(MissionShow::class, ['mission' => $mission])
            ->assertSee('My Mission')
            ->assertSee('Lyon');
    });

    it('displays mission tags', function () {
        $tag = Tag::factory()->create(['name' => 'PHP']);
        $mission = Mission::factory()->create([
            'commercial_id' => $this->commercial->id,
        ]);
        $mission->tags()->attach($tag);

        Livewire::actingAs($this->commercial)
            ->test(MissionShow::class, ['mission' => $mission])
            ->assertSee('PHP');
    });

    it('displays applications for the mission', function () {
        $mission = Mission::factory()->create([
            'commercial_id' => $this->commercial->id,
        ]);
        $consultant = User::factory()->consultant()->create(['name' => 'John Consultant']);
        Application::factory()->create([
            'mission_id' => $mission->id,
            'consultant_id' => $consultant->id,
        ]);

        Livewire::actingAs($this->commercial)
            ->test(MissionShow::class, ['mission' => $mission])
            ->assertSee('John Consultant');
    });

    it('filters applications by status', function () {
        $mission = Mission::factory()->create([
            'commercial_id' => $this->commercial->id,
        ]);
        $pendingConsultant = User::factory()->consultant()->create(['name' => 'Pending Consultant']);
        $acceptedConsultant = User::factory()->consultant()->create(['name' => 'Accepted Consultant']);

        Application::factory()->create([
            'mission_id' => $mission->id,
            'consultant_id' => $pendingConsultant->id,
            'status' => ApplicationStatus::Pending,
        ]);
        Application::factory()->create([
            'mission_id' => $mission->id,
            'consultant_id' => $acceptedConsultant->id,
            'status' => ApplicationStatus::Accepted,
        ]);

        Livewire::actingAs($this->commercial)
            ->test(MissionShow::class, ['mission' => $mission])
            ->set('applicationStatus', ApplicationStatus::Pending->value)
            ->assertSee('Pending Consultant')
            ->assertDontSee('Accepted Consultant');
    });

    it('can update application status', function () {
        Notification::fake();

        $mission = Mission::factory()->create([
            'commercial_id' => $this->commercial->id,
        ]);
        $consultant = User::factory()->consultant()->create();
        $application = Application::factory()->create([
            'mission_id' => $mission->id,
            'consultant_id' => $consultant->id,
            'status' => ApplicationStatus::Pending,
        ]);

        Livewire::actingAs($this->commercial)
            ->test(MissionShow::class, ['mission' => $mission])
            ->call('updateApplicationStatus', $application->id, ApplicationStatus::Accepted->value);

        expect($application->fresh()->status)->toBe(ApplicationStatus::Accepted);
    });

    it('sends notification when application status changes', function () {
        Notification::fake();

        $mission = Mission::factory()->create([
            'commercial_id' => $this->commercial->id,
        ]);
        $consultant = User::factory()->consultant()->create();
        $application = Application::factory()->create([
            'mission_id' => $mission->id,
            'consultant_id' => $consultant->id,
            'status' => ApplicationStatus::Pending,
        ]);

        Livewire::actingAs($this->commercial)
            ->test(MissionShow::class, ['mission' => $mission])
            ->call('updateApplicationStatus', $application->id, ApplicationStatus::Accepted->value);

        Notification::assertSentTo($consultant, ApplicationStatusChanged::class);
    });

    it('forbids access to other commercials mission', function () {
        $otherCommercial = User::factory()->commercial()->create();
        $mission = Mission::factory()->create([
            'commercial_id' => $otherCommercial->id,
        ]);

        Livewire::actingAs($this->commercial)
            ->test(MissionShow::class, ['mission' => $mission])
            ->assertForbidden();
    });

    it('cannot update application status for other commercials mission', function () {
        $otherCommercial = User::factory()->commercial()->create();
        $mission = Mission::factory()->create([
            'commercial_id' => $otherCommercial->id,
        ]);
        $application = Application::factory()->create([
            'mission_id' => $mission->id,
            'status' => ApplicationStatus::Pending,
        ]);

        // Create a mission owned by our commercial
        $ownMission = Mission::factory()->create([
            'commercial_id' => $this->commercial->id,
        ]);

        Livewire::actingAs($this->commercial)
            ->test(MissionShow::class, ['mission' => $ownMission])
            ->call('updateApplicationStatus', $application->id, ApplicationStatus::Accepted->value);

        // Status should not change
        expect($application->fresh()->status)->toBe(ApplicationStatus::Pending);
    });
});
