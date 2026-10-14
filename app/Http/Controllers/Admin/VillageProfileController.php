<?php

namespace App\Http\Controllers\Admin;

use App\Http\Controllers\Controller;
use App\Models\VillageProfile;
use App\Support\Media;
use App\Support\Rules;
use App\Support\SiteCache;
use Illuminate\Http\RedirectResponse;
use Illuminate\Http\Request;
use Illuminate\View\View;

class VillageProfileController extends Controller
{
    public function edit(): View
    {
        return view('admin.profile.edit', ['profile' => VillageProfile::current()]);
    }

    public function update(Request $request): RedirectResponse
    {
        $data = $request->validate([
            'about' => ['nullable', 'string', 'max:20000'],
            'history' => ['nullable', 'string', 'max:20000'],
            'vision' => ['nullable', 'string', 'max:2000'],
            'mission' => ['nullable', 'string', 'max:5000'],
            'area_description' => ['nullable', 'string', 'max:5000'],
            'general_info' => ['nullable', 'string', 'max:20000'],
            'hero_image' => Rules::image(),
        ], Rules::messages(), Rules::attributes());

        $profile = VillageProfile::first() ?? new VillageProfile();

        if ($request->hasFile('hero_image')) {
            Media::delete($profile->hero_image);
            $data['hero_image'] = Media::storeImage($request->file('hero_image'), 'profile');
        } else {
            unset($data['hero_image']);
        }

        $profile->fill($data)->save();
        SiteCache::flush();

        return back()->with('success', 'Profil desa disimpan.');
    }
}
