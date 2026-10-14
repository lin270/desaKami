<?php

namespace App\Http\Controllers\Admin;

use App\Http\Controllers\Controller;
use App\Models\User;
use App\Support\Rules;
use Illuminate\Http\RedirectResponse;
use Illuminate\Http\Request;
use Illuminate\Validation\Rule;
use Illuminate\View\View;

/** Hanya role admin (dijaga Gate "admin-only" di route). */
class UserController extends Controller
{
    public function index(): View
    {
        $users = User::withCount('posts')->orderBy('name')->get();

        return view('admin.users.index', compact('users'));
    }

    public function create(): View
    {
        $user = new User(['role' => 'editor', 'is_active' => true]);

        return view('admin.users.create', compact('user'));
    }

    public function store(Request $request): RedirectResponse
    {
        $data = $request->validate([
            'name' => ['required', 'string', 'max:100'],
            'email' => ['required', 'email', 'max:150', 'unique:users,email'],
            'password' => ['required', 'string', 'min:8', 'confirmed'],
            'role' => ['required', 'in:admin,editor'],
            'is_active' => ['nullable', 'boolean'],
        ], Rules::messages(), Rules::attributes());

        $data['is_active'] = $request->boolean('is_active');

        User::create($data); // password di-hash otomatis oleh cast "hashed"

        return redirect()->route('admin.users.index')->with('success', 'Pengguna ditambahkan.');
    }

    public function edit(User $user): View
    {
        return view('admin.users.edit', compact('user'));
    }

    public function update(Request $request, User $user): RedirectResponse
    {
        $data = $request->validate([
            'name' => ['required', 'string', 'max:100'],
            'email' => ['required', 'email', 'max:150', Rule::unique('users', 'email')->ignore($user->id)],
            'password' => ['nullable', 'string', 'min:8', 'confirmed'],
            'role' => ['required', 'in:admin,editor'],
            'is_active' => ['nullable', 'boolean'],
        ], Rules::messages(), Rules::attributes());

        $data['is_active'] = $request->boolean('is_active');

        // Cegah admin mengunci dirinya sendiri
        if ($user->id === $request->user()->id && (! $data['is_active'] || $data['role'] !== 'admin')) {
            return back()
                ->withInput()
                ->with('error', 'Anda tidak dapat menonaktifkan akun sendiri atau menurunkan role sendiri.');
        }

        if (blank($data['password'] ?? null)) {
            unset($data['password']);
        }

        $user->update($data);

        return redirect()->route('admin.users.index')->with('success', 'Pengguna diperbarui.');
    }

    public function destroy(Request $request, User $user): RedirectResponse
    {
        if ($user->id === $request->user()->id) {
            return back()->with('error', 'Anda tidak dapat menghapus akun sendiri.');
        }

        // FK posts.author_id bersifat restrict (termasuk berita yang sudah dihapus lunak)
        if ($user->posts()->withTrashed()->exists()) {
            return back()->with('error', 'Pengguna tidak dapat dihapus karena memiliki berita. Nonaktifkan saja akunnya.');
        }

        $user->delete();

        return redirect()->route('admin.users.index')->with('success', 'Pengguna dihapus.');
    }
}
