<?php

namespace App\Livewire\Commercial;

use App\Enums\ApplicationStatus;
use App\Enums\MissionStatus;
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

        $totalMissions = Mission::where('commercial_id', $userId)->count();
        $activeMissions = Mission::where('commercial_id', $userId)
            ->where('status', MissionStatus::Active)
            ->count();

        $pendingApplications = Application::query()
            ->whereHas('mission', fn ($q) => $q->where('commercial_id', $userId))
            ->where('status', ApplicationStatus::Pending)
            ->count();

        $totalApplications = Application::query()
            ->whereHas('mission', fn ($q) => $q->where('commercial_id', $userId))
            ->count();

        $recentApplications = Application::query()
            ->with(['consultant', 'mission'])
            ->whereHas('mission', fn ($q) => $q->where('commercial_id', $userId))
            ->latest()
            ->take(5)
            ->get();

        $recentMissions = Mission::query()
            ->withCount('applications')
            ->where('commercial_id', $userId)
            ->latest()
            ->take(5)
            ->get();

        return view('livewire.commercial.dashboard', [
            'totalMissions' => $totalMissions,
            'activeMissions' => $activeMissions,
            'pendingApplications' => $pendingApplications,
            'totalApplications' => $totalApplications,
            'recentApplications' => $recentApplications,
            'recentMissions' => $recentMissions,
        ]);
    }
}
