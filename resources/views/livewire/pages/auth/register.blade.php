<?php

use Livewire\Attributes\Layout;
use Livewire\Volt\Component;

new #[Layout('layouts.guest')] class extends Component
{
    //
}; ?>

<div>
    <div class="text-center mb-8">
        <h2 class="text-2xl font-bold text-slate-900 mb-2">Créer un compte</h2>
        <p class="text-slate-600">Choisissez votre profil pour vous inscrire</p>
    </div>

    <div class="space-y-4">
        <!-- Consultant Card -->
        <a href="{{ route('consultant.register') }}" wire:navigate
            class="block p-6 bg-white border-2 border-slate-200 rounded-xl hover:border-blue-500 hover:shadow-lg transition-all group">
            <div class="flex items-center gap-4">
                <div
                    class="w-14 h-14 bg-blue-100 text-blue-600 rounded-xl flex items-center justify-center group-hover:bg-blue-600 group-hover:text-white transition-colors">
                    <svg class="w-7 h-7" fill="none" stroke="currentColor" viewBox="0 0 24 24">
                        <path stroke-linecap="round" stroke-linejoin="round" stroke-width="2"
                            d="M16 7a4 4 0 11-8 0 4 4 0 018 0zM12 14a7 7 0 00-7 7h14a7 7 0 00-7-7z" />
                    </svg>
                </div>
                <div class="flex-1">
                    <h3 class="text-lg font-semibold text-slate-900 group-hover:text-blue-600 transition-colors">Je suis
                        Consultant</h3>
                    <p class="text-sm text-slate-500">Parcourez les missions et postulez</p>
                </div>
                <svg class="w-5 h-5 text-slate-400 group-hover:text-blue-600 transition-colors" fill="none"
                    stroke="currentColor" viewBox="0 0 24 24">
                    <path stroke-linecap="round" stroke-linejoin="round" stroke-width="2" d="M9 5l7 7-7 7" />
                </svg>
            </div>
        </a>

        <!-- Commercial Card -->
        <a href="{{ route('commercial.register') }}" wire:navigate
            class="block p-6 bg-white border-2 border-slate-200 rounded-xl hover:border-orange-500 hover:shadow-lg transition-all group">
            <div class="flex items-center gap-4">
                <div
                    class="w-14 h-14 bg-orange-100 text-orange-600 rounded-xl flex items-center justify-center group-hover:bg-orange-600 group-hover:text-white transition-colors">
                    <svg class="w-7 h-7" fill="none" stroke="currentColor" viewBox="0 0 24 24">
                        <path stroke-linecap="round" stroke-linejoin="round" stroke-width="2"
                            d="M21 13.255A23.931 23.931 0 0112 15c-3.183 0-6.22-.62-9-1.745M16 6V4a2 2 0 00-2-2h-4a2 2 0 00-2 2v2m4 6h.01M5 20h14a2 2 0 002-2V8a2 2 0 00-2-2H5a2 2 0 00-2 2v10a2 2 0 002 2z" />
                    </svg>
                </div>
                <div class="flex-1">
                    <h3 class="text-lg font-semibold text-slate-900 group-hover:text-orange-600 transition-colors">Je
                        suis Commercial</h3>
                    <p class="text-sm text-slate-500">Publiez des missions et trouvez des talents</p>
                </div>
                <svg class="w-5 h-5 text-slate-400 group-hover:text-orange-600 transition-colors" fill="none"
                    stroke="currentColor" viewBox="0 0 24 24">
                    <path stroke-linecap="round" stroke-linejoin="round" stroke-width="2" d="M9 5l7 7-7 7" />
                </svg>
            </div>
        </a>
    </div>

    <div class="mt-8 text-center">
        <p class="text-sm text-slate-600">
            Déjà inscrit ?
            <a href="{{ route('login') }}" wire:navigate class="font-medium text-blue-600 hover:text-blue-700">
                Se connecter
            </a>
        </p>
    </div>
</div>