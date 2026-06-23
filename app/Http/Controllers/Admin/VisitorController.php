<?php

namespace App\Http\Controllers\Admin;

use App\Http\Controllers\Concerns\AuthorizesAdminResource;
use App\Http\Controllers\Controller;
use App\Models\Visitor;
use Illuminate\Http\Request;

class VisitorController extends Controller
{
    use AuthorizesAdminResource;

    public function __construct()
    {
        $this->middleware('auth');
        $this->authorizeAdminResource('visitor');
    }

    public function index()
    {
        $visitors = Visitor::with(['student.user', 'registrar'])->paginate(15);
        return view('admin.pages.hostel.visitors.index', compact('visitors'));
    }

    public function create()
    {
        return view('admin.pages.hostel.visitors.create');
    }

    public function store(Request $request)
    {
        // Implementation
    }

    public function show(Visitor $visitor)
    {
        return view('admin.pages.hostel.visitors.show', compact('visitor'));
    }

    public function edit(Visitor $visitor)
    {
        return view('admin.pages.hostel.visitors.edit', compact('visitor'));
    }

    public function update(Request $request, Visitor $visitor)
    {
        // Implementation
    }

    public function destroy(Visitor $visitor)
    {
        // Implementation
    }
}
