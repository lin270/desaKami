<?php

namespace Database\Seeders;

use App\Models\VillageProfile;
use Illuminate\Database\Seeder;

class VillageProfileSeeder extends Seeder
{
    public function run(): void
    {
        // TEKS CONTOH - bukan data resmi. Ganti lewat menu Profil Desa di panel admin.
        VillageProfile::create([
            'about' => "Desa Mondokan adalah desa yang berkomitmen melayani masyarakat dengan pemerintahan yang terbuka dan mudah diakses.\n\n*(Teks contoh - isi dengan profil resmi desa.)*",
            'history' => "Sejarah Desa Mondokan akan diisi sesuai catatan resmi dan cerita yang dihimpun dari tokoh masyarakat.\n\n*(Teks contoh - belum merupakan fakta sejarah desa.)*",
            'vision' => 'Mewujudkan Desa Mondokan yang maju, mandiri, dan sejahtera. (Contoh - ganti dengan visi resmi.)',
            'mission' => "Meningkatkan kualitas pelayanan publik kepada masyarakat.\nMendorong ekonomi desa melalui UMKM dan pertanian.\nMenjaga kelestarian lingkungan dan gotong royong warga.\nMeningkatkan keterbukaan informasi desa.\n(Contoh - ganti dengan misi resmi.)",
            'area_description' => 'Deskripsi wilayah desa, batas wilayah, dan pembagian dusun akan diisi sesuai data resmi. (Teks contoh.)',
            'general_info' => 'Informasi umum desa lainnya dapat ditambahkan di sini. (Teks contoh.)',
        ]);
    }
}
