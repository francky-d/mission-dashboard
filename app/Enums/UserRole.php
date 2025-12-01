<?php

namespace App\Enums;

enum UserRole: string
{
    case Consultant = 'consultant';
    case Commercial = 'commercial';
    case Admin = 'admin';

    public function label(): string
    {
        return match ($this) {
            self::Consultant => 'Consultant',
            self::Commercial => 'Commercial',
            self::Admin => 'Administrateur',
        };
    }

    public function color(): string
    {
        return match ($this) {
            self::Consultant => 'info',
            self::Commercial => 'success',
            self::Admin => 'danger',
        };
    }
}
