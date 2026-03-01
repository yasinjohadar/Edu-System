<?php

namespace App\Http\Controllers\Admin;

use App\Http\Controllers\Controller;
use App\Models\BusStop;
use Illuminate\Http\Request;

class BusStopController extends Controller
{
    public function __construct()
    {
        $this->middleware('auth');
    }

    public function index()
    {
        $stops = BusStop::with('route')->paginate(15);
        return view('admin.pages.transport.bus-stops.index', compact('stops'));
    }

    public function create()
    {
        return view('admin.pages.transport.bus-stops.create');
    }

    public function store(Request $request)
    {
        // Implementation
    }

    public function show(BusStop $busStop)
    {
        return view('admin.pages.transport.bus-stops.show', compact('busStop'));
    }

    public function edit(BusStop $busStop)
    {
        return view('admin.pages.transport.bus-stops.edit', compact('busStop'));
    }

    public function update(Request $request, BusStop $busStop)
    {
        // Implementation
    }

    public function destroy(BusStop $busStop)
    {
        // Implementation
    }
}
