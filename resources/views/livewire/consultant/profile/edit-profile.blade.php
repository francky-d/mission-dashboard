<section>
    <header>
        <h2 class="text-lg font-medium text-gray-900 dark:text-gray-100">
            {{ __('Profil Consultant') }}
        </h2>

        <p class="mt-1 text-sm text-gray-600 dark:text-gray-400">
            {{ __('Complétez votre profil pour être visible auprès des commerciaux et postuler aux missions.') }}
        </p>
    </header>

    <form wire:submit="save" class="mt-6 space-y-6">
        <!-- Bio -->
        <div>
            <x-input-label for="bio" :value="__('Biographie')" />
            <textarea id="bio" wire:model="bio" rows="4"
                class="mt-1 block w-full rounded-md border-gray-300 shadow-sm focus:border-indigo-500 focus:ring-indigo-500 dark:border-gray-700 dark:bg-gray-900 dark:text-gray-300"
                placeholder="{{ __('Décrivez votre parcours, vos expériences et vos centres d\'intérêt professionnels...') }}"></textarea>
            <x-input-error :messages="$errors->get('bio')" class="mt-2" />
        </div>

        <!-- Experience Years -->
        <div>
            <x-input-label for="experienceYears" :value="__('Années d\'expérience')" />
            <x-text-input id="experienceYears" wire:model="experienceYears" type="number" min="0" max="50"
                class="mt-1 block w-32" />
            <x-input-error :messages="$errors->get('experienceYears')" class="mt-2" />
        </div>

        <!-- Tags/Compétences -->
        <div>
            <x-input-label :value="__('Compétences / Tags')" />
            <p class="mt-1 text-sm text-gray-600 dark:text-gray-400">
                {{ __('Sélectionnez les compétences qui correspondent à votre profil.') }}
            </p>
            <div class="mt-3 flex flex-wrap gap-2">
                @forelse($tags as $tag)
                            <label
                                class="inline-flex cursor-pointer items-center rounded-full border px-3 py-1 text-sm transition-colors
                                    {{ in_array($tag->id, $selectedTags)
                    ? 'border-indigo-500 bg-indigo-100 text-indigo-800 dark:bg-indigo-900 dark:text-indigo-200'
                    : 'border-gray-300 bg-white text-gray-700 hover:bg-gray-50 dark:border-gray-600 dark:bg-gray-800 dark:text-gray-300 dark:hover:bg-gray-700' }}">
                                <input type="checkbox" wire:model.live="selectedTags" value="{{ $tag->id }}" class="sr-only" />
                                {{ $tag->name }}
                            </label>
                @empty
                    <p class="text-sm text-gray-500 dark:text-gray-400">
                        {{ __('Aucun tag disponible pour le moment.') }}
                    </p>
                @endforelse
            </div>
            <x-input-error :messages="$errors->get('selectedTags')" class="mt-2" />
        </div>

        <!-- CV Upload -->
        <div>
            <x-input-label for="cv" :value="__('CV (PDF, max 5 Mo)')" />

            @if($existingCvUrl)
                <div class="mt-2 flex items-center gap-4">
                    <a href="{{ Storage::url($existingCvUrl) }}" target="_blank"
                        class="inline-flex items-center text-sm text-indigo-600 hover:text-indigo-500 dark:text-indigo-400">
                        <svg class="mr-1 h-4 w-4" fill="none" stroke="currentColor" viewBox="0 0 24 24">
                            <path stroke-linecap="round" stroke-linejoin="round" stroke-width="2"
                                d="M12 10v6m0 0l-3-3m3 3l3-3m2 8H7a2 2 0 01-2-2V5a2 2 0 012-2h5.586a1 1 0 01.707.293l5.414 5.414a1 1 0 01.293.707V19a2 2 0 01-2 2z" />
                        </svg>
                        {{ __('Voir le CV actuel') }}
                    </a>
                    <button type="button" wire:click="deleteCv"
                        wire:confirm="{{ __('Êtes-vous sûr de vouloir supprimer votre CV ?') }}"
                        class="text-sm text-red-600 hover:text-red-500 dark:text-red-400">
                        {{ __('Supprimer') }}
                    </button>
                </div>
            @endif

            <input id="cv" type="file" wire:model="cv" accept=".pdf"
                class="mt-2 block w-full text-sm text-gray-500 file:mr-4 file:rounded-full file:border-0 file:bg-indigo-50 file:px-4 file:py-2 file:text-sm file:font-semibold file:text-indigo-700 hover:file:bg-indigo-100 dark:text-gray-400 dark:file:bg-indigo-900 dark:file:text-indigo-300" />

            <div wire:loading wire:target="cv" class="mt-2 text-sm text-gray-500">
                {{ __('Téléchargement en cours...') }}
            </div>

            <x-input-error :messages="$errors->get('cv')" class="mt-2" />
        </div>

        <div class="flex items-center gap-4">
            <x-primary-button>{{ __('Enregistrer') }}</x-primary-button>

            <x-action-message class="me-3" on="profile-updated">
                {{ __('Enregistré.') }}
            </x-action-message>
        </div>
    </form>
</section>