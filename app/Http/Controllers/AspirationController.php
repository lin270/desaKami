<?php

namespace App\Http\Controllers;

use App\Models\Aspiration;
use Illuminate\Http\RedirectResponse;
use Illuminate\Http\Request;
use Illuminate\Support\Facades\Validator;
use Illuminate\View\View;

class AspirationController extends Controller
{
    /** GET /aspirasi - papan buletin */
    public function index(): View
    {
        $aspirations = Aspiration::query()
            ->latest()
            ->paginate(18)
            ->onEachSide(1);

        return view('aspirasi.index', compact('aspirations'));
    }

    /** POST /aspirasi - siapa pun boleh menulis tanpa login (dibatasi throttle). */
    public function store(Request $request): RedirectResponse
    {
        // Jebakan bot: kolom ini disembunyikan dari manusia, sehingga harus kosong
        if (filled($request->input('website'))) {
            return redirect()->route('aspirations.index');
        }

        $validator = Validator::make($request->all(), [
            'name' => ['nullable', 'string', 'max:40'],
            'message' => ['required', 'string', 'min:10', 'max:500', 'not_regex:/https?:\/\/|www\./i'],
        ], [
            'message.required' => 'Aspirasi wajib diisi.',
            'message.min' => 'Aspirasi terlalu pendek (minimal 10 karakter).',
            'message.max' => 'Aspirasi terlalu panjang (maksimal 500 karakter).',
            'message.not_regex' => 'Mohon tidak mencantumkan tautan (link) pada aspirasi.',
            'name.max' => 'Nama maksimal 40 karakter.',
        ], [
            'name' => 'nama',
            'message' => 'aspirasi',
        ]);

        // Kembali ke formulir (bukan ke puncak halaman) bila ada kesalahan
        if ($validator->fails()) {
            return redirect()->to(route('aspirations.index').'#tulis')
                ->withErrors($validator)
                ->withInput();
        }

        $data = $validator->validated();

        Aspiration::create([
            'name' => trim((string) ($data['name'] ?? '')) ?: 'Warga',
            'message' => trim($data['message']),
            'color' => random_int(0, Aspiration::COLORS - 1),
            'ip_hash' => hash('sha256', $request->ip().config('app.key')),
        ]);

        return redirect()->to(route('aspirations.index').'#papan')
            ->with('success', 'Terima kasih! Aspirasi Anda sudah dipasang di papan.');
    }
}
