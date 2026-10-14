<?php

namespace App\Http\Controllers\Admin;

use App\Http\Controllers\Controller;
use App\Models\Setting;
use App\Support\Media;
use App\Support\Rules;
use App\Support\SiteCache;
use Illuminate\Http\RedirectResponse;
use Illuminate\Http\Request;
use Illuminate\View\View;

/** Hanya role admin (dijaga Gate "admin-only" di route). */
class SettingController extends Controller
{
    private const KEYS = [
        'site_name', 'site_tagline', 'address', 'phone', 'email',
        'services_hours', 'facebook', 'instagram', 'youtube',
    ];

    public function edit(): View
    {
        return view('admin.settings.edit', ['settings' => Setting::allCached()]);
    }

    public function update(Request $request): RedirectResponse
    {
        $data = $request->validate([
            'site_name' => ['required', 'string', 'max:100'],
            'site_tagline' => ['nullable', 'string', 'max:150'],
            'address' => ['nullable', 'string', 'max:255'],
            'phone' => ['nullable', 'string', 'max:50'],
            'email' => ['nullable', 'email', 'max:150'],
            'services_hours' => ['nullable', 'string', 'max:150'],
            'facebook' => ['nullable', 'url:http,https', 'max:255'],
            'instagram' => ['nullable', 'url:http,https', 'max:255'],
            'youtube' => ['nullable', 'url:http,https', 'max:255'],
            'logo' => Rules::image(),
        ], Rules::messages(), Rules::attributes());

        foreach (self::KEYS as $key) {
            Setting::write($key, $data[$key] ?? '');
        }

        if ($request->hasFile('logo')) {
            Media::delete(Setting::read('logo'));
            Setting::write('logo', Media::storeImage($request->file('logo'), 'settings', 400));
        }

        SiteCache::flush();

        return back()->with('success', 'Pengaturan disimpan.');
    }
}
