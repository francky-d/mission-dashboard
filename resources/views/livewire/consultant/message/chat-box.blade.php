<div class="flex h-full flex-col" wire:poll.5s>
    @if($this->receiver)
        {{-- Header --}}
        <div class="flex items-center gap-3 border-b border-gray-200 p-4 dark:border-gray-700">
            <div
                class="flex h-10 w-10 flex-shrink-0 items-center justify-center rounded-full bg-indigo-100 dark:bg-indigo-900">
                <span class="text-sm font-medium text-indigo-600 dark:text-indigo-400">
                    {{ strtoupper(substr($this->receiver->name, 0, 2)) }}
                </span>
            </div>
            <div>
                <h3 class="font-medium text-gray-900 dark:text-gray-100">
                    {{ $this->receiver->name }}
                </h3>
                <p class="text-sm text-gray-500 dark:text-gray-400">
                    {{ $this->receiver->role->label() }}
                </p>
            </div>
        </div>

        {{-- Messages --}}
        <div class="flex-1 space-y-4 overflow-y-auto p-4" id="messages-container" x-data
            x-init="$el.scrollTop = $el.scrollHeight"
            x-effect="$wire.chatMessages; $nextTick(() => $el.scrollTop = $el.scrollHeight)">
            @forelse($this->chatMessages as $chatMessage)
                <div class="flex {{ $chatMessage->sender_id === auth()->id() ? 'justify-end' : 'justify-start' }}">
                    <div class="max-w-xs rounded-lg px-4 py-2 lg:max-w-md {{ $chatMessage->sender_id === auth()->id()
                    ? 'bg-indigo-600 text-white'
                    : 'bg-gray-100 text-gray-900 dark:bg-gray-800 dark:text-gray-100' }}">
                        <p class="text-sm">{{ $chatMessage->message }}</p>
                        <p
                            class="mt-1 text-xs {{ $chatMessage->sender_id === auth()->id() ? 'text-indigo-200' : 'text-gray-500 dark:text-gray-400' }}">
                            {{ $chatMessage->created_at->format('H:i') }}
                        </p>
                    </div>
                </div>
            @empty
                <div class="flex h-full items-center justify-center">
                    <div class="text-center">
                        <x-heroicon-o-chat-bubble-oval-left class="mx-auto h-12 w-12 text-gray-400" />
                        <p class="mt-4 text-sm text-gray-500 dark:text-gray-400">
                            {{ __('Aucun message dans cette conversation.') }}
                        </p>
                        <p class="mt-1 text-sm text-gray-400 dark:text-gray-500">
                            {{ __('Envoyez le premier message !') }}
                        </p>
                    </div>
                </div>
            @endforelse
        </div>

        {{-- Input --}}
        <div class="border-t border-gray-200 p-4 dark:border-gray-700">
            <form wire:submit="sendMessage" class="flex gap-2">
                <input type="text" wire:model="newMessage" placeholder="{{ __('Écrivez votre message...') }}"
                    class="flex-1 rounded-lg border-gray-300 shadow-sm focus:border-indigo-500 focus:ring-indigo-500 dark:border-gray-700 dark:bg-gray-900 dark:text-gray-300"
                    autocomplete="off" />
                <button type="submit"
                    class="inline-flex items-center rounded-lg bg-indigo-600 px-4 py-2 text-sm font-medium text-white transition hover:bg-indigo-500 focus:outline-none focus:ring-2 focus:ring-indigo-500 focus:ring-offset-2 disabled:opacity-50 dark:focus:ring-offset-gray-800"
                    wire:loading.attr="disabled">
                    <x-heroicon-o-paper-airplane class="h-5 w-5" />
                </button>
            </form>
            @error('newMessage')
                <p class="mt-2 text-sm text-red-600 dark:text-red-400">{{ $message }}</p>
            @enderror
        </div>
    @else
        {{-- No conversation selected --}}
        <div class="flex h-full items-center justify-center">
            <div class="text-center">
                <x-heroicon-o-chat-bubble-left-right class="mx-auto h-16 w-16 text-gray-400" />
                <h3 class="mt-4 text-lg font-medium text-gray-900 dark:text-gray-100">
                    {{ __('Sélectionnez une conversation') }}
                </h3>
                <p class="mt-2 text-sm text-gray-500 dark:text-gray-400">
                    {{ __('Choisissez une conversation dans la liste pour voir les messages.') }}
                </p>
            </div>
        </div>
    @endif
</div>