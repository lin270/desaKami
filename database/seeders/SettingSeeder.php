<?php

namespace Database\Seeders;

use App\Models\Setting;
use Illuminate\Database\Seeder;

class SettingSeeder extends Seeder
{
    public function run(): void
    {
        // DATA CONTOH - ganti lewat menu Pengaturan di panel admin.
        $settings = [
            'site_name' => 'DESA MONDOKAN',
            'site_tagline' => 'Website Resmi Desa Mondokan',
            'address' => 'Alamat kantor Desa Mondokan (isi alamat resmi di Pengaturan)',
            'phone' => '08xx-xxxx-xxxx',
            'email' => 'info@desamondokan.test',
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
