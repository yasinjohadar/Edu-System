<?php

namespace App\Http\Controllers\Admin;

use App\Http\Controllers\Controller;
use App\Models\Subject;
use App\Models\ClassModel;
use Illuminate\Http\Request;

class SubjectController extends Controller
{
    public function __construct()
    {
        $this->middleware('auth');
    }

    public function index(Request $request)
    {
        $subjectsQuery = Subject::query()->orderBy('name');

        if ($request->filled('query')) {
            $search = $request->input('query');
            $subjectsQuery->where(function ($q) use ($search) {
                $q->where('name', 'like', "%$search%")
                  ->orWhere('name_en', 'like', "%$search%")
                  ->orWhere('code', 'like', "%$search%");
            });
        }

        if ($request->filled('type')) {
            $subjectsQuery->where('type', $request->input('type'));
        }

        if ($request->filled('is_active')) {
            $subjectsQuery->where('is_active', $request->input('is_active'));
        }

        $subjects = $subjectsQuery->paginate(10);

        return view('admin.pages.subjects.index', compact('subjects'));
    }

    public function create()
    {
        $classes = ClassModel::with('grade')->where('is_active', true)->orderBy('order')->get();
        return view('admin.pages.subjects.create', compact('classes'));
    }

    public function store(Request $request)
    {
        $request->validate([
            'name' => 'required|string|max:255',
            'name_en' => 'nullable|string|max:255',
            'code' => 'nullable|string|max:255|unique:subjects,code',
            'type' => 'required|in:required,optional',
            'weekly_hours' => 'nullable|integer|min:0',
            'full_marks' => 'nullable|numeric|min:0',
            'pass_marks' => 'nullable|numeric|min:0',
            'is_active' => 'boolean',
            'description' => 'nullable|string',
            'classes' => 'nullable|array',
            'classes.*' => 'exists:classes,id',
        ]);

        $subject = Subject::create($request->except('classes'));

        if ($request->has('classes')) {
            $subject->classes()->attach($request->classes);
        }

        return redirect()->route('admin.subjects.index')->with('success', 'تم إنشاء المادة بنجاح');
    }

    public function show(string $id)
    {
        $subject = Subject::with('classes.grade')->findOrFail($id);
        return view('admin.pages.subjects.show', compact('subject'));
    }

    public function edit(string $id)
    {
        $subject = Subject::with('classes')->findOrFail($id);
        $classes = ClassModel::with('grade')->where('is_active', true)->orderBy('order')->get();
        return view('admin.pages.subjects.edit', compact('subject', 'classes'));
    }

    public function update(Request $request, string $id)
    {
        $subject = Subject::findOrFail($id);

        $request->validate([
            'name' => 'required|string|max:255',
            'name_en' => 'nullable|string|max:255',
            'code' => 'nullable|string|max:255|unique:subjects,code,' . $id,
            'type' => 'required|in:required,optional',
            'weekly_hours' => 'nullable|integer|min:0',
            'full_marks' => 'nullable|numeric|min:0',
            'pass_marks' => 'nullable|numeric|min:0',
            'is_active' => 'boolean',
            'description' => 'nullable|string',
            'classes' => 'nullable|array',
            'classes.*' => 'exists:classes,id',
        ]);

        $subject->update($request->except('classes'));

        if ($request->has('classes')) {
            $subject->classes()->sync($request->classes);
        } else {
            $subject->classes()->detach();
        }

        return redirect()->route('admin.subjects.index')->with('success', 'تم تحديث المادة بنجاح');
    }

    public function destroy(string $id)
    {
        $subject = Subject::findOrFail($id);
        $subject->delete();

        return redirect()->route('admin.subjects.index')->with('success', 'تم حذف المادة بنجاح');
    }
}
