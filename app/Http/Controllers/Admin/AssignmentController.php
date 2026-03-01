<?php

namespace App\Http\Controllers\Admin;

use App\Http\Controllers\Controller;
use App\Models\Assignment;
use App\Models\Subject;
use App\Models\Teacher;
use App\Models\Section;
use Illuminate\Http\Request;
use Illuminate\Support\Facades\Auth;
use Illuminate\Support\Facades\Storage;
use Illuminate\Support\Facades\DB;
use Carbon\Carbon;

class AssignmentController extends Controller
{
    public function __construct()
    {
        $this->middleware('auth');
        $this->middleware('permission:assignment-list|assignment-create|assignment-edit|assignment-delete', ['only' => ['index', 'show']]);
        $this->middleware('permission:assignment-create', ['only' => ['create', 'store']]);
        $this->middleware('permission:assignment-edit', ['only' => ['edit', 'update', 'publish', 'close']]);
        $this->middleware('permission:assignment-delete', ['only' => ['destroy']]);
    }

    /**
     * عرض قائمة الواجبات
     */
    public function index(Request $request)
    {
        $query = Assignment::with(['subject', 'teacher.user', 'section'])->orderBy('created_at', 'desc');

        // فلترة حسب المادة
        if ($request->filled('subject_id')) {
            $query->where('subject_id', $request->subject_id);
        }

        // فلترة حسب المعلم
        if ($request->filled('teacher_id')) {
            $query->where('teacher_id', $request->teacher_id);
        }

        // فلترة حسب الفصل
        if ($request->filled('section_id')) {
            $query->where('section_id', $request->section_id);
        }

        // فلترة حسب الحالة
        if ($request->filled('status')) {
            $query->where('status', $request->status);
        }

        // فلترة حسب النشاط
        if ($request->filled('is_active')) {
            $query->where('is_active', $request->input('is_active'));
        }

        // البحث
        if ($request->filled('query')) {
            $search = $request->input('query');
            $query->where(function ($q) use ($search) {
                $q->where('title', 'like', "%$search%")
                  ->orWhere('description', 'like', "%$search%")
                  ->orWhere('assignment_number', 'like', "%$search%");
            });
        }

        $assignments = $query->paginate(20);
        $subjects = Subject::where('is_active', true)->get();
        $teachers = Teacher::with('user')->get();
        $sections = Section::where('is_active', true)->with('class')->get();

        return view('admin.pages.assignments.index', compact('assignments', 'subjects', 'teachers', 'sections'));
    }

    /**
     * عرض نموذج إنشاء واجب جديد
     */
    public function create()
    {
        $subjects = Subject::where('is_active', true)->get();
        $teachers = Teacher::with('user')->get();
        $sections = Section::where('is_active', true)->with('class')->get();

        return view('admin.pages.assignments.create', compact('subjects', 'teachers', 'sections'));
    }

