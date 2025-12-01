<?php

namespace App\Filament\Resources\AllowedEmailDomains\Pages;

use App\Filament\Resources\AllowedEmailDomains\AllowedEmailDomainResource;
use Filament\Actions\CreateAction;
use Filament\Resources\Pages\ListRecords;

class ListAllowedEmailDomains extends ListRecords
{
    protected static string $resource = AllowedEmailDomainResource::class;

    protected function getHeaderActions(): array
    {
        return [
            CreateAction::make(),
        ];
    }
}
