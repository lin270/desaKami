<?php

namespace App\Http\Controllers\Admin;

use App\Http\Controllers\Controller;
use App\Models\Category;
use App\Support\Rules;
use App\Support\SiteCache;
use Illuminate\Http\RedirectResponse;
use Illuminate\Http\Request;
use Illuminate\Validation\Rule;
use Illuminate\View\View;

class CategoryController extends Controller
{
    /** Daftar kategori + form tambah di halaman yang sama. */
    public function index(): View
    {
        $categories = Category::withCount('posts')->orderBy('name')->get();

        return view('admin.categories.index', compact('categories'));
    }

    public function store(Request $request): RedirectResponse
    {
        $data = $request->validate([
            'name' => ['required', 'string', 'max:100', 'unique:categories,name'],
            'description' => ['nullable', 'string', 'max:255'],
        ], Rules::messages(), Rules::attributes());

        Category::create($data);
        SiteCache::flush();

        return redirect()->route('admin.categories.index')->with('success', 'Kategori ditambahkan.');
    }

    public function edit(Category $category): View
    {
        return view('admin.categories.edit', compact('category'));
    }

    public function update(Request $request, Category $category): RedirectResponse
    {
        $data = $request->validate([
            'name' => ['required', 'string', 'max:100', Rule::unique('categories', 'name')->ignore($category->id)],
            'description' => ['nullable', 'string', 'max:255'],
        ], Rules::messages(), Rules::attributes());

        $category->update($data);
        SiteCache::flush();

        return redirect()->route('admin.categories.index')->with('success', 'Kategori diperbarui.');
    }

    public function destroy(Category $category): RedirectResponse
    {
        // Berita (termasuk yang sudah dihapus lunak) masih memakai kategori ini
        if ($category->posts()->withTrashed()->exists()) {
            return back()->with('error', 'Kategori tidak dapat dihapus karena masih dipakai oleh berita.');
        }

        $category->delete();
        SiteCache::flush();

        return redirect()->route('admin.categories.index')->with('success', 'Kategori dihapus.');
    }
}
