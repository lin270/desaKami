<?php

namespace App\Http\Controllers;

use App\Models\Budget;
use Illuminate\Support\Facades\Storage;
use Illuminate\View\View;
use Symfony\Component\HttpFoundation\StreamedResponse;

class ApbdesController extends Controller
{
    /** GET /apbdes - daftar APBDes per tahun, masing-masing satu PDF. */
    public function index(): View
    {
        return view('apbdes.index', [
            'budgets' => Budget::published()->orderByDesc('year')->get(),
        ]);
    }

    /** GET /apbdes/{year}/unduh - unduh PDF APBDes tahun tersebut. */
    public function download(int $year): StreamedResponse
    {
        $budget = Budget::published()->where('year', $year)->firstOrFail();
        abort_unless($budget->fileExists(), 404);

        return Storage::disk('public')->download($budget->file, $budget->download_name, [
            'Content-Type' => 'application/pdf',
        ]);
    }
}
