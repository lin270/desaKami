<?php

namespace Database\Seeders;

use App\Models\VillageStatistic;
use Illuminate\Database\Seeder;

class VillageStatisticSeeder extends Seeder
{
    public function run(): void
    {
        // ANGKA CONTOH - bukan data resmi. Ganti lewat menu Data Desa di panel admin.
        $stats = [
            ['penduduk_total', 'Jumlah Penduduk', '4.500', 'jiwa', 'penduduk'],
            ['penduduk_laki', 'Laki-laki', '2.250', 'jiwa', 'penduduk'],
            ['penduduk_perempuan', 'Perempuan', '2.250', 'jiwa', 'penduduk'],
            ['kepala_keluarga', 'Kepala Keluarga', '1.350', 'KK', 'penduduk'],
            ['jumlah_dusun', 'Jumlah Dusun', '3', 'dusun', 'wilayah'],
            ['jumlah_rw', 'Jumlah RW', '6', 'RW', 'wilayah'],
            ['jumlah_rt', 'Jumlah RT', '18', 'RT', 'wilayah'],
            ['luas_wilayah', 'Luas Wilayah', '350', 'ha', 'wilayah'],
        ];

        foreach ($stats as $i => [$key, $label, $value, $unit, $group]) {
            VillageStatistic::create([
                'key' => $key,
                'label' => $label,
                'value' => $value,
                'unit' => $unit,
                'group' => $group,
                'sort_order' => $i,
            ]);
        }
    }
}
