<?php

namespace App\Http\Controllers\Admin;

use App\Http\Controllers\Controller;
use App\Models\Alumni;
use Illuminate\Http\Request;

class AlumniController extends Controller
{
    public function __construct()
    {
        $this->middleware('auth');
    }

    public function index()
    {
        $alumni = Alumni::with('student.user')->paginate(15);
        return view('admin.pages.alumni.index', compact('alumni'));
    }

    public function create()
    {
        $students = \App\Models\Student::with('user')->get();
        return view('admin.pages.alumni.create', compact('students'));
    }

    public function store(Request $request)
    {
        $request->validate([
            'name' => 'required|string|max:255',
            'email' => 'required|string|email|max:255|unique:alumni,email',
            'phone' => 'nullable|string|max:20',
            'graduation_date' => 'required|date',
            'degree' => 'nullable|string|max:255',
            'major' => 'nullable|string|max:255',
            'current_job' => 'nullable|string|max:255',
            'company' => 'nullable|string|max:255',
            'address' => 'nullable|string',
            'student_id' => 'nullable|exists:students,id',
            'is_active' => 'boolean',
        ]);

        Alumni::create([
            'student_id' => $request->student_id,
            'name' => $request->name,
            'email' => $request->email,
            'phone' => $request->phone,
            'graduation_date' => $request->graduation_date,
            'degree' => $request->degree,
            'major' => $request->major,
            'current_job' => $request->current_job,
            'company' => $request->company,
            'address' => $request->address,
            'is_active' => $request->has('is_active'),
        ]);

        return redirect()->route('admin.alumni.index')->with('success', 'تم إضافة الخريج بنجاح');
    }

    public function show(Alumni $alumni)
    {
        $alumni->load('student.user');
        return view('admin.pages.alumni.show', compact('alumni'));
    }

    public function edit(Alumni $alumni)
    {
        return view('admin.pages.alumni.edit', compact('alumni'));
    }

    public function update(Request $request, Alumni $alumni)
    {
        $request->validate([
            'name' => 'required|string|max:255',
            'email' => 'required|string|email|max:255|unique:alumni,email,' . $alumni->id,
            'phone' => 'nullable|string|max:20',
            'graduation_date' => 'required|date',
            'degree' => 'nullable|string|max:255',
            'major' => 'nullable|string|max:255',
            'current_job' => 'nullable|string|max:255',
            'company' => 'nullable|string|max:255',
            'address' => 'nullable|string',
            'is_active' => 'boolean',
        ]);

        $alumni->update([
            'name' => $request->name,
            'email' => $request->email,
            'phone' => $request->phone,
            'graduation_date' => $request->graduation_date,
            'degree' => $request->degree,
            'major' => $request->major,
            'current_job' => $request->current_job,
            'company' => $request->company,
            'address' => $request->address,
            'is_active' => $request->has('is_active'),
        ]);

        return redirect()->route('admin.alumni.index')->with('success', 'تم تحديث بيانات الخريج بنجاح');
    }

    public function destroy(Alumni $alumni)
    {
        $alumni->delete();
        return redirect()->route('admin.alumni.index')->with('success', 'تم حذف الخريج بنجاح');
    }
}
