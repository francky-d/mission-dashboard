@php
    $siteSettings = \App\Models\SiteSettings::instance();
@endphp
<!DOCTYPE html>
<html lang="{{ str_replace('_', '-', app()->getLocale()) }}" class="scroll-smooth">

<head>
    <meta charset="utf-8">
    <meta name="viewport" content="width=device-width, initial-scale=1">
    <meta name="description"
        content="Plateforme de mise en relation entre consultants IT et commerciaux. Trouvez votre prochaine mission ou le talent idéal.">

    <title>{{ $siteSettings->site_name }} - Plateforme de Missions IT</title>

    <!-- Fonts -->
    <link rel="preconnect" href="https://fonts.bunny.net">
    <link href="https://fonts.bunny.net/css?family=inter:400,500,600,700,800&display=swap" rel="stylesheet" />

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

        .hero-gradient {
            background: linear-gradient(135deg, #0f172a 0%, #1e293b 50%, #0f172a 100%);
        }

        .glass-card {
            background: rgba(255, 255, 255, 0.95);
            backdrop-filter: blur(10px);
        }

        .animate-float {
            animation: float 6s ease-in-out infinite;
        }

        .animate-float-delayed {
            animation: float 6s ease-in-out infinite;
            animation-delay: 2s;
        }

        @keyframes float {

            0%,
            100% {
                transform: translateY(0px);
            }

            50% {
                transform: translateY(-20px);
            }
        }

        .gradient-text-consultant {
            background: linear-gradient(135deg, var(--consultant-primary), var(--consultant-accent));
            -webkit-background-clip: text;
            -webkit-text-fill-color: transparent;
            background-clip: text;
        }

        .gradient-text-commercial {
            background: linear-gradient(135deg, var(--commercial-primary), var(--commercial-accent));
            -webkit-background-clip: text;
            -webkit-text-fill-color: transparent;
            background-clip: text;
        }
    </style>
</head>

<body class="antialiased font-sans bg-slate-50">
    <!-- Navigation -->
    <nav
        class="fixed top-0 left-0 right-0 z-50 bg-white/80 backdrop-blur-lg border-b border-slate-200/50 transition-all duration-300">
        <div class="max-w-7xl mx-auto px-4 sm:px-6 lg:px-8">
            <div class="flex justify-between h-16">
                <div class="flex items-center">
                    <a href="/" class="flex items-center gap-3 group">
                        @if($siteSettings->logo_url)
                            <img src="{{ $siteSettings->logo_url }}" alt="{{ $siteSettings->site_name }}"
                                class="h-10 w-auto">
                        @else
                            <div
                                class="w-10 h-10 rounded-xl bg-gradient-to-br from-blue-600 to-orange-500 flex items-center justify-center">
                                <svg class="w-6 h-6 text-white" fill="none" stroke="currentColor" viewBox="0 0 24 24">
                                    <path stroke-linecap="round" stroke-linejoin="round" stroke-width="2"
                                        d="M13 10V3L4 14h7v7l9-11h-7z" />
                                </svg>
                            </div>
                            <span
                                class="text-xl font-bold text-slate-900 group-hover:text-blue-600 transition-colors">{{ $siteSettings->site_name }}</span>
                        @endif
                    </a>
                </div>
                <div class="flex items-center gap-6">
                    <a href="#features"
                        class="hidden sm:block text-slate-600 hover:text-slate-900 font-medium transition-colors">Fonctionnalités</a>
                    <a href="#roles"
                        class="hidden sm:block text-slate-600 hover:text-slate-900 font-medium transition-colors">Rejoindre</a>
                    @auth
                        <a href="{{ route('dashboard') }}"
                            class="inline-flex items-center gap-2 px-5 py-2.5 text-sm font-semibold text-white bg-gradient-to-r from-blue-600 to-blue-700 rounded-xl hover:from-blue-700 hover:to-blue-800 transition-all shadow-lg shadow-blue-500/25 hover:shadow-blue-500/40">
                            Dashboard
                            <svg class="w-4 h-4" fill="none" stroke="currentColor" viewBox="0 0 24 24">
                                <path stroke-linecap="round" stroke-linejoin="round" stroke-width="2"
                                    d="M13 7l5 5m0 0l-5 5m5-5H6" />
                            </svg>
                        </a>
                    @else
                        <a href="{{ route('login') }}"
                            class="text-slate-600 hover:text-slate-900 font-medium transition-colors">Connexion</a>
                    @endauth
                </div>
            </div>
        </div>
    </nav>

    <!-- Hero Section -->
    <section class="relative min-h-screen hero-gradient overflow-hidden pt-16">
        <!-- Animated background elements -->
        <div class="absolute inset-0">
            <div class="absolute top-20 left-10 w-72 h-72 bg-blue-500/10 rounded-full blur-3xl animate-float"></div>
            <div
                class="absolute bottom-20 right-10 w-96 h-96 bg-orange-500/10 rounded-full blur-3xl animate-float-delayed">
            </div>
            <div
                class="absolute top-1/2 left-1/2 -translate-x-1/2 -translate-y-1/2 w-[800px] h-[800px] bg-gradient-to-r from-blue-500/5 to-orange-500/5 rounded-full blur-3xl">
            </div>
        </div>

        <!-- Grid pattern overlay -->
        <div class="absolute inset-0 bg-[url('data:image/svg+xml,%3Csvg width=\" 60\" height=\"60\" viewBox=\"0 0 60
            60\" xmlns=\"http://www.w3.org/2000/svg\"%3E%3Cg fill=\"none\" fill-rule=\"evenodd\"%3E%3Cg
            fill=\"%23ffffff\" fill-opacity=\"0.03\"%3E%3Cpath d=\"M36
            34v-4h-2v4h-4v2h4v4h2v-4h4v-2h-4zm0-30V0h-2v4h-4v2h4v4h2V6h4V4h-4zM6 34v-4H4v4H0v2h4v4h2v-4h4v-2H6zM6
            4V0H4v4H0v2h4v4h2V6h4V4H6z\"/%3E%3C/g%3E%3C/g%3E%3C/svg%3E')]"></div>

        <div class="relative max-w-7xl mx-auto px-4 sm:px-6 lg:px-8 pt-20 pb-32">
            <div class="text-center max-w-4xl mx-auto">
                <!-- Badge -->
                <div
                    class="inline-flex items-center gap-2 px-4 py-2 rounded-full bg-white/10 backdrop-blur-sm border border-white/20 text-sm text-white/90 mb-8">
                    <span class="w-2 h-2 rounded-full bg-green-400 animate-pulse"></span>
                    Plateforme N°1 des missions IT en France
                </div>

                <!-- Main heading -->
                <h1 class="text-4xl sm:text-5xl lg:text-7xl font-extrabold text-white tracking-tight leading-tight">
                    Connectez
                    <span class="gradient-text-consultant">Talents</span>
                    <br class="hidden sm:block">
                    et
                    <span class="gradient-text-commercial">Opportunités</span>
                </h1>

                <p class="mt-8 text-lg sm:text-xl text-slate-300 max-w-2xl mx-auto leading-relaxed">
                    La plateforme qui révolutionne la mise en relation entre consultants IT et commerciaux.
                    Simple, efficace et transparente.
                </p>

                <!-- CTA Buttons -->
                <div class="mt-12 flex flex-col sm:flex-row gap-4 justify-center">
                    <a href="#roles"
                        class="group inline-flex items-center justify-center gap-2 px-8 py-4 text-lg font-semibold text-white bg-gradient-to-r from-blue-600 to-blue-700 rounded-2xl hover:from-blue-700 hover:to-blue-800 transition-all shadow-2xl shadow-blue-500/30 hover:shadow-blue-500/50 hover:-translate-y-1">
                        Commencer maintenant
                        <svg class="w-5 h-5 group-hover:translate-x-1 transition-transform" fill="none"
                            stroke="currentColor" viewBox="0 0 24 24">
                            <path stroke-linecap="round" stroke-linejoin="round" stroke-width="2"
                                d="M13 7l5 5m0 0l-5 5m5-5H6" />
                        </svg>
                    </a>
                    <a href="#features"
                        class="inline-flex items-center justify-center gap-2 px-8 py-4 text-lg font-semibold text-white border-2 border-white/20 rounded-2xl hover:bg-white/10 transition-all">
                        Découvrir
                    </a>
                </div>

                <!-- Stats -->
                <div class="mt-20 grid grid-cols-2 md:grid-cols-4 gap-8">
                    <div class="text-center">
                        <div class="text-3xl sm:text-4xl font-bold text-white">500+</div>
                        <div class="mt-1 text-slate-400 text-sm">Consultants actifs</div>
                    </div>
                    <div class="text-center">
                        <div class="text-3xl sm:text-4xl font-bold text-white">200+</div>
                        <div class="mt-1 text-slate-400 text-sm">Missions publiées</div>
                    </div>
                    <div class="text-center">
                        <div class="text-3xl sm:text-4xl font-bold text-white">98%</div>
                        <div class="mt-1 text-slate-400 text-sm">Satisfaction</div>
                    </div>
                    <div class="text-center">
                        <div class="text-3xl sm:text-4xl font-bold text-white">24h</div>
                        <div class="mt-1 text-slate-400 text-sm">Temps de réponse</div>
                    </div>
                </div>
            </div>
        </div>

        <!-- Wave separator -->
        <div class="absolute bottom-0 left-0 right-0">
            <svg viewBox="0 0 1440 120" fill="none" xmlns="http://www.w3.org/2000/svg" class="w-full h-auto">
                <path
                    d="M0 120L60 105C120 90 240 60 360 45C480 30 600 30 720 37.5C840 45 960 60 1080 67.5C1200 75 1320 75 1380 75L1440 75V120H1380C1320 120 1200 120 1080 120C960 120 840 120 720 120C600 120 480 120 360 120C240 120 120 120 60 120H0Z"
                    fill="#f8fafc" />
            </svg>
        </div>
    </section>

    <!-- Role Selection Section -->
    <section id="roles" class="py-24 bg-slate-50">
        <div class="max-w-7xl mx-auto px-4 sm:px-6 lg:px-8">
            <div class="text-center mb-16">
                <span
                    class="inline-block px-4 py-1.5 rounded-full bg-slate-200 text-slate-700 text-sm font-medium mb-4">Choisissez
                    votre profil</span>
                <h2 class="text-3xl sm:text-4xl font-bold text-slate-900">Rejoignez notre communauté</h2>
                <p class="mt-4 text-lg text-slate-600 max-w-2xl mx-auto">Que vous soyez consultant à la recherche de
                    missions ou commercial en quête de talents, nous avons la solution pour vous.</p>
            </div>

            <div class="grid lg:grid-cols-2 gap-8 max-w-5xl mx-auto">
                <!-- Consultant Card -->
                <div
                    class="group relative glass-card rounded-3xl shadow-xl overflow-hidden border border-slate-200 hover:border-blue-300 transition-all duration-500 hover:shadow-2xl hover:shadow-blue-500/10 hover:-translate-y-2">
                    <div class="absolute top-0 left-0 right-0 h-2"
                        style="background: linear-gradient(90deg, var(--consultant-primary), var(--consultant-accent));">
                    </div>
                    <div class="p-8 lg:p-10">
                        <div class="w-16 h-16 rounded-2xl flex items-center justify-center mb-6 transition-transform group-hover:scale-110"
                            style="background: linear-gradient(135deg, var(--consultant-primary), var(--consultant-accent));">
                            <svg class="w-8 h-8 text-white" fill="none" stroke="currentColor" viewBox="0 0 24 24">
                                <path stroke-linecap="round" stroke-linejoin="round" stroke-width="2"
                                    d="M16 7a4 4 0 11-8 0 4 4 0 018 0zM12 14a7 7 0 00-7 7h14a7 7 0 00-7-7z" />
                            </svg>
                        </div>
                        <h3 class="text-2xl font-bold text-slate-900 mb-3">Je suis Consultant</h3>
                        <p class="text-slate-600 mb-6 leading-relaxed">Accédez aux meilleures missions IT, gérez vos
                            candidatures et développez votre carrière avec les meilleurs commerciaux.</p>

                        <ul class="space-y-3 mb-8">
                            <li class="flex items-center text-slate-700">
                                <div class="w-6 h-6 rounded-full flex items-center justify-center mr-3"
                                    style="background-color: color-mix(in srgb, var(--consultant-primary) 15%, transparent);">
                                    <svg class="w-4 h-4" style="color: var(--consultant-primary);" fill="currentColor"
                                        viewBox="0 0 20 20">
                                        <path fill-rule="evenodd"
                                            d="M16.707 5.293a1 1 0 010 1.414l-8 8a1 1 0 01-1.414 0l-4-4a1 1 0 011.414-1.414L8 12.586l7.293-7.293a1 1 0 011.414 0z"
                                            clip-rule="evenodd" />
                                    </svg>
                                </div>
                                Parcourez les missions disponibles
                            </li>
                            <li class="flex items-center text-slate-700">
                                <div class="w-6 h-6 rounded-full flex items-center justify-center mr-3"
                                    style="background-color: color-mix(in srgb, var(--consultant-primary) 15%, transparent);">
                                    <svg class="w-4 h-4" style="color: var(--consultant-primary);" fill="currentColor"
                                        viewBox="0 0 20 20">
                                        <path fill-rule="evenodd"
                                            d="M16.707 5.293a1 1 0 010 1.414l-8 8a1 1 0 01-1.414 0l-4-4a1 1 0 011.414-1.414L8 12.586l7.293-7.293a1 1 0 011.414 0z"
                                            clip-rule="evenodd" />
                                    </svg>
                                </div>
                                Postulez en un clic
                            </li>
                            <li class="flex items-center text-slate-700">
                                <div class="w-6 h-6 rounded-full flex items-center justify-center mr-3"
                                    style="background-color: color-mix(in srgb, var(--consultant-primary) 15%, transparent);">
                                    <svg class="w-4 h-4" style="color: var(--consultant-primary);" fill="currentColor"
                                        viewBox="0 0 20 20">
                                        <path fill-rule="evenodd"
                                            d="M16.707 5.293a1 1 0 010 1.414l-8 8a1 1 0 01-1.414 0l-4-4a1 1 0 011.414-1.414L8 12.586l7.293-7.293a1 1 0 011.414 0z"
                                            clip-rule="evenodd" />
                                    </svg>
                                </div>
                                Messagerie intégrée
                            </li>
                        </ul>

                        <div class="flex flex-col sm:flex-row gap-3">
                            <a href="{{ route('consultant.register') }}"
                                class="flex-1 inline-flex justify-center items-center px-6 py-3.5 rounded-xl text-white font-semibold transition-all duration-300 shadow-lg hover:shadow-xl hover:-translate-y-0.5"
                                style="background: linear-gradient(135deg, var(--consultant-primary), var(--consultant-secondary));">
                                S'inscrire
                                <svg class="w-5 h-5 ml-2" fill="none" stroke="currentColor" viewBox="0 0 24 24">
                                    <path stroke-linecap="round" stroke-linejoin="round" stroke-width="2"
                                        d="M13 7l5 5m0 0l-5 5m5-5H6" />
                                </svg>
                            </a>
                            <a href="{{ route('consultant.login') }}"
                                class="flex-1 inline-flex justify-center items-center px-6 py-3.5 rounded-xl font-semibold border-2 transition-all duration-300 hover:shadow-lg"
                                style="border-color: var(--consultant-primary); color: var(--consultant-primary);">
                                Connexion
                            </a>
                        </div>
                    </div>
                </div>

                <!-- Commercial Card -->
                <div
                    class="group relative glass-card rounded-3xl shadow-xl overflow-hidden border border-slate-200 hover:border-orange-300 transition-all duration-500 hover:shadow-2xl hover:shadow-orange-500/10 hover:-translate-y-2">
                    <div class="absolute top-0 left-0 right-0 h-2"
                        style="background: linear-gradient(90deg, var(--commercial-primary), var(--commercial-accent));">
                    </div>
                    <div class="p-8 lg:p-10">
                        <div class="w-16 h-16 rounded-2xl flex items-center justify-center mb-6 transition-transform group-hover:scale-110"
                            style="background: linear-gradient(135deg, var(--commercial-primary), var(--commercial-accent));">
                            <svg class="w-8 h-8 text-white" fill="none" stroke="currentColor" viewBox="0 0 24 24">
                                <path stroke-linecap="round" stroke-linejoin="round" stroke-width="2"
                                    d="M21 13.255A23.931 23.931 0 0112 15c-3.183 0-6.22-.62-9-1.745M16 6V4a2 2 0 00-2-2h-4a2 2 0 00-2 2v2m4 6h.01M5 20h14a2 2 0 002-2V8a2 2 0 00-2-2H5a2 2 0 00-2 2v10a2 2 0 002 2z" />
                            </svg>
                        </div>
                        <h3 class="text-2xl font-bold text-slate-900 mb-3">Je suis Commercial</h3>
                        <p class="text-slate-600 mb-6 leading-relaxed">Publiez vos missions, trouvez les meilleurs
                            talents et gérez vos recrutements efficacement.</p>

                        <ul class="space-y-3 mb-8">
                            <li class="flex items-center text-slate-700">
                                <div class="w-6 h-6 rounded-full flex items-center justify-center mr-3"
                                    style="background-color: color-mix(in srgb, var(--commercial-primary) 15%, transparent);">
                                    <svg class="w-4 h-4" style="color: var(--commercial-primary);" fill="currentColor"
                                        viewBox="0 0 20 20">
                                        <path fill-rule="evenodd"
                                            d="M16.707 5.293a1 1 0 010 1.414l-8 8a1 1 0 01-1.414 0l-4-4a1 1 0 011.414-1.414L8 12.586l7.293-7.293a1 1 0 011.414 0z"
                                            clip-rule="evenodd" />
                                    </svg>
                                </div>
                                Publiez vos missions
                            </li>
                            <li class="flex items-center text-slate-700">
                                <div class="w-6 h-6 rounded-full flex items-center justify-center mr-3"
                                    style="background-color: color-mix(in srgb, var(--commercial-primary) 15%, transparent);">
                                    <svg class="w-4 h-4" style="color: var(--commercial-primary);" fill="currentColor"
                                        viewBox="0 0 20 20">
                                        <path fill-rule="evenodd"
                                            d="M16.707 5.293a1 1 0 010 1.414l-8 8a1 1 0 01-1.414 0l-4-4a1 1 0 011.414-1.414L8 12.586l7.293-7.293a1 1 0 011.414 0z"
                                            clip-rule="evenodd" />
                                    </svg>
                                </div>
                                Gérez les candidatures
                            </li>
                            <li class="flex items-center text-slate-700">
                                <div class="w-6 h-6 rounded-full flex items-center justify-center mr-3"
                                    style="background-color: color-mix(in srgb, var(--commercial-primary) 15%, transparent);">
                                    <svg class="w-4 h-4" style="color: var(--commercial-primary);" fill="currentColor"
                                        viewBox="0 0 20 20">
                                        <path fill-rule="evenodd"
                                            d="M16.707 5.293a1 1 0 010 1.414l-8 8a1 1 0 01-1.414 0l-4-4a1 1 0 011.414-1.414L8 12.586l7.293-7.293a1 1 0 011.414 0z"
                                            clip-rule="evenodd" />
                                    </svg>
                                </div>
                                Contactez les consultants
                            </li>
                        </ul>

                        <div class="flex flex-col sm:flex-row gap-3">
                            <a href="{{ route('commercial.register') }}"
                                class="flex-1 inline-flex justify-center items-center px-6 py-3.5 rounded-xl text-white font-semibold transition-all duration-300 shadow-lg hover:shadow-xl hover:-translate-y-0.5"
                                style="background: linear-gradient(135deg, var(--commercial-primary), var(--commercial-secondary));">
                                S'inscrire
                                <svg class="w-5 h-5 ml-2" fill="none" stroke="currentColor" viewBox="0 0 24 24">
                                    <path stroke-linecap="round" stroke-linejoin="round" stroke-width="2"
                                        d="M13 7l5 5m0 0l-5 5m5-5H6" />
                                </svg>
                            </a>
                            <a href="{{ route('commercial.login') }}"
                                class="flex-1 inline-flex justify-center items-center px-6 py-3.5 rounded-xl font-semibold border-2 transition-all duration-300 hover:shadow-lg"
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
    <section id="features" class="py-24 bg-white">
        <div class="max-w-7xl mx-auto px-4 sm:px-6 lg:px-8">
            <div class="text-center mb-16">
                <span
                    class="inline-block px-4 py-1.5 rounded-full bg-blue-100 text-blue-700 text-sm font-medium mb-4">Nos
                    fonctionnalités</span>
                <h2 class="text-3xl sm:text-4xl font-bold text-slate-900">Pourquoi nous choisir ?</h2>
                <p class="mt-4 text-lg text-slate-600 max-w-2xl mx-auto">Une plateforme complète conçue pour simplifier
                    et optimiser votre quotidien professionnel.</p>
            </div>

            <div class="grid md:grid-cols-3 gap-8">
                <div
                    class="group p-8 rounded-2xl bg-gradient-to-br from-slate-50 to-white border border-slate-100 hover:shadow-xl hover:shadow-blue-500/5 transition-all duration-500 hover:-translate-y-1">
                    <div
                        class="w-14 h-14 rounded-2xl bg-gradient-to-br from-blue-500 to-blue-600 flex items-center justify-center mb-6 group-hover:scale-110 transition-transform">
                        <svg class="w-7 h-7 text-white" fill="none" stroke="currentColor" viewBox="0 0 24 24">
                            <path stroke-linecap="round" stroke-linejoin="round" stroke-width="2"
                                d="M13 10V3L4 14h7v7l9-11h-7z" />
                        </svg>
                    </div>
                    <h3 class="text-xl font-bold text-slate-900 mb-3">Rapide & Efficace</h3>
                    <p class="text-slate-600 leading-relaxed">Trouvez ou publiez des missions en quelques clics.
                        Interface intuitive et processus simplifié pour une productivité maximale.</p>
                </div>

                <div
                    class="group p-8 rounded-2xl bg-gradient-to-br from-slate-50 to-white border border-slate-100 hover:shadow-xl hover:shadow-orange-500/5 transition-all duration-500 hover:-translate-y-1">
                    <div
                        class="w-14 h-14 rounded-2xl bg-gradient-to-br from-orange-500 to-orange-600 flex items-center justify-center mb-6 group-hover:scale-110 transition-transform">
                        <svg class="w-7 h-7 text-white" fill="none" stroke="currentColor" viewBox="0 0 24 24">
                            <path stroke-linecap="round" stroke-linejoin="round" stroke-width="2"
                                d="M17 8h2a2 2 0 012 2v6a2 2 0 01-2 2h-2v4l-4-4H9a1.994 1.994 0 01-1.414-.586m0 0L11 14h4a2 2 0 002-2V6a2 2 0 00-2-2H5a2 2 0 00-2 2v6a2 2 0 002 2h2v4l.586-.586z" />
                        </svg>
                    </div>
                    <h3 class="text-xl font-bold text-slate-900 mb-3">Communication Directe</h3>
                    <p class="text-slate-600 leading-relaxed">Messagerie intégrée en temps réel pour échanger
                        directement entre consultants et commerciaux sans intermédiaire.</p>
                </div>

                <div
                    class="group p-8 rounded-2xl bg-gradient-to-br from-slate-50 to-white border border-slate-100 hover:shadow-xl hover:shadow-green-500/5 transition-all duration-500 hover:-translate-y-1">
                    <div
                        class="w-14 h-14 rounded-2xl bg-gradient-to-br from-green-500 to-green-600 flex items-center justify-center mb-6 group-hover:scale-110 transition-transform">
                        <svg class="w-7 h-7 text-white" fill="none" stroke="currentColor" viewBox="0 0 24 24">
                            <path stroke-linecap="round" stroke-linejoin="round" stroke-width="2"
                                d="M9 12l2 2 4-4m5.618-4.016A11.955 11.955 0 0112 2.944a11.955 11.955 0 01-8.618 3.04A12.02 12.02 0 003 9c0 5.591 3.824 10.29 9 11.622 5.176-1.332 9-6.03 9-11.622 0-1.042-.133-2.052-.382-3.016z" />
                        </svg>
                    </div>
                    <h3 class="text-xl font-bold text-slate-900 mb-3">Sécurisé & Privé</h3>
                    <p class="text-slate-600 leading-relaxed">Vos données sont protégées avec les derniers standards de
                        sécurité. Accès réservé aux membres vérifiés.</p>
                </div>
            </div>
        </div>
    </section>

    <!-- CTA Section -->
    <section class="py-24 hero-gradient relative overflow-hidden">
        <div class="absolute inset-0">
            <div class="absolute top-0 left-1/4 w-96 h-96 bg-blue-500/10 rounded-full blur-3xl"></div>
            <div class="absolute bottom-0 right-1/4 w-96 h-96 bg-orange-500/10 rounded-full blur-3xl"></div>
        </div>
        <div class="relative max-w-4xl mx-auto px-4 sm:px-6 lg:px-8 text-center">
            <h2 class="text-3xl sm:text-4xl font-bold text-white mb-6">Prêt à commencer ?</h2>
            <p class="text-lg text-slate-300 mb-10 max-w-2xl mx-auto">Rejoignez notre communauté grandissante de
                professionnels IT et trouvez votre prochaine opportunité dès aujourd'hui.</p>
            <div class="flex flex-col sm:flex-row gap-4 justify-center">
                <a href="{{ route('consultant.register') }}"
                    class="inline-flex items-center justify-center gap-2 px-8 py-4 text-lg font-semibold text-white rounded-2xl transition-all shadow-xl hover:shadow-2xl hover:-translate-y-1"
                    style="background: linear-gradient(135deg, var(--consultant-primary), var(--consultant-secondary));">
                    <svg class="w-5 h-5" fill="none" stroke="currentColor" viewBox="0 0 24 24">
                        <path stroke-linecap="round" stroke-linejoin="round" stroke-width="2"
                            d="M16 7a4 4 0 11-8 0 4 4 0 018 0zM12 14a7 7 0 00-7 7h14a7 7 0 00-7-7z" />
                    </svg>
                    Espace Consultant
                </a>
                <a href="{{ route('commercial.register') }}"
                    class="inline-flex items-center justify-center gap-2 px-8 py-4 text-lg font-semibold text-white rounded-2xl transition-all shadow-xl hover:shadow-2xl hover:-translate-y-1"
                    style="background: linear-gradient(135deg, var(--commercial-primary), var(--commercial-secondary));">
                    <svg class="w-5 h-5" fill="none" stroke="currentColor" viewBox="0 0 24 24">
                        <path stroke-linecap="round" stroke-linejoin="round" stroke-width="2"
                            d="M21 13.255A23.931 23.931 0 0112 15c-3.183 0-6.22-.62-9-1.745M16 6V4a2 2 0 00-2-2h-4a2 2 0 00-2 2v2m4 6h.01M5 20h14a2 2 0 002-2V8a2 2 0 00-2-2H5a2 2 0 00-2 2v10a2 2 0 002 2z" />
                    </svg>
                    Espace Commercial
                </a>
            </div>
        </div>
    </section>

    <!-- Footer -->
    <footer class="bg-slate-900 pt-16 pb-8">
        <div class="max-w-7xl mx-auto px-4 sm:px-6 lg:px-8">
            <div class="grid md:grid-cols-4 gap-8 mb-12">
                <div class="md:col-span-2">
                    <div class="flex items-center gap-3 mb-4">
                        @if($siteSettings->logo_url)
                            <img src="{{ $siteSettings->logo_url }}" alt="{{ $siteSettings->site_name }}"
                                class="h-10 w-auto brightness-0 invert">
                        @else
                            <div
                                class="w-10 h-10 rounded-xl bg-gradient-to-br from-blue-500 to-orange-500 flex items-center justify-center">
                                <svg class="w-6 h-6 text-white" fill="none" stroke="currentColor" viewBox="0 0 24 24">
                                    <path stroke-linecap="round" stroke-linejoin="round" stroke-width="2"
                                        d="M13 10V3L4 14h7v7l9-11h-7z" />
                                </svg>
                            </div>
                            <span class="text-xl font-bold text-white">{{ $siteSettings->site_name }}</span>
                        @endif
                    </div>
                    <p class="text-slate-400 max-w-md">La plateforme de référence pour la mise en relation entre
                        consultants IT et commerciaux. Simple, efficace et sécurisée.</p>
                </div>
                <div>
                    <h4 class="text-white font-semibold mb-4">Consultants</h4>
                    <ul class="space-y-2">
                        <li><a href="{{ route('consultant.login') }}"
                                class="text-slate-400 hover:text-white transition-colors">Connexion</a></li>
                        <li><a href="{{ route('consultant.register') }}"
                                class="text-slate-400 hover:text-white transition-colors">Inscription</a></li>
                    </ul>
                </div>
                <div>
                    <h4 class="text-white font-semibold mb-4">Commerciaux</h4>
                    <ul class="space-y-2">
                        <li><a href="{{ route('commercial.login') }}"
                                class="text-slate-400 hover:text-white transition-colors">Connexion</a></li>
                        <li><a href="{{ route('commercial.register') }}"
                                class="text-slate-400 hover:text-white transition-colors">Inscription</a></li>
                    </ul>
                </div>
            </div>
            <div class="border-t border-slate-800 pt-8">
                <p class="text-center text-slate-500 text-sm">
                    &copy; {{ date('Y') }} {{ $siteSettings->site_name }}. Tous droits réservés.
                </p>
            </div>
        </div>
    </footer>
</body>

</html>