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
    <div class="mb-6">
        <h2 class="text-2xl font-bold text-gray-900">Connexion Consultant</h2>
        <p class="mt-1 text-sm text-gray-600">
            Accédez à votre espace personnel
        </p>
    </div>

    <!-- Session Status -->
    <x-auth-session-status class="mb-4" :status="session('status')" />

    <form wire:submit="login">
        <!-- Email Address -->
        <div>
            <x-input-label for="email" :value="__('Email')" />
            <x-text-input wire:model="form.email" id="email"
                class="block mt-1 w-full focus:ring-[var(--theme-primary)] focus:border-[var(--theme-primary)]"
                type="email" name="email" required autofocus autocomplete="username" />
            <x-input-error :messages="$errors->get('form.email')" class="mt-2" />
        </div>

        <!-- Password -->
        <div class="mt-4">
            <x-input-label for="password" :value="__('Password')" />
            <x-text-input wire:model="form.password" id="password"
                class="block mt-1 w-full focus:ring-[var(--theme-primary)] focus:border-[var(--theme-primary)]"
                type="password" name="password" required autocomplete="current-password" />
            <x-input-error :messages="$errors->get('form.password')" class="mt-2" />
        </div>

        <!-- Remember Me -->
        <div class="block mt-4">
            <label for="remember" class="inline-flex items-center">
                <input wire:model="form.remember" id="remember" type="checkbox"
                    class="rounded border-gray-300 shadow-sm focus:ring-[var(--theme-primary)]"
                    style="color: var(--theme-primary);" name="remember">
                <span class="ms-2 text-sm text-gray-600">{{ __('Remember me') }}</span>
            </label>
        </div>

        <div class="mt-6">
            <button type="submit"
                class="w-full flex justify-center py-3 px-4 border border-transparent rounded-lg shadow-sm text-sm font-medium text-white transition-colors duration-200"
                style="background-color: var(--theme-primary);"
                onmouseover="this.style.backgroundColor='var(--theme-secondary)'"
                onmouseout="this.style.backgroundColor='var(--theme-primary)'">
                {{ __('Se connecter') }}
            </button>
        </div>

        <div class="mt-6 flex items-center justify-between text-sm">
            @if (Route::has('password.request'))
                <a class="text-gray-600 hover:text-gray-900 underline" href="{{ route('password.request') }}" wire:navigate>
                    {{ __('Mot de passe oublié ?') }}
                </a>
            @endif

            <a href="{{ route('consultant.register') }}" wire:navigate style="color: var(--theme-primary);"
                class="font-medium hover:underline">
                {{ __('Créer un compte') }}
            </a>
        </div>
    </form>
</div>