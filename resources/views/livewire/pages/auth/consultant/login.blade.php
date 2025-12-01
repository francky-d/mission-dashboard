<?php

use App\Livewire\Forms\LoginForm;
use Illuminate\Support\Facades\Session;
use Livewire\Attributes\Layout;
use Livewire\Volt\Component;

new #[Layout('layouts.guest', ['theme' => 'consultant'])] class extends Component
{
    public LoginForm $form;

    /**
     * Handle an incoming authentication request.
     */
    public function login(): void
    {
        $this->validate();

        $this->form->authenticate();

        Session::regenerate();

        $this->redirectIntended(default: route('dashboard', absolute: false), navigate: true);
    }
}; ?>

<div>
    <div class="mb-8 text-center">
        <div class="inline-flex items-center justify-center w-14 h-14 rounded-2xl mb-4"
            style="background: linear-gradient(135deg, var(--theme-primary), var(--theme-accent));">
            <svg class="w-7 h-7 text-white" fill="none" stroke="currentColor" viewBox="0 0 24 24">
                <path stroke-linecap="round" stroke-linejoin="round" stroke-width="2"
                    d="M16 7a4 4 0 11-8 0 4 4 0 018 0zM12 14a7 7 0 00-7 7h14a7 7 0 00-7-7z" />
            </svg>
        </div>
        <h2 class="text-2xl font-bold text-slate-900">Connexion Consultant</h2>
        <p class="mt-2 text-sm text-slate-500">
            Accédez à votre espace personnel
        </p>
    </div>

    <!-- Session Status -->
    <x-auth-session-status class="mb-4" :status="session('status')" />

    <form wire:submit="login" class="space-y-5">
        <!-- Email Address -->
        <div>
            <x-input-label for="email" :value="__('Email')" class="text-slate-700 font-medium" />
            <x-text-input wire:model="form.email" id="email"
                class="block mt-1.5 w-full rounded-xl border-slate-200 shadow-sm focus:ring-2 focus:ring-offset-0 transition-all duration-200"
                style="--tw-ring-color: var(--theme-primary); border-color: var(--tw-ring-color);" type="email"
                name="email" required autofocus autocomplete="username" placeholder="consultant@exemple.com" />
            <x-input-error :messages="$errors->get('form.email')" class="mt-2" />
        </div>

        <!-- Password -->
        <div>
            <x-input-label for="password" :value="__('Mot de passe')" class="text-slate-700 font-medium" />
            <x-text-input wire:model="form.password" id="password"
                class="block mt-1.5 w-full rounded-xl border-slate-200 shadow-sm focus:ring-2 focus:ring-offset-0 transition-all duration-200"
                style="--tw-ring-color: var(--theme-primary);" type="password" name="password" required
                autocomplete="current-password" placeholder="••••••••" />
            <x-input-error :messages="$errors->get('form.password')" class="mt-2" />
        </div>

        <!-- Remember Me & Forgot Password -->
        <div class="flex items-center justify-between">
            <label for="remember" class="inline-flex items-center cursor-pointer">
                <input wire:model="form.remember" id="remember" type="checkbox"
                    class="w-4 h-4 rounded border-slate-300 shadow-sm transition-colors"
                    style="color: var(--theme-primary);" name="remember">
                <span class="ms-2 text-sm text-slate-600">{{ __('Se souvenir de moi') }}</span>
            </label>

            @if (Route::has('password.request'))
                <a class="text-sm text-slate-500 hover:text-slate-700 transition-colors"
                    href="{{ route('password.request') }}" wire:navigate>
                    {{ __('Mot de passe oublié ?') }}
                </a>
            @endif
        </div>

        <div class="pt-2">
            <button type="submit"
                class="w-full flex justify-center items-center gap-2 py-3.5 px-4 rounded-xl text-sm font-semibold text-white shadow-lg transition-all duration-300 hover:shadow-xl hover:-translate-y-0.5"
                style="background: linear-gradient(135deg, var(--theme-primary), var(--theme-secondary));">
                <svg class="w-5 h-5" fill="none" stroke="currentColor" viewBox="0 0 24 24">
                    <path stroke-linecap="round" stroke-linejoin="round" stroke-width="2"
                        d="M11 16l-4-4m0 0l4-4m-4 4h14m-5 4v1a3 3 0 01-3 3H6a3 3 0 01-3-3V7a3 3 0 013-3h7a3 3 0 013 3v1" />
                </svg>
                {{ __('Se connecter') }}
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
                Pas encore de compte ?
                <a href="{{ route('consultant.register') }}" wire:navigate
                    class="font-semibold transition-colors hover:underline" style="color: var(--theme-primary);">
                    {{ __('Créer un compte') }}
                </a>
            </p>
        </div>
    </form>
</div>