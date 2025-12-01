<?php

use App\Enums\UserRole;
use App\Models\User;
use App\Rules\AllowedEmailDomain;
use Illuminate\Auth\Events\Registered;
use Illuminate\Support\Facades\Auth;
use Illuminate\Support\Facades\Hash;
use Illuminate\Validation\Rules;
use Livewire\Attributes\Layout;
use Livewire\Volt\Component;

new #[Layout('layouts.guest', ['theme' => 'commercial'])] class extends Component
{
    public string $name = '';

    public string $email = '';

    public string $password = '';

    public string $password_confirmation = '';

    /**
     * Handle an incoming registration request.
     */
    public function register(): void
    {
        $validated = $this->validate([
            'name' => ['required', 'string', 'max:255'],
            'email' => ['required', 'string', 'lowercase', 'email', 'max:255', 'unique:'.User::class, new AllowedEmailDomain],
            'password' => ['required', 'string', 'confirmed', Rules\Password::defaults()],
        ]);

        $validated['password'] = Hash::make($validated['password']);
        $validated['role'] = UserRole::Commercial;

        event(new Registered($user = User::create($validated)));

        Auth::login($user);

        $this->redirect(route('dashboard', absolute: false), navigate: true);
    }
}; ?>

<div>
    <div class="mb-6">
        <h2 class="text-2xl font-bold text-gray-900">Inscription Commercial</h2>
        <p class="mt-1 text-sm text-gray-600">
            Créez votre compte commercial pour gérer votre portefeuille
        </p>
    </div>

    <form wire:submit="register">
        <!-- Name -->
        <div>
            <x-input-label for="name" :value="__('Nom complet')" />
            <x-text-input wire:model="name" id="name"
                class="block mt-1 w-full focus:ring-[var(--theme-primary)] focus:border-[var(--theme-primary)]"
                type="text" name="name" required autofocus autocomplete="name" />
            <x-input-error :messages="$errors->get('name')" class="mt-2" />
        </div>

        <!-- Email Address -->
        <div class="mt-4">
            <x-input-label for="email" :value="__('Email')" />
            <x-text-input wire:model="email" id="email"
                class="block mt-1 w-full focus:ring-[var(--theme-primary)] focus:border-[var(--theme-primary)]"
                type="email" name="email" required autocomplete="username" />
            <x-input-error :messages="$errors->get('email')" class="mt-2" />
        </div>

        <!-- Password -->
        <div class="mt-4">
            <x-input-label for="password" :value="__('Mot de passe')" />
            <x-text-input wire:model="password" id="password"
                class="block mt-1 w-full focus:ring-[var(--theme-primary)] focus:border-[var(--theme-primary)]"
                type="password" name="password" required autocomplete="new-password" />
            <x-input-error :messages="$errors->get('password')" class="mt-2" />
        </div>

        <!-- Confirm Password -->
        <div class="mt-4">
            <x-input-label for="password_confirmation" :value="__('Confirmer le mot de passe')" />
            <x-text-input wire:model="password_confirmation" id="password_confirmation"
                class="block mt-1 w-full focus:ring-[var(--theme-primary)] focus:border-[var(--theme-primary)]"
                type="password" name="password_confirmation" required autocomplete="new-password" />
            <x-input-error :messages="$errors->get('password_confirmation')" class="mt-2" />
        </div>

        <div class="mt-6">
            <button type="submit"
                class="w-full flex justify-center py-3 px-4 border border-transparent rounded-lg shadow-sm text-sm font-medium text-white transition-colors duration-200"
                style="background-color: var(--theme-primary);"
                onmouseover="this.style.backgroundColor='var(--theme-secondary)'"
                onmouseout="this.style.backgroundColor='var(--theme-primary)'">
                {{ __('Créer mon compte') }}
            </button>
        </div>

        <div class="mt-6 text-center text-sm">
            <span class="text-gray-600">Déjà inscrit ?</span>
            <a href="{{ route('commercial.login') }}" wire:navigate style="color: var(--theme-primary);"
                class="font-medium hover:underline ml-1">
                {{ __('Se connecter') }}
            </a>
        </div>
    </form>
</div>