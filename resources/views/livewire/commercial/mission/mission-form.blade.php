<div class="bg-white dark:bg-gray-800 rounded-lg shadow-sm border border-gray-200 dark:border-gray-700 overflow-hidden">
    <form wire:submit="save" class="p-6 space-y-6">
        {{-- Titre --}}
        <div>
            <label for="title" class="block text-sm font-medium text-gray-700 dark:text-gray-300">
                {{ __('Titre de la mission') }} <span class="text-red-500">*</span>
            </label>
            <input type="text" id="title" wire:model="title"
                class="mt-1 block w-full rounded-md border-gray-300 shadow-sm focus:border-indigo-500 focus:ring-indigo-500 dark:border-gray-600 dark:bg-gray-700 dark:text-white sm:text-sm"
                placeholder="Ex: Développeur Laravel Senior" />
            @error('title')
                <p class="mt-1 text-sm text-red-600 dark:text-red-400">{{ $message }}</p>
            @enderror
        </div>

        {{-- Description --}}
        <div>
            <label for="description" class="block text-sm font-medium text-gray-700 dark:text-gray-300">
                {{ __('Description') }} <span class="text-red-500">*</span>
            </label>
            <textarea id="description" wire:model="description" rows="6"
                class="mt-1 block w-full rounded-md border-gray-300 shadow-sm focus:border-indigo-500 focus:ring-indigo-500 dark:border-gray-600 dark:bg-gray-700 dark:text-white sm:text-sm"
                placeholder="Décrivez la mission, les compétences requises, le contexte..."></textarea>
            @error('description')
                <p class="mt-1 text-sm text-red-600 dark:text-red-400">{{ $message }}</p>
            @enderror
        </div>

        {{-- TJM et Lieu --}}
        <div class="grid grid-cols-1 gap-6 sm:grid-cols-2">
            <div>
                <label for="daily_rate" class="block text-sm font-medium text-gray-700 dark:text-gray-300">
                    {{ __('TJM (€/jour)') }} <span class="text-red-500">*</span>
                </label>
                <div class="mt-1 relative rounded-md shadow-sm">
                    <input type="number" id="daily_rate" wire:model="daily_rate" min="100" max="5000"
                        class="block w-full rounded-md border-gray-300 pr-12 shadow-sm focus:border-indigo-500 focus:ring-indigo-500 dark:border-gray-600 dark:bg-gray-700 dark:text-white sm:text-sm"
                        placeholder="500" />
                    <div class="pointer-events-none absolute inset-y-0 right-0 flex items-center pr-3">
                        <span class="text-gray-500 sm:text-sm">€</span>
                    </div>
                </div>
                @error('daily_rate')
                    <p class="mt-1 text-sm text-red-600 dark:text-red-400">{{ $message }}</p>
                @enderror
            </div>

            <div>
                <label for="location" class="block text-sm font-medium text-gray-700 dark:text-gray-300">
                    {{ __('Lieu') }} <span class="text-red-500">*</span>
                </label>
                <input type="text" id="location" wire:model="location"
                    class="mt-1 block w-full rounded-md border-gray-300 shadow-sm focus:border-indigo-500 focus:ring-indigo-500 dark:border-gray-600 dark:bg-gray-700 dark:text-white sm:text-sm"
                    placeholder="Paris, Remote, Lyon..." />
                @error('location')
                    <p class="mt-1 text-sm text-red-600 dark:text-red-400">{{ $message }}</p>
                @enderror
            </div>
        </div>

        {{-- Tags --}}
        <div>
            <label class="block text-sm font-medium text-gray-700 dark:text-gray-300 mb-2">
                {{ __('Compétences requises') }}
            </label>
            <div class="flex flex-wrap gap-2">
                @forelse($tags as $tag)
                    <button type="button" wire:click="toggleTag({{ $tag->id }})" @class([
                        'inline-flex items-center rounded-full px-3 py-1.5 text-sm font-medium transition-colors',
                        'bg-indigo-600 text-white' => in_array($tag->id, $selectedTags),
                        'bg-gray-100 text-gray-700 hover:bg-gray-200 dark:bg-gray-700 dark:text-gray-300 dark:hover:bg-gray-600' => !in_array($tag->id, $selectedTags),
                    ])>
                        @if(in_array($tag->id, $selectedTags))
                            <x-heroicon-m-check class="w-4 h-4 mr-1" />
                        @endif
                        {{ $tag->name }}
                    </button>
                @empty
                    <p class="text-sm text-gray-500 dark:text-gray-400">
                        {{ __('Aucun tag disponible.') }}
                    </p>
                @endforelse
            </div>
            @error('selectedTags')
                <p class="mt-1 text-sm text-red-600 dark:text-red-400">{{ $message }}</p>
            @enderror
        </div>

        {{-- Actions --}}
        <div class="flex items-center justify-between pt-4 border-t border-gray-200 dark:border-gray-700">
            <a href="{{ route('commercial.missions.index') }}" wire:navigate
                class="inline-flex items-center text-sm font-medium text-gray-500 hover:text-gray-700 dark:text-gray-400 dark:hover:text-gray-200">
                <x-heroicon-m-arrow-left class="w-4 h-4 mr-2" />
                {{ __('Retour') }}
            </a>

            <button type="submit" wire:loading.attr="disabled"
                class="inline-flex items-center rounded-md bg-indigo-600 px-4 py-2 text-sm font-semibold text-white shadow-sm hover:bg-indigo-500 focus-visible:outline focus-visible:outline-2 focus-visible:outline-offset-2 focus-visible:outline-indigo-600 disabled:opacity-50 disabled:cursor-not-allowed">
                <span wire:loading.remove wire:target="save">
                    @if($isEditing)
                        <x-heroicon-m-check class="w-4 h-4 mr-2" />
                        {{ __('Mettre à jour') }}
                    @else
                        <x-heroicon-m-plus class="w-4 h-4 mr-2" />
                        {{ __('Créer la mission') }}
                    @endif
                </span>
                <span wire:loading wire:target="save" class="flex items-center">
                    <svg class="animate-spin -ml-1 mr-2 h-4 w-4 text-white" xmlns="http://www.w3.org/2000/svg"
                        fill="none" viewBox="0 0 24 24">
                        <circle class="opacity-25" cx="12" cy="12" r="10" stroke="currentColor" stroke-width="4">
                        </circle>
                        <path class="opacity-75" fill="currentColor"
                            d="M4 12a8 8 0 018-8V0C5.373 0 0 5.373 0 12h4zm2 5.291A7.962 7.962 0 014 12H0c0 3.042 1.135 5.824 3 7.938l3-2.647z">
                        </path>
                    </svg>
                    {{ __('Enregistrement...') }}
                </span>
            </button>
        </div>
    </form>
</div>