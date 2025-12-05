<div class="space-y-6">
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
                        'bg-amber-100 text-amber-800' => $statusOption === \App\Enums\ApplicationStatus::Pending,
                        'bg-sky-100 text-sky-800' => $statusOption === \App\Enums\ApplicationStatus::Viewed,
                        'bg-emerald-100 text-emerald-800' => $statusOption === \App\Enums\ApplicationStatus::Accepted,
                        'bg-rose-100 text-rose-800' => $statusOption === \App\Enums\ApplicationStatus::Rejected,
                    ])>
                        {{ $statusCounts[$statusOption->value] ?? 0 }}
                    </span>
                </button>
            @endforeach
        </nav>
    </div>

    {{-- Applications List --}}
    @if($applications->isEmpty())
        <div class="rounded-xl border-2 border-dashed border-slate-300 p-12 text-center bg-white">
            <x-heroicon-o-document-text class="mx-auto h-12 w-12 text-slate-400" />
            <h3 class="mt-4 text-lg font-semibold text-slate-900">
                {{ __('Aucune candidature') }}
            </h3>
            <p class="mt-2 text-slate-600">
                @if($status)
                    {{ __('Vous n\'avez pas de candidature avec ce statut.') }}
                @else
                    {{ __('Vous n\'avez pas encore postulé à une mission.') }}
                @endif
            </p>
            <a href="{{ route('consultant.missions.index') }}" wire:navigate
                class="mt-4 inline-flex items-center rounded-lg bg-indigo-600 px-4 py-2 text-sm font-semibold text-white hover:bg-indigo-700 transition-colors">
                {{ __('Voir les missions disponibles') }}
            </a>
        </div>
    @else
        <div class="space-y-4">
            @foreach($applications as $application)
                <div
                    class="overflow-hidden rounded-xl bg-white shadow-sm border border-slate-200 transition-all hover:shadow-md hover:border-slate-300">
                    <div class="p-6">
                        <div class="flex items-start justify-between">
                            <div class="flex-1">
                                <a href="{{ route('consultant.missions.show', $application->mission) }}" wire:navigate
                                    class="block">
                                    <h3 class="text-lg font-semibold text-slate-900 hover:text-indigo-600 transition-colors">
                                        {{ $application->mission->title }}
                                    </h3>
                                </a>
                                <p class="mt-1 text-sm text-slate-600">
                                    {{ __('Candidature envoyée le') }} {{ $application->created_at->format('d/m/Y à H:i') }}
                                </p>
                            </div>
                            <div class="ml-4 flex-shrink-0">
                                <span @class([
                                    'inline-flex items-center rounded-full px-3 py-1 text-sm font-medium',
                                    'bg-amber-100 text-amber-800' => $application->status === \App\Enums\ApplicationStatus::Pending,
                                    'bg-sky-100 text-sky-800' => $application->status === \App\Enums\ApplicationStatus::Viewed,
                                    'bg-emerald-100 text-emerald-800' => $application->status === \App\Enums\ApplicationStatus::Accepted,
                                    'bg-rose-100 text-rose-800' => $application->status === \App\Enums\ApplicationStatus::Rejected,
                                ])>
                                    {{ $application->status->label() }}
                                </span>
                            </div>
                        </div>

                        <div class="mt-4 flex flex-wrap items-center gap-4 text-sm text-slate-600">
                            @if($application->mission->location)
                                <div class="flex items-center">
                                    <x-heroicon-m-map-pin class="w-4 h-4 mr-1" />
                                    {{ $application->mission->location }}
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
                                        class="inline-flex items-center rounded-full bg-indigo-50 px-2.5 py-0.5 text-xs font-medium text-indigo-700 border border-indigo-200">
                                        {{ $tag->name }}
                                    </span>
                                @endforeach
                            </div>
                        @endif

                        <div class="mt-4 flex items-center justify-between">
                            <a href="{{ route('consultant.missions.show', $application->mission) }}" wire:navigate
                                class="inline-flex items-center text-sm font-medium text-indigo-600 hover:text-indigo-800 transition-colors">
                                {{ __('Voir la mission') }}
                                <x-heroicon-m-arrow-right class="ml-1 h-4 w-4" />
                            </a>

                            {{-- Withdraw button only for pending applications --}}
                            @if($application->status === \App\Enums\ApplicationStatus::Pending)
                                <button type="button" wire:click="withdraw({{ $application->id }})" wire:loading.attr="disabled"
                                    wire:confirm="{{ __('Êtes-vous sûr de vouloir retirer votre candidature ?') }}"
                                    class="inline-flex items-center rounded-lg bg-rose-50 px-3 py-1.5 text-sm font-medium text-rose-700 hover:bg-rose-100 border border-rose-200 transition-colors">
                                    <x-heroicon-m-x-mark class="w-4 h-4 mr-1" />
                                    {{ __('Retirer ma candidature') }}
                                </button>
                            @endif
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