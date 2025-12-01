<?php

namespace App\Filament\Widgets;

use App\Enums\ApplicationStatus;
use App\Models\Application;
use Filament\Tables\Columns\TextColumn;
use Filament\Tables\Table;
use Filament\Widgets\TableWidget;
use Illuminate\Database\Eloquent\Builder;

class LatestApplications extends TableWidget
{
    protected int|string|array $columnSpan = 'full';

    protected static ?string $heading = 'Dernières candidatures';

    public function table(Table $table): Table
    {
        return $table
            ->query(
                fn (): Builder => Application::query()
                    ->with(['consultant', 'mission'])
                    ->latest()
                    ->limit(5)
            )
            ->columns([
                TextColumn::make('consultant.name')
                    ->label('Consultant')
                    ->searchable(),
                TextColumn::make('mission.title')
                    ->label('Mission')
                    ->limit(30),
                TextColumn::make('status')
                    ->label('Statut')
                    ->badge()
                    ->formatStateUsing(fn (ApplicationStatus $state): string => $state->label())
                    ->color(fn (ApplicationStatus $state): string => $state->color()),
                TextColumn::make('created_at')
                    ->label('Date')
                    ->dateTime('d/m/Y H:i')
                    ->sortable(),
            ])
            ->paginated(false);
    }
}
