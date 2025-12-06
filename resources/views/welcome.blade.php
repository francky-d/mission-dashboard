<!DOCTYPE html>
<html lang="{{ str_replace('_', '-', app()->getLocale()) }}">

<head>
    <meta charset="utf-8">
    <meta name="viewport" content="width=device-width, initial-scale=1">
    <meta name="csrf-token" content="{{ csrf_token() }}">
    <title>{{ $siteSettings['site_name'] ?? config('app.name', 'Mission Dashboard') }}</title>

    <!-- Fonts -->
    <link rel="preconnect" href="https://fonts.bunny.net">
    <link href="https://fonts.bunny.net/css?family=figtree:400,500,600,700&display=swap" rel="stylesheet" />

    <!-- Styles -->
    @vite(['resources/css/app.css', 'resources/js/app.js'])

    <style>
        :root {
            --consultant-primary:
                {{ $siteSettings['consultant_primary_color'] ?? '#3B82F6' }}
            ;
            --consultant-secondary:
                {{ $siteSettings['consultant_secondary_color'] ?? '#1E40AF' }}
            ;
            --commercial-primary:
                {{ $siteSettings['commercial_primary_color'] ?? '#F97316' }}
            ;
            --commercial-secondary:
                {{ $siteSettings['commercial_secondary_color'] ?? '#C2410C' }}
            ;
        }
    </style>
</head>

