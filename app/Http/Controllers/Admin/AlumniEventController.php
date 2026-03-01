<?php

namespace App\Http\Controllers\Admin;

use App\Http\Controllers\Controller;
use App\Models\AlumniEvent;
use Illuminate\Http\Request;

class AlumniEventController extends Controller
{
    public function __construct()
    {
        $this->middleware('auth');
    }

    public function index()
    {
        $events = AlumniEvent::with('creator')->paginate(15);
        return view('admin.pages.alumni.events.index', compact('events'));
    }

    public function create()
    {
        return view('admin.pages.alumni.events.create');
    }

    public function store(Request $request)
    {
        // Implementation
    }

    public function show(AlumniEvent $alumniEvent)
    {
        return view('admin.pages.alumni.events.show', compact('alumniEvent'));
    }

    public function edit(AlumniEvent $alumniEvent)
    {
        return view('admin.pages.alumni.events.edit', compact('alumniEvent'));
    }

    public function update(Request $request, AlumniEvent $alumniEvent)
    {
        // Implementation
    }

    public function destroy(AlumniEvent $alumniEvent)
    {
        // Implementation
    }
}
