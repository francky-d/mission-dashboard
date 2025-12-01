@php
    $siteSettings = \App\Models\SiteSettings::instance();
@endphp
<!DOCTYPE html>
<html lang="{{ str_replace('_', '-', app()->getLocale()) }}">

<head>
    <meta charset="utf-8">
    <meta name="viewport" content="width=device-width, initial-scale=1">

    <title>{{ $siteSettings->site_name }} - Plateforme de Missions IT</title>

    <!-- Fonts -->
    <link rel="preconnect" href="https://fonts.bunny.net">
    <link href="https://fonts.bunny.net/css?family=figtree:400,500,600,700&display=swap" rel="stylesheet" />

    <!-- Styles -->
    @vite(['resources/css/app.css', 'resources/js/app.js'])

    <style>
        :root {
            --consultant-primary:
                {{ $siteSettings->consultant_primary_color }}
            ;
            --consultant-secondary:
                {{ $siteSettings->consultant_secondary_color }}
            ;
            --consultant-accent:
                {{ $siteSettings->consultant_accent_color }}
            ;
            --commercial-primary:
                {{ $siteSettings->commercial_primary_color }}
            ;
            --commercial-secondary:
                {{ $siteSettings->commercial_secondary_color }}
            ;
            --commercial-accent:
                {{ $siteSettings->commercial_accent_color }}
            ;
        }
    </style>
</head>

