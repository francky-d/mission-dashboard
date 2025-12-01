<div class="space-y-6">
    {{-- Stats Cards --}}
    <div class="grid grid-cols-1 gap-6 sm:grid-cols-2 lg:grid-cols-4">
        {{-- Available Missions --}}
        <div class="overflow-hidden rounded-lg bg-white shadow dark:bg-gray-800">
            <div class="p-5">
                <div class="flex items-center">
                    <div class="flex-shrink-0">
                        <x-heroicon-o-briefcase class="h-6 w-6 text-gray-400" />
                    </div>
                    <div class="ml-5 w-0 flex-1">
                        <dl>
                            <dt class="truncate text-sm font-medium text-gray-500 dark:text-gray-400">
                                {{ __('Missions disponibles') }}
                            </dt>
                            <dd class="text-lg font-semibold text-gray-900 dark:text-white">
                                {{ $availableMissions }}
                            </dd>
                        </dl>
                    </div>
                </div>
            </div>
        </div>

        {{-- My Applications --}}
        <div class="overflow-hidden rounded-lg bg-white shadow dark:bg-gray-800">
            <div class="p-5">
                <div class="flex items-center">
                    <div class="flex-shrink-0">
                        <x-heroicon-o-document-text class="h-6 w-6 text-indigo-400" />
                    </div>
                    <div class="ml-5 w-0 flex-1">
                        <dl>
                            <dt class="truncate text-sm font-medium text-gray-500 dark:text-gray-400">
                                {{ __('Mes candidatures') }}
                            </dt>
                            <dd class="text-lg font-semibold text-gray-900 dark:text-white">
                                {{ $myApplications }}
                            </dd>
                        </dl>
                    </div>
                </div>
            </div>
        </div>

        {{-- Pending Applications --}}
        <div class="overflow-hidden rounded-lg bg-white shadow dark:bg-gray-800">
            <div class="p-5">
                <div class="flex items-center">
                    <div class="flex-shrink-0">
                        <x-heroicon-o-clock class="h-6 w-6 text-yellow-400" />
                    </div>
                    <div class="ml-5 w-0 flex-1">
                        <dl>
                            <dt class="truncate text-sm font-medium text-gray-500 dark:text-gray-400">
                                {{ __('En attente') }}
                            </dt>
                            <dd class="text-lg font-semibold text-gray-900 dark:text-white">
                                {{ $pendingApplications }}
                            </dd>
                        </dl>
                    </div>
                </div>
            </div>
        </div>

        {{-- Accepted Applications --}}
        <div class="overflow-hidden rounded-lg bg-white shadow dark:bg-gray-800">
            <div class="p-5">
                <div class="flex items-center">
                    <div class="flex-shrink-0">
                        <x-heroicon-o-check-circle class="h-6 w-6 text-green-400" />
                    </div>
                    <div class="ml-5 w-0 flex-1">
                        <dl>
                            <dt class="truncate text-sm font-medium text-gray-500 dark:text-gray-400">
                                {{ __('Acceptées') }}
                            </dt>
                            <dd class="text-lg font-semibold text-gray-900 dark:text-white">
                                {{ $acceptedApplications }}
                            </dd>
                        </dl>
                    </div>
                </div>
            </div>
        </div>
    </div>

    <div class="grid grid-cols-1 gap-6 lg:grid-cols-2">
        {{-- Recent Applications --}}
        <div class="overflow-hidden rounded-lg bg-white shadow dark:bg-gray-800">
            <div class="p-6">
                <div class="flex items-center justify-between">
                    <h3 class="text-base font-semibold text-gray-900 dark:text-white">
                        {{ __('Mes dernières candidatures') }}
                    </h3>
                    <a href="{{ route('consultant.applications.index') }}" wire:navigate
                        class="text-sm font-medium text-indigo-600 hover:text-indigo-500 dark:text-indigo-400">
                        {{ __('Voir tout') }}
                    </a>
                </div>
                <div class="mt-6 flow-root">
                    @if($recentApplications->isEmpty())
                        <p class="text-sm text-gray-500 dark:text-gray-400">
                            {{ __('Aucune candidature.') }}
                        </p>
                    @else
                        <ul role="list" class="-my-5 divide-y divide-gray-200 dark:divide-gray-700">
                            @foreach($recentApplications as $application)
                                <li class="py-4">
                                    <div class="flex items-center justify-between">
                                        <div class="min-w-0 flex-1">
                                            <a href="{{ route('consultant.missions.show', $application->mission) }}"
                                                wire:navigate
                                                class="truncate text-sm font-medium text-gray-900 hover:text-indigo-600 dark:text-white dark:hover:text-indigo-400">
                                                {{ $application->mission->title }}
                                            </a>
                                            <p class="truncate text-sm text-gray-500 dark:text-gray-400">
                                                {{ $application->mission->commercial->name ?? '-' }}
                                            </p>
                                        </div>
                                        <div class="ml-4">
                                            <span @class([
                                                'inline-flex items-center rounded-full px-2.5 py-0.5 text-xs font-medium',
                                                'bg-yellow-100 text-yellow-800 dark:bg-yellow-900/30 dark:text-yellow-400' => $application->status === \App\Enums\ApplicationStatus::Pending,
                                                'bg-blue-100 text-blue-800 dark:bg-blue-900/30 dark:text-blue-400' => $application->status === \App\Enums\ApplicationStatus::Viewed,
                                                'bg-green-100 text-green-800 dark:bg-green-900/30 dark:text-green-400' => $application->status === \App\Enums\ApplicationStatus::Accepted,
                                                'bg-red-100 text-red-800 dark:bg-red-900/30 dark:text-red-400' => $application->status === \App\Enums\ApplicationStatus::Rejected,
                                            ])>
                                                {{ $application->status->label() }}
                                            </span>
                                        </div>
                                    </div>
                                </li>
                            @endforeach
                        </ul>
                    @endif
                </div>
            </div>
        </div>

        {{-- Recommended Missions --}}
        <div class="overflow-hidden rounded-lg bg-white shadow dark:bg-gray-800">
            <div class="p-6">
                <div class="flex items-center justify-between">
                    <h3 class="text-base font-semibold text-gray-900 dark:text-white">
                        {{ __('Missions recommandées') }}
                    </h3>
                    <a href="{{ route('consultant.missions.index') }}" wire:navigate
                        class="text-sm font-medium text-indigo-600 hover:text-indigo-500 dark:text-indigo-400">
                        {{ __('Voir tout') }}
                    </a>
                </div>
                <div class="mt-6 flow-root">
                    @if($recommendedMissions->isEmpty())
                        <p class="text-sm text-gray-500 dark:text-gray-400">
                            {{ __('Aucune mission disponible.') }}
                        </p>
                    @else
                        <ul role="list" class="-my-5 divide-y divide-gray-200 dark:divide-gray-700">
                            @foreach($recommendedMissions as $mission)
                                <li class="py-4">
                                    <div class="flex items-center justify-between">
                                        <div class="min-w-0 flex-1">
                                            <a href="{{ route('consultant.missions.show', $mission) }}" wire:navigate
                                                class="truncate text-sm font-medium text-gray-900 hover:text-indigo-600 dark:text-white dark:hover:text-indigo-400">
                                                {{ $mission->title }}
                                            </a>
                                            <p class="truncate text-sm text-gray-500 dark:text-gray-400">
                                                {{ $mission->location }} • {{ $mission->daily_rate }}€/jour
                                            </p>
                                        </div>
                                        <div class="ml-4 flex flex-wrap gap-1">
                                            @foreach($mission->tags->take(2) as $tag)
                                                <span
                                                    class="inline-flex items-center rounded-full bg-indigo-100 px-2 py-0.5 text-xs font-medium text-indigo-800 dark:bg-indigo-900/30 dark:text-indigo-400">
                                                    {{ $tag->name }}
                                                </span>
                                            @endforeach
                                        </div>
                                    </div>
                                </li>
                            @endforeach
                        </ul>
                    @endif
                </div>
                <div class="mt-6">
                    <a href="{{ route('consultant.missions.index') }}" wire:navigate
                        class="flex w-full items-center justify-center rounded-md bg-white px-3 py-2 text-sm font-semibold text-gray-900 shadow-sm ring-1 ring-inset ring-gray-300 hover:bg-gray-50 dark:bg-gray-700 dark:text-white dark:ring-gray-600 dark:hover:bg-gray-600">
                        {{ __('Voir toutes les missions') }}
                    </a>
                </div>
            </div>
        </div>
    </div>
</div>