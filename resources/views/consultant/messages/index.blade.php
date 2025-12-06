<x-app-layout>
    <x-slot name="header">
        <h2 class="text-xl font-bold leading-tight text-slate-900">
            {{ __('Messages') }}
        </h2>
    </x-slot>

    <div class="py-8">
        <div class="mx-auto max-w-7xl sm:px-6 lg:px-8">
            <div class="overflow-hidden bg-white rounded-xl border border-slate-200 shadow-lg">
                <div class="flex h-[600px]">
                    {{-- Liste des conversations --}}
                    <div class="w-80 flex-shrink-0 border-r border-slate-200">
                        @livewire('consultant.message.conversation-list')
                    </div>

                    {{-- Zone de chat --}}
                    <div class="flex-1">
                        @livewire('consultant.message.chat-box')
                    </div>
                </div>
            </div>
        </div>
    </div>
</x-app-layout>