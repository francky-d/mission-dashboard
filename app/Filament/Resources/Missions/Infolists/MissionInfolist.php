<?php

namespace App\Filament\Resources\Missions\Infolists;

use App\Enums\MissionStatus;
use Filament\Infolists\Components\TextEntry;
use Filament\Schemas\Components\Grid;
use Filament\Schemas\Components\Section;
use Filament\Schemas\Schema;

class MissionInfolist
{
    public static function configure(Schema $schema): Schema
    {
        return $schema
            ->components([
                Section::make('Informations de la mission')
                    ->schema([
                        Grid::make(2)
                            ->schema([
                                TextEntry::make('title')
                                    ->label('Titre'),
                                TextEntry::make('commercial.name')
                                    ->label('Commercial'),
                                TextEntry::make('location')
                                    ->label('Localisation'),
                                TextEntry::make('daily_rate')
                                    ->label('TJM')
                                    ->money('EUR'),
                                TextEntry::make('status')
                                    ->label('Statut')
                                    ->badge()
                                    ->formatStateUsing(fn (MissionStatus $state): string => $state->label())
                                    ->color(fn (MissionStatus $state): string => $state->color()),
                                TextEntry::make('created_at')
                                    ->label('Créé le')
                                    ->dateTime('d/m/Y H:i'),
                            ]),
                    ]),
                Section::make('Description')
                    ->schema([
                        TextEntry::make('description')
                            ->label('')
                            ->prose()
                            ->columnSpanFull(),
                    ]),
                Section::make('Tags')
                    ->schema([
                        TextEntry::make('tags.name')
                            ->label('')
                            ->badge()
                            ->separator(', '),
                    ]),
                Section::make('Statistiques')
                    ->schema([
                        Grid::make(3)
                            ->schema([
                                TextEntry::make('applications_count')
                                    ->label('Candidatures')
                                    ->state(fn ($record) => $record->applications()->count()),
                                TextEntry::make('pending_applications')
                                    ->label('En attente')
                                    ->state(fn ($record) => $record->applications()->where('status', 'pending')->count()),
                                TextEntry::make('accepted_applications')
                                    ->label('Acceptées')
                                    ->state(fn ($record) => $record->applications()->where('status', 'accepted')->count()),
                            ]),
                    ]),
            ]);
    }
}
