# Desa Mondokan - Panduan Pemasangan (semua tahap)

Paket ini berisi SELURUH file aplikasi (tahap 2-7 + Potensi Desa, Barang Hilang & Ditemukan, APBDes). Timpa ke project Laravel 12 yang baru.

## A. Pemasangan dari nol (XAMPP / lokal)
1. composer create-project laravel/laravel desa-mondokan
2. Ekstrak zip ini ke folder project, pilih "timpa semua file".
3. Buat database kosong `desa_mondokan` di phpMyAdmin, lalu edit `.env`:
     APP_NAME="Desa Mondokan"
     APP_URL=http://localhost:8000
     APP_LOCALE=id
     APP_FALLBACK_LOCALE=id
     APP_FAKER_LOCALE=id_ID
     DB_CONNECTION=mysql
     DB_DATABASE=desa_mondokan
4. config/app.php -> 'timezone' => 'Asia/Jakarta'
5. php.ini XAMPP (lalu restart Apache):
     upload_max_filesize = 8M
     post_max_size = 40M
     max_file_uploads = 20
     extension=gd        (dan extension=exif agar foto HP tidak miring)
6. php artisan storage:link
7. php artisan migrate:fresh --seed
8. php artisan serve  ->  http://localhost:8000   |   admin: /admin/login

Akun development (HANYA lokal, di production seeder ini otomatis dilewati):
  admin@desamondokan.test  / password  (role admin)
  editor@desamondokan.test / password  (role editor)

## B. Hak akses
- Admin  : semua menu.
- Editor : Berita (hanya milik sendiri), Pengumuman, Agenda, Galeri, Potensi Desa, Barang Hilang.
- Khusus admin: Kategori, Pemerintahan Desa, Profil Desa, Data Desa, Pengguna, Pengaturan, APBDes.

## C. Checklist production
 1. .env: APP_ENV=production, APP_DEBUG=false, APP_URL=https://domain-anda.id, php artisan key:generate
 2. .env: SESSION_SECURE_COOKIE=true, SESSION_LIFETIME=120
 3. Web root server menunjuk ke folder `public/` (bukan folder project)
 4. php artisan migrate --force      (JANGAN migrate:fresh; di production perintah itu otomatis diblokir)
 5. php artisan admin:create email@domain.id      (membuat admin, password diketik tersembunyi)
 6. php artisan storage:link
 7. php artisan config:cache && php artisan route:cache && php artisan view:cache
 8. Edit public/robots.txt: ganti domain dan hapus tanda # pada baris Sitemap
 9. Aktifkan HTTPS. Header keamanan + CSP aktif otomatis saat APP_DEBUG=false
    (uji dulu di lokal: CSP_ENABLED=true dan APP_DEBUG=false, buka semua halaman, cek Console browser)
10. Jadwalkan backup database + folder storage/app/public
11. Setelah mengubah kode di server: php artisan optimize:clear lalu ulangi langkah 7

Opsional (Apache, mempercepat muat ulang file statis) - tambahkan di public/.htaccess:
  <IfModule mod_expires.c>
    ExpiresActive On
    ExpiresByType text/css "access plus 1 month"
    ExpiresByType application/javascript "access plus 1 month"
    ExpiresByType image/webp "access plus 1 year"
    ExpiresByType image/svg+xml "access plus 1 year"
  </IfModule>
  <IfModule mod_deflate.c>
    AddOutputFilterByType DEFLATE text/html text/css application/javascript application/xml
  </IfModule>
CSS/JS sudah memakai ?v=waktu-ubah sehingga aman di-cache lama.

## D. Uji cepat setelah terpasang (±10 menit)
Publik : /  /berita  /berita?urut=populer  /kategori/ekonomi  /search?q=desa  /profil  /pemerintahan
         /informasi  /pengumuman  /agenda  /galeri  /galeri/musyawarah-pembangunan-desa  /sitemap.xml
         /halaman-ngawur (harus tampil 404 bergaya situs)
Admin  : login -> tambah berita + gambar -> terbitkan -> cek tampil di beranda;
         buat album + upload beberapa foto -> jadikan cover -> hapus satu foto;
         ubah Pengaturan (nama, logo) -> cek header/footer; login sebagai editor -> menu khusus admin tidak muncul
Responsif: DevTools (F12) -> mode perangkat, uji lebar 360, 390, 768, 1024, 1280.
  Cek tidak ada scroll horizontal - tempel di Console untuk menemukan elemen yang melebar:
  [...document.querySelectorAll('body *')].filter(e=>e.getBoundingClientRect().right>document.documentElement.clientWidth+1).map(e=>e.tagName+'.'+e.className).slice(0,20)
Log N+1: storage/logs/laravel.log - cari "Lazy loading terdeteksi" setelah membuka semua halaman.

## E. Perintah berguna
  php artisan admin:create email@domain.id   # buat admin / reset password
  php artisan optimize:clear                 # bersihkan semua cache
  php artisan route:list --path=admin        # daftar route admin


## D. Menu baru: Potensi, Barang Hilang & Ditemukan, APBDes
Publik : /potensi, /barang-hilang, /apbdes (menu navigasi sekarang berkelompok/dropdown).
Admin  : Potensi Desa, Barang Hilang (tombol "Tandai selesai"), APBDes (khusus admin).

APBDes = satu file PDF per tahun. Admin: menu APBDes > "Unggah APBDes" > isi tahun (mis. 2026), pilih PDF (maks. 8 MB),
status Terbit. Publik: /apbdes menampilkan daftar tahun, tiap tahun ada tombol "Unduh PDF"
(file terunduh bernama APBDes-Desa-Mondokan-2026.pdf). Tahun terbaru juga tampil di beranda.
Pastikan php artisan storage:link sudah dijalankan.

Sudah punya database dari tahap sebelumnya (JANGAN migrate:fresh, data akan hilang):
  php artisan migrate
  php artisan db:seed --class=PotentialSeeder     (opsional, contoh data)
  php artisan db:seed --class=LostItemSeeder      (opsional)
  php artisan optimize:clear
Migration 2026_10_06 membuang tabel rincian APBDes lama (budget_items) dan menambah kolom file. Instalasi baru: migrate:fresh --seed.
