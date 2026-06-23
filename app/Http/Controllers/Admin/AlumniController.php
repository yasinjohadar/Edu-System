<?php

namespace App\Http\Controllers\Admin;

use App\Http\Controllers\Concerns\AuthorizesAdminResource;
use App\Http\Controllers\Controller;
use App\Models\Alumni;
use App\Models\Student;
use Illuminate\Http\Request;
use Illuminate\Validation\Rule;

class AlumniController extends Controller
{
    use AuthorizesAdminResource;

    public function __construct()
    {
        $this->middleware('auth');
        $this->authorizeAdminResource('alumni');
    }

    public function index(Request $request)
    {
        $alumni = Alumni::with('student.user')
            ->when($request->filled('search'), function ($q) use ($request) {
                $search = $request->search;
                $q->where(function ($query) use ($search) {
                    $query->where('name', 'like', "%{$search}%")
                        ->orWhere('email', 'like', "%{$search}%")
                        ->orWhere('company', 'like', "%{$search}%");
                });
            })
            ->when($request->filled('is_active'), fn ($q) => $q->where('is_active', $request->is_active === '1'))
            ->orderByDesc('graduation_date')
            ->paginate(15)
            ->withQueryString();

        return view('admin.pages.alumni.index', compact('alumni'));
    }

    public function create()
    {
        return view('admin.pages.alumni.create', $this->formData());
    }

    public function store(Request $request)
    {
        $validated = $request->validate($this->rules());
        $validated = $this->mergeStudentDefaults($validated);

        Alumni::create([
            ...$validated,
            'is_active' => (bool) $request->input('is_active', 1),
        ]);

        return redirect()->route('admin.alumni.index')
            ->with('success', 'تم إضافة الخريج بنجاح.');
    }

    public function show(Alumni $alumni)
    {
        $alumni->load(['student.user', 'donations']);

        return view('admin.pages.alumni.show', compact('alumni'));
    }

    public function edit(Alumni $alumni)
    {
        return view('admin.pages.alumni.edit', [
            'alumni' => $alumni,
            ...$this->formData(),
        ]);
    }

    public function update(Request $request, Alumni $alumni)
    {
        $validated = $request->validate($this->rules($alumni->id));
        $validated = $this->mergeStudentDefaults($validated);

        $alumni->update([
            ...$validated,
            'is_active' => (bool) $request->input('is_active', 0),
        ]);

        return redirect()->route('admin.alumni.index')
            ->with('success', 'تم تحديث بيانات الخريج بنجاح.');
    }

    public function destroy(Alumni $alumni)
    {
        if ($alumni->donations()->exists()) {
            return redirect()->route('admin.alumni.index')
                ->with('error', 'لا يمكن حذف الخريج لأنه مرتبط بتبرعات. احذف التبرعات أولاً أو عطّل الحساب.');
        }

        $alumni->delete();

        return redirect()->route('admin.alumni.index')
            ->with('success', 'تم حذف الخريج بنجاح.');
    }

    private function formData(): array
    {
        return [
            'students' => Student::with('user')->where('status', 'active')->orderBy('student_code')->get(),
        ];
    }

    private function rules(?int $ignoreId = null): array
    {
        return [
            'student_id' => 'nullable|exists:students,id',
            'name' => 'required|string|max:255',
            'email' => [
                'required',
                'email',
                'max:255',
                Rule::unique('alumni', 'email')->ignore($ignoreId),
            ],
            'phone' => 'nullable|string|max:20',
            'graduation_date' => 'required|date',
            'degree' => 'nullable|string|max:255',
            'major' => 'nullable|string|max:255',
            'current_job' => 'nullable|string|max:255',
            'company' => 'nullable|string|max:255',
            'address' => 'nullable|string|max:1000',
        ];
    }

    private function mergeStudentDefaults(array $validated): array
    {
        if (empty($validated['student_id'])) {
            return $validated;
        }

        $student = Student::with('user')->find($validated['student_id']);

        if (! $student?->user) {
            return $validated;
        }

        if (empty($validated['name'])) {
            $validated['name'] = $student->user->name;
        }

        if (empty($validated['email']) && $student->user->email) {
            $validated['email'] = $student->user->email;
        }

        return $validated;
    }
}
