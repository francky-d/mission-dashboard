<?php

namespace App\Filament\Widgets;

use App\Models\Application;
use App\Models\Mission;
use Carbon\Carbon;
use Filament\Widgets\ChartWidget;

class MissionsChart extends ChartWidget
{
    protected ?string $heading = 'Missions et Candidatures';

    protected ?string $pollingInterval = '30s';

    protected function getData(): array
    {
        $months = collect(range(5, 0))->map(function ($monthsAgo) {
            return Carbon::now()->subMonths($monthsAgo);
        });

        $missionsData = $months->map(function ($month) {
            return Mission::whereYear('created_at', $month->year)
                ->whereMonth('created_at', $month->month)
                ->count();
        })->toArray();

        $applicationsData = $months->map(function ($month) {
            return Application::whereYear('created_at', $month->year)
                ->whereMonth('created_at', $month->month)
                ->count();
        })->toArray();

        $labels = $months->map(function ($month) {
            return $month->translatedFormat('M Y');
        })->toArray();

        return [
            'datasets' => [
                [
                    'label' => 'Missions créées',
                    'data' => $missionsData,
                    'borderColor' => '#10b981',
                    'backgroundColor' => 'rgba(16, 185, 129, 0.1)',
                    'fill' => true,
                ],
                [
                    'label' => 'Candidatures',
                    'data' => $applicationsData,
                    'borderColor' => '#f59e0b',
                    'backgroundColor' => 'rgba(245, 158, 11, 0.1)',
                    'fill' => true,
                ],
            ],
            'labels' => $labels,
        ];
    }

    protected function getType(): string
    {
        return 'line';
    }
}
