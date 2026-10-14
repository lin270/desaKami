<?php

namespace App\Http\Controllers\Admin;

use App\Http\Controllers\Controller;
use App\Models\Gallery;
use App\Models\GalleryImage;
use App\Support\Media;
use App\Support\Rules;
use App\Support\SiteCache;
use Illuminate\Http\RedirectResponse;
use Illuminate\Http\Request;
use Illuminate\View\View;

class GalleryController extends Controller
{
    public function index(Request $request): View
    {
        $q = trim((string) $request->query('q', ''));

        $galleries = Gallery::with(['cover', 'images'])
            ->withCount('images')
            ->when($q !== '', fn ($w) => $w->where('title', 'like', '%'.addcslashes($q, '%_\\').'%'))
            ->latest('updated_at')
            ->paginate(12)
            ->withQueryString();

        return view('admin.galleries.index', compact('galleries', 'q'));
    }

    public function create(): View
    {
        $gallery = new Gallery(['status' => 'published', 'taken_at' => now()]);

        return view('admin.galleries.create', compact('gallery'));
    }

    public function store(Request $request): RedirectResponse
    {
        $data = $this->prepare($this->validated($request));

        $gallery = Gallery::create($data);
        $this->addImages($gallery, $request);
        SiteCache::flush();

        return redirect()->route('admin.galleries.edit', $gallery)
            ->with('success', 'Album dibuat. Anda dapat menambah foto lagi atau mengatur cover di bawah.');
    }

    public function edit(Gallery $gallery): View
    {
        $gallery->load('images');

        return view('admin.galleries.edit', compact('gallery'));
    }

    public function update(Request $request, Gallery $gallery): RedirectResponse
    {
        $gallery->update($this->prepare($this->validated($request, $gallery)));
        $this->addImages($gallery, $request);
        SiteCache::flush();

        return redirect()->route('admin.galleries.edit', $gallery)->with('success', 'Album diperbarui.');
    }

    public function destroy(Gallery $gallery): RedirectResponse
    {
        // Soft delete: foto tetap tersimpan di storage
        $gallery->delete();
        SiteCache::flush();

        return redirect()->route('admin.galleries.index')->with('success', 'Album dihapus.');
    }

    private function validated(Request $request, ?Gallery $gallery = null): array
    {
        return $request->validate([
            'title' => ['required', 'string', 'max:200'],
            'slug' => Rules::slug('galleries', $gallery?->id),
            'description' => ['nullable', 'string', 'max:1000'],
            'taken_at' => ['nullable', 'date'],
            'status' => ['required', 'in:draft,published'],
            'images' => ['nullable', 'array', 'max:20'],
            'images.*' => Rules::image(),
        ], Rules::messages(), Rules::attributes());
    }

    private function prepare(array $data): array
    {
        unset($data['images']);

        if (blank($data['slug'] ?? null)) {
            unset($data['slug']);
        }

        return $data;
    }

    /** Simpan banyak foto sekaligus; foto pertama otomatis jadi cover jika belum ada. */
    private function addImages(Gallery $gallery, Request $request): void
    {
        if (! $request->hasFile('images')) {
            return;
        }

        // Aggregate langsung dari model (bukan lewat relasi ber-orderBy) agar aman di MySQL strict mode
        $order = (int) GalleryImage::where('gallery_id', $gallery->id)->max('sort_order');
        $first = null;

        foreach ($request->file('images') as $file) {
            $order++;

            $image = $gallery->images()->create([
                'image' => Media::storeImage($file, 'galleries', 1800),
                'sort_order' => $order,
            ]);

            $first ??= $image;
        }

        if ($first && ! $gallery->cover_image_id) {
            $gallery->update(['cover_image_id' => $first->id]);
        }
    }
}
