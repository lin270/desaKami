<?php

namespace Database\Seeders;

use App\Models\Budget;
use Illuminate\Database\Seeder;

class BudgetSeeder extends Seeder
{
    public function run(): void
    {
        $budget = Budget::create([
            'year' => (int) date('Y'),
            'title' => 'APBDes Desa Klepu Tahun '.date('Y'),
            'notes' => 'ANGKA CONTOH - ganti dengan data APBDes resmi dari Perdes.',
            'status' => 'published',
        ]);

        // [jenis, kelompok, uraian, anggaran, realisasi] - ANGKA CONTOH
        $rows = [
            ['pendapatan', 'Pendapatan Asli Desa', 'Hasil usaha desa', 50_000_000, 40_000_000],
            ['pendapatan', 'Transfer', 'Dana Desa', 800_000_000, 600_000_000],
            ['pendapatan', 'Transfer', 'Bagi hasil pajak dan retribusi', 120_000_000, 90_000_000],
            ['belanja', 'Bidang Penyelenggaraan Pemerintahan Desa', 'Operasional pemerintahan desa', 350_000_000, 280_000_000],
            ['belanja', 'Bidang Pelaksanaan Pembangunan Desa', 'Pembangunan dan pemeliharaan jalan', 400_000_000, 250_000_000],
            ['belanja', 'Bidang Pembinaan Kemasyarakatan', 'Kegiatan kemasyarakatan', 80_000_000, 60_000_000],
            ['belanja', 'Bidang Pemberdayaan Masyarakat', 'Pelatihan UMKM dan pertanian', 100_000_000, 70_000_000],
            ['pembiayaan_penerimaan', 'Penerimaan', 'SILPA tahun sebelumnya', 60_000_000, 60_000_000],
            ['pembiayaan_pengeluaran', 'Pengeluaran', 'Penyertaan modal BUMDes', 100_000_000, 100_000_000],
        ];

        foreach ($rows as $i => [$type, $category, $name, $plan, $realized]) {
            $budget->items()->create([
                'type' => $type, 'category' => $category, 'name' => $name,
                'amount_plan' => $plan, 'amount_realized' => $realized, 'sort_order' => $i + 1,
            ]);
        }
    }
}
