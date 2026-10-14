<?php

namespace App\Http\Controllers\Admin;

use App\Http\Controllers\Controller;
use App\Models\Official;
use App\Support\Media;
use App\Support\Rules;
use App\Support\SiteCache;
use Illuminate\Http\RedirectResponse;
use Illuminate\Http\Request;
use Illuminate\Validation\Rule;
use Illuminate\View\View;

class OfficialController extends Controller
{
    public function index(): View
    {
        $officials = Official::orderBy('sort_order')->orderBy('id')->get();

        return view('admin.officials.index', ['officials' => $officials, 'groups' => Official::GROUPS]);
    }

    public function create(): View
    {
        $official = new Official(['is_active' => true, 'sort_order' => (int) Official::max('sort_order') + 1]);

        return view('admin.officials.create', ['official' => $official, 'groups' => Official::GROUPS]);
    }

    public function store(Request $request): RedirectResponse
    {
        $data = $this->validated($request);

        if ($request->hasFile('photo')) {
            $data['photo'] = Media::storeImage($request->file('photo'), 'officials', 800);
        } else {
            unset($data['photo']);
        }

        Official::create($data);
        SiteCache::flush();

        return redirect()->route('admin.officials.index')->with('success', 'Perangkat desa ditambahkan.');
    }

    public function edit(Official $official): View
    {
        return view('admin.officials.edit', ['official' => $official, 'groups' => Official::GROUPS]);
    }

    public function update(Request $request, Official $official): RedirectResponse
    {
        $data = $this->validated($request);

        if ($request->hasFile('photo')) {
            Media::delete($official->photo);
            $data['photo'] = Media::storeImage($request->file('photo'), 'officials', 800);
        } else {
            unset($data['photo']);
        }

        $official->update($data);
        SiteCache::flush();

        return redirect()->route('admin.officials.index')->with('success', 'Data perangkat desa diperbarui.');
    }

    public function destroy(Official $official): RedirectResponse
    {
        Media::delete($official->photo);
        $official->delete();
        SiteCache::flush();

        return redirect()->route('admin.officials.index')->with('success', 'Data perangkat desa dihapus.');
    }

    private function validated(Request $request): array
    {
        $data = $request->validate([
            'name' => ['required', 'string', 'max:150'],
            'position' => ['required', 'string', 'max:150'],
            'group' => ['required', Rule::in(array_keys(Official::GROUPS))],
            'photo' => Rules::image(),
            'phone' => ['nullable', 'string', 'max:30'],
            'sort_order' => ['nullable', 'integer', 'min:0', 'max:65000'],
            'is_active' => ['nullable', 'boolean'],
        ], Rules::messages(), Rules::attributes());

        $data['is_active'] = $request->boolean('is_active');
        $data['sort_order'] = (int) ($data['sort_order'] ?? 0);

        return $data;
    }
}
