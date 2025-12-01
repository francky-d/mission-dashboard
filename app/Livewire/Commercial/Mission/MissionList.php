<?php

namespace App\Livewire\Commercial\Mission;

use App\Enums\MissionStatus;
use App\Models\Mission;
use Illuminate\Support\Facades\Auth;
use Illuminate\View\View;
use Livewire\Attributes\Url;
use Livewire\Component;
use Livewire\WithPagination;

class MissionList extends Component
{
    use WithPagination;

    #[Url]
    public string $search = '';

    #[Url]
    public string $status = '';

    public function updatedSearch(): void
    {
        $this->resetPage();
    }

    public function updatedStatus(): void
    {
        $this->resetPage();
    }

    public function archive(int $missionId): void
    {
        $mission = Mission::query()
            ->where('id', $missionId)
            ->where('commercial_id', Auth::id())
            ->first();

        if ($mission) {
            $mission->update(['status' => MissionStatus::Archived]);
        }
    }

    public function activate(int $missionId): void
    {
        $mission = Mission::query()
            ->where('id', $missionId)
            ->where('commercial_id', Auth::id())
            ->first();

        if ($mission) {
            $mission->update(['status' => MissionStatus::Active]);
        }
    }

    public function render(): View
    {
        $missions = Mission::query()
            ->withCount('applications')
            ->with('tags')
            ->where('commercial_id', Auth::id())
            ->when($this->search, function ($query) {
                $query->where(function ($q) {
                    $q->where('title', 'ilike', "%{$this->search}%")
                        ->orWhere('description', 'ilike', "%{$this->search}%")
                        ->orWhere('location', 'ilike', "%{$this->search}%");
                });
            })
            ->when($this->status, function ($query) {
                $query->where('status', $this->status);
            })
            ->latest()
            ->paginate(10);

        $statusCounts = Mission::query()
            ->where('commercial_id', Auth::id())
            ->selectRaw('status, count(*) as count')
            ->groupBy('status')
            ->pluck('count', 'status')
            ->toArray();

        return view('livewire.commercial.mission.mission-list', [
            'missions' => $missions,
            'statusCounts' => $statusCounts,
            'statuses' => MissionStatus::cases(),
        ]);
    }
}
