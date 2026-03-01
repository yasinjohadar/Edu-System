<?php

namespace App\Http\Controllers\Admin;

use App\Http\Controllers\Controller;
use App\Models\Supervisor;
use Illuminate\Http\Request;

class SupervisorController extends Controller
{
    public function __construct()
    {
        $this->middleware('auth');
    }

    public function index()
    {
        $supervisors = Supervisor::with('user')->paginate(15);
        return view('admin.pages.transport.supervisors.index', compact('supervisors'));
    }

    public function create()
    {
        return view('admin.pages.transport.supervisors.create');
    }

    public function store(Request $request)
    {
        // Implementation
    }

    public function show(Supervisor $supervisor)
    {
        return view('admin.pages.transport.supervisors.show', compact('supervisor'));
    }

    public function edit(Supervisor $supervisor)
    {
        return view('admin.pages.transport.supervisors.edit', compact('supervisor'));
    }

    public function update(Request $request, Supervisor $supervisor)
    {
        // Implementation
    }

    public function destroy(Supervisor $supervisor)
    {
        // Implementation
    }
}
