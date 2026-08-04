<?php

namespace App\Enums;

enum ResidentStatus: string
{
    case ACTIVE = 'active';
    case INACTIVE = 'inactive';
    case CANCELLED = 'cancelled';
    case SUSPENDED = 'suspended';

    public function label(): string
    {
        return match($this) {
            self::ACTIVE => 'Aktif',
            self::INACTIVE => 'Tidak Aktif',
            self::CANCELLED => 'Dibatalkan',
            self::SUSPENDED => 'Ditangguhkan',
        };
    }

    public function color(): string
    {
        return match($this) {
            self::ACTIVE => 'green',
            self::INACTIVE => 'gray',
            self::CANCELLED => 'red',
            self::SUSPENDED => 'yellow',
        };
    }
}
