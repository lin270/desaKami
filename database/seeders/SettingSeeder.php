<?php

namespace Database\Seeders;

use App\Models\Setting;
use Illuminate\Database\Seeder;

class SettingSeeder extends Seeder
{
    public function run(): void
    {
        // Identitas dasar. Alamat, telepon, dan email sengaja dikosongkan: isi data resmi lewat menu Pengaturan di panel admin.
        $settings = [
            'site_name' => 'DESA KLEPU',
            'site_tagline' => 'Website Resmi Desa Klepu',
            'address' => '',
            'phone' => '',
            'email' => '',
            'services_hours' => 'Senin - Jumat, 08.00 - 15.00 WIB',
            'facebook' => '',
            'instagram' => '',
            'youtube' => '',
            'logo' => '',
        ];

        foreach ($settings as $key => $value) {
            Setting::write($key, $value);
        }
    }
}
