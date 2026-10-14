<?php

namespace App\Http\Controllers;

use App\Models\Official;
use App\Models\VillageProfile;
use App\Models\VillageStatistic;
use Illuminate\Support\Facades\Cache;
use Illuminate\Support\Str;
use Illuminate\View\View;

class ProfileController extends Controller
{
    /** GET /profil */
    public function profile(): View
    {
        $profile = VillageProfile::current();

        // Markdown aman: tag HTML mentah dibuang
        $md = fn ($text) => Str::markdown((string) $text, [
            'html_input' => 'strip',
            'allow_unsafe_links' => false,
        ]);

        $stats = Cache::remember('home.stats', 3600, fn () => VillageStatistic::orderBy('sort_order')->get());

        $leaders = Official::active()
            ->whereIn('group', ['kepala_desa', 'sekretaris'])
            ->orderBy('sort_order')
            ->get();

        return view('profil.index', [
            'profile' => $profile,
            'about' => $md($profile->about),
            'history' => $md($profile->history),
            'generalInfo' => $md($profile->general_info),
            'missions' => $profile->missionList(),
            'populationStats' => $stats->where('group', 'penduduk'),
            'areaStats' => $stats->where('group', 'wilayah'),
            'leaders' => $leaders,
        ]);
    }

    /** GET /pemerintahan */
    public function officials(): View
    {
        $officials = Official::active()
            ->orderBy('sort_order')
            ->get()
            ->groupBy('group');

        return view('pemerintahan.index', [
            'officials' => $officials,
            'groups' => Official::GROUPS,
        ]);
    }
}
