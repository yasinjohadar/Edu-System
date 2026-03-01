<?php

namespace App\Http\Controllers\Admin;

use App\Http\Controllers\Controller;
use App\Models\Assignment;
use App\Models\AssignmentSubmission;
use Illuminate\Http\Request;
use Illuminate\Support\Facades\Auth;
use Illuminate\Support\Facades\Storage;
use Illuminate\Support\Facades\DB;
use Carbon\Carbon;
use ZipArchive;

class AssignmentSubmissionController extends Controller
{
    public function __construct()
    {
        $this->middleware('auth');
        $this->middleware('permission:assignment-submission-list|assignment-submission-view|assignment-submission-grade', ['only' => ['index', 'show']]);
        $this->middleware('permission:assignment-submission-grade', ['only' => ['grade', 'requestResubmission']]);
    }

    /**
     * عرض قائمة تسليمات واجب معين
     */
    public function index(Request $request, string $assignmentId)
    {
        $assignment = Assignment::findOrFail($assignmentId);

        $query = $assignment->submissions()
            ->with(['student.user', 'grader'])
            ->orderBy('submitted_at', 'desc');

        // فلترة حسب الحالة
        if ($request->filled('status')) {
            $query->where('status', $request->status);
        }

        // فلترة حسب الطالب
        if ($request->filled('student_id')) {
            $query->where('student_id', $request->student_id);
        }

        // فلترة حسب المحاولة
        if ($request->filled('attempt_number')) {
            $query->where('attempt_number', $request->attempt_number);
        }

        $submissions = $query->paginate(20);

        // إحصائيات
        $totalSubmissions = $assignment->submissions()->count();
        $gradedSubmissions = $assignment->submissions()->where('status', 'graded')->count();
        $pendingSubmissions = $assignment->submissions()->whereIn('status', ['submitted', 'late'])->count();
        $lateSubmissions = $assignment->submissions()->where('is_late', true)->count();

        $stats = [
            'total_submissions' => $totalSubmissions,
            'graded_submissions' => $gradedSubmissions,
            'pending_submissions' => $pendingSubmissions,
            'late_submissions' => $lateSubmissions,
        ];

        return view('admin.pages.assignments.submissions.index', compact('assignment', 'submissions', 'stats'));
    }

    /**
     * عرض تفاصيل تسليم
     */
    public function show(string $assignmentId, string $submissionId)
    {
        $assignment = Assignment::findOrFail($assignmentId);
        $submission = AssignmentSubmission::with([
            'student.user',
            'assignment',
            'files',
            'texts',
            'links',
            'grader',
            'previousSubmission',
            'resubmissions'
        ])->findOrFail($submissionId);

        // التحقق من أن التسليم يتبع للواجب
        if ($submission->assignment_id != $assignment->id) {
            abort(404);
        }

        return view('admin.pages.assignments.submissions.show', compact('assignment', 'submission'));
    }

    /**
     * تصحيح تسليم
     */
    public function grade(Request $request, string $assignmentId, string $submissionId)
    {
        $assignment = Assignment::findOrFail($assignmentId);
        $submission = AssignmentSubmission::findOrFail($submissionId);

        // التحقق من أن التسليم يتبع للواجب
        if ($submission->assignment_id != $assignment->id) {
            abort(404);
        }

        $validated = $request->validate([
            'marks_obtained' => 'required|numeric|min:0|max:' . $assignment->total_marks,
            'feedback' => 'nullable|string|max:500',
            'teacher_notes' => 'nullable|string',
            'requires_resubmission' => 'boolean',
            'resubmission_reason' => 'nullable|string|required_if:requires_resubmission,1',
        ]);

        DB::beginTransaction();
        try {
            $validated['status'] = $validated['requires_resubmission'] ? 'returned' : 'graded';
            $validated['graded_at'] = Carbon::now();
            $validated['graded_by'] = Auth::id();

            $submission->update($validated);

            DB::commit();

            return redirect()->route('admin.assignments.submissions.show', [$assignmentId, $submissionId])
                ->with('success', 'تم تصحيح الواجب بنجاح.');
        } catch (\Exception $e) {
            DB::rollBack();
            return back()->with('error', 'حدث خطأ أثناء تصحيح الواجب: ' . $e->getMessage())->withInput();
        }
    }

    /**
     * طلب إعادة تسليم
     */
    public function requestResubmission(Request $request, string $assignmentId, string $submissionId)
    {
        $assignment = Assignment::findOrFail($assignmentId);
        $submission = AssignmentSubmission::findOrFail($submissionId);

        // التحقق من أن التسليم يتبع للواجب
        if ($submission->assignment_id != $assignment->id) {
            abort(404);
        }

        // التحقق من أن الواجب يسمح بإعادة التسليم
        if (!$assignment->allow_resubmission) {
            return back()->with('error', 'هذا الواجب لا يسمح بإعادة التسليم.');
        }

        $validated = $request->validate([
            'resubmission_reason' => 'required|string',
            'resubmission_deadline' => 'nullable|date|after:today',
        ]);

        DB::beginTransaction();
        try {
            $submission->update([
                'requires_resubmission' => true,
                'resubmission_reason' => $validated['resubmission_reason'],
                'status' => 'returned',
            ]);

            // تحديث موعد إعادة التسليم في الواجب إذا تم تحديده
            if ($request->filled('resubmission_deadline')) {
                $assignment->update([
                    'resubmission_deadline' => $validated['resubmission_deadline']
                ]);
            }

            DB::commit();

            // TODO: إرسال إشعار للطالب

            return redirect()->route('admin.assignments.submissions.show', [$assignmentId, $submissionId])
                ->with('success', 'تم طلب إعادة التسليم بنجاح.');
        } catch (\Exception $e) {
            DB::rollBack();
            return back()->with('error', 'حدث خطأ أثناء طلب إعادة التسليم: ' . $e->getMessage())->withInput();
        }
    }

    /**
     * تحميل ملفات التسليم كـ ZIP
     */
    public function downloadFiles(string $assignmentId, string $submissionId)
    {
        $assignment = Assignment::findOrFail($assignmentId);
        $submission = AssignmentSubmission::with('files')->findOrFail($submissionId);

        // التحقق من أن التسليم يتبع للواجب
        if ($submission->assignment_id != $assignment->id) {
            abort(404);
        }

        if ($submission->files->isEmpty()) {
            return back()->with('error', 'لا توجد ملفات للتحميل.');
        }

        $zip = new ZipArchive();
        $zipFileName = 'submission_' . $submission->submission_number . '.zip';
        $zipPath = storage_path('app/temp/' . $zipFileName);

        // إنشاء مجلد temp إذا لم يكن موجوداً
        if (!file_exists(storage_path('app/temp'))) {
            mkdir(storage_path('app/temp'), 0755, true);
        }

        if ($zip->open($zipPath, ZipArchive::CREATE) === TRUE) {
            foreach ($submission->files as $file) {
                $filePath = storage_path('app/public/' . $file->file_path);
                if (file_exists($filePath)) {
                    $zip->addFile($filePath, $file->file_name);
                }
            }
            $zip->close();

            return response()->download($zipPath)->deleteFileAfterSend(true);
        }

        return back()->with('error', 'حدث خطأ أثناء إنشاء ملف ZIP.');
    }
}
