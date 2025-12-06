<div class="space-y-8">
    {{-- Welcome Banner --}}
    <div class="card-themed p-6 sm:p-8" style="background-color: var(--theme-primary);">
        <div class="flex flex-col sm:flex-row sm:items-center sm:justify-between gap-4">
            <div class="text-white">
                <h1 class="text-2xl font-bold">Bonjour, {{ auth()->user()->name }} 👋</h1>
                <p class="mt-1 text-white/80">Gérez vos missions et suivez vos candidatures</p>
            </div>
            <a href="{{ route('commercial.missions.create') }}" wire:navigate
                class="inline-flex items-center px-5 py-2.5 bg-white text-[var(--theme-primary)] font-semibold rounded-lg shadow-sm hover:bg-white/90 transition-colors">
                <x-heroicon-o-plus class="w-4 h-4 mr-2" />
                Créer une mission
            </a>
        </div>
    </div>

    {{-- Stats Cards --}}
    <div class="grid grid-cols-2 lg:grid-cols-4 gap-4 sm:gap-6">
        <a href="{{ route('commercial.missions.index') }}" wire:navigate
            class="stat-card hover:shadow-lg transition-shadow cursor-pointer">
            <div class="stat-icon">
                <x-heroicon-o-briefcase class="w-6 h-6" />
            </div>
            <div class="stat-value">{{ $totalMissions }}</div>
            <div class="stat-label">Total missions</div>
        </a>

        <a href="{{ route('commercial.missions.index', ['status' => 'active']) }}" wire:navigate
            class="stat-card hover:shadow-lg transition-shadow cursor-pointer">
            <div class="stat-icon">
                <x-heroicon-o-check-badge class="w-6 h-6" />
            </div>
            <div class="stat-value">{{ $activeMissions }}</div>
            <div class="stat-label">Missions actives</div>
        </a>

        <a href="{{ route('commercial.missions.index') }}" wire:navigate
            class="stat-card hover:shadow-lg transition-shadow cursor-pointer">
            <div class="stat-icon">
                <x-heroicon-o-clock class="w-6 h-6" />
            </div>
            <div class="stat-value">{{ $pendingApplications }}</div>
            <div class="stat-label">Candidatures en attente</div>
        </a>

        <a href="{{ route('commercial.missions.index') }}" wire:navigate
            class="stat-card hover:shadow-lg transition-shadow cursor-pointer">
            <div class="stat-icon">
                <x-heroicon-o-users class="w-6 h-6" />
            </div>
            <div class="stat-value">{{ $totalApplications }}</div>
            <div class="stat-label">Total candidatures</div>
        </a>
    </div>

    <div class="grid grid-cols-1 lg:grid-cols-2 gap-6">
        {{-- Recent Applications --}}
        <div class="card-themed">
            <div class="p-6">
                <div class="section-header">
                    <h3 class="section-title">Dernières candidatures</h3>
                </div>
                @if($recentApplications->isEmpty())
                    <div class="empty-state py-8">
                        <div class="empty-state-icon">
                            <x-heroicon-o-users class="w-8 h-8" />
                        </div>
                        <p class="text-gray-500">Aucune candidature récente</p>
                    </div>
                @else
                    <div class="space-y-4">
                        @foreach($recentApplications as $application)
                            <a href="{{ route('commercial.missions.show', $application->mission) }}" wire:navigate
                                class="flex items-center gap-4 p-4 rounded-lg bg-gray-50 hover:bg-gray-100 transition-colors duration-200">
                                <div class="avatar">
                                    {{ strtoupper(substr($application->consultant->name, 0, 1)) }}
                                </div>
                                <div class="min-w-0 flex-1">
                                    <p class="font-medium text-gray-900 truncate">{{ $application->consultant->name }}</p>
                                    <p class="text-sm text-gray-500 truncate">{{ $application->mission->title }}</p>
                                </div>
                                <span @class([
                                    'inline-flex items-center px-2.5 py-1 rounded-full text-xs font-medium',
                                    'bg-yellow-100 text-yellow-800' => $application->status === \App\Enums\ApplicationStatus::Pending,
                                    'bg-blue-100 text-blue-800' => $application->status === \App\Enums\ApplicationStatus::Viewed,
                                    'bg-green-100 text-green-800' => $application->status === \App\Enums\ApplicationStatus::Accepted,
                                    'bg-red-100 text-red-800' => $application->status === \App\Enums\ApplicationStatus::Rejected,
                                ])>
                                    {{ $application->status->label() }}
                                </span>
                            </a>
                        @endforeach
                    </div>
                @endif
            </div>
        </div>

        {{-- Recent Missions --}}
        <div class="card-themed">
            <div class="p-6">
                <div class="section-header">
                    <h3 class="section-title">Mes missions récentes</h3>
                    <a href="{{ route('commercial.missions.create') }}" wire:navigate class="link-themed text-sm">
                        + Créer
                    </a>
                </div>
                @if($recentMissions->isEmpty())
                    <div class="empty-state py-8">
                        <div class="empty-state-icon">
                            <x-heroicon-o-briefcase class="w-8 h-8" />
                        </div>
                        <p class="text-gray-500">Aucune mission créée</p>
                        <a href="{{ route('commercial.missions.create') }}" wire:navigate class="btn-primary mt-4">
                            Créer ma première mission
                        </a>
                    </div>
                @else
                    <div class="space-y-4">
                        @foreach($recentMissions as $mission)
                            <a href="{{ route('commercial.missions.show', $mission) }}" wire:navigate
                                class="block p-4 rounded-lg border border-gray-100 hover:border-gray-200 hover:shadow-sm transition-all duration-200">
                                <div class="flex items-start justify-between gap-4">
                                    <div class="min-w-0 flex-1">
                                        <h4 class="font-medium text-gray-900 truncate">{{ $mission->title }}</h4>
                                        <div class="flex items-center gap-3 mt-1 text-sm text-gray-500">
                                            <span class="flex items-center gap-1">
                                                <x-heroicon-o-map-pin class="w-4 h-4" />
                                                {{ $mission->location }}
                                            </span>
                                        </div>
                                    </div>
                                    <div class="flex flex-col items-end gap-2">
                                        <span class="badge-themed">
                                            {{ $mission->applications_count }} candidat(s)
                                        </span>
                                        <span @class([
                                            'inline-flex items-center px-2.5 py-0.5 rounded-full text-xs font-medium',
                                            'bg-green-100 text-green-800' => $mission->status === \App\Enums\MissionStatus::Active,
                                            'bg-gray-100 text-gray-800' => $mission->status === \App\Enums\MissionStatus::Archived,
                                        ])>
                                            {{ $mission->status->label() }}
                                        </span>
                                    </div>
                                </div>
                            </a>
                        @endforeach
                    </div>
                    <div class="mt-6">
                        <a href="{{ route('commercial.missions.index') }}" wire:navigate
                            class="btn-secondary w-full justify-center">
                            Voir toutes les missions
                        </a>
                    </div>
                @endif
            </div>
        </div>
    </div>
</div>