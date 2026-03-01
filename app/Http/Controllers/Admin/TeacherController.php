<?php

namespace App\Http\Controllers\Admin;

use App\Http\Controllers\Controller;
use App\Models\Teacher;
use App\Models\User;
use App\Models\Subject;
use Spatie\Permission\Models\Role;
use Illuminate\Http\Request;
use Illuminate\Support\Facades\Hash;

class TeacherController extends Controller
{
    public function __construct()
    {
        $this->middleware('auth');
    }

    public function index(Request $request)
    {
        $teachersQuery = Teacher::with('user')->orderBy('created_at', 'desc');

        if ($request->filled('query')) {
            $search = $request->input('query');
            $teachersQuery->whereHas('user', function ($q) use ($search) {
                $q->where('name', 'like', "%$search%")
                  ->orWhere('email', 'like', "%$search%")
                  ->orWhere('phone', 'like', "%$search%");
            })->orWhere('teacher_code', 'like', "%$search%");
        }

        if ($request->filled('status')) {
            $teachersQuery->where('status', $request->input('status'));
        }

        if ($request->filled('specialization')) {
            $teachersQuery->where('specialization', 'like', "%{$request->input('specialization')}%");
        }

        $teachers = $teachersQuery->paginate(10);
        $subjects = Subject::where('is_active', true)->get();

        return view('admin.pages.teachers.index', compact('teachers', 'subjects'));
    }

    public function create()
    {
        $subjects = Subject::where('is_active', true)->get();
        return view('admin.pages.teachers.create', compact('subjects'));
    }

    public function store(Request $request)
    {
        $request->validate([
            'name' => 'required|string|max:255',
            'email' => 'required|string|email|max:255|unique:users,email',
            'phone' => 'nullable|string|max:20|unique:users,phone',
            'password' => 'required|string|min:8|confirmed',
            'teacher_code' => 'required|string|max:255|unique:teachers,teacher_code',
            'date_of_birth' => 'nullable|date',
            'gender' => 'nullable|in:male,female',
            'address' => 'nullable|string',
            'hire_date' => 'nullable|date',
            'qualification' => 'nullable|string|max:255',
            'specialization' => 'nullable|string|max:255',
            'experience_years' => 'nullable|string|max:255',
            'salary' => 'nullable|numeric|min:0',
            'status' => 'required|in:active,inactive,on_leave,resigned',
            'notes' => 'nullable|string',
            'photo' => 'nullable|image|mimes:jpeg,png,jpg,gif|max:2048',
            'subjects' => 'nullable|array',
            'subjects.*' => 'exists:subjects,id',
        ]);

        // إنشاء المستخدم
        $user = User::create([
            'name' => $request->name,
            'email' => $request->email,
            'phone' => $request->phone,
            'password' => Hash::make($request->password),
            'status' => 'active',
            'is_active' => true,
        ]);

        // تعيين دور المعلم
        $teacherRole = Role::firstOrCreate(['name' => 'teacher']);
        $user->assignRole($teacherRole);

        // معالجة الصورة
        $photoPath = null;
        if ($request->hasFile('photo')) {
            $photo = $request->file('photo');
            $photoName = time() . '_' . $photo->getClientOriginalName();
            $photoPath = $photo->storeAs('teachers/photos', $photoName, 'public');
        }

        // إنشاء المعلم
        $teacher = Teacher::create([
            'user_id' => $user->id,
            'teacher_code' => $request->teacher_code,
            'date_of_birth' => $request->date_of_birth,
            'gender' => $request->gender,
            'address' => $request->address,
            'hire_date' => $request->hire_date,
            'qualification' => $request->qualification,
            'specialization' => $request->specialization,
            'experience_years' => $request->experience_years,
            'salary' => $request->salary,
            'status' => $request->status,
            'notes' => $request->notes,
            'photo' => $photoPath,
        ]);

        // ربط المعلم بالمواد
        if ($request->has('subjects')) {
            $teacher->subjects()->attach($request->subjects);
        }

        return redirect()->route('admin.teachers.index')->with('success', 'تم إنشاء المعلم بنجاح');
    }

    public function show(string $id)
    {
        $teacher = Teacher::with('user', 'subjects', 'sections.class.grade')->findOrFail($id);
        return view('admin.pages.teachers.show', compact('teacher'));
    }

    public function edit(string $id)
    {
        $teacher = Teacher::with('user', 'subjects')->findOrFail($id);
        $subjects = Subject::where('is_active', true)->get();
        return view('admin.pages.teachers.edit', compact('teacher', 'subjects'));
    }

    public function update(Request $request, string $id)
    {
        $teacher = Teacher::with('user')->findOrFail($id);

        $request->validate([
            'name' => 'required|string|max:255',
            'email' => 'required|string|email|max:255|unique:users,email,' . $teacher->user_id,
            'phone' => 'nullable|string|max:20|unique:users,phone,' . $teacher->user_id,
            'teacher_code' => 'required|string|max:255|unique:teachers,teacher_code,' . $id,
            'date_of_birth' => 'nullable|date',
            'gender' => 'nullable|in:male,female',
            'address' => 'nullable|string',
            'hire_date' => 'nullable|date',
            'qualification' => 'nullable|string|max:255',
            'specialization' => 'nullable|string|max:255',
            'experience_years' => 'nullable|string|max:255',
            'salary' => 'nullable|numeric|min:0',
            'status' => 'required|in:active,inactive,on_leave,resigned',
            'notes' => 'nullable|string',
            'photo' => 'nullable|image|mimes:jpeg,png,jpg,gif|max:2048',
            'subjects' => 'nullable|array',
            'subjects.*' => 'exists:subjects,id',
        ]);

        // تحديث بيانات المستخدم
        $teacher->user->update([
            'name' => $request->name,
            'email' => $request->email,
            'phone' => $request->phone,
        ]);

        // معالجة الصورة
        $photoPath = $teacher->photo;
        if ($request->hasFile('photo')) {
            $photo = $request->file('photo');
            $photoName = time() . '_' . $photo->getClientOriginalName();
            $photoPath = $photo->storeAs('teachers/photos', $photoName, 'public');
        }

        // تحديث بيانات المعلم
        $teacher->update([
            'teacher_code' => $request->teacher_code,
            'date_of_birth' => $request->date_of_birth,
            'gender' => $request->gender,
            'address' => $request->address,
            'hire_date' => $request->hire_date,
            'qualification' => $request->qualification,
            'specialization' => $request->specialization,
            'experience_years' => $request->experience_years,
            'salary' => $request->salary,
            'status' => $request->status,
            'notes' => $request->notes,
            'photo' => $photoPath,
        ]);

        // تحديث ربط المعلم بالمواد
        if ($request->has('subjects')) {
            $teacher->subjects()->sync($request->subjects);
        } else {
            $teacher->subjects()->detach();
        }

        return redirect()->route('admin.teachers.index')->with('success', 'تم تحديث المعلم بنجاح');
    }

    public function destroy(string $id)
    {
        $teacher = Teacher::with('user')->findOrFail($id);
        $userId = $teacher->user_id;
        $teacher->delete();
        
        // حذف المستخدم أيضاً
        User::find($userId)->delete();

        return redirect()->route('admin.teachers.index')->with('success', 'تم حذف المعلم بنجاح');
    }
}
