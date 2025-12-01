<?php

namespace App\Enums;

enum ApplicationStatus: string
{
    case Pending = 'pending';
    case Viewed = 'viewed';
    case Accepted = 'accepted';
    case Rejected = 'rejected';

    public function label(): string
    {
        return match ($this) {
            self::Pending => 'En attente',
            self::Viewed => 'Consultée',
            self::Accepted => 'Acceptée',
            self::Rejected => 'Refusée',
        };
    }

    public function color(): string
    {
        return match ($this) {
            self::Pending => 'warning',
            self::Viewed => 'info',
            self::Accepted => 'success',
            self::Rejected => 'danger',
        };
    }
}
