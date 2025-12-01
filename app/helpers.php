<?php

if (! function_exists('hexToRgb')) {
    /**
     * Convert hex color to RGB string.
     */
    function hexToRgb(string $hex): string
    {
        $hex = ltrim($hex, '#');
        if (strlen($hex) === 3) {
            $hex = $hex[0].$hex[0].$hex[1].$hex[1].$hex[2].$hex[2];
        }

        return implode(', ', array_map('hexdec', str_split($hex, 2)));
    }
}

if (! function_exists('siteSettings')) {
    /**
     * Get the site settings instance.
     */
    function siteSettings(): \App\Models\SiteSettings
    {
        return app('site.settings');
    }
}
