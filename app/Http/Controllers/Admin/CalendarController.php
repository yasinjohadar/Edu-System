<?php

namespace App\Http\Controllers\Admin;

use App\Http\Controllers\Controller;
use App\Models\Event;
use Illuminate\Http\Request;
use Carbon\Carbon;

class CalendarController extends Controller
{
    public function __construct()
    {
        $this->middleware('auth');
    }

    /**
     * عرض التقويم الشهري
     */
    public function month(Request $request)
    {
        $year = $request->get('year', date('Y'));
        $month = $request->get('month', date('m'));

        $startDate = Carbon::create($year, $month, 1)->startOfMonth();
        $endDate = $startDate->copy()->endOfMonth();

        $events = Event::where('is_active', true)
            ->whereBetween('start_date', [$startDate, $endDate])
            ->orWhere(function($query) use ($startDate, $endDate) {
                $query->where('end_date', '>=', $startDate)
                      ->where('start_date', '<=', $endDate);
            })
            ->with('category')
            ->get();

        return view('admin.pages.events.calendar', compact('events', 'year', 'month', 'startDate', 'endDate'));
    }

    /**
     * عرض التقويم الأسبوعي
     */
    public function week(Request $request)
    {
        $date = $request->get('date', date('Y-m-d'));
        $startDate = Carbon::parse($date)->startOfWeek();
        $endDate = $startDate->copy()->endOfWeek();

        $events = Event::where('is_active', true)
            ->whereBetween('start_date', [$startDate, $endDate])
            ->orWhere(function($query) use ($startDate, $endDate) {
                $query->where('end_date', '>=', $startDate)
                      ->where('start_date', '<=', $endDate);
            })
            ->with('category')
            ->get();

        return view('admin.pages.events.calendar-week', compact('events', 'startDate', 'endDate'));
    }

    /**
     * عرض التقويم اليومي
     */
    public function day(Request $request)
    {
        $date = $request->get('date', date('Y-m-d'));
        $selectedDate = Carbon::parse($date);

        $events = Event::where('is_active', true)
            ->whereDate('start_date', '<=', $selectedDate)
            ->where(function($query) use ($selectedDate) {
                $query->whereNull('end_date')
                      ->orWhereDate('end_date', '>=', $selectedDate);
            })
            ->with('category')
            ->get();

        return view('admin.pages.events.calendar-day', compact('events', 'selectedDate'));
    }
}
