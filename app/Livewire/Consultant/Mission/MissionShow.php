<?php

namespace App\Livewire\Consultant\Mission;

use App\Enums\ApplicationStatus;
use App\Models\Application;
use App\Models\Mission;
use App\Notifications\NewApplicationReceived;
use Illuminate\Support\Facades\Auth;
use Illuminate\View\View;
use Livewire\Attributes\Computed;
use Livewire\Attributes\Locked;
use Livewire\Component;

class MissionShow extends Component
{
    #[Locked]
    public Mission $mission;

    public function mount(Mission $mission): void
    {
        $this->mission = $mission->load(['commercial', 'tags']);
    }

    #[Computed]
    public function existingApplication(): ?Application
    {
        return Application::query()
            ->where('mission_id', $this->mission->id)
            ->where('consultant_id', Auth::id())
            ->first();
    }

    #[Computed]
    public function hasApplied(): bool
    {
        return $this->existingApplication !== null;
    }

    public function apply(): void
    {
        if ($this->hasApplied) {
            return;
        }

        $application = Application::create([
            'mission_id' => $this->mission->id,
            'consultant_id' => Auth::id(),
            'status' => ApplicationStatus::Pending,
        ]);

        // Notify the commercial about the new application
        $this->mission->commercial->notify(new NewApplicationReceived($application));

        unset($this->existingApplication);
        unset($this->hasApplied);

        $this->dispatch('application-submitted');
    }

    public function withdraw(): void
    {
        $application = $this->existingApplication;

        if (! $application) {
            return;
        }

        // Only allow withdrawal for pending applications
        if ($application->status !== ApplicationStatus::Pending) {
            return;
        }

        $application->delete();

        unset($this->existingApplication);
        unset($this->hasApplied);

        $this->dispatch('application-withdrawn');
    }

    public function render(): View
    {
        return view('livewire.consultant.mission.mission-show');
    }
}
