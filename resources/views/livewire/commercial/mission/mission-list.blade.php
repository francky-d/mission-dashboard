<div class="space-y-6">
    {{-- Flash Messages --}}
    @if(session('success'))
        <div class="rounded-xl bg-emerald-50 p-4 border border-emerald-200">
            <div class="flex">
                <x-heroicon-m-check-circle class="h-5 w-5 text-emerald-500" />
                <div class="ml-3">
                    <p class="text-sm font-medium text-emerald-800">
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
                <x-heroicon-m-magnifying-glass class="h-5 w-5 text-slate-400" />
            </div>
            <input type="search" wire:model.live.debounce.300ms="search"
                placeholder="{{ __('Rechercher une mission...') }}"
                class="block w-full rounded-lg border-slate-300 pl-10 shadow-sm focus:border-indigo-500 focus:ring-indigo-500 sm:text-sm" />
        </div>
    </div>

    {{-- Status Filter Tabs --}}
    <div class="border-b border-slate-200">
        <nav class="-mb-px flex space-x-8" aria-label="Tabs">
            <button type="button" wire:click="$set('status', '')" @class([
                'whitespace-nowrap border-b-2 py-4 px-1 text-sm font-medium transition-colors',
                'border-indigo-600 text-indigo-600' => $status === '',
                'border-transparent text-slate-600 hover:border-slate-300 hover:text-slate-900' => $status !== '',
            ])>
                {{ __('Toutes') }}
                <span class="ml-2 rounded-full bg-slate-100 px-2.5 py-0.5 text-xs font-medium text-slate-700">
                    {{ array_sum($statusCounts) }}
                </span>
            </button>

            @foreach($statuses as $statusOption)
                <button type="button" wire:click="$set('status', '{{ $statusOption->value }}')" @class([
                    'whitespace-nowrap border-b-2 py-4 px-1 text-sm font-medium transition-colors',
                    'border-indigo-600 text-indigo-600' => $status === $statusOption->value,
                    'border-transparent text-slate-600 hover:border-slate-300 hover:text-slate-900' => $status !== $statusOption->value,
                ])>
                    {{ $statusOption->label() }}
                    <span @class([
                        'ml-2 rounded-full px-2.5 py-0.5 text-xs font-medium',
                        'bg-emerald-100 text-emerald-800' => $statusOption === \App\Enums\MissionStatus::Active,
                        'bg-slate-100 text-slate-700' => $statusOption === \App\Enums\MissionStatus::Archived,
                    ])>
                        {{ $statusCounts[$statusOption->value] ?? 0 }}
                    </span>
                </button>
            @endforeach
        </nav>
    </div>

    {{-- Missions List --}}
    @if($missions->isEmpty())
        <div class="rounded-xl border-2 border-dashed border-slate-300 p-12 text-center bg-white">
            <x-heroicon-o-briefcase class="mx-auto h-12 w-12 text-slate-400" />
            <h3 class="mt-4 text-lg font-semibold text-slate-900">
                {{ __('Aucune mission') }}
            </h3>
            <p class="mt-2 text-slate-600">
                @if($search || $status)
                    {{ __('Aucune mission ne correspond à vos critères.') }}
                @else
                    {{ __('Créez votre première mission pour commencer.') }}
                @endif
            </p>
            @if(!$search && !$status)
                <a href="{{ route('commercial.missions.create') }}" wire:navigate
                    class="mt-4 inline-flex items-center rounded-lg bg-indigo-600 px-4 py-2 text-sm font-semibold text-white hover:bg-indigo-700 transition-colors">
                    <x-heroicon-m-plus class="w-4 h-4 mr-2" />
                    {{ __('Créer une mission') }}
                </a>
            @endif
        </div>
    @else
        <div class="space-y-4">
            @foreach($missions as $mission)
                <div
                    class="overflow-hidden rounded-xl bg-white shadow-sm border border-slate-200 transition-all hover:shadow-md hover:border-slate-300">
                    <div class="p-6">
                        <div class="flex items-start justify-between">
                            <div class="flex-1">
                                <div class="flex items-center gap-3">
                                    <a href="{{ route('commercial.missions.show', $mission) }}" wire:navigate
                                        class="text-lg font-semibold text-slate-900 hover:text-indigo-600 transition-colors">
                                        {{ $mission->title }}
                                    </a>
                                    <span @class([
                                        'inline-flex items-center rounded-full px-2.5 py-0.5 text-xs font-medium',
                                        'bg-emerald-100 text-emerald-800' => $mission->status === \App\Enums\MissionStatus::Active,
                                        'bg-slate-100 text-slate-700' => $mission->status === \App\Enums\MissionStatus::Archived,
                                    ])>
                                        {{ $mission->status->label() }}
                                    </span>
                                </div>
                                <p class="mt-1 text-sm text-slate-600">
                                    {{ __('Publiée le') }} {{ $mission->created_at->format('d/m/Y') }}
                                </p>
                            </div>
                            <div class="ml-4 flex items-center gap-2">
                                {{-- Candidatures count --}}
                                <span
                                    class="inline-flex items-center rounded-full bg-indigo-50 px-3 py-1 text-sm font-medium text-indigo-700 border border-indigo-200">
                                    <x-heroicon-m-users class="w-4 h-4 mr-1" />
                                    {{ $mission->applications_count }} {{ __('candidature(s)') }}
                                </span>
                            </div>
                        </div>

                        <div class="mt-4 flex flex-wrap items-center gap-4 text-sm text-slate-600">
                            @if($mission->location)
                                <div class="flex items-center">
                                    <x-heroicon-m-map-pin class="w-4 h-4 mr-1" />
                                    {{ $mission->location }}
                                </div>
                            @endif
                        </div>

                        @if($mission->tags->isNotEmpty())
                            <div class="mt-4 flex flex-wrap gap-2">
                                @foreach($mission->tags as $tag)
                                    <span
                                        class="inline-flex items-center rounded-full bg-sky-50 px-2.5 py-0.5 text-xs font-medium text-sky-700 border border-sky-200">
                                        {{ $tag->name }}
                                    </span>
                                @endforeach
                            </div>
                        @endif

                        <div class="mt-4 flex items-center justify-between border-t border-slate-100 pt-4">
                            <div class="flex items-center gap-2">
                                <a href="{{ route('commercial.missions.show', $mission) }}" wire:navigate
                                    class="inline-flex items-center text-sm font-medium text-indigo-600 hover:text-indigo-800 transition-colors">
                                    {{ __('Voir les candidatures') }}
                                    <x-heroicon-m-arrow-right class="ml-1 h-4 w-4" />
                                </a>
                            </div>

                            <div class="flex items-center gap-2">
                                <a href="{{ route('commercial.missions.edit', $mission) }}" wire:navigate
                                    class="inline-flex items-center rounded-lg bg-slate-100 px-3 py-1.5 text-sm font-medium text-slate-700 hover:bg-slate-200 transition-colors">
                                    <x-heroicon-m-pencil class="w-4 h-4 mr-1" />
                                    {{ __('Modifier') }}
                                </a>

                                @if($mission->status === \App\Enums\MissionStatus::Active)
                                    <button type="button" wire:click="archive({{ $mission->id }})"
                                        wire:confirm="{{ __('Êtes-vous sûr de vouloir archiver cette mission ?') }}"
                                        class="inline-flex items-center rounded-lg bg-slate-100 px-3 py-1.5 text-sm font-medium text-slate-700 hover:bg-slate-200 transition-colors">
                                        <x-heroicon-m-archive-box class="w-4 h-4 mr-1" />
                                        {{ __('Archiver') }}
                                    </button>
                                @else
                                    <button type="button" wire:click="activate({{ $mission->id }})"
                                        class="inline-flex items-center rounded-lg bg-emerald-50 px-3 py-1.5 text-sm font-medium text-emerald-700 hover:bg-emerald-100 border border-emerald-200 transition-colors">
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