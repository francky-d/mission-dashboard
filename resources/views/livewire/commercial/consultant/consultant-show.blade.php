@php use Illuminate\Support\Facades\Storage; @endphp
<div class="space-y-6">
    {{-- Back link --}}
    <div>
        <a href="javascript:history.back()"
            class="inline-flex items-center text-sm font-medium text-gray-500 hover:text-gray-700 dark:text-gray-400 dark:hover:text-gray-200">
            <x-heroicon-m-arrow-left class="w-4 h-4 mr-2" />
            {{ __('Retour') }}
        </a>
    </div>

    {{-- Profile Header --}}
    <div
        class="bg-white dark:bg-gray-800 rounded-lg shadow-sm border border-gray-200 dark:border-gray-700 overflow-hidden">
        <div class="p-6">
            <div class="flex items-start gap-6">
                {{-- Avatar --}}
                <div
                    class="h-20 w-20 rounded-full bg-indigo-100 dark:bg-indigo-900/30 flex items-center justify-center flex-shrink-0">
                    <span class="text-3xl font-bold text-indigo-600 dark:text-indigo-400">
                        {{ strtoupper(substr($user->name, 0, 1)) }}
                    </span>
                </div>

                <div class="flex-1">
                    <h1 class="text-2xl font-bold text-gray-900 dark:text-white">
                        {{ $user->name }}
                    </h1>
                    <p class="text-sm text-gray-500 dark:text-gray-400">
                        {{ $user->email }}
                    </p>

                    @if($user->consultantProfile)
                        <div class="mt-4 flex flex-wrap items-center gap-4 text-sm text-gray-500 dark:text-gray-400">
                            @if($user->consultantProfile->experience_years)
                                <span class="flex items-center">
                                    <x-heroicon-m-briefcase class="w-4 h-4 mr-1" />
                                    {{ $user->consultantProfile->experience_years }} {{ __('années d\'expérience') }}
                                </span>
                            @endif
                            @if($user->consultantProfile->cv_url)
                                <a href="{{ Storage::url($user->consultantProfile->cv_url) }}" target="_blank"
                                    class="flex items-center text-indigo-600 hover:text-indigo-500 dark:text-indigo-400">
                                    <x-heroicon-m-document-arrow-down class="w-4 h-4 mr-1" />
                                    {{ __('Télécharger le CV') }}
                                </a>
                            @endif
                        </div>
                    @endif

                    {{-- Contact Button --}}
                    <div class="mt-4">
                        <a href="{{ route('commercial.messages.index') }}?consultant={{ $user->id }}" wire:navigate
                            class="inline-flex items-center rounded-md bg-indigo-600 px-4 py-2 text-sm font-semibold text-white shadow-sm hover:bg-indigo-500">
                            <x-heroicon-m-chat-bubble-left class="w-4 h-4 mr-2" />
                            {{ __('Contacter') }}
                        </a>
                    </div>
                </div>
            </div>
        </div>
    </div>

    {{-- Skills / Tags --}}
    @if($user->consultantProfile && $user->consultantProfile->tags->isNotEmpty())
        <div
            class="bg-white dark:bg-gray-800 rounded-lg shadow-sm border border-gray-200 dark:border-gray-700 overflow-hidden">
            <div class="p-6">
                <h2 class="text-lg font-semibold text-gray-900 dark:text-white mb-4">
                    {{ __('Compétences') }}
                </h2>
                <div class="flex flex-wrap gap-2">
                    @foreach($user->consultantProfile->tags as $tag)
                        <span
                            class="inline-flex items-center rounded-full bg-blue-100 px-3 py-1 text-sm font-medium text-blue-800 dark:bg-blue-900/30 dark:text-blue-400">
                            {{ $tag->name }}
                        </span>
                    @endforeach
                </div>
            </div>
        </div>
    @endif

    {{-- Bio --}}
    @if($user->consultantProfile && $user->consultantProfile->bio)
        <div
            class="bg-white dark:bg-gray-800 rounded-lg shadow-sm border border-gray-200 dark:border-gray-700 overflow-hidden">
            <div class="p-6">
                <h2 class="text-lg font-semibold text-gray-900 dark:text-white mb-4">
                    {{ __('Biographie') }}
                </h2>
                <div class="prose dark:prose-invert max-w-none text-gray-600 dark:text-gray-300">
                    {!! nl2br(e($user->consultantProfile->bio)) !!}
                </div>
            </div>
        </div>
    @endif

    {{-- Applications to my missions --}}
    @if($applications->isNotEmpty())
        <div
            class="bg-white dark:bg-gray-800 rounded-lg shadow-sm border border-gray-200 dark:border-gray-700 overflow-hidden">
            <div class="p-6">
                <h2 class="text-lg font-semibold text-gray-900 dark:text-white mb-4">
                    {{ __('Candidatures sur mes missions') }}
                </h2>
                <div class="space-y-3">
                    @foreach($applications as $application)
                        <div
                            class="flex items-center justify-between rounded-lg border border-gray-200 dark:border-gray-700 p-3">
                            <div>
                                <a href="{{ route('commercial.missions.show', $application->mission) }}" wire:navigate
                                    class="text-sm font-medium text-gray-900 hover:text-indigo-600 dark:text-white dark:hover:text-indigo-400">
                                    {{ $application->mission->title }}
                                </a>
                                <p class="text-xs text-gray-500 dark:text-gray-400">
                                    {{ __('Postulé le') }} {{ $application->created_at->format('d/m/Y') }}
                                </p>
                            </div>
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
                    @endforeach
                </div>
            </div>
        </div>
    @endif
</div>