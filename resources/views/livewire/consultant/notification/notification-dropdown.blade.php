<div class="relative" x-data="{ open: @entangle('isOpen') }">
    {{-- Bouton Notifications --}}
    <button @click="open = !open" type="button"
        class="relative inline-flex items-center justify-center rounded-lg p-2 text-slate-500 hover:bg-slate-100 hover:text-slate-700 focus:outline-none focus:ring-2 focus:ring-[var(--theme-primary)] focus:ring-offset-2 transition-colors">
        <span class="sr-only">{{ __('Voir les notifications') }}</span>
        <x-heroicon-o-bell class="h-6 w-6" />

        {{-- Badge non lus --}}
        @if ($this->unreadCount > 0)
            <span
                class="absolute -top-1 -right-1 flex h-5 w-5 items-center justify-center rounded-full bg-red-500 text-xs font-bold text-white shadow-sm">
                {{ $this->unreadCount > 9 ? '9+' : $this->unreadCount }}
            </span>
        @endif
    </button>

    {{-- Panel Dropdown --}}
    <div x-show="open" @click.outside="open = false" x-transition:enter="transition ease-out duration-200"
        x-transition:enter-start="opacity-0 scale-95" x-transition:enter-end="opacity-100 scale-100"
        x-transition:leave="transition ease-in duration-75" x-transition:leave-start="opacity-100 scale-100"
        x-transition:leave-end="opacity-0 scale-95"
        class="absolute right-0 z-50 mt-2 w-80 origin-top-right rounded-xl bg-white shadow-xl ring-1 ring-slate-200 focus:outline-none overflow-hidden"
        style="display: none;">
        {{-- Header --}}
        <div class="flex items-center justify-between border-b border-slate-200 px-4 py-3 bg-slate-50">
            <h3 class="text-sm font-bold text-slate-900">
                {{ __('Notifications') }}
            </h3>
            @if ($this->unreadCount > 0)
                <button wire:click="markAllAsRead" type="button" class="text-xs link-themed hover:underline font-medium">
                    {{ __('Tout marquer comme lu') }}
                </button>
            @endif
        </div>

        {{-- Liste des notifications --}}
        <div class="max-h-96 overflow-y-auto">
            @forelse ($this->notifications as $notification)
                <div wire:key="notification-{{ $notification->id }}"
                    wire:click="goToNotification('{{ $notification->id }}')"
                    class="border-b border-slate-100 px-4 py-3 hover:bg-slate-50 transition-colors cursor-pointer {{ is_null($notification->read_at) ? 'bg-[var(--theme-primary)]/5' : '' }}">
                    <div class="flex items-start justify-between">
                        <div class="flex-1">
                            {{-- Icône notification --}}
                            <div class="flex items-center gap-2">
                                @if ($notification->type === \App\Notifications\ApplicationStatusChanged::class)
                                    <x-heroicon-s-briefcase class="h-4 w-4 text-[var(--theme-primary)]" />
                                @elseif ($notification->type === \App\Notifications\NewMessageReceived::class)
                                    <x-heroicon-s-chat-bubble-left class="h-4 w-4 text-emerald-500" />
                                @else
                                    <x-heroicon-s-bell class="h-4 w-4 text-slate-400" />
                                @endif

                                <p class="text-sm font-semibold text-slate-900">
                                    {{ $notification->data['title'] ?? __('Notification') }}
                                </p>
                            </div>

                            {{-- Message --}}
                            <p class="mt-1 text-xs text-slate-600">
                                {{ $notification->data['message'] ?? '' }}
                            </p>

                            {{-- Horodatage --}}
                            <p class="mt-1 text-xs text-slate-400">
                                {{ $notification->created_at->diffForHumans() }}
                            </p>
                        </div>

                        {{-- Bouton marquer comme lu --}}
                        @if (is_null($notification->read_at))
                            <button wire:click="markAsRead('{{ $notification->id }}')" type="button"
                                class="ml-2 text-slate-400 hover:text-[var(--theme-primary)] transition-colors"
                                title="{{ __('Marquer comme lu') }}">
                                <x-heroicon-o-check-circle class="h-5 w-5" />
                            </button>
                        @endif
                    </div>
                </div>
            @empty
                <div class="px-4 py-8 text-center">
                    <x-heroicon-o-bell-slash class="mx-auto h-8 w-8 text-slate-300" />
                    <p class="mt-2 text-sm text-slate-500">
                        {{ __('Aucune notification') }}
                    </p>
                </div>
            @endforelse
        </div>
    </div>
</div>