    /**
     * حفظ واجب جديد
     */
    public function store(Request $request)
    {
        $validated = $request->validate([
            'subject_id' => 'required|exists:subjects,id',
            'teacher_id' => 'required|exists:teachers,id',
            'section_id' => 'nullable|exists:sections,id',
            'title' => 'required|string|max:255',
            'description' => 'nullable|string',
            'instructions' => 'nullable|string',
            'total_marks' => 'required|numeric|min:1',
            'due_date' => 'required|date|after:today',
            'due_time' => 'required|date_format:H:i',
            'allow_late_submission' => 'boolean',
            'late_penalty_per_day' => 'nullable|numeric|min:0',
            'max_late_days' => 'nullable|integer|min:1',
            'max_attempts' => 'nullable|integer|min:1|max:10',
            'allow_resubmission' => 'boolean',
            'resubmission_deadline' => 'nullable|date|after:due_date',
            'submission_types' => 'required|array|min:1',
            'submission_types.*' => 'in:file,text,link',
            'status' => 'required|in:draft,published,closed',
            'is_active' => 'boolean',
            'attachments' => 'nullable|array',
            'attachments.*' => 'file|max:10240', // 10MB لكل ملف
        ]);

        DB::beginTransaction();
        try {
            $validated['assignment_number'] = Assignment::generateAssignmentNumber();
            $validated['created_by'] = Auth::id();
            $validated['submission_types'] = json_encode($validated['submission_types']);

            $assignment = Assignment::create($validated);

            // رفع المرفقات
            if ($request->hasFile('attachments')) {
                foreach ($request->file('attachments') as $index => $file) {
                    $filePath = $file->store('assignments/attachments', 'public');
                    $assignment->attachments()->create([
                        'file_path' => $filePath,
                        'file_name' => $file->getClientOriginalName(),
                        'file_size' => $file->getSize(),
                        'file_type' => $file->getMimeType(),
                        'sort_order' => $index,
                    ]);
                }
            }

            DB::commit();

            return redirect()->route('admin.assignments.index')->with('success', 'تم إنشاء الواجب بنجاح.');
        } catch (\Exception $e) {
            DB::rollBack();
            return back()->with('error', 'حدث خطأ أثناء إنشاء الواجب: ' . $e->getMessage())->withInput();
        }
    }

    /**
     * عرض تفاصيل الواجب
     */
    public function show(string $id)
    {
        $assignment = Assignment::with(['subject', 'teacher.user', 'section', 'attachments', 'submissions.student.user'])
            ->findOrFail($id);

        // تحويل submission_types من JSON إلى array إذا كان string
        if (is_string($assignment->submission_types)) {
            $assignment->submission_types = json_decode($assignment->submission_types, true);
        }

        // إحصائيات
        $totalSubmissions = $assignment->submissions()->count();
        $gradedSubmissions = $assignment->submissions()->where('status', 'graded')->count();
        $pendingSubmissions = $assignment->submissions()->whereIn('status', ['submitted', 'late'])->count();
        $averageMarks = $assignment->submissions()->whereNotNull('marks_obtained')->avg('marks_obtained') ?? 0;

        $stats = [
            'total_submissions' => $totalSubmissions,
            'graded_submissions' => $gradedSubmissions,
            'pending_submissions' => $pendingSubmissions,
            'average_marks' => round($averageMarks, 2),
        ];

        return view('admin.pages.assignments.show', compact('assignment', 'stats'));
    }

    /**
     * عرض نموذج تعديل واجب
     */
    public function edit(string $id)
    {
        $assignment = Assignment::findOrFail($id);
        
        // منع التعديل إذا كان منشوراً ولديه تسليمات
        if ($assignment->status === 'published' && $assignment->submissions()->count() > 0) {
            return redirect()->route('admin.assignments.show', $id)
                ->with('error', 'لا يمكن تعديل واجب منشور لديه تسليمات.');
        }

        // تحويل submission_types من JSON إلى array إذا كان string
        if (is_string($assignment->submission_types)) {
            $assignment->submission_types = json_decode($assignment->submission_types, true);
        }

        $subjects = Subject::where('is_active', true)->get();
        $teachers = Teacher::with('user')->get();
        $sections = Section::where('is_active', true)->with('class')->get();

        return view('admin.pages.assignments.edit', compact('assignment', 'subjects', 'teachers', 'sections'));
    }

