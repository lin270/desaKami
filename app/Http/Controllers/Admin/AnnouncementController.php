<?php

namespace App\Http\Controllers\Admin;

use App\Http\Controllers\Controller;
use App\Models\Announcement;
use App\Support\Media;
use App\Support\Rules;
use App\Support\SiteCache;
use Illuminate\Http\RedirectResponse;
use Illuminate\Http\Request;
use Illuminate\View\View;

class AnnouncementController extends Controller
{
    public function index(Request $request): View
    {
        $q = trim((string) $request->query('q', ''));

        $announcements = Announcement::query()
            ->when($q !== '', fn ($w) => $w->where('title', 'like', '%'.addcslashes($q, '%_\\').'%'))
            ->latest('updated_at')
            ->paginate(15)
            ->withQueryString();

        return view('admin.announcements.index', compact('announcements', 'q'));
    }

    public function create(): View
    {
        $announcement = new Announcement(['status' => 'draft', 'category' => 'Umum', 'published_at' => now()]);

        return view('admin.announcements.create', compact('announcement'));
    }

    public function store(Request $request): RedirectResponse
    {
        $data = $this->prepare($request, $this->validated($request));

        if ($request->hasFile('image')) {
            $data['image'] = Media::storeImage($request->file('image'), 'announcements');
        } else {
            unset($data['image']);
        }

        Announcement::create($data);
        SiteCache::flush();

        return redirect()->route('admin.announcements.index')->with('success', 'Pengumuman dibuat.');
    }

    public function edit(Announcement $announcement): View
    {
        return view('admin.announcements.edit', compact('announcement'));
    }

    public function update(Request $request, Announcement $announcement): RedirectResponse
    {
        $data = $this->prepare($request, $this->validated($request, $announcement), $announcement);

        if ($request->hasFile('image')) {
            Media::delete($announcement->image);
            $data['image'] = Media::storeImage($request->file('image'), 'announcements');
        } else {
            unset($data['image']);
        }

        $announcement->update($data);
        SiteCache::flush();

        return redirect()->route('admin.announcements.index')->with('success', 'Pengumuman diperbarui.');
    }

    public function destroy(Announcement $announcement): RedirectResponse
    {
        $announcement->delete();
        SiteCache::flush();

        return redirect()->route('admin.announcements.index')->with('success', 'Pengumuman dihapus.');
    }

    private function validated(Request $request, ?Announcement $announcement = null): array
    {
        return $request->validate([
            'title' => ['required', 'string', 'max:200'],
            'slug' => Rules::slug('announcements', $announcement?->id),
            'category' => ['required', 'string', 'max:50'],
            'content' => ['required', 'string'],
            'image' => Rules::image(),
            'is_important' => ['nullable', 'boolean'],
            'status' => ['required', 'in:draft,published'],
            'published_at' => ['nullable', 'date'],
        ], Rules::messages(), Rules::attributes());
    }

    private function prepare(Request $request, array $data, ?Announcement $announcement = null): array
    {
        $data['is_important'] = $request->boolean('is_important');

        if (blank($data['slug'] ?? null)) {
            unset($data['slug']);
        }

        if ($data['status'] === 'published' && blank($data['published_at'] ?? null)) {
            $data['published_at'] = $announcement?->published_at ?? now();
        }

        if (blank($data['published_at'] ?? null)) {
            unset($data['published_at']);
        }

        return $data;
    }
}
