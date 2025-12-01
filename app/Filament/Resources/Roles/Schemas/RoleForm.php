<?php

namespace App\Filament\Resources\Roles\Schemas;

use Filament\Forms\Components\CheckboxList;
use Filament\Forms\Components\TextInput;
use Filament\Schemas\Components\Section;
use Filament\Schemas\Schema;
use Spatie\Permission\Models\Permission;

class RoleForm
{
    public static function configure(Schema $schema): Schema
    {
        return $schema
            ->components([
                Section::make('Informations du rôle')
                    ->schema([
                        TextInput::make('name')
                            ->label('Nom')
                            ->required()
                            ->unique(ignoreRecord: true)
                            ->maxLength(255),
                    ]),
                Section::make('Permissions')
                    ->schema([
                        CheckboxList::make('permissions')
                            ->label('Permissions associées')
                            ->relationship('permissions', 'name')
                            ->columns(2)
                            ->searchable()
                            ->bulkToggleable()
                            ->getOptionLabelFromRecordUsing(fn (Permission $record) => $record->name),
                    ]),
            ]);
    }
}
