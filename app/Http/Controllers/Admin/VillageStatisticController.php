<?php

namespace App\Http\Controllers\Admin;

use App\Http\Controllers\Controller;
use App\Models\VillageStatistic;
use App\Support\Rules;
use App\Support\SiteCache;
use Illuminate\Http\RedirectResponse;
use Illuminate\Http\Request;
use Illuminate\Validation\Rule;
use Illuminate\View\View;

/** Menu "Data Desa": statistik penduduk dan wilayah. */
class VillageStatisticController extends Controller
{
    public function index(): View
    {
        $statistics = VillageStatistic::orderBy('group')->orderBy('sort_order')->get()->groupBy('group');

        return view('admin.statistics.index', compact('statistics'));
    }

    public function create(): View
    {
        $statistic = new VillageStatistic(['group' => 'penduduk', 'sort_order' => (int) VillageStatistic::max('sort_order') + 1]);

        return view('admin.statistics.create', compact('statistic'));
    }

    public function store(Request $request): RedirectResponse
    {
        VillageStatistic::create($this->validated($request));
        SiteCache::flush();

        return redirect()->route('admin.statistics.index')->with('success', 'Data desa ditambahkan.');
    }

    public function edit(VillageStatistic $statistic): View
    {
        return view('admin.statistics.edit', compact('statistic'));
    }

    public function update(Request $request, VillageStatistic $statistic): RedirectResponse
    {
        $statistic->update($this->validated($request, $statistic));
        SiteCache::flush();

        return redirect()->route('admin.statistics.index')->with('success', 'Data desa diperbarui.');
    }

    public function destroy(VillageStatistic $statistic): RedirectResponse
    {
        $statistic->delete();
        SiteCache::flush();

        return redirect()->route('admin.statistics.index')->with('success', 'Data desa dihapus.');
    }

    private function validated(Request $request, ?VillageStatistic $statistic = null): array
    {
        $data = $request->validate([
            'key' => ['required', 'string', 'max:80', 'regex:/^[a-z0-9]+(?:_[a-z0-9]+)*$/', Rule::unique('village_statistics', 'key')->ignore($statistic?->id)],
            'label' => ['required', 'string', 'max:150'],
            'value' => ['required', 'string', 'max:100'],
            'unit' => ['nullable', 'string', 'max:30'],
            'group' => ['required', 'in:penduduk,wilayah,lainnya'],
            'sort_order' => ['nullable', 'integer', 'min:0', 'max:65000'],
        ], Rules::messages(), Rules::attributes());

        $data['sort_order'] = (int) ($data['sort_order'] ?? 0);

        return $data;
    }
}
