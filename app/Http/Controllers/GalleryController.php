<?php

namespace App\Http\Controllers;

use App\Models\Gallery;
use Illuminate\Http\Request;
use Illuminate\View\View;

class GalleryController extends Controller
{
    /** GET /galeri */
    public function index(): View
    {
        $galleries = Gallery::published()
            ->with(['cover', 'images'])
            ->withCount('images')
            ->orderByDesc('taken_at')
            ->orderByDesc('id')
            ->paginate(9)
            ->onEachSide(1);

        return view('galeri.index', compact('galleries'));
    }

    /** GET /galeri/{slug} */
    public function show(Request $request, Gallery $gallery): View
    {
        $isStaff = (bool) $request->user()?->canAccessPanel();
        abort_unless($gallery->status === 'published' || $isStaff, 404);

        $gallery->load('images');

        return view('galeri.show', compact('gallery'));
    }
}
