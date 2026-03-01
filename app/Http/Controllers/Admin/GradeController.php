<?php

namespace App\Http\Controllers\Admin;

use App\Http\Controllers\Controller;
use App\Models\Grade;
use Illuminate\Http\Request;

class GradeController extends Controller
{
    public function __construct()
    {
        $this->middleware('auth');
    }

    /**
     * Display a listing of the resource.
     */
    public function index(Request $request)
    {
        $gradesQuery = Grade::query()->orderBy('order');

        if ($request->filled('query')) {
            $search = $request->input('query');
            $gradesQuery->where(function ($q) use ($search) {
                $q->where('name', 'like', "%$search%")
                  ->orWhere('name_en', 'like', "%$search%");
            });
        }

        if ($request->filled('is_active')) {
            $gradesQuery->where('is_active', $request->input('is_active'));
        }

        $grades = $gradesQuery->paginate(10);

        return view('admin.pages.grades.index', compact('grades'));
    }

    /**
     * Show the form for creating a new resource.
     */
    public function create()
    {
        return view('admin.pages.grades.create');
    }

    /**
     * Store a newly created resource in storage.
     */
    public function store(Request $request)
    {
        $request->validate([
            'name' => 'required|string|max:255',
            'name_en' => 'nullable|string|max:255',
            'min_age' => 'nullable|integer|min:0',
            'max_age' => 'nullable|integer|min:0',
            'fees' => 'nullable|numeric|min:0',
            'order' => 'nullable|integer',
            'is_active' => 'boolean',
            'description' => 'nullable|string',
        ]);

        Grade::create($request->all());

        return redirect()->route('grades.index')->with('success', 'تم إنشاء المرحلة بنجاح');
    }

    /**
     * Display the specified resource.
     */
    public function show(string $id)
    {
        $grade = Grade::findOrFail($id);
        return view('admin.pages.grades.show', compact('grade'));
    }

    /**
     * Show the form for editing the specified resource.
     */
    public function edit(string $id)
    {
        $grade = Grade::findOrFail($id);
        return view('admin.pages.grades.edit', compact('grade'));
    }

    /**
     * Update the specified resource in storage.
     */
    public function update(Request $request, string $id)
    {
        $grade = Grade::findOrFail($id);

        $request->validate([
            'name' => 'required|string|max:255',
            'name_en' => 'nullable|string|max:255',
            'min_age' => 'nullable|integer|min:0',
            'max_age' => 'nullable|integer|min:0',
            'fees' => 'nullable|numeric|min:0',
            'order' => 'nullable|integer',
            'is_active' => 'boolean',
            'description' => 'nullable|string',
        ]);

        $grade->update($request->all());

        return redirect()->route('grades.index')->with('success', 'تم تحديث المرحلة بنجاح');
    }

    /**
     * Remove the specified resource from storage.
     */
    public function destroy(string $id)
    {
        $grade = Grade::findOrFail($id);
        $grade->delete();

        return redirect()->route('grades.index')->with('success', 'تم حذف المرحلة بنجاح');
    }
}
