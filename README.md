# Desa Klepu - Website Desa (Laravel 12)

Portal informasi Desa Klepu: profil, berita, peta, galeri, pengumuman, kalender warga,
barang hilang & ditemukan, APBDes, serta dashboard admin. Redesign bergaya *cinematic rural editorial*
di atas proyek Laravel yang sudah ada (tidak dibuat ulang dari nol).

## Instalasi

Kebutuhan: PHP 8.2+, Composer, database (MySQL sesuai proyek asal, atau SQLite).

```bash
cp .env.example .env          # lalu isi koneksi database & APP_URL
composer install              # folder vendor sudah disertakan; jalankan bila perlu
php artisan key:generate      # hanya jika APP_KEY masih kosong
php artisan migrate           # AMAN: tidak menghapus data. Termasuk migration identitas (lihat bawah)
php artisan storage:link      # jika public/storage belum terhubung
php artisan optimize:clear
php artisan serve
```

Pengguna baru: `php artisan db:seed` (data contoh) atau `php artisan` perintah `CreateAdmin` yang sudah ada.
**Jangan menimpa `.env` milik Anda.** ZIP ini sengaja tidak memuat `.env` maupun `database/database.sqlite`.

> Setelah `migrate`, nilai pengaturan yang masih berisi teks bawaan lama ("DESA MONDOKAN", alamat/telepon/email contoh)
> otomatis dirapikan. Nilai yang sudah Anda isi sendiri tidak disentuh.

## Sistem desain

| Elemen | Nilai |
|---|---|
| Forest green | `#293B30` (identitas) |
| Deep olive | `#414A38` |
| Warm ivory | `#F5F1E8` (latar) |
| Sand beige | `#D5C7AA` (aksen) |
| Sage | `#87907B` |
| Charcoal | `#292B27` (teks) |

Tipografi: serif editorial (Iowan Old Style / Palatino / Georgia) untuk judul, sans-serif sistem untuk isi.
Memakai *font stack* sistem, jadi tidak ada ketergantungan font eksternal dan aman terhadap CSP.

File utama: `public/css/app.css` (publik), `public/css/admin.css`, `public/css/auth.css`,
`resources/views/layouts/app.blade.php`, `layouts/admin.blade.php`, `home.blade.php`.

## Pembaruan terbaru
- **Peta Desa gaya peta daring:** peta layar penuh, panel pencarian + chip kategori, daftar dengan foto, kartu detail (foto besar, alamat, deskripsi, tombol Rute dan OpenStreetMap), pin kustom, bottom-sheet di HP.
- **Foto per lokasi:** Admin > Lokasi Peta Desa kini bisa unggah/ganti/hapus foto. Migration baru `2026_10_16_000002_add_image_to_village_locations_table.php` (kolom `image` nullable, aman untuk data lama). **Jalankan `php artisan migrate`.**
- **Kalender Warga:** tampilan kalender dinding (jilid, grid bulanan Senin-Minggu, penanda hari ini, label agenda di tiap tanggal, panel agenda per tanggal, navigasi papan ketik).
- **Berita gaya surat kabar:** kepala halaman bertanggal, tab kategori, berita utama + pendamping, daftar baris dengan byline dan waktu baca, pencarian; detail artikel dengan judul besar, deck, byline, bagikan (WhatsApp/Facebook/salin tautan), huruf awal besar, "Baca juga".
- **Bug:** lapisan "Memuat peta..." menutupi peta terus karena `display` mengalahkan atribut `hidden` (diperbaiki, plus aturan `[hidden]` global). Checkbox "Tampilkan di Peta Desa" kini benar-benar bisa menyembunyikan lokasi saat tidak dicentang.

