@php use Illuminate\Support\Facades\Storage; @endphp
<div class="min-h-screen bg-gray-50 py-8">
    <div class="max-w-4xl mx-auto px-4 sm:px-6 lg:px-8 space-y-6">

        {{-- Lien retour --}}
        <div>
            <a href="javascript:history.back()"
                class="link-themed inline-flex items-center text-sm font-medium hover:underline">
                <x-heroicon-m-arrow-left class="w-4 h-4 mr-2" />
                {{ __('Retour') }}
            </a>
        </div>

        {{-- En-tête profil --}}
        <div class="card-themed">
            <div class="p-6 sm:p-8">
                <div class="flex items-start gap-6">
                    {{-- Avatar --}}
                    <div class="h-20 w-20 rounded-2xl flex items-center justify-center flex-shrink-0 shadow-lg ring-4 ring-slate-100"
                        style="background-color: var(--theme-primary);">
                        <span class="text-3xl font-bold text-white">
                            {{ strtoupper(substr($user->name, 0, 1)) }}
                        </span>
                    </div>

                    <div class="flex-1">
                        <h1 class="text-2xl font-bold text-slate-900">
                            {{ $user->name }}
                        </h1>
                        <p class="text-sm text-slate-500">
                            {{ $user->email }}
                        </p>

                        @if($user->consultantProfile)
                            <div class="mt-4 flex flex-wrap items-center gap-4 text-sm text-slate-600">
                                @if($user->consultantProfile->experience_years)
                                    <span class="flex items-center">
                                        <x-heroicon-m-briefcase class="w-4 h-4 mr-1.5 text-slate-400" />
                                        {{ $user->consultantProfile->experience_years }} {{ __('années d\'expérience') }}
                                    </span>
                                @endif
                                @if($user->consultantProfile->cv_url)
                                    <a href="{{ Storage::url($user->consultantProfile->cv_url) }}" target="_blank"
                                        class="link-themed inline-flex items-center hover:underline">
                                        <x-heroicon-m-document-arrow-down class="w-4 h-4 mr-1.5" />
                                        {{ __('Télécharger le CV') }}
                                    </a>
                                @endif
                            </div>
                        @endif

                        {{-- Bouton contact --}}
                        <div class="mt-6">
                            <a href="{{ route('commercial.messages.index') }}?consultant={{ $user->id }}" wire:navigate
                                class="btn-primary">
                                <x-heroicon-m-chat-bubble-left class="w-4 h-4 mr-2" />
                                {{ __('Contacter') }}
                            </a>
                        </div>
                    </div>
                </div>
            </div>
        </div>

        {{-- Compétences / Tags --}}
        @if($user->consultantProfile && $user->consultantProfile->tags->isNotEmpty())
            <div class="card-themed">
                <div class="p-6 sm:p-8">
                    <h2 class="text-lg font-bold text-slate-900 mb-4">
                        {{ __('Compétences') }}
                    </h2>
                    <div class="flex flex-wrap gap-2">
                        @foreach($user->consultantProfile->tags as $tag)
                            <span class="tag-pill">
                                {{ $tag->name }}
                            </span>
                        @endforeach
                    </div>
                </div>
            </div>
        @endif

        {{-- Bio --}}
        @if($user->consultantProfile && $user->consultantProfile->bio)
            <div class="card-themed">
                <div class="p-6 sm:p-8">
                    <h2 class="text-lg font-bold text-slate-900 mb-4">
                        {{ __('Biographie') }}
                    </h2>
                    <div class="prose prose-slate max-w-none text-slate-700 leading-relaxed bg-slate-50 p-4 rounded-xl">
                        {!! nl2br(e($user->consultantProfile->bio)) !!}
                    </div>
                </div>
            </div>
        @endif

        {{-- Candidatures sur mes missions --}}
        @if($applications->isNotEmpty())
            <div class="card-themed">
                <div class="p-6 sm:p-8">
                    <h2 class="text-lg font-bold text-slate-900 mb-4">
                        {{ __('Candidatures sur mes missions') }}
                    </h2>
                    <div class="space-y-3">
                        @foreach($applications as $application)
                            <div
                                class="flex items-center justify-between rounded-xl border border-slate-200 p-4 bg-white shadow-sm">
                                <div>
                                    <a href="{{ route('commercial.missions.show', $application->mission) }}" wire:navigate
                                        class="text-sm font-semibold text-slate-900 hover:text-[var(--theme-primary)] transition-colors">
                                        {{ $application->mission->title }}
                                    </a>
                                    <p class="text-xs text-slate-500 mt-1">
                                        {{ __('Postulé le') }} {{ $application->created_at->translatedFormat('d M Y') }}
                                    </p>
                                </div>
                                <span @class([
                                    'inline-flex items-center rounded-full px-3 py-1 text-xs font-semibold',
                                    'bg-amber-100 text-amber-800' => $application->status === \App\Enums\ApplicationStatus::Pending,
                                    'bg-sky-100 text-sky-800' => $application->status === \App\Enums\ApplicationStatus::Viewed,
                                    'bg-emerald-100 text-emerald-800' => $application->status === \App\Enums\ApplicationStatus::Accepted,
                                    'bg-rose-100 text-rose-800' => $application->status === \App\Enums\ApplicationStatus::Rejected,
                                ])>
                                    {{ $application->status->label() }}
                                </span>
                            </div>
                        @endforeach
                    </div>
                </div>
            </div>
        @endif
    </div>
</div>