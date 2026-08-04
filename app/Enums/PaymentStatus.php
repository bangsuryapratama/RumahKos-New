<?php

namespace App\Enums;

enum PaymentStatus: string
{
    case PENDING = 'pending';
    case PAID = 'paid';
    case CONFIRMED = 'confirmed';
    case FAILED = 'failed';
    case VERIFICATION = 'verification';
    case CANCELLED = 'cancelled';

    public function label(): string
    {
        return match($this) {
            self::PENDING => 'Menunggu Pembayaran',
            self::PAID => 'Dibayar',
            self::CONFIRMED => 'Lunas',
            self::FAILED => 'Gagal',
            self::VERIFICATION => 'Verifikasi',
            self::CANCELLED => 'Dibatalkan',
        };
    }

    public function color(): string
    {
        return match($this) {
            self::PENDING => 'yellow',
            self::PAID => 'blue',
            self::CONFIRMED => 'green',
            self::FAILED => 'red',
            self::VERIFICATION => 'orange',
            self::CANCELLED => 'gray',
        };
    }

    public function badgeClass(): string
    {
        return match($this) {
            self::PENDING => 'bg-yellow-100 text-yellow-700',
            self::PAID => 'bg-blue-100 text-blue-700',
            self::CONFIRMED => 'bg-green-100 text-green-700',
            self::FAILED => 'bg-red-100 text-red-700',
            self::VERIFICATION => 'bg-orange-100 text-orange-700',
            self::CANCELLED => 'bg-gray-100 text-gray-700',
        };
    }
}
