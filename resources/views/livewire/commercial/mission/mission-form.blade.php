<div class="card-themed">
    <form wire:submit="save" class="p-6 sm:p-8 space-y-6">
        {{-- Titre --}}
        <div>
            <label for="title" class="block text-sm font-semibold text-slate-900 mb-2">
                {{ __('Titre de la mission') }} <span class="text-red-500">*</span>
            </label>
            <input type="text" id="title" wire:model="title"
                class="block w-full rounded-lg border-slate-300 shadow-sm focus:border-[var(--theme-primary)] focus:ring-[var(--theme-primary)] text-slate-900 placeholder-slate-400 sm:text-sm"
                placeholder="Ex: Développeur Laravel Senior" />
            @error('title')
                <p class="mt-2 text-sm text-red-600 flex items-center">
                    <x-heroicon-m-exclamation-circle class="w-4 h-4 mr-1" />
                    {{ $message }}
                </p>
            @enderror
        </div>

        {{-- Description --}}
        <div>
            <label for="description" class="block text-sm font-semibold text-slate-900 mb-2">
                {{ __('Description') }} <span class="text-red-500">*</span>
            </label>
            <textarea id="description" wire:model="description" rows="6"
                class="block w-full rounded-lg border-slate-300 shadow-sm focus:border-[var(--theme-primary)] focus:ring-[var(--theme-primary)] text-slate-900 placeholder-slate-400 sm:text-sm"
                placeholder="Décrivez la mission, les compétences requises, le contexte..."></textarea>
            @error('description')
                <p class="mt-2 text-sm text-red-600 flex items-center">
                    <x-heroicon-m-exclamation-circle class="w-4 h-4 mr-1" />
                    {{ $message }}
                </p>
            @enderror
        </div>

        {{-- Lieu --}}
        <div>
            <label for="location" class="block text-sm font-semibold text-slate-900 mb-2">
                {{ __('Lieu') }} <span class="text-red-500">*</span>
            </label>
            <input type="text" id="location" wire:model="location"
                class="block w-full rounded-lg border-slate-300 shadow-sm focus:border-[var(--theme-primary)] focus:ring-[var(--theme-primary)] text-slate-900 placeholder-slate-400 sm:text-sm"
                placeholder="Paris, Remote, Lyon..." />
            @error('location')
                <p class="mt-2 text-sm text-red-600 flex items-center">
                    <x-heroicon-m-exclamation-circle class="w-4 h-4 mr-1" />
                    {{ $message }}
                </p>
            @enderror
        </div>

        {{-- Tags --}}
        <div>
            <label class="block text-sm font-semibold text-slate-900 mb-3">
                {{ __('Compétences requises') }}
            </label>

            {{-- Add new tag --}}
            <div class="flex gap-2 mb-4">
                <div class="flex-1">
                    <input type="text" wire:model="newTagName" wire:keydown.enter.prevent="addTag"
                        class="block w-full rounded-lg border-slate-300 shadow-sm focus:border-[var(--theme-primary)] focus:ring-[var(--theme-primary)] text-slate-900 placeholder-slate-400 sm:text-sm"
                        placeholder="Ajouter une nouvelle compétence..." />
                    @error('newTagName')
                        <p class="mt-1 text-sm text-red-600">{{ $message }}</p>
                    @enderror
                </div>
                <button type="button" wire:click="addTag"
                    class="inline-flex items-center px-4 py-2 bg-[var(--theme-primary)] text-white text-sm font-medium rounded-lg hover:opacity-90 transition-opacity">
                    <x-heroicon-m-plus class="w-4 h-4 mr-1" />
                    Ajouter
                </button>
            </div>

            <div class="flex flex-wrap gap-2">
                @forelse($tags as $tag)
                    <button type="button" wire:click="toggleTag({{ $tag->id }})" @class([
                        'inline-flex items-center rounded-full px-4 py-2 text-sm font-medium transition-all duration-200',
                        'bg-[var(--theme-primary)] text-white shadow-md' => in_array($tag->id, $selectedTags),
                        'bg-white text-slate-700 hover:bg-slate-50 border border-slate-300' => !in_array($tag->id, $selectedTags),
                    ])>
                        @if (in_array($tag->id, $selectedTags))
                            <x-heroicon-m-check class="w-4 h-4 mr-1.5" />
                        @endif
                        {{ $tag->name }}
                    </button>
                @empty
                    <p class="text-sm text-slate-500 italic">
                        {{ __('Aucune compétence disponible. Ajoutez-en une ci-dessus.') }}
                    </p>
                @endforelse
            </div>
            @error('selectedTags')
                <p class="mt-2 text-sm text-red-600 flex items-center">
                    <x-heroicon-m-exclamation-circle class="w-4 h-4 mr-1" />
                    {{ $message }}
                </p>
            @enderror
        </div>

        {{-- Actions --}}
        <div class="flex items-center justify-between pt-6 border-t border-slate-200">
            <a href="{{ route('commercial.missions.index') }}" wire:navigate
                class="link-themed inline-flex items-center text-sm font-medium hover:underline">
                <x-heroicon-m-arrow-left class="w-4 h-4 mr-2" />
                {{ __('Retour') }}
            </a>

            <button type="submit" wire:loading.attr="disabled"
                class="btn-primary disabled:opacity-50 disabled:cursor-not-allowed">
                <span wire:loading.remove wire:target="save" class="flex items-center">
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