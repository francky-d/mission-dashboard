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
    <div class="mb-8 text-center">
        <div class="inline-flex items-center justify-center w-14 h-14 rounded-2xl mb-4"
            style="background: linear-gradient(135deg, var(--theme-primary), var(--theme-accent));">
            <svg class="w-7 h-7 text-white" fill="none" stroke="currentColor" viewBox="0 0 24 24">
                <path stroke-linecap="round" stroke-linejoin="round" stroke-width="2"
                    d="M17 20h5v-2a3 3 0 00-5.356-1.857M17 20H7m10 0v-2c0-.656-.126-1.283-.356-1.857M7 20H2v-2a3 3 0 015.356-1.857M7 20v-2c0-.656.126-1.283.356-1.857m0 0a5.002 5.002 0 019.288 0M15 7a3 3 0 11-6 0 3 3 0 016 0zm6 3a2 2 0 11-4 0 2 2 0 014 0zM7 10a2 2 0 11-4 0 2 2 0 014 0z" />
            </svg>
        </div>
        <h2 class="text-2xl font-bold text-slate-900">Inscription Commercial</h2>
        <p class="mt-2 text-sm text-slate-500">
            Créez votre compte commercial pour gérer votre portefeuille
        </p>
    </div>

    <form wire:submit="register" class="space-y-5">
        <!-- Name -->
        <div>
            <x-input-label for="name" :value="__('Nom complet')" class="text-slate-700 font-medium" />
            <x-text-input wire:model="name" id="name"
                class="block mt-1.5 w-full rounded-xl border-slate-200 shadow-sm focus:ring-2 focus:ring-offset-0 transition-all duration-200"
                style="--tw-ring-color: var(--theme-primary);" type="text" name="name" required autofocus
                autocomplete="name" placeholder="Marie Martin" />
            <x-input-error :messages="$errors->get('name')" class="mt-2" />
        </div>

        <!-- Email Address -->
        <div>
            <x-input-label for="email" :value="__('Email')" class="text-slate-700 font-medium" />
            <x-text-input wire:model="email" id="email"
                class="block mt-1.5 w-full rounded-xl border-slate-200 shadow-sm focus:ring-2 focus:ring-offset-0 transition-all duration-200"
                style="--tw-ring-color: var(--theme-primary);" type="email" name="email" required
                autocomplete="username" placeholder="commercial@exemple.com" />
            <x-input-error :messages="$errors->get('email')" class="mt-2" />
        </div>

        <!-- Password -->
        <div>
            <x-input-label for="password" :value="__('Mot de passe')" class="text-slate-700 font-medium" />
            <x-text-input wire:model="password" id="password"
                class="block mt-1.5 w-full rounded-xl border-slate-200 shadow-sm focus:ring-2 focus:ring-offset-0 transition-all duration-200"
                style="--tw-ring-color: var(--theme-primary);" type="password" name="password" required
                autocomplete="new-password" placeholder="••••••••" />
            <x-input-error :messages="$errors->get('password')" class="mt-2" />
        </div>

        <!-- Confirm Password -->
        <div>
            <x-input-label for="password_confirmation" :value="__('Confirmer le mot de passe')"
                class="text-slate-700 font-medium" />
            <x-text-input wire:model="password_confirmation" id="password_confirmation"
                class="block mt-1.5 w-full rounded-xl border-slate-200 shadow-sm focus:ring-2 focus:ring-offset-0 transition-all duration-200"
                style="--tw-ring-color: var(--theme-primary);" type="password" name="password_confirmation" required
                autocomplete="new-password" placeholder="••••••••" />
            <x-input-error :messages="$errors->get('password_confirmation')" class="mt-2" />
        </div>

        <div class="pt-2">
            <button type="submit"
                class="w-full flex justify-center items-center gap-2 py-3.5 px-4 rounded-xl text-sm font-semibold text-white shadow-lg transition-all duration-300 hover:shadow-xl hover:-translate-y-0.5"
                style="background: linear-gradient(135deg, var(--theme-primary), var(--theme-secondary));">
                <svg class="w-5 h-5" fill="none" stroke="currentColor" viewBox="0 0 24 24">
                    <path stroke-linecap="round" stroke-linejoin="round" stroke-width="2"
                        d="M18 9v3m0 0v3m0-3h3m-3 0h-3m-2-5a4 4 0 11-8 0 4 4 0 018 0zM3 20a6 6 0 0112 0v1H3v-1z" />
                </svg>
                {{ __('Créer mon compte') }}
            </button>
        </div>

        <div class="relative py-4">
            <div class="absolute inset-0 flex items-center">
                <div class="w-full border-t border-slate-200"></div>
            </div>
            <div class="relative flex justify-center text-sm">
                <span class="px-4 bg-white text-slate-400">ou</span>
            </div>
        </div>

        <div class="text-center">
            <p class="text-sm text-slate-500">
                Déjà inscrit ?
                <a href="{{ route('commercial.login') }}" wire:navigate
                    class="font-semibold transition-colors hover:underline" style="color: var(--theme-primary);">
                    {{ __('Se connecter') }}
                </a>
            </p>
        </div>
    </form>
</div>