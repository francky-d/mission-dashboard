<?php

namespace App\Livewire\Consultant\Application;

use App\Enums\ApplicationStatus;
use App\Models\Application;
use Illuminate\Support\Facades\Auth;
use Illuminate\View\View;
use Livewire\Attributes\Url;
use Livewire\Component;
use Livewire\WithPagination;

class ApplicationList extends Component
{
    use WithPagination;

    #[Url]
    public string $status = '';

    public function updatedStatus(): void
    {
        $this->resetPage();
    }

    public function withdraw(int $applicationId): void
    {
        $application = Application::query()
            ->where('id', $applicationId)
            ->where('consultant_id', Auth::id())
            ->where('status', ApplicationStatus::Pending)
            ->first();

        if ($application) {
            $application->delete();
        }
    }

    public function render(): View
    {
        $applications = Application::query()
            ->with(['mission.commercial', 'mission.tags'])
            ->where('consultant_id', Auth::id())
            ->when($this->status, function ($query) {
                $query->where('status', $this->status);
            })
            ->latest()
            ->paginate(10);

        $statusCounts = Application::query()
            ->where('consultant_id', Auth::id())
            ->selectRaw('status, count(*) as count')
            ->groupBy('status')
            ->pluck('count', 'status')
            ->toArray();

        return view('livewire.consultant.application.application-list', [
            'applications' => $applications,
            'statusCounts' => $statusCounts,
            'statuses' => ApplicationStatus::cases(),
        ]);
    }
}
