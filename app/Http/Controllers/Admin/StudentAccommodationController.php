<?php

namespace App\Http\Controllers\Admin;

use App\Http\Controllers\Controller;
use App\Models\StudentAccommodation;
use Illuminate\Http\Request;

class StudentAccommodationController extends Controller
{
    public function __construct()
    {
        $this->middleware('auth');
    }

    public function index()
    {
        $accommodations = StudentAccommodation::with(['student.user', 'hostel', 'room', 'bed'])->paginate(15);
        return view('admin.pages.hostel.accommodations.index', compact('accommodations'));
    }

    public function create()
    {
        return view('admin.pages.hostel.accommodations.create');
    }

    public function store(Request $request)
    {
        // Implementation
    }

    public function show(StudentAccommodation $studentAccommodation)
    {
        return view('admin.pages.hostel.accommodations.show', compact('studentAccommodation'));
    }

    public function edit(StudentAccommodation $studentAccommodation)
    {
        return view('admin.pages.hostel.accommodations.edit', compact('studentAccommodation'));
    }

    public function update(Request $request, StudentAccommodation $studentAccommodation)
    {
        // Implementation
    }

    public function destroy(StudentAccommodation $studentAccommodation)
    {
        // Implementation
    }
}
