<?php

namespace App\Http\Controllers;

use App\Models\LostItem;
use Illuminate\Http\Request;
use Illuminate\View\View;

class LostItemController extends Controller
{
    /** GET /barang-hilang?jenis=hilang|ditemukan */
    public function index(Request $request): View
    {
        $type = $request->query('jenis');
        $type = is_string($type) && array_key_exists($type, LostItem::TYPES) ? $type : null;

        // Yang masih aktif di atas, yang sudah selesai di bawah
        $items = LostItem::published()
            ->when($type, fn ($q) => $q->where('type', $type))
            ->orderBy('is_resolved')
            ->orderByDesc('event_date')
            ->orderByDesc('id')
            ->paginate(12)
            ->onEachSide(1)
            ->withQueryString();

        $counts = LostItem::published()->active()
            ->selectRaw('type, count(*) as total')->groupBy('type')->pluck('total', 'type');

        return view('barang.index', [
            'items' => $items,
            'type' => $type,
            'types' => LostItem::TYPES,
            'counts' => $counts,
        ]);
    }

    /** GET /barang-hilang/{slug} */
    public function show(Request $request, LostItem $lostItem): View
    {
        $isStaff = (bool) $request->user()?->canAccessPanel();
        abort_unless($lostItem->status === 'published' || $isStaff, 404);

        $others = LostItem::published()->active()
            ->whereKeyNot($lostItem->id)
            ->latest('event_date')
            ->take(4)
            ->get();

        return view('barang.show', [
            'item' => $lostItem,
            'others' => $others,
            'preview' => $lostItem->status !== 'published',
        ]);
    }
}