<body class="antialiased bg-slate-50 min-h-screen">
    <!-- Navigation -->
    <nav class="fixed top-0 inset-x-0 z-50 bg-white shadow-sm border-b border-slate-200">
        <div class="max-w-7xl mx-auto px-4 sm:px-6 lg:px-8">
            <div class="flex justify-between h-16">
                <div class="flex items-center">
                    @if(!empty($siteSettings['logo_url']))
                        <img src="{{ $siteSettings['logo_url'] }}" alt="{{ $siteSettings['site_name'] ?? 'Logo' }}"
                            class="h-10 w-auto">
                    @else
                        <span
                            class="text-xl font-bold text-slate-900">{{ $siteSettings['site_name'] ?? 'Mission Dashboard' }}</span>
                    @endif
                </div>
                <div class="flex items-center gap-4">
                    @if (Route::has('login'))
                        @auth
                            <a href="{{ url('/dashboard') }}"
                                class="inline-flex items-center px-4 py-2 bg-blue-600 text-white text-sm font-medium rounded-lg hover:bg-blue-700 transition-colors">
                                Tableau de bord
                            </a>
                        @else
                            <a href="{{ route('login') }}" class="text-slate-600 hover:text-slate-900 font-medium">
                                Connexion
                            </a>
                            @if (Route::has('register'))
                                <a href="{{ route('register') }}"
                                    class="inline-flex items-center px-4 py-2 bg-blue-600 text-white text-sm font-medium rounded-lg hover:bg-blue-700 transition-colors">
                                    S'inscrire
                                </a>
                            @endif
                        @endauth
                    @endif
                </div>
            </div>
        </div>
    </nav>

    <!-- Main Content -->
    <main class="pt-24 pb-16">
        <!-- Hero Section - Simple -->
        <section class="max-w-7xl mx-auto px-4 sm:px-6 lg:px-8 text-center mb-20">
            <h1 class="text-4xl sm:text-5xl font-bold text-slate-900 mb-6">
                Bienvenue sur <span class="text-blue-600">{{ $siteSettings['site_name'] ?? 'Mission Dashboard' }}</span>
            </h1>
            <p class="text-xl text-slate-600 max-w-2xl mx-auto">
                Votre plateforme interne de gestion des missions et des consultants.
            </p>
        </section>

        <!-- Comment ça marche -->
        <section class="max-w-7xl mx-auto px-4 sm:px-6 lg:px-8 mb-20">
            <div class="text-center mb-12">
                <h2 class="text-3xl font-bold text-slate-900 mb-4">Comment ça marche ?</h2>
                <p class="text-slate-600 max-w-2xl mx-auto">Une plateforme simple et efficace pour connecter consultants
                    et commerciaux.</p>
            </div>

            <div class="grid md:grid-cols-3 gap-8">
                <!-- Étape 1 -->
                <div class="bg-white rounded-xl shadow-lg p-8 text-center">
                    <div
                        class="w-16 h-16 bg-blue-100 text-blue-600 rounded-full flex items-center justify-center mx-auto mb-6">
                        <span class="text-2xl font-bold">1</span>
                    </div>
                    <h3 class="text-xl font-semibold text-slate-900 mb-3">Créez votre compte</h3>
                    <p class="text-slate-600">Inscrivez-vous en tant que consultant ou commercial et complétez votre
                        profil.</p>
                </div>

                <!-- Étape 2 -->
                <div class="bg-white rounded-xl shadow-lg p-8 text-center">
                    <div
                        class="w-16 h-16 bg-orange-100 text-orange-600 rounded-full flex items-center justify-center mx-auto mb-6">
                        <span class="text-2xl font-bold">2</span>
                    </div>
                    <h3 class="text-xl font-semibold text-slate-900 mb-3">Explorez les opportunités</h3>
                    <p class="text-slate-600">Les consultants découvrent les missions, les commerciaux publient leurs
                        offres.</p>
                </div>

                <!-- Étape 3 -->
                <div class="bg-white rounded-xl shadow-lg p-8 text-center">
                    <div
                        class="w-16 h-16 bg-green-100 text-green-600 rounded-full flex items-center justify-center mx-auto mb-6">
                        <span class="text-2xl font-bold">3</span>
                    </div>
                    <h3 class="text-xl font-semibold text-slate-900 mb-3">Collaborez efficacement</h3>
                    <p class="text-slate-600">Échangez via la messagerie intégrée et suivez vos candidatures en temps
                        réel.</p>
                </div>
            </div>
        </section>

        <!-- Les deux espaces -->
        <section class="max-w-7xl mx-auto px-4 sm:px-6 lg:px-8">
            <div class="text-center mb-12">
                <h2 class="text-3xl font-bold text-slate-900 mb-4">Deux espaces dédiés</h2>
                <p class="text-slate-600 max-w-2xl mx-auto">Choisissez l'espace qui correspond à votre rôle au sein de
                    l'ESN.</p>
            </div>

            <div class="grid md:grid-cols-2 gap-8 max-w-4xl mx-auto">
                <!-- Espace Consultant -->
                <div
                    class="bg-white rounded-2xl shadow-lg overflow-hidden border border-slate-200 hover:shadow-xl transition-shadow">
                    <div class="h-3" style="background-color: var(--consultant-primary);"></div>
                    <div class="p-8">
                        <div class="w-16 h-16 rounded-xl flex items-center justify-center mb-6"
                            style="background-color: var(--consultant-primary);">
                            <svg class="w-8 h-8 text-white" fill="none" stroke="currentColor" viewBox="0 0 24 24">
                                <path stroke-linecap="round" stroke-linejoin="round" stroke-width="2"
                                    d="M16 7a4 4 0 11-8 0 4 4 0 018 0zM12 14a7 7 0 00-7 7h14a7 7 0 00-7-7z" />
                            </svg>
                        </div>
                        <h3 class="text-2xl font-bold text-slate-900 mb-4">Espace Consultant</h3>
                        <p class="text-slate-600 mb-6">Accédez aux missions disponibles, gérez votre profil et vos
                            compétences, suivez vos candidatures.</p>
                        <ul class="space-y-3 mb-8">
                            <li class="flex items-center text-slate-700">
                                <svg class="w-5 h-5 mr-3" style="color: var(--consultant-primary);" fill="currentColor"
                                    viewBox="0 0 20 20">
                                    <path fill-rule="evenodd"
                                        d="M16.707 5.293a1 1 0 010 1.414l-8 8a1 1 0 01-1.414 0l-4-4a1 1 0 011.414-1.414L8 12.586l7.293-7.293a1 1 0 011.414 0z"
                                        clip-rule="evenodd" />
                                </svg>
                                Parcourir les missions
                            </li>
                            <li class="flex items-center text-slate-700">
                                <svg class="w-5 h-5 mr-3" style="color: var(--consultant-primary);" fill="currentColor"
                                    viewBox="0 0 20 20">
                                    <path fill-rule="evenodd"
                                        d="M16.707 5.293a1 1 0 010 1.414l-8 8a1 1 0 01-1.414 0l-4-4a1 1 0 011.414-1.414L8 12.586l7.293-7.293a1 1 0 011.414 0z"
                                        clip-rule="evenodd" />
                                </svg>
                                Gérer votre profil
                            </li>
                            <li class="flex items-center text-slate-700">
                                <svg class="w-5 h-5 mr-3" style="color: var(--consultant-primary);" fill="currentColor"
                                    viewBox="0 0 20 20">
                                    <path fill-rule="evenodd"
                                        d="M16.707 5.293a1 1 0 010 1.414l-8 8a1 1 0 01-1.414 0l-4-4a1 1 0 011.414-1.414L8 12.586l7.293-7.293a1 1 0 011.414 0z"
                                        clip-rule="evenodd" />
                                </svg>
                                Suivre vos candidatures
                            </li>
                        </ul>
                        @auth
                            @if(auth()->user()->hasRole('consultant'))
                                <a href="{{ route('consultant.dashboard') }}"
                                    class="inline-flex items-center justify-center w-full px-6 py-3 text-white font-semibold rounded-lg transition-colors"
                                    style="background-color: var(--consultant-primary);">
                                    Accéder à mon espace
                                    <svg class="w-5 h-5 ml-2" fill="none" stroke="currentColor" viewBox="0 0 24 24">
                                        <path stroke-linecap="round" stroke-linejoin="round" stroke-width="2"
                                            d="M13 7l5 5m0 0l-5 5m5-5H6" />
                                    </svg>
                                </a>
                            @endif
                        @else
                            <a href="{{ route('register') }}"
                                class="inline-flex items-center justify-center w-full px-6 py-3 text-white font-semibold rounded-lg transition-colors"
                                style="background-color: var(--consultant-primary);">
                                S'inscrire comme consultant
                                <svg class="w-5 h-5 ml-2" fill="none" stroke="currentColor" viewBox="0 0 24 24">
                                    <path stroke-linecap="round" stroke-linejoin="round" stroke-width="2"
                                        d="M13 7l5 5m0 0l-5 5m5-5H6" />
                                </svg>
                            </a>
                        @endauth
                    </div>
                </div>

                <!-- Espace Commercial -->
                <div
                    class="bg-white rounded-2xl shadow-lg overflow-hidden border border-slate-200 hover:shadow-xl transition-shadow">
                    <div class="h-3" style="background-color: var(--commercial-primary);"></div>
                    <div class="p-8">
                        <div class="w-16 h-16 rounded-xl flex items-center justify-center mb-6"
                            style="background-color: var(--commercial-primary);">
                            <svg class="w-8 h-8 text-white" fill="none" stroke="currentColor" viewBox="0 0 24 24">
                                <path stroke-linecap="round" stroke-linejoin="round" stroke-width="2"
                                    d="M21 13.255A23.931 23.931 0 0112 15c-3.183 0-6.22-.62-9-1.745M16 6V4a2 2 0 00-2-2h-4a2 2 0 00-2 2v2m4 6h.01M5 20h14a2 2 0 002-2V8a2 2 0 00-2-2H5a2 2 0 00-2 2v10a2 2 0 002 2z" />
                            </svg>
                        </div>
                        <h3 class="text-2xl font-bold text-slate-900 mb-4">Espace Commercial</h3>
                        <p class="text-slate-600 mb-6">Publiez vos missions, trouvez les consultants idéaux, gérez les
                            candidatures reçues.</p>
                        <ul class="space-y-3 mb-8">
                            <li class="flex items-center text-slate-700">
                                <svg class="w-5 h-5 mr-3" style="color: var(--commercial-primary);" fill="currentColor"
                                    viewBox="0 0 20 20">
                                    <path fill-rule="evenodd"
                                        d="M16.707 5.293a1 1 0 010 1.414l-8 8a1 1 0 01-1.414 0l-4-4a1 1 0 011.414-1.414L8 12.586l7.293-7.293a1 1 0 011.414 0z"
                                        clip-rule="evenodd" />
                                </svg>
                                Publier des missions
                            </li>
                            <li class="flex items-center text-slate-700">
                                <svg class="w-5 h-5 mr-3" style="color: var(--commercial-primary);" fill="currentColor"
                                    viewBox="0 0 20 20">
                                    <path fill-rule="evenodd"
                                        d="M16.707 5.293a1 1 0 010 1.414l-8 8a1 1 0 01-1.414 0l-4-4a1 1 0 011.414-1.414L8 12.586l7.293-7.293a1 1 0 011.414 0z"
                                        clip-rule="evenodd" />
                                </svg>
                                Rechercher des profils
                            </li>
                            <li class="flex items-center text-slate-700">
                                <svg class="w-5 h-5 mr-3" style="color: var(--commercial-primary);" fill="currentColor"
                                    viewBox="0 0 20 20">
                                    <path fill-rule="evenodd"
                                        d="M16.707 5.293a1 1 0 010 1.414l-8 8a1 1 0 01-1.414 0l-4-4a1 1 0 011.414-1.414L8 12.586l7.293-7.293a1 1 0 011.414 0z"
                                        clip-rule="evenodd" />
                                </svg>
                                Gérer les candidatures
                            </li>
                        </ul>
                        @auth
                            @if(auth()->user()->hasRole('commercial'))
                                <a href="{{ route('commercial.dashboard') }}"
                                    class="inline-flex items-center justify-center w-full px-6 py-3 text-white font-semibold rounded-lg transition-colors"
                                    style="background-color: var(--commercial-primary);">
                                    Accéder à mon espace
                                    <svg class="w-5 h-5 ml-2" fill="none" stroke="currentColor" viewBox="0 0 24 24">
                                        <path stroke-linecap="round" stroke-linejoin="round" stroke-width="2"
                                            d="M13 7l5 5m0 0l-5 5m5-5H6" />
                                    </svg>
                                </a>
                            @endif
                        @else
                            <a href="{{ route('register') }}"
                                class="inline-flex items-center justify-center w-full px-6 py-3 text-white font-semibold rounded-lg transition-colors"
                                style="background-color: var(--commercial-primary);">
                                S'inscrire comme commercial
                                <svg class="w-5 h-5 ml-2" fill="none" stroke="currentColor" viewBox="0 0 24 24">
                                    <path stroke-linecap="round" stroke-linejoin="round" stroke-width="2"
                                        d="M13 7l5 5m0 0l-5 5m5-5H6" />
                                </svg>
                            </a>
                        @endauth
                    </div>
                </div>
            </div>
        </section>
    </main>

    <!-- Footer Simple -->
    <footer class="bg-white border-t border-slate-200">
        <div class="max-w-7xl mx-auto px-4 sm:px-6 lg:px-8 py-8">
            <div class="text-center text-slate-600">
                <p>&copy; {{ date('Y') }} {{ $siteSettings['site_name'] ?? config('app.name') }}. Tous droits réservés.
                </p>
            </div>
        </div>
    </footer>
</body>

</html>