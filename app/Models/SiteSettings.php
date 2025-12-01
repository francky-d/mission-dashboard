<?php

namespace App\Models;

use Illuminate\Database\Eloquent\Factories\HasFactory;
use Illuminate\Database\Eloquent\Model;
use Illuminate\Support\Facades\Cache;
use Illuminate\Support\Facades\Storage;

class SiteSettings extends Model
{
    /** @use HasFactory<\Database\Factories\SiteSettingsFactory> */
    use HasFactory;

    protected $fillable = [
        'site_name',
        'logo_path',
        'consultant_primary_color',
        'consultant_secondary_color',
        'consultant_accent_color',
        'commercial_primary_color',
        'commercial_secondary_color',
        'commercial_accent_color',
    ];

    /**
     * Get default colors for themes.
     *
     * @return array<string, string>
     */
    public static function defaultColors(): array
    {
        return [
            'consultant_primary_color' => '#3B82F6',
            'consultant_secondary_color' => '#1E40AF',
            'consultant_accent_color' => '#60A5FA',
            'commercial_primary_color' => '#F97316',
            'commercial_secondary_color' => '#C2410C',
            'commercial_accent_color' => '#FB923C',
        ];
    }

    /**
     * Get the singleton settings instance (cached).
     */
    public static function instance(): self
    {
        return Cache::rememberForever('site_settings', function () {
            return self::query()->first() ?? self::create(array_merge(
                ['site_name' => 'Mission Dashboard'],
                self::defaultColors()
            ));
        });
    }

    /**
     * Clear the cached settings.
     */
    public static function clearCache(): void
    {
        Cache::forget('site_settings');
    }

    /**
     * Get the logo URL.
     */
    public function getLogoUrlAttribute(): ?string
    {
        if (! $this->logo_path) {
            return null;
        }

        return Storage::disk('public')->url($this->logo_path);
    }

    /**
     * Get consultant theme colors.
     *
     * @return array<string, string>
     */
    public function getConsultantTheme(): array
    {
        return [
            'primary' => $this->consultant_primary_color,
            'secondary' => $this->consultant_secondary_color,
            'accent' => $this->consultant_accent_color,
        ];
    }

    /**
     * Get commercial theme colors.
     *
     * @return array<string, string>
     */
    public function getCommercialTheme(): array
    {
        return [
            'primary' => $this->commercial_primary_color,
            'secondary' => $this->commercial_secondary_color,
            'accent' => $this->commercial_accent_color,
        ];
    }

    /**
     * Boot the model.
     */
    protected static function booted(): void
    {
        static::saved(fn () => self::clearCache());
        static::deleted(fn () => self::clearCache());
    }
}
