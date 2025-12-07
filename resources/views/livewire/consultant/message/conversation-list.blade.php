<div class="flex h-full flex-col">
    <div class="border-b border-slate-200 p-4 bg-slate-50">
        <h2 class="text-lg font-bold text-slate-900">
            {{ __('Conversations') }}
        </h2>
    </div>

    <div class="flex-1 overflow-y-auto">
        @forelse($conversations as $conversation)
            <button type="button" wire:click="selectConversation({{ $conversation->user->id }})"
                class="flex w-full items-center gap-3 border-b border-slate-100 p-4 text-left transition hover:bg-slate-50 {{ $selectedUserId === $conversation->user->id ? 'bg-[var(--theme-primary)]/5 border-l-4 border-l-[var(--theme-primary)]' : '' }}">
                <div class="flex h-11 w-11 flex-shrink-0 items-center justify-center rounded-full shadow-sm"
                    style="background-color: var(--theme-primary);">
                    <span class="text-sm font-bold text-white">
                        {{ strtoupper(substr($conversation->user->name, 0, 2)) }}
                    </span>
                </div>
                <div class="min-w-0 flex-1">
                    <div class="flex items-center justify-between">
                        <p class="truncate text-sm font-semibold text-slate-900">
                            {{ $conversation->user->name }}
                        </p>
                        @if($conversation->lastMessage)
                            <p class="text-xs text-slate-400">
                                {{ $conversation->lastMessage->created_at->diffForHumans(short: true) }}
                            </p>
                        @endif
                    </div>
                    <div class="flex items-center justify-between mt-1">
                        @if($conversation->lastMessage)
                            <p class="truncate text-sm text-slate-500">
                                {{ Str::limit($conversation->lastMessage->message, 30) }}
                            </p>
                        @else
                            <p class="text-sm text-slate-400">
                                {{ __('Aucun message') }}
                            </p>
                        @endif
                        @if($conversation->unreadCount > 0)
                            <span
                                class="ml-2 inline-flex h-5 min-w-5 items-center justify-center rounded-full bg-[var(--theme-primary)] px-1.5 text-xs font-bold text-white shadow-sm">
                                {{ $conversation->unreadCount }}
                            </span>
                        @endif
                    </div>
                </div>
            </button>
        @empty
            <div class="p-8 text-center">
                <x-heroicon-o-chat-bubble-left-right class="mx-auto h-12 w-12 text-slate-300" />
                <p class="mt-4 text-sm font-medium text-slate-600">
                    {{ __('Aucune conversation pour le moment.') }}
                </p>
                <p class="mt-1 text-sm text-slate-400">
                    {{ __('Les messages échangés avec les commerciaux apparaîtront ici.') }}
                </p>
            </div>
        @endforelse
    </div>
</div>