<?php

namespace App\Enums;

enum RoomStatus: string
{
    case AVAILABLE = 'available';
    case OCCUPIED = 'occupied';
    case MAINTENANCE = 'maintenance';

    public function label(): string
    {
        return match($this) {
            self::AVAILABLE => 'Tersedia',
            self::OCCUPIED => 'Terisi',
            self::MAINTENANCE => 'Pemeliharaan',
        };
    }

    public function color(): string
    {
        return match($this) {
            self::AVAILABLE => 'green',
            self::OCCUPIED => 'blue',
            self::MAINTENANCE => 'yellow',
        };
    }
}
