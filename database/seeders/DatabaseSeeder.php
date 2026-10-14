<?php

namespace Database\Seeders;

use Illuminate\Database\Seeder;

class DatabaseSeeder extends Seeder
{
    /**
     * Jalankan: php artisan migrate:fresh --seed
     * Urutan penting: user & kategori harus ada sebelum berita.
     */
    public function run(): void
    {
        $this->call([
            SettingSeeder::class,
            UserSeeder::class,
            CategorySeeder::class,
            PostSeeder::class,
            AnnouncementSeeder::class,
            EventSeeder::class,
            GallerySeeder::class,
            OfficialSeeder::class,
            VillageProfileSeeder::class,
            VillageStatisticSeeder::class,
            PotentialSeeder::class,
            LostItemSeeder::class,
        ]);
    }
}
