<div class="flex h-full flex-col">
    <div class="border-b border-gray-200 p-4 dark:border-gray-700">
        <h2 class="text-lg font-semibold text-gray-900 dark:text-gray-100">
            {{ __('Conversations') }}
        </h2>
    </div>

    <div class="flex-1 overflow-y-auto">
        @forelse($conversations as $conversation)
            <button type="button" wire:click="selectConversation({{ $conversation->user->id }})" class="flex w-full items-center gap-3 border-b border-gray-100 p-4 text-left transition hover:bg-gray-50 dark:border-gray-800 dark:hover:bg-gray-800/50
                        {{ $selectedUserId === $conversation->user->id ? 'bg-indigo-50 dark:bg-indigo-900/20' : '' }}">
                <div
                    class="flex h-10 w-10 flex-shrink-0 items-center justify-center rounded-full bg-indigo-100 dark:bg-indigo-900">
                    <span class="text-sm font-medium text-indigo-600 dark:text-indigo-400">
                        {{ strtoupper(substr($conversation->user->name, 0, 2)) }}
                    </span>
                </div>
                <div class="min-w-0 flex-1">
                    <div class="flex items-center justify-between">
                        <p class="truncate text-sm font-medium text-gray-900 dark:text-gray-100">
                            {{ $conversation->user->name }}
                        </p>
                        @if($conversation->lastMessage)
                            <p class="text-xs text-gray-500 dark:text-gray-400">
                                {{ $conversation->lastMessage->created_at->diffForHumans(short: true) }}
                            </p>
                        @endif
                    </div>
                    <div class="flex items-center justify-between">
                        @if($conversation->lastMessage)
                            <p class="truncate text-sm text-gray-500 dark:text-gray-400">
                                {{ Str::limit($conversation->lastMessage->message, 30) }}
                            </p>
                        @else
                            <p class="text-sm text-gray-400 dark:text-gray-500">
                                {{ __('Aucun message') }}
                            </p>
                        @endif
                        @if($conversation->unreadCount > 0)
                            <span
                                class="ml-2 inline-flex h-5 min-w-5 items-center justify-center rounded-full bg-indigo-600 px-1.5 text-xs font-medium text-white">
                                {{ $conversation->unreadCount }}
                            </span>
                        @endif
                    </div>
                </div>
            </button>
        @empty
            <div class="p-8 text-center">
                <x-heroicon-o-chat-bubble-left-right class="mx-auto h-12 w-12 text-gray-400" />
                <p class="mt-4 text-sm text-gray-500 dark:text-gray-400">
                    {{ __('Aucune conversation pour le moment.') }}
                </p>
                <p class="mt-1 text-sm text-gray-400 dark:text-gray-500">
                    {{ __('Les messages échangés avec les commerciaux apparaîtront ici.') }}
                </p>
            </div>
        @endforelse
    </div>
</div>