<body class="antialiased font-sans bg-gray-50">
    <!-- Navigation -->
    <nav class="bg-white shadow-sm border-b border-gray-100">
        <div class="max-w-7xl mx-auto px-4 sm:px-6 lg:px-8">
            <div class="flex justify-between h-16">
                <div class="flex items-center">
                    @if($siteSettings->logo_url)
                        <img src="{{ $siteSettings->logo_url }}" alt="{{ $siteSettings->site_name }}" class="h-10 w-auto">
                    @else
                        <span class="text-xl font-bold text-gray-900">{{ $siteSettings->site_name }}</span>
                    @endif
                </div>
                <div class="flex items-center gap-4">
                    @auth
                        <a href="{{ route('dashboard') }}"
                            class="text-gray-600 hover:text-gray-900 font-medium">Dashboard</a>
                    @else
                        <a href="{{ route('login') }}" class="text-gray-600 hover:text-gray-900 font-medium">Connexion</a>
                    @endauth
                </div>
            </div>
        </div>
    </nav>

    <!-- Hero Section -->
    <section class="relative overflow-hidden bg-gradient-to-br from-gray-900 via-gray-800 to-gray-900 py-24 sm:py-32">
        <div class="absolute inset-0 bg-[url('data:image/svg+xml,%3Csvg width=\" 60\" height=\"60\" viewBox=\"0 0 60
            60\" xmlns=\"http://www.w3.org/2000/svg\"%3E%3Cg fill=\"none\" fill-rule=\"evenodd\"%3E%3Cg
            fill=\"%239C92AC\" fill-opacity=\"0.05\"%3E%3Cpath d=\"M36
            34v-4h-2v4h-4v2h4v4h2v-4h4v-2h-4zm0-30V0h-2v4h-4v2h4v4h2V6h4V4h-4zM6 34v-4H4v4H0v2h4v4h2v-4h4v-2H6zM6
            4V0H4v4H0v2h4v4h2V6h4V4H6z\"/%3E%3C/g%3E%3C/g%3E%3C/svg%3E')] opacity-40"></div>
        <div class="relative max-w-7xl mx-auto px-4 sm:px-6 lg:px-8">
            <div class="text-center">
                <h1 class="text-4xl sm:text-5xl lg:text-6xl font-bold text-white tracking-tight">
                    Trouvez votre prochaine
                    <span class="block mt-2 bg-gradient-to-r from-blue-400 to-orange-400 bg-clip-text text-transparent">
                        mission IT
                    </span>
                </h1>
                <p class="mt-6 max-w-2xl mx-auto text-lg sm:text-xl text-gray-300">
                    La plateforme qui connecte les consultants IT aux meilleures opportunités de missions.
                    Simple, efficace et transparent.
                </p>
            </div>
        </div>
    </section>

    <!-- Role Selection Section -->
    <section class="py-16 sm:py-24">
        <div class="max-w-7xl mx-auto px-4 sm:px-6 lg:px-8">
            <div class="text-center mb-12">
                <h2 class="text-3xl font-bold text-gray-900">Rejoignez-nous</h2>
                <p class="mt-4 text-lg text-gray-600">Choisissez votre profil pour commencer</p>
            </div>

            <div class="grid md:grid-cols-2 gap-8 max-w-5xl mx-auto">
                <!-- Consultant Card -->
                <div
                    class="group relative bg-white rounded-2xl shadow-xl overflow-hidden border-2 border-transparent hover:border-blue-500 transition-all duration-300">
                    <div class="absolute inset-0 bg-gradient-to-br from-blue-500/5 to-blue-600/5"></div>
                    <div class="relative p-8">
                        <div class="w-16 h-16 rounded-xl bg-blue-500/10 flex items-center justify-center mb-6">
                            <svg class="w-8 h-8 text-blue-600" fill="none" stroke="currentColor" viewBox="0 0 24 24">
                                <path stroke-linecap="round" stroke-linejoin="round" stroke-width="2"
                                    d="M16 7a4 4 0 11-8 0 4 4 0 018 0zM12 14a7 7 0 00-7 7h14a7 7 0 00-7-7z" />
                            </svg>
                        </div>
                        <h3 class="text-2xl font-bold text-gray-900 mb-3">Je suis Consultant</h3>
                        <p class="text-gray-600 mb-6">
                            Accédez aux meilleures missions IT, gérez vos candidatures et développez votre carrière.
                        </p>
                        <ul class="space-y-3 mb-8">
                            <li class="flex items-center text-gray-700">
                                <svg class="w-5 h-5 text-blue-500 mr-3" fill="currentColor" viewBox="0 0 20 20">
                                    <path fill-rule="evenodd"
                                        d="M16.707 5.293a1 1 0 010 1.414l-8 8a1 1 0 01-1.414 0l-4-4a1 1 0 011.414-1.414L8 12.586l7.293-7.293a1 1 0 011.414 0z"
                                        clip-rule="evenodd" />
                                </svg>
                                Parcourez les missions disponibles
                            </li>
                            <li class="flex items-center text-gray-700">
                                <svg class="w-5 h-5 text-blue-500 mr-3" fill="currentColor" viewBox="0 0 20 20">
                                    <path fill-rule="evenodd"
                                        d="M16.707 5.293a1 1 0 010 1.414l-8 8a1 1 0 01-1.414 0l-4-4a1 1 0 011.414-1.414L8 12.586l7.293-7.293a1 1 0 011.414 0z"
                                        clip-rule="evenodd" />
                                </svg>
                                Postulez en un clic
                            </li>
                            <li class="flex items-center text-gray-700">
                                <svg class="w-5 h-5 text-blue-500 mr-3" fill="currentColor" viewBox="0 0 20 20">
                                    <path fill-rule="evenodd"
                                        d="M16.707 5.293a1 1 0 010 1.414l-8 8a1 1 0 01-1.414 0l-4-4a1 1 0 011.414-1.414L8 12.586l7.293-7.293a1 1 0 011.414 0z"
                                        clip-rule="evenodd" />
                                </svg>
                                Échangez avec les commerciaux
                            </li>
                        </ul>
                        <div class="flex flex-col sm:flex-row gap-3">
                            <a href="{{ route('consultant.register') }}"
                                class="flex-1 inline-flex justify-center items-center px-6 py-3 rounded-lg text-white font-semibold transition-all duration-200 shadow-lg hover:shadow-xl"
                                style="background-color: var(--consultant-primary);">
                                S'inscrire
                                <svg class="w-5 h-5 ml-2" fill="none" stroke="currentColor" viewBox="0 0 24 24">
                                    <path stroke-linecap="round" stroke-linejoin="round" stroke-width="2"
                                        d="M13 7l5 5m0 0l-5 5m5-5H6" />
                                </svg>
                            </a>
                            <a href="{{ route('consultant.login') }}"
                                class="flex-1 inline-flex justify-center items-center px-6 py-3 rounded-lg font-semibold border-2 transition-all duration-200"
                                style="border-color: var(--consultant-primary); color: var(--consultant-primary);">
                                Connexion
                            </a>
                        </div>
                    </div>
                </div>

                <!-- Commercial Card -->
                <div
                    class="group relative bg-white rounded-2xl shadow-xl overflow-hidden border-2 border-transparent hover:border-orange-500 transition-all duration-300">
                    <div class="absolute inset-0 bg-gradient-to-br from-orange-500/5 to-orange-600/5"></div>
                    <div class="relative p-8">
                        <div class="w-16 h-16 rounded-xl bg-orange-500/10 flex items-center justify-center mb-6">
                            <svg class="w-8 h-8 text-orange-600" fill="none" stroke="currentColor" viewBox="0 0 24 24">
                                <path stroke-linecap="round" stroke-linejoin="round" stroke-width="2"
                                    d="M21 13.255A23.931 23.931 0 0112 15c-3.183 0-6.22-.62-9-1.745M16 6V4a2 2 0 00-2-2h-4a2 2 0 00-2 2v2m4 6h.01M5 20h14a2 2 0 002-2V8a2 2 0 00-2-2H5a2 2 0 00-2 2v10a2 2 0 002 2z" />
                            </svg>
                        </div>
                        <h3 class="text-2xl font-bold text-gray-900 mb-3">Je suis Commercial</h3>
                        <p class="text-gray-600 mb-6">
                            Publiez vos missions, trouvez les meilleurs talents et gérez vos recrutements efficacement.
                        </p>
                        <ul class="space-y-3 mb-8">
                            <li class="flex items-center text-gray-700">
                                <svg class="w-5 h-5 text-orange-500 mr-3" fill="currentColor" viewBox="0 0 20 20">
                                    <path fill-rule="evenodd"
                                        d="M16.707 5.293a1 1 0 010 1.414l-8 8a1 1 0 01-1.414 0l-4-4a1 1 0 011.414-1.414L8 12.586l7.293-7.293a1 1 0 011.414 0z"
                                        clip-rule="evenodd" />
                                </svg>
                                Publiez vos missions
                            </li>
                            <li class="flex items-center text-gray-700">
                                <svg class="w-5 h-5 text-orange-500 mr-3" fill="currentColor" viewBox="0 0 20 20">
                                    <path fill-rule="evenodd"
                                        d="M16.707 5.293a1 1 0 010 1.414l-8 8a1 1 0 01-1.414 0l-4-4a1 1 0 011.414-1.414L8 12.586l7.293-7.293a1 1 0 011.414 0z"
                                        clip-rule="evenodd" />
                                </svg>
                                Gérez les candidatures
                            </li>
                            <li class="flex items-center text-gray-700">
                                <svg class="w-5 h-5 text-orange-500 mr-3" fill="currentColor" viewBox="0 0 20 20">
                                    <path fill-rule="evenodd"
                                        d="M16.707 5.293a1 1 0 010 1.414l-8 8a1 1 0 01-1.414 0l-4-4a1 1 0 011.414-1.414L8 12.586l7.293-7.293a1 1 0 011.414 0z"
                                        clip-rule="evenodd" />
                                </svg>
                                Contactez les consultants
                            </li>
                        </ul>
                        <div class="flex flex-col sm:flex-row gap-3">
                            <a href="{{ route('commercial.register') }}"
                                class="flex-1 inline-flex justify-center items-center px-6 py-3 rounded-lg text-white font-semibold transition-all duration-200 shadow-lg hover:shadow-xl"
                                style="background-color: var(--commercial-primary);">
                                S'inscrire
                                <svg class="w-5 h-5 ml-2" fill="none" stroke="currentColor" viewBox="0 0 24 24">
                                    <path stroke-linecap="round" stroke-linejoin="round" stroke-width="2"
                                        d="M13 7l5 5m0 0l-5 5m5-5H6" />
                                </svg>
                            </a>
                            <a href="{{ route('commercial.login') }}"
                                class="flex-1 inline-flex justify-center items-center px-6 py-3 rounded-lg font-semibold border-2 transition-all duration-200"
                                style="border-color: var(--commercial-primary); color: var(--commercial-primary);">
                                Connexion
                            </a>
                        </div>
                    </div>
                </div>
            </div>
        </div>
    </section>

    <!-- Features Section -->
    <section class="py-16 sm:py-24 bg-white">
        <div class="max-w-7xl mx-auto px-4 sm:px-6 lg:px-8">
            <div class="text-center mb-16">
                <h2 class="text-3xl font-bold text-gray-900">Pourquoi nous choisir ?</h2>
                <p class="mt-4 text-lg text-gray-600">Une plateforme conçue pour simplifier votre quotidien</p>
            </div>

            <div class="grid md:grid-cols-3 gap-8">
                <div class="text-center p-6">
                    <div class="w-14 h-14 mx-auto rounded-xl bg-blue-100 flex items-center justify-center mb-4">
                        <svg class="w-7 h-7 text-blue-600" fill="none" stroke="currentColor" viewBox="0 0 24 24">
                            <path stroke-linecap="round" stroke-linejoin="round" stroke-width="2"
                                d="M13 10V3L4 14h7v7l9-11h-7z" />
                        </svg>
                    </div>
                    <h3 class="text-xl font-semibold text-gray-900 mb-2">Rapide & Efficace</h3>
                    <p class="text-gray-600">Trouvez ou publiez des missions en quelques clics. Interface intuitive et
                        processus simplifié.</p>
                </div>

                <div class="text-center p-6">
                    <div class="w-14 h-14 mx-auto rounded-xl bg-orange-100 flex items-center justify-center mb-4">
                        <svg class="w-7 h-7 text-orange-600" fill="none" stroke="currentColor" viewBox="0 0 24 24">
                            <path stroke-linecap="round" stroke-linejoin="round" stroke-width="2"
                                d="M17 8h2a2 2 0 012 2v6a2 2 0 01-2 2h-2v4l-4-4H9a1.994 1.994 0 01-1.414-.586m0 0L11 14h4a2 2 0 002-2V6a2 2 0 00-2-2H5a2 2 0 00-2 2v6a2 2 0 002 2h2v4l.586-.586z" />
                        </svg>
                    </div>
                    <h3 class="text-xl font-semibold text-gray-900 mb-2">Communication Directe</h3>
                    <p class="text-gray-600">Messagerie intégrée pour échanger directement entre consultants et
                        commerciaux.</p>
                </div>

                <div class="text-center p-6">
                    <div class="w-14 h-14 mx-auto rounded-xl bg-green-100 flex items-center justify-center mb-4">
                        <svg class="w-7 h-7 text-green-600" fill="none" stroke="currentColor" viewBox="0 0 24 24">
                            <path stroke-linecap="round" stroke-linejoin="round" stroke-width="2"
                                d="M9 12l2 2 4-4m5.618-4.016A11.955 11.955 0 0112 2.944a11.955 11.955 0 01-8.618 3.04A12.02 12.02 0 003 9c0 5.591 3.824 10.29 9 11.622 5.176-1.332 9-6.03 9-11.622 0-1.042-.133-2.052-.382-3.016z" />
                        </svg>
                    </div>
                    <h3 class="text-xl font-semibold text-gray-900 mb-2">Sécurisé</h3>
                    <p class="text-gray-600">Vos données sont protégées. Accès réservé aux membres de votre
                        organisation.</p>
                </div>
            </div>
        </div>
    </section>

    <!-- Footer -->
    <footer class="bg-gray-900 py-12">
        <div class="max-w-7xl mx-auto px-4 sm:px-6 lg:px-8">
            <div class="text-center">
                <p class="text-gray-400">
                    &copy; {{ date('Y') }} {{ $siteSettings->site_name }}. Tous droits réservés.
                </p>
            </div>
        </div>
    </footer>
</body>

</html>