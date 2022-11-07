<?php

namespace App\Enums;

use Illuminate\Support\Str;

enum AreaGroup: string
{
    case Provinsi = 'provinsi';
    case Kabupaten = 'kabupaten';
    case Kota = 'kota';
    case Kecamatan = 'kecamatan';
    case Kelurahan = 'kelurahan';

    public function label(): string
    {
        return match ($this) {
            self::Provinsi => 'Provinsi',
            self::Kabupaten => 'Kabupaten',
            self::Kota => 'Kota',
            self::Kecamatan => 'Kecamatan',
            self::Kelurahan => 'Kelurahan',
        };
    }

    public function slug(): string
    {
        return Str::slug($this->label());
    }

    public static function options(): array
    {
        return array_map(fn ($case) => [
            'id' => $case->value,
            'label' => $case->label(),
        ], self::cases());
    }
}
