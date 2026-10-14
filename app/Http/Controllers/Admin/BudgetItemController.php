<?php

namespace App\Http\Controllers\Admin;

use App\Http\Controllers\Controller;
use App\Models\Budget;
use App\Models\BudgetItem;
use App\Support\Rules;
use App\Support\SiteCache;
use Illuminate\Http\RedirectResponse;
use Illuminate\Http\Request;
use Illuminate\Validation\Rule;
use Illuminate\View\View;

class BudgetItemController extends Controller
{
    public function store(Request $request, Budget $budget): RedirectResponse
    {
        $budget->items()->create($this->validated($request));
        SiteCache::flush();

        return redirect()->route('admin.budgets.edit', $budget)->with('success', 'Rincian ditambahkan.');
    }

    public function edit(BudgetItem $item): View
    {
        return view('admin.budgets.item-edit', ['item' => $item, 'budget' => $item->budget, 'types' => BudgetItem::TYPES]);
    }

    public function update(Request $request, BudgetItem $item): RedirectResponse
    {
        $item->update($this->validated($request));
        SiteCache::flush();

        return redirect()->route('admin.budgets.edit', $item->budget_id)->with('success', 'Rincian diperbarui.');
    }

    public function destroy(BudgetItem $item): RedirectResponse
    {
        $budgetId = $item->budget_id;
        $item->delete();
        SiteCache::flush();

        return redirect()->route('admin.budgets.edit', $budgetId)->with('success', 'Rincian dihapus.');
    }

    private function validated(Request $request): array
    {
        // Angka boleh diketik dengan titik pemisah ribuan: 1.250.000
        $request->merge([
            'amount_plan' => preg_replace('/[.\s]/', '', (string) $request->input('amount_plan', '0')),
            'amount_realized' => preg_replace('/[.\s]/', '', (string) $request->input('amount_realized', '0')),
        ]);

        $data = $request->validate([
            'type' => ['required', Rule::in(array_keys(BudgetItem::TYPES))],
            'category' => ['nullable', 'string', 'max:150'],
            'name' => ['required', 'string', 'max:200'],
            'amount_plan' => ['required', 'integer', 'min:0', 'max:999999999999999'],
            'amount_realized' => ['required', 'integer', 'min:0', 'max:999999999999999'],
            'sort_order' => ['nullable', 'integer', 'min:0', 'max:65000'],
        ], Rules::messages(), Rules::attributes());

        $data['sort_order'] = (int) ($data['sort_order'] ?? 0);

        return $data;
    }
}
