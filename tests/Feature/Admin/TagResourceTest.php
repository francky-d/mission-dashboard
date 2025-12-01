<?php

use App\Enums\UserRole;
use App\Filament\Resources\Tags\Pages\CreateTag;
use App\Filament\Resources\Tags\Pages\EditTag;
use App\Filament\Resources\Tags\Pages\ListTags;
use App\Filament\Resources\Tags\TagResource;
use App\Models\Tag;
use App\Models\User;
use Filament\Facades\Filament;
use Livewire\Livewire;

beforeEach(function () {
    Filament::setCurrentPanel(Filament::getPanel('admin'));
});

describe('TagResource Access Control', function () {
    it('denies access to non-admin users', function () {
        $consultant = User::factory()->create(['role' => UserRole::Consultant]);

        $this->actingAs($consultant)
            ->get(TagResource::getUrl('index'))
            ->assertForbidden();
    });

    it('denies access to commercial users', function () {
        $commercial = User::factory()->create(['role' => UserRole::Commercial]);

        $this->actingAs($commercial)
            ->get(TagResource::getUrl('index'))
            ->assertForbidden();
    });

    it('allows access to admin users', function () {
        $admin = User::factory()->create(['role' => UserRole::Admin]);

        $this->actingAs($admin)
            ->get(TagResource::getUrl('index'))
            ->assertSuccessful();
    });
});

describe('TagResource CRUD', function () {
    beforeEach(function () {
        $this->admin = User::factory()->create(['role' => UserRole::Admin]);
        $this->actingAs($this->admin);
    });

    it('can list tags', function () {
        $tags = Tag::factory()->count(5)->create();

        Livewire::test(ListTags::class)
            ->assertCanSeeTableRecords($tags);
    });

    it('can search tags by name', function () {
        $tag1 = Tag::factory()->create(['name' => 'PHP']);
        $tag2 = Tag::factory()->create(['name' => 'JavaScript']);

        Livewire::test(ListTags::class)
            ->searchTable('PHP')
            ->assertCanSeeTableRecords([$tag1])
            ->assertCanNotSeeTableRecords([$tag2]);
    });

    it('can create a tag', function () {
        Livewire::test(CreateTag::class)
            ->fillForm([
                'name' => 'Laravel',
            ])
            ->call('create')
            ->assertHasNoFormErrors();

        $this->assertDatabaseHas('tags', [
            'name' => 'Laravel',
        ]);
    });

    it('validates tag name is required', function () {
        Livewire::test(CreateTag::class)
            ->fillForm([
                'name' => '',
            ])
            ->call('create')
            ->assertHasFormErrors(['name' => 'required']);
    });

    it('validates tag name is unique', function () {
        Tag::factory()->create(['name' => 'PHP']);

        Livewire::test(CreateTag::class)
            ->fillForm([
                'name' => 'PHP',
            ])
            ->call('create')
            ->assertHasFormErrors(['name' => 'unique']);
    });

    it('can edit a tag', function () {
        $tag = Tag::factory()->create(['name' => 'Old Name']);

        Livewire::test(EditTag::class, ['record' => $tag->getRouteKey()])
            ->fillForm([
                'name' => 'New Name',
            ])
            ->call('save')
            ->assertHasNoFormErrors();

        expect($tag->fresh()->name)->toBe('New Name');
    });

    it('can delete a tag', function () {
        $tag = Tag::factory()->create();

        Livewire::test(EditTag::class, ['record' => $tag->getRouteKey()])
            ->callAction('delete');

        $this->assertDatabaseMissing('tags', [
            'id' => $tag->id,
        ]);
    });

    it('can bulk delete tags', function () {
        $tags = Tag::factory()->count(3)->create();

        Livewire::test(ListTags::class)
            ->callTableBulkAction('delete', $tags);

        foreach ($tags as $tag) {
            $this->assertDatabaseMissing('tags', [
                'id' => $tag->id,
            ]);
        }
    });
});
