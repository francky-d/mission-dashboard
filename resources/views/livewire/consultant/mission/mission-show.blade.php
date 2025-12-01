<div class="space-y-6">
    {{-- Retour à la liste --}}
    <div>
        <a href="{{ route('consultant.missions.index') }}"
            class="inline-flex items-center text-sm font-medium text-gray-500 hover:text-gray-700 dark:text-gray-400 dark:hover:text-gray-200">
            <x-heroicon-m-arrow-left class="w-4 h-4 mr-2" />
            {{ __('Retour aux missions') }}
        </a>
    </div>

    {{-- En-tête de la mission --}}
    <div
        class="bg-white dark:bg-gray-800 rounded-lg shadow-sm border border-gray-200 dark:border-gray-700 overflow-hidden">
        <div class="p-6">
            <div class="flex items-start justify-between">
                <div class="flex-1">
                    <h1 class="text-2xl font-bold text-gray-900 dark:text-white">
                        {{ $mission->title }}
                    </h1>
                    <div class="mt-2 flex flex-wrap items-center gap-4 text-sm text-gray-500 dark:text-gray-400">
                        @if($mission->location)
                            <div class="flex items-center">
                                <x-heroicon-m-map-pin class="w-4 h-4 mr-1" />
                                {{ $mission->location }}
                            </div>
                        @endif
                        @if($mission->daily_rate)
                            <div class="flex items-center">
                                <x-heroicon-m-currency-euro class="w-4 h-4 mr-1" />
                                {{ number_format($mission->daily_rate, 0, ',', ' ') }} €/jour
                            </div>
                        @endif
                        <div class="flex items-center">
                            <x-heroicon-m-calendar class="w-4 h-4 mr-1" />
                            {{ __('Publiée le') }} {{ $mission->created_at->format('d/m/Y') }}
                        </div>
                    </div>
                </div>
                <div>
                    <span
                        class="inline-flex items-center rounded-full px-3 py-1 text-sm font-medium bg-green-100 text-green-800 dark:bg-green-900/30 dark:text-green-400">
                        {{ $mission->status->label() }}
                    </span>
                </div>
            </div>

            {{-- Tags --}}
            @if($mission->tags->isNotEmpty())
                <div class="mt-4 flex flex-wrap gap-2">
                    @foreach($mission->tags as $tag)
                        <span
                            class="inline-flex items-center rounded-full bg-blue-100 px-2.5 py-0.5 text-xs font-medium text-blue-800 dark:bg-blue-900/30 dark:text-blue-400">
                            {{ $tag->name }}
                        </span>
                    @endforeach
                </div>
            @endif
        </div>
    </div>

    {{-- Description --}}
    <div
        class="bg-white dark:bg-gray-800 rounded-lg shadow-sm border border-gray-200 dark:border-gray-700 overflow-hidden">
        <div class="p-6">
            <h2 class="text-lg font-semibold text-gray-900 dark:text-white mb-4">
                {{ __('Description de la mission') }}
            </h2>
            <div class="prose dark:prose-invert max-w-none text-gray-600 dark:text-gray-300">
                {!! nl2br(e($mission->description)) !!}
            </div>
        </div>
    </div>

    {{-- Contact commercial --}}
    <div
        class="bg-white dark:bg-gray-800 rounded-lg shadow-sm border border-gray-200 dark:border-gray-700 overflow-hidden">
        <div class="p-6">
            <h2 class="text-lg font-semibold text-gray-900 dark:text-white mb-4">
                {{ __('Contact') }}
            </h2>
            <div class="flex items-center">
                <div class="h-12 w-12 rounded-full bg-gray-200 dark:bg-gray-700 flex items-center justify-center">
                    <x-heroicon-m-user class="w-6 h-6 text-gray-500 dark:text-gray-400" />
                </div>
                <div class="ml-4">
                    <p class="text-sm font-medium text-gray-900 dark:text-white">
                        {{ $mission->commercial->name }}
                    </p>
                    <p class="text-sm text-gray-500 dark:text-gray-400">
                        {{ __('Commercial') }}
                    </p>
                </div>
            </div>
        </div>
    </div>

    {{-- Actions --}}
    <div class="flex justify-end">
        <x-primary-button wire:navigate href="{{ route('consultant.missions.index') }}">
            {{ __('Retour à la liste') }}
        </x-primary-button>
    </div>
</div>