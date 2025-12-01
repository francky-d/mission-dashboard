<?php

namespace App\Livewire\Commercial\Consultant;

use App\Models\Application;
use App\Models\User;
use Illuminate\Support\Facades\Auth;
use Illuminate\View\View;
use Livewire\Attributes\Locked;
use Livewire\Component;

class ConsultantShow extends Component
{
    #[Locked]
    public User $user;

    public function mount(User $user): void
    {
        $this->user = $user->load(['consultantProfile.tags']);
    }

    public function render(): View
    {
        // Get applications from this consultant to the commercial's missions
        $applications = Application::query()
            ->with('mission')
            ->where('consultant_id', $this->user->id)
            ->whereHas('mission', fn ($q) => $q->where('commercial_id', Auth::id()))
            ->latest()
            ->get();

        return view('livewire.commercial.consultant.consultant-show', [
            'applications' => $applications,
        ]);
    }
}
