<!DOCTYPE html>
<html lang="{{ str_replace('_', '-', app()->getLocale()) }}" class="scroll-smooth">

<head>
    <meta charset="utf-8">
    <meta name="viewport" content="width=device-width, initial-scale=1">
    <meta name="csrf-token" content="{{ csrf_token() }}">

    @php
        $siteSettings = \App\Models\SiteSettings::instance();
    @endphp

    <title>{{ $siteSettings->site_name ?? config('app.name', 'Laravel') }} - Plateforme de Gestion de Missions</title>
    <meta name="description"
        content="Connectez consultants et commerciaux pour des missions réussies. Gérez vos opportunités, candidatures et collaborations en un seul endroit.">

    <!-- Fonts -->
    <link rel="preconnect" href="https://fonts.bunny.net">
    <link href="https://fonts.bunny.net/css?family=inter:400,500,600,700,800&display=swap" rel="stylesheet" />

    <!-- Scripts -->
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

        .gradient-text {
            background: linear-gradient(135deg, var(--consultant-primary) 0%, var(--commercial-primary) 100%);
            -webkit-background-clip: text;
            -webkit-text-fill-color: transparent;
            background-clip: text;
        }

        .hero-gradient {
            background: linear-gradient(135deg, #0F172A 0%, #1E293B 50%, #334155 100%);
        }

        .card-shine {
            background: linear-gradient(135deg, rgba(255, 255, 255, 0.1) 0%, rgba(255, 255, 255, 0) 50%);
        }

        .animate-float {
            animation: float 6s ease-in-out infinite;
        }

        .animate-float-delayed {
            animation: float 6s ease-in-out infinite;
            animation-delay: 3s;
        }

        .animate-pulse-slow {
            animation: pulse 4s cubic-bezier(0.4, 0, 0.6, 1) infinite;
        }

        @keyframes float {

            0%,
            100% {
                transform: translateY(0px) rotate(0deg);
            }

            50% {
                transform: translateY(-20px) rotate(2deg);
            }
        }
    </style>
</head>

<body class="font-sans antialiased bg-slate-50 text-slate-900">
    <!-- Navigation -->
    <nav class="fixed top-0 left-0 right-0 z-50 bg-white/80 backdrop-blur-lg border-b border-slate-200/50">
        <div class="max-w-7xl mx-auto px-4 sm:px-6 lg:px-8">
            <div class="flex items-center justify-between h-16">
                <!-- Logo -->
                <a href="/" class="flex items-center gap-3 group">
                    @if($siteSettings->logo_url)
                        <img src="{{ $siteSettings->logo_url }}" alt="{{ $siteSettings->site_name }}"
                            class="h-10 w-auto group-hover:scale-105 transition-transform">
                    @else
                        <div
                            class="w-10 h-10 rounded-xl bg-gradient-to-br from-blue-600 to-orange-500 flex items-center justify-center group-hover:scale-105 transition-transform">
                            <svg class="w-6 h-6 text-white" fill="none" stroke="currentColor" viewBox="0 0 24 24">
                                <path stroke-linecap="round" stroke-linejoin="round" stroke-width="2"
                                    d="M13 10V3L4 14h7v7l9-11h-7z" />
                            </svg>
                        </div>
                        <span class="text-xl font-bold text-slate-900">{{ $siteSettings->site_name }}</span>
                    @endif
                </a>

                <!-- Desktop Navigation -->
                <div class="hidden md:flex items-center gap-8">
                    <a href="#features"
                        class="text-sm font-medium text-slate-600 hover:text-slate-900 transition-colors">Fonctionnalités</a>
                    <a href="#how-it-works"
                        class="text-sm font-medium text-slate-600 hover:text-slate-900 transition-colors">Comment ça
                        marche</a>
                    <a href="#roles"
                        class="text-sm font-medium text-slate-600 hover:text-slate-900 transition-colors">Espaces</a>
                </div>

                <!-- Auth Buttons -->
                <div class="flex items-center gap-3">
                    <a href="{{ route('consultant.login') }}"
                        class="hidden sm:inline-flex items-center gap-2 px-4 py-2 text-sm font-medium text-slate-700 hover:text-blue-600 transition-colors">
                        Connexion
                    </a>
                    <a href="#roles"
                        class="inline-flex items-center gap-2 px-4 py-2 text-sm font-semibold text-white bg-gradient-to-r from-blue-600 to-blue-700 rounded-xl hover:from-blue-700 hover:to-blue-800 transition-all shadow-lg shadow-blue-500/25 hover:shadow-xl hover:shadow-blue-500/30 hover:-translate-y-0.5">
                        Commencer
                        <svg class="w-4 h-4" fill="none" stroke="currentColor" viewBox="0 0 24 24">
                            <path stroke-linecap="round" stroke-linejoin="round" stroke-width="2"
                                d="M13 7l5 5m0 0l-5 5m5-5H6" />
                        </svg>
                    </a>
                </div>
            </div>
        </div>
    </nav>

    <!-- Hero Section -->
    <section class="relative min-h-screen flex items-center hero-gradient overflow-hidden pt-16">
        <!-- Animated Background Elements -->
        <div class="absolute inset-0 overflow-hidden">
            <div class="absolute top-20 left-10 w-72 h-72 bg-blue-500/10 rounded-full blur-3xl animate-float"></div>
            <div
                class="absolute bottom-20 right-10 w-96 h-96 bg-orange-500/10 rounded-full blur-3xl animate-float-delayed">
            </div>
            <div
                class="absolute top-1/2 left-1/2 -translate-x-1/2 -translate-y-1/2 w-[800px] h-[800px] bg-gradient-to-r from-blue-500/5 to-orange-500/5 rounded-full blur-3xl">
            </div>
        </div>

        <!-- Grid Pattern -->
        <div class="absolute inset-0 bg-[url('data:image/svg+xml,%3Csvg width=\" 60\" height=\"60\" viewBox=\"0 0 60
            60\" xmlns=\"http://www.w3.org/2000/svg\"%3E%3Cg fill=\"none\" fill-rule=\"evenodd\"%3E%3Cg
            fill=\"%23ffffff\" fill-opacity=\"0.03\"%3E%3Cpath d=\"M36
            34v-4h-2v4h-4v2h4v4h2v-4h4v-2h-4zm0-30V0h-2v4h-4v2h4v4h2V6h4V4h-4zM6 34v-4H4v4H0v2h4v4h2v-4h4v-2H6zM6
            4V0H4v4H0v2h4v4h2V6h4V4H6z\"/%3E%3C/g%3E%3C/g%3E%3C/svg%3E')]"></div>

        <div class="relative z-10 max-w-7xl mx-auto px-4 sm:px-6 lg:px-8 py-20">
            <div class="grid lg:grid-cols-2 gap-12 items-center">
                <!-- Left Content -->
                <div class="text-center lg:text-left">
                    <div
                        class="inline-flex items-center gap-2 px-4 py-2 rounded-full bg-white/10 backdrop-blur-sm border border-white/20 text-white/80 text-sm mb-8">
                        <span class="w-2 h-2 bg-green-400 rounded-full animate-pulse"></span>
                        Plateforme de mise en relation professionnelle
                    </div>

                    <h1 class="text-4xl sm:text-5xl lg:text-6xl font-bold text-white mb-6 leading-tight">
                        Connectez
                        <span class="gradient-text">Talents</span>
                        et
                        <span class="gradient-text">Opportunités</span>
                    </h1>

                    <p class="text-lg sm:text-xl text-slate-300 mb-10 max-w-xl mx-auto lg:mx-0 leading-relaxed">
                        Une plateforme moderne pour gérer vos missions, candidatures et collaborations entre consultants
                        et commerciaux.
                    </p>

                    <div class="flex flex-col sm:flex-row gap-4 justify-center lg:justify-start">
                        <a href="{{ route('consultant.register') }}"
                            class="inline-flex items-center justify-center gap-2 px-8 py-4 text-base font-semibold text-white bg-gradient-to-r from-blue-600 to-blue-700 rounded-2xl hover:from-blue-700 hover:to-blue-800 transition-all shadow-xl shadow-blue-500/30 hover:shadow-2xl hover:shadow-blue-500/40 hover:-translate-y-1">
                            <svg class="w-5 h-5" fill="none" stroke="currentColor" viewBox="0 0 24 24">
                                <path stroke-linecap="round" stroke-linejoin="round" stroke-width="2"
                                    d="M16 7a4 4 0 11-8 0 4 4 0 018 0zM12 14a7 7 0 00-7 7h14a7 7 0 00-7-7z" />
                            </svg>
                            Je suis Consultant
                        </a>
                        <a href="{{ route('commercial.register') }}"
                            class="inline-flex items-center justify-center gap-2 px-8 py-4 text-base font-semibold text-white bg-gradient-to-r from-orange-500 to-orange-600 rounded-2xl hover:from-orange-600 hover:to-orange-700 transition-all shadow-xl shadow-orange-500/30 hover:shadow-2xl hover:shadow-orange-500/40 hover:-translate-y-1">
                            <svg class="w-5 h-5" fill="none" stroke="currentColor" viewBox="0 0 24 24">
                                <path stroke-linecap="round" stroke-linejoin="round" stroke-width="2"
                                    d="M21 13.255A23.931 23.931 0 0112 15c-3.183 0-6.22-.62-9-1.745M16 6V4a2 2 0 00-2-2h-4a2 2 0 00-2 2v2m4 6h.01M5 20h14a2 2 0 002-2V8a2 2 0 00-2-2H5a2 2 0 00-2 2v10a2 2 0 002 2z" />
                            </svg>
                            Je suis Commercial
                        </a>
                    </div>

                    <!-- Stats -->
                    <div class="mt-16 grid grid-cols-3 gap-8 max-w-lg mx-auto lg:mx-0">
                        <div class="text-center lg:text-left">
                            <div class="text-3xl sm:text-4xl font-bold text-white">500+</div>
                            <div class="text-sm text-slate-400 mt-1">Consultants</div>
                        </div>
                        <div class="text-center lg:text-left">
                            <div class="text-3xl sm:text-4xl font-bold text-white">200+</div>
                            <div class="text-sm text-slate-400 mt-1">Missions</div>
                        </div>
                        <div class="text-center lg:text-left">
                            <div class="text-3xl sm:text-4xl font-bold text-white">98%</div>
                            <div class="text-sm text-slate-400 mt-1">Satisfaction</div>
                        </div>
                    </div>
                </div>

                <!-- Right Content - Dashboard Preview -->
                <div class="hidden lg:block relative">
                    <div class="relative">
                        <!-- Main Card -->
                        <div class="bg-white/10 backdrop-blur-xl rounded-3xl border border-white/20 p-6 shadow-2xl">
                            <div class="flex items-center gap-3 mb-6">
                                <div class="w-3 h-3 rounded-full bg-red-400"></div>
                                <div class="w-3 h-3 rounded-full bg-yellow-400"></div>
                                <div class="w-3 h-3 rounded-full bg-green-400"></div>
                                <span class="ml-4 text-sm text-white/60">Mission Dashboard</span>
                            </div>

                            <!-- Mock Dashboard Content -->
                            <div class="space-y-4">
                                <div class="bg-white/10 rounded-2xl p-4">
                                    <div class="flex items-center justify-between mb-3">
                                        <span class="text-sm font-medium text-white">Missions en cours</span>
                                        <span
                                            class="text-xs px-2 py-1 bg-green-500/20 text-green-400 rounded-full">+12%</span>
                                    </div>
                                    <div class="text-3xl font-bold text-white">24</div>
                                </div>

                                <div class="grid grid-cols-2 gap-4">
                                    <div class="bg-white/10 rounded-2xl p-4">
                                        <div class="text-sm text-white/60 mb-1">Candidatures</div>
                                        <div class="text-2xl font-bold text-white">156</div>
                                    </div>
                                    <div class="bg-white/10 rounded-2xl p-4">
                                        <div class="text-sm text-white/60 mb-1">Messages</div>
                                        <div class="text-2xl font-bold text-white">38</div>
                                    </div>
                                </div>

                                <div class="bg-white/10 rounded-2xl p-4">
                                    <div class="flex items-center gap-3">
                                        <div
                                            class="w-10 h-10 rounded-full bg-gradient-to-br from-blue-500 to-blue-600 flex items-center justify-center text-white font-semibold">
                                            JD</div>
                                        <div>
                                            <div class="text-sm font-medium text-white">Nouvelle candidature</div>
                                            <div class="text-xs text-white/60">Jean Dupont - Dev Senior</div>
                                        </div>
                                    </div>
                                </div>
                            </div>
                        </div>

                        <!-- Floating Cards -->
                        <div
                            class="absolute -top-4 -right-4 bg-gradient-to-br from-blue-500 to-blue-600 rounded-2xl p-4 shadow-xl animate-float">
                            <svg class="w-8 h-8 text-white" fill="none" stroke="currentColor" viewBox="0 0 24 24">
                                <path stroke-linecap="round" stroke-linejoin="round" stroke-width="2"
                                    d="M9 12l2 2 4-4m6 2a9 9 0 11-18 0 9 9 0 0118 0z" />
                            </svg>
                        </div>

                        <div
                            class="absolute -bottom-4 -left-4 bg-gradient-to-br from-orange-500 to-orange-600 rounded-2xl p-4 shadow-xl animate-float-delayed">
                            <svg class="w-8 h-8 text-white" fill="none" stroke="currentColor" viewBox="0 0 24 24">
                                <path stroke-linecap="round" stroke-linejoin="round" stroke-width="2"
                                    d="M13 10V3L4 14h7v7l9-11h-7z" />
                            </svg>
                        </div>
                    </div>
                </div>
            </div>
        </div>

        <!-- Scroll Indicator -->
        <div class="absolute bottom-8 left-1/2 -translate-x-1/2 animate-bounce">
            <a href="#features" class="text-white/50 hover:text-white transition-colors">
                <svg class="w-6 h-6" fill="none" stroke="currentColor" viewBox="0 0 24 24">
                    <path stroke-linecap="round" stroke-linejoin="round" stroke-width="2"
                        d="M19 14l-7 7m0 0l-7-7m7 7V3" />
                </svg>
            </a>
        </div>
    </section>

    <!-- Features Section -->
    <section id="features" class="py-24 bg-white">
        <div class="max-w-7xl mx-auto px-4 sm:px-6 lg:px-8">
            <div class="text-center max-w-3xl mx-auto mb-16">
                <div
                    class="inline-flex items-center gap-2 px-4 py-2 rounded-full bg-blue-50 text-blue-600 text-sm font-medium mb-6">
                    <svg class="w-4 h-4" fill="none" stroke="currentColor" viewBox="0 0 24 24">
                        <path stroke-linecap="round" stroke-linejoin="round" stroke-width="2"
                            d="M5 3v4M3 5h4M6 17v4m-2-2h4m5-16l2.286 6.857L21 12l-5.714 2.143L13 21l-2.286-6.857L5 12l5.714-2.143L13 3z" />
                    </svg>
                    Fonctionnalités
                </div>
                <h2 class="text-3xl sm:text-4xl font-bold text-slate-900 mb-4">
                    Tout ce dont vous avez besoin
                </h2>
                <p class="text-lg text-slate-600">
                    Une suite complète d'outils pour gérer efficacement vos missions et collaborations
                </p>
            </div>

            <div class="grid md:grid-cols-2 lg:grid-cols-3 gap-8">
                <!-- Feature 1 -->
                <div
                    class="group bg-slate-50 rounded-3xl p-8 hover:bg-white hover:shadow-2xl hover:shadow-slate-200/50 transition-all duration-300 hover:-translate-y-1">
                    <div
                        class="w-14 h-14 rounded-2xl bg-gradient-to-br from-blue-500 to-blue-600 flex items-center justify-center mb-6 group-hover:scale-110 transition-transform">
                        <svg class="w-7 h-7 text-white" fill="none" stroke="currentColor" viewBox="0 0 24 24">
                            <path stroke-linecap="round" stroke-linejoin="round" stroke-width="2"
                                d="M21 13.255A23.931 23.931 0 0112 15c-3.183 0-6.22-.62-9-1.745M16 6V4a2 2 0 00-2-2h-4a2 2 0 00-2 2v2m4 6h.01M5 20h14a2 2 0 002-2V8a2 2 0 00-2-2H5a2 2 0 00-2 2v10a2 2 0 002 2z" />
                        </svg>
                    </div>
                    <h3 class="text-xl font-bold text-slate-900 mb-3">Gestion des Missions</h3>
                    <p class="text-slate-600 leading-relaxed">
                        Créez, publiez et gérez vos missions en quelques clics. Suivez les candidatures en temps réel.
                    </p>
                </div>

                <!-- Feature 2 -->
                <div
                    class="group bg-slate-50 rounded-3xl p-8 hover:bg-white hover:shadow-2xl hover:shadow-slate-200/50 transition-all duration-300 hover:-translate-y-1">
                    <div
                        class="w-14 h-14 rounded-2xl bg-gradient-to-br from-orange-500 to-orange-600 flex items-center justify-center mb-6 group-hover:scale-110 transition-transform">
                        <svg class="w-7 h-7 text-white" fill="none" stroke="currentColor" viewBox="0 0 24 24">
                            <path stroke-linecap="round" stroke-linejoin="round" stroke-width="2"
                                d="M17 20h5v-2a3 3 0 00-5.356-1.857M17 20H7m10 0v-2c0-.656-.126-1.283-.356-1.857M7 20H2v-2a3 3 0 015.356-1.857M7 20v-2c0-.656.126-1.283.356-1.857m0 0a5.002 5.002 0 019.288 0M15 7a3 3 0 11-6 0 3 3 0 016 0zm6 3a2 2 0 11-4 0 2 2 0 014 0zM7 10a2 2 0 11-4 0 2 2 0 014 0z" />
                        </svg>
                    </div>
                    <h3 class="text-xl font-bold text-slate-900 mb-3">Profils Consultants</h3>
                    <p class="text-slate-600 leading-relaxed">
                        Consultez les profils détaillés, compétences et expériences des consultants disponibles.
                    </p>
                </div>

                <!-- Feature 3 -->
                <div
                    class="group bg-slate-50 rounded-3xl p-8 hover:bg-white hover:shadow-2xl hover:shadow-slate-200/50 transition-all duration-300 hover:-translate-y-1">
                    <div
                        class="w-14 h-14 rounded-2xl bg-gradient-to-br from-green-500 to-green-600 flex items-center justify-center mb-6 group-hover:scale-110 transition-transform">
                        <svg class="w-7 h-7 text-white" fill="none" stroke="currentColor" viewBox="0 0 24 24">
                            <path stroke-linecap="round" stroke-linejoin="round" stroke-width="2"
                                d="M8 12h.01M12 12h.01M16 12h.01M21 12c0 4.418-4.03 8-9 8a9.863 9.863 0 01-4.255-.949L3 20l1.395-3.72C3.512 15.042 3 13.574 3 12c0-4.418 4.03-8 9-8s9 3.582 9 8z" />
                        </svg>
                    </div>
                    <h3 class="text-xl font-bold text-slate-900 mb-3">Messagerie Intégrée</h3>
                    <p class="text-slate-600 leading-relaxed">
                        Communiquez directement avec les consultants ou commerciaux via notre messagerie sécurisée.
                    </p>
                </div>

                <!-- Feature 4 -->
                <div
                    class="group bg-slate-50 rounded-3xl p-8 hover:bg-white hover:shadow-2xl hover:shadow-slate-200/50 transition-all duration-300 hover:-translate-y-1">
                    <div
                        class="w-14 h-14 rounded-2xl bg-gradient-to-br from-purple-500 to-purple-600 flex items-center justify-center mb-6 group-hover:scale-110 transition-transform">
                        <svg class="w-7 h-7 text-white" fill="none" stroke="currentColor" viewBox="0 0 24 24">
                            <path stroke-linecap="round" stroke-linejoin="round" stroke-width="2"
                                d="M9 12l2 2 4-4m6 2a9 9 0 11-18 0 9 9 0 0118 0z" />
                        </svg>
                    </div>
                    <h3 class="text-xl font-bold text-slate-900 mb-3">Suivi des Candidatures</h3>
                    <p class="text-slate-600 leading-relaxed">
                        Suivez l'état de vos candidatures et recevez des notifications en temps réel.
                    </p>
                </div>

                <!-- Feature 5 -->
                <div
                    class="group bg-slate-50 rounded-3xl p-8 hover:bg-white hover:shadow-2xl hover:shadow-slate-200/50 transition-all duration-300 hover:-translate-y-1">
                    <div
                        class="w-14 h-14 rounded-2xl bg-gradient-to-br from-pink-500 to-pink-600 flex items-center justify-center mb-6 group-hover:scale-110 transition-transform">
                        <svg class="w-7 h-7 text-white" fill="none" stroke="currentColor" viewBox="0 0 24 24">
                            <path stroke-linecap="round" stroke-linejoin="round" stroke-width="2"
                                d="M7 7h.01M7 3h5c.512 0 1.024.195 1.414.586l7 7a2 2 0 010 2.828l-7 7a2 2 0 01-2.828 0l-7-7A1.994 1.994 0 013 12V7a4 4 0 014-4z" />
                        </svg>
                    </div>
                    <h3 class="text-xl font-bold text-slate-900 mb-3">Tags & Compétences</h3>
                    <p class="text-slate-600 leading-relaxed">
                        Filtrez les missions et profils par compétences techniques et secteurs d'activité.
                    </p>
                </div>

                <!-- Feature 6 -->
                <div
                    class="group bg-slate-50 rounded-3xl p-8 hover:bg-white hover:shadow-2xl hover:shadow-slate-200/50 transition-all duration-300 hover:-translate-y-1">
                    <div
                        class="w-14 h-14 rounded-2xl bg-gradient-to-br from-cyan-500 to-cyan-600 flex items-center justify-center mb-6 group-hover:scale-110 transition-transform">
                        <svg class="w-7 h-7 text-white" fill="none" stroke="currentColor" viewBox="0 0 24 24">
                            <path stroke-linecap="round" stroke-linejoin="round" stroke-width="2"
                                d="M15 17h5l-1.405-1.405A2.032 2.032 0 0118 14.158V11a6.002 6.002 0 00-4-5.659V5a2 2 0 10-4 0v.341C7.67 6.165 6 8.388 6 11v3.159c0 .538-.214 1.055-.595 1.436L4 17h5m6 0v1a3 3 0 11-6 0v-1m6 0H9" />
                        </svg>
                    </div>
                    <h3 class="text-xl font-bold text-slate-900 mb-3">Notifications</h3>
                    <p class="text-slate-600 leading-relaxed">
                        Restez informé des nouvelles opportunités et mises à jour importantes.
                    </p>
                </div>
            </div>
        </div>
    </section>

    <!-- How It Works Section -->
    <section id="how-it-works" class="py-24 bg-slate-50">
        <div class="max-w-7xl mx-auto px-4 sm:px-6 lg:px-8">
            <div class="text-center max-w-3xl mx-auto mb-16">
                <div
                    class="inline-flex items-center gap-2 px-4 py-2 rounded-full bg-orange-50 text-orange-600 text-sm font-medium mb-6">
                    <svg class="w-4 h-4" fill="none" stroke="currentColor" viewBox="0 0 24 24">
                        <path stroke-linecap="round" stroke-linejoin="round" stroke-width="2"
                            d="M13 10V3L4 14h7v7l9-11h-7z" />
                    </svg>
                    Comment ça marche
                </div>
                <h2 class="text-3xl sm:text-4xl font-bold text-slate-900 mb-4">
                    Simple et efficace
                </h2>
                <p class="text-lg text-slate-600">
                    Commencez en quelques minutes avec notre processus simplifié
                </p>
            </div>

            <div class="grid md:grid-cols-3 gap-8">
                <!-- Step 1 -->
                <div class="relative">
                    <div class="bg-white rounded-3xl p-8 shadow-sm border border-slate-100 relative z-10">
                        <div
                            class="w-12 h-12 rounded-full bg-gradient-to-br from-blue-500 to-blue-600 flex items-center justify-center text-white font-bold text-lg mb-6">
                            1
                        </div>
                        <h3 class="text-xl font-bold text-slate-900 mb-3">Créez votre compte</h3>
                        <p class="text-slate-600 leading-relaxed">
                            Inscrivez-vous en tant que consultant ou commercial et complétez votre profil en quelques
                            minutes.
                        </p>
                    </div>
                    <div
                        class="hidden md:block absolute top-1/2 left-full w-full h-0.5 bg-gradient-to-r from-blue-200 to-transparent -translate-y-1/2 z-0">
                    </div>
                </div>

                <!-- Step 2 -->
                <div class="relative">
                    <div class="bg-white rounded-3xl p-8 shadow-sm border border-slate-100 relative z-10">
                        <div
                            class="w-12 h-12 rounded-full bg-gradient-to-br from-orange-500 to-orange-600 flex items-center justify-center text-white font-bold text-lg mb-6">
                            2
                        </div>
                        <h3 class="text-xl font-bold text-slate-900 mb-3">Explorez les opportunités</h3>
                        <p class="text-slate-600 leading-relaxed">
                            Parcourez les missions disponibles ou recherchez des consultants selon vos critères.
                        </p>
                    </div>
                    <div
                        class="hidden md:block absolute top-1/2 left-full w-full h-0.5 bg-gradient-to-r from-orange-200 to-transparent -translate-y-1/2 z-0">
                    </div>
                </div>

                <!-- Step 3 -->
                <div class="relative">
                    <div class="bg-white rounded-3xl p-8 shadow-sm border border-slate-100 relative z-10">
                        <div
                            class="w-12 h-12 rounded-full bg-gradient-to-br from-green-500 to-green-600 flex items-center justify-center text-white font-bold text-lg mb-6">
                            3
                        </div>
                        <h3 class="text-xl font-bold text-slate-900 mb-3">Collaborez</h3>
                        <p class="text-slate-600 leading-relaxed">
                            Postulez aux missions, échangez avec les parties prenantes et démarrez votre collaboration.
                        </p>
                    </div>
                </div>
            </div>
        </div>
    </section>

    <!-- Roles Section -->
    <section id="roles" class="py-24 bg-white">
        <div class="max-w-7xl mx-auto px-4 sm:px-6 lg:px-8">
            <div class="text-center max-w-3xl mx-auto mb-16">
                <div
                    class="inline-flex items-center gap-2 px-4 py-2 rounded-full bg-purple-50 text-purple-600 text-sm font-medium mb-6">
                    <svg class="w-4 h-4" fill="none" stroke="currentColor" viewBox="0 0 24 24">
                        <path stroke-linecap="round" stroke-linejoin="round" stroke-width="2"
                            d="M12 4.354a4 4 0 110 5.292M15 21H3v-1a6 6 0 0112 0v1zm0 0h6v-1a6 6 0 00-9-5.197M13 7a4 4 0 11-8 0 4 4 0 018 0z" />
                    </svg>
                    Choisissez votre espace
                </div>
                <h2 class="text-3xl sm:text-4xl font-bold text-slate-900 mb-4">
                    Deux espaces, une plateforme
                </h2>
                <p class="text-lg text-slate-600">
                    Sélectionnez votre profil pour accéder à votre espace dédié
                </p>
            </div>

            <div class="grid md:grid-cols-2 gap-8 max-w-5xl mx-auto">
                <!-- Consultant Card -->
                <div
                    class="group relative bg-gradient-to-br from-blue-600 to-blue-800 rounded-3xl p-8 sm:p-10 text-white overflow-hidden">
                    <!-- Background Pattern -->
                    <div class="absolute inset-0 bg-[url('data:image/svg+xml,%3Csvg width=\" 60\" height=\"60\"
                        viewBox=\"0 0 60 60\" xmlns=\"http://www.w3.org/2000/svg\"%3E%3Cg fill=\"none\"
                        fill-rule=\"evenodd\"%3E%3Cg fill=\"%23ffffff\" fill-opacity=\"0.05\"%3E%3Cpath d=\"M36
                        34v-4h-2v4h-4v2h4v4h2v-4h4v-2h-4zm0-30V0h-2v4h-4v2h4v4h2V6h4V4h-4zM6
                        34v-4H4v4H0v2h4v4h2v-4h4v-2H6zM6 4V0H4v4H0v2h4v4h2V6h4V4H6z\"/%3E%3C/g%3E%3C/g%3E%3C/svg%3E')]">
                    </div>

                    <div class="relative z-10">
                        <div
                            class="w-16 h-16 rounded-2xl bg-white/20 backdrop-blur-sm flex items-center justify-center mb-6 group-hover:scale-110 transition-transform">
                            <svg class="w-8 h-8 text-white" fill="none" stroke="currentColor" viewBox="0 0 24 24">
                                <path stroke-linecap="round" stroke-linejoin="round" stroke-width="2"
                                    d="M16 7a4 4 0 11-8 0 4 4 0 018 0zM12 14a7 7 0 00-7 7h14a7 7 0 00-7-7z" />
                            </svg>
                        </div>

                        <h3 class="text-2xl font-bold mb-3">Espace Consultant</h3>
                        <p class="text-blue-100 mb-8 leading-relaxed">
                            Accédez aux missions disponibles, postulez et gérez votre carrière de consultant en toute
                            simplicité.
                        </p>

                        <ul class="space-y-3 mb-8">
                            <li class="flex items-center gap-3">
                                <svg class="w-5 h-5 text-blue-300 shrink-0" fill="none" stroke="currentColor"
                                    viewBox="0 0 24 24">
                                    <path stroke-linecap="round" stroke-linejoin="round" stroke-width="2"
                                        d="M5 13l4 4L19 7" />
                                </svg>
                                <span class="text-blue-50">Parcourez les missions disponibles</span>
                            </li>
                            <li class="flex items-center gap-3">
                                <svg class="w-5 h-5 text-blue-300 shrink-0" fill="none" stroke="currentColor"
                                    viewBox="0 0 24 24">
                                    <path stroke-linecap="round" stroke-linejoin="round" stroke-width="2"
                                        d="M5 13l4 4L19 7" />
                                </svg>
                                <span class="text-blue-50">Postulez en un clic</span>
                            </li>
                            <li class="flex items-center gap-3">
                                <svg class="w-5 h-5 text-blue-300 shrink-0" fill="none" stroke="currentColor"
                                    viewBox="0 0 24 24">
                                    <path stroke-linecap="round" stroke-linejoin="round" stroke-width="2"
                                        d="M5 13l4 4L19 7" />
                                </svg>
                                <span class="text-blue-50">Échangez avec les commerciaux</span>
                            </li>
                        </ul>

                        <div class="flex flex-col sm:flex-row gap-3">
                            <a href="{{ route('consultant.register') }}"
                                class="inline-flex items-center justify-center gap-2 px-6 py-3 bg-white text-blue-700 font-semibold rounded-xl hover:bg-blue-50 transition-all shadow-lg hover:shadow-xl hover:-translate-y-0.5">
                                <svg class="w-5 h-5" fill="none" stroke="currentColor" viewBox="0 0 24 24">
                                    <path stroke-linecap="round" stroke-linejoin="round" stroke-width="2"
                                        d="M18 9v3m0 0v3m0-3h3m-3 0h-3m-2-5a4 4 0 11-8 0 4 4 0 018 0zM3 20a6 6 0 0112 0v1H3v-1z" />
                                </svg>
                                S'inscrire
                            </a>
                            <a href="{{ route('consultant.login') }}"
                                class="inline-flex items-center justify-center gap-2 px-6 py-3 bg-white/10 text-white font-semibold rounded-xl border border-white/30 hover:bg-white/20 transition-all">
                                <svg class="w-5 h-5" fill="none" stroke="currentColor" viewBox="0 0 24 24">
                                    <path stroke-linecap="round" stroke-linejoin="round" stroke-width="2"
                                        d="M11 16l-4-4m0 0l4-4m-4 4h14m-5 4v1a3 3 0 01-3 3H6a3 3 0 01-3-3V7a3 3 0 013-3h7a3 3 0 013 3v1" />
                                </svg>
                                Se connecter
                            </a>
                        </div>
                    </div>
                </div>

                <!-- Commercial Card -->
                <div
                    class="group relative bg-gradient-to-br from-orange-500 to-orange-700 rounded-3xl p-8 sm:p-10 text-white overflow-hidden">
                    <!-- Background Pattern -->
                    <div class="absolute inset-0 bg-[url('data:image/svg+xml,%3Csvg width=\" 60\" height=\"60\"
                        viewBox=\"0 0 60 60\" xmlns=\"http://www.w3.org/2000/svg\"%3E%3Cg fill=\"none\"
                        fill-rule=\"evenodd\"%3E%3Cg fill=\"%23ffffff\" fill-opacity=\"0.05\"%3E%3Cpath d=\"M36
                        34v-4h-2v4h-4v2h4v4h2v-4h4v-2h-4zm0-30V0h-2v4h-4v2h4v4h2V6h4V4h-4zM6
                        34v-4H4v4H0v2h4v4h2v-4h4v-2H6zM6 4V0H4v4H0v2h4v4h2V6h4V4H6z\"/%3E%3C/g%3E%3C/g%3E%3C/svg%3E')]">
                    </div>

                    <div class="relative z-10">
                        <div
                            class="w-16 h-16 rounded-2xl bg-white/20 backdrop-blur-sm flex items-center justify-center mb-6 group-hover:scale-110 transition-transform">
                            <svg class="w-8 h-8 text-white" fill="none" stroke="currentColor" viewBox="0 0 24 24">
                                <path stroke-linecap="round" stroke-linejoin="round" stroke-width="2"
                                    d="M21 13.255A23.931 23.931 0 0112 15c-3.183 0-6.22-.62-9-1.745M16 6V4a2 2 0 00-2-2h-4a2 2 0 00-2 2v2m4 6h.01M5 20h14a2 2 0 002-2V8a2 2 0 00-2-2H5a2 2 0 00-2 2v10a2 2 0 002 2z" />
                            </svg>
                        </div>

                        <h3 class="text-2xl font-bold mb-3">Espace Commercial</h3>
                        <p class="text-orange-100 mb-8 leading-relaxed">
                            Publiez vos missions, gérez les candidatures et trouvez les meilleurs talents pour vos
                            projets.
                        </p>

                        <ul class="space-y-3 mb-8">
                            <li class="flex items-center gap-3">
                                <svg class="w-5 h-5 text-orange-300 shrink-0" fill="none" stroke="currentColor"
                                    viewBox="0 0 24 24">
                                    <path stroke-linecap="round" stroke-linejoin="round" stroke-width="2"
                                        d="M5 13l4 4L19 7" />
                                </svg>
                                <span class="text-orange-50">Publiez vos missions</span>
                            </li>
                            <li class="flex items-center gap-3">
                                <svg class="w-5 h-5 text-orange-300 shrink-0" fill="none" stroke="currentColor"
                                    viewBox="0 0 24 24">
                                    <path stroke-linecap="round" stroke-linejoin="round" stroke-width="2"
                                        d="M5 13l4 4L19 7" />
                                </svg>
                                <span class="text-orange-50">Gérez les candidatures</span>
                            </li>
                            <li class="flex items-center gap-3">
                                <svg class="w-5 h-5 text-orange-300 shrink-0" fill="none" stroke="currentColor"
                                    viewBox="0 0 24 24">
                                    <path stroke-linecap="round" stroke-linejoin="round" stroke-width="2"
                                        d="M5 13l4 4L19 7" />
                                </svg>
                                <span class="text-orange-50">Contactez les consultants</span>
                            </li>
                        </ul>

                        <div class="flex flex-col sm:flex-row gap-3">
                            <a href="{{ route('commercial.register') }}"
                                class="inline-flex items-center justify-center gap-2 px-6 py-3 bg-white text-orange-700 font-semibold rounded-xl hover:bg-orange-50 transition-all shadow-lg hover:shadow-xl hover:-translate-y-0.5">
                                <svg class="w-5 h-5" fill="none" stroke="currentColor" viewBox="0 0 24 24">
                                    <path stroke-linecap="round" stroke-linejoin="round" stroke-width="2"
                                        d="M18 9v3m0 0v3m0-3h3m-3 0h-3m-2-5a4 4 0 11-8 0 4 4 0 018 0zM3 20a6 6 0 0112 0v1H3v-1z" />
                                </svg>
                                S'inscrire
                            </a>
                            <a href="{{ route('commercial.login') }}"
                                class="inline-flex items-center justify-center gap-2 px-6 py-3 bg-white/10 text-white font-semibold rounded-xl border border-white/30 hover:bg-white/20 transition-all">
                                <svg class="w-5 h-5" fill="none" stroke="currentColor" viewBox="0 0 24 24">
                                    <path stroke-linecap="round" stroke-linejoin="round" stroke-width="2"
                                        d="M11 16l-4-4m0 0l4-4m-4 4h14m-5 4v1a3 3 0 01-3 3H6a3 3 0 01-3-3V7a3 3 0 013-3h7a3 3 0 013 3v1" />
                                </svg>
                                Se connecter
                            </a>
                        </div>
                    </div>
                </div>
            </div>
        </div>
    </section>

    <!-- CTA Section -->
    <section class="py-24 bg-gradient-to-br from-slate-900 to-slate-800">
        <div class="max-w-4xl mx-auto px-4 sm:px-6 lg:px-8 text-center">
            <h2 class="text-3xl sm:text-4xl font-bold text-white mb-6">
                Prêt à commencer ?
            </h2>
            <p class="text-lg text-slate-300 mb-10 max-w-2xl mx-auto">
                Rejoignez notre communauté de professionnels et trouvez votre prochaine opportunité dès aujourd'hui.
            </p>
            <div class="flex flex-col sm:flex-row gap-4 justify-center">
                <a href="{{ route('consultant.register') }}"
                    class="inline-flex items-center justify-center gap-2 px-8 py-4 text-base font-semibold text-white bg-gradient-to-r from-blue-600 to-blue-700 rounded-2xl hover:from-blue-700 hover:to-blue-800 transition-all shadow-xl shadow-blue-500/30 hover:shadow-2xl hover:shadow-blue-500/40 hover:-translate-y-1">
                    Commencer maintenant
                    <svg class="w-5 h-5" fill="none" stroke="currentColor" viewBox="0 0 24 24">
                        <path stroke-linecap="round" stroke-linejoin="round" stroke-width="2"
                            d="M13 7l5 5m0 0l-5 5m5-5H6" />
                    </svg>
                </a>
            </div>
        </div>
    </section>

    <!-- Footer -->
    <footer class="bg-slate-900 border-t border-slate-800">
        <div class="max-w-7xl mx-auto px-4 sm:px-6 lg:px-8 py-12">
            <div class="grid md:grid-cols-4 gap-8">
                <!-- Brand -->
                <div class="md:col-span-2">
                    <a href="/" class="flex items-center gap-3 mb-4">
                        @if($siteSettings->logo_url)
                            <img src="{{ $siteSettings->logo_url }}" alt="{{ $siteSettings->site_name }}"
                                class="h-10 w-auto brightness-0 invert">
                        @else
                            <div
                                class="w-10 h-10 rounded-xl bg-gradient-to-br from-blue-600 to-orange-500 flex items-center justify-center">
                                <svg class="w-6 h-6 text-white" fill="none" stroke="currentColor" viewBox="0 0 24 24">
                                    <path stroke-linecap="round" stroke-linejoin="round" stroke-width="2"
                                        d="M13 10V3L4 14h7v7l9-11h-7z" />
                                </svg>
                            </div>
                            <span class="text-xl font-bold text-white">{{ $siteSettings->site_name }}</span>
                        @endif
                    </a>
                    <p class="text-slate-400 max-w-md leading-relaxed">
                        La plateforme de référence pour connecter consultants et commerciaux autour de missions
                        passionnantes.
                    </p>
                </div>

                <!-- Quick Links -->
                <div>
                    <h4 class="text-sm font-semibold text-white uppercase tracking-wider mb-4">Navigation</h4>
                    <ul class="space-y-3">
                        <li><a href="#features"
                                class="text-slate-400 hover:text-white transition-colors">Fonctionnalités</a></li>
                        <li><a href="#how-it-works" class="text-slate-400 hover:text-white transition-colors">Comment ça
                                marche</a></li>
                        <li><a href="#roles" class="text-slate-400 hover:text-white transition-colors">Espaces</a></li>
                    </ul>
                </div>

                <!-- Account -->
                <div>
                    <h4 class="text-sm font-semibold text-white uppercase tracking-wider mb-4">Compte</h4>
                    <ul class="space-y-3">
                        <li><a href="{{ route('consultant.login') }}"
                                class="text-slate-400 hover:text-white transition-colors">Connexion Consultant</a></li>
                        <li><a href="{{ route('commercial.login') }}"
                                class="text-slate-400 hover:text-white transition-colors">Connexion Commercial</a></li>
                        <li><a href="{{ route('consultant.register') }}"
                                class="text-slate-400 hover:text-white transition-colors">Inscription</a></li>
                    </ul>
                </div>
            </div>

            <div
                class="border-t border-slate-800 mt-12 pt-8 flex flex-col sm:flex-row items-center justify-between gap-4">
                <p class="text-slate-400 text-sm">
                    &copy; {{ date('Y') }} {{ $siteSettings->site_name }}. Tous droits réservés.
                </p>
                <div class="flex items-center gap-6">
                    <a href="#" class="text-slate-400 hover:text-white transition-colors">
                        <svg class="w-5 h-5" fill="currentColor" viewBox="0 0 24 24">
                            <path
                                d="M24 4.557c-.883.392-1.832.656-2.828.775 1.017-.609 1.798-1.574 2.165-2.724-.951.564-2.005.974-3.127 1.195-.897-.957-2.178-1.555-3.594-1.555-3.179 0-5.515 2.966-4.797 6.045-4.091-.205-7.719-2.165-10.148-5.144-1.29 2.213-.669 5.108 1.523 6.574-.806-.026-1.566-.247-2.229-.616-.054 2.281 1.581 4.415 3.949 4.89-.693.188-1.452.232-2.224.084.626 1.956 2.444 3.379 4.6 3.419-2.07 1.623-4.678 2.348-7.29 2.04 2.179 1.397 4.768 2.212 7.548 2.212 9.142 0 14.307-7.721 13.995-14.646.962-.695 1.797-1.562 2.457-2.549z" />
                        </svg>
                    </a>
                    <a href="#" class="text-slate-400 hover:text-white transition-colors">
                        <svg class="w-5 h-5" fill="currentColor" viewBox="0 0 24 24">
                            <path
                                d="M19 0h-14c-2.761 0-5 2.239-5 5v14c0 2.761 2.239 5 5 5h14c2.762 0 5-2.239 5-5v-14c0-2.761-2.238-5-5-5zm-11 19h-3v-11h3v11zm-1.5-12.268c-.966 0-1.75-.79-1.75-1.764s.784-1.764 1.75-1.764 1.75.79 1.75 1.764-.783 1.764-1.75 1.764zm13.5 12.268h-3v-5.604c0-3.368-4-3.113-4 0v5.604h-3v-11h3v1.765c1.396-2.586 7-2.777 7 2.476v6.759z" />
                        </svg>
                    </a>
                    <a href="#" class="text-slate-400 hover:text-white transition-colors">
                        <svg class="w-5 h-5" fill="currentColor" viewBox="0 0 24 24">
                            <path
                                d="M12 0c-6.626 0-12 5.373-12 12 0 5.302 3.438 9.8 8.207 11.387.599.111.793-.261.793-.577v-2.234c-3.338.726-4.033-1.416-4.033-1.416-.546-1.387-1.333-1.756-1.333-1.756-1.089-.745.083-.729.083-.729 1.205.084 1.839 1.237 1.839 1.237 1.07 1.834 2.807 1.304 3.492.997.107-.775.418-1.305.762-1.604-2.665-.305-5.467-1.334-5.467-5.931 0-1.311.469-2.381 1.236-3.221-.124-.303-.535-1.524.117-3.176 0 0 1.008-.322 3.301 1.23.957-.266 1.983-.399 3.003-.404 1.02.005 2.047.138 3.006.404 2.291-1.552 3.297-1.23 3.297-1.23.653 1.653.242 2.874.118 3.176.77.84 1.235 1.911 1.235 3.221 0 4.609-2.807 5.624-5.479 5.921.43.372.823 1.102.823 2.222v3.293c0 .319.192.694.801.576 4.765-1.589 8.199-6.086 8.199-11.386 0-6.627-5.373-12-12-12z" />
                        </svg>
                    </a>
                </div>
            </div>
        </div>
    </footer>
</body>

</html>