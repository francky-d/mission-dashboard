<div class="min-h-screen bg-gray-50 py-8">
    <div class="max-w-4xl mx-auto px-4 sm:px-6 lg:px-8 space-y-6">

        {{-- Retour à la liste --}}
        <div>
            <a href="{{ route('consultant.missions.index') }}" wire:navigate class="link-themed inline-flex items-center text-sm font-medium hover:underline">
                <x-heroicon-m-arrow-left class="w-4 h-4 mr-2" />
                {{ __('Retour aux missions') }}
            </a>
        </div>

        {{-- Message de succès candidature --}}
        <div x-data="{ show: false }"
            x-on:application-submitted.window="show = true; setTimeout(() => show = false, 5000)"
            x-show="show"
            x-transition:enter="transition ease-out duration-300"
            x-transition:enter-start="opacity-0 transform translate-y-2"
            x-transition:enter-end="opacity-100 transform translate-y-0"
            x-transition:leave="transition ease-in duration-200"
            x-transition:leave-start="opacity-100 transform translate-y-0"
            x-transition:leave-end="opacity-0 transform translate-y-2"
            class="rounded-xl bg-emerald-50 border border-emerald-200 p-4 shadow-sm"
            x-cloak>
            <div class="flex items-center">
                <x-heroicon-m-check-circle class="h-5 w-5 text-emerald-500 shrink-0" />
                <p class="ml-3 text-sm font-medium text-emerald-800">
                    {{ __('Votre candidature a été envoyée avec succès !') }}
                </p>
            </div>
        </div>

        {{-- Message retrait candidature --}}
        <div x-data="{ show: false }"
            x-on:application-withdrawn.window="show = true; setTimeout(() => show = false, 5000)"
            x-show="show"
            x-transition:enter="transition ease-out duration-300"
            x-transition:enter-start="opacity-0 transform translate-y-2"
            x-transition:enter-end="opacity-100 transform translate-y-0"
            x-transition:leave="transition ease-in duration-200"
            x-transition:leave-start="opacity-100 transform translate-y-0"
            x-transition:leave-end="opacity-0 transform translate-y-2"
            class="rounded-xl bg-amber-50 border border-amber-200 p-4 shadow-sm"
            x-cloak>
            <div class="flex items-center">
                <x-heroicon-m-arrow-uturn-left class="h-5 w-5 text-amber-500 shrink-0" />
                <p class="ml-3 text-sm font-medium text-amber-800">
                    {{ __('Votre candidature a été retirée.') }}
                </p>
            </div>
        </div>

        {{-- En-tête de la mission --}}
        <div class="card-themed">
            <div class="p-6 sm:p-8">
                <div class="flex flex-col sm:flex-row sm:items-start sm:justify-between gap-4">
                    <div class="flex-1">
                        <h1 class="text-2xl sm:text-3xl font-bold text-slate-900">
                            {{ $mission->title }}
                        </h1>
                        <div class="mt-3 flex flex-wrap items-center gap-4 text-sm text-slate-600">
                            @if($mission->location)
                                <div class="flex items-center">
                                    <x-heroicon-m-map-pin class="w-4 h-4 mr-1.5 text-slate-400" />
                                    {{ $mission->location }}
                                </div>
                            @endif
                            <div class="flex items-center">
                                <x-heroicon-m-calendar class="w-4 h-4 mr-1.5 text-slate-400" />
                                {{ __('Publiée le') }} {{ $mission->created_at->translatedFormat('d F Y') }}
                            </div>
                        </div>
                    </div>
                    <div class="shrink-0">
                        <span class="badge-themed inline-flex items-center px-4 py-2 rounded-full text-sm font-semibold">
                            {{ $mission->status->label() }}
                        </span>
                    </div>
                </div>

                {{-- Tags --}}
                @if($mission->tags->isNotEmpty())
                    <div class="mt-6 pt-6 border-t border-slate-200">
                        <div class="flex flex-wrap gap-2">
                            @foreach($mission->tags as $tag)
                                <span class="tag-pill">
                                    {{ $tag->name }}
                                </span>
                            @endforeach
                        </div>
                    </div>
                @endif
            </div>
        </div>

        {{-- Description --}}
        <div class="card-themed">
            <div class="p-6 sm:p-8">
                <h2 class="text-lg font-bold text-slate-900 mb-4">
                    {{ __('Description de la mission') }}
                </h2>
                <div class="prose prose-slate max-w-none text-slate-700 leading-relaxed">
                    {!! nl2br(e($mission->description)) !!}
                </div>
            </div>
        </div>

        {{-- Contact commercial --}}
        <div class="card-themed">
            <div class="p-6 sm:p-8">
                <h2 class="text-lg font-bold text-slate-900 mb-4">
                    {{ __('Contact') }}
                </h2>
                <div class="flex items-center">
                    <div class="h-14 w-14 rounded-full flex items-center justify-center ring-4 ring-slate-100" style="background-color: var(--theme-primary);">
                        <span class="text-lg font-bold text-white">
                            {{ strtoupper(substr($mission->commercial->name, 0, 2)) }}
                        </span>
                    </div>
                    <div class="ml-4">
                        <p class="text-base font-semibold text-slate-900">
                            {{ $mission->commercial->name }}
                        </p>
                        <p class="text-sm text-slate-500">
                            {{ __('Commercial') }}
                        </p>
                    </div>
                </div>
            </div>
        </div>

        {{-- Actions --}}
        <div class="flex items-center justify-between pt-4">
            <a href="{{ route('consultant.missions.index') }}" wire:navigate class="link-themed inline-flex items-center text-sm font-medium hover:underline">
                <x-heroicon-m-arrow-left class="w-4 h-4 mr-2" />
                {{ __('Retour à la liste') }}
            </a>

            @if($this->hasApplied)
                <div class="flex items-center gap-3">
                    <span class="inline-flex items-center rounded-full px-4 py-2 text-sm font-semibold
                        @switch($this->existingApplication->status)
                            @case(\App\Enums\ApplicationStatus::Pending)
                                bg-amber-100 text-amber-800 border border-amber-200
                                @break
                            @case(\App\Enums\ApplicationStatus::Viewed)
                                bg-blue-100 text-blue-800 border border-blue-200
                                @break
                            @case(\App\Enums\ApplicationStatus::Accepted)
                                bg-emerald-100 text-emerald-800 border border-emerald-200
                                @break
                            @case(\App\Enums\ApplicationStatus::Rejected)
                                bg-red-100 text-red-800 border border-red-200
                                @break
                        @endswitch
                    ">
                        <x-heroicon-m-check class="w-4 h-4 mr-1.5" />
                        {{ __('Candidature envoyée') }} - {{ $this->existingApplication->status->label() }}
                    </span>

                    {{-- Bouton retirer uniquement pour les candidatures en attente --}}
                    @if($this->existingApplication->status === \App\Enums\ApplicationStatus::Pending)
                        <button
                            type="button"
                            wire:click="withdraw"
                            wire:loading.attr="disabled"
                            wire:confirm="{{ __('Êtes-vous sûr de vouloir retirer votre candidature ?') }}"
                            class="inline-flex items-center rounded-lg bg-red-50 border border-red-200 px-4 py-2 text-sm font-medium text-red-700 hover:bg-red-100 transition-colors"
                        >
                            <x-heroicon-m-x-mark class="w-4 h-4 mr-1.5" />
                            {{ __('Retirer') }}
                        </button>
                    @endif
                </div>
            @else
                <button
                    type="button"
                    wire:click="apply"
                    wire:loading.attr="disabled"
                    class="btn-primary disabled:opacity-50 disabled:cursor-not-allowed"
                >
                    <span wire:loading.remove wire:target="apply" class="flex items-center">
                        <x-heroicon-m-paper-airplane class="w-4 h-4 mr-2" />
                        {{ __('Je suis intéressé') }}
                    </span>
                    <span wire:loading wire:target="apply" class="flex items-center">
                        <svg class="animate-spin -ml-1 mr-2 h-4 w-4 text-white" xmlns="http://www.w3.org/2000/svg" fill="none" viewBox="0 0 24 24">
                            <circle class="opacity-25" cx="12" cy="12" r="10" stroke="currentColor" stroke-width="4"></circle>
                            <path class="opacity-75" fill="currentColor" d="M4 12a8 8 0 018-8V0C5.373 0 0 5.373 0 12h4zm2 5.291A7.962 7.962 0 014 12H0c0 3.042 1.135 5.824 3 7.938l3-2.647z"></path>
                        </svg>
                        {{ __('Envoi en cours...') }}
                    </span>
                </button>
            @endif
        </div>
    </div>
</div>
