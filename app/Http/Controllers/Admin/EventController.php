<?php

namespace App\Http\Controllers\Admin;

use App\Http\Controllers\Controller;
use App\Models\Event;
use App\Models\EventCategory;
use App\Models\ClassModel;
use App\Models\Section;
use Illuminate\Http\Request;
use Illuminate\Support\Facades\DB;

class EventController extends Controller
{
    public function __construct()
    {
        $this->middleware('auth');
    }

    /**
     * Display a listing of the resource.
     */
    public function index(Request $request)
    {
        $eventsQuery = Event::with(['category', 'creator'])->orderBy('start_date', 'desc');

        if ($request->filled('type')) {
            $eventsQuery->where('type', $request->type);
        }

        if ($request->filled('category_id')) {
            $eventsQuery->where('category_id', $request->category_id);
        }

        if ($request->filled('date_from')) {
            $eventsQuery->whereDate('start_date', '>=', $request->date_from);
        }

        if ($request->filled('date_to')) {
            $eventsQuery->whereDate('start_date', '<=', $request->date_to);
        }

        $events = $eventsQuery->paginate(15);
        $categories = EventCategory::where('is_active', true)->orderBy('order')->get();

        return view('admin.pages.events.index', compact('events', 'categories'));
    }

    /**
     * Show the form for creating a new resource.
     */
    public function create()
    {
        $categories = EventCategory::where('is_active', true)->orderBy('order')->get();
        $classes = ClassModel::with('grade')->where('is_active', true)->get();
        $sections = Section::with('class.grade')->where('is_active', true)->get();

        return view('admin.pages.events.create', compact('categories', 'classes', 'sections'));
    }

    /**
     * Store a newly created resource in storage.
     */
    public function store(Request $request)
    {
        $request->validate([
            'title' => 'required|string|max:255',
            'category_id' => 'nullable|exists:event_categories,id',
            'description' => 'nullable|string',
            'start_date' => 'required|date',
            'end_date' => 'nullable|date|after_or_equal:start_date',
            'start_time' => 'nullable|date_format:H:i',
            'end_time' => 'nullable|date_format:H:i|after:start_time',
            'location' => 'nullable|string|max:255',
            'type' => 'required|in:holiday,exam,activity,meeting,other',
            'recurrence' => 'required|in:none,daily,weekly,monthly,yearly',
            'recurrence_end_date' => 'nullable|date|after:start_date',
            'is_all_day' => 'boolean',
            'target_audience' => 'nullable|array',
            'notes' => 'nullable|string',
        ]);

        $event = Event::create([
            'category_id' => $request->category_id,
            'title' => $request->title,
            'description' => $request->description,
            'start_date' => $request->start_date,
            'end_date' => $request->end_date ?? $request->start_date,
            'start_time' => $request->start_time,
            'end_time' => $request->end_time,
            'location' => $request->location,
            'type' => $request->type,
            'recurrence' => $request->recurrence,
            'recurrence_end_date' => $request->recurrence_end_date,
            'is_all_day' => $request->has('is_all_day'),
            'target_audience' => $request->target_audience,
            'notes' => $request->notes,
            'created_by' => auth()->id(),
        ]);

        return redirect()->route('admin.events.index')->with('success', 'تم إنشاء الحدث بنجاح');
    }

    /**
     * Display the specified resource.
     */
    public function show(Event $event)
    {
        $event->load(['category', 'creator']);
        return view('admin.pages.events.show', compact('event'));
    }

    /**
     * Show the form for editing the specified resource.
     */
    public function edit(Event $event)
    {
        $categories = EventCategory::where('is_active', true)->orderBy('order')->get();
        $classes = ClassModel::with('grade')->where('is_active', true)->get();
        $sections = Section::with('class.grade')->where('is_active', true)->get();

        return view('admin.pages.events.edit', compact('event', 'categories', 'classes', 'sections'));
    }

    /**
     * Update the specified resource in storage.
     */
    public function update(Request $request, Event $event)
    {
        $request->validate([
            'title' => 'required|string|max:255',
            'category_id' => 'nullable|exists:event_categories,id',
            'description' => 'nullable|string',
            'start_date' => 'required|date',
            'end_date' => 'nullable|date|after_or_equal:start_date',
            'start_time' => 'nullable|date_format:H:i',
            'end_time' => 'nullable|date_format:H:i|after:start_time',
            'location' => 'nullable|string|max:255',
            'type' => 'required|in:holiday,exam,activity,meeting,other',
            'recurrence' => 'required|in:none,daily,weekly,monthly,yearly',
            'recurrence_end_date' => 'nullable|date|after:start_date',
            'is_all_day' => 'boolean',
            'target_audience' => 'nullable|array',
            'notes' => 'nullable|string',
        ]);

        $event->update([
            'category_id' => $request->category_id,
            'title' => $request->title,
            'description' => $request->description,
            'start_date' => $request->start_date,
            'end_date' => $request->end_date ?? $request->start_date,
            'start_time' => $request->start_time,
            'end_time' => $request->end_time,
            'location' => $request->location,
            'type' => $request->type,
            'recurrence' => $request->recurrence,
            'recurrence_end_date' => $request->recurrence_end_date,
            'is_all_day' => $request->has('is_all_day'),
            'target_audience' => $request->target_audience,
            'notes' => $request->notes,
        ]);

        return redirect()->route('admin.events.index')->with('success', 'تم تحديث الحدث بنجاح');
    }

    /**
     * Remove the specified resource from storage.
     */
    public function destroy(Event $event)
    {
        $event->delete();
        return redirect()->route('admin.events.index')->with('success', 'تم حذف الحدث بنجاح');
    }
}
