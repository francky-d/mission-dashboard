<x-app-layout>
    <x-slot name="header">
        <h2 class="text-xl font-semibold leading-tight text-slate-900">
            {{ __('Messages') }}
        </h2>
    </x-slot>

    <div class="py-12">
        <div class="mx-auto max-w-7xl sm:px-6 lg:px-8">
            <div class="overflow-hidden bg-white shadow-sm dark:bg-gray-800 sm:rounded-lg">
                <div class="flex h-[600px]">
                    {{-- Conversation List --}}
                    <div class="w-80 flex-shrink-0 border-r border-gray-200 dark:border-gray-700">
                        @livewire('consultant.message.conversation-list')
                    </div>

                    {{-- Chat Box --}}
                    <div class="flex-1">
                        @livewire('consultant.message.chat-box')
                    </div>
                </div>
            </div>
        </div>
    </div>
</x-app-layout>