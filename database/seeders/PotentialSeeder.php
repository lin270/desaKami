<?php

namespace Database\Seeders;

use App\Models\Potential;
use Illuminate\Database\Seeder;

class PotentialSeeder extends Seeder
{
    public function run(): void
    {
        // [judul, kategori, ringkasan, isi] - KONTEN CONTOH, ganti dengan data asli desa
        $items = [
            ['Sentra UMKM Olahan Pangan', 'umkm', 'Kelompok usaha warga yang mengolah hasil bumi menjadi makanan ringan.', "Para pelaku UMKM desa mengolah hasil pertanian menjadi aneka produk makanan.\n\n*(Isi contoh - ganti dengan data resmi.)*"],
            ['Pertanian Padi dan Palawija', 'pertanian', 'Lahan pertanian produktif sebagai sumber penghidupan utama warga.', "Sebagian besar warga bertani padi dan palawija secara bergilir.\n\n*(Isi contoh.)*"],
            ['Wisata Alam Desa', 'wisata', 'Potensi wisata alam dan budaya yang dapat dikembangkan.', "Desa memiliki lanskap dan tradisi yang berpotensi menjadi daya tarik wisata.\n\n*(Isi contoh.)*"],
            ['Produk Unggulan Desa', 'produk_unggulan', 'Produk khas yang dihasilkan warga desa.', "Produk unggulan dikembangkan bersama BUMDes dan kelompok warga.\n\n*(Isi contoh.)*"],
        ];

        foreach ($items as $i => [$title, $category, $summary, $content]) {
            Potential::create([
                'title' => $title,
                'category' => $category,
                'summary' => $summary,
                'content' => $content,
                'status' => 'published',
                'sort_order' => $i + 1,
            ]);
        }
    }
}
