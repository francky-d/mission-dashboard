<x-app-layout>
    <x-slot name="header">
        <h2 class="text-xl font-bold leading-tight text-slate-900">
            {{ __('Mon profil') }}
        </h2>
    </x-slot>

    <div class="py-8">
        <div class="mx-auto max-w-7xl space-y-6 sm:px-6 lg:px-8">
            <div class="card-themed p-6 sm:p-8">
                <div class="max-w-xl">
                    <livewire:profile.update-profile-information-form />
                </div>
            </div>

            <div class="card-themed p-6 sm:p-8">
                <div class="max-w-xl">
                    <livewire:consultant.profile.edit-profile />
                </div>
            </div>

            <div class="card-themed p-6 sm:p-8">
                <div class="max-w-xl">
                    <livewire:profile.update-password-form />
                </div>
            </div>

            <div class="card-themed p-6 sm:p-8">
                <div class="max-w-xl">
                    <livewire:profile.delete-user-form />
                </div>
            </div>
        </div>
    </div>
</x-app-layout>