    /**
     * تحديث واجب
     */
    public function update(Request $request, string $id)
    {
        $assignment = Assignment::findOrFail($id);

        // منع التعديل إذا كان منشوراً ولديه تسليمات
        if ($assignment->status === 'published' && $assignment->submissions()->count() > 0) {
            return redirect()->route('admin.assignments.show', $id)
                ->with('error', 'لا يمكن تعديل واجب منشور لديه تسليمات.');
        }

        $validated = $request->validate([
            'subject_id' => 'required|exists:subjects,id',
            'teacher_id' => 'required|exists:teachers,id',
            'section_id' => 'nullable|exists:sections,id',
            'title' => 'required|string|max:255',
            'description' => 'nullable|string',
            'instructions' => 'nullable|string',
            'total_marks' => 'required|numeric|min:1',
            'due_date' => 'required|date',
            'due_time' => 'required|date_format:H:i',
            'allow_late_submission' => 'boolean',
            'late_penalty_per_day' => 'nullable|numeric|min:0',
            'max_late_days' => 'nullable|integer|min:1',
            'max_attempts' => 'nullable|integer|min:1|max:10',
            'allow_resubmission' => 'boolean',
            'resubmission_deadline' => 'nullable|date|after:due_date',
            'submission_types' => 'required|array|min:1',
            'submission_types.*' => 'in:file,text,link',
            'status' => 'required|in:draft,published,closed',
            'is_active' => 'boolean',
            'attachments' => 'nullable|array',
            'attachments.*' => 'file|max:10240',
            'delete_attachments' => 'nullable|array',
        ]);

        DB::beginTransaction();
        try {
            $validated['submission_types'] = json_encode($validated['submission_types']);

            $assignment->update($validated);

            // حذف المرفقات المحددة
            if ($request->filled('delete_attachments')) {
                foreach ($request->delete_attachments as $attachmentId) {
                    $attachment = $assignment->attachments()->find($attachmentId);
                    if ($attachment) {
                        Storage::disk('public')->delete($attachment->file_path);
                        $attachment->delete();
                    }
                }
            }

            // إضافة مرفقات جديدة
            if ($request->hasFile('attachments')) {
                $maxSortOrder = $assignment->attachments()->max('sort_order') ?? -1;
                foreach ($request->file('attachments') as $index => $file) {
                    $filePath = $file->store('assignments/attachments', 'public');
                    $assignment->attachments()->create([
                        'file_path' => $filePath,
                        'file_name' => $file->getClientOriginalName(),
                        'file_size' => $file->getSize(),
                        'file_type' => $file->getMimeType(),
                        'sort_order' => $maxSortOrder + $index + 1,
                    ]);
                }
            }

            DB::commit();

            return redirect()->route('admin.assignments.index')->with('success', 'تم تحديث الواجب بنجاح.');
        } catch (\Exception $e) {
            DB::rollBack();
            return back()->with('error', 'حدث خطأ أثناء تحديث الواجب: ' . $e->getMessage())->withInput();
        }
    }

    /**
     * حذف واجب
     */
    public function destroy(string $id)
    {
        $assignment = Assignment::findOrFail($id);

        // منع الحذف إذا كان لديه تسليمات
        if ($assignment->submissions()->count() > 0) {
            return redirect()->route('admin.assignments.index')
                ->with('error', 'لا يمكن حذف واجب لديه تسليمات.');
        }

        DB::beginTransaction();
        try {
            // حذف المرفقات
            foreach ($assignment->attachments as $attachment) {
                Storage::disk('public')->delete($attachment->file_path);
            }

            $assignment->delete();

            DB::commit();

            return redirect()->route('admin.assignments.index')->with('success', 'تم حذف الواجب بنجاح.');
        } catch (\Exception $e) {
            DB::rollBack();
            return redirect()->route('admin.assignments.index')
                ->with('error', 'حدث خطأ أثناء حذف الواجب: ' . $e->getMessage());
        }
    }

    /**
     * نشر واجب
     */
    public function publish(string $id)
    {
        $assignment = Assignment::findOrFail($id);
        
        if ($assignment->status === 'published') {
            return back()->with('error', 'الواجب منشور بالفعل.');
        }

        $assignment->update(['status' => 'published']);

        return back()->with('success', 'تم نشر الواجب بنجاح.');
    }

    /**
     * إغلاق واجب
     */
    public function close(string $id)
    {
        $assignment = Assignment::findOrFail($id);
        
        if ($assignment->status === 'closed') {
            return back()->with('error', 'الواجب مغلق بالفعل.');
        }

        $assignment->update(['status' => 'closed']);

        return back()->with('success', 'تم إغلاق الواجب بنجاح.');
    }
}
