<?php

namespace App\Http\Controllers\Admin;

use App\Http\Controllers\Controller;
use App\Models\Gallery;
use App\Models\GalleryImage;
use App\Support\Media;
use App\Support\SiteCache;
use Illuminate\Http\RedirectResponse;

class GalleryImageController extends Controller
{
    public function destroy(GalleryImage $image): RedirectResponse
    {
        $gallery = $image->gallery;

        Media::delete($image->image);
        $image->delete(); // FK cover_image_id otomatis jadi NULL jika foto ini cover

        // Jika cover terhapus, pakai foto pertama yang tersisa
        if ($gallery && ! $gallery->fresh()->cover_image_id) {
            $next = GalleryImage::where('gallery_id', $gallery->id)->orderBy('sort_order')->orderBy('id')->first();
            if ($next) {
                $gallery->update(['cover_image_id' => $next->id]);
            }
        }

        SiteCache::flush();

        return back()->with('success', 'Foto dihapus.');
    }

    public function cover(Gallery $gallery, GalleryImage $image): RedirectResponse
    {
        abort_unless($image->gallery_id === $gallery->id, 404);

        $gallery->update(['cover_image_id' => $image->id]);
        SiteCache::flush();

        return back()->with('success', 'Cover album diperbarui.');
    }
}
