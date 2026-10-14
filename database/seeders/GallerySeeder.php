<?php

namespace Database\Seeders;

use App\Models\Gallery;
use Illuminate\Database\Seeder;

class GallerySeeder extends Seeder
{
    public function run(): void
    {
        // [judul, deskripsi, jumlah foto] - foto memakai placeholder SVG lokal
        $albums = [
            ['Musyawarah Pembangunan Desa', 'Dokumentasi musyawarah bersama perwakilan warga (foto contoh).', 5],
            ['Kerja Bakti Bersama', 'Warga bergotong royong menjaga kebersihan lingkungan (foto contoh).', 4],
            ['Kegiatan Posyandu', 'Pelayanan kesehatan ibu dan anak di desa (foto contoh).', 4],
            ['Produk Unggulan Desa', 'Hasil UMKM dan pertanian warga (foto contoh).', 5],
        ];

        foreach ($albums as $a => [$title, $description, $count]) {
            $gallery = Gallery::create([
                'title' => $title,
                'description' => $description,
                'taken_at' => now()->subDays(($a + 1) * 7)->toDateString(),
                'status' => 'published',
            ]);

            $first = null;

            for ($i = 0; $i < $count; $i++) {
                $image = $gallery->images()->create([
                    'image' => 'images/placeholder/foto-'.((($a + $i) % 6) + 1).'.svg',
                    'caption' => $title.' - foto '.($i + 1),
                    'sort_order' => $i,
                ]);

                $first ??= $image;
            }

            // Foto pertama menjadi cover album
            $gallery->update(['cover_image_id' => $first->id]);
        }
    }
}
