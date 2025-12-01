<?php

namespace App\Filament\Widgets;

use App\Enums\MissionStatus;
use App\Models\Application;
use App\Models\Mission;
use App\Models\User;
use Filament\Support\Icons\Heroicon;
use Filament\Widgets\StatsOverviewWidget;
use Filament\Widgets\StatsOverviewWidget\Stat;

class StatsOverview extends StatsOverviewWidget
{
    protected function getStats(): array
    {
        $totalUsers = User::count();
        $totalMissions = Mission::count();
        $activeMissions = Mission::where('status', MissionStatus::Active)->count();
        $totalApplications = Application::count();
        $pendingApplications = Application::where('status', 'pending')->count();

        return [
            Stat::make('Utilisateurs', $totalUsers)
                ->description('Total des utilisateurs')
                ->descriptionIcon(Heroicon::Users)
                ->color('primary'),
            Stat::make('Missions', $totalMissions)
                ->description($activeMissions.' actives')
                ->descriptionIcon(Heroicon::Briefcase)
                ->color('success'),
            Stat::make('Candidatures', $totalApplications)
                ->description($pendingApplications.' en attente')
                ->descriptionIcon(Heroicon::DocumentText)
                ->color('warning'),
        ];
    }
}
