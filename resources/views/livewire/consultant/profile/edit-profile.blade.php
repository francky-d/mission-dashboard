<section>
    <header>
        <h2 class="text-lg font-bold text-slate-900">
            {{ __('Profil Consultant') }}
        </h2>

        <p class="mt-1 text-sm text-slate-600">
            {{ __('Complétez votre profil pour être visible auprès des commerciaux et postuler aux missions.') }}
        </p>
    </header>

    <form wire:submit="save" class="mt-6 space-y-6">
        <!-- Bio -->
        <div>
            <x-input-label for="bio" :value="__('Biographie')" class="text-slate-900 font-semibold" />
            <textarea id="bio" wire:model="bio" rows="4"
                class="mt-2 block w-full rounded-xl border-slate-300 shadow-sm focus:border-[var(--theme-primary)] focus:ring-[var(--theme-primary)] text-slate-900 placeholder-slate-400"
                placeholder="{{ __('Décrivez votre parcours, vos expériences et vos centres d\'intérêt professionnels...') }}"></textarea>
            <x-input-error :messages="$errors->get('bio')" class="mt-2" />
        </div>

        <!-- Experience Years -->
        <div>
            <x-input-label for="experienceYears" :value="__('Années d\'expérience')"
                class="text-slate-900 font-semibold" />
            <x-text-input id="experienceYears" wire:model="experienceYears" type="number" min="0" max="50"
                class="mt-2 block w-32 rounded-xl border-slate-300 shadow-sm focus:border-[var(--theme-primary)] focus:ring-[var(--theme-primary)] text-slate-900" />
            <x-input-error :messages="$errors->get('experienceYears')" class="mt-2" />
        </div>

        <!-- Tags/Compétences -->
        <div>
            <x-input-label :value="__('Compétences / Tags')" class="text-slate-900 font-semibold" />
            <p class="mt-1 text-sm text-slate-500">
                {{ __('Sélectionnez les compétences qui correspondent à votre profil.') }}
            </p>
            <div class="mt-3 flex flex-wrap gap-2">
                @forelse($tags as $tag)
                            <label class="inline-flex cursor-pointer items-center rounded-full border px-4 py-2 text-sm font-medium transition-all duration-200
                                    {{ in_array($tag->id, $selectedTags)
                    ? 'border-[var(--theme-primary)] bg-[var(--theme-primary)] text-white shadow-md'
                    : 'border-slate-300 bg-white text-slate-700 hover:bg-slate-50' }}">
                                <input type="checkbox" wire:model.live="selectedTags" value="{{ $tag->id }}" class="sr-only" />
                                @if(in_array($tag->id, $selectedTags))
                                    <x-heroicon-m-check class="w-4 h-4 mr-1.5" />
                                @endif
                                {{ $tag->name }}
                            </label>
                @empty
                    <p class="text-sm text-slate-500 italic">
                        {{ __('Aucun tag disponible pour le moment.') }}
                    </p>
                @endforelse
            </div>
            <x-input-error :messages="$errors->get('selectedTags')" class="mt-2" />
        </div>

        <!-- CV Upload -->
        <div>
            <x-input-label for="cv" :value="__('CV (PDF, max 5 Mo)')" class="text-slate-900 font-semibold" />

            @if($existingCvUrl)
                <div class="mt-3 flex items-center gap-4 p-3 bg-slate-50 rounded-xl">
                    <a href="{{ Storage::url($existingCvUrl) }}" target="_blank"
                        class="link-themed inline-flex items-center text-sm hover:underline">
                        <x-heroicon-m-document-arrow-down class="w-4 h-4 mr-1.5" />
                        {{ __('Voir le CV actuel') }}
                    </a>
                    <button type="button" wire:click="deleteCv"
                        wire:confirm="{{ __('Êtes-vous sûr de vouloir supprimer votre CV ?') }}"
                        class="text-sm text-red-600 hover:text-red-700 hover:underline">
                        {{ __('Supprimer') }}
                    </button>
                </div>
            @endif

            <input id="cv" type="file" wire:model="cv" accept=".pdf"
                class="mt-3 block w-full text-sm text-slate-600 file:mr-4 file:rounded-full file:border-0 file:bg-[var(--theme-primary)]/10 file:px-4 file:py-2 file:text-sm file:font-semibold file:text-[var(--theme-primary)] hover:file:bg-[var(--theme-primary)]/20 transition-colors" />

            <div wire:loading wire:target="cv" class="mt-2 text-sm text-slate-500 flex items-center">
                <svg class="animate-spin h-4 w-4 mr-2" xmlns="http://www.w3.org/2000/svg" fill="none"
                    viewBox="0 0 24 24">
                    <circle class="opacity-25" cx="12" cy="12" r="10" stroke="currentColor" stroke-width="4"></circle>
                    <path class="opacity-75" fill="currentColor"
                        d="M4 12a8 8 0 018-8V0C5.373 0 0 5.373 0 12h4zm2 5.291A7.962 7.962 0 014 12H0c0 3.042 1.135 5.824 3 7.938l3-2.647z">
                    </path>
                </svg>
                {{ __('Téléchargement en cours...') }}
            </div>

            <x-input-error :messages="$errors->get('cv')" class="mt-2" />
        </div>

        <div class="flex items-center gap-4 pt-4 border-t border-slate-200">
            <x-primary-button class="btn-primary">{{ __('Enregistrer') }}</x-primary-button>

            <x-action-message class="me-3 text-emerald-600 font-medium" on="profile-updated">
                {{ __('Enregistré.') }}
            </x-action-message>
        </div>
    </form>
</section>