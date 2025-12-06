<?php

use App\Livewire\Actions\Logout;
use App\Models\Message;
use Livewire\Volt\Component;

new class extends Component
{
    /**
     * Log the current user out of the application.
     */
    public function logout(Logout $logout): void
    {
        $logout();

        $this->redirect('/', navigate: true);
    }

    /**
     * Get unread messages count for the current user.
     */
    public function getUnreadMessagesCountProperty(): int
    {
        if (! auth()->check()) {
            return 0;
        }

        return Message::where('receiver_id', auth()->id())
            ->whereNull('read_at')
            ->count();
    }
}; ?>

<nav x-data="{ open: false }" class="bg-white border-b border-gray-200 sticky top-0 z-50">
    <!-- Primary Navigation Menu -->
    <div class="max-w-7xl mx-auto px-4 sm:px-6 lg:px-8">
        <div class="flex justify-between h-16">
            <div class="flex">
                <!-- Logo -->
                <div class="shrink-0 flex items-center">
                    <a href="{{ route('dashboard') }}" wire:navigate class="flex items-center gap-3">
                        @if($siteSettings->logo_url)
                            <img src="{{ $siteSettings->logo_url }}" alt="{{ $siteSettings->site_name }}"
                                class="h-8 w-auto">
                        @else
                            <div class="avatar" style="width: 36px; height: 36px;">
                                <span class="text-sm">{{ substr($siteSettings->site_name ?? 'M', 0, 1) }}</span>
                            </div>
                        @endif
                        <span
                            class="hidden sm:block text-lg font-semibold text-gray-900">{{ $siteSettings->site_name ?? 'Mission Dashboard' }}</span>
                    </a>
                </div>

                <!-- Navigation Links -->
                <div class="hidden space-x-1 sm:-my-px sm:ms-8 sm:flex">
                    @auth
                        @if(auth()->user()->role === \App\Enums\UserRole::Commercial)
                            <a href="{{ route('commercial.dashboard') }}" wire:navigate
                                class="inline-flex items-center px-4 py-2 text-sm font-medium transition-colors duration-200 border-b-2 {{ request()->routeIs('commercial.dashboard') ? 'nav-active border-current' : 'border-transparent text-gray-600 hover:text-gray-900' }}">
                                <x-heroicon-o-home class="w-4 h-4 mr-2" />
                                {{ __('Dashboard') }}
                            </a>
                            <a href="{{ route('commercial.missions.index') }}" wire:navigate
                                class="inline-flex items-center px-4 py-2 text-sm font-medium transition-colors duration-200 border-b-2 {{ request()->routeIs('commercial.missions.*') ? 'nav-active border-current' : 'border-transparent text-gray-600 hover:text-gray-900' }}">
                                <x-heroicon-o-briefcase class="w-4 h-4 mr-2" />
                                {{ __('Missions') }}
                            </a>
                            <a href="{{ route('commercial.messages.index') }}" wire:navigate
                                class="inline-flex items-center px-4 py-2 text-sm font-medium transition-colors duration-200 border-b-2 {{ request()->routeIs('commercial.messages.*') ? 'nav-active border-current' : 'border-transparent text-gray-600 hover:text-gray-900' }}">
                                <x-heroicon-o-chat-bubble-left-right class="w-4 h-4 mr-2" />
                                {{ __('Messages') }}
                                @if($this->unreadMessagesCount > 0)
                                    <span
                                        class="ml-2 flex h-5 min-w-5 items-center justify-center rounded-full bg-red-500 text-[10px] font-bold text-white">
                                        {{ $this->unreadMessagesCount > 9 ? '9+' : $this->unreadMessagesCount }}
                                    </span>
                                @endif
                            </a>
                        @else
                            <a href="{{ route('consultant.dashboard') }}" wire:navigate
                                class="inline-flex items-center px-4 py-2 text-sm font-medium transition-colors duration-200 border-b-2 {{ request()->routeIs('consultant.dashboard') ? 'nav-active border-current' : 'border-transparent text-gray-600 hover:text-gray-900' }}">
                                <x-heroicon-o-home class="w-4 h-4 mr-2" />
                                {{ __('Dashboard') }}
                            </a>
                            <a href="{{ route('consultant.missions.index') }}" wire:navigate
                                class="inline-flex items-center px-4 py-2 text-sm font-medium transition-colors duration-200 border-b-2 {{ request()->routeIs('consultant.missions.*') ? 'nav-active border-current' : 'border-transparent text-gray-600 hover:text-gray-900' }}">
                                <x-heroicon-o-magnifying-glass class="w-4 h-4 mr-2" />
                                {{ __('Missions') }}
                            </a>
                            <a href="{{ route('consultant.applications.index') }}" wire:navigate
                                class="inline-flex items-center px-4 py-2 text-sm font-medium transition-colors duration-200 border-b-2 {{ request()->routeIs('consultant.applications.*') ? 'nav-active border-current' : 'border-transparent text-gray-600 hover:text-gray-900' }}">
                                <x-heroicon-o-document-text class="w-4 h-4 mr-2" />
                                {{ __('Candidatures') }}
                            </a>
                            <a href="{{ route('consultant.messages.index') }}" wire:navigate
                                class="inline-flex items-center px-4 py-2 text-sm font-medium transition-colors duration-200 border-b-2 {{ request()->routeIs('consultant.messages.*') ? 'nav-active border-current' : 'border-transparent text-gray-600 hover:text-gray-900' }}">
                                <x-heroicon-o-chat-bubble-left-right class="w-4 h-4 mr-2" />
                                {{ __('Messages') }}
                                @if($this->unreadMessagesCount > 0)
                                    <span
                                        class="ml-2 flex h-5 min-w-5 items-center justify-center rounded-full bg-red-500 text-[10px] font-bold text-white">
                                        {{ $this->unreadMessagesCount > 9 ? '9+' : $this->unreadMessagesCount }}
                                    </span>
                                @endif
                            </a>
                        @endif
                    @else
                        <a href="{{ route('dashboard') }}" wire:navigate
                            class="inline-flex items-center px-4 py-2 text-sm font-medium transition-colors duration-200 border-b-2 {{ request()->routeIs('dashboard') ? 'nav-active border-current' : 'border-transparent text-gray-600 hover:text-gray-900' }}">
                            {{ __('Dashboard') }}
                        </a>
                    @endauth
                </div>
            </div>

            <!-- Settings Dropdown -->
            <div class="hidden sm:flex sm:items-center sm:ms-6 gap-3">
                {{-- Notifications --}}
                @auth
                    @if(auth()->user()->role === \App\Enums\UserRole::Commercial)
                        <livewire:commercial.notification.notification-dropdown />
                    @else
                        <livewire:consultant.notification.notification-dropdown />
                    @endif
                @endauth

                <x-dropdown align="right" width="48">
                    <x-slot name="trigger">
                        <button
                            class="inline-flex items-center gap-2 px-3 py-2 text-sm font-medium text-gray-700 bg-gray-50 rounded-lg hover:bg-gray-100 focus:outline-none transition-colors duration-200">
                            <div class="avatar" style="width: 28px; height: 28px; font-size: 11px;">
                                {{ strtoupper(substr(auth()->user()->name, 0, 1)) }}
                            </div>
                            <span x-data="{{ json_encode(['name' => auth()->user()->name]) }}" x-text="name"
                                x-on:profile-updated.window="name = $event.detail.name" class="hidden md:block"></span>
                            <x-heroicon-s-chevron-down class="w-4 h-4 text-gray-500" />
                        </button>
                    </x-slot>

                    <x-slot name="content">
                        <div class="px-4 py-3 border-b border-gray-100">
                            <p class="text-sm font-medium text-gray-900"
                                x-data="{{ json_encode(['name' => auth()->user()->name]) }}" x-text="name"
                                x-on:profile-updated.window="name = $event.detail.name"></p>
                            <p class="text-xs text-gray-500 mt-0.5">{{ auth()->user()->email }}</p>
                        </div>

                        @if(auth()->user()->role === \App\Enums\UserRole::Consultant)
                            <x-dropdown-link :href="route('consultant.profile')" wire:navigate
                                class="flex items-center gap-2">
                                <x-heroicon-o-user-circle class="w-4 h-4" />
                                {{ __('Mon profil') }}
                            </x-dropdown-link>
                        @else
                            <x-dropdown-link :href="route('profile')" wire:navigate class="flex items-center gap-2">
                                <x-heroicon-o-user-circle class="w-4 h-4" />
                                {{ __('Profile') }}
                            </x-dropdown-link>
                        @endif

                        <!-- Authentication -->
                        <button wire:click="logout" class="w-full text-start">
                            <x-dropdown-link class="flex items-center gap-2 text-red-600 hover:bg-red-50">
                                <x-heroicon-o-arrow-right-on-rectangle class="w-4 h-4" />
                                {{ __('Déconnexion') }}
                            </x-dropdown-link>
                        </button>
                    </x-slot>
                </x-dropdown>
            </div>

            <!-- Hamburger -->
            <div class="-me-2 flex items-center sm:hidden">
                <button @click="open = ! open"
                    class="inline-flex items-center justify-center p-2 rounded-lg text-gray-500 hover:text-gray-700 hover:bg-gray-100 focus:outline-none transition duration-200">
                    <x-heroicon-o-bars-3 x-show="!open" class="h-6 w-6" />
                    <x-heroicon-o-x-mark x-show="open" x-cloak class="h-6 w-6" />
                </button>
            </div>
        </div>
    </div>

    <!-- Responsive Navigation Menu -->
    <div :class="{'block': open, 'hidden': ! open}" class="hidden sm:hidden border-t border-gray-100">
        <div class="pt-2 pb-3 space-y-1 px-4">
            @auth
                @if(auth()->user()->role === \App\Enums\UserRole::Commercial)
                    <a href="{{ route('commercial.dashboard') }}" wire:navigate
                        class="flex items-center gap-3 px-3 py-2 rounded-lg text-base font-medium {{ request()->routeIs('commercial.dashboard') ? 'bg-gray-100 text-gray-900' : 'text-gray-600 hover:bg-gray-50' }}">
                        <x-heroicon-o-home class="w-5 h-5" />
                        {{ __('Dashboard') }}
                    </a>
                    <a href="{{ route('commercial.missions.index') }}" wire:navigate
                        class="flex items-center gap-3 px-3 py-2 rounded-lg text-base font-medium {{ request()->routeIs('commercial.missions.*') ? 'bg-gray-100 text-gray-900' : 'text-gray-600 hover:bg-gray-50' }}">
                        <x-heroicon-o-briefcase class="w-5 h-5" />
                        {{ __('Missions') }}
                    </a>
                    <a href="{{ route('commercial.messages.index') }}" wire:navigate
                        class="relative flex items-center gap-3 px-3 py-2 rounded-lg text-base font-medium {{ request()->routeIs('commercial.messages.*') ? 'bg-gray-100 text-gray-900' : 'text-gray-600 hover:bg-gray-50' }}">
                        <x-heroicon-o-chat-bubble-left-right class="w-5 h-5" />
                        {{ __('Messages') }}
                        @if($this->unreadMessagesCount > 0)
                            <span
                                class="flex h-5 min-w-5 items-center justify-center rounded-full bg-red-500 text-[10px] font-bold text-white shadow-sm leading-none">
                                {{ $this->unreadMessagesCount > 9 ? '9+' : $this->unreadMessagesCount }}
                            </span>
                        @endif
                    </a>
                @else
                    <a href="{{ route('consultant.dashboard') }}" wire:navigate
                        class="flex items-center gap-3 px-3 py-2 rounded-lg text-base font-medium {{ request()->routeIs('consultant.dashboard') ? 'bg-gray-100 text-gray-900' : 'text-gray-600 hover:bg-gray-50' }}">
                        <x-heroicon-o-home class="w-5 h-5" />
                        {{ __('Dashboard') }}
                    </a>
                    <a href="{{ route('consultant.missions.index') }}" wire:navigate
                        class="flex items-center gap-3 px-3 py-2 rounded-lg text-base font-medium {{ request()->routeIs('consultant.missions.*') ? 'bg-gray-100 text-gray-900' : 'text-gray-600 hover:bg-gray-50' }}">
                        <x-heroicon-o-magnifying-glass class="w-5 h-5" />
                        {{ __('Missions') }}
                    </a>
                    <a href="{{ route('consultant.applications.index') }}" wire:navigate
                        class="flex items-center gap-3 px-3 py-2 rounded-lg text-base font-medium {{ request()->routeIs('consultant.applications.*') ? 'bg-gray-100 text-gray-900' : 'text-gray-600 hover:bg-gray-50' }}">
                        <x-heroicon-o-document-text class="w-5 h-5" />
                        {{ __('Candidatures') }}
                    </a>
                    <a href="{{ route('consultant.messages.index') }}" wire:navigate
                        class="relative flex items-center gap-3 px-3 py-2 rounded-lg text-base font-medium {{ request()->routeIs('consultant.messages.*') ? 'bg-gray-100 text-gray-900' : 'text-gray-600 hover:bg-gray-50' }}">
                        <x-heroicon-o-chat-bubble-left-right class="w-5 h-5" />
                        {{ __('Messages') }}
                        @if($this->unreadMessagesCount > 0)
                            <span
                                class="flex h-5 min-w-5 items-center justify-center rounded-full bg-red-500 text-[10px] font-bold text-white shadow-sm leading-none">
                                {{ $this->unreadMessagesCount > 9 ? '9+' : $this->unreadMessagesCount }}
                            </span>
                        @endif
                    </a>
                @endif
            @else
                <a href="{{ route('dashboard') }}" wire:navigate
                    class="flex items-center gap-3 px-3 py-2 rounded-lg text-base font-medium {{ request()->routeIs('dashboard') ? 'bg-gray-100 text-gray-900' : 'text-gray-600 hover:bg-gray-50' }}">
                    {{ __('Dashboard') }}
                </a>
            @endauth
        </div>

        <!-- Responsive Settings Options -->
        <div class="pt-4 pb-4 border-t border-gray-200 px-4">
            <div class="flex items-center gap-3 mb-4">
                <div class="avatar">
                    {{ strtoupper(substr(auth()->user()->name, 0, 1)) }}
                </div>
                <div>
                    <div class="font-medium text-gray-900" x-data="{{ json_encode(['name' => auth()->user()->name]) }}"
                        x-text="name" x-on:profile-updated.window="name = $event.detail.name"></div>
                    <div class="text-sm text-gray-500">{{ auth()->user()->email }}</div>
                </div>
            </div>

            <div class="space-y-1">
                @if(auth()->user()->role === \App\Enums\UserRole::Consultant)
                    <a href="{{ route('consultant.profile') }}" wire:navigate
                        class="flex items-center gap-3 px-3 py-2 rounded-lg text-base font-medium text-gray-600 hover:bg-gray-50">
                        <x-heroicon-o-user-circle class="w-5 h-5" />
                        {{ __('Mon profil') }}
                    </a>
                @else
                    <a href="{{ route('profile') }}" wire:navigate
                        class="flex items-center gap-3 px-3 py-2 rounded-lg text-base font-medium text-gray-600 hover:bg-gray-50">
                        <x-heroicon-o-user-circle class="w-5 h-5" />
                        {{ __('Profile') }}
                    </a>
                @endif

                <!-- Authentication -->
                <button wire:click="logout"
                    class="w-full flex items-center gap-3 px-3 py-2 rounded-lg text-base font-medium text-red-600 hover:bg-red-50">
                    <x-heroicon-o-arrow-right-on-rectangle class="w-5 h-5" />
                    {{ __('Déconnexion') }}
                </button>
            </div>
        </div>
    </div>
</nav>