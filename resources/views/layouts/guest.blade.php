<!DOCTYPE html>
<html lang="{{ str_replace('_', '-', app()->getLocale()) }}" class="scroll-smooth">

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
    <link href="https://fonts.bunny.net/css?family=inter:400,500,600,700,800&display=swap" rel="stylesheet" />

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

            .animate-float {
                animation: float 6s ease-in-out infinite;
            }

            .animate-float-delayed {
                animation: float 6s ease-in-out infinite;
                animation-delay: 3s;
            }

            @keyframes float {

                0%,
                100% {
                    transform: translateY(0px) rotate(0deg);
                }

                50% {
                    transform: translateY(-20px) rotate(3deg);
                }
            }

            .gradient-border {
                background: white;
                border: 2px solid var(--theme-primary);
            }
        </style>
    @endisset
</head>

<body class="font-sans text-slate-900 antialiased">
    @isset($theme)
        {{-- Themed layout for role-specific auth --}}
        <div class="min-h-screen flex">
            {{-- Left side - Branding --}}
            <div class="hidden lg:flex lg:w-1/2 relative overflow-hidden" style="background-color: var(--theme-primary);">

                {{-- Animated background shapes --}}
                <div class="absolute inset-0">
                    <div class="absolute top-20 left-10 w-64 h-64 rounded-full opacity-10 bg-white animate-float"></div>
                    <div
                        class="absolute bottom-20 right-10 w-80 h-80 rounded-full opacity-10 bg-white animate-float-delayed">
                    </div>
                    <div class="absolute top-1/2 left-1/3 w-40 h-40 rounded-full opacity-5 bg-white"></div>
                </div>

                {{-- Grid pattern --}}
                <div class="absolute inset-0 bg-[url('data:image/svg+xml,%3Csvg width=\" 60\" height=\"60\" viewBox=\"0 0 60
                    60\" xmlns=\"http://www.w3.org/2000/svg\"%3E%3Cg fill=\"none\" fill-rule=\"evenodd\"%3E%3Cg
                    fill=\"%23ffffff\" fill-opacity=\"0.05\"%3E%3Cpath d=\"M36
                    34v-4h-2v4h-4v2h4v4h2v-4h4v-2h-4zm0-30V0h-2v4h-4v2h4v4h2V6h4V4h-4zM6 34v-4H4v4H0v2h4v4h2v-4h4v-2H6zM6
                    4V0H4v4H0v2h4v4h2V6h4V4H6z\"/%3E%3C/g%3E%3C/g%3E%3C/svg%3E')]"></div>

                <div class="relative z-10 flex flex-col justify-center items-center w-full p-12 text-white">
                    {{-- Logo --}}
                    <a href="/" class="mb-12 group">
                        @if($siteSettings->logo_url)
                            <img src="{{ $siteSettings->logo_url }}" alt="{{ $siteSettings->site_name }}"
                                class="h-16 w-auto brightness-0 invert group-hover:scale-105 transition-transform">
                        @else
                            <div class="flex items-center gap-3 group-hover:scale-105 transition-transform">
                                <div class="w-12 h-12 rounded-xl bg-white/20 backdrop-blur-sm flex items-center justify-center">
                                    <svg class="w-7 h-7" fill="none" stroke="currentColor" viewBox="0 0 24 24">
                                        <path stroke-linecap="round" stroke-linejoin="round" stroke-width="2"
                                            d="M13 10V3L4 14h7v7l9-11h-7z" />
                                    </svg>
                                </div>
                                <span class="text-2xl font-bold">{{ $siteSettings->site_name }}</span>
                            </div>
                        @endif
                    </a>

                    <div class="max-w-md text-center">
                        @if($theme === 'consultant')
                            {{-- Badge --}}
                            <div
                                class="inline-flex items-center gap-2 px-4 py-2 rounded-full bg-white/10 backdrop-blur-sm border border-white/20 text-sm mb-6">
                                <svg class="w-5 h-5" fill="none" stroke="currentColor" viewBox="0 0 24 24">
                                    <path stroke-linecap="round" stroke-linejoin="round" stroke-width="2"
                                        d="M16 7a4 4 0 11-8 0 4 4 0 018 0zM12 14a7 7 0 00-7 7h14a7 7 0 00-7-7z" />
                                </svg>
                                Espace Consultant
                            </div>
                            <h2 class="text-4xl font-bold mb-4">Bienvenue</h2>
                            <p class="text-lg text-white/80 mb-10 leading-relaxed">
                                Accédez à vos missions, postulez aux nouvelles opportunités et échangez avec les commerciaux.
                            </p>
                            <div
                                class="space-y-4 text-left bg-white/10 backdrop-blur-sm rounded-2xl p-6 border border-white/20">
                                <div class="flex items-center gap-4">
                                    <div
                                        class="w-10 h-10 rounded-xl bg-white/20 flex items-center justify-center flex-shrink-0">
                                        <svg class="w-5 h-5" fill="none" stroke="currentColor" viewBox="0 0 24 24">
                                            <path stroke-linecap="round" stroke-linejoin="round" stroke-width="2"
                                                d="M21 13.255A23.931 23.931 0 0112 15c-3.183 0-6.22-.62-9-1.745M16 6V4a2 2 0 00-2-2h-4a2 2 0 00-2 2v2m4 6h.01M5 20h14a2 2 0 002-2V8a2 2 0 00-2-2H5a2 2 0 00-2 2v10a2 2 0 002 2z" />
                                        </svg>
                                    </div>
                                    <span class="text-white/90">Parcourez les missions disponibles</span>
                                </div>
                                <div class="flex items-center gap-4">
                                    <div
                                        class="w-10 h-10 rounded-xl bg-white/20 flex items-center justify-center flex-shrink-0">
                                        <svg class="w-5 h-5" fill="none" stroke="currentColor" viewBox="0 0 24 24">
                                            <path stroke-linecap="round" stroke-linejoin="round" stroke-width="2"
                                                d="M9 12l2 2 4-4m6 2a9 9 0 11-18 0 9 9 0 0118 0z" />
                                        </svg>
                                    </div>
                                    <span class="text-white/90">Postulez en un clic</span>
                                </div>
                                <div class="flex items-center gap-4">
                                    <div
                                        class="w-10 h-10 rounded-xl bg-white/20 flex items-center justify-center flex-shrink-0">
                                        <svg class="w-5 h-5" fill="none" stroke="currentColor" viewBox="0 0 24 24">
                                            <path stroke-linecap="round" stroke-linejoin="round" stroke-width="2"
                                                d="M8 12h.01M12 12h.01M16 12h.01M21 12c0 4.418-4.03 8-9 8a9.863 9.863 0 01-4.255-.949L3 20l1.395-3.72C3.512 15.042 3 13.574 3 12c0-4.418 4.03-8 9-8s9 3.582 9 8z" />
                                        </svg>
                                    </div>
                                    <span class="text-white/90">Messagerie intégrée</span>
                                </div>
                            </div>
                        @else
                            {{-- Badge --}}
                            <div
                                class="inline-flex items-center gap-2 px-4 py-2 rounded-full bg-white/10 backdrop-blur-sm border border-white/20 text-sm mb-6">
                                <svg class="w-5 h-5" fill="none" stroke="currentColor" viewBox="0 0 24 24">
                                    <path stroke-linecap="round" stroke-linejoin="round" stroke-width="2"
                                        d="M21 13.255A23.931 23.931 0 0112 15c-3.183 0-6.22-.62-9-1.745M16 6V4a2 2 0 00-2-2h-4a2 2 0 00-2 2v2m4 6h.01M5 20h14a2 2 0 002-2V8a2 2 0 00-2-2H5a2 2 0 00-2 2v10a2 2 0 002 2z" />
                                </svg>
                                Espace Commercial
                            </div>
                            <h2 class="text-4xl font-bold mb-4">Bienvenue</h2>
                            <p class="text-lg text-white/80 mb-10 leading-relaxed">
                                Gérez vos missions, trouvez les meilleurs talents et suivez vos candidatures.
                            </p>
                            <div
                                class="space-y-4 text-left bg-white/10 backdrop-blur-sm rounded-2xl p-6 border border-white/20">
                                <div class="flex items-center gap-4">
                                    <div
                                        class="w-10 h-10 rounded-xl bg-white/20 flex items-center justify-center flex-shrink-0">
                                        <svg class="w-5 h-5" fill="none" stroke="currentColor" viewBox="0 0 24 24">
                                            <path stroke-linecap="round" stroke-linejoin="round" stroke-width="2"
                                                d="M12 6v6m0 0v6m0-6h6m-6 0H6" />
                                        </svg>
                                    </div>
                                    <span class="text-white/90">Publiez vos missions</span>
                                </div>
                                <div class="flex items-center gap-4">
                                    <div
                                        class="w-10 h-10 rounded-xl bg-white/20 flex items-center justify-center flex-shrink-0">
                                        <svg class="w-5 h-5" fill="none" stroke="currentColor" viewBox="0 0 24 24">
                                            <path stroke-linecap="round" stroke-linejoin="round" stroke-width="2"
                                                d="M17 20h5v-2a3 3 0 00-5.356-1.857M17 20H7m10 0v-2c0-.656-.126-1.283-.356-1.857M7 20H2v-2a3 3 0 015.356-1.857M7 20v-2c0-.656.126-1.283.356-1.857m0 0a5.002 5.002 0 019.288 0M15 7a3 3 0 11-6 0 3 3 0 016 0zm6 3a2 2 0 11-4 0 2 2 0 014 0zM7 10a2 2 0 11-4 0 2 2 0 014 0z" />
                                        </svg>
                                    </div>
                                    <span class="text-white/90">Gérez les candidatures</span>
                                </div>
                                <div class="flex items-center gap-4">
                                    <div
                                        class="w-10 h-10 rounded-xl bg-white/20 flex items-center justify-center flex-shrink-0">
                                        <svg class="w-5 h-5" fill="none" stroke="currentColor" viewBox="0 0 24 24">
                                            <path stroke-linecap="round" stroke-linejoin="round" stroke-width="2"
                                                d="M8 12h.01M12 12h.01M16 12h.01M21 12c0 4.418-4.03 8-9 8a9.863 9.863 0 01-4.255-.949L3 20l1.395-3.72C3.512 15.042 3 13.574 3 12c0-4.418 4.03-8 9-8s9 3.582 9 8z" />
                                        </svg>
                                    </div>
                                    <span class="text-white/90">Contactez les consultants</span>
                                </div>
                            </div>
                        @endif
                    </div>
                </div>

                {{-- Bottom gradient --}}
                <div class="absolute bottom-0 left-0 w-full h-32" style="background-color: rgba(0,0,0,0.1);"></div>
            </div>

            {{-- Right side - Form --}}
            <div class="w-full lg:w-1/2 flex flex-col justify-center items-center p-6 sm:p-12 bg-slate-50">
                <div class="w-full max-w-md">
                    {{-- Mobile logo --}}
                    <div class="lg:hidden mb-8 text-center">
                        <a href="/" class="inline-block">
                            @if($siteSettings->logo_url)
                                <img src="{{ $siteSettings->logo_url }}" alt="{{ $siteSettings->site_name }}"
                                    class="h-12 w-auto mx-auto">
                            @else
                                <div class="flex items-center justify-center gap-2" style="color: var(--theme-primary);">
                                    <div class="w-10 h-10 rounded-xl flex items-center justify-center"
                                        style="background-color: var(--theme-primary);">
                                        <svg class="w-6 h-6 text-white" fill="none" stroke="currentColor" viewBox="0 0 24 24">
                                            <path stroke-linecap="round" stroke-linejoin="round" stroke-width="2"
                                                d="M13 10V3L4 14h7v7l9-11h-7z" />
                                        </svg>
                                    </div>
                                    <span class="text-2xl font-bold">{{ $siteSettings->site_name }}</span>
                                </div>
                            @endif
                        </a>
                        <div class="mt-3 inline-flex items-center gap-2 px-3 py-1.5 rounded-full text-xs font-medium"
                            style="background-color: color-mix(in srgb, var(--theme-primary) 10%, transparent); color: var(--theme-primary);">
                            {{ $theme === 'consultant' ? 'Espace Consultant' : 'Espace Commercial' }}
                        </div>
                    </div>

                    {{-- Form card --}}
                    <div class="bg-white shadow-xl shadow-slate-200/50 rounded-3xl p-8 sm:p-10 gradient-border">
                        {{ $slot }}
                    </div>

                    {{-- Back link --}}
                    <p class="mt-8 text-center">
                        <a href="/"
                            class="inline-flex items-center gap-2 text-sm font-medium text-slate-500 hover:text-slate-900 transition-colors group">
                            <svg class="w-4 h-4 group-hover:-translate-x-1 transition-transform" fill="none"
                                stroke="currentColor" viewBox="0 0 24 24">
                                <path stroke-linecap="round" stroke-linejoin="round" stroke-width="2"
                                    d="M10 19l-7-7m0 0l7-7m-7 7h18" />
                            </svg>
                            Retour à l'accueil
                        </a>
                    </p>
                </div>
            </div>
        </div>
    @else
        {{-- Default layout --}}
        <div class="min-h-screen flex flex-col sm:justify-center items-center pt-6 sm:pt-0 bg-slate-100">
            <div class="mb-6">
                <a href="/" wire:navigate class="group">
                    @if($siteSettings->logo_url)
                        <img src="{{ $siteSettings->logo_url }}" alt="{{ $siteSettings->site_name }}"
                            class="h-16 w-auto group-hover:scale-105 transition-transform">
                    @else
                        <div class="flex items-center gap-3 group-hover:scale-105 transition-transform">
                            <div class="w-12 h-12 rounded-xl flex items-center justify-center"
                                style="background-color: var(--theme-primary, #3b82f6);">
                                <svg class="w-7 h-7 text-white" fill="none" stroke="currentColor" viewBox="0 0 24 24">
                                    <path stroke-linecap="round" stroke-linejoin="round" stroke-width="2"
                                        d="M13 10V3L4 14h7v7l9-11h-7z" />
                                </svg>
                            </div>
                            <span class="text-2xl font-bold text-slate-900">{{ $siteSettings->site_name }}</span>
                        </div>
                    @endif
                </a>
            </div>

            <div class="w-full sm:max-w-md px-6 py-8 bg-white shadow-xl shadow-slate-200/50 rounded-3xl overflow-hidden">
                {{ $slot }}
            </div>

            <p class="mt-6">
                <a href="/"
                    class="inline-flex items-center gap-2 text-sm font-medium text-slate-500 hover:text-slate-900 transition-colors group">
                    <svg class="w-4 h-4 group-hover:-translate-x-1 transition-transform" fill="none" stroke="currentColor"
                        viewBox="0 0 24 24">
                        <path stroke-linecap="round" stroke-linejoin="round" stroke-width="2"
                            d="M10 19l-7-7m0 0l7-7m-7 7h18" />
                    </svg>
                    Retour à l'accueil
                </a>
            </p>
        </div>
    @endisset
</body>

</html>