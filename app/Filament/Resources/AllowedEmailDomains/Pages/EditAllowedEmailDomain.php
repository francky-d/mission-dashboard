<?php

namespace App\Filament\Resources\AllowedEmailDomains\Pages;

use App\Filament\Resources\AllowedEmailDomains\AllowedEmailDomainResource;
use Filament\Actions\DeleteAction;
use Filament\Resources\Pages\EditRecord;

class EditAllowedEmailDomain extends EditRecord
{
    protected static string $resource = AllowedEmailDomainResource::class;

    protected function getHeaderActions(): array
    {
        return [
            DeleteAction::make(),
        ];
    }
}
