<?php

namespace App\Http\Controllers;

use App\Models\Event;
use Illuminate\View\View;

class EventController extends Controller
{
    /** GET /agenda */
    public function index(): View
    {
        $upcoming = Event::published()
            ->upcoming()
            ->orderBy('date')
            ->orderBy('start_time')
            ->take(50)
            ->get();

        $calendarEvents = Event::published()
            ->whereDate('date', '>=', today()->subMonths(6))
            ->whereDate('date', '<=', today()->addYear())
            ->orderBy('date')
            ->orderBy('start_time')
            ->get();

        $past = Event::published()
            ->whereDate('date', '<', today())
            ->orderByDesc('date')
            ->take(6)
            ->get();

        return view('agenda.index', compact('upcoming', 'past', 'calendarEvents'));
    }
}
