<?php

namespace App\Support;

use Illuminate\Http\UploadedFile;
use Illuminate\Support\Facades\Storage;
use Illuminate\Support\Str;

class Media
{
    /**
     * Ubah path gambar di database menjadi URL.
     * - kosong            -> gambar placeholder
     * - diawali "images/" -> file di folder public (placeholder seeder)
     * - selain itu        -> file upload di storage/app/public
     */
    public static function url(?string $path, ?string $fallback = 'images/placeholder/foto-1.svg'): ?string
    {
        if (blank($path)) {
            return $fallback ? asset($fallback) : null;
        }

        if (str_starts_with($path, 'images/')) {
            return asset($path);
        }

        return asset('storage/'.$path);
    }

    /**
     * Simpan gambar upload ke disk "public" dalam folder $dir.
     * Jika ekstensi GD tersedia: gambar diperkecil (maks. $maxWidth px) dan
     * di-encode ulang ke WebP/JPEG. Ini menghemat ukuran dan membuang
     * metadata/konten tersembunyi dari file asli.
     * Mengembalikan path relatif untuk disimpan di database.
     */
    public static function storeImage(UploadedFile $file, string $dir, int $maxWidth = 1600): string
    {
        $dir = trim($dir, '/');
        $realPath = $file->getRealPath();

        if (function_exists('imagecreatefromstring') && $realPath !== false) {
            $raw = @file_get_contents($realPath);
            $img = $raw !== false ? @imagecreatefromstring($raw) : false;

            if ($img !== false) {
                $img = self::applyOrientation($img, $realPath);

                if (imagesx($img) > $maxWidth) {
                    $scaled = imagescale($img, $maxWidth);
                    if ($scaled !== false) {
                        $img = $scaled;
                    }
                }

                imagepalettetotruecolor($img);
                imagealphablending($img, false);
                imagesavealpha($img, true);

                ob_start();
                if (function_exists('imagewebp')) {
                    imagewebp($img, null, 82);
                    $ext = 'webp';
                } else {
                    imagejpeg($img, null, 85);
                    $ext = 'jpg';
                }
                $binary = ob_get_clean();

                if (is_string($binary) && $binary !== '') {
                    $path = $dir.'/'.Str::random(40).'.'.$ext;
                    Storage::disk('public')->put($path, $binary);

                    return $path;
                }
            }
        }

        // Fallback: simpan file asli (sudah divalidasi sebagai gambar)
        return $file->store($dir, 'public');
    }

    /** Hapus file upload. Placeholder bawaan (images/...) tidak pernah dihapus. */
    public static function delete(?string $path): void
    {
        if (blank($path) || str_starts_with($path, 'images/')) {
            return;
        }

        Storage::disk('public')->delete($path);
    }

    /**
     * Putar gambar sesuai tag orientasi EXIF. Tanpa ini, foto dari HP yang
     * di-encode ulang oleh GD tampil miring karena metadata EXIF terbuang.
     */
    private static function applyOrientation($img, string $path)
    {
        if (! function_exists('exif_read_data')) {
            return $img;
        }

        $exif = @exif_read_data($path);
        $orientation = (int) ($exif['Orientation'] ?? 1);

        $angle = match ($orientation) {
            3 => 180,
            6 => -90,
            8 => 90,
            default => 0,
        };

        if ($angle !== 0) {
            $rotated = imagerotate($img, $angle, 0);
            if ($rotated !== false) {
                return $rotated;
            }
        }

        return $img;
    }
}
