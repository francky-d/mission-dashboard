@php use Illuminate\Support\Facades\Storage; @endphp
<div class="flex h-[calc(100vh-12rem)] overflow-hidden rounded-xl bg-white border border-slate-200 shadow-lg"
    wire:poll.5s>
    {{-- Liste des conversations --}}
    <div class="w-1/3 flex-shrink-0 border-r border-slate-200">
        <div class="flex h-full flex-col">
            <div class="border-b border-slate-200 p-4 bg-slate-50">
                <h2 class="text-lg font-bold text-slate-900">
                    {{ __('Consultants') }}
                </h2>
            </div>

            <div class="flex-1 overflow-y-auto">
                @forelse($conversations as $conversation)
                    <button type="button" wire:click="selectConversation({{ $conversation->user->id }})"
                        class="flex w-full items-center gap-3 border-b border-slate-100 p-4 text-left transition hover:bg-slate-50 {{ $consultant === $conversation->user->id ? 'bg-[var(--theme-primary)]/5 border-l-4 border-l-[var(--theme-primary)]' : '' }}">
                        <div
                            class="flex h-11 w-11 flex-shrink-0 items-center justify-center rounded-full bg-gradient-to-br from-[var(--theme-primary)] to-[var(--theme-secondary)] shadow-sm">
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
                                @elseif($conversation->application)
                                    <p class="truncate text-sm text-slate-400 italic">
                                        {{ __('Candidat sur :mission', ['mission' => Str::limit($conversation->application->mission->title, 20)]) }}
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
                            {{ __('Aucun consultant à contacter.') }}
                        </p>
                        <p class="mt-1 text-sm text-slate-400">
                            {{ __('Les consultants qui postulent à vos missions apparaîtront ici.') }}
                        </p>
                    </div>
                @endforelse
            </div>
        </div>
    </div>

    {{-- Zone de chat --}}
    <div class="flex flex-1 flex-col bg-slate-50">
        @if($this->receiver)
            {{-- En-tête --}}
            <div class="flex items-center justify-between border-b border-slate-200 p-4 bg-white">
                <div class="flex items-center gap-3">
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
                <button type="button" wire:click="openProfileModal"
                    class="link-themed inline-flex items-center gap-1.5 text-sm font-medium hover:underline">
                    <x-heroicon-o-user class="h-4 w-4" />
                    {{ __('Voir le profil') }}
                </button>
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
                                {{ __('Envoyez le premier message au consultant !') }}
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
                        {{ __('Sélectionnez un consultant') }}
                    </h3>
                    <p class="mt-2 text-sm text-slate-500">
                        {{ __('Choisissez un consultant dans la liste pour démarrer une conversation.') }}
                    </p>
                </div>
            </div>
        @endif
    </div>

    {{-- Modal Profil --}}
    @if($showProfileModal && $this->receiver)
        @php
            $user = $this->receiver->load(['consultantProfile.tags']);
            $applications = $this->consultantApplications;
        @endphp
        <div class="fixed inset-0 z-50 overflow-y-auto" aria-labelledby="modal-title" role="dialog" aria-modal="true">
            <div class="flex min-h-screen items-end justify-center px-4 pt-4 pb-20 text-center sm:block sm:p-0">
                {{-- Overlay --}}
                <div class="fixed inset-0 bg-slate-900/60 backdrop-blur-sm transition-opacity"
                    wire:click="closeProfileModal"></div>

                <span class="hidden sm:inline-block sm:align-middle sm:h-screen" aria-hidden="true">&#8203;</span>

                {{-- Contenu modal --}}
                <div
                    class="inline-block transform overflow-hidden rounded-2xl bg-white text-left align-bottom shadow-2xl transition-all sm:my-8 sm:w-full sm:max-w-2xl sm:align-middle">
                    {{-- Header --}}
                    <div class="bg-gradient-to-r from-[var(--theme-primary)] to-[var(--theme-secondary)] px-6 py-5">
                        <div class="flex items-center justify-between">
                            <h3 class="text-xl font-bold text-white" id="modal-title">
                                {{ __('Profil du consultant') }}
                            </h3>
                            <button type="button" wire:click="closeProfileModal"
                                class="text-white/80 hover:text-white transition-colors">
                                <x-heroicon-o-x-mark class="h-6 w-6" />
                            </button>
                        </div>
                    </div>

                    {{-- Body --}}
                    <div class="max-h-[70vh] overflow-y-auto px-6 py-6">
                        <div class="space-y-6">
                            {{-- Profil Header --}}
                            <div class="flex items-start gap-4">
                                <div
                                    class="h-16 w-16 rounded-2xl bg-gradient-to-br from-[var(--theme-primary)] to-[var(--theme-secondary)] flex items-center justify-center flex-shrink-0 shadow-lg ring-4 ring-slate-100">
                                    <span class="text-2xl font-bold text-white">
                                        {{ strtoupper(substr($user->name, 0, 1)) }}
                                    </span>
                                </div>
                                <div class="flex-1">
                                    <h4 class="text-xl font-bold text-slate-900">
                                        {{ $user->name }}
                                    </h4>
                                    <p class="text-sm text-slate-500">
                                        {{ $user->email }}
                                    </p>
                                    @if($user->consultantProfile && $user->consultantProfile->experience_years)
                                        <p class="mt-2 flex items-center text-sm text-slate-600">
                                            <x-heroicon-m-briefcase class="w-4 h-4 mr-1.5 text-slate-400" />
                                            {{ $user->consultantProfile->experience_years }} {{ __('années d\'expérience') }}
                                        </p>
                                    @endif
                                    @if($user->consultantProfile && $user->consultantProfile->cv_url)
                                        <a href="{{ Storage::url($user->consultantProfile->cv_url) }}" target="_blank"
                                            class="mt-2 inline-flex items-center text-sm link-themed hover:underline">
                                            <x-heroicon-m-document-arrow-down class="w-4 h-4 mr-1.5" />
                                            {{ __('Télécharger le CV') }}
                                        </a>
                                    @endif
                                </div>
                            </div>

                            {{-- Compétences --}}
                            @if($user->consultantProfile && $user->consultantProfile->tags->isNotEmpty())
                                <div class="pt-4 border-t border-slate-200">
                                    <h5 class="text-sm font-semibold text-slate-900 mb-3">
                                        {{ __('Compétences') }}
                                    </h5>
                                    <div class="flex flex-wrap gap-2">
                                        @foreach($user->consultantProfile->tags as $tag)
                                            <span class="tag-pill">
                                                {{ $tag->name }}
                                            </span>
                                        @endforeach
                                    </div>
                                </div>
                            @endif

                            {{-- Bio --}}
                            @if($user->consultantProfile && $user->consultantProfile->bio)
                                <div class="pt-4 border-t border-slate-200">
                                    <h5 class="text-sm font-semibold text-slate-900 mb-3">
                                        {{ __('Biographie') }}
                                    </h5>
                                    <div
                                        class="prose prose-slate prose-sm max-w-none text-slate-600 bg-slate-50 p-4 rounded-xl">
                                        {!! nl2br(e($user->consultantProfile->bio)) !!}
                                    </div>
                                </div>
                            @endif

                            {{-- Candidatures --}}
                            @if($applications->isNotEmpty())
                                <div class="pt-4 border-t border-slate-200">
                                    <h5 class="text-sm font-semibold text-slate-900 mb-3">
                                        {{ __('Candidatures sur mes missions') }}
                                    </h5>
                                    <div class="space-y-2">
                                        @foreach($applications as $application)
                                            <div
                                                class="flex items-center justify-between rounded-xl border border-slate-200 p-4 bg-white shadow-sm">
                                                <div>
                                                    <p class="text-sm font-semibold text-slate-900">
                                                        {{ $application->mission->title }}
                                                    </p>
                                                    <p class="text-xs text-slate-500 mt-1">
                                                        {{ __('Postulé le') }}
                                                        {{ $application->created_at->translatedFormat('d M Y') }}
                                                    </p>
                                                </div>
                                                <span @class([
                                                    'inline-flex items-center rounded-full px-3 py-1 text-xs font-semibold',
                                                    'bg-amber-100 text-amber-800' => $application->status === \App\Enums\ApplicationStatus::Pending,
                                                    'bg-sky-100 text-sky-800' => $application->status === \App\Enums\ApplicationStatus::Viewed,
                                                    'bg-emerald-100 text-emerald-800' => $application->status === \App\Enums\ApplicationStatus::Accepted,
                                                    'bg-rose-100 text-rose-800' => $application->status === \App\Enums\ApplicationStatus::Rejected,
                                                ])>
                                                    {{ $application->status->label() }}
                                                </span>
                                            </div>
                                        @endforeach
                                    </div>
                                </div>
                            @endif
                        </div>
                    </div>

                    {{-- Footer --}}
                    <div class="flex justify-end gap-3 border-t border-slate-200 px-6 py-4 bg-slate-50">
                        <button type="button" wire:click="closeProfileModal"
                            class="inline-flex items-center rounded-lg bg-white border border-slate-300 px-4 py-2 text-sm font-medium text-slate-700 hover:bg-slate-50 transition-colors">
                            {{ __('Fermer') }}
                        </button>
                        <a href="{{ route('commercial.consultants.show', $this->receiver) }}" wire:navigate
                            class="btn-primary">
                            {{ __('Voir le profil complet') }}
                        </a>
                    </div>
                </div>
            </div>
        </div>
    @endif
</div>