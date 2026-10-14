# Fitur Peta Desa dan Kalender Warga

## Perubahan yang ditambahkan

- Halaman publik `/peta-desa` dengan peta interaktif Leaflet + OpenStreetMap, pencarian, filter kategori, daftar lokasi, detail marker, dan tautan petunjuk arah.
- Pengelolaan lokasi peta di dashboard admin pada menu **Lokasi Peta Desa**. Fitur ini dibatasi untuk admin.
- Halaman `/agenda` diubah menjadi **Kalender Warga** dengan tampilan kalender bulanan, navigasi bulan, tombol Hari Ini, detail agenda per tanggal, serta daftar agenda mendatang.
- Navigasi publik dan judul agenda pada halaman utama diperbarui. Google Maps footer yang sudah ada tidak dihapus atau diganti.
- Ditambahkan `config/view.php` untuk konfigurasi path Blade views yang tidak ada di salinan proyek yang diterima.

## Cara memasang

1. Cadangkan proyek dan database terlebih dahulu.
2. Salin file proyek hasil pembaruan ke proyek lokal. Jangan menimpa file `.env` milik sendiri.
3. Pastikan database pada `.env` masih mengarah ke database proyek yang benar.
4. Jalankan di terminal pada folder proyek:

   ```bash
   php artisan migrate
   php artisan optimize:clear
   ```

5. Login sebagai admin. Buka menu **Lokasi Peta Desa** untuk menambahkan tempat.
6. Masukkan koordinat latitude dan longitude yang akurat untuk setiap lokasi. Koordinat dapat diperoleh dari lokasi yang dipilih di Google Maps; verifikasi sebelum menerbitkan. Belum ada marker lokasi yang dikarang atau ditambahkan otomatis.
7. Buka `/peta-desa` untuk memeriksa peta dan `/agenda` untuk memeriksa kalender.

## Catatan

- Tile peta memerlukan koneksi internet dan menggunakan layanan OpenStreetMap. Ikuti kebijakan penggunaan tile OpenStreetMap jika trafik website meningkat.
- Kalender mengambil data dari agenda yang sudah ada dan berstatus `published`; data lama tidak perlu dimasukkan ulang.
- Kalender memuat agenda dari enam bulan sebelumnya hingga satu tahun ke depan agar pengguna dapat menelusuri tanggal. Daftar “Agenda Mendatang” tetap menampilkan agenda hari ini dan seterusnya.
- Data lokasi yang dimasukkan harus nyata dan koordinatnya perlu diverifikasi oleh pengelola desa.
