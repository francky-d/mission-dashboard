<?php

use App\Enums\UserRole;
use App\Filament\Resources\AllowedEmailDomains\AllowedEmailDomainResource;
use App\Filament\Resources\AllowedEmailDomains\Pages\CreateAllowedEmailDomain;
use App\Filament\Resources\AllowedEmailDomains\Pages\EditAllowedEmailDomain;
use App\Filament\Resources\AllowedEmailDomains\Pages\ListAllowedEmailDomains;
use App\Models\AllowedEmailDomain;
use App\Models\User;
use Filament\Facades\Filament;
use Livewire\Livewire;

beforeEach(function () {
    Filament::setCurrentPanel(Filament::getPanel('admin'));
});

describe('AllowedEmailDomainResource Access Control', function () {
    it('denies access to non-admin users', function () {
        $consultant = User::factory()->create(['role' => UserRole::Consultant]);

        $this->actingAs($consultant)
            ->get(AllowedEmailDomainResource::getUrl('index'))
            ->assertForbidden();
    });

    it('denies access to commercial users', function () {
        $commercial = User::factory()->create(['role' => UserRole::Commercial]);

        $this->actingAs($commercial)
            ->get(AllowedEmailDomainResource::getUrl('index'))
            ->assertForbidden();
    });

    it('allows access to admin users', function () {
        $admin = User::factory()->create(['role' => UserRole::Admin]);

        $this->actingAs($admin)
            ->get(AllowedEmailDomainResource::getUrl('index'))
            ->assertSuccessful();
    });
});

describe('AllowedEmailDomainResource CRUD', function () {
    beforeEach(function () {
        $this->admin = User::factory()->create(['role' => UserRole::Admin]);
        $this->actingAs($this->admin);
    });

    it('can list allowed email domains', function () {
        $domains = AllowedEmailDomain::factory()->count(5)->create();

        Livewire::test(ListAllowedEmailDomains::class)
            ->assertCanSeeTableRecords($domains);
    });

    it('can search domains by name', function () {
        $domain1 = AllowedEmailDomain::factory()->create(['domain' => 'company.com']);
        $domain2 = AllowedEmailDomain::factory()->create(['domain' => 'other.org']);

        Livewire::test(ListAllowedEmailDomains::class)
            ->searchTable('company')
            ->assertCanSeeTableRecords([$domain1])
            ->assertCanNotSeeTableRecords([$domain2]);
    });

    it('can filter by active status', function () {
        $activeDomain = AllowedEmailDomain::factory()->create(['is_active' => true]);
        $inactiveDomain = AllowedEmailDomain::factory()->create(['is_active' => false]);

        Livewire::test(ListAllowedEmailDomains::class)
            ->filterTable('is_active', true)
            ->assertCanSeeTableRecords([$activeDomain])
            ->assertCanNotSeeTableRecords([$inactiveDomain]);
    });

    it('can create an allowed email domain', function () {
        Livewire::test(CreateAllowedEmailDomain::class)
            ->fillForm([
                'domain' => 'newcompany.com',
                'description' => 'New company domain',
                'is_active' => true,
            ])
            ->call('create')
            ->assertHasNoFormErrors();

        $this->assertDatabaseHas('allowed_email_domains', [
            'domain' => 'newcompany.com',
            'description' => 'New company domain',
            'is_active' => true,
        ]);
    });

    it('validates domain is required', function () {
        Livewire::test(CreateAllowedEmailDomain::class)
            ->fillForm([
                'domain' => '',
            ])
            ->call('create')
            ->assertHasFormErrors(['domain' => 'required']);
    });

    it('validates domain is unique', function () {
        AllowedEmailDomain::factory()->create(['domain' => 'existing.com']);

        Livewire::test(CreateAllowedEmailDomain::class)
            ->fillForm([
                'domain' => 'existing.com',
            ])
            ->call('create')
            ->assertHasFormErrors(['domain' => 'unique']);
    });

    it('validates domain format', function () {
        Livewire::test(CreateAllowedEmailDomain::class)
            ->fillForm([
                'domain' => 'invalid-domain',
            ])
            ->call('create')
            ->assertHasFormErrors(['domain']);
    });

    it('can edit an allowed email domain', function () {
        $domain = AllowedEmailDomain::factory()->create([
            'domain' => 'old.com',
            'description' => 'Old description',
            'is_active' => true,
        ]);

        Livewire::test(EditAllowedEmailDomain::class, ['record' => $domain->getRouteKey()])
            ->fillForm([
                'domain' => 'new.com',
                'description' => 'New description',
                'is_active' => false,
            ])
            ->call('save')
            ->assertHasNoFormErrors();

        expect($domain->fresh())
            ->domain->toBe('new.com')
            ->description->toBe('New description')
            ->is_active->toBeFalse();
    });

    it('can delete an allowed email domain', function () {
        $domain = AllowedEmailDomain::factory()->create();

        Livewire::test(EditAllowedEmailDomain::class, ['record' => $domain->getRouteKey()])
            ->callAction('delete');

        $this->assertDatabaseMissing('allowed_email_domains', [
            'id' => $domain->id,
        ]);
    });

    it('can bulk delete domains', function () {
        $domains = AllowedEmailDomain::factory()->count(3)->create();

        Livewire::test(ListAllowedEmailDomains::class)
            ->callTableBulkAction('delete', $domains);

        foreach ($domains as $domain) {
            $this->assertDatabaseMissing('allowed_email_domains', [
                'id' => $domain->id,
            ]);
        }
    });

    it('can toggle domain active status', function () {
        $domain = AllowedEmailDomain::factory()->create(['is_active' => true]);

        Livewire::test(EditAllowedEmailDomain::class, ['record' => $domain->getRouteKey()])
            ->fillForm([
                'is_active' => false,
            ])
            ->call('save')
            ->assertHasNoFormErrors();

        expect($domain->fresh()->is_active)->toBeFalse();
    });
});
