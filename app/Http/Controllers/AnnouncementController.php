<?php

namespace App\Http\Controllers;

use App\Models\Announcement;
use Illuminate\Http\Request;
use Illuminate\Support\Str;
use Illuminate\View\View;

class AnnouncementController extends Controller
{
    /** GET /pengumuman */
    public function index(Request $request): View
    {
        $categories = Announcement::published()
            ->select('category')->distinct()->orderBy('category')->pluck('category');

        $selected = $request->query('kategori');
        $query = Announcement::published();

        if (is_string($selected) && $categories->contains($selected)) {
            $query->where('category', $selected);
        } else {
            $selected = null;
        }

        // Pengumuman penting selalu di atas, lalu yang terbaru
        $announcements = $query
            ->orderByDesc('is_important')
            ->latest('published_at')
            ->paginate(10)
            ->onEachSide(1)
            ->withQueryString();

        return view('pengumuman.index', compact('announcements', 'categories', 'selected'));
    }

    /** GET /pengumuman/{slug} */
    public function show(Request $request, Announcement $announcement): View
    {
        $visible = $announcement->status === 'published'
            && $announcement->published_at !== null
            && $announcement->published_at->lte(now());

        $isStaff = (bool) $request->user()?->canAccessPanel();
        abort_unless($visible || $isStaff, 404);

        $others = Announcement::published()
            ->whereKeyNot($announcement->id)
            ->latest('published_at')
            ->take(5)
            ->get();

        $body = Str::markdown($announcement->content, [
            'html_input' => 'strip',
            'allow_unsafe_links' => false,
        ]);

        return view('pengumuman.show', [
            'announcement' => $announcement,
            'body' => $body,
            'others' => $others,
            'preview' => ! $visible,
        ]);
    }
}
