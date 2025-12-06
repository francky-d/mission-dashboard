<?php

use App\Livewire\Actions\Logout;
use Illuminate\Support\Facades\Auth;
use Illuminate\Support\Facades\Session;
use Livewire\Attributes\Layout;
use Livewire\Volt\Component;

new #[Layout('layouts.guest')] class extends Component
{
    /**
     * Send an email verification notification to the user.
     */
    public function sendVerification(): void
    {
        if (Auth::user()->hasVerifiedEmail()) {
            $this->redirectIntended(default: route('dashboard', absolute: false), navigate: true);

            return;
        }

        Auth::user()->sendEmailVerificationNotification();

        Session::flash('status', 'verification-link-sent');
    }

    /**
     * Log the current user out of the application.
     */
    public function logout(Logout $logout): void
    {
        $logout();

        $this->redirect('/', navigate: true);
    }
}; ?>

<div>
    <div class="text-center mb-6">
        <div class="inline-flex items-center justify-center w-16 h-16 bg-blue-100 text-blue-600 rounded-full mb-4">
            <svg class="w-8 h-8" fill="none" stroke="currentColor" viewBox="0 0 24 24">
                <path stroke-linecap="round" stroke-linejoin="round" stroke-width="2"
                    d="M3 8l7.89 5.26a2 2 0 002.22 0L21 8M5 19h14a2 2 0 002-2V7a2 2 0 00-2-2H5a2 2 0 00-2 2v10a2 2 0 002 2z" />
            </svg>
        </div>
        <h2 class="text-xl font-semibold text-slate-900">Vérifiez votre adresse email</h2>
    </div>

    <div class="mb-6 text-sm text-slate-600 text-center">
        Merci pour votre inscription ! Avant de commencer, veuillez vérifier votre adresse email en cliquant sur le lien
        que nous venons de vous envoyer. Si vous n'avez pas reçu l'email, nous vous en enverrons un nouveau avec
        plaisir.
    </div>

    @if (session('status') == 'verification-link-sent')
        <div class="mb-4 p-4 bg-green-50 border border-green-200 rounded-lg">
            <p class="text-sm text-green-700 text-center">
                Un nouveau lien de vérification a été envoyé à l'adresse email que vous avez fournie lors de l'inscription.
            </p>
        </div>
    @endif

    <div class="flex flex-col gap-4">
        <x-primary-button wire:click="sendVerification" class="w-full justify-center">
            <svg class="w-4 h-4 mr-2" fill="none" stroke="currentColor" viewBox="0 0 24 24">
                <path stroke-linecap="round" stroke-linejoin="round" stroke-width="2"
                    d="M4 4v5h.582m15.356 2A8.001 8.001 0 004.582 9m0 0H9m11 11v-5h-.581m0 0a8.003 8.003 0 01-15.357-2m15.357 2H15" />
            </svg>
            Renvoyer l'email de vérification
        </x-primary-button>

        <button wire:click="logout" type="button"
            class="w-full text-center text-sm text-slate-600 hover:text-slate-900">
            Se déconnecter
        </button>
    </div>
</div>