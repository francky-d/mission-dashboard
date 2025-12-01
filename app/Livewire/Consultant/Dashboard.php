<?php

namespace App\Livewire\Consultant;

use App\Enums\ApplicationStatus;
use App\Models\Application;
use App\Models\Mission;
use Illuminate\Support\Facades\Auth;
use Illuminate\View\View;
use Livewire\Component;

class Dashboard extends Component
{
    public function render(): View
    {
        $userId = Auth::id();

        $myApplications = Application::where('consultant_id', $userId)->count();
        $pendingApplications = Application::where('consultant_id', $userId)
            ->where('status', ApplicationStatus::Pending)
            ->count();
        $acceptedApplications = Application::where('consultant_id', $userId)
            ->where('status', ApplicationStatus::Accepted)
            ->count();

        $availableMissions = Mission::active()->count();

        $recentApplications = Application::query()
            ->with(['mission', 'mission.commercial'])
            ->where('consultant_id', $userId)
            ->latest()
            ->take(5)
            ->get();

        $recommendedMissions = Mission::query()
            ->active()
            ->whereDoesntHave('applications', fn ($q) => $q->where('consultant_id', $userId))
            ->latest()
            ->take(5)
            ->get();

        return view('livewire.consultant.dashboard', [
            'myApplications' => $myApplications,
            'pendingApplications' => $pendingApplications,
            'acceptedApplications' => $acceptedApplications,
            'availableMissions' => $availableMissions,
            'recentApplications' => $recentApplications,
            'recommendedMissions' => $recommendedMissions,
        ]);
    }
}
