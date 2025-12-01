<div class="space-y-6">
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
                        'bg-yellow-100 text-yellow-600 dark:bg-yellow-900/30 dark:text-yellow-400' => $statusOption === \App\Enums\ApplicationStatus::Pending,
                        'bg-blue-100 text-blue-600 dark:bg-blue-900/30 dark:text-blue-400' => $statusOption === \App\Enums\ApplicationStatus::Viewed,
                        'bg-green-100 text-green-600 dark:bg-green-900/30 dark:text-green-400' => $statusOption === \App\Enums\ApplicationStatus::Accepted,
                        'bg-red-100 text-red-600 dark:bg-red-900/30 dark:text-red-400' => $statusOption === \App\Enums\ApplicationStatus::Rejected,
                    ])>
                        {{ $statusCounts[$statusOption->value] ?? 0 }}
                    </span>
                </button>
            @endforeach
        </nav>
    </div>

    {{-- Applications List --}}
    @if($applications->isEmpty())
        <div class="rounded-lg border-2 border-dashed border-gray-300 p-12 text-center dark:border-gray-600">
            <x-heroicon-o-document-text class="mx-auto h-12 w-12 text-gray-400" />
            <h3 class="mt-4 text-lg font-medium text-gray-900 dark:text-gray-100">
                {{ __('Aucune candidature') }}
            </h3>
            <p class="mt-2 text-gray-500 dark:text-gray-400">
                @if($status)
                    {{ __('Vous n\'avez pas de candidature avec ce statut.') }}
                @else
                    {{ __('Vous n\'avez pas encore postulé à une mission.') }}
                @endif
            </p>
            <a href="{{ route('consultant.missions.index') }}" wire:navigate
                class="mt-4 inline-flex items-center rounded-md bg-indigo-600 px-4 py-2 text-sm font-medium text-white hover:bg-indigo-700">
                {{ __('Voir les missions disponibles') }}
            </a>
        </div>
    @else
        <div class="space-y-4">
            @foreach($applications as $application)
                <div class="overflow-hidden rounded-lg bg-white shadow transition hover:shadow-md dark:bg-gray-800">
                    <div class="p-6">
                        <div class="flex items-start justify-between">
                            <div class="flex-1">
                                <a href="{{ route('consultant.missions.show', $application->mission) }}" wire:navigate
                                    class="block">
                                    <h3
                                        class="text-lg font-semibold text-gray-900 hover:text-indigo-600 dark:text-gray-100 dark:hover:text-indigo-400">
                                        {{ $application->mission->title }}
                                    </h3>
                                </a>
                                <p class="mt-1 text-sm text-gray-500 dark:text-gray-400">
                                    {{ __('Candidature envoyée le') }} {{ $application->created_at->format('d/m/Y à H:i') }}
                                </p>
                            </div>
                            <div class="ml-4 flex-shrink-0">
                                <span @class([
                                    'inline-flex items-center rounded-full px-3 py-1 text-sm font-medium',
                                    'bg-yellow-100 text-yellow-800 dark:bg-yellow-900/30 dark:text-yellow-400' => $application->status === \App\Enums\ApplicationStatus::Pending,
                                    'bg-blue-100 text-blue-800 dark:bg-blue-900/30 dark:text-blue-400' => $application->status === \App\Enums\ApplicationStatus::Viewed,
                                    'bg-green-100 text-green-800 dark:bg-green-900/30 dark:text-green-400' => $application->status === \App\Enums\ApplicationStatus::Accepted,
                                    'bg-red-100 text-red-800 dark:bg-red-900/30 dark:text-red-400' => $application->status === \App\Enums\ApplicationStatus::Rejected,
                                ])>
                                    {{ $application->status->label() }}
                                </span>
                            </div>
                        </div>

                        <div class="mt-4 flex flex-wrap items-center gap-4 text-sm text-gray-500 dark:text-gray-400">
                            @if($application->mission->location)
                                <div class="flex items-center">
                                    <x-heroicon-m-map-pin class="w-4 h-4 mr-1" />
                                    {{ $application->mission->location }}
                                </div>
                            @endif
                            @if($application->mission->daily_rate)
                                <div class="flex items-center">
                                    <x-heroicon-m-currency-euro class="w-4 h-4 mr-1" />
                                    {{ number_format($application->mission->daily_rate, 0, ',', ' ') }} €/jour
                                </div>
                            @endif
                            <div class="flex items-center">
                                <x-heroicon-m-user class="w-4 h-4 mr-1" />
                                {{ $application->mission->commercial->name }}
                            </div>
                        </div>

                        @if($application->mission->tags->isNotEmpty())
                            <div class="mt-4 flex flex-wrap gap-2">
                                @foreach($application->mission->tags as $tag)
                                    <span
                                        class="inline-flex items-center rounded-full bg-indigo-100 px-2.5 py-0.5 text-xs font-medium text-indigo-800 dark:bg-indigo-900 dark:text-indigo-200">
                                        {{ $tag->name }}
                                    </span>
                                @endforeach
                            </div>
                        @endif

                        <div class="mt-4">
                            <a href="{{ route('consultant.missions.show', $application->mission) }}" wire:navigate
                                class="inline-flex items-center text-sm font-medium text-indigo-600 hover:text-indigo-500 dark:text-indigo-400 dark:hover:text-indigo-300">
                                {{ __('Voir la mission') }}
                                <x-heroicon-m-arrow-right class="ml-1 h-4 w-4" />
                            </a>
                        </div>
                    </div>
                </div>
            @endforeach
        </div>

        {{-- Pagination --}}
        <div class="mt-6">
            {{ $applications->links() }}
        </div>
    @endif
</div>