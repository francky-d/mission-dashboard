<div class="space-y-6">
    {{-- Flash Messages --}}
    @if(session('success'))
        <div class="rounded-lg bg-green-50 p-4 dark:bg-green-900/30">
            <div class="flex">
                <x-heroicon-m-check-circle class="h-5 w-5 text-green-400" />
                <div class="ml-3">
                    <p class="text-sm font-medium text-green-800 dark:text-green-200">
                        {{ session('success') }}
                    </p>
                </div>
            </div>
        </div>
    @endif

    {{-- Search and Filters --}}
    <div class="flex flex-col gap-4 sm:flex-row sm:items-center sm:justify-between">
        <div class="relative flex-1 max-w-md">
            <div class="pointer-events-none absolute inset-y-0 left-0 flex items-center pl-3">
                <x-heroicon-m-magnifying-glass class="h-5 w-5 text-gray-400" />
            </div>
            <input type="search" wire:model.live.debounce.300ms="search"
                placeholder="{{ __('Rechercher une mission...') }}"
                class="block w-full rounded-md border-gray-300 pl-10 shadow-sm focus:border-indigo-500 focus:ring-indigo-500 dark:border-gray-600 dark:bg-gray-700 dark:text-white sm:text-sm" />
        </div>
    </div>

    {{-- Status Filter Tabs --}}
    <div class="border-b border-gray-200 dark:border-gray-700">
        <nav class="-mb-px flex space-x-8" aria-label="Tabs">
            <button type="button" wire:click="$set('status', '')" @class([
                'whitespace-nowrap border-b-2 py-4 px-1 text-sm font-medium',
                'border-indigo-500 text-indigo-600 dark:text-indigo-400' => $status === '',
                'border-transparent text-gray-500 hover:border-gray-300 hover:text-gray-700 dark:text-gray-400 dark:hover:text-gray-300' => $status !== '',
            ])>
                {{ __('Toutes') }}
                <span
                    class="ml-2 rounded-full bg-gray-100 px-2.5 py-0.5 text-xs font-medium text-gray-600 dark:bg-gray-700 dark:text-gray-300">
                    {{ array_sum($statusCounts) }}
                </span>
            </button>

            @foreach($statuses as $statusOption)
                <button type="button" wire:click="$set('status', '{{ $statusOption->value }}')" @class([
                    'whitespace-nowrap border-b-2 py-4 px-1 text-sm font-medium',
                    'border-indigo-500 text-indigo-600 dark:text-indigo-400' => $status === $statusOption->value,
                    'border-transparent text-gray-500 hover:border-gray-300 hover:text-gray-700 dark:text-gray-400 dark:hover:text-gray-300' => $status !== $statusOption->value,
                ])>
                    {{ $statusOption->label() }}
                    <span @class([
                        'ml-2 rounded-full px-2.5 py-0.5 text-xs font-medium',
                        'bg-green-100 text-green-600 dark:bg-green-900/30 dark:text-green-400' => $statusOption === \App\Enums\MissionStatus::Active,
                        'bg-gray-100 text-gray-600 dark:bg-gray-700 dark:text-gray-400' => $statusOption === \App\Enums\MissionStatus::Archived,
                    ])>
                        {{ $statusCounts[$statusOption->value] ?? 0 }}
                    </span>
                </button>
            @endforeach
        </nav>
    </div>

    {{-- Missions List --}}
    @if($missions->isEmpty())
        <div class="rounded-lg border-2 border-dashed border-gray-300 p-12 text-center dark:border-gray-600">
            <x-heroicon-o-briefcase class="mx-auto h-12 w-12 text-gray-400" />
            <h3 class="mt-4 text-lg font-medium text-gray-900 dark:text-gray-100">
                {{ __('Aucune mission') }}
            </h3>
            <p class="mt-2 text-gray-500 dark:text-gray-400">
                @if($search || $status)
                    {{ __('Aucune mission ne correspond à vos critères.') }}
                @else
                    {{ __('Créez votre première mission pour commencer.') }}
                @endif
            </p>
            @if(!$search && !$status)
                <a href="{{ route('commercial.missions.create') }}" wire:navigate
                    class="mt-4 inline-flex items-center rounded-md bg-indigo-600 px-4 py-2 text-sm font-medium text-white hover:bg-indigo-700">
                    <x-heroicon-m-plus class="w-4 h-4 mr-2" />
                    {{ __('Créer une mission') }}
                </a>
            @endif
        </div>
    @else
        <div class="space-y-4">
            @foreach($missions as $mission)
                <div class="overflow-hidden rounded-lg bg-white shadow transition hover:shadow-md dark:bg-gray-800">
                    <div class="p-6">
                        <div class="flex items-start justify-between">
                            <div class="flex-1">
                                <div class="flex items-center gap-3">
                                    <a href="{{ route('commercial.missions.show', $mission) }}" wire:navigate
                                        class="text-lg font-semibold text-gray-900 hover:text-indigo-600 dark:text-gray-100 dark:hover:text-indigo-400">
                                        {{ $mission->title }}
                                    </a>
                                    <span @class([
                                        'inline-flex items-center rounded-full px-2.5 py-0.5 text-xs font-medium',
                                        'bg-green-100 text-green-800 dark:bg-green-900/30 dark:text-green-400' => $mission->status === \App\Enums\MissionStatus::Active,
                                        'bg-gray-100 text-gray-800 dark:bg-gray-700 dark:text-gray-400' => $mission->status === \App\Enums\MissionStatus::Archived,
                                    ])>
                                        {{ $mission->status->label() }}
                                    </span>
                                </div>
                                <p class="mt-1 text-sm text-gray-500 dark:text-gray-400">
                                    {{ __('Publiée le') }} {{ $mission->created_at->format('d/m/Y') }}
                                </p>
                            </div>
                            <div class="ml-4 flex items-center gap-2">
                                {{-- Candidatures count --}}
                                <span
                                    class="inline-flex items-center rounded-full bg-indigo-100 px-3 py-1 text-sm font-medium text-indigo-800 dark:bg-indigo-900/30 dark:text-indigo-400">
                                    <x-heroicon-m-users class="w-4 h-4 mr-1" />
                                    {{ $mission->applications_count }} {{ __('candidature(s)') }}
                                </span>
                            </div>
                        </div>

                        <div class="mt-4 flex flex-wrap items-center gap-4 text-sm text-gray-500 dark:text-gray-400">
                            @if($mission->location)
                                <div class="flex items-center">
                                    <x-heroicon-m-map-pin class="w-4 h-4 mr-1" />
                                    {{ $mission->location }}
                                </div>
                            @endif
                            @if($mission->daily_rate)
                                <div class="flex items-center">
                                    <x-heroicon-m-currency-euro class="w-4 h-4 mr-1" />
                                    {{ number_format($mission->daily_rate, 0, ',', ' ') }} €/jour
                                </div>
                            @endif
                        </div>

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

                        <div class="mt-4 flex items-center justify-between border-t border-gray-100 pt-4 dark:border-gray-700">
                            <div class="flex items-center gap-2">
                                <a href="{{ route('commercial.missions.show', $mission) }}" wire:navigate
                                    class="inline-flex items-center text-sm font-medium text-indigo-600 hover:text-indigo-500 dark:text-indigo-400">
                                    {{ __('Voir les candidatures') }}
                                    <x-heroicon-m-arrow-right class="ml-1 h-4 w-4" />
                                </a>
                            </div>

                            <div class="flex items-center gap-2">
                                <a href="{{ route('commercial.missions.edit', $mission) }}" wire:navigate
                                    class="inline-flex items-center rounded-md bg-gray-100 px-3 py-1.5 text-sm font-medium text-gray-700 hover:bg-gray-200 dark:bg-gray-700 dark:text-gray-300 dark:hover:bg-gray-600">
                                    <x-heroicon-m-pencil class="w-4 h-4 mr-1" />
                                    {{ __('Modifier') }}
                                </a>

                                @if($mission->status === \App\Enums\MissionStatus::Active)
                                    <button type="button" wire:click="archive({{ $mission->id }})"
                                        wire:confirm="{{ __('Êtes-vous sûr de vouloir archiver cette mission ?') }}"
                                        class="inline-flex items-center rounded-md bg-gray-100 px-3 py-1.5 text-sm font-medium text-gray-700 hover:bg-gray-200 dark:bg-gray-700 dark:text-gray-300 dark:hover:bg-gray-600">
                                        <x-heroicon-m-archive-box class="w-4 h-4 mr-1" />
                                        {{ __('Archiver') }}
                                    </button>
                                @else
                                    <button type="button" wire:click="activate({{ $mission->id }})"
                                        class="inline-flex items-center rounded-md bg-green-100 px-3 py-1.5 text-sm font-medium text-green-700 hover:bg-green-200 dark:bg-green-900/30 dark:text-green-400 dark:hover:bg-green-900/50">
                                        <x-heroicon-m-arrow-path class="w-4 h-4 mr-1" />
                                        {{ __('Réactiver') }}
                                    </button>
                                @endif
                            </div>
                        </div>
                    </div>
                </div>
            @endforeach
        </div>

        {{-- Pagination --}}
        <div class="mt-6">
            {{ $missions->links() }}
        </div>
    @endif
</div>