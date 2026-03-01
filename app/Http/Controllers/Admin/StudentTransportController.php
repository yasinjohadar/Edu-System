<?php

namespace App\Http\Controllers\Admin;

use App\Http\Controllers\Controller;
use App\Models\StudentTransport;
use Illuminate\Http\Request;

class StudentTransportController extends Controller
{
    public function __construct()
    {
        $this->middleware('auth');
    }

    public function index()
    {
        $transports = StudentTransport::with(['student.user', 'route', 'driver', 'supervisor'])->paginate(15);
        return view('admin.pages.transport.student-transports.index', compact('transports'));
    }

    public function create()
    {
        return view('admin.pages.transport.student-transports.create');
    }

    public function store(Request $request)
    {
        // Implementation
    }

    public function show(StudentTransport $studentTransport)
    {
        return view('admin.pages.transport.student-transports.show', compact('studentTransport'));
    }

    public function edit(StudentTransport $studentTransport)
    {
        return view('admin.pages.transport.student-transports.edit', compact('studentTransport'));
    }

    public function update(Request $request, StudentTransport $studentTransport)
    {
        // Implementation
    }

    public function destroy(StudentTransport $studentTransport)
    {
        // Implementation
    }
}
