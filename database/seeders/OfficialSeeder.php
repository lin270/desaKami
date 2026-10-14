<?php

namespace Database\Seeders;

use App\Models\Official;
use Illuminate\Database\Seeder;

class OfficialSeeder extends Seeder
{
    public function run(): void
    {
        // PLACEHOLDER: nama pejabat sengaja generik. Ganti dengan data resmi di panel admin.
        $officials = [
            ['Nama Kepala Desa', 'Kepala Desa', 'kepala_desa'],
            ['Nama Sekretaris Desa', 'Sekretaris Desa', 'sekretaris'],
            ['Nama Kaur Tata Usaha dan Umum', 'Kepala Urusan Tata Usaha dan Umum', 'kaur'],
            ['Nama Kaur Keuangan', 'Kepala Urusan Keuangan', 'kaur'],
            ['Nama Kaur Perencanaan', 'Kepala Urusan Perencanaan', 'kaur'],
            ['Nama Kasi Pemerintahan', 'Kepala Seksi Pemerintahan', 'kasi'],
            ['Nama Kasi Kesejahteraan', 'Kepala Seksi Kesejahteraan', 'kasi'],
            ['Nama Kasi Pelayanan', 'Kepala Seksi Pelayanan', 'kasi'],
            ['Nama Kepala Dusun I', 'Kepala Dusun I', 'kadus'],
            ['Nama Kepala Dusun II', 'Kepala Dusun II', 'kadus'],
            ['Nama Kepala Dusun III', 'Kepala Dusun III', 'kadus'],
        ];

        foreach ($officials as $i => [$name, $position, $group]) {
            Official::create([
                'name' => $name,
                'position' => $position,
                'group' => $group,
                'sort_order' => $i,
                'is_active' => true,
            ]);
        }
    }
}
