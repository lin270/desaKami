<?php

namespace App\View\Composers;

use App\Models\Category;
use App\Models\Post;
use Illuminate\Support\Facades\Cache;
use Illuminate\View\View;

/** Data sidebar (populer, terbaru, kategori) - di-cache 10 menit. */
class SidebarComposer
{
    public function compose(View $view): void
    {
        $data = Cache::remember('sidebar.data', 600, function () {
            return [
                'popularPosts' => Post::published()->with('category')
                    ->orderByDesc('views')->take(5)->get(),
                'latestPosts' => Post::published()->with('category')
                    ->latest('published_at')->take(5)->get(),
                'sidebarCategories' => Category::withCount(['posts' => fn ($q) => $q->published()])
                    ->orderBy('name')->get(),
            ];
        });

        $view->with($data);
    }
}
