<?php

namespace App\Http\Controllers\Admin;

use App\Http\Controllers\Controller;
use App\Models\Student;
use App\Models\User;
use App\Models\ClassModel;
use App\Models\Section;
use App\Models\ParentModel;
use Spatie\Permission\Models\Role;
use Illuminate\Http\Request;
use Illuminate\Support\Facades\Hash;
use Illuminate\Support\Facades\DB;
use Illuminate\Support\Facades\Storage;

class StudentController extends Controller
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
        $studentsQuery = Student::with('user', 'class.grade', 'section', 'parents.user')
            ->orderBy('created_at', 'desc');

        // البحث
        if ($request->filled('query')) {
            $search = $request->input('query');
            $studentsQuery->whereHas('user', function ($q) use ($search) {
                $q->where('name', 'like', "%$search%")
                  ->orWhere('email', 'like', "%$search%")
                  ->orWhere('phone', 'like', "%$search%");
            })->orWhere('student_code', 'like', "%$search%");
        }

        // فلترة حسب الحالة
        if ($request->filled('status')) {
            $studentsQuery->where('status', $request->input('status'));
        }

        // فلترة حسب الصف
        if ($request->filled('class_id')) {
            $studentsQuery->where('class_id', $request->input('class_id'));
        }

        // فلترة حسب الفصل
        if ($request->filled('section_id')) {
            $studentsQuery->where('section_id', $request->input('section_id'));
        }

        $students = $studentsQuery->paginate(15);
        $classes = ClassModel::with('grade')->where('is_active', true)->get();
        $sections = Section::with('class.grade')->where('is_active', true)->get();

        return view('admin.pages.students.index', compact('students', 'classes', 'sections'));
    }

    /**
     * Show the form for creating a new resource.
     */
    public function create()
    {
        $classes = ClassModel::with('grade')->where('is_active', true)->get();
        $sections = Section::with('class.grade')->where('is_active', true)->get();
        $parents = ParentModel::with('user')->get();

        return view('admin.pages.students.create', compact('classes', 'sections', 'parents'));
    }

    /**
     * Store a newly created resource in storage.
     */
    public function store(Request $request)
    {
        $request->validate([
            'name' => 'required|string|max:255',
            'email' => 'required|string|email|max:255|unique:users,email',
            'phone' => 'nullable|string|max:20|unique:users,phone',
            'password' => 'required|string|min:8|confirmed',
            'student_code' => 'required|string|max:255|unique:students,student_code',
            'date_of_birth' => 'nullable|date',
            'gender' => 'nullable|in:male,female',
            'address' => 'nullable|string',
            'enrollment_date' => 'nullable|date',
            'status' => 'required|in:active,graduated,transferred,suspended',
            'class_id' => 'nullable|exists:classes,id',
            'section_id' => 'nullable|exists:sections,id',
            'parent_guardian' => 'nullable|string|max:255',
            'emergency_contact' => 'nullable|string|max:255',
            'medical_notes' => 'nullable|string',
            'photo' => 'nullable|image|mimes:jpeg,png,jpg,gif|max:2048',
            'birth_certificate' => 'nullable|file|mimes:pdf,jpeg,png,jpg|max:5120',
            'health_certificate' => 'nullable|file|mimes:pdf,jpeg,png,jpg|max:5120',
            'parent_ids' => 'nullable|array',
            'parent_ids.*' => 'exists:parents,id',
        ]);

        DB::beginTransaction();
        try {
            // إنشاء المستخدم
            $user = User::create([
                'name' => $request->name,
                'email' => $request->email,
                'phone' => $request->phone,
                'password' => Hash::make($request->password),
                'status' => 'active',
                'is_active' => true,
                'email_verified_at' => now(),
            ]);

            // تعيين دور الطالب
            $studentRole = Role::firstOrCreate(['name' => 'student']);
            $user->assignRole($studentRole);

            // معالجة الملفات
            $photoPath = null;
            $birthCertificatePath = null;
            $healthCertificatePath = null;

            if ($request->hasFile('photo')) {
                $photo = $request->file('photo');
                $photoName = time() . '_' . $photo->getClientOriginalName();
                $photoPath = $photo->storeAs('students/photos', $photoName, 'public');
            }

            if ($request->hasFile('birth_certificate')) {
                $file = $request->file('birth_certificate');
                $fileName = time() . '_birth_' . $file->getClientOriginalName();
                $birthCertificatePath = $file->storeAs('students/certificates', $fileName, 'public');
            }

            if ($request->hasFile('health_certificate')) {
                $file = $request->file('health_certificate');
                $fileName = time() . '_health_' . $file->getClientOriginalName();
                $healthCertificatePath = $file->storeAs('students/certificates', $fileName, 'public');
            }

            // إنشاء الطالب
            $student = Student::create([
                'user_id' => $user->id,
                'student_code' => $request->student_code,
                'date_of_birth' => $request->date_of_birth,
                'gender' => $request->gender,
                'address' => $request->address,
                'enrollment_date' => $request->enrollment_date ?? now(),
                'status' => $request->status,
                'class_id' => $request->class_id,
                'section_id' => $request->section_id,
                'parent_guardian' => $request->parent_guardian,
                'emergency_contact' => $request->emergency_contact,
                'medical_notes' => $request->medical_notes,
                'photo' => $photoPath,
                'birth_certificate' => $birthCertificatePath,
                'health_certificate' => $healthCertificatePath,
            ]);

            // ربط الطالب بأولياء الأمور
            if ($request->has('parent_ids')) {
                foreach ($request->parent_ids as $index => $parentId) {
                    $parent = ParentModel::find($parentId);
                    if ($parent) {
                        $student->parents()->attach($parentId, [
                            'relationship_type' => $parent->relationship,
                            'is_primary' => $index === 0, // أول ولي أمر هو الأساسي
                        ]);
                    }
                }
            }

            DB::commit();
            return redirect()->route('admin.students.index')->with('success', 'تم إنشاء الطالب بنجاح');
        } catch (\Exception $e) {
            DB::rollBack();
            return back()->with('error', 'حدث خطأ أثناء إنشاء الطالب: ' . $e->getMessage())->withInput();
        }
    }

    /**
     * Display the specified resource.
     */
    public function show(string $id)
    {
        $student = Student::with('user', 'class.grade', 'section', 'parents.user', 'attendances')
            ->findOrFail($id);
        
        return view('admin.pages.students.show', compact('student'));
    }

    /**
     * Show the form for editing the specified resource.
     */
    public function edit(string $id)
    {
        $student = Student::with('user', 'parents')->findOrFail($id);
        $classes = ClassModel::with('grade')->where('is_active', true)->get();
        $sections = Section::with('class.grade')->where('is_active', true)->get();
        $parents = ParentModel::with('user')->get();

        return view('admin.pages.students.edit', compact('student', 'classes', 'sections', 'parents'));
    }

    /**
     * Update the specified resource in storage.
     */
    public function update(Request $request, string $id)
    {
        $student = Student::with('user')->findOrFail($id);

        $request->validate([
            'name' => 'required|string|max:255',
            'email' => 'required|string|email|max:255|unique:users,email,' . $student->user_id,
            'phone' => 'nullable|string|max:20|unique:users,phone,' . $student->user_id,
            'student_code' => 'required|string|max:255|unique:students,student_code,' . $id,
            'date_of_birth' => 'nullable|date',
            'gender' => 'nullable|in:male,female',
            'address' => 'nullable|string',
            'enrollment_date' => 'nullable|date',
            'status' => 'required|in:active,graduated,transferred,suspended',
            'class_id' => 'nullable|exists:classes,id',
            'section_id' => 'nullable|exists:sections,id',
            'parent_guardian' => 'nullable|string|max:255',
            'emergency_contact' => 'nullable|string|max:255',
            'medical_notes' => 'nullable|string',
            'photo' => 'nullable|image|mimes:jpeg,png,jpg,gif|max:2048',
            'birth_certificate' => 'nullable|file|mimes:pdf,jpeg,png,jpg|max:5120',
            'health_certificate' => 'nullable|file|mimes:pdf,jpeg,png,jpg|max:5120',
            'parent_ids' => 'nullable|array',
            'parent_ids.*' => 'exists:parents,id',
        ]);

        DB::beginTransaction();
        try {
            // تحديث بيانات المستخدم
            $student->user->update([
                'name' => $request->name,
                'email' => $request->email,
                'phone' => $request->phone,
            ]);

            // معالجة الملفات
            $photoPath = $student->photo;
            $birthCertificatePath = $student->birth_certificate;
            $healthCertificatePath = $student->health_certificate;

            if ($request->hasFile('photo')) {
                // حذف الصورة القديمة
                if ($photoPath && Storage::disk('public')->exists($photoPath)) {
                    Storage::disk('public')->delete($photoPath);
                }
                $photo = $request->file('photo');
                $photoName = time() . '_' . $photo->getClientOriginalName();
                $photoPath = $photo->storeAs('students/photos', $photoName, 'public');
            }

            if ($request->hasFile('birth_certificate')) {
                if ($birthCertificatePath && Storage::disk('public')->exists($birthCertificatePath)) {
                    Storage::disk('public')->delete($birthCertificatePath);
                }
                $file = $request->file('birth_certificate');
                $fileName = time() . '_birth_' . $file->getClientOriginalName();
                $birthCertificatePath = $file->storeAs('students/certificates', $fileName, 'public');
            }

            if ($request->hasFile('health_certificate')) {
                if ($healthCertificatePath && Storage::disk('public')->exists($healthCertificatePath)) {
                    Storage::disk('public')->delete($healthCertificatePath);
                }
                $file = $request->file('health_certificate');
                $fileName = time() . '_health_' . $file->getClientOriginalName();
                $healthCertificatePath = $file->storeAs('students/certificates', $fileName, 'public');
            }

            // تحديث بيانات الطالب
            $student->update([
                'student_code' => $request->student_code,
                'date_of_birth' => $request->date_of_birth,
                'gender' => $request->gender,
                'address' => $request->address,
                'enrollment_date' => $request->enrollment_date,
                'status' => $request->status,
                'class_id' => $request->class_id,
                'section_id' => $request->section_id,
                'parent_guardian' => $request->parent_guardian,
                'emergency_contact' => $request->emergency_contact,
                'medical_notes' => $request->medical_notes,
                'photo' => $photoPath,
                'birth_certificate' => $birthCertificatePath,
                'health_certificate' => $healthCertificatePath,
            ]);

            // تحديث ربط الطالب بأولياء الأمور
            if ($request->has('parent_ids')) {
                $student->parents()->detach();
                foreach ($request->parent_ids as $index => $parentId) {
                    $parent = ParentModel::find($parentId);
                    if ($parent) {
                        $student->parents()->attach($parentId, [
                            'relationship_type' => $parent->relationship,
                            'is_primary' => $index === 0,
                        ]);
                    }
                }
            } else {
                $student->parents()->detach();
            }

            DB::commit();
            return redirect()->route('admin.students.index')->with('success', 'تم تحديث الطالب بنجاح');
        } catch (\Exception $e) {
            DB::rollBack();
            return back()->with('error', 'حدث خطأ أثناء تحديث الطالب: ' . $e->getMessage())->withInput();
        }
    }

    /**
     * Remove the specified resource from storage.
     */
    public function destroy(string $id)
    {
        $student = Student::with('user')->findOrFail($id);
        
        DB::beginTransaction();
        try {
            // حذف الملفات
            if ($student->photo && Storage::disk('public')->exists($student->photo)) {
                Storage::disk('public')->delete($student->photo);
            }
            if ($student->birth_certificate && Storage::disk('public')->exists($student->birth_certificate)) {
                Storage::disk('public')->delete($student->birth_certificate);
            }
            if ($student->health_certificate && Storage::disk('public')->exists($student->health_certificate)) {
                Storage::disk('public')->delete($student->health_certificate);
            }

            $userId = $student->user_id;
            $student->delete();
            
            // حذف المستخدم أيضاً
            User::find($userId)->delete();

            DB::commit();
            return redirect()->route('admin.students.index')->with('success', 'تم حذف الطالب بنجاح');
        } catch (\Exception $e) {
            DB::rollBack();
            return back()->with('error', 'حدث خطأ أثناء حذف الطالب: ' . $e->getMessage());
        }
    }
}
