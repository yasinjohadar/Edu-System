<?php

namespace App\Http\Controllers\Admin;

use App\Http\Controllers\Controller;
use App\Models\BusRoute;
use Illuminate\Http\Request;

class BusRouteController extends Controller
{
    public function __construct()
    {
        $this->middleware('auth');
    }

    public function index()
    {
        $routes = BusRoute::with('stops')->paginate(15);
        return view('admin.pages.transport.bus-routes.index', compact('routes'));
    }

    public function create()
    {
        return view('admin.pages.transport.bus-routes.create');
    }

    public function store(Request $request)
    {
        // Implementation
    }

    public function show(BusRoute $busRoute)
    {
        return view('admin.pages.transport.bus-routes.show', compact('busRoute'));
    }

    public function edit(BusRoute $busRoute)
    {
        return view('admin.pages.transport.bus-routes.edit', compact('busRoute'));
    }

    public function update(Request $request, BusRoute $busRoute)
    {
        // Implementation
    }

    public function destroy(BusRoute $busRoute)
    {
        // Implementation
    }
}
