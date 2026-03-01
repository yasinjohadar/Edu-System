<?php

namespace App\Http\Controllers\Admin;

use App\Http\Controllers\Controller;
use App\Models\Driver;
use Illuminate\Http\Request;

class DriverController extends Controller
{
    public function __construct()
    {
        $this->middleware('auth');
    }

    public function index()
    {
        $drivers = Driver::with('user')->paginate(15);
        return view('admin.pages.transport.drivers.index', compact('drivers'));
    }

    public function create()
    {
        return view('admin.pages.transport.drivers.create');
    }

    public function store(Request $request)
    {
        // Implementation
    }

    public function show(Driver $driver)
    {
        return view('admin.pages.transport.drivers.show', compact('driver'));
    }

    public function edit(Driver $driver)
    {
        return view('admin.pages.transport.drivers.edit', compact('driver'));
    }

    public function update(Request $request, Driver $driver)
    {
        // Implementation
    }

    public function destroy(Driver $driver)
    {
        // Implementation
    }
}
