<?php

namespace App\Http\Controllers\Admin;

use App\Http\Controllers\Controller;
use App\Models\Hostel;
use Illuminate\Http\Request;

class HostelController extends Controller
{
    public function __construct()
    {
        $this->middleware('auth');
    }

    public function index()
    {
        $hostels = Hostel::with('rooms')->paginate(15);
        return view('admin.pages.hostel.hostels.index', compact('hostels'));
    }

    public function create()
    {
        return view('admin.pages.hostel.hostels.create');
    }

    public function store(Request $request)
    {
        // Implementation
    }

    public function show(Hostel $hostel)
    {
        return view('admin.pages.hostel.hostels.show', compact('hostel'));
    }

    public function edit(Hostel $hostel)
    {
        return view('admin.pages.hostel.hostels.edit', compact('hostel'));
    }

    public function update(Request $request, Hostel $hostel)
    {
        // Implementation
    }

    public function destroy(Hostel $hostel)
    {
        // Implementation
    }
}
