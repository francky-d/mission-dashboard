<!DOCTYPE html>
<html lang="{{ str_replace('_', '-', app()->getLocale()) }}">

<head>
    <meta charset="utf-8">
    <meta name="viewport" content="width=device-width, initial-scale=1">
    <meta name="csrf-token" content="{{ csrf_token() }}">

    @php
        $siteSettings = \App\Models\SiteSettings::instance();
    @endphp

    <title>{{ $siteSettings->site_name ?? config('app.name', 'Laravel') }}</title>

    <!-- Fonts -->
    <link rel="preconnect" href="https://fonts.bunny.net">
    <link href="https://fonts.bunny.net/css?family=figtree:400,500,600,700&display=swap" rel="stylesheet" />

    <!-- Scripts -->
    @vite(['resources/css/app.css', 'resources/js/app.js'])

    @isset($theme)
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
    @endisset
</head>

<body class="font-sans text-gray-900 antialiased">
    @isset($theme)
        {{-- Themed layout for role-specific auth --}}
        <div class="min-h-screen flex">
            {{-- Left side - Branding --}}
            <div class="hidden lg:flex lg:w-1/2 relative overflow-hidden"
                style="background: linear-gradient(135deg, var(--theme-primary) 0%, var(--theme-secondary) 100%);">
                <div class="absolute inset-0 bg-black/10"></div>
                <div class="relative z-10 flex flex-col justify-center items-center w-full p-12 text-white">
                    <a href="/" class="mb-8">
                        @if($siteSettings->logo_url)
                            <img src="{{ $siteSettings->logo_url }}" alt="{{ $siteSettings->site_name }}"
                                class="h-20 w-auto brightness-0 invert">
                        @else
                            <div class="flex items-center space-x-3">
                                <svg class="w-12 h-12" viewBox="0 0 24 24" fill="currentColor">
                                    <path d="M12 2L2 7l10 5 10-5-10-5zM2 17l10 5 10-5M2 12l10 5 10-5"></path>
                                </svg>
                                <span class="text-3xl font-bold">{{ $siteSettings->site_name }}</span>
                            </div>
                        @endif
                    </a>

                    <div class="max-w-md text-center">
                        @if($theme === 'consultant')
                            <h2 class="text-3xl font-bold mb-4">Espace Consultant</h2>
                            <p class="text-lg text-white/90 mb-8">
                                Gérez vos missions, suivez vos CRA et restez connecté avec votre équipe commerciale.
                            </p>
                            <div class="space-y-4 text-left">
                                <div class="flex items-center space-x-3">
                                    <svg class="w-6 h-6 text-white/80" fill="none" stroke="currentColor" viewBox="0 0 24 24">
                                        <path stroke-linecap="round" stroke-linejoin="round" stroke-width="2"
                                            d="M9 12l2 2 4-4m6 2a9 9 0 11-18 0 9 9 0 0118 0z"></path>
                                    </svg>
                                    <span>Suivi de vos missions en temps réel</span>
                                </div>
                                <div class="flex items-center space-x-3">
                                    <svg class="w-6 h-6 text-white/80" fill="none" stroke="currentColor" viewBox="0 0 24 24">
                                        <path stroke-linecap="round" stroke-linejoin="round" stroke-width="2"
                                            d="M9 12l2 2 4-4m6 2a9 9 0 11-18 0 9 9 0 0118 0z"></path>
                                    </svg>
                                    <span>Génération automatique des CRA</span>
                                </div>
                                <div class="flex items-center space-x-3">
                                    <svg class="w-6 h-6 text-white/80" fill="none" stroke="currentColor" viewBox="0 0 24 24">
                                        <path stroke-linecap="round" stroke-linejoin="round" stroke-width="2"
                                            d="M9 12l2 2 4-4m6 2a9 9 0 11-18 0 9 9 0 0118 0z"></path>
                                    </svg>
                                    <span>Communication simplifiée</span>
                                </div>
                            </div>
                        @else
                            <h2 class="text-3xl font-bold mb-4">Espace Commercial</h2>
                            <p class="text-lg text-white/90 mb-8">
                                Pilotez votre portefeuille de consultants et optimisez le placement de vos talents.
                            </p>
                            <div class="space-y-4 text-left">
                                <div class="flex items-center space-x-3">
                                    <svg class="w-6 h-6 text-white/80" fill="none" stroke="currentColor" viewBox="0 0 24 24">
                                        <path stroke-linecap="round" stroke-linejoin="round" stroke-width="2"
                                            d="M9 12l2 2 4-4m6 2a9 9 0 11-18 0 9 9 0 0118 0z"></path>
                                    </svg>
                                    <span>Gestion de votre équipe de consultants</span>
                                </div>
                                <div class="flex items-center space-x-3">
                                    <svg class="w-6 h-6 text-white/80" fill="none" stroke="currentColor" viewBox="0 0 24 24">
                                        <path stroke-linecap="round" stroke-linejoin="round" stroke-width="2"
                                            d="M9 12l2 2 4-4m6 2a9 9 0 11-18 0 9 9 0 0118 0z"></path>
                                    </svg>
                                    <span>Suivi des missions et disponibilités</span>
                                </div>
                                <div class="flex items-center space-x-3">
                                    <svg class="w-6 h-6 text-white/80" fill="none" stroke="currentColor" viewBox="0 0 24 24">
                                        <path stroke-linecap="round" stroke-linejoin="round" stroke-width="2"
                                            d="M9 12l2 2 4-4m6 2a9 9 0 11-18 0 9 9 0 0118 0z"></path>
                                    </svg>
                                    <span>Tableaux de bord et analytics</span>
                                </div>
                            </div>
                        @endif
                    </div>
                </div>

                {{-- Decorative elements --}}
                <div class="absolute bottom-0 left-0 w-full h-32 bg-gradient-to-t from-black/20 to-transparent"></div>
                <div class="absolute -bottom-20 -left-20 w-80 h-80 rounded-full"
                    style="background: var(--theme-accent); opacity: 0.1;"></div>
                <div class="absolute -top-20 -right-20 w-96 h-96 rounded-full"
                    style="background: var(--theme-accent); opacity: 0.1;"></div>
            </div>

            {{-- Right side - Form --}}
            <div class="w-full lg:w-1/2 flex flex-col justify-center items-center p-6 sm:p-12 bg-gray-50">
                <div class="w-full max-w-md">
                    {{-- Mobile logo --}}
                    <div class="lg:hidden mb-8 text-center">
                        <a href="/" class="inline-block">
                            @if($siteSettings->logo_url)
                                <img src="{{ $siteSettings->logo_url }}" alt="{{ $siteSettings->site_name }}"
                                    class="h-12 w-auto mx-auto">
                            @else
                                <div class="flex items-center justify-center space-x-2" style="color: var(--theme-primary);">
                                    <svg class="w-10 h-10" viewBox="0 0 24 24" fill="currentColor">
                                        <path d="M12 2L2 7l10 5 10-5-10-5zM2 17l10 5 10-5M2 12l10 5 10-5"></path>
                                    </svg>
                                    <span class="text-2xl font-bold">{{ $siteSettings->site_name }}</span>
                                </div>
                            @endif
                        </a>
                        <p class="mt-2 text-sm text-gray-600">
                            {{ $theme === 'consultant' ? 'Espace Consultant' : 'Espace Commercial' }}
                        </p>
                    </div>

                    <div class="bg-white shadow-xl rounded-2xl p-8">
                        {{ $slot }}
                    </div>

                    <p class="mt-6 text-center text-sm text-gray-500">
                        <a href="/" class="hover:underline" style="color: var(--theme-primary);">
                            ← Retour à l'accueil
                        </a>
                    </p>
                </div>
            </div>
        </div>
    @else
        {{-- Default layout --}}
        <div class="min-h-screen flex flex-col sm:justify-center items-center pt-6 sm:pt-0 bg-gray-100">
            <div>
                <a href="/" wire:navigate>
                    @if($siteSettings->logo_url)
                        <img src="{{ $siteSettings->logo_url }}" alt="{{ $siteSettings->site_name }}" class="h-20 w-auto">
                    @else
                        <x-application-logo class="w-20 h-20 fill-current text-gray-500" />
                    @endif
                </a>
            </div>

            <div class="w-full sm:max-w-md mt-6 px-6 py-4 bg-white shadow-md overflow-hidden sm:rounded-lg">
                {{ $slot }}
            </div>
        </div>
    @endisset
</body>

</html>