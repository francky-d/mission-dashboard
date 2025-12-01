<?php

namespace App\Livewire\Consultant\Mission;

use App\Enums\MissionStatus;
use App\Models\Mission;
use Livewire\Attributes\Url;
use Livewire\Component;
use Livewire\WithPagination;

class MissionList extends Component
{
    use WithPagination;

    #[Url]
    public string $search = '';

    public function updatedSearch(): void
    {
        $this->resetPage();
    }

    public function render()
    {
        $missions = Mission::query()
            ->with(['commercial', 'tags'])
            ->where('status', MissionStatus::Active)
            ->when($this->search, function ($query) {
                $query->where(function ($q) {
                    $q->where('title', 'ilike', "%{$this->search}%")
                        ->orWhere('description', 'ilike', "%{$this->search}%")
                        ->orWhere('location', 'ilike', "%{$this->search}%");
                });
            })
            ->latest()
            ->paginate(10);

        return view('livewire.consultant.mission.mission-list', [
            'missions' => $missions,
        ]);
    }
}
