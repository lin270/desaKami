<?php

use Illuminate\Database\Migrations\Migration;
use Illuminate\Support\Facades\Cache;
use Illuminate\Support\Facades\DB;
use Illuminate\Support\Facades\Schema;

/**
 * Menyelaraskan identitas bawaan dengan nama yang benar: DESA KLEPU.
 *
 * Aman untuk data yang sudah ada: hanya mengubah nilai yang MASIH berisi
 * teks bawaan lama persis (mis. "DESA MONDOKAN"). Nilai yang sudah diisi
 * pengelola tidak disentuh, dan tidak ada tabel/kolom yang diubah atau dihapus.
 */
return new class extends Migration
{
    public function up(): void
    {
        if (! Schema::hasTable('settings')) {
            return;
        }

        $replace = [
            'site_name' => [['DESA MONDOKAN', 'Desa Mondokan'], 'DESA KLEPU'],
            'site_tagline' => [['Website Resmi Desa Mondokan'], 'Website Resmi Desa Klepu'],
            // Teks contoh dari seeder lama: dikosongkan agar tidak tampil di footer
            'address' => [['Alamat kantor Desa Mondokan (isi alamat resmi di Pengaturan)'], ''],
            'phone' => [['08xx-xxxx-xxxx'], ''],
            'email' => [['info@desamondokan.test'], ''],
        ];

        foreach ($replace as $key => [$olds, $new]) {
            DB::table('settings')->where('key', $key)->whereIn('value', $olds)->update(['value' => $new]);
        }

        try {
            Cache::forget('settings.all');
        } catch (\Throwable $e) {
            // cache belum siap; pengaturan akan dimuat ulang otomatis
        }
    }

    public function down(): void
    {
        // Tidak ada yang dikembalikan: perubahan hanya merapikan teks bawaan.
    }
};
