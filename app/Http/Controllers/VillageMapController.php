<?php

namespace App\Http\Controllers;

use App\Models\VillageLocation;
use Illuminate\View\View;

class VillageMapController extends Controller
{
    public function index(): View
    {
        $locations = VillageLocation::query()->where('is_published', true)->orderBy('name')->get();
        $categories = $locations->pluck('category')->unique()->values();
        return view('peta.index', compact('locations', 'categories'));
    }
}
