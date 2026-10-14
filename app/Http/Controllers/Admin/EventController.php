<?php

namespace App\Http\Controllers\Admin;

use App\Http\Controllers\Controller;
use App\Models\Event;
use App\Support\Media;
use App\Support\Rules;
use App\Support\SiteCache;
use Illuminate\Http\RedirectResponse;
use Illuminate\Http\Request;
use Illuminate\View\View;

class EventController extends Controller
{
    public function index(Request $request): View
    {
        $q = trim((string) $request->query('q', ''));

        $events = Event::query()
            ->when($q !== '', fn ($w) => $w->where('title', 'like', '%'.addcslashes($q, '%_\\').'%'))
            ->orderByDesc('date')
            ->orderByDesc('start_time')
            ->paginate(15)
            ->withQueryString();

        return view('admin.events.index', compact('events', 'q'));
    }

    public function create(): View
    {
        $event = new Event(['status' => 'published', 'date' => now()->addDays(7)]);

        return view('admin.events.create', compact('event'));
    }

    public function store(Request $request): RedirectResponse
    {
        $data = $this->prepare($this->validated($request));

        if ($request->hasFile('image')) {
            $data['image'] = Media::storeImage($request->file('image'), 'events');
        } else {
            unset($data['image']);
        }

        Event::create($data);
        SiteCache::flush();

        return redirect()->route('admin.events.index')->with('success', 'Agenda dibuat.');
    }

    public function edit(Event $event): View
    {
        return view('admin.events.edit', compact('event'));
    }

    public function update(Request $request, Event $event): RedirectResponse
    {
        $data = $this->prepare($this->validated($request, $event));

        if ($request->hasFile('image')) {
            Media::delete($event->image);
            $data['image'] = Media::storeImage($request->file('image'), 'events');
        } else {
            unset($data['image']);
        }

        $event->update($data);
        SiteCache::flush();

        return redirect()->route('admin.events.index')->with('success', 'Agenda diperbarui.');
    }

    public function destroy(Event $event): RedirectResponse
    {
        $event->delete();
        SiteCache::flush();

        return redirect()->route('admin.events.index')->with('success', 'Agenda dihapus.');
    }

    private function validated(Request $request, ?Event $event = null): array
    {
        return $request->validate([
            'title' => ['required', 'string', 'max:200'],
            'slug' => Rules::slug('events', $event?->id),
            'description' => ['nullable', 'string', 'max:2000'],
            'date' => ['required', 'date'],
            'start_time' => ['required', 'date_format:H:i'],
            'end_time' => ['nullable', 'date_format:H:i', 'after:start_time'],
            'location' => ['required', 'string', 'max:255'],
            'image' => Rules::image(),
            'status' => ['required', 'in:draft,published'],
        ], Rules::messages(), Rules::attributes());
    }

    private function prepare(array $data): array
    {
        if (blank($data['slug'] ?? null)) {
            unset($data['slug']);
        }

        return $data;
    }
}
