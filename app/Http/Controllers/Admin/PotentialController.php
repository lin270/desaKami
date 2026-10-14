<?php

namespace App\Http\Controllers\Admin;

use App\Http\Controllers\Controller;
use App\Models\Potential;
use App\Support\Media;
use App\Support\Rules;
use App\Support\SiteCache;
use Illuminate\Http\RedirectResponse;
use Illuminate\Http\Request;
use Illuminate\Validation\Rule;
use Illuminate\View\View;

class PotentialController extends Controller
{
    public function index(Request $request): View
    {
        $q = trim((string) $request->query('q', ''));

        $potentials = Potential::query()
            ->when($q !== '', fn ($w) => $w->where('title', 'like', '%'.addcslashes($q, '%_\\').'%'))
            ->orderBy('sort_order')->latest('updated_at')
            ->paginate(15)
            ->withQueryString();

        return view('admin.potentials.index', ['potentials' => $potentials, 'q' => $q, 'categories' => Potential::CATEGORIES]);
    }

    public function create(): View
    {
        $potential = new Potential(['status' => 'published', 'category' => 'umkm', 'sort_order' => 0]);

        return view('admin.potentials.create', ['potential' => $potential, 'categories' => Potential::CATEGORIES]);
    }

    public function store(Request $request): RedirectResponse
    {
        $data = $this->prepared($request, $this->validated($request));

        if ($request->hasFile('image')) {
            $data['image'] = Media::storeImage($request->file('image'), 'potentials');
        } else {
            unset($data['image']);
        }

        Potential::create($data);
        SiteCache::flush();

        return redirect()->route('admin.potentials.index')->with('success', 'Potensi desa ditambahkan.');
    }

    public function edit(Potential $potential): View
    {
        return view('admin.potentials.edit', ['potential' => $potential, 'categories' => Potential::CATEGORIES]);
    }

    public function update(Request $request, Potential $potential): RedirectResponse
    {
        $data = $this->prepared($request, $this->validated($request, $potential));

        if ($request->hasFile('image')) {
            Media::delete($potential->image);
            $data['image'] = Media::storeImage($request->file('image'), 'potentials');
        } else {
            unset($data['image']);
        }

        $potential->update($data);
        SiteCache::flush();

        return redirect()->route('admin.potentials.index')->with('success', 'Potensi desa diperbarui.');
    }

    public function destroy(Potential $potential): RedirectResponse
    {
        $potential->delete();
        SiteCache::flush();

        return redirect()->route('admin.potentials.index')->with('success', 'Potensi desa dihapus.');
    }

    private function validated(Request $request, ?Potential $potential = null): array
    {
        return $request->validate([
            'title' => ['required', 'string', 'max:200'],
            'slug' => Rules::slug('potentials', $potential?->id),
            'category' => ['required', Rule::in(array_keys(Potential::CATEGORIES))],
            'summary' => ['nullable', 'string', 'max:300'],
            'content' => ['required', 'string'],
            'image' => Rules::image(),
            'location' => ['nullable', 'string', 'max:200'],
            'contact' => ['nullable', 'string', 'max:100'],
            'status' => ['required', 'in:draft,published'],
            'sort_order' => ['nullable', 'integer', 'min:0', 'max:65000'],
        ], Rules::messages(), Rules::attributes());
    }

    private function prepared(Request $request, array $data): array
    {
        if (blank($data['slug'] ?? null)) {
            unset($data['slug']);
        }
        $data['sort_order'] = (int) ($data['sort_order'] ?? 0);

        return $data;
    }
}
