<!DOCTYPE html>
<html lang="{{ str_replace('_', '-', app()->getLocale()) }}">

<head>
    <meta charset="utf-8">
    <meta name="viewport" content="width=device-width, initial-scale=1">
    <meta name="csrf-token" content="{{ csrf_token() }}">

    @php
        $siteSettings = \App\Models\SiteSettings::instance();
        $theme = auth()->check() && auth()->user()->role === \App\Enums\UserRole::Commercial ? 'commercial' : 'consultant';
    @endphp

    <title>{{ config('app.name', 'Laravel') }}</title>

    <!-- Fonts -->
    <link rel="preconnect" href="https://fonts.bunny.net">
    <link href="https://fonts.bunny.net/css?family=inter:400,500,600,700,800&display=swap" rel="stylesheet" />

    <!-- Scripts -->
    @vite(['resources/css/app.css', 'resources/js/app.js'])

    <style>
        :root {
            --theme-primary:
                {{ $theme === 'consultant' ? $siteSettings->consultant_primary_color : $siteSettings->commercial_primary_color }}
            ;
            --theme-secondary:
                {{ $theme === 'consultant' ? $siteSettings->consultant_secondary_color : $siteSettings->commercial_secondary_color }}
            ;
            --theme-accent:
                {{ $theme === 'consultant' ? $siteSettings->consultant_accent_color : $siteSettings->commercial_accent_color }}
            ;
        }
    </style>
</head>

<body class="font-sans antialiased">
    <div class="min-h-screen bg-gray-100">
        <livewire:layout.navigation />

        <!-- Page Heading -->
        @if (isset($header))
            <header class="bg-white shadow">
                <div class="max-w-7xl mx-auto py-6 px-4 sm:px-6 lg:px-8">
                    {{ $header }}
                </div>
            </header>
        @endif

        <!-- Page Content -->
        <main>
            {{ $slot }}
        </main>
    </div>
</body>

</html>