<x-app-layout>
    <x-slot name="header">
        <div class="flex items-center justify-between">
            <h2 class="text-xl font-semibold leading-tight text-gray-800 dark:text-gray-200">
                {{ __('Mes missions') }}
            </h2>
            <a href="{{ route('commercial.missions.create') }}" wire:navigate
                class="inline-flex items-center rounded-md bg-indigo-600 px-4 py-2 text-sm font-semibold text-white shadow-sm hover:bg-indigo-500">
                <x-heroicon-m-plus class="w-4 h-4 mr-2" />
                {{ __('Nouvelle mission') }}
            </a>
        </div>
    </x-slot>

    <div class="py-12">
        <div class="mx-auto max-w-7xl sm:px-6 lg:px-8">
            <livewire:commercial.mission.mission-list />
        </div>
    </div>
</x-app-layout>