<?php

namespace App\Livewire\Consultant\Mission;

use App\Models\Mission;
use Illuminate\View\View;
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

    public function render(): View
    {
        return view('livewire.consultant.mission.mission-show');
    }
}
