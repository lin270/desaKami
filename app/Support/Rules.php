<?php

namespace App\Support;

use Illuminate\Validation\Rule;

/** Aturan validasi bersama + pesan berbahasa Indonesia untuk form admin. */
class Rules
{
    /** Slug opsional (kosong = dibuat otomatis dari judul). */
    public static function slug(string $table, ?int $ignoreId = null): array
    {
        return [
            'nullable',
            'max:200',
            'regex:/^[a-z0-9]+(?:-[a-z0-9]+)*$/',
            Rule::unique($table, 'slug')->ignore($ignoreId),
        ];
    }

    /** Upload gambar: hanya jpg/png/webp (SVG sengaja ditolak), maks. 4 MB. */
    public static function image(): array
    {
        return ['nullable', 'image', 'mimes:jpg,jpeg,png,webp', 'max:4096'];
    }

    public static function messages(): array
    {
        return [
            'required' => ':attribute wajib diisi.',
            'string' => ':attribute harus berupa teks.',
            'max.string' => ':attribute maksimal :max karakter.',
            'max.file' => 'Ukuran :attribute maksimal :max KB.',
            'max.array' => ':attribute maksimal :max berkas.',
            'min.string' => ':attribute minimal :min karakter.',
            'email' => 'Format :attribute tidak valid.',
            'unique' => ':attribute sudah digunakan.',
            'exists' => ':attribute yang dipilih tidak valid.',
            'image' => ':attribute harus berupa gambar.',
            'mimes' => ':attribute harus berformat :values.',
            'date' => ':attribute bukan tanggal yang valid.',
            'date_format' => 'Format :attribute tidak valid.',
            'in' => ':attribute yang dipilih tidak valid.',
            'confirmed' => 'Konfirmasi :attribute tidak cocok.',
            'regex' => 'Format :attribute tidak valid (huruf kecil, angka, dan tanda hubung).',
            'url' => ':attribute harus berupa URL lengkap (diawali https://).',
            'boolean' => ':attribute tidak valid.',
            'integer' => ':attribute harus berupa angka bulat.',
            'array' => ':attribute tidak valid.',
            'after' => ':attribute harus setelah jam mulai.',
            'uploaded' => 'Unggahan :attribute gagal. Periksa batas upload_max_filesize di php.ini.',
        ];
    }

    public static function attributes(): array
    {
        return [
            'title' => 'Judul',
            'slug' => 'Slug',
            'name' => 'Nama',
            'description' => 'Deskripsi',
            'category_id' => 'Kategori',
            'author_id' => 'Penulis',
            'thumbnail' => 'Gambar utama',
            'image' => 'Gambar',
            'images' => 'Foto',
            'images.*' => 'Foto',
            'excerpt' => 'Ringkasan',
            'content' => 'Isi',
            'status' => 'Status',
            'published_at' => 'Waktu terbit',
            'is_featured' => 'Berita utama',
            'is_important' => 'Penting',
            'is_active' => 'Status aktif',
            'category' => 'Kategori',
            'date' => 'Tanggal',
            'start_time' => 'Jam mulai',
            'end_time' => 'Jam selesai',
            'location' => 'Lokasi',
            'taken_at' => 'Tanggal dokumentasi',
            'position' => 'Jabatan',
            'group' => 'Kelompok',
            'photo' => 'Foto',
            'phone' => 'Telepon',
            'sort_order' => 'Urutan',
            'key' => 'Kunci',
            'label' => 'Label',
            'value' => 'Nilai',
            'unit' => 'Satuan',
            'email' => 'Email',
            'password' => 'Password',
            'role' => 'Role',
            'about' => 'Tentang desa',
            'history' => 'Sejarah',
            'vision' => 'Visi',
            'mission' => 'Misi',
            'area_description' => 'Deskripsi wilayah',
            'general_info' => 'Informasi umum',
            'hero_image' => 'Gambar sampul',
            'site_name' => 'Nama situs',
            'site_tagline' => 'Subjudul',
            'address' => 'Alamat',
            'services_hours' => 'Jam pelayanan',
            'facebook' => 'Facebook',
            'instagram' => 'Instagram',
            'youtube' => 'YouTube',
            'logo' => 'Logo',
            'summary' => 'Ringkasan',
            'contact' => 'Kontak',
            'type' => 'Jenis',
            'event_date' => 'Tanggal kejadian',
            'year' => 'Tahun',
            'notes' => 'Catatan',
            'amount_plan' => 'Anggaran',
            'amount_realized' => 'Realisasi',
        ];
    }
}
