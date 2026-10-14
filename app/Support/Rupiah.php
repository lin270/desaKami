<?php

namespace App\Support;

class Rupiah
{
    /** 1234567 -> "Rp 1.234.567" (negatif: "-Rp 1.234") */
    public static function format(int|float|null $amount): string
    {
        $amount = (int) round($amount ?? 0);

        return ($amount < 0 ? '-' : '').'Rp '.number_format(abs($amount), 0, ',', '.');
    }

    /** Ringkas untuk kartu: 1250000000 -> "Rp 1,25 M", 850000000 -> "Rp 850 jt". */
    public static function short(int|float|null $amount): string
    {
        $amount = (int) round($amount ?? 0);
        $abs = abs($amount);
        $sign = $amount < 0 ? '-' : '';

        if ($abs >= 1_000_000_000_000) {
            return $sign.'Rp '.rtrim(rtrim(number_format($abs / 1_000_000_000_000, 2, ',', '.'), '0'), ',').' T';
        }
        if ($abs >= 1_000_000_000) {
            return $sign.'Rp '.rtrim(rtrim(number_format($abs / 1_000_000_000, 2, ',', '.'), '0'), ',').' M';
        }
        if ($abs >= 1_000_000) {
            return $sign.'Rp '.rtrim(rtrim(number_format($abs / 1_000_000, 1, ',', '.'), '0'), ',').' jt';
        }

        return self::format($amount);
    }

    /** Persentase realisasi (0-100+), null jika anggaran 0. */
    public static function percent(int|float $realized, int|float $plan): ?float
    {
        return $plan > 0 ? round($realized / $plan * 100, 1) : null;
    }
}