## Revisi peta & pemerintahan
- **Peta:** kotak kiri selalu menampilkan semua lokasi (tidak berubah saat lokasi diklik). Klik lokasi: peta zoom ke marker, lalu **foto muncul sebagai popup tepat di atas marker** (foto, kategori, nama, alamat, tombol Rute). Di HP, daftar otomatis diciutkan agar popup terlihat.
- **Pemerintahan:** kini berupa bagan struktur organisasi (Kepala Desa > Sekretaris Desa > cabang Kepala Urusan / Kepala Seksi / Kepala Dusun) dengan garis penghubung; di HP menjadi pohon vertikal. Bagan dibangun dari kelompok jabatan pada data admin; kelompok yang kosong tidak ditampilkan.

## Galeri bergaya album
- **Daftar album:** tiap album berupa tumpukan foto ala polaroid (sampul + 2 foto di belakangnya, selotip, keterangan jumlah foto) di atas latar kertas; hover/fokus membuka tumpukan.
- **Halaman album:** sampul album berbingkai emas, foto ditempel miring seperti polaroid dengan keterangan; lightbox dengan bingkai putih, penghitung (3 / 12), panah papan ketik, dan geser jari di HP.

## Ruang Aspirasi (fitur baru)
- **Publik (`/aspirasi`):** papan buletin gabus dengan catatan tempel berwarna dan pin; tanpa login. Form "Tulis aspirasi Anda" di bawah papan (nama opsional, isi 10-500 karakter). Setelah dikirim, aspirasi langsung dipasang di papan. Balasan admin tampil sebagai kertas putih yang dijepit di catatan itu.
- **Perlindungan:** CSRF, batas 5 kiriman per 10 menit per IP, kolom jebakan bot (honeypot), tautan dilarang, input di-escape saat ditampilkan, hanya hash IP yang disimpan (bukan IP asli), tidak meminta telepon/email.
- **Admin (Aspirasi Warga):** filter belum/sudah dibalas, pencarian, balas/ubah balasan (kosongkan untuk menghapus balasan), dan hapus aspirasi yang melanggar (soft delete: hilang dari papan, tetap tersimpan sebagai arsip). Dapat diakses role admin dan editor. Dashboard menampilkan jumlah aspirasi belum dibalas.
- **Database:** migration baru `2026_10_16_000003_create_aspirations_table.php` (tabel baru, tidak mengubah tabel lama). **Jalankan `php artisan migrate`.**
- Karena aspirasi langsung tayang tanpa moderasi awal, pantau secara berkala dan hapus yang melanggar.

## Yang diubah

**Publik**
- Navbar baru (transparan di atas hero, solid saat scroll, menu mobile layar penuh, dropdown Profil Desa & Layanan, *active state*).
- Beranda: hero sinematik, pengantar asimetris, berita editorial (data dari database), Jelajahi Desa (potensi + ringkasan peta), galeri mosaik, layanan & partisipasi warga, footer baru.
- Kepala halaman dalam, kartu berita, sidebar, pagination, profil, pemerintahan, potensi, galeri, pengumuman, barang hilang, APBDes, error pages: ikut palet & tipografi baru lewat sistem CSS bersama.
- Peta Desa: Leaflet + OpenStreetMap, pencarian, filter kategori, daftar tersinkron marker, tombol "Tampilkan semua", state memuat/error/kosong.
- Kalender Warga: tampilan disesuaikan palet baru.

**Admin & autentikasi**
- Login baru (panel ilustrasi + form, tampilkan/sembunyikan password; validasi & throttle server tidak diubah).
- Sidebar berkelompok dan dapat diciutkan di desktop, breadcrumb, notifikasi dengan tombol tutup/auto-hilang, tabel dapat digeser di HP.
- Dashboard: statistik nyata dari database (berita, pengumuman, agenda + yang mendatang, lokasi peta, laporan barang aktif, album, views).

