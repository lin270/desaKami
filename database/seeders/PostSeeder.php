<?php

namespace Database\Seeders;

use App\Models\Category;
use App\Models\Post;
use App\Models\User;
use Illuminate\Database\Seeder;

class PostSeeder extends Seeder
{
    public function run(): void
    {
        $categories = Category::pluck('id', 'name');
        $authors = User::orderBy('id')->pluck('id')->all();

        // [judul, kategori, ringkasan] - SEMUA KONTEN CONTOH, ganti lewat panel admin.
        $items = [
            ['Pemerintah Desa Klepu Gelar Musyawarah Pembangunan', 'Pembangunan', 'Perangkat desa dan perwakilan warga berkumpul membahas prioritas pembangunan desa untuk periode mendatang.'],
            ['Warga Klepu Kerja Bakti Bersihkan Saluran Irigasi', 'Kegiatan', 'Puluhan warga bergotong royong membersihkan saluran air agar aliran ke lahan pertanian tetap lancar.'],
            ['Posyandu Balita Rutin Digelar Setiap Bulan', 'Kesehatan', 'Kegiatan penimbangan dan pemantauan tumbuh kembang balita berjalan rutin dengan dukungan kader desa.'],
            ['Pelayanan Administrasi Desa Kini Lebih Tertib dan Cepat', 'Pemerintahan', 'Kantor desa merapikan alur pelayanan agar warga lebih mudah mengurus surat dan dokumen.'],
            ['UMKM Klepu Dorong Produk Lokal Masuk Pasar Digital', 'Ekonomi', 'Pelaku usaha kecil di desa mulai memanfaatkan media digital untuk memperkenalkan produk unggulan.'],
            ['Pelatihan Kelompok Tani untuk Meningkatkan Hasil Panen', 'Ekonomi', 'Kelompok tani mengikuti pelatihan pengelolaan lahan dan pemupukan yang lebih efisien.'],
            ['Pembangunan Jalan Lingkungan Memasuki Tahap Pengerjaan', 'Pembangunan', 'Pengerjaan jalan lingkungan dimulai setelah melalui tahap perencanaan bersama warga.'],
            ['Sosialisasi Pencegahan Demam Berdarah di Lingkungan Desa', 'Kesehatan', 'Warga diajak menjaga kebersihan lingkungan dan rutin menguras tempat penampungan air.'],
            ['Perpustakaan Desa Buka Kegiatan Literasi Anak', 'Pendidikan', 'Anak-anak desa diajak membaca dan bercerita bersama dalam kegiatan literasi akhir pekan.'],
            ['Karang Taruna Klepu Gelar Olahraga Antar Dusun', 'Masyarakat', 'Pemuda dari berbagai dusun berkumpul dalam pertandingan persahabatan yang berlangsung meriah.'],
            ['Jadwal Layanan Kantor Desa Selama Bulan Ini', 'Pengumuman', 'Pemerintah desa menginformasikan jadwal dan jam pelayanan kantor desa untuk warga.'],
            ['Penyaluran Bantuan Dilakukan Secara Terbuka dan Tertib', 'Desa', 'Pemerintah desa menekankan pentingnya data yang jelas dan proses penyaluran yang terbuka.'],
            ['Rapat Koordinasi Perangkat Desa Bahas Program Kerja', 'Pemerintahan', 'Perangkat desa menyamakan langkah dalam pelaksanaan program kerja yang telah disepakati.'],
            ['Lomba Kebersihan Antar RT Meriahkan Lingkungan Desa', 'Kegiatan', 'Setiap RT berlomba menata lingkungan agar bersih, asri, dan nyaman bagi seluruh warga.'],
            ['Dukungan Pendidikan bagi Pelajar Desa Klepu', 'Pendidikan', 'Pemerintah desa mengajak warga turut mendukung pelajar agar dapat melanjutkan pendidikan.'],
        ];

        foreach ($items as $i => [$title, $category, $excerpt]) {
            Post::create([
                'category_id' => $categories[$category],
                'author_id' => $authors[$i % count($authors)],
                'title' => $title,
                'thumbnail' => 'images/placeholder/foto-'.(($i % 6) + 1).'.svg',
                'excerpt' => $excerpt,
                'content' => $this->content($excerpt),
                'status' => 'published',
                'is_featured' => $i === 0,
                // Selalu di masa lalu agar langsung tampil setelah seeding
                'published_at' => now()->subHours(2)->subDays($i * 2),
                'views' => random_int(30, 950),
            ]);
        }
    }

    private function content(string $excerpt): string
    {
        return $excerpt."\n\n"
            ."Kegiatan ini merupakan bagian dari upaya pemerintah desa bersama masyarakat untuk meningkatkan pelayanan dan kebersamaan warga. "
            ."Teks pada halaman ini adalah **konten contoh** yang dapat diganti melalui panel admin.\n\n"
            ."Warga yang ingin mengetahui informasi lebih lanjut dapat menghubungi kantor desa pada jam pelayanan.\n\n"
            ."## Catatan\n\n"
            ."Berita ini hanya data dummy untuk keperluan pengembangan tampilan website dan bukan informasi resmi.";
    }
}
