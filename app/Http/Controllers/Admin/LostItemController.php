<?php

namespace App\Http\Controllers\Admin;

use App\Http\Controllers\Controller;
use App\Models\LostItem;
use App\Support\Media;
use App\Support\Rules;
use App\Support\SiteCache;
use Illuminate\Http\RedirectResponse;
use Illuminate\Http\Request;
use Illuminate\Validation\Rule;
use Illuminate\View\View;

class LostItemController extends Controller
{
    public function index(Request $request): View
    {
        $q = trim((string) $request->query('q', ''));
        $type = $request->query('jenis');

        $items = LostItem::query()
            ->when($q !== '', fn ($w) => $w->where('title', 'like', '%'.addcslashes($q, '%_\\').'%'))
            ->when(array_key_exists((string) $type, LostItem::TYPES), fn ($w) => $w->where('type', $type))
            ->orderBy('is_resolved')->latest('id')
            ->paginate(15)
            ->withQueryString();

        return view('admin.lost-items.index', ['items' => $items, 'q' => $q, 'type' => $type, 'types' => LostItem::TYPES]);
    }

    public function create(): View
    {
        $item = new LostItem(['type' => 'hilang', 'status' => 'published', 'event_date' => now()]);

        return view('admin.lost-items.create', ['item' => $item, 'types' => LostItem::TYPES]);
    }

    public function store(Request $request): RedirectResponse
    {
        $data = $this->prepared($request, $this->validated($request));

        if ($request->hasFile('image')) {
            $data['image'] = Media::storeImage($request->file('image'), 'lost-items');
        } else {
            unset($data['image']);
        }

        LostItem::create($data);
        SiteCache::flush();

        return redirect()->route('admin.lost-items.index')->with('success', 'Laporan ditambahkan.');
    }

    public function edit(LostItem $lostItem): View
    {
        return view('admin.lost-items.edit', ['item' => $lostItem, 'types' => LostItem::TYPES]);
    }

    public function update(Request $request, LostItem $lostItem): RedirectResponse
    {
        $data = $this->prepared($request, $this->validated($request, $lostItem), $lostItem);

        if ($request->hasFile('image')) {
            Media::delete($lostItem->image);
            $data['image'] = Media::storeImage($request->file('image'), 'lost-items');
        } else {
            unset($data['image']);
        }

        $lostItem->update($data);
        SiteCache::flush();

        return redirect()->route('admin.lost-items.index')->with('success', 'Laporan diperbarui.');
    }

    /** Tandai selesai (barang kembali / pemilik ditemukan) atau buka kembali. */
    public function resolve(LostItem $lostItem): RedirectResponse
    {
        $done = ! $lostItem->is_resolved;
        $lostItem->update(['is_resolved' => $done, 'resolved_at' => $done ? now() : null]);
        SiteCache::flush();

        return back()->with('success', $done ? 'Laporan ditandai selesai.' : 'Laporan dibuka kembali.');
    }

    public function destroy(LostItem $lostItem): RedirectResponse
    {
        $lostItem->delete();
        SiteCache::flush();

        return redirect()->route('admin.lost-items.index')->with('success', 'Laporan dihapus.');
    }

    private function validated(Request $request, ?LostItem $item = null): array
    {
        return $request->validate([
            'type' => ['required', Rule::in(array_keys(LostItem::TYPES))],
            'title' => ['required', 'string', 'max:200'],
            'slug' => Rules::slug('lost_items', $item?->id),
            'description' => ['required', 'string', 'max:3000'],
            'location' => ['nullable', 'string', 'max:200'],
            'event_date' => ['nullable', 'date'],
            'image' => Rules::image(),
            'contact' => ['nullable', 'string', 'max:100'],
            'status' => ['required', 'in:draft,published'],
        ], Rules::messages(), Rules::attributes());
    }

    private function prepared(Request $request, array $data, ?LostItem $item = null): array
    {
        if (blank($data['slug'] ?? null)) {
            unset($data['slug']);
        }

        $done = $request->boolean('is_resolved');
        $data['is_resolved'] = $done;
        $data['resolved_at'] = $done ? ($item?->resolved_at ?? now()) : null;

        return $data;
    }
}
