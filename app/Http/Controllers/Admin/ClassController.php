<?php

namespace App\Http\Controllers\Admin;

use App\Http\Controllers\Concerns\AuthorizesAdminResource;
use App\Http\Controllers\Controller;
use App\Models\Grade;
use App\Models\ClassModel;
use Illuminate\Http\Request;

class ClassController extends Controller
{
    use AuthorizesAdminResource;

    public function __construct()
    {
        $this->middleware('auth');
        $this->authorizeAdminResource('class');
    }

    public function index(Request $request)
    {
        $classes = $this->buildQuery($request)->paginate(10)->withQueryString();
        $grades = Grade::where('is_active', true)->orderBy('order')->get();

        if ($request->ajax()) {
            return response()->json([
                'body' => view('admin.partials.classes-table-body', compact('classes'))->render(),
                'extra' => view('admin.partials.classes-table-footer', compact('classes'))->render(),
                'from' => $classes->firstItem(),
                'to' => $classes->lastItem(),
                'total' => $classes->total(),
            ]);
        }

        return view('admin.pages.classes.index', compact('classes', 'grades'));
    }

    private function buildQuery(Request $request)
    {
        $classesQuery = ClassModel::with('grade')->orderBy('order');

        if ($request->filled('query')) {
            $search = $request->input('query');
            $classesQuery->where(function ($q) use ($search) {
                $q->where('name', 'like', "%{$search}%")
                    ->orWhere('name_en', 'like', "%{$search}%");
            });
        }

        if ($request->filled('grade_id')) {
            $classesQuery->where('grade_id', $request->input('grade_id'));
        }

        if ($request->filled('is_active')) {
            $classesQuery->where('is_active', $request->input('is_active'));
        }

        return $classesQuery;
    }

    public function create()
    {
        $grades = Grade::where('is_active', true)->orderBy('order')->get();
        return view('admin.pages.classes.create', compact('grades'));
    }

    public function store(Request $request)
    {
        $request->validate([
            'grade_id' => 'required|exists:grades,id',
            'name' => 'required|string|max:255',
            'name_en' => 'nullable|string|max:255',
            'order' => 'nullable|integer',
            'is_active' => 'boolean',
            'description' => 'nullable|string',
        ]);

        ClassModel::create([
            ...$request->except('is_active'),
            'is_active' => $request->has('is_active'),
        ]);

        return redirect()->route('admin.classes.index')->with('success', 'تم إنشاء الصف بنجاح');
    }

    public function show(string $id)
    {
        $class = ClassModel::with('grade', 'sections')->findOrFail($id);
        return view('admin.pages.classes.show', compact('class'));
    }

    public function edit(string $id)
    {
        $class = ClassModel::findOrFail($id);
        $grades = Grade::where('is_active', true)->orderBy('order')->get();
        return view('admin.pages.classes.edit', compact('class', 'grades'));
    }

    public function update(Request $request, string $id)
    {
        $class = ClassModel::findOrFail($id);

        $request->validate([
            'grade_id' => 'required|exists:grades,id',
            'name' => 'required|string|max:255',
            'name_en' => 'nullable|string|max:255',
            'order' => 'nullable|integer',
            'is_active' => 'boolean',
            'description' => 'nullable|string',
        ]);

        $class->update([
            ...$request->except('is_active'),
            'is_active' => $request->has('is_active'),
        ]);

        return redirect()->route('admin.classes.index')->with('success', 'تم تحديث الصف بنجاح');
    }

    public function destroy(string $id)
    {
        $class = ClassModel::findOrFail($id);
        $class->delete();

        return redirect()->route('admin.classes.index')->with('success', 'تم حذف الصف بنجاح');
    }
}
