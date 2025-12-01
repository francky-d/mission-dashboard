<?php

namespace App\Filament\Resources\AllowedEmailDomains\Pages;

use App\Filament\Resources\AllowedEmailDomains\AllowedEmailDomainResource;
use Filament\Resources\Pages\CreateRecord;

class CreateAllowedEmailDomain extends CreateRecord
{
    protected static string $resource = AllowedEmailDomainResource::class;
}
