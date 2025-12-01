<?php

namespace App\Filament\Resources\AllowedEmailDomains;

use App\Filament\Resources\AllowedEmailDomains\Pages\CreateAllowedEmailDomain;
use App\Filament\Resources\AllowedEmailDomains\Pages\EditAllowedEmailDomain;
use App\Filament\Resources\AllowedEmailDomains\Pages\ListAllowedEmailDomains;
use App\Filament\Resources\AllowedEmailDomains\Schemas\AllowedEmailDomainForm;
use App\Filament\Resources\AllowedEmailDomains\Tables\AllowedEmailDomainsTable;
use App\Models\AllowedEmailDomain;
use BackedEnum;
use Filament\Resources\Resource;
use Filament\Schemas\Schema;
use Filament\Support\Icons\Heroicon;
use Filament\Tables\Table;
use UnitEnum;

class AllowedEmailDomainResource extends Resource
{
    protected static ?string $model = AllowedEmailDomain::class;

    protected static string|BackedEnum|null $navigationIcon = Heroicon::OutlinedGlobeAlt;

    protected static string|UnitEnum|null $navigationGroup = 'Paramètres';

    protected static ?string $modelLabel = 'Domaine email';

    protected static ?string $pluralModelLabel = 'Domaines email';

    protected static ?int $navigationSort = 100;

    public static function form(Schema $schema): Schema
    {
        return AllowedEmailDomainForm::configure($schema);
    }

    public static function table(Table $table): Table
    {
        return AllowedEmailDomainsTable::configure($table);
    }

    public static function getRelations(): array
    {
        return [
            //
        ];
    }

    public static function getPages(): array
    {
        return [
            'index' => ListAllowedEmailDomains::route('/'),
            'create' => CreateAllowedEmailDomain::route('/create'),
            'edit' => EditAllowedEmailDomain::route('/{record}/edit'),
        ];
    }
}
