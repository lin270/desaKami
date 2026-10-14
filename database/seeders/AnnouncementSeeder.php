<?php

namespace Database\Seeders;

use App\Models\Announcement;
use Illuminate\Database\Seeder;

class AnnouncementSeeder extends Seeder
{
    public function run(): void
    {
        // [judul, kategori, penting?, isi] - KONTEN CONTOH
        $items = [
            ['Jadwal Pelayanan Kantor Desa', 'Pelayanan', true, "Kantor desa melayani warga pada hari dan jam kerja. Mohon membawa berkas persyaratan yang lengkap.\n\n*(Isi contoh - sesuaikan dengan jadwal resmi.)*"],
            ['Pemberitahuan Kerja Bakti Mingguan', 'Umum', false, "Warga diharapkan hadir dalam kerja bakti di lingkungan masing-masing.\n\n*(Isi contoh.)*"],
            ['Jadwal Posyandu Bulan Ini', 'Kesehatan', false, "Posyandu dilaksanakan sesuai jadwal yang telah ditentukan. Mohon membawa buku KIA.\n\n*(Isi contoh.)*"],
            ['Pemberitahuan Pekerjaan Jalan Lingkungan', 'Pembangunan', true, "Selama pekerjaan berlangsung, pengguna jalan diharapkan berhati-hati dan mengikuti arahan petugas.\n\n*(Isi contoh.)*"],
            ['Informasi Pelatihan UMKM Desa', 'Umum', false, "Pelaku UMKM yang berminat dapat mendaftar melalui kantor desa.\n\n*(Isi contoh.)*"],
        ];

        foreach ($items as $i => [$title, $category, $important, $content]) {
            Announcement::create([
                'title' => $title,
                'category' => $category,
                'is_important' => $important,
                'content' => $content,
                'status' => 'published',
                'published_at' => now()->subHours(2)->subDays($i * 3),
            ]);
        }
    }
}
