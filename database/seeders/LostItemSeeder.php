<?php

namespace Database\Seeders;

use App\Models\LostItem;
use Illuminate\Database\Seeder;

class LostItemSeeder extends Seeder
{
    public function run(): void
    {
        // [jenis, judul, keterangan, selesai?] - DATA CONTOH
        $items = [
            ['hilang', 'Dompet cokelat', 'Dompet kulit cokelat hilang di sekitar pasar desa. Mohon hubungi kantor desa bila menemukan.', false],
            ['ditemukan', 'Kunci motor dengan gantungan biru', 'Ditemukan di depan balai desa. Pemilik silakan mengambil di kantor desa dengan menyebutkan ciri-cirinya.', false],
            ['hilang', 'Tas sekolah hitam', 'Tertinggal di halte. Sudah kembali ke pemiliknya.', true],
        ];

        foreach ($items as $i => [$type, $title, $description, $done]) {
            LostItem::create([
                'type' => $type,
                'title' => $title,
                'description' => $description,
                'location' => 'Desa Klepu',
                'event_date' => now()->subDays($i + 1)->toDateString(),
                'contact' => 'Kantor Desa',
                'status' => 'published',
                'is_resolved' => $done,
                'resolved_at' => $done ? now() : null,
            ]);
        }
    }
}
