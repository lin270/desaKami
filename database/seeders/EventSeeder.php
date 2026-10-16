<?php

namespace Database\Seeders;

use App\Models\Event;
use Illuminate\Database\Seeder;

class EventSeeder extends Seeder
{
    public function run(): void
    {
        // [judul, +hari dari sekarang, mulai, selesai, lokasi, deskripsi] - DATA CONTOH
        $items = [
            ['Kerja Bakti Lingkungan', 3, '06:30', '09:00', 'Lingkungan desa (lokasi contoh)', 'Kerja bakti membersihkan fasilitas umum bersama warga.'],
            ['Musyawarah Desa', 5, '09:00', '12:00', 'Balai Desa Klepu', 'Pembahasan rencana kerja dan prioritas pembangunan bersama perwakilan warga.'],
            ['Posyandu Balita', 8, '08:00', '11:00', 'Pos pelayanan (lokasi contoh)', 'Penimbangan dan pemantauan tumbuh kembang balita.'],
            ['Pertemuan Karang Taruna', 12, '19:30', '21:30', 'Balai Desa Klepu', 'Pertemuan rutin pemuda untuk menyusun kegiatan bersama.'],
            ['Pameran Produk Lokal Desa', 20, '08:00', '16:00', 'Lapangan desa (lokasi contoh)', 'Pameran produk UMKM dan hasil pertanian desa.'],
        ];

        foreach ($items as [$title, $plusDays, $start, $end, $location, $description]) {
            Event::create([
                'title' => $title,
                'description' => $description,
                'date' => now()->addDays($plusDays)->toDateString(),
                'start_time' => $start,
                'end_time' => $end,
                'location' => $location,
                'status' => 'published',
            ]);
        }
    }
}
