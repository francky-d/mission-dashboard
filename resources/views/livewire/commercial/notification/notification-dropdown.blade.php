<div class="relative" x-data="{ open: @entangle('isOpen') }">
    {{-- Notification Bell Button --}}
    <button @click="open = !open" type="button"
        class="relative inline-flex items-center justify-center rounded-md p-2 text-gray-500 hover:bg-gray-100 hover:text-gray-700 focus:outline-none focus:ring-2 focus:ring-indigo-500 focus:ring-offset-2 dark:text-gray-400 dark:hover:bg-gray-700 dark:hover:text-gray-300">
        <span class="sr-only">{{ __('Voir les notifications') }}</span>
        <x-heroicon-o-bell class="h-6 w-6" />

        {{-- Unread Badge --}}
        @if ($this->unreadCount > 0)
            <span
                class="absolute -top-1 -right-1 flex h-5 w-5 items-center justify-center rounded-full bg-red-500 text-xs font-bold text-white">
                {{ $this->unreadCount > 9 ? '9+' : $this->unreadCount }}
            </span>
        @endif
    </button>

    {{-- Dropdown Panel --}}
    <div x-show="open" @click.outside="open = false" x-transition:enter="transition ease-out duration-200"
        x-transition:enter-start="opacity-0 scale-95" x-transition:enter-end="opacity-100 scale-100"
        x-transition:leave="transition ease-in duration-75" x-transition:leave-start="opacity-100 scale-100"
        x-transition:leave-end="opacity-0 scale-95"
        class="absolute right-0 z-50 mt-2 w-80 origin-top-right rounded-md bg-white shadow-lg ring-1 ring-black ring-opacity-5 focus:outline-none dark:bg-gray-800 dark:ring-gray-700"
        style="display: none;">
        {{-- Header --}}
        <div class="flex items-center justify-between border-b border-gray-200 px-4 py-3 dark:border-gray-700">
            <h3 class="text-sm font-semibold text-gray-900 dark:text-white">
                {{ __('Notifications') }}
            </h3>
            @if ($this->unreadCount > 0)
                <button wire:click="markAllAsRead" type="button"
                    class="text-xs text-indigo-600 hover:text-indigo-800 dark:text-indigo-400 dark:hover:text-indigo-300">
                    {{ __('Tout marquer comme lu') }}
                </button>
            @endif
        </div>

        {{-- Notification List --}}
        <div class="max-h-96 overflow-y-auto">
            @forelse ($this->notifications as $notification)
                <div wire:key="notification-{{ $notification->id }}"
                    class="border-b border-gray-100 px-4 py-3 hover:bg-gray-50 dark:border-gray-700 dark:hover:bg-gray-700 {{ is_null($notification->read_at) ? 'bg-indigo-50 dark:bg-indigo-900/20' : '' }}">
                    <div class="flex items-start justify-between">
                        <div class="flex-1">
                            {{-- Notification Icon --}}
                            <div class="flex items-center gap-2">
                                @if ($notification->type === \App\Notifications\NewApplicationReceived::class)
                                    <x-heroicon-s-user-plus class="h-4 w-4 text-green-500" />
                                @elseif ($notification->type === \App\Notifications\NewMessageReceived::class)
                                    <x-heroicon-s-chat-bubble-left class="h-4 w-4 text-blue-500" />
                                @else
                                    <x-heroicon-s-bell class="h-4 w-4 text-gray-500" />
                                @endif

                                <p class="text-sm font-medium text-gray-900 dark:text-white">
                                    {{ $notification->data['title'] ?? __('Notification') }}
                                </p>
                            </div>

                            {{-- Message --}}
                            <p class="mt-1 text-xs text-gray-600 dark:text-gray-400">
                                {{ $notification->data['message'] ?? '' }}
                            </p>

                            {{-- Timestamp --}}
                            <p class="mt-1 text-xs text-gray-400 dark:text-gray-500">
                                {{ $notification->created_at->diffForHumans() }}
                            </p>
                        </div>

                        {{-- Mark as Read Button --}}
                        @if (is_null($notification->read_at))
                            <button wire:click="markAsRead('{{ $notification->id }}')" type="button"
                                class="ml-2 text-gray-400 hover:text-indigo-500" title="{{ __('Marquer comme lu') }}">
                                <x-heroicon-o-check-circle class="h-5 w-5" />
                            </button>
                        @endif
                    </div>
                </div>
            @empty
                <div class="px-4 py-8 text-center">
                    <x-heroicon-o-bell-slash class="mx-auto h-8 w-8 text-gray-400" />
                    <p class="mt-2 text-sm text-gray-500 dark:text-gray-400">
                        {{ __('Aucune notification') }}
                    </p>
                </div>
            @endforelse
        </div>
    </div>
</div>