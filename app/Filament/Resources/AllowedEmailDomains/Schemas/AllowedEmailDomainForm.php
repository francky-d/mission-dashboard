<?php

namespace App\Filament\Resources\AllowedEmailDomains\Schemas;

use Filament\Forms\Components\Textarea;
use Filament\Forms\Components\TextInput;
use Filament\Forms\Components\Toggle;
use Filament\Schemas\Schema;

class AllowedEmailDomainForm
{
    public static function configure(Schema $schema): Schema
    {
        return $schema
            ->components([
                TextInput::make('domain')
                    ->label('Domaine')
                    ->placeholder('example.com')
                    ->helperText('Entrez le domaine sans @ (ex: company.com)')
                    ->required()
                    ->unique(ignoreRecord: true)
                    ->maxLength(255)
                    ->rules(['regex:/^[a-zA-Z0-9][a-zA-Z0-9-]*\.[a-zA-Z]{2,}$/'])
                    ->validationMessages([
                        'regex' => 'Le format du domaine est invalide. Utilisez un format comme example.com',
                    ]),
                Textarea::make('description')
                    ->label('Description')
                    ->placeholder('Description optionnelle du domaine')
                    ->rows(2)
                    ->maxLength(500),
                Toggle::make('is_active')
                    ->label('Actif')
                    ->helperText('Les domaines inactifs ne permettent pas l\'inscription ou la connexion')
                    ->default(true),
            ]);
    }
}
