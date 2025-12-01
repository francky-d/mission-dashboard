<div>
    <!-- Search and Filters -->
    <div class="mb-6 space-y-4">
        <!-- Search Bar -->
        <div class="relative">
            <div class="pointer-events-none absolute inset-y-0 left-0 flex items-center pl-3">
                <x-heroicon-m-magnifying-glass class="h-5 w-5 text-gray-400" />
            </div>
            <input type="text" wire:model.live.debounce.300ms="search"
                placeholder="{{ __('Rechercher une mission (titre, description, lieu)...') }}"
                class="block w-full rounded-lg border border-gray-300 bg-white py-3 pl-10 pr-4 text-gray-900 placeholder-gray-500 focus:border-indigo-500 focus:ring-indigo-500 dark:border-gray-600 dark:bg-gray-800 dark:text-gray-100 dark:placeholder-gray-400" />
        </div>

        <!-- Tag Filters -->
        @if($this->availableTags->isNotEmpty())
            <div class="rounded-lg border border-gray-200 bg-white p-4 dark:border-gray-700 dark:bg-gray-800">
                <div class="flex items-center justify-between mb-3">
                    <h3 class="text-sm font-medium text-gray-900 dark:text-gray-100">
                        {{ __('Filtrer par compétences') }}
                    </h3>
                    @if(!empty($selectedTags) || $search)
                        <button type="button" wire:click="clearFilters"
                            class="text-sm text-indigo-600 hover:text-indigo-500 dark:text-indigo-400 dark:hover:text-indigo-300">
                            {{ __('Effacer les filtres') }}
                        </button>
                    @endif
                </div>
                <div class="flex flex-wrap gap-2">
                    @foreach($this->availableTags as $tag)
                        <button type="button" wire:click="toggleTag({{ $tag->id }})" @class([
                            'inline-flex items-center rounded-full px-3 py-1.5 text-sm font-medium transition-colors',
                            'bg-indigo-600 text-white hover:bg-indigo-700' => in_array($tag->id, $selectedTags),
                            'bg-gray-100 text-gray-700 hover:bg-gray-200 dark:bg-gray-700 dark:text-gray-300 dark:hover:bg-gray-600' => !in_array($tag->id, $selectedTags),
                        ])>
                            {{ $tag->name }}
                            @if(in_array($tag->id, $selectedTags))
                                <x-heroicon-m-x-mark class="ml-1.5 h-4 w-4" />
                            @endif
                        </button>
                    @endforeach
                </div>
            </div>
        @endif

        <!-- Active Filters Summary -->
        @if(!empty($selectedTags))
            <div class="flex items-center gap-2 text-sm text-gray-600 dark:text-gray-400">
                <x-heroicon-m-funnel class="h-4 w-4" />
                <span>{{ count($selectedTags) }}
                    {{ trans_choice('compétence sélectionnée|compétences sélectionnées', count($selectedTags)) }}</span>
            </div>
        @endif
    </div>

    <!-- Missions List -->
    @if($missions->isEmpty())
        <div class="rounded-lg border-2 border-dashed border-gray-300 p-12 text-center dark:border-gray-600">
            <x-heroicon-o-document-text class="mx-auto h-12 w-12 text-gray-400" />
            <h3 class="mt-4 text-lg font-medium text-gray-900 dark:text-gray-100">
                {{ __('Aucune mission disponible') }}
            </h3>
            <p class="mt-2 text-gray-500 dark:text-gray-400">
                @if($search || !empty($selectedTags))
                    {{ __('Aucune mission ne correspond à vos critères de recherche.') }}
                @else
                    {{ __('Il n\'y a actuellement aucune mission active.') }}
                @endif
            </p>
            @if($search || !empty($selectedTags))
                <button type="button" wire:click="clearFilters"
                    class="mt-4 inline-flex items-center rounded-md bg-indigo-600 px-4 py-2 text-sm font-medium text-white hover:bg-indigo-700">
                    {{ __('Effacer les filtres') }}
                </button>
            @endif
        </div>
    @else
        <div class="space-y-4">
            @foreach($missions as $mission)
                <div class="overflow-hidden rounded-lg bg-white shadow transition hover:shadow-md dark:bg-gray-800">
                    <div class="p-6">
                        <div class="flex items-start justify-between">
                            <div class="flex-1">
                                <a href="{{ route('consultant.missions.show', $mission) }}" class="block" wire:navigate>
                                    <h3
                                        class="text-lg font-semibold text-gray-900 hover:text-indigo-600 dark:text-gray-100 dark:hover:text-indigo-400">
                                        {{ $mission->title }}
                                    </h3>
                                </a>
                                <p class="mt-1 text-sm text-gray-500 dark:text-gray-400">
                                    {{ __('Publié par') }} {{ $mission->commercial->name }}
                                    · {{ $mission->created_at->diffForHumans() }}
                                </p>
                            </div>
                            @if($mission->daily_rate)
                                <div class="ml-4 flex-shrink-0">
                                    <span
                                        class="inline-flex items-center rounded-full bg-green-100 px-3 py-1 text-sm font-medium text-green-800 dark:bg-green-900 dark:text-green-200">
                                        {{ number_format($mission->daily_rate, 0, ',', ' ') }} €/jour
                                    </span>
                                </div>
                            @endif
                        </div>

                        <p class="mt-3 line-clamp-2 text-gray-600 dark:text-gray-300">
                            {{ Str::limit($mission->description, 200) }}
                        </p>

                        <div class="mt-4 flex flex-wrap items-center gap-2">
                            @if($mission->location)
                                <span class="inline-flex items-center text-sm text-gray-500 dark:text-gray-400">
                                    <x-heroicon-m-map-pin class="mr-1 h-4 w-4" />
                                    {{ $mission->location }}
                                </span>
                            @endif
                        </div>

                        @if($mission->tags->isNotEmpty())
                            <div class="mt-4 flex flex-wrap gap-2">
                                @foreach($mission->tags as $tag)
                                    <span @class([
                                        'inline-flex items-center rounded-full px-2.5 py-0.5 text-xs font-medium',
                                        'bg-indigo-600 text-white' => in_array($tag->id, $selectedTags),
                                        'bg-indigo-100 text-indigo-800 dark:bg-indigo-900 dark:text-indigo-200' => !in_array($tag->id, $selectedTags),
                                    ])>
                                        {{ $tag->name }}
                                    </span>
                                @endforeach
                            </div>
                        @endif

                        <div class="mt-4">
                            <a href="{{ route('consultant.missions.show', $mission) }}" wire:navigate
                                class="inline-flex items-center text-sm font-medium text-indigo-600 hover:text-indigo-500 dark:text-indigo-400 dark:hover:text-indigo-300">
                                {{ __('Voir les détails') }}
                                <x-heroicon-m-arrow-right class="ml-1 h-4 w-4" />
                            </a>
                        </div>
                    </div>
                </div>
            @endforeach
        </div>

        <!-- Pagination -->
        <div class="mt-6">
            {{ $missions->links() }}
        </div>
    @endif
</div>