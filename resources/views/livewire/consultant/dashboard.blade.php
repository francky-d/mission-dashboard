<div class="space-y-8">
    {{-- Welcome Banner --}}
    <div class="card-themed p-6 sm:p-8"
        style="background: linear-gradient(135deg, var(--theme-primary) 0%, var(--theme-secondary) 100%);">
        <div class="flex flex-col sm:flex-row sm:items-center sm:justify-between gap-4">
            <div class="text-white">
                <h1 class="text-2xl font-bold">Bonjour, {{ auth()->user()->name }} 👋</h1>
                <p class="mt-1 text-white/80">Découvrez les nouvelles opportunités de mission</p>
            </div>
            <a href="{{ route('consultant.missions.index') }}" wire:navigate
                class="btn-secondary bg-white border-white text-white hover:bg-white/10">
                <x-heroicon-o-magnifying-glass class="w-4 h-4 mr-2" />
                Explorer les missions
            </a>
        </div>
    </div>

    {{-- Stats Cards --}}
    <div class="grid grid-cols-2 lg:grid-cols-4 gap-4 sm:gap-6">
        <div class="stat-card">
            <div class="stat-icon">
                <x-heroicon-o-briefcase class="w-6 h-6" />
            </div>
            <div class="stat-value">{{ $availableMissions }}</div>
            <div class="stat-label">Missions disponibles</div>
        </div>

        <div class="stat-card">
            <div class="stat-icon">
                <x-heroicon-o-document-text class="w-6 h-6" />
            </div>
            <div class="stat-value">{{ $myApplications }}</div>
            <div class="stat-label">Mes candidatures</div>
        </div>

        <div class="stat-card">
            <div class="stat-icon">
                <x-heroicon-o-clock class="w-6 h-6" />
            </div>
            <div class="stat-value">{{ $pendingApplications }}</div>
            <div class="stat-label">En attente</div>
        </div>

        <div class="stat-card">
            <div class="stat-icon">
                <x-heroicon-o-check-circle class="w-6 h-6" />
            </div>
            <div class="stat-value">{{ $acceptedApplications }}</div>
            <div class="stat-label">Acceptées</div>
        </div>
    </div>

    <div class="grid grid-cols-1 lg:grid-cols-2 gap-6">
        {{-- Recent Applications --}}
        <div class="card-themed">
            <div class="p-6">
                <div class="section-header">
                    <h3 class="section-title">Mes dernières candidatures</h3>
                    <a href="{{ route('consultant.applications.index') }}" wire:navigate class="link-themed text-sm">
                        Voir tout →
                    </a>
                </div>
                @if($recentApplications->isEmpty())
                    <div class="empty-state py-8">
                        <div class="empty-state-icon">
                            <x-heroicon-o-document-text class="w-8 h-8" />
                        </div>
                        <p class="text-gray-500">Aucune candidature pour le moment</p>
                        <a href="{{ route('consultant.missions.index') }}" wire:navigate class="btn-primary mt-4">
                            Découvrir les missions
                        </a>
                    </div>
                @else
                    <div class="space-y-1">
                        @foreach($recentApplications as $application)
                            <a href="{{ route('consultant.missions.show', $application->mission) }}" wire:navigate
                                class="list-item hover:bg-gray-50 -mx-6 px-6 transition-colors duration-200">
                                <div class="min-w-0 flex-1">
                                    <p class="font-medium text-gray-900 truncate">{{ $application->mission->title }}</p>
                                    <p class="text-sm text-gray-500 truncate">
                                        {{ $application->mission->commercial->name ?? '-' }}</p>
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

        {{-- Recommended Missions --}}
        <div class="card-themed">
            <div class="p-6">
                <div class="section-header">
                    <h3 class="section-title">Missions recommandées</h3>
                    <a href="{{ route('consultant.missions.index') }}" wire:navigate class="link-themed text-sm">
                        Voir tout →
                    </a>
                </div>
                @if($recommendedMissions->isEmpty())
                    <div class="empty-state py-8">
                        <div class="empty-state-icon">
                            <x-heroicon-o-briefcase class="w-8 h-8" />
                        </div>
                        <p class="text-gray-500">Aucune mission disponible</p>
                    </div>
                @else
                    <div class="space-y-4">
                        @foreach($recommendedMissions as $mission)
                            <a href="{{ route('consultant.missions.show', $mission) }}" wire:navigate
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
                                        <div class="flex flex-wrap gap-1.5 mt-3">
                                            @foreach($mission->tags->take(3) as $tag)
                                                <span class="tag-pill">{{ $tag->name }}</span>
                                            @endforeach
                                        </div>
                                    </div>
                                </a>
                        @endforeach
                    </div>
                    <div class="mt-6">
                        <a href="{{ route('consultant.missions.index') }}" wire:navigate
                            class="btn-secondary w-full justify-center">
                            Voir toutes les missions
                        </a>
                    </div>
                @endif
            </div>
        </div>
    </div>
</div>