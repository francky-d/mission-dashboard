<div class="space-y-6">
    {{-- Retour à la liste --}}
    <div>
        <a href="{{ route('commercial.missions.index') }}" wire:navigate
            class="inline-flex items-center text-sm font-medium text-gray-500 hover:text-gray-700 dark:text-gray-400 dark:hover:text-gray-200">
            <x-heroicon-m-arrow-left class="w-4 h-4 mr-2" />
            {{ __('Retour aux missions') }}
        </a>
    </div>

    {{-- En-tête de la mission --}}
    <div class="bg-white dark:bg-gray-800 rounded-lg shadow-sm border border-gray-200 dark:border-gray-700 overflow-hidden">
        <div class="p-6">
            <div class="flex items-start justify-between">
                <div class="flex-1">
                    <div class="flex items-center gap-3">
                        <h1 class="text-2xl font-bold text-gray-900 dark:text-white">
                            {{ $mission->title }}
                        </h1>
                        <span @class([
                            'inline-flex items-center rounded-full px-2.5 py-0.5 text-xs font-medium',
                            'bg-green-100 text-green-800 dark:bg-green-900/30 dark:text-green-400' => $mission->status === \App\Enums\MissionStatus::Active,
                            'bg-gray-100 text-gray-800 dark:bg-gray-700 dark:text-gray-400' => $mission->status === \App\Enums\MissionStatus::Archived,
                        ])>
                            {{ $mission->status->label() }}
                        </span>
                    </div>
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
                    <a href="{{ route('commercial.missions.edit', $mission) }}" wire:navigate
                        class="inline-flex items-center rounded-md bg-gray-100 px-3 py-2 text-sm font-medium text-gray-700 hover:bg-gray-200 dark:bg-gray-700 dark:text-gray-300 dark:hover:bg-gray-600">
                        <x-heroicon-m-pencil class="w-4 h-4 mr-2" />
                        {{ __('Modifier') }}
                    </a>
                </div>
            </div>

            @if($mission->tags->isNotEmpty())
                <div class="mt-4 flex flex-wrap gap-2">
                    @foreach($mission->tags as $tag)
                        <span class="inline-flex items-center rounded-full bg-blue-100 px-2.5 py-0.5 text-xs font-medium text-blue-800 dark:bg-blue-900/30 dark:text-blue-400">
                            {{ $tag->name }}
                        </span>
                    @endforeach
                </div>
            @endif
        </div>
    </div>

    {{-- Candidatures Section --}}
    <div class="bg-white dark:bg-gray-800 rounded-lg shadow-sm border border-gray-200 dark:border-gray-700 overflow-hidden">
        <div class="p-6">
            <h2 class="text-lg font-semibold text-gray-900 dark:text-white mb-4">
                {{ __('Candidatures') }}
            </h2>

            {{-- Status Filter Tabs --}}
            <div class="border-b border-gray-200 dark:border-gray-700 mb-6">
                <nav class="-mb-px flex space-x-8" aria-label="Tabs">
                    <button type="button" wire:click="$set('applicationStatus', '')" @class([
                        'whitespace-nowrap border-b-2 py-4 px-1 text-sm font-medium',
                        'border-indigo-500 text-indigo-600 dark:text-indigo-400' => $applicationStatus === '',
                        'border-transparent text-gray-500 hover:border-gray-300 hover:text-gray-700 dark:text-gray-400 dark:hover:text-gray-300' => $applicationStatus !== '',
                    ])>
                        {{ __('Toutes') }}
                        <span class="ml-2 rounded-full bg-gray-100 px-2.5 py-0.5 text-xs font-medium text-gray-600 dark:bg-gray-700 dark:text-gray-300">
                            {{ array_sum($statusCounts) }}
                        </span>
                    </button>

                    @foreach($statuses as $statusOption)
                        <button type="button" wire:click="$set('applicationStatus', '{{ $statusOption->value }}')" @class([
                            'whitespace-nowrap border-b-2 py-4 px-1 text-sm font-medium',
                            'border-indigo-500 text-indigo-600 dark:text-indigo-400' => $applicationStatus === $statusOption->value,
                            'border-transparent text-gray-500 hover:border-gray-300 hover:text-gray-700 dark:text-gray-400 dark:hover:text-gray-300' => $applicationStatus !== $statusOption->value,
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
                    <x-heroicon-o-users class="mx-auto h-12 w-12 text-gray-400" />
                    <h3 class="mt-4 text-lg font-medium text-gray-900 dark:text-gray-100">
                        {{ __('Aucune candidature') }}
                    </h3>
                    <p class="mt-2 text-gray-500 dark:text-gray-400">
                        @if($applicationStatus)
                            {{ __('Aucune candidature avec ce statut.') }}
                        @else
                            {{ __('Aucun consultant n\'a encore postulé à cette mission.') }}
                        @endif
                    </p>
                </div>
            @else
                <div class="space-y-4">
                    @foreach($applications as $application)
                        <div class="rounded-lg border border-gray-200 dark:border-gray-700 p-4 hover:bg-gray-50 dark:hover:bg-gray-700/50 transition">
                            <div class="flex items-start justify-between">
                                <div class="flex items-start gap-4">
                                    {{-- Avatar --}}
                                    <div class="h-12 w-12 rounded-full bg-indigo-100 dark:bg-indigo-900/30 flex items-center justify-center flex-shrink-0">
                                        <span class="text-lg font-semibold text-indigo-600 dark:text-indigo-400">
                                            {{ strtoupper(substr($application->consultant->name, 0, 1)) }}
                                        </span>
                                    </div>

                                    <div>
                                        <a href="{{ route('commercial.consultants.show', $application->consultant) }}" wire:navigate
                                            class="text-base font-semibold text-gray-900 hover:text-indigo-600 dark:text-white dark:hover:text-indigo-400">
                                            {{ $application->consultant->name }}
                                        </a>
                                        <p class="text-sm text-gray-500 dark:text-gray-400">
                                            {{ $application->consultant->email }}
                                        </p>

                                        @if($application->consultant->consultantProfile)
                                            <div class="mt-2 flex flex-wrap items-center gap-2 text-sm text-gray-500 dark:text-gray-400">
                                                @if($application->consultant->consultantProfile->experience_years)
                                                    <span class="flex items-center">
                                                        <x-heroicon-m-briefcase class="w-4 h-4 mr-1" />
                                                        {{ $application->consultant->consultantProfile->experience_years }} {{ __('ans d\'exp.') }}
                                                    </span>
                                                @endif
                                                @if($application->consultant->consultantProfile->cv_url)
                                                    <a href="{{ Storage::url($application->consultant->consultantProfile->cv_url) }}" target="_blank"
                                                        class="flex items-center text-indigo-600 hover:text-indigo-500 dark:text-indigo-400">
                                                        <x-heroicon-m-document class="w-4 h-4 mr-1" />
                                                        {{ __('Voir le CV') }}
                                                    </a>
                                                @endif
                                            </div>

                                            @if($application->consultant->consultantProfile->tags->isNotEmpty())
                                                <div class="mt-2 flex flex-wrap gap-1">
                                                    @foreach($application->consultant->consultantProfile->tags as $tag)
                                                        <span @class([
                                                            'inline-flex items-center rounded-full px-2 py-0.5 text-xs font-medium',
                                                            'bg-green-100 text-green-800 dark:bg-green-900/30 dark:text-green-400' => $mission->tags->contains($tag->id),
                                                            'bg-gray-100 text-gray-700 dark:bg-gray-700 dark:text-gray-300' => !$mission->tags->contains($tag->id),
                                                        ])>
                                                            @if($mission->tags->contains($tag->id))
                                                                <x-heroicon-m-check class="w-3 h-3 mr-0.5" />
                                                            @endif
                                                            {{ $tag->name }}
                                                        </span>
                                                    @endforeach
                                                </div>
                                            @endif
                                        @endif

                                        <p class="mt-2 text-xs text-gray-400 dark:text-gray-500">
                                            {{ __('Candidature reçue le') }} {{ $application->created_at->format('d/m/Y à H:i') }}
                                        </p>
                                    </div>
                                </div>

                                <div class="flex flex-col items-end gap-2">
                                    {{-- Current Status Badge --}}
                                    <span @class([
                                        'inline-flex items-center rounded-full px-3 py-1 text-sm font-medium',
                                        'bg-yellow-100 text-yellow-800 dark:bg-yellow-900/30 dark:text-yellow-400' => $application->status === \App\Enums\ApplicationStatus::Pending,
                                        'bg-blue-100 text-blue-800 dark:bg-blue-900/30 dark:text-blue-400' => $application->status === \App\Enums\ApplicationStatus::Viewed,
                                        'bg-green-100 text-green-800 dark:bg-green-900/30 dark:text-green-400' => $application->status === \App\Enums\ApplicationStatus::Accepted,
                                        'bg-red-100 text-red-800 dark:bg-red-900/30 dark:text-red-400' => $application->status === \App\Enums\ApplicationStatus::Rejected,
                                    ])>
                                        {{ $application->status->label() }}
                                    </span>

                                    {{-- Status Actions --}}
                                    <div class="flex items-center gap-1">
                                        @if($application->status !== \App\Enums\ApplicationStatus::Viewed)
                                            <button
                                                type="button"
                                                wire:click="updateApplicationStatus({{ $application->id }}, 'viewed')"
                                                class="inline-flex items-center rounded px-2 py-1 text-xs font-medium text-blue-600 hover:bg-blue-50 dark:text-blue-400 dark:hover:bg-blue-900/30"
                                                title="{{ __('Marquer comme consulté') }}"
                                            >
                                                <x-heroicon-m-eye class="w-4 h-4" />
                                            </button>
                                        @endif

                                        @if($application->status !== \App\Enums\ApplicationStatus::Accepted)
                                            <button
                                                type="button"
                                                wire:click="updateApplicationStatus({{ $application->id }}, 'accepted')"
                                                class="inline-flex items-center rounded px-2 py-1 text-xs font-medium text-green-600 hover:bg-green-50 dark:text-green-400 dark:hover:bg-green-900/30"
                                                title="{{ __('Accepter') }}"
                                            >
                                                <x-heroicon-m-check class="w-4 h-4" />
                                            </button>
                                        @endif

                                        @if($application->status !== \App\Enums\ApplicationStatus::Rejected)
                                            <button
                                                type="button"
                                                wire:click="updateApplicationStatus({{ $application->id }}, 'rejected')"
                                                class="inline-flex items-center rounded px-2 py-1 text-xs font-medium text-red-600 hover:bg-red-50 dark:text-red-400 dark:hover:bg-red-900/30"
                                                title="{{ __('Refuser') }}"
                                            >
                                                <x-heroicon-m-x-mark class="w-4 h-4" />
                                            </button>
                                        @endif
                                    </div>

                                    {{-- Actions --}}
                                    <div class="flex items-center gap-2 mt-2">
                                        <a href="{{ route('commercial.consultants.show', $application->consultant) }}" wire:navigate
                                            class="inline-flex items-center rounded-md bg-gray-100 px-2.5 py-1.5 text-xs font-medium text-gray-700 hover:bg-gray-200 dark:bg-gray-700 dark:text-gray-300 dark:hover:bg-gray-600">
                                            <x-heroicon-m-user class="w-3.5 h-3.5 mr-1" />
                                            {{ __('Profil') }}
                                        </a>
                                        <a href="{{ route('commercial.messages.index') }}?user={{ $application->consultant->id }}" wire:navigate
                                            class="inline-flex items-center rounded-md bg-indigo-100 px-2.5 py-1.5 text-xs font-medium text-indigo-700 hover:bg-indigo-200 dark:bg-indigo-900/30 dark:text-indigo-400 dark:hover:bg-indigo-900/50">
                                            <x-heroicon-m-chat-bubble-left class="w-3.5 h-3.5 mr-1" />
                                            {{ __('Contacter') }}
                                        </a>
                                    </div>
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
    </div>
</div>
