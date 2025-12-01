<?php

use App\Livewire\Consultant\Profile\EditProfile;
use App\Models\ConsultantProfile;
use App\Models\Tag;
use App\Models\User;
use Illuminate\Http\UploadedFile;
use Illuminate\Support\Facades\Storage;
use Livewire\Livewire;

beforeEach(function () {
    Storage::fake('public');
});

it('redirects guests to login page', function () {
    $this->get(route('consultant.profile'))
        ->assertRedirect(route('login'));
});

it('denies access to non-consultant users', function () {
    $commercial = User::factory()->commercial()->create();

    $this->actingAs($commercial)
        ->get(route('consultant.profile'))
        ->assertForbidden();
});

it('allows consultant to access profile page', function () {
    $consultant = User::factory()->consultant()->create();

    $this->actingAs($consultant)
        ->get(route('consultant.profile'))
        ->assertOk()
        ->assertSeeLivewire(EditProfile::class);
});

it('can update consultant profile bio and experience', function () {
    $consultant = User::factory()->consultant()->create();

    Livewire::actingAs($consultant)
        ->test(EditProfile::class)
        ->set('bio', 'Je suis un développeur passionné avec une expertise en Laravel.')
        ->set('experienceYears', 5)
        ->call('save')
        ->assertDispatched('profile-updated');

    $this->assertDatabaseHas('consultant_profiles', [
        'user_id' => $consultant->id,
        'bio' => 'Je suis un développeur passionné avec une expertise en Laravel.',
        'experience_years' => 5,
    ]);
});

it('can select tags for consultant profile', function () {
    $consultant = User::factory()->consultant()->create();
    $tags = Tag::factory()->count(3)->sequence(
        ['name' => 'PHP'],
        ['name' => 'Laravel'],
        ['name' => 'JavaScript'],
    )->create();

    Livewire::actingAs($consultant)
        ->test(EditProfile::class)
        ->set('bio', 'Développeur full-stack')
        ->set('selectedTags', [$tags[0]->id, $tags[1]->id])
        ->call('save')
        ->assertDispatched('profile-updated');

    $profile = $consultant->fresh()->consultantProfile;

    expect($profile->tags)->toHaveCount(2);
    expect($profile->tags->pluck('name')->toArray())->toContain('PHP', 'Laravel');
});

it('can upload cv as pdf', function () {
    $consultant = User::factory()->consultant()->create();
    $cv = UploadedFile::fake()->create('mon-cv.pdf', 1024, 'application/pdf');

    Livewire::actingAs($consultant)
        ->test(EditProfile::class)
        ->set('cv', $cv)
        ->call('save')
        ->assertDispatched('profile-updated');

    $profile = $consultant->fresh()->consultantProfile;

    expect($profile->cv_url)->not->toBeNull();
    Storage::disk('public')->assertExists($profile->cv_url);
});

it('validates cv must be pdf', function () {
    $consultant = User::factory()->consultant()->create();
    $wrongFile = UploadedFile::fake()->create('document.docx', 1024);

    Livewire::actingAs($consultant)
        ->test(EditProfile::class)
        ->set('cv', $wrongFile)
        ->call('save')
        ->assertHasErrors(['cv']);
});

it('validates cv max size is 5MB', function () {
    $consultant = User::factory()->consultant()->create();
    $largeFile = UploadedFile::fake()->create('large-cv.pdf', 6000, 'application/pdf');

    Livewire::actingAs($consultant)
        ->test(EditProfile::class)
        ->set('cv', $largeFile)
        ->call('save')
        ->assertHasErrors(['cv']);
});

it('can delete existing cv', function () {
    $consultant = User::factory()->consultant()->create();
    $profile = ConsultantProfile::factory()->withCv()->create(['user_id' => $consultant->id]);

    // Create fake file
    Storage::disk('public')->put($profile->cv_url, 'fake content');

    Livewire::actingAs($consultant)
        ->test(EditProfile::class)
        ->call('deleteCv');

    expect($consultant->fresh()->consultantProfile->cv_url)->toBeNull();
    Storage::disk('public')->assertMissing($profile->cv_url);
});

it('loads existing profile data on mount', function () {
    $consultant = User::factory()->consultant()->create();
    $tags = Tag::factory()->count(2)->sequence(
        ['name' => 'React'],
        ['name' => 'Vue.js'],
    )->create();

    $profile = ConsultantProfile::factory()->create([
        'user_id' => $consultant->id,
        'bio' => 'Mon profil existant',
        'experience_years' => 8,
    ]);
    $profile->tags()->attach($tags);

    Livewire::actingAs($consultant)
        ->test(EditProfile::class)
        ->assertSet('bio', 'Mon profil existant')
        ->assertSet('experienceYears', 8)
        ->assertSet('selectedTags', $tags->pluck('id')->toArray());
});

it('validates bio max length is 2000 characters', function () {
    $consultant = User::factory()->consultant()->create();

    Livewire::actingAs($consultant)
        ->test(EditProfile::class)
        ->set('bio', str_repeat('a', 2001))
        ->call('save')
        ->assertHasErrors(['bio']);
});

it('validates experience years is between 0 and 50', function () {
    $consultant = User::factory()->consultant()->create();

    Livewire::actingAs($consultant)
        ->test(EditProfile::class)
        ->set('experienceYears', -1)
        ->call('save')
        ->assertHasErrors(['experienceYears']);

    Livewire::actingAs($consultant)
        ->test(EditProfile::class)
        ->set('experienceYears', 51)
        ->call('save')
        ->assertHasErrors(['experienceYears']);
});
