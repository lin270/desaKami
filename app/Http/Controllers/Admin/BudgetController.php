<?php

namespace App\Http\Controllers\Admin;

use App\Http\Controllers\Controller;
use App\Models\Budget;
use App\Support\Rules;
use App\Support\SiteCache;
use Illuminate\Http\RedirectResponse;
use Illuminate\Http\Request;
use Illuminate\Http\UploadedFile;
use Illuminate\Support\Facades\Storage;
use Illuminate\Validation\Rule;
use Illuminate\Validation\ValidationException;
use Illuminate\View\View;

class BudgetController extends Controller
{
    public function index(): View
    {
        return view('admin.budgets.index', ['budgets' => Budget::orderByDesc('year')->get()]);
    }

    public function create(): View
    {
        return view('admin.budgets.create', ['budget' => new Budget(['year' => (int) date('Y'), 'status' => 'published'])]);
    }

    public function store(Request $request): RedirectResponse
    {
        $data = $this->validated($request);
        $this->requireFileIfPublished($data, null, $request);

        $data = $this->withFile($request, $data);
        Budget::create($data);
        SiteCache::flush();

        return redirect()->route('admin.budgets.index')->with('success', 'APBDes ditambahkan.');
    }

    public function edit(Budget $budget): View
    {
        return view('admin.budgets.edit', compact('budget'));
    }

    public function update(Request $request, Budget $budget): RedirectResponse
    {
        $data = $this->validated($request, $budget);
        $this->requireFileIfPublished($data, $budget, $request);

        $old = $budget->file;
        $data = $this->withFile($request, $data);
        $budget->update($data);

        if ($request->hasFile('file') && $old) {
            Storage::disk('public')->delete($old);
        }
        SiteCache::flush();

        return redirect()->route('admin.budgets.index')->with('success', 'APBDes diperbarui.');
    }

    public function destroy(Budget $budget): RedirectResponse
    {
        if ($budget->file) {
            Storage::disk('public')->delete($budget->file);
        }
        $budget->delete();
        SiteCache::flush();

        return redirect()->route('admin.budgets.index')->with('success', 'APBDes dihapus.');
    }

    private function validated(Request $request, ?Budget $budget = null): array
    {
        return $request->validate([
            'year' => ['required', 'integer', 'min:2000', 'max:2100', Rule::unique('budgets', 'year')->ignore($budget?->id)],
            'title' => ['nullable', 'string', 'max:150'],
            'notes' => ['nullable', 'string', 'max:3000'],
            'status' => ['required', 'in:draft,published'],
            // mimes memeriksa isi file sebenarnya, bukan hanya ekstensi
            'file' => ['nullable', 'file', 'mimes:pdf', 'max:8192'],
        ], Rules::messages() + ['file.max' => 'Ukuran file PDF maksimal 8 MB.', 'file.mimes' => 'File harus berformat PDF.'], Rules::attributes());
    }

    private function requireFileIfPublished(array $data, ?Budget $budget, Request $request): void
    {
        if ($data['status'] === 'published' && ! $request->hasFile('file') && blank($budget?->file)) {
            throw ValidationException::withMessages(['file' => 'Unggah file PDF dulu sebelum menerbitkan.']);
        }
    }

    private function withFile(Request $request, array $data): array
    {
        unset($data['file']);

        if ($request->hasFile('file')) {
            /** @var UploadedFile $file */
            $file = $request->file('file');
            $data['file'] = $file->store('apbdes', 'public');
            $data['file_size'] = $file->getSize();
        }

        return $data;
    }
}
