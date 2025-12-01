<?php

use App\Models\AllowedEmailDomain;
use App\Rules\AllowedEmailDomain as AllowedEmailDomainRule;
use Illuminate\Support\Facades\Cache;
use Illuminate\Support\Facades\Validator;

describe('AllowedEmailDomain Model', function () {
    it('can create a domain', function () {
        $domain = AllowedEmailDomain::factory()->create([
            'domain' => 'company.com',
            'description' => 'Main company domain',
            'is_active' => true,
        ]);

        expect($domain)->toBeInstanceOf(AllowedEmailDomain::class)
            ->and($domain->domain)->toBe('company.com')
            ->and($domain->description)->toBe('Main company domain')
            ->and($domain->is_active)->toBeTrue();
    });

    it('allows all emails when no domains configured', function () {
        AllowedEmailDomain::query()->delete();
        AllowedEmailDomain::clearCache();

        expect(AllowedEmailDomain::isEmailAllowed('anyone@random.com'))->toBeTrue();
    });

    it('allows emails from active domains', function () {
        AllowedEmailDomain::factory()->create(['domain' => 'company.com', 'is_active' => true]);
        AllowedEmailDomain::clearCache();

        expect(AllowedEmailDomain::isEmailAllowed('user@company.com'))->toBeTrue();
    });

    it('rejects emails from inactive domains when active domains exist', function () {
        AllowedEmailDomain::factory()->create(['domain' => 'active.com', 'is_active' => true]);
        AllowedEmailDomain::factory()->create(['domain' => 'inactive.com', 'is_active' => false]);
        AllowedEmailDomain::clearCache();

        expect(AllowedEmailDomain::isEmailAllowed('user@inactive.com'))->toBeFalse();
    });

    it('rejects emails from non-allowed domains', function () {
        AllowedEmailDomain::factory()->create(['domain' => 'allowed.com', 'is_active' => true]);
        AllowedEmailDomain::clearCache();

        expect(AllowedEmailDomain::isEmailAllowed('user@notallowed.com'))->toBeFalse();
    });

    it('handles case insensitive domain matching', function () {
        AllowedEmailDomain::factory()->create(['domain' => 'Company.COM', 'is_active' => true]);
        AllowedEmailDomain::clearCache();

        expect(AllowedEmailDomain::isEmailAllowed('user@company.com'))->toBeTrue()
            ->and(AllowedEmailDomain::isEmailAllowed('user@COMPANY.COM'))->toBeTrue();
    });

    it('handles invalid emails gracefully', function () {
        AllowedEmailDomain::factory()->create(['domain' => 'company.com', 'is_active' => true]);
        AllowedEmailDomain::clearCache();

        expect(AllowedEmailDomain::isEmailAllowed('invalid-email'))->toBeFalse()
            ->and(AllowedEmailDomain::isEmailAllowed(''))->toBeFalse();
    });

    it('caches active domains', function () {
        AllowedEmailDomain::factory()->create(['domain' => 'cached.com', 'is_active' => true]);
        AllowedEmailDomain::clearCache();

        // First call should cache
        AllowedEmailDomain::getActiveDomains();

        expect(Cache::has('allowed_email_domains'))->toBeTrue();
    });

    it('clears cache on create', function () {
        AllowedEmailDomain::factory()->create(['domain' => 'first.com', 'is_active' => true]);
        AllowedEmailDomain::clearCache();
        AllowedEmailDomain::getActiveDomains();

        expect(Cache::has('allowed_email_domains'))->toBeTrue();

        AllowedEmailDomain::factory()->create(['domain' => 'second.com', 'is_active' => true]);

        // Should re-cache with new domain
        $domains = AllowedEmailDomain::getActiveDomains();
        expect($domains)->toContain('second.com');
    });

    it('clears cache on update', function () {
        $domain = AllowedEmailDomain::factory()->create(['domain' => 'update.com', 'is_active' => true]);
        AllowedEmailDomain::clearCache();
        AllowedEmailDomain::getActiveDomains();

        $domain->update(['is_active' => false]);

        $domains = AllowedEmailDomain::getActiveDomains();
        expect($domains)->not->toContain('update.com');
    });

    it('clears cache on delete', function () {
        $domain = AllowedEmailDomain::factory()->create(['domain' => 'delete.com', 'is_active' => true]);
        AllowedEmailDomain::clearCache();
        AllowedEmailDomain::getActiveDomains();

        $domain->delete();

        $domains = AllowedEmailDomain::getActiveDomains();
        expect($domains)->not->toContain('delete.com');
    });
});

describe('AllowedEmailDomain Validation Rule', function () {
    it('passes validation for allowed email domain', function () {
        AllowedEmailDomain::factory()->create(['domain' => 'valid.com', 'is_active' => true]);
        AllowedEmailDomain::clearCache();

        $validator = Validator::make(
            ['email' => 'test@valid.com'],
            ['email' => ['email', new AllowedEmailDomainRule]]
        );

        expect($validator->passes())->toBeTrue();
    });

    it('fails validation for disallowed email domain', function () {
        AllowedEmailDomain::factory()->create(['domain' => 'allowed.com', 'is_active' => true]);
        AllowedEmailDomain::clearCache();

        $validator = Validator::make(
            ['email' => 'test@notallowed.com'],
            ['email' => ['email', new AllowedEmailDomainRule]]
        );

        expect($validator->fails())->toBeTrue()
            ->and($validator->errors()->first('email'))->toContain('not allowed');
    });

    it('passes validation when no domains configured', function () {
        AllowedEmailDomain::query()->delete();
        AllowedEmailDomain::clearCache();

        $validator = Validator::make(
            ['email' => 'test@any.com'],
            ['email' => ['email', new AllowedEmailDomainRule]]
        );

        expect($validator->passes())->toBeTrue();
    });
});
