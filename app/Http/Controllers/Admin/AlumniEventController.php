<?php

namespace App\Http\Controllers\Admin;

use App\Http\Controllers\Concerns\AuthorizesAdminResource;
use App\Http\Controllers\Controller;
use App\Models\AlumniEvent;
use Illuminate\Http\Request;
use Illuminate\Validation\Rule;

class AlumniEventController extends Controller
{
    use AuthorizesAdminResource;

    public function __construct()
    {
        $this->middleware('auth');
        $this->authorizeAdminResource('alumni-event');
    }

    public function index(Request $request)
    {
        $events = AlumniEvent::with('creator')
            ->when($request->filled('type'), fn ($q) => $q->where('type', $request->type))
            ->when($request->filled('search'), fn ($q) => $q->where('title', 'like', '%' . $request->search . '%'))
            ->orderByDesc('event_date')
            ->paginate(15)
            ->withQueryString();

        return view('admin.pages.alumni.events.index', [
            'events' => $events,
            'types' => $this->types(),
        ]);
    }

    public function create()
    {
        return view('admin.pages.alumni.events.create', ['types' => $this->types()]);
    }

    public function store(Request $request)
    {
        $validated = $request->validate($this->rules());

        AlumniEvent::create([
            ...$validated,
            'is_active' => (bool) $request->input('is_active', 1),
            'created_by' => auth()->id(),
        ]);

        return redirect()->route('admin.alumni-events.index')
            ->with('success', 'تم إضافة الحدث بنجاح.');
    }

    public function show(AlumniEvent $alumniEvent)
    {
        $alumniEvent->load('creator');

        return view('admin.pages.alumni.events.show', [
            'event' => $alumniEvent,
            'types' => $this->types(),
        ]);
    }

    public function edit(AlumniEvent $alumniEvent)
    {
        return view('admin.pages.alumni.events.edit', [
            'event' => $alumniEvent,
            'types' => $this->types(),
        ]);
    }

    public function update(Request $request, AlumniEvent $alumniEvent)
    {
        $validated = $request->validate($this->rules());

        $alumniEvent->update([
            ...$validated,
            'is_active' => (bool) $request->input('is_active', 0),
        ]);

        return redirect()->route('admin.alumni-events.index')
            ->with('success', 'تم تحديث الحدث بنجاح.');
    }

    public function destroy(AlumniEvent $alumniEvent)
    {
        $alumniEvent->delete();

        return redirect()->route('admin.alumni-events.index')
            ->with('success', 'تم حذف الحدث بنجاح.');
    }

    private function rules(): array
    {
        return [
            'title' => 'required|string|max:255',
            'description' => 'nullable|string|max:5000',
            'event_date' => 'required|date',
            'event_time' => 'nullable|date_format:H:i',
            'location' => 'nullable|string|max:255',
            'type' => ['required', Rule::in(array_keys($this->types()))],
            'max_attendees' => 'nullable|integer|min:1',
            'fee' => 'nullable|numeric|min:0',
        ];
    }

    private function types(): array
    {
        return [
            'reunion' => 'لقاء تخرج',
            'networking' => 'تواصل مهني',
            'workshop' => 'ورشة عمل',
            'seminar' => 'ندوة',
            'other' => 'أخرى',
        ];
    }
}
