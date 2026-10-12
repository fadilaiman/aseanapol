<?php

namespace App\Http\Controllers\Admin;

use App\Http\Controllers\Controller;
use App\Models\Event;
use Illuminate\Http\Request;
use Illuminate\Support\Facades\DB;
use Illuminate\Support\Facades\Storage;
use Illuminate\Validation\Rule;

class EventController extends Controller
{
    public function index(Request $request)
    {
        // ?q[]=x style array input must not 500 -> treat non-strings as empty
        $str    = fn (string $k): string => is_string($v = $request->query($k)) ? $v : '';
        $q      = trim($str('q'));
        $kind   = $str('kind');
        $year   = $str('year');
        $type   = $str('type');
        $status = $str('status');

        $query = Event::query();

        if ($q !== '') {
            $like = '%' . str_replace(['%', '_'], ['\%', '\_'], $q) . '%';
            $query->where(function ($w) use ($like) {
                $w->where('title', 'like', $like)
                  ->orWhere('city', 'like', $like)
                  ->orWhere('country', 'like', $like)
                  ->orWhere('conf_number', 'like', $like)
                  ->orWhere('abbr', 'like', $like);
            });
        }
        if (array_key_exists($kind, Event::KINDS)) {
            $query->where('kind', $kind);
        }
        if (preg_match('/^\d{4}$/', $year)) {
            $query->where(function ($w) use ($year) {
                $w->whereYear('start_date', $year)->orWhere('conf_year', (int) $year);
            });
        }
        if (in_array($type, Event::TYPES, true)) {
            $query->where('type', $type);
        }
        if ($status === 'published') {
            $query->where('is_published', true);
        } elseif ($status === 'hidden') {
            $query->where('is_published', false);
        }

        $events = $query->orderBy('kind')
            ->orderByRaw('COALESCE(YEAR(start_date), conf_year, 0) DESC')
            ->orderBy('sort_order')
            ->orderByDesc('start_date')
            ->orderBy('id')
            ->paginate(20)
            ->withQueryString();

        $years = collect(DB::table('events')->selectRaw('DISTINCT YEAR(start_date) as y')->whereNotNull('start_date')->pluck('y'))
            ->merge(DB::table('events')->whereNotNull('conf_year')->distinct()->pluck('conf_year'))
            ->unique()->sortDesc()->values();

        return view('admin.events.index', [
            'events' => $events,
            'years'  => $years,
            'filters' => compact('q', 'kind', 'year', 'type', 'status'),
        ]);
    }

    public function create(Request $request)
    {
        $kind = $request->query('kind');
        $event = new Event([
            'kind'         => array_key_exists($kind, Event::KINDS) ? $kind : 'calendar',
            'type'         => 'Meeting',
            'status'       => 'upcoming',
            'is_published' => true,
            'sort_order'   => null,
        ]);

        return view('admin.events.create', compact('event'));
    }

    public function store(Request $request)
    {
        $data = $this->validated($request);
        $data['image'] = $this->storeImage($request);
        Event::create($data);

        return redirect()->route('admin.events.index')->with('success', 'Event added successfully.');
    }

    public function edit(Event $event)
    {
        return view('admin.events.edit', compact('event'));
    }

    public function update(Request $request, Event $event)
    {
        $data = $this->validated($request);

        if ($request->hasFile('image')) {
            $this->deleteImage($event->image);
            $data['image'] = $this->storeImage($request);
        } elseif ($request->boolean('remove_image')) {
            $this->deleteImage($event->image);
            $data['image'] = null;
        }

        $event->update($data);

        return redirect()->route('admin.events.index')->with('success', 'Event updated successfully.');
    }

    public function destroy(Event $event)
    {
        $this->deleteImage($event->image);
        $event->delete();

        return redirect()->route('admin.events.index')->with('success', 'Event removed.');
    }

