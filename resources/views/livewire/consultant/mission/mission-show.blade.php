<div class="space-y-6">
    {{-- Retour à la liste --}}
    <div>
        <a href="{{ route('consultant.missions.index') }}" wire:navigate
            class="inline-flex items-center text-sm font-medium text-gray-500 hover:text-gray-700 dark:text-gray-400 dark:hover:text-gray-200">
            <x-heroicon-m-arrow-left class="w-4 h-4 mr-2" />
            {{ __('Retour aux missions') }}
        </a>
    </div>

    {{-- Application Success Message --}}
    <div x-data="{ show: false }"
        x-on:application-submitted.window="show = true; setTimeout(() => show = false, 5000)"
        x-show="show"
        x-transition:enter="transition ease-out duration-300"
        x-transition:enter-start="opacity-0 transform translate-y-2"
        x-transition:enter-end="opacity-100 transform translate-y-0"
        x-transition:leave="transition ease-in duration-200"
        x-transition:leave-start="opacity-100 transform translate-y-0"
        x-transition:leave-end="opacity-0 transform translate-y-2"
        class="rounded-lg bg-green-50 p-4 dark:bg-green-900/30"
        x-cloak>
        <div class="flex">
            <x-heroicon-m-check-circle class="h-5 w-5 text-green-400" />
            <div class="ml-3">
                <p class="text-sm font-medium text-green-800 dark:text-green-200">
                    {{ __('Votre candidature a été envoyée avec succès !') }}
                </p>
            </div>
        </div>
    </div>

    {{-- Application Withdrawn Message --}}
    <div x-data="{ show: false }"
        x-on:application-withdrawn.window="show = true; setTimeout(() => show = false, 5000)"
        x-show="show"
        x-transition:enter="transition ease-out duration-300"
        x-transition:enter-start="opacity-0 transform translate-y-2"
        x-transition:enter-end="opacity-100 transform translate-y-0"
        x-transition:leave="transition ease-in duration-200"
        x-transition:leave-start="opacity-100 transform translate-y-0"
        x-transition:leave-end="opacity-0 transform translate-y-2"
        class="rounded-lg bg-yellow-50 p-4 dark:bg-yellow-900/30"
        x-cloak>
        <div class="flex">
            <x-heroicon-m-arrow-uturn-left class="h-5 w-5 text-yellow-400" />
            <div class="ml-3">
                <p class="text-sm font-medium text-yellow-800 dark:text-yellow-200">
                    {{ __('Votre candidature a été retirée.') }}
                </p>
            </div>
        </div>
    </div>

    {{-- En-tête de la mission --}}
    <div
        class="bg-white dark:bg-gray-800 rounded-lg shadow-sm border border-gray-200 dark:border-gray-700 overflow-hidden">
        <div class="p-6">
            <div class="flex items-start justify-between">
                <div class="flex-1">
                    <h1 class="text-2xl font-bold text-gray-900 dark:text-white">
                        {{ $mission->title }}
                    </h1>
                    <div class="mt-2 flex flex-wrap items-center gap-4 text-sm text-gray-500 dark:text-gray-400">
                        @if($mission->location)
                            <div class="flex items-center">
                                <x-heroicon-m-map-pin class="w-4 h-4 mr-1" />
                                {{ $mission->location }}
                            </div>
                        @endif
                        <div class="flex items-center">
                            <x-heroicon-m-calendar class="w-4 h-4 mr-1" />
                            {{ __('Publiée le') }} {{ $mission->created_at->format('d/m/Y') }}
                        </div>
                    </div>
                </div>
                <div>
                    <span
                        class="inline-flex items-center rounded-full px-3 py-1 text-sm font-medium bg-green-100 text-green-800 dark:bg-green-900/30 dark:text-green-400">
                        {{ $mission->status->label() }}
                    </span>
                </div>
            </div>

            {{-- Tags --}}
            @if($mission->tags->isNotEmpty())
                <div class="mt-4 flex flex-wrap gap-2">
                    @foreach($mission->tags as $tag)
                        <span
                            class="inline-flex items-center rounded-full bg-blue-100 px-2.5 py-0.5 text-xs font-medium text-blue-800 dark:bg-blue-900/30 dark:text-blue-400">
                            {{ $tag->name }}
                        </span>
                    @endforeach
                </div>
            @endif
        </div>
    </div>

    {{-- Description --}}
    <div
        class="bg-white dark:bg-gray-800 rounded-lg shadow-sm border border-gray-200 dark:border-gray-700 overflow-hidden">
        <div class="p-6">
            <h2 class="text-lg font-semibold text-gray-900 dark:text-white mb-4">
                {{ __('Description de la mission') }}
            </h2>
            <div class="prose dark:prose-invert max-w-none text-gray-600 dark:text-gray-300">
                {!! nl2br(e($mission->description)) !!}
            </div>
        </div>
    </div>

    {{-- Contact commercial --}}
    <div
        class="bg-white dark:bg-gray-800 rounded-lg shadow-sm border border-gray-200 dark:border-gray-700 overflow-hidden">
        <div class="p-6">
            <h2 class="text-lg font-semibold text-gray-900 dark:text-white mb-4">
                {{ __('Contact') }}
            </h2>
            <div class="flex items-center">
                <div class="h-12 w-12 rounded-full bg-gray-200 dark:bg-gray-700 flex items-center justify-center">
                    <x-heroicon-m-user class="w-6 h-6 text-gray-500 dark:text-gray-400" />
                </div>
                <div class="ml-4">
                    <p class="text-sm font-medium text-gray-900 dark:text-white">
                        {{ $mission->commercial->name }}
                    </p>
                    <p class="text-sm text-gray-500 dark:text-gray-400">
                        {{ __('Commercial') }}
                    </p>
                </div>
            </div>
        </div>
    </div>

    {{-- Actions --}}
    <div class="flex items-center justify-between">
        <a href="{{ route('consultant.missions.index') }}" wire:navigate
            class="inline-flex items-center text-sm font-medium text-gray-500 hover:text-gray-700 dark:text-gray-400 dark:hover:text-gray-200">
            <x-heroicon-m-arrow-left class="w-4 h-4 mr-2" />
            {{ __('Retour à la liste') }}
        </a>

        @if($this->hasApplied)
            <div class="flex items-center gap-3">
                <span class="inline-flex items-center rounded-full px-3 py-1.5 text-sm font-medium
                    @switch($this->existingApplication->status)
                        @case(\App\Enums\ApplicationStatus::Pending)
                            bg-yellow-100 text-yellow-800 dark:bg-yellow-900/30 dark:text-yellow-400
                            @break
                        @case(\App\Enums\ApplicationStatus::Viewed)
                            bg-blue-100 text-blue-800 dark:bg-blue-900/30 dark:text-blue-400
                            @break
                        @case(\App\Enums\ApplicationStatus::Accepted)
                            bg-green-100 text-green-800 dark:bg-green-900/30 dark:text-green-400
                            @break
                        @case(\App\Enums\ApplicationStatus::Rejected)
                            bg-red-100 text-red-800 dark:bg-red-900/30 dark:text-red-400
                            @break
                    @endswitch
                ">
                    <x-heroicon-m-check class="w-4 h-4 mr-1.5" />
                    {{ __('Candidature envoyée') }} - {{ $this->existingApplication->status->label() }}
                </span>

                {{-- Withdraw button only for pending applications --}}
                @if($this->existingApplication->status === \App\Enums\ApplicationStatus::Pending)
                    <button
                        type="button"
                        wire:click="withdraw"
                        wire:loading.attr="disabled"
                        wire:confirm="{{ __('Êtes-vous sûr de vouloir retirer votre candidature ?') }}"
                        class="inline-flex items-center rounded-md bg-red-50 px-3 py-1.5 text-sm font-medium text-red-700 hover:bg-red-100 dark:bg-red-900/30 dark:text-red-400 dark:hover:bg-red-900/50"
                    >
                        <x-heroicon-m-x-mark class="w-4 h-4 mr-1" />
                        {{ __('Retirer') }}
                    </button>
                @endif
            </div>
        @else
            <button type="button" wire:click="apply" wire:loading.attr="disabled"
                class="inline-flex items-center rounded-md bg-indigo-600 px-4 py-2.5 text-sm font-semibold text-white shadow-sm hover:bg-indigo-500 focus-visible:outline focus-visible:outline-2 focus-visible:outline-offset-2 focus-visible:outline-indigo-600 disabled:opacity-50 disabled:cursor-not-allowed">
                <span wire:loading.remove wire:target="apply">
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