<?php

namespace App\Http\Controllers\Admin;

use App\Http\Controllers\Controller;
use App\Models\ClassModel;
use App\Models\Section;
use App\Models\User;
use Illuminate\Http\Request;

class SectionController extends Controller
{
    public function __construct()
    {
        $this->middleware('auth');
    }

    public function index(Request $request)
    {
        $sectionsQuery = Section::with('class.grade', 'classTeacher')->orderBy('name');

        if ($request->filled('query')) {
            $search = $request->input('query');
            $sectionsQuery->where(function ($q) use ($search) {
                $q->where('name', 'like', "%$search%")
                  ->orWhere('name_en', 'like', "%$search%");
            });
        }

        if ($request->filled('class_id')) {
            $sectionsQuery->where('class_id', $request->input('class_id'));
        }

        if ($request->filled('is_active')) {
            $sectionsQuery->where('is_active', $request->input('is_active'));
        }

        $sections = $sectionsQuery->paginate(10);
        $classes = ClassModel::with('grade')->where('is_active', true)->orderBy('order')->get();

        return view('admin.pages.sections.index', compact('sections', 'classes'));
    }

    public function create()
    {
        $classes = ClassModel::with('grade')->where('is_active', true)->orderBy('order')->get();
        $teachers = User::whereHas('roles', function($q) {
            $q->where('name', 'teacher');
        })->get();
        return view('admin.pages.sections.create', compact('classes', 'teachers'));
    }

    public function store(Request $request)
    {
        $request->validate([
            'class_id' => 'required|exists:classes,id',
            'name' => 'required|string|max:255',
            'name_en' => 'nullable|string|max:255',
            'capacity' => 'required|integer|min:1',
            'class_teacher_id' => 'nullable|exists:users,id',
            'is_active' => 'boolean',
            'description' => 'nullable|string',
        ]);

        Section::create($request->all());

        return redirect()->route('admin.sections.index')->with('success', 'تم إنشاء الفصل بنجاح');
    }

    public function show(string $id)
    {
        $section = Section::with('class.grade', 'classTeacher', 'students')->findOrFail($id);
        return view('admin.pages.sections.show', compact('section'));
    }

    public function edit(string $id)
    {
        $section = Section::findOrFail($id);
        $classes = ClassModel::with('grade')->where('is_active', true)->orderBy('order')->get();
        $teachers = User::whereHas('roles', function($q) {
            $q->where('name', 'teacher');
        })->get();
        return view('admin.pages.sections.edit', compact('section', 'classes', 'teachers'));
    }

    public function update(Request $request, string $id)
    {
        $section = Section::findOrFail($id);

        $request->validate([
            'class_id' => 'required|exists:classes,id',
            'name' => 'required|string|max:255',
            'name_en' => 'nullable|string|max:255',
            'capacity' => 'required|integer|min:1',
            'class_teacher_id' => 'nullable|exists:users,id',
            'is_active' => 'boolean',
            'description' => 'nullable|string',
        ]);

        $section->update($request->all());

        return redirect()->route('admin.sections.index')->with('success', 'تم تحديث الفصل بنجاح');
    }

    public function destroy(string $id)
    {
        $section = Section::findOrFail($id);
        $section->delete();

        return redirect()->route('admin.sections.index')->with('success', 'تم حذف الفصل بنجاح');
    }
}
