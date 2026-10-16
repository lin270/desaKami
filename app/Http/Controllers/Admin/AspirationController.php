<?php

namespace App\Http\Controllers\Admin;

use App\Http\Controllers\Controller;
use App\Models\Aspiration;
use Illuminate\Http\RedirectResponse;
use Illuminate\Http\Request;
use Illuminate\View\View;

class AspirationController extends Controller
{
    public function index(Request $request): View
    {
        $q = trim((string) $request->query('q', ''));
        $status = in_array($request->query('status'), ['pending', 'replied'], true) ? $request->query('status') : '';

        $aspirations = Aspiration::query()
            ->with('replier')
            ->when($q !== '', function ($w) use ($q) {
                $like = '%'.addcslashes($q, '%_\\').'%';
                $w->where(fn ($x) => $x->where('message', 'like', $like)->orWhere('name', 'like', $like));
            })
            ->when($status === 'pending', fn ($w) => $w->pending())
            ->when($status === 'replied', fn ($w) => $w->replied())
            ->latest()
            ->paginate(15)
            ->withQueryString();

        $counts = [
            'all' => Aspiration::count(),
            'pending' => Aspiration::pending()->count(),
            'replied' => Aspiration::replied()->count(),
        ];

        return view('admin.aspirations.index', compact('aspirations', 'q', 'status', 'counts'));
    }

    /** Kirim atau ubah balasan. Balasan dikosongkan = balasan dihapus. */
    public function reply(Request $request, Aspiration $aspiration): RedirectResponse
    {
        $data = $request->validate([
            'reply' => ['nullable', 'string', 'max:800'],
        ], [
            'reply.max' => 'Balasan maksimal 800 karakter.',
        ]);

        $reply = trim((string) ($data['reply'] ?? ''));

        if ($reply === '') {
            $aspiration->update(['reply' => null, 'replied_at' => null, 'replied_by' => null]);

            return back()->with('success', 'Balasan dihapus.');
        }

        $aspiration->update([
            'reply' => $reply,
            'replied_at' => now(),
            'replied_by' => $request->user()->id,
        ]);

        return back()->with('success', 'Balasan terkirim dan tampil di papan aspirasi.');
    }

    public function destroy(Aspiration $aspiration): RedirectResponse
    {
        $aspiration->delete(); // soft delete: hilang dari papan, tetap tersimpan untuk arsip

        return back()->with('success', 'Aspirasi dihapus dari papan.');
    }
}
