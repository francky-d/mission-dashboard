@php
    $settings = $siteSettings ?? \App\Models\SiteSettings::instance();
    $userRole = auth()->user()?->role ?? null;

    // Determine theme based on user role
    $isCommercial = $userRole === \App\Enums\UserRole::Commercial;

    $primaryColor = $isCommercial ? $settings->commercial_primary_color : $settings->consultant_primary_color;
    $secondaryColor = $isCommercial ? $settings->commercial_secondary_color : $settings->consultant_secondary_color;
    $accentColor = $isCommercial ? $settings->commercial_accent_color : $settings->consultant_accent_color;
@endphp

<style>
    :root {
        --theme-primary:
            {{ $primaryColor }}
        ;
        --theme-secondary:
            {{ $secondaryColor }}
        ;
        --theme-accent:
            {{ $accentColor }}
        ;
        --theme-primary-rgb:
            {{ hexToRgb($primaryColor) }}
        ;
        --theme-secondary-rgb:
            {{ hexToRgb($secondaryColor) }}
        ;
        --theme-accent-rgb:
            {{ hexToRgb($accentColor) }}
        ;
    }
</style>