<?php

namespace App\Filament\Resources\Missions\Tables;

use App\Enums\MissionStatus;
use Filament\Actions\ViewAction;
use Filament\Tables\Columns\TextColumn;
use Filament\Tables\Filters\SelectFilter;
use Filament\Tables\Table;

class MissionsTable
{
    public static function configure(Table $table): Table
    {
        return $table
            ->columns([
                TextColumn::make('title')
                    ->label('Titre')
                    ->searchable()
                    ->sortable()
                    ->limit(50),
                TextColumn::make('commercial.name')
                    ->label('Commercial')
                    ->searchable()
                    ->sortable(),
                TextColumn::make('location')
                    ->label('Localisation')
                    ->searchable()
                    ->sortable()
                    ->toggleable(),
                TextColumn::make('status')
                    ->label('Statut')
                    ->badge()
                    ->formatStateUsing(fn (MissionStatus $state): string => $state->label())
                    ->color(fn (MissionStatus $state): string => $state->color()),
                TextColumn::make('applications_count')
                    ->label('Candidatures')
                    ->counts('applications')
                    ->sortable(),
                TextColumn::make('created_at')
                    ->label('Créé le')
                    ->dateTime('d/m/Y H:i')
                    ->sortable()
                    ->toggleable(isToggledHiddenByDefault: true),
            ])
            ->defaultSort('created_at', 'desc')
            ->filters([
                SelectFilter::make('status')
                    ->label('Statut')
                    ->options(MissionStatus::class),
                SelectFilter::make('commercial')
                    ->label('Commercial')
                    ->relationship('commercial', 'name'),
            ])
            ->recordActions([
                ViewAction::make(),
            ])
            ->toolbarActions([]);
    }
}
