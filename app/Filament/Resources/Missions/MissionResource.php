<?php

namespace App\Filament\Resources\Missions;

use App\Filament\Resources\Missions\Infolists\MissionInfolist;
use App\Filament\Resources\Missions\Pages\ListMissions;
use App\Filament\Resources\Missions\Pages\ViewMission;
use App\Filament\Resources\Missions\Tables\MissionsTable;
use App\Models\Mission;
use BackedEnum;
use Filament\Resources\Resource;
use Filament\Schemas\Schema;
use Filament\Support\Icons\Heroicon;
use Filament\Tables\Table;
use UnitEnum;

class MissionResource extends Resource
{
    protected static ?string $model = Mission::class;

    protected static string|BackedEnum|null $navigationIcon = Heroicon::OutlinedBriefcase;

    protected static ?string $navigationLabel = 'Missions';

    protected static ?string $modelLabel = 'Mission';

    protected static ?string $pluralModelLabel = 'Missions';

    protected static string|UnitEnum|null $navigationGroup = 'Supervision';

    protected static ?int $navigationSort = 1;

    public static function table(Table $table): Table
    {
        return MissionsTable::configure($table);
    }

    public static function infolist(Schema $schema): Schema
    {
        return MissionInfolist::configure($schema);
    }

    public static function getPages(): array
    {
        return [
            'index' => ListMissions::route('/'),
            'view' => ViewMission::route('/{record}'),
        ];
    }
}
