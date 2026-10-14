<?php

namespace App\Http\Controllers;

use App\Models\Potential;
use Illuminate\Http\Request;
use Illuminate\Support\Str;
use Illuminate\View\View;

class PotentialController extends Controller
{
    /** GET /potensi?kategori=umkm */
    public function index(Request $request): View
    {
        $category = $request->query('kategori');
        $category = is_string($category) && array_key_exists($category, Potential::CATEGORIES) ? $category : null;

        $potentials = Potential::published()
            ->when($category, fn ($q) => $q->where('category', $category))
            ->orderBy('sort_order')
            ->latest('id')
            ->paginate(9)
            ->onEachSide(1)
            ->withQueryString();

        return view('potensi.index', [
            'potentials' => $potentials,
            'category' => $category,
            'categories' => Potential::CATEGORIES,
        ]);
    }

    /** GET /potensi/{slug} */
    public function show(Request $request, Potential $potential): View
    {
        $isStaff = (bool) $request->user()?->canAccessPanel();
        abort_unless($potential->status === 'published' || $isStaff, 404);

        $related = Potential::published()
            ->where('category', $potential->category)
            ->whereKeyNot($potential->id)
            ->orderBy('sort_order')
            ->take(3)
            ->get();

        $body = Str::markdown($potential->content, [
            'html_input' => 'strip',
            'allow_unsafe_links' => false,
        ]);

        return view('potensi.show', [
            'potential' => $potential,
            'body' => $body,
            'related' => $related,
            'preview' => $potential->status !== 'published',
        ]);
    }
}