    public function toggle(Event $event)
    {
        $event->update(['is_published' => ! $event->is_published]);

        return back()->with('success', $event->is_published ? 'Event is now visible on the site.' : 'Event hidden from the site.');
    }

    private function validated(Request $request): array
    {
        $kind = $request->input('kind');

        $rules = [
            'kind'         => ['required', Rule::in(array_keys(Event::KINDS))],
            'title'        => 'required|string|max:255',
            'start_date'   => 'nullable|date',
            'end_date'     => 'nullable|date|after_or_equal:start_date',
            'city'         => 'nullable|string|max:255',
            'country'      => 'nullable|string|max:255',
            'description'  => 'nullable|string|max:5000',
            'link'         => 'nullable|url|max:500',
            'link_label'   => 'nullable|string|max:120',
            'image'        => 'nullable|image|mimes:jpg,jpeg,png,webp|max:4096',
            'sort_order'   => 'nullable|integer|min:-100000|max:100000',
        ];

        if ($kind === 'calendar') {
            $rules['start_date'] = 'required|date';
            $rules['type']       = ['required', Rule::in(Event::TYPES)];
        }
        if ($kind === 'conference') {
            $rules['conf_number'] = 'required|string|max:20';
            $rules['conf_year']   = 'required|integer|min:1900|max:2200';
            $rules['status']      = ['required', Rule::in(array_keys(Event::STATUSES))];
            $rules['dates_label'] = 'nullable|string|max:255';
            $rules['flag']        = 'nullable|string|max:16';
            $rules['theme']       = 'nullable|string|max:500';
        }
        if ($kind === 'training') {
            $rules['description'] = 'required|string|max:5000';
            $rules['category']    = 'required|string|max:60';
            $rules['abbr']        = 'nullable|string|max:30';
            $rules['icon']        = 'nullable|string|max:60';
            $rules['frequency']   = 'nullable|string|max:60';
            $rules['latest']      = 'nullable|string|max:255';
        }

        $data = $request->validate($rules, [
            'end_date.after_or_equal' => 'The end date cannot be before the start date.',
        ]);

        // Keep only the columns that belong to this kind so switching kinds leaves no stray data.
        $common = ['kind', 'title', 'city', 'country', 'link', 'link_label', 'sort_order'];
        $byKind = [
            'calendar'   => ['start_date', 'end_date', 'type', 'description'],
            'conference' => ['start_date', 'end_date', 'conf_number', 'conf_year', 'dates_label', 'flag', 'theme', 'status', 'description'],
            'training'   => ['description', 'abbr', 'icon', 'category', 'frequency', 'latest'],
        ];
        $allowed = array_merge($common, $byKind[$kind]);
        $clean = [];
        foreach (array_merge($common, ...array_values($byKind)) as $col) {
            $v = in_array($col, $allowed, true) ? ($data[$col] ?? null) : null;
            $clean[$col] = ($v === '' ? null : $v);
        }

        if ($kind === 'calendar' && empty($clean['end_date'])) {
            $clean['end_date'] = $clean['start_date'];
        }
        if ($clean['sort_order'] === null) {
            // Blank = training programmes go to the end of the list; calendar/conference order by date/year anyway.
            $clean['sort_order'] = $kind === 'training' ? ((int) Event::where('kind', 'training')->max('sort_order') + 1) : 0;
        }
        $clean['is_published'] = $request->boolean('is_published');

        return $clean;
    }

    private function storeImage(Request $request): ?string
    {
        if (! $request->hasFile('image')) {
            return null;
        }
        $file = $request->file('image');
        $name = 'event-' . now()->format('Ymd-His') . '-' . bin2hex(random_bytes(4)) . '.' . ($file->extension() ?: 'jpg'); // extension from the real content, never the client's filename

        return $file->storeAs('events', $name, 'public');
    }

    private function deleteImage(?string $path): void
    {
        if ($path && ! preg_match('#^https?://#', $path)) {
            Storage::disk('public')->delete($path);
        }
    }
}
