<div class="min-h-screen bg-gray-50 py-8">
    <div class="max-w-5xl mx-auto px-4 sm:px-6 lg:px-8">

        {{-- Bouton retour --}}
        <div class="mb-6">
            <a href="{{ route('commercial.dashboard') }}" class="link-themed inline-flex items-center text-sm font-medium hover:underline">
                <x-heroicon-o-arrow-left class="w-4 h-4 mr-2" />
                {{ __('Retour au tableau de bord') }}
            </a>
        </div>

        {{-- Carte principale de la mission --}}
        <div class="card-themed mb-8">
            <div class="p-6 sm:p-8">
                {{-- En-tête avec titre et statut --}}
                <div class="flex flex-col sm:flex-row sm:items-start sm:justify-between gap-4 mb-6">
                    <div class="flex-1">
                        <h1 class="text-2xl sm:text-3xl font-bold text-slate-900 mb-2">
                            {{ $mission->title }}
                        </h1>
                        <div class="flex flex-wrap items-center gap-4 text-sm text-slate-600">
                            @if($mission->location)
                                <p class="flex items-center">
                                    <x-heroicon-o-map-pin class="w-4 h-4 mr-1.5 text-slate-400" />
                                    {{ $mission->location }}
                                </p>
                            @endif
                            <p class="flex items-center">
                                <x-heroicon-o-calendar class="w-4 h-4 mr-1.5 text-slate-400" />
                                {{ __('Créée le') }} {{ $mission->created_at->translatedFormat('d F Y') }}
                            </p>
                        </div>
                    </div>
                    <div class="shrink-0">
                        <span class="badge-themed inline-flex items-center px-4 py-2 rounded-full text-sm font-semibold">
                            {{ $mission->status->label() }}
                        </span>
                    </div>
                </div>

                {{-- Description --}}
                <div class="mb-6">
                    <h2 class="text-lg font-semibold text-slate-900 mb-3">{{ __('Description') }}</h2>
                    <div class="prose prose-slate max-w-none text-slate-700 leading-relaxed">
                        {!! nl2br(e($mission->description)) !!}
                    </div>
                </div>

                {{-- Tags --}}
                @if($mission->tags->isNotEmpty())
                    <div class="pt-6 border-t border-slate-200">
                        <h2 class="text-lg font-semibold text-slate-900 mb-3">{{ __('Compétences requises') }}</h2>
                        <div class="flex flex-wrap gap-2">
                            @foreach($mission->tags as $tag)
                                <span class="tag-pill">
                                    {{ $tag->name }}
                                </span>
                            @endforeach
                        </div>
                    </div>
                @endif
            </div>
        </div>

        {{-- Section des candidatures --}}
        <div class="card-themed">
            <div class="p-6 sm:p-8">
                <div class="flex items-center justify-between mb-6">
                    <h2 class="text-xl font-bold text-slate-900">
                        {{ __('Candidatures') }}
                    </h2>
                    <span class="text-sm text-slate-500 font-medium">
                        {{ $applications->total() }} {{ __('candidat(s)') }}
                    </span>
                </div>

                {{-- Onglets de filtre par statut --}}
                <div class="mb-6">
                    <nav class="flex flex-wrap gap-2" role="tablist">
                        <button
                            wire:click="$set('applicationStatus', '')"
                            type="button"
                            role="tab"
                            aria-selected="{{ $applicationStatus === '' ? 'true' : 'false' }}"
                            class="px-4 py-2 rounded-lg text-sm font-medium transition-all duration-200
                                {{ $applicationStatus === ''
                                    ? 'bg-[var(--theme-primary)] text-white shadow-md'
                                    : 'bg-white text-slate-600 hover:bg-slate-100 border border-slate-200' }}"
                        >
                            {{ __('Tous') }}
                        </button>

                        @foreach(\App\Enums\ApplicationStatus::cases() as $status)
                            <button
                                wire:click="$set('applicationStatus', '{{ $status->value }}')"
                                type="button"
                                role="tab"
                                aria-selected="{{ $applicationStatus === $status->value ? 'true' : 'false' }}"
                                class="px-4 py-2 rounded-lg text-sm font-medium transition-all duration-200
                                    {{ $applicationStatus === $status->value
                                        ? 'bg-[var(--theme-primary)] text-white shadow-md'
                                        : 'bg-white text-slate-600 hover:bg-slate-100 border border-slate-200' }}"
                            >
                                {{ $status->label() }}
                            </button>
                        @endforeach
                    </nav>
                </div>

                {{-- Liste des candidatures --}}
                @if($applications->isEmpty())
                    <div class="text-center py-12 bg-slate-50 rounded-xl border-2 border-dashed border-slate-200">
                        <x-heroicon-o-user-group class="mx-auto h-12 w-12 text-slate-400" />
                        <h3 class="mt-3 text-lg font-semibold text-slate-700">{{ __('Aucune candidature') }}</h3>
                        <p class="mt-1 text-sm text-slate-500">
                            {{ $applicationStatus
                                ? __('Aucune candidature avec ce statut.')
                                : __('Aucun consultant n\'a encore postulé à cette mission.') }}
                        </p>
                    </div>
                @else
                    <div class="space-y-4">
                        @foreach($applications as $application)
                            <div class="bg-white rounded-xl border border-slate-200 shadow-sm hover:shadow-md transition-shadow duration-200 overflow-hidden">
                                <div class="p-5">
                                    <div class="flex flex-col sm:flex-row sm:items-center gap-4">
                                        {{-- Avatar et infos consultant --}}
                                        <div class="flex items-center gap-4 flex-1 min-w-0">
                                            <div class="shrink-0">
                                                @if($application->consultant->profile_photo_path)
                                                    <img
                                                        src="{{ Storage::url($application->consultant->profile_photo_path) }}"
                                                        alt="{{ $application->consultant->name }}"
                                                        class="w-14 h-14 rounded-full object-cover ring-2 ring-slate-100"
                                                    />
                                                @else
                                                    <div class="w-14 h-14 rounded-full bg-gradient-to-br from-[var(--theme-primary)] to-[var(--theme-secondary)] flex items-center justify-center ring-2 ring-slate-100">
                                                        <span class="text-lg font-bold text-white">
                                                            {{ strtoupper(substr($application->consultant->name, 0, 2)) }}
                                                        </span>
                                                    </div>
                                                @endif
                                            </div>

                                            <div class="flex-1 min-w-0">
                                                <h3 class="text-lg font-semibold text-slate-900 truncate">
                                                    {{ $application->consultant->name }}
                                                </h3>
                                                <p class="text-sm text-slate-500 truncate">
                                                    {{ $application->consultant->email }}
                                                </p>
                                                <p class="text-xs text-slate-400 mt-1">
                                                    {{ __('Postulé le') }} {{ $application->created_at->translatedFormat('d M Y à H:i') }}
                                                </p>
                                            </div>
                                        </div>

                                        {{-- Statut et actions --}}
                                        <div class="flex flex-col sm:flex-row items-start sm:items-center gap-3">
                                            {{-- Badge statut --}}
                                            <span class="inline-flex items-center px-3 py-1.5 rounded-full text-xs font-semibold
                                                @switch($application->status->value)
                                                    @case('pending')
                                                        bg-amber-100 text-amber-800
                                                        @break
                                                    @case('accepted')
                                                        bg-emerald-100 text-emerald-800
                                                        @break
                                                    @case('rejected')
                                                        bg-red-100 text-red-800
                                                        @break
                                                    @default
                                                        bg-slate-100 text-slate-800
                                                @endswitch
                                            ">
                                                {{ $application->status->label() }}
                                            </span>

                                            {{-- Boutons d'action --}}
                                            <div class="flex items-center gap-2">
                                                {{-- Voir profil --}}
                                                <button
                                                    wire:click="showConsultantProfile({{ $application->consultant->id }})"
                                                    type="button"
                                                    class="inline-flex items-center px-3 py-2 text-sm font-medium text-slate-700 bg-white border border-slate-300 rounded-lg hover:bg-slate-50 focus:outline-none focus:ring-2 focus:ring-offset-2 focus:ring-[var(--theme-primary)] transition-colors"
                                                    title="{{ __('Voir le profil') }}"
                                                >
                                                    <x-heroicon-o-eye class="w-4 h-4 sm:mr-1.5" />
                                                    <span class="hidden sm:inline">{{ __('Profil') }}</span>
                                                </button>

                                                {{-- Actions selon statut --}}
                                                @if($application->status->value === 'pending')
                                                    <button
                                                        wire:click="acceptApplication({{ $application->id }})"
                                                        wire:confirm="{{ __('Êtes-vous sûr de vouloir accepter cette candidature ?') }}"
                                                        type="button"
                                                        class="inline-flex items-center px-3 py-2 text-sm font-medium text-white bg-emerald-600 rounded-lg hover:bg-emerald-700 focus:outline-none focus:ring-2 focus:ring-offset-2 focus:ring-emerald-500 transition-colors"
                                                        title="{{ __('Accepter') }}"
                                                    >
                                                        <x-heroicon-o-check class="w-4 h-4 sm:mr-1.5" />
                                                        <span class="hidden sm:inline">{{ __('Accepter') }}</span>
                                                    </button>

                                                    <button
                                                        wire:click="rejectApplication({{ $application->id }})"
                                                        wire:confirm="{{ __('Êtes-vous sûr de vouloir refuser cette candidature ?') }}"
                                                        type="button"
                                                        class="inline-flex items-center px-3 py-2 text-sm font-medium text-white bg-red-600 rounded-lg hover:bg-red-700 focus:outline-none focus:ring-2 focus:ring-offset-2 focus:ring-red-500 transition-colors"
                                                        title="{{ __('Refuser') }}"
                                                    >
                                                        <x-heroicon-o-x-mark class="w-4 h-4 sm:mr-1.5" />
                                                        <span class="hidden sm:inline">{{ __('Refuser') }}</span>
                                                    </button>
                                                @endif
                                            </div>
                                        </div>
                                    </div>

                                    {{-- Message de motivation si présent --}}
                                    @if($application->motivation)
                                        <div class="mt-4 pt-4 border-t border-slate-100">
                                            <p class="text-sm font-medium text-slate-700 mb-2">{{ __('Message de motivation :') }}</p>
                                            <p class="text-sm text-slate-600 bg-slate-50 p-3 rounded-lg">
                                                {{ $application->motivation }}
                                            </p>
                                        </div>
                                    @endif
                                </div>
                            </div>
                        @endforeach
                    </div>
                @endif
            </div>
        </div>
    </div>

    {{-- Modal profil consultant --}}
    @if($showProfileModal && $selectedConsultant)
        <div
            class="fixed inset-0 z-50 overflow-y-auto"
            aria-labelledby="modal-title"
            role="dialog"
            aria-modal="true"
        >
            <div class="flex items-end justify-center min-h-screen pt-4 px-4 pb-20 text-center sm:block sm:p-0">
                {{-- Overlay --}}
                <div
                    wire:click="closeProfileModal"
                    class="fixed inset-0 bg-slate-900/60 backdrop-blur-sm transition-opacity"
                    aria-hidden="true"
                ></div>

                <span class="hidden sm:inline-block sm:align-middle sm:h-screen" aria-hidden="true">&#8203;</span>

                {{-- Contenu modal --}}
                <div class="relative inline-block align-bottom bg-white rounded-2xl text-left overflow-hidden shadow-2xl transform transition-all sm:my-8 sm:align-middle sm:max-w-2xl sm:w-full">
                    {{-- Header --}}
                    <div class="bg-gradient-to-r from-[var(--theme-primary)] to-[var(--theme-secondary)] px-6 py-5">
                        <div class="flex items-center justify-between">
                            <h3 class="text-xl font-bold text-white" id="modal-title">
                                {{ __('Profil du consultant') }}
                            </h3>
                            <button
                                wire:click="closeProfileModal"
                                type="button"
                                class="text-white/80 hover:text-white transition-colors"
                            >
                                <x-heroicon-o-x-mark class="w-6 h-6" />
                            </button>
                        </div>
                    </div>

                    {{-- Body --}}
                    <div class="px-6 py-6">
                        <div class="flex flex-col sm:flex-row gap-6">
                            {{-- Photo --}}
                            <div class="shrink-0 text-center sm:text-left">
                                @if($selectedConsultant->profile_photo_path)
                                    <img
                                        src="{{ Storage::url($selectedConsultant->profile_photo_path) }}"
                                        alt="{{ $selectedConsultant->name }}"
                                        class="w-28 h-28 rounded-2xl object-cover mx-auto sm:mx-0 shadow-lg ring-4 ring-slate-100"
                                    />
                                @else
                                    <div class="w-28 h-28 rounded-2xl bg-gradient-to-br from-[var(--theme-primary)] to-[var(--theme-secondary)] flex items-center justify-center mx-auto sm:mx-0 shadow-lg ring-4 ring-slate-100">
                                        <span class="text-3xl font-bold text-white">
                                            {{ strtoupper(substr($selectedConsultant->name, 0, 2)) }}
                                        </span>
                                    </div>
                                @endif
                            </div>

                            {{-- Infos --}}
                            <div class="flex-1 text-center sm:text-left">
                                <h4 class="text-2xl font-bold text-slate-900">
                                    {{ $selectedConsultant->name }}
                                </h4>
                                <p class="text-slate-600 mt-1">
                                    {{ $selectedConsultant->email }}
                                </p>

                                @if($selectedConsultant->consultantProfile)
                                    @if($selectedConsultant->consultantProfile->title)
                                        <p class="text-[var(--theme-primary)] font-semibold mt-3">
                                            {{ $selectedConsultant->consultantProfile->title }}
                                        </p>
                                    @endif

                                    @if($selectedConsultant->consultantProfile->bio)
                                        <div class="mt-4 p-4 bg-slate-50 rounded-xl">
                                            <p class="text-sm font-medium text-slate-700 mb-2">{{ __('Bio') }}</p>
                                            <p class="text-sm text-slate-600 leading-relaxed">
                                                {{ $selectedConsultant->consultantProfile->bio }}
                                            </p>
                                        </div>
                                    @endif
                                @endif
                            </div>
                        </div>

                        {{-- Compétences --}}
                        @if($selectedConsultant->tags && $selectedConsultant->tags->isNotEmpty())
                            <div class="mt-6 pt-6 border-t border-slate-200">
                                <p class="text-sm font-semibold text-slate-700 mb-3">{{ __('Compétences') }}</p>
                                <div class="flex flex-wrap gap-2">
                                    @foreach($selectedConsultant->tags as $tag)
                                        <span class="tag-pill">
                                            {{ $tag->name }}
                                        </span>
                                    @endforeach
                                </div>
                            </div>
                        @endif
                    </div>

                    {{-- Footer --}}
                    <div class="bg-slate-50 px-6 py-4 flex justify-end">
                        <button
                            wire:click="closeProfileModal"
                            type="button"
                            class="btn-primary"
                        >
                            {{ __('Fermer') }}
                        </button>
                    </div>
                </div>
            </div>
        </div>
    @endif
</div>
