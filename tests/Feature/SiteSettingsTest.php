<?php

use App\Enums\UserRole;
use App\Filament\Pages\ManageSiteSettings;
use App\Models\SiteSettings;
use App\Models\User;
use Filament\Facades\Filament;
use Illuminate\Support\Facades\Cache;
use Spatie\Permission\Models\Role;

beforeEach(function () {
    // Clear cache before each test
    Cache::forget('site_settings');
    SiteSettings::query()->delete();
});

describe('SiteSettings Model', function () {
    it('creates default instance when none exists', function () {
        $settings = SiteSettings::instance();

        expect($settings)->toBeInstanceOf(SiteSettings::class)
            ->and($settings->site_name)->toBe('Mission Dashboard')
            ->and($settings->consultant_primary_color)->toBe('#3B82F6')
            ->and($settings->consultant_secondary_color)->toBe('#1E40AF')
            ->and($settings->consultant_accent_color)->toBe('#60A5FA')
            ->and($settings->commercial_primary_color)->toBe('#F97316')
            ->and($settings->commercial_secondary_color)->toBe('#C2410C')
            ->and($settings->commercial_accent_color)->toBe('#FB923C');
    });

    it('returns existing instance when one exists', function () {
        SiteSettings::create([
            'site_name' => 'Custom App',
            'consultant_primary_color' => '#FF0000',
            'consultant_secondary_color' => '#00FF00',
            'consultant_accent_color' => '#0000FF',
            'commercial_primary_color' => '#FFFF00',
            'commercial_secondary_color' => '#FF00FF',
            'commercial_accent_color' => '#00FFFF',
        ]);

        Cache::forget('site_settings');
        $settings = SiteSettings::instance();

        expect($settings->site_name)->toBe('Custom App')
            ->and($settings->consultant_primary_color)->toBe('#FF0000');
    });

    it('caches the instance', function () {
        $settings1 = SiteSettings::instance();
        $settings2 = SiteSettings::instance();

        // Both should be from cache after first call
        expect(Cache::has('site_settings'))->toBeTrue();
    });

    it('clears cache when updated', function () {
        $settings = SiteSettings::instance();
        Cache::put('site_settings', $settings);

        expect(Cache::has('site_settings'))->toBeTrue();

        $settings->update(['site_name' => 'Updated Name']);

        expect(Cache::has('site_settings'))->toBeFalse();
    });

    it('has default consultant colors', function () {
        $defaults = SiteSettings::defaultColors();

        expect($defaults['consultant_primary_color'])->toBe('#3B82F6')
            ->and($defaults['consultant_secondary_color'])->toBe('#1E40AF')
            ->and($defaults['consultant_accent_color'])->toBe('#60A5FA');
    });

    it('has default commercial colors', function () {
        $defaults = SiteSettings::defaultColors();

        expect($defaults['commercial_primary_color'])->toBe('#F97316')
            ->and($defaults['commercial_secondary_color'])->toBe('#C2410C')
            ->and($defaults['commercial_accent_color'])->toBe('#FB923C');
    });

    it('gets consultant theme colors', function () {
        $settings = SiteSettings::instance();
        $theme = $settings->getConsultantTheme();

        expect($theme)->toHaveKeys(['primary', 'secondary', 'accent'])
            ->and($theme['primary'])->toBe('#3B82F6')
            ->and($theme['secondary'])->toBe('#1E40AF')
            ->and($theme['accent'])->toBe('#60A5FA');
    });

    it('gets commercial theme colors', function () {
        $settings = SiteSettings::instance();
        $theme = $settings->getCommercialTheme();

        expect($theme)->toHaveKeys(['primary', 'secondary', 'accent'])
            ->and($theme['primary'])->toBe('#F97316')
            ->and($theme['secondary'])->toBe('#C2410C')
            ->and($theme['accent'])->toBe('#FB923C');
    });
});

describe('SiteSettings Admin Page', function () {
    beforeEach(function () {
        Filament::setCurrentPanel(Filament::getPanel('admin'));
        Role::firstOrCreate(['name' => 'Admin']);

        $this->admin = User::factory()->create(['role' => UserRole::Admin]);
        $this->admin->assignRole('Admin');
    });

    it('allows admin to access settings page', function () {
        $this->actingAs($this->admin);

        Livewire::test(ManageSiteSettings::class)
            ->assertSuccessful();
    });

    it('displays current settings in form', function () {
        SiteSettings::instance();

        $this->actingAs($this->admin);

        Livewire::test(ManageSiteSettings::class)
            ->assertFormSet([
                'site_name' => 'Mission Dashboard',
                'consultant_primary_color' => '#3B82F6',
                'commercial_primary_color' => '#F97316',
            ]);
    });

    it('saves settings changes', function () {
        SiteSettings::instance();

        $this->actingAs($this->admin);

        Livewire::test(ManageSiteSettings::class)
            ->fillForm([
                'site_name' => 'My Custom App',
                'consultant_primary_color' => '#FF0000',
                'consultant_secondary_color' => '#1E40AF',
                'consultant_accent_color' => '#60A5FA',
                'commercial_primary_color' => '#F97316',
                'commercial_secondary_color' => '#C2410C',
                'commercial_accent_color' => '#FB923C',
            ])
            ->call('save')
            ->assertNotified('Paramètres enregistrés');

        Cache::forget('site_settings');
        $settings = SiteSettings::instance();
        expect($settings->site_name)->toBe('My Custom App')
            ->and($settings->consultant_primary_color)->toBe('#FF0000');
    });
});

describe('Theme Colors for Consultant', function () {
    it('applies consultant colors in theme styles', function () {
        Role::firstOrCreate(['name' => 'Consultant']);
        SiteSettings::instance();

        $consultant = User::factory()->create(['role' => UserRole::Consultant]);
        $consultant->assignRole('Consultant');

        $response = $this->actingAs($consultant)->get('/consultant/dashboard');

        $response->assertSuccessful();
    });
});

describe('Theme Colors for Commercial', function () {
    it('applies commercial colors in theme styles', function () {
        Role::firstOrCreate(['name' => 'Commercial']);
        SiteSettings::instance();

        $commercial = User::factory()->create(['role' => UserRole::Commercial]);
        $commercial->assignRole('Commercial');

        $response = $this->actingAs($commercial)->get('/commercial/dashboard');

        $response->assertSuccessful();
    });
});

describe('Helper Functions', function () {
    it('converts hex to rgb correctly', function () {
        expect(hexToRgb('#FF0000'))->toBe('255, 0, 0')
            ->and(hexToRgb('#00FF00'))->toBe('0, 255, 0')
            ->and(hexToRgb('#0000FF'))->toBe('0, 0, 255')
            ->and(hexToRgb('#3B82F6'))->toBe('59, 130, 246')
            ->and(hexToRgb('#FFFFFF'))->toBe('255, 255, 255')
            ->and(hexToRgb('#000000'))->toBe('0, 0, 0');
    });

    it('handles hex without hash', function () {
        expect(hexToRgb('FF0000'))->toBe('255, 0, 0');
    });

    it('handles shorthand hex', function () {
        expect(hexToRgb('#F00'))->toBe('255, 0, 0')
            ->and(hexToRgb('#0F0'))->toBe('0, 255, 0')
            ->and(hexToRgb('#00F'))->toBe('0, 0, 255');
    });
});
