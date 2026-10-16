<?php

namespace App\Http\Controllers\Admin;

use App\Http\Controllers\Controller;
use App\Models\Announcement;
use App\Models\Event;
use App\Models\Aspiration;
use App\Models\Gallery;
use App\Models\LostItem;
use App\Models\Post;
use App\Models\VillageLocation;
use Illuminate\Http\Request;
use Illuminate\Support\Facades\Gate;
use Illuminate\Support\Facades\Schema;
use Illuminate\View\View;

class DashboardController extends Controller
{
    public function index(Request $request): View
    {
        $recentPosts = Post::with(['category', 'author'])
            ->latest('updated_at')
            ->take(6)
            ->get();

        // Aktivitas terbaru: gabungan perubahan terakhir dari 4 jenis konten
        $activity = collect()
            ->merge(Post::latest('updated_at')->take(5)->get()->map(fn ($m) => [
                'type' => 'Berita',
                'title' => $m->title,
                'at' => $m->updated_at,
                'new' => $m->wasRecentlyCreated || $m->created_at->eq($m->updated_at),
                'url' => Gate::allows('update', $m) ? route('admin.posts.edit', $m) : null,
            ]))
            ->merge(Announcement::latest('updated_at')->take(5)->get()->map(fn ($m) => [
                'type' => 'Pengumuman',
                'title' => $m->title,
                'at' => $m->updated_at,
                'new' => $m->created_at->eq($m->updated_at),
                'url' => route('admin.announcements.edit', $m),
            ]))
            ->merge(Event::latest('updated_at')->take(5)->get()->map(fn ($m) => [
                'type' => 'Agenda',
                'title' => $m->title,
                'at' => $m->updated_at,
                'new' => $m->created_at->eq($m->updated_at),
                'url' => route('admin.events.edit', $m),
            ]))
            ->merge(Gallery::latest('updated_at')->take(5)->get()->map(fn ($m) => [
                'type' => 'Galeri',
                'title' => $m->title,
                'at' => $m->updated_at,
                'new' => $m->created_at->eq($m->updated_at),
                'url' => route('admin.galleries.edit', $m),
            ]))
            ->sortByDesc('at')
            ->take(8)
            ->values();

        return view('admin.dashboard', [
            'totalPosts' => Post::count(),
            'totalDrafts' => Post::where('status', 'draft')->count(),
            'totalAnnouncements' => Announcement::count(),
            'totalEvents' => Event::count(),
            'totalGalleries' => Gallery::count(),
            'totalViews' => (int) Post::sum('views'),
            // Dihitung dari database; lokasi dilewati bila migration peta belum dijalankan
            'totalLocations' => Schema::hasTable('village_locations') ? VillageLocation::count() : null,
            'pendingAspirations' => Schema::hasTable('aspirations') ? Aspiration::pending()->count() : null,
            'activeLostItems' => LostItem::active()->count(),
            'upcomingEvents' => Event::published()->upcoming()->count(),
            'recentPosts' => $recentPosts,
            'activity' => $activity,
        ]);
    }
}
