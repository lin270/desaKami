<?php

namespace Database\Seeders;

use App\Models\Category;
use Illuminate\Database\Seeder;

class CategorySeeder extends Seeder
{
    public function run(): void
    {
        $categories = [
            'Pemerintahan' => 'Kabar seputar penyelenggaraan pemerintahan desa.',
            'Desa' => 'Informasi umum tentang Desa Mondokan.',
            'Masyarakat' => 'Cerita dan kegiatan warga.',
            'Kegiatan' => 'Dokumentasi kegiatan di lingkungan desa.',
            'Pembangunan' => 'Perkembangan pembangunan dan infrastruktur desa.',
            'Ekonomi' => 'UMKM, pertanian, dan potensi ekonomi desa.',
            'Pendidikan' => 'Kabar dunia pendidikan dan literasi.',
            'Kesehatan' => 'Layanan dan informasi kesehatan masyarakat.',
            'Pengumuman' => 'Pemberitahuan resmi dari pemerintah desa.',
        ];

        foreach ($categories as $name => $description) {
            Category::create([
                'name' => $name,
                'description' => $description,
            ]);
        }
    }
}
