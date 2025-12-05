@php use Illuminate\Support\Facades\Storage; @endphp
<div class="flex h-[calc(100vh-12rem)] overflow-hidden rounded-lg border border-gray-200 bg-white shadow-sm dark:border-gray-700 dark:bg-gray-800"
    wire:poll.5s>
    {{-- Conversation List --}}
    <div class="w-1/3 flex-shrink-0 border-r border-gray-200 dark:border-gray-700">
        <div class="flex h-full flex-col">
            <div class="border-b border-gray-200 p-4 dark:border-gray-700">
                <h2 class="text-lg font-semibold text-gray-900 dark:text-gray-100">
                    {{ __('Consultants') }}
                </h2>
            </div>

            <div class="flex-1 overflow-y-auto">
                @forelse($conversations as $conversation)
                    <button type="button" wire:click="selectConversation({{ $conversation->user->id }})"
                        class="flex w-full items-center gap-3 border-b border-gray-100 p-4 text-left transition hover:bg-gray-50 dark:border-gray-800 dark:hover:bg-gray-800/50 {{ $consultant === $conversation->user->id ? 'bg-indigo-50 dark:bg-indigo-900/20' : '' }}">
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
                                @elseif($conversation->application)
                                    <p class="truncate text-sm text-gray-400 italic dark:text-gray-500">
                                        {{ __('Candidat sur :mission', ['mission' => Str::limit($conversation->application->mission->title, 20)]) }}
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
                            {{ __('Aucun consultant à contacter.') }}
                        </p>
                        <p class="mt-1 text-sm text-gray-400 dark:text-gray-500">
                            {{ __('Les consultants qui postulent à vos missions apparaîtront ici.') }}
                        </p>
                    </div>
                @endforelse
            </div>
        </div>
    </div>

    {{-- Chat Area --}}
    <div class="flex flex-1 flex-col">
        @if($this->receiver)
            {{-- Header --}}
            <div class="flex items-center justify-between border-b border-gray-200 p-4 dark:border-gray-700">
                <div class="flex items-center gap-3">
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
                <button type="button" wire:click="openProfileModal"
                    class="inline-flex items-center gap-1 text-sm text-indigo-600 hover:text-indigo-500 dark:text-indigo-400 dark:hover:text-indigo-300">
                    <x-heroicon-o-user class="h-4 w-4" />
                    {{ __('Voir le profil') }}
                </button>
            </div>

            {{-- Messages --}}
            <div class="flex-1 space-y-4 overflow-y-auto p-4" id="messages-container" x-data
                x-init="$el.scrollTop = $el.scrollHeight" x-effect="$nextTick(() => $el.scrollTop = $el.scrollHeight)">
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
                                {{ __('Envoyez le premier message au consultant !') }}
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
                        {{ __('Sélectionnez un consultant') }}
                    </h3>
                    <p class="mt-2 text-sm text-gray-500 dark:text-gray-400">
                        {{ __('Choisissez un consultant dans la liste pour démarrer une conversation.') }}
                    </p>
                </div>
            </div>
        @endif
    </div>

    {{-- Profile Modal --}}
    @if($showProfileModal && $this->receiver)
        @php
            $user = $this->receiver->load(['consultantProfile.tags']);
            $applications = $this->consultantApplications;
        @endphp
        <div class="fixed inset-0 z-50 overflow-y-auto" aria-labelledby="modal-title" role="dialog" aria-modal="true">
            <div class="flex min-h-screen items-end justify-center px-4 pt-4 pb-20 text-center sm:block sm:p-0">
                {{-- Background overlay --}}
                <div class="fixed inset-0 bg-gray-500 bg-opacity-75 transition-opacity" wire:click="closeProfileModal"></div>

                {{-- Modal panel --}}
                <div class="inline-block transform overflow-hidden rounded-lg bg-white text-left align-bottom shadow-xl transition-all sm:my-8 sm:w-full sm:max-w-2xl sm:align-middle dark:bg-gray-800">
                    {{-- Modal Header --}}
                    <div class="flex items-center justify-between border-b border-gray-200 px-6 py-4 dark:border-gray-700">
                        <h3 class="text-lg font-semibold text-gray-900 dark:text-white" id="modal-title">
                            {{ __('Profil du consultant') }}
                        </h3>
                        <button type="button" wire:click="closeProfileModal"
                            class="rounded-md text-gray-400 hover:text-gray-500 focus:outline-none">
                            <x-heroicon-o-x-mark class="h-6 w-6" />
                        </button>
                    </div>

                    {{-- Modal Body --}}
                    <div class="max-h-[70vh] overflow-y-auto px-6 py-4">
                        <div class="space-y-6">
                            {{-- Profile Header --}}
                            <div class="flex items-start gap-4">
                                <div class="h-16 w-16 rounded-full bg-indigo-100 dark:bg-indigo-900/30 flex items-center justify-center flex-shrink-0">
                                    <span class="text-2xl font-bold text-indigo-600 dark:text-indigo-400">
                                        {{ strtoupper(substr($user->name, 0, 1)) }}
                                    </span>
                                </div>
                                <div class="flex-1">
                                    <h4 class="text-xl font-bold text-gray-900 dark:text-white">
                                        {{ $user->name }}
                                    </h4>
                                    <p class="text-sm text-gray-500 dark:text-gray-400">
                                        {{ $user->email }}
                                    </p>
                                    @if($user->consultantProfile && $user->consultantProfile->experience_years)
                                        <p class="mt-2 flex items-center text-sm text-gray-500 dark:text-gray-400">
                                            <x-heroicon-m-briefcase class="w-4 h-4 mr-1" />
                                            {{ $user->consultantProfile->experience_years }} {{ __('années d\'expérience') }}
                                        </p>
                                    @endif
                                    @if($user->consultantProfile && $user->consultantProfile->cv_url)
                                        <a href="{{ Storage::url($user->consultantProfile->cv_url) }}" target="_blank"
                                            class="mt-2 inline-flex items-center text-sm text-indigo-600 hover:text-indigo-500 dark:text-indigo-400">
                                            <x-heroicon-m-document-arrow-down class="w-4 h-4 mr-1" />
                                            {{ __('Télécharger le CV') }}
                                        </a>
                                    @endif
                                </div>
                            </div>

                            {{-- Skills / Tags --}}
                            @if($user->consultantProfile && $user->consultantProfile->tags->isNotEmpty())
                                <div>
                                    <h5 class="text-sm font-semibold text-gray-900 dark:text-white mb-2">
                                        {{ __('Compétences') }}
                                    </h5>
                                    <div class="flex flex-wrap gap-2">
                                        @foreach($user->consultantProfile->tags as $tag)
                                            <span class="inline-flex items-center rounded-full bg-indigo-50 px-2.5 py-0.5 text-xs font-medium text-indigo-700 border border-indigo-200">
                                                {{ $tag->name }}
                                            </span>
                                        @endforeach
                                    </div>
                                </div>
                            @endif

                            {{-- Bio --}}
                            @if($user->consultantProfile && $user->consultantProfile->bio)
                                <div>
                                    <h5 class="text-sm font-semibold text-gray-900 dark:text-white mb-2">
                                        {{ __('Biographie') }}
                                    </h5>
                                    <div class="prose prose-sm dark:prose-invert max-w-none text-gray-600 dark:text-gray-300">
                                        {!! nl2br(e($user->consultantProfile->bio)) !!}
                                    </div>
                                </div>
                            @endif

                            {{-- Applications --}}
                            @if($applications->isNotEmpty())
                                <div>
                                    <h5 class="text-sm font-semibold text-gray-900 dark:text-white mb-2">
                                        {{ __('Candidatures sur mes missions') }}
                                    </h5>
                                    <div class="space-y-2">
                                        @foreach($applications as $application)
                                            <div class="flex items-center justify-between rounded-lg border border-gray-200 dark:border-gray-700 p-3">
                                                <div>
                                                    <p class="text-sm font-medium text-gray-900 dark:text-white">
                                                        {{ $application->mission->title }}
                                                    </p>
                                                    <p class="text-xs text-gray-500 dark:text-gray-400">
                                                        {{ __('Postulé le') }} {{ $application->created_at->format('d/m/Y') }}
                                                    </p>
                                                </div>
                                                <span @class([
                                                    'inline-flex items-center rounded-full px-2.5 py-0.5 text-xs font-medium',
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

                    {{-- Modal Footer --}}
                    <div class="flex justify-end gap-3 border-t border-gray-200 px-6 py-4 dark:border-gray-700">
                        <button type="button" wire:click="closeProfileModal"
                            class="inline-flex items-center rounded-lg bg-gray-100 px-4 py-2 text-sm font-medium text-gray-700 hover:bg-gray-200 transition-colors">
                            {{ __('Fermer') }}
                        </button>
                        <a href="{{ route('commercial.consultants.show', $this->receiver) }}" wire:navigate
                            class="inline-flex items-center rounded-lg bg-indigo-600 px-4 py-2 text-sm font-semibold text-white hover:bg-indigo-500 transition-colors">
                            {{ __('Voir le profil complet') }}
                        </a>
                    </div>
                </div>
            </div>
        </div>
    @endif
</div>