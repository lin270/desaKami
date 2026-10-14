<?php

namespace App\Http\Controllers\Admin;

use App\Http\Controllers\Controller;
use App\Models\Category;
use App\Models\Post;
use App\Models\User;
use App\Support\Media;
use App\Support\Rules;
use App\Support\SiteCache;
use Illuminate\Http\RedirectResponse;
use Illuminate\Http\Request;
use Illuminate\Support\Facades\Gate;
use Illuminate\View\View;

class PostController extends Controller
{
    public function index(Request $request): View
    {
        $user = $request->user();
        $q = trim((string) $request->query('q', ''));
        $status = $request->query('status');

        $posts = Post::with(['category', 'author'])
            // Editor hanya melihat berita miliknya; admin melihat semua
            ->when(! $user->isAdmin(), fn ($w) => $w->where('author_id', $user->id))
            ->when($q !== '', fn ($w) => $w->where('title', 'like', '%'.addcslashes($q, '%_\\').'%'))
            ->when(in_array($status, ['draft', 'published'], true), fn ($w) => $w->where('status', $status))
            ->latest('updated_at')
            ->paginate(15)
            ->withQueryString();

        return view('admin.posts.index', compact('posts', 'q', 'status'));
    }

    public function create(Request $request): View
    {
        $post = new Post(['status' => 'draft', 'published_at' => now()]);

        return view('admin.posts.create', $this->formData($post, $request));
    }

    public function store(Request $request): RedirectResponse
    {
        $data = $this->prepare($request, $this->validated($request));

        if ($request->hasFile('thumbnail')) {
            $data['thumbnail'] = Media::storeImage($request->file('thumbnail'), 'posts');
        } else {
            unset($data['thumbnail']);
        }

        Post::create($data);
        SiteCache::flush();

        return redirect()->route('admin.posts.index')->with('success', 'Berita berhasil dibuat.');
    }

    public function edit(Request $request, Post $post): View
    {
        Gate::authorize('update', $post);

        return view('admin.posts.edit', $this->formData($post, $request));
    }

    public function update(Request $request, Post $post): RedirectResponse
    {
        Gate::authorize('update', $post);

        $data = $this->prepare($request, $this->validated($request, $post), $post);

        if ($request->hasFile('thumbnail')) {
            Media::delete($post->thumbnail);
            $data['thumbnail'] = Media::storeImage($request->file('thumbnail'), 'posts');
        } else {
            unset($data['thumbnail']);
        }

        $post->update($data);
        SiteCache::flush();

        return redirect()->route('admin.posts.index')->with('success', 'Berita berhasil diperbarui.');
    }

    public function destroy(Post $post): RedirectResponse
    {
        Gate::authorize('delete', $post);

        // Soft delete: data dan gambar tetap tersimpan sehingga bisa dipulihkan lewat database
        $post->delete();
        SiteCache::flush();

        return redirect()->route('admin.posts.index')->with('success', 'Berita dihapus.');
    }

    /** Publish / unpublish dengan satu klik. */
    public function toggle(Post $post): RedirectResponse
    {
        Gate::authorize('update', $post);

        if ($post->status === 'published') {
            $post->update(['status' => 'draft']);
            $message = 'Berita dikembalikan ke draft.';
        } else {
            $post->update(['status' => 'published', 'published_at' => $post->published_at ?? now()]);
            $message = 'Berita diterbitkan.';
        }

        SiteCache::flush();

        return back()->with('success', $message);
    }

    private function formData(Post $post, Request $request): array
    {
        $isAdmin = $request->user()->isAdmin();

        return [
            'post' => $post,
            'categories' => Category::orderBy('name')->get(),
            'authors' => $isAdmin ? User::where('is_active', true)->orderBy('name')->get() : collect(),
            'isAdmin' => $isAdmin,
        ];
    }

    private function validated(Request $request, ?Post $post = null): array
    {
        $isAdmin = $request->user()->isAdmin();

        return $request->validate([
            'title' => ['required', 'string', 'max:200'],
            'slug' => Rules::slug('posts', $post?->id),
            'category_id' => ['required', 'exists:categories,id'],
            'author_id' => [$isAdmin ? 'required' : 'nullable', 'exists:users,id'],
            'thumbnail' => Rules::image(),
            'excerpt' => ['nullable', 'string', 'max:300'],
            'content' => ['required', 'string'],
            'status' => ['required', 'in:draft,published'],
            'published_at' => ['nullable', 'date'],
            'is_featured' => ['nullable', 'boolean'],
        ], Rules::messages(), Rules::attributes());
    }

    /** Rapikan data tervalidasi sebelum disimpan. */
    private function prepare(Request $request, array $data, ?Post $post = null): array
    {
        $user = $request->user();

        // Penulis: admin boleh memilih; editor selalu dirinya sendiri / penulis asli
        $data['author_id'] = $user->isAdmin()
            ? $data['author_id']
            : ($post?->author_id ?? $user->id);

        $data['is_featured'] = $request->boolean('is_featured');

        // Slug kosong = otomatis (baru) atau tidak berubah (edit)
        if (blank($data['slug'] ?? null)) {
            unset($data['slug']);
        }

        // Status terbit tanpa tanggal -> pakai sekarang
        if ($data['status'] === 'published' && blank($data['published_at'] ?? null)) {
            $data['published_at'] = $post?->published_at ?? now();
        }

        if (blank($data['published_at'] ?? null)) {
            unset($data['published_at']);
        }

        return $data;
    }
}