## Bug yang diperbaiki
1. **Peta Desa & Kalender Warga tidak berfungsi di produksi.** Skrip dan gaya ditulis inline, sedangkan `SecurityHeaders` memakai CSP `script-src 'self'` saat `APP_DEBUG=false`. Kode dipindah ke `public/js/peta.js` dan `public/js/kalender.js`; CSP khusus `/peta-desa` kini mengizinkan `unpkg.com` (Leaflet) dan `tile.openstreetmap.org` saja.
2. Nama identitas lama "Mondokan" pada default `SiteComposer`, JSON-LD, nama file unduhan APBDes, seeder, placeholder SVG, dan README diganti ke "Klepu" (nama tabel/kolom/route tidak diubah).
3. Footer tidak lagi menampilkan telepon/email/alamat contoh dari seeder; bagian kosong disembunyikan.
4. Peta: koordinat tidak valid disembunyikan dari daftar, bukan dipaksa tampil.

## Perubahan database
Satu migration baru, **non-destruktif**: `2026_10_16_000001_rename_default_site_identity.php`.
Hanya memperbarui baris `settings` yang masih berisi teks bawaan lama persis. Tidak ada tabel/kolom ditambah atau dihapus.

## Yang perlu Anda isi / konfirmasi
- **Foto hero lanskap.** Foto proyek yang ada adalah dokumentasi kegiatan/kantor, bukan lanskap. Selama foto hero belum diunggah (Admin > Profil Desa), hero memakai *ilustrasi vektor orisinal* (diberi keterangan "Ilustrasi"). Ganti dengan foto lanskap asli Desa Klepu.
- Alamat, telepon, email, jam layanan, media sosial: isi di Admin > Pengaturan.
- Lokasi peta + koordinat: Admin > Lokasi Peta Desa. Tidak ada marker fiktif.
- Halaman **Informasi Layanan** memuat *persyaratan administrasi contoh* (sudah berlabel contoh di halaman). Ganti dengan ketentuan resmi desa. Website ini **tidak** menyediakan layanan surat digital.
- Statistik beranda berasal dari menu Data Desa; pastikan nilainya data resmi.
- Tile peta dan Leaflet dimuat dari internet (unpkg & OpenStreetMap).

## Catatan database lokal
Jika database lokal Anda masih bernama `desa_mondokan`, **biarkan** `.env` Anda apa adanya. Nama database tidak perlu diganti
(mengganti nama hanya diperlukan untuk instalasi baru).

## Pengujian

**Sudah dijalankan (statis, di lingkungan pengerjaan yang tidak memiliki PHP maupun browser):**
- `node --check` pada semua file JS (`app`, `admin`, `auth`, `galeri`, `kalender`, `peta`): lolos.
- Keseimbangan kurung `{}` pada `app.css`, `admin.css`, `auth.css`: seimbang.
- Keseimbangan direktif Blade (`@if/@foreach/@forelse/@push`) pada seluruh view: seimbang.
- Pencarian sisa nama lama "Mondokan" di kode, view, seeder, CSS/JS: tersisa 0 (di luar `.env` Anda dan `composer.lock`).
- Tidak ada skrip inline yang dieksekusi di view publik (kecuali blok JSON data kalender, yang bukan skrip eksekusi).

**BELUM diuji (tidak dapat dijalankan di sini) - mohon dicek manual:**
`php artisan route:list`, `php artisan view:cache` (akan menangkap kesalahan sintaks Blade), `php artisan migrate`,
tampilan di browser (mobile 360-390 px, tablet 768 px, desktop 1366-1440 px), menu mobile, dropdown navbar,
peta + marker + filter, kalender, login, CRUD admin, upload gambar, dan `storage/logs/laravel.log`.

Langkah cepat:
1. `php artisan view:cache && php artisan route:list` - tidak boleh ada error.
2. Buka `/`, `/profil`, `/berita`, `/peta-desa`, `/agenda`, `/galeri`, `/pengumuman`, `/barang-hilang`, `/apbdes`, `/admin/login`.
3. Uji dengan `APP_DEBUG=false` (CSP aktif) khusus untuk `/peta-desa` dan `/agenda`.
4. Login admin, buka Dashboard dan tiap menu sidebar; tambah/ubah/hapus satu data uji.
