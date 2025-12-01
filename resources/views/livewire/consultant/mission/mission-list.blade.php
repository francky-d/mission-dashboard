<div>
    <!-- Search Bar -->
    <div class="mb-6">
        <div class="relative">
            <div class="pointer-events-none absolute inset-y-0 left-0 flex items-center pl-3">
                <svg class="h-5 w-5 text-gray-400" fill="none" stroke="currentColor" viewBox="0 0 24 24">
                    <path stroke-linecap="round" stroke-linejoin="round" stroke-width="2"
                        d="M21 21l-6-6m2-5a7 7 0 11-14 0 7 7 0 0114 0z" />
                </svg>
            </div>
            <input type="text" wire:model.live.debounce.300ms="search"
                placeholder="{{ __('Rechercher une mission (titre, description, lieu)...') }}"
                class="block w-full rounded-lg border border-gray-300 bg-white py-3 pl-10 pr-4 text-gray-900 placeholder-gray-500 focus:border-indigo-500 focus:ring-indigo-500 dark:border-gray-600 dark:bg-gray-800 dark:text-gray-100 dark:placeholder-gray-400" />
        </div>
    </div>

    <!-- Missions List -->
    @if($missions->isEmpty())
        <div class="rounded-lg border-2 border-dashed border-gray-300 p-12 text-center dark:border-gray-600">
            <svg class="mx-auto h-12 w-12 text-gray-400" fill="none" stroke="currentColor" viewBox="0 0 24 24">
                <path stroke-linecap="round" stroke-linejoin="round" stroke-width="2"
                    d="M9 12h6m-6 4h6m2 5H7a2 2 0 01-2-2V5a2 2 0 012-2h5.586a1 1 0 01.707.293l5.414 5.414a1 1 0 01.293.707V19a2 2 0 01-2 2z" />
            </svg>
            <h3 class="mt-4 text-lg font-medium text-gray-900 dark:text-gray-100">
                {{ __('Aucune mission disponible') }}
            </h3>
            <p class="mt-2 text-gray-500 dark:text-gray-400">
                @if($search)
                    {{ __('Aucune mission ne correspond à votre recherche.') }}
                @else
                    {{ __('Il n\'y a actuellement aucune mission active.') }}
                @endif
            </p>
        </div>
    @else
        <div class="space-y-4">
            @foreach($missions as $mission)
                <div class="overflow-hidden rounded-lg bg-white shadow transition hover:shadow-md dark:bg-gray-800">
                    <div class="p-6">
                        <div class="flex items-start justify-between">
                            <div class="flex-1">
                                <a href="{{ route('consultant.missions.show', $mission) }}" class="block">
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
                                    <svg class="mr-1 h-4 w-4" fill="none" stroke="currentColor" viewBox="0 0 24 24">
                                        <path stroke-linecap="round" stroke-linejoin="round" stroke-width="2"
                                            d="M17.657 16.657L13.414 20.9a1.998 1.998 0 01-2.827 0l-4.244-4.243a8 8 0 1111.314 0z" />
                                        <path stroke-linecap="round" stroke-linejoin="round" stroke-width="2"
                                            d="M15 11a3 3 0 11-6 0 3 3 0 016 0z" />
                                    </svg>
                                    {{ $mission->location }}
                                </span>
                            @endif
                        </div>

                        @if($mission->tags->isNotEmpty())
                            <div class="mt-4 flex flex-wrap gap-2">
                                @foreach($mission->tags as $tag)
                                    <span
                                        class="inline-flex items-center rounded-full bg-indigo-100 px-2.5 py-0.5 text-xs font-medium text-indigo-800 dark:bg-indigo-900 dark:text-indigo-200">
                                        {{ $tag->name }}
                                    </span>
                                @endforeach
                            </div>
                        @endif

                        <div class="mt-4">
                            <a href="{{ route('consultant.missions.show', $mission) }}"
                                class="inline-flex items-center text-sm font-medium text-indigo-600 hover:text-indigo-500 dark:text-indigo-400 dark:hover:text-indigo-300">
                                {{ __('Voir les détails') }}
                                <svg class="ml-1 h-4 w-4" fill="none" stroke="currentColor" viewBox="0 0 24 24">
                                    <path stroke-linecap="round" stroke-linejoin="round" stroke-width="2" d="M9 5l7 7-7 7" />
                                </svg>
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