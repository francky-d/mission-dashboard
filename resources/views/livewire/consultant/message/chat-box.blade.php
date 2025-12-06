<div class="flex h-full flex-col bg-slate-50" wire:poll.5s>
    @if($this->receiver)
        {{-- Header --}}
        <div class="flex items-center gap-3 border-b border-slate-200 p-4 bg-white">
            <div
                class="flex h-11 w-11 flex-shrink-0 items-center justify-center rounded-full bg-gradient-to-br from-[var(--theme-primary)] to-[var(--theme-secondary)] shadow-sm">
                <span class="text-sm font-bold text-white">
                    {{ strtoupper(substr($this->receiver->name, 0, 2)) }}
                </span>
            </div>
            <div>
                <h3 class="font-semibold text-slate-900">
                    {{ $this->receiver->name }}
                </h3>
                <p class="text-sm text-slate-500">
                    {{ $this->receiver->role->label() }}
                </p>
            </div>
        </div>

        {{-- Messages --}}
        <div class="flex-1 space-y-4 overflow-y-auto p-4" id="messages-container" x-data
            x-init="$el.scrollTop = $el.scrollHeight" x-effect="$nextTick(() => $el.scrollTop = $el.scrollHeight)">
            @forelse($this->chatMessages as $chatMessage)
                <div class="flex {{ $chatMessage->sender_id === auth()->id() ? 'justify-end' : 'justify-start' }}">
                    <div class="max-w-xs rounded-2xl px-4 py-3 lg:max-w-md shadow-sm {{ $chatMessage->sender_id === auth()->id()
                    ? 'bg-[var(--theme-primary)] text-white'
                    : 'bg-white text-slate-900 border border-slate-200' }}">
                        <p class="text-sm leading-relaxed">{{ $chatMessage->message }}</p>
                        <p
                            class="mt-1.5 text-xs {{ $chatMessage->sender_id === auth()->id() ? 'text-white/70' : 'text-slate-400' }}">
                            {{ $chatMessage->created_at->format('H:i') }}
                        </p>
                    </div>
                </div>
            @empty
                <div class="flex h-full items-center justify-center">
                    <div class="text-center">
                        <x-heroicon-o-chat-bubble-oval-left class="mx-auto h-12 w-12 text-slate-300" />
                        <p class="mt-4 text-sm font-medium text-slate-600">
                            {{ __('Aucun message dans cette conversation.') }}
                        </p>
                        <p class="mt-1 text-sm text-slate-400">
                            {{ __('Envoyez le premier message !') }}
                        </p>
                    </div>
                </div>
            @endforelse
        </div>

        {{-- Input --}}
        <div class="border-t border-slate-200 p-4 bg-white">
            <form wire:submit="sendMessage" class="flex gap-3">
                <input type="text" wire:model="newMessage" placeholder="{{ __('Écrivez votre message...') }}"
                    class="flex-1 rounded-xl border-slate-300 shadow-sm focus:border-[var(--theme-primary)] focus:ring-[var(--theme-primary)] text-slate-900 placeholder-slate-400"
                    autocomplete="off" />
                <button type="submit" class="btn-primary rounded-xl disabled:opacity-50" wire:loading.attr="disabled">
                    <x-heroicon-o-paper-airplane class="h-5 w-5" />
                </button>
            </form>
            @error('newMessage')
                <p class="mt-2 text-sm text-red-600">{{ $message }}</p>
            @enderror
        </div>
    @else
        {{-- Aucune conversation sélectionnée --}}
        <div class="flex h-full items-center justify-center">
            <div class="text-center">
                <x-heroicon-o-chat-bubble-left-right class="mx-auto h-16 w-16 text-slate-300" />
                <h3 class="mt-4 text-lg font-semibold text-slate-900">
                    {{ __('Sélectionnez une conversation') }}
                </h3>
                <p class="mt-2 text-sm text-slate-500">
                    {{ __('Choisissez une conversation dans la liste pour voir les messages.') }}
                </p>
            </div>
        </div>
    @endif
</div>