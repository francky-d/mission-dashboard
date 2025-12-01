<?php

namespace App\Livewire\Commercial\Mission;

use App\Enums\ApplicationStatus;
use App\Models\Application;
use App\Models\Mission;
use App\Notifications\ApplicationStatusChanged;
use Illuminate\Support\Facades\Auth;
use Illuminate\View\View;
use Livewire\Attributes\Locked;
use Livewire\Attributes\Url;
use Livewire\Component;
use Livewire\WithPagination;

class MissionShow extends Component
{
    use WithPagination;

    #[Locked]
    public Mission $mission;

    #[Url]
    public string $applicationStatus = '';

    public function mount(Mission $mission): void
    {
        // Ensure the commercial owns this mission
        abort_if($mission->commercial_id !== Auth::id(), 403);

        $this->mission = $mission->load('tags');
    }

    public function updatedApplicationStatus(): void
    {
        $this->resetPage();
    }

    public function updateApplicationStatus(int $applicationId, string $newStatus): void
    {
        $application = Application::query()
            ->where('id', $applicationId)
            ->whereHas('mission', function ($query) {
                $query->where('commercial_id', Auth::id());
            })
            ->first();

        if (! $application) {
            return;
        }

        $oldStatus = $application->status;
        $newStatusEnum = ApplicationStatus::from($newStatus);

        $application->update(['status' => $newStatusEnum]);

        // Notify the consultant about the status change
        $application->consultant->notify(
            new ApplicationStatusChanged($application, $oldStatus, $newStatusEnum)
        );
    }

    public function render(): View
    {
        $applications = Application::query()
            ->with(['consultant.consultantProfile.tags'])
            ->where('mission_id', $this->mission->id)
            ->when($this->applicationStatus, function ($query) {
                $query->where('status', $this->applicationStatus);
            })
            ->latest()
            ->paginate(10);

        $statusCounts = Application::query()
            ->where('mission_id', $this->mission->id)
            ->selectRaw('status, count(*) as count')
            ->groupBy('status')
            ->pluck('count', 'status')
            ->toArray();

        return view('livewire.commercial.mission.mission-show', [
            'applications' => $applications,
            'statusCounts' => $statusCounts,
            'statuses' => ApplicationStatus::cases(),
        ]);
    }
}
