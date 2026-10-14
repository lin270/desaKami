<?php

namespace App\Http\Controllers\Admin;

use App\Http\Controllers\Controller;
use App\Models\VillageLocation;
use Illuminate\Http\RedirectResponse;
use Illuminate\Http\Request;
use Illuminate\View\View;

class VillageLocationController extends Controller
{
    public function index(): View { $locations = VillageLocation::orderBy('name')->paginate(15); return view('admin.village-locations.index', compact('locations')); }
    public function create(): View { $location = new VillageLocation(['is_published' => true]); return view('admin.village-locations.form', compact('location')); }
    public function store(Request $request): RedirectResponse { VillageLocation::create($this->validated($request)); return redirect()->route('admin.village-locations.index')->with('success', 'Lokasi berhasil ditambahkan.'); }
    public function edit(VillageLocation $village_location): View { $location = $village_location; return view('admin.village-locations.form', compact('location')); }
    public function update(Request $request, VillageLocation $village_location): RedirectResponse { $village_location->update($this->validated($request)); return redirect()->route('admin.village-locations.index')->with('success', 'Lokasi berhasil diperbarui.'); }
    public function destroy(VillageLocation $village_location): RedirectResponse { $village_location->delete(); return redirect()->route('admin.village-locations.index')->with('success', 'Lokasi dihapus.'); }
    
private function validated(Request $request): array
{
    $data = $request->validate([
        'name' => ['required', 'string', 'max:150'],
        'category' => ['required', 'string', 'max:60'],
        'description' => ['nullable', 'string', 'max:1500'],
        'address' => ['nullable', 'string', 'max:255'],
        'latitude' => ['required', 'numeric', 'between:-90,90'],
        'longitude' => ['required', 'numeric', 'between:-180,180'],
        'is_published' => ['nullable', 'boolean'],
    ], [], [
        'name' => 'nama lokasi',
        'category' => 'kategori',
        'latitude' => 'latitude',
        'longitude' => 'longitude',
    ]);

    return $data;
}
}
