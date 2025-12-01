<?php

namespace App\Models;

use Illuminate\Database\Eloquent\Factories\HasFactory;
use Illuminate\Database\Eloquent\Model;
use Illuminate\Support\Facades\Cache;

class AllowedEmailDomain extends Model
{
    /** @use HasFactory<\Database\Factories\AllowedEmailDomainFactory> */
    use HasFactory;

    protected $fillable = [
        'domain',
        'description',
        'is_active',
    ];

    protected function casts(): array
    {
        return [
            'is_active' => 'boolean',
        ];
    }

    /**
     * Check if an email address has an allowed domain.
     */
    public static function isEmailAllowed(string $email): bool
    {
        $domain = strtolower(substr(strrchr($email, '@'), 1));

        if (empty($domain)) {
            return false;
        }

        $allowedDomains = self::getActiveDomains();

        // If no domains are configured, allow all (for initial setup)
        if (empty($allowedDomains)) {
            return true;
        }

        return in_array($domain, $allowedDomains, true);
    }

    /**
     * Get all active domains (cached for performance).
     *
     * @return array<string>
     */
    public static function getActiveDomains(): array
    {
        return Cache::remember('allowed_email_domains', 3600, function () {
            return self::query()
                ->where('is_active', true)
                ->pluck('domain')
                ->map(fn (string $domain) => strtolower($domain))
                ->all();
        });
    }

    /**
     * Clear the cached domains.
     */
    public static function clearCache(): void
    {
        Cache::forget('allowed_email_domains');
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
