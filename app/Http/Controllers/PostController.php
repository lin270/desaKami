<?php

namespace App\Http\Controllers;

use App\Models\Category;
use App\Models\Post;
use Illuminate\Database\Eloquent\Builder;
use Illuminate\Http\Request;
use Illuminate\Support\Facades\DB;
use Illuminate\Support\Str;
use Illuminate\View\View;

class PostController extends Controller
{
    private const PER_PAGE = 8;

    /** GET /berita */
    public function index(Request $request): View
    {
        return $this->listing($request, Post::published(), null);
    }

    /** GET /kategori/{slug} */
    public function category(Request $request, Category $category): View
    {
        return $this->listing($request, Post::published()->where('category_id', $category->id), $category);
    }

    /** GET /berita/{slug} */
    public function show(Request $request, Post $post): View
    {
        $visible = $post->status === 'published'
            && $post->published_at !== null
            && $post->published_at->lte(now());

        // Berita draft/terjadwal hanya bisa dilihat staf admin (pratinjau)
        $isStaff = (bool) $request->user()?->canAccessPanel();
        abort_unless($visible || $isStaff, 404);

        $post->load(['category', 'author']);

        // Tambah views sekali per sesi per berita (refresh tidak menambah)
        $key = 'viewed_post_'.$post->id;
        if ($visible && ! $request->session()->has($key)) {
            // Query langsung (bukan $post->increment) agar updated_at tidak ikut berubah
            DB::table('posts')->where('id', $post->id)->increment('views');
            $post->views++; // hanya di memori, agar angka di halaman ikut naik
            $request->session()->put($key, true);
        }

        $related = Post::published()
            ->with(['category', 'author'])
            ->where('category_id', $post->category_id)
            ->whereKeyNot($post->id)
            ->latest('published_at')
            ->take(4)
            ->get();

        // Markdown aman: tag HTML mentah dibuang, link berbahaya diblokir
        $body = Str::markdown($post->content, [
            'html_input' => 'strip',
            'allow_unsafe_links' => false,
        ]);

        return view('berita.show', [
            'post' => $post,
            'body' => $body,
            'related' => $related,
            'preview' => ! $visible,
        ]);
    }

    private function listing(Request $request, Builder $query, ?Category $category): View
    {
        $sort = $request->query('urut') === 'populer' ? 'populer' : 'terbaru';

        $query->with(['category', 'author']);

        if ($sort === 'populer') {
            $query->orderByDesc('views')->orderByDesc('published_at');
        } else {
            $query->latest('published_at');
        }

        $posts = $query->paginate(self::PER_PAGE)->onEachSide(1)->withQueryString();

        return view('berita.index', [
            'posts' => $posts,
            'category' => $category,
            'sort' => $sort,
            'categories' => Category::orderBy('name')->get(),
        ]);
    }
}
