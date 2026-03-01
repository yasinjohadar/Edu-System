<?php

namespace App\Http\Controllers\Student;

use App\Http\Controllers\Controller;
use App\Models\Assignment;
use App\Models\AssignmentSubmission;
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
        $this->middleware('role:student');
    }

    /**
     * عرض قائمة واجبات الطالب
     */
    public function index(Request $request)
    {
        $user = Auth::user();
        $student = $user->student;

        if (!$student) {
            return redirect()->route('login')->with('error', 'لا يوجد حساب طالب مرتبط بهذا المستخدم');
        }

        if (!$student->section_id) {
            return redirect()->route('student.dashboard')->with('error', 'لم يتم تعيين فصل دراسي لك');
        }

        // جلب الواجبات للفصل الدراسي
        $query = Assignment::where('section_id', $student->section_id)
            ->where('status', 'published')
            ->where('is_active', true)
            ->with(['subject', 'teacher.user']);

        // فلترة حسب المادة
        if ($request->filled('subject_id')) {
            $query->where('subject_id', $request->subject_id);
        }

        // فلترة حسب الحالة (جديد، مُسلم، مُصحح)
        if ($request->filled('status')) {
            if ($request->status === 'new') {
                $query->whereDoesntHave('submissions', function($q) use ($student) {
                    $q->where('student_id', $student->id);
                });
            } elseif ($request->status === 'submitted') {
                $query->whereHas('submissions', function($q) use ($student) {
                    $q->where('student_id', $student->id)
                      ->whereIn('status', ['submitted', 'late']);
                });
            } elseif ($request->status === 'graded') {
                $query->whereHas('submissions', function($q) use ($student) {
                    $q->where('student_id', $student->id)
                      ->where('status', 'graded');
                });
            }
        }

        $assignments = $query->orderBy('due_date', 'asc')->paginate(20);

        // إحصائيات
        $totalAssignments = Assignment::where('section_id', $student->section_id)
            ->where('status', 'published')
            ->where('is_active', true)
            ->count();
        
        $submittedAssignments = Assignment::where('section_id', $student->section_id)
            ->where('status', 'published')
            ->where('is_active', true)
            ->whereHas('submissions', function($q) use ($student) {
                $q->where('student_id', $student->id);
            })
            ->count();

        $stats = [
            'total_assignments' => $totalAssignments,
            'submitted_assignments' => $submittedAssignments,
            'pending_assignments' => $totalAssignments - $submittedAssignments,
        ];

        // المواد الدراسية للفلترة
        $subjects = \App\Models\Subject::whereHas('assignments', function($q) use ($student) {
            $q->where('section_id', $student->section_id)
              ->where('status', 'published');
        })->get();

        return view('student.pages.assignments.index', compact('assignments', 'stats', 'subjects'));
    }

    /**
     * عرض تفاصيل واجب
     */
    public function show(string $id)
    {
        $user = Auth::user();
        $student = $user->student;

        if (!$student) {
            return redirect()->route('login')->with('error', 'لا يوجد حساب طالب مرتبط بهذا المستخدم');
        }

        $assignment = Assignment::with(['subject', 'teacher.user', 'attachments'])
            ->where('section_id', $student->section_id)
            ->findOrFail($id);

        // التحقق من أن الواجب منشور
        if ($assignment->status !== 'published' || !$assignment->is_active) {
            return redirect()->route('student.assignments.index')
                ->with('error', 'الواجب غير متاح.');
        }

        // جلب تسليمات الطالب لهذا الواجب
        $submissions = $assignment->submissions()
            ->where('student_id', $student->id)
            ->orderBy('attempt_number', 'desc')
            ->get();

        // التحقق من إمكانية التسليم
        $canSubmit = $assignment->canSubmit($student);
        $remainingAttempts = $assignment->getRemainingAttempts($student);

        return view('student.pages.assignments.show', compact('assignment', 'submissions', 'canSubmit', 'remainingAttempts'));
    }

    /**
     * عرض نموذج تسليم واجب
     */
    public function submit(string $id)
    {
        $user = Auth::user();
        $student = $user->student;

        if (!$student) {
            return redirect()->route('login')->with('error', 'لا يوجد حساب طالب مرتبط بهذا المستخدم');
        }

        $assignment = Assignment::with(['subject', 'teacher.user', 'attachments'])
            ->where('section_id', $student->section_id)
            ->findOrFail($id);

        // التحقق من إمكانية التسليم
        if (!$assignment->canSubmit($student)) {
            return redirect()->route('student.assignments.show', $id)
                ->with('error', 'لا يمكنك تسليم هذا الواجب.');
        }

        // تحويل submission_types من JSON إلى array إذا كان string
        if (is_string($assignment->submission_types)) {
            $assignment->submission_types = json_decode($assignment->submission_types, true);
        }

        // جلب آخر تسليم (إن وجد) لإعادة التسليم
        $previousSubmission = $assignment->submissions()
            ->where('student_id', $student->id)
            ->latest('attempt_number')
            ->first();

        // حساب رقم المحاولة التالي
        $nextAttemptNumber = $previousSubmission ? $previousSubmission->attempt_number + 1 : 1;
        $remainingAttempts = $assignment->getRemainingAttempts($student);

        return view('student.pages.assignments.submit', compact('assignment', 'previousSubmission', 'nextAttemptNumber', 'remainingAttempts'));
    }

    /**
     * حفظ تسليم واجب
     */
    public function storeSubmission(Request $request, string $id)
    {
        $user = Auth::user();
        $student = $user->student;

        if (!$student) {
            return redirect()->route('login')->with('error', 'لا يوجد حساب طالب مرتبط بهذا المستخدم');
        }

        $assignment = Assignment::where('section_id', $student->section_id)->findOrFail($id);

        // التحقق من إمكانية التسليم
        if (!$assignment->canSubmit($student)) {
            return redirect()->route('student.assignments.show', $id)
                ->with('error', 'لا يمكنك تسليم هذا الواجب.');
        }

        // جلب آخر تسليم
        $previousSubmission = $assignment->submissions()
            ->where('student_id', $student->id)
            ->latest('attempt_number')
            ->first();

        $nextAttemptNumber = $previousSubmission ? $previousSubmission->attempt_number + 1 : 1;

        // Validation
        $rules = [
            'student_notes' => 'nullable|string',
        ];

        // التحقق من أنواع التسليم المسموحة
        $submissionTypes = $assignment->submission_types ?? ['file', 'text', 'link'];
        
        if (in_array('file', $submissionTypes)) {
            $rules['files'] = 'nullable|array';
            $rules['files.*'] = 'file|max:51200'; // 50MB لكل ملف
        }

        if (in_array('text', $submissionTypes)) {
            $rules['texts'] = 'nullable|array';
            $rules['texts.*'] = 'nullable|string';
        }

        if (in_array('link', $submissionTypes)) {
            $rules['links'] = 'nullable|array';
            $rules['links.*.url'] = 'nullable|url';
            $rules['links.*.title'] = 'nullable|string|max:255';
            $rules['links.*.description'] = 'nullable|string';
            $rules['links.*.link_type'] = 'nullable|in:google_drive,dropbox,youtube,onedrive,other';
        }

        $validated = $request->validate($rules);

        // التحقق من وجود محتوى
        $hasContent = false;
        if (in_array('file', $submissionTypes) && $request->hasFile('files')) {
            $hasContent = true;
        }
        if (in_array('text', $submissionTypes) && $request->filled('texts')) {
            foreach ($request->texts as $text) {
                if (!empty(trim($text))) {
                    $hasContent = true;
                    break;
                }
            }
        }
        if (in_array('link', $submissionTypes) && $request->filled('links')) {
            foreach ($request->links as $link) {
                if (!empty($link['url'])) {
                    $hasContent = true;
                    break;
                }
            }
        }

        if (!$hasContent) {
            return back()->with('error', 'يجب إضافة محتوى على الأقل (ملف، نص، أو رابط).')->withInput();
        }

        // التحقق من عدد المحاولات
        if ($assignment->max_attempts !== null && $nextAttemptNumber > $assignment->max_attempts) {
            return redirect()->route('student.assignments.show', $id)
                ->with('error', 'تم الوصول للحد الأقصى من المحاولات.');
        }

        DB::beginTransaction();
        try {
            // حساب التأخير
            $dueDateTime = Carbon::parse($assignment->due_date->format('Y-m-d') . ' ' . $assignment->due_time);
            $isLate = Carbon::now()->gt($dueDateTime);
            $daysLate = $isLate ? Carbon::now()->diffInDays($dueDateTime) : 0;
            $latePenalty = 0;

            if ($isLate && $assignment->allow_late_submission) {
                if ($assignment->max_late_days && $daysLate > $assignment->max_late_days) {
                    DB::rollBack();
                    return back()->with('error', 'تم تجاوز الحد الأقصى لأيام التأخير المسموحة.')->withInput();
                }
                $latePenalty = $assignment->late_penalty_per_day * $daysLate;
            } elseif ($isLate && !$assignment->allow_late_submission) {
                DB::rollBack();
                return back()->with('error', 'انتهى موعد التسليم ولا يسمح بالتسليم المتأخر.')->withInput();
            }

            // إنشاء التسليم
            $submission = AssignmentSubmission::create([
                'assignment_id' => $assignment->id,
                'student_id' => $student->id,
                'submission_number' => AssignmentSubmission::generateSubmissionNumber(),
                'attempt_number' => $nextAttemptNumber,
                'is_resubmission' => $previousSubmission ? true : false,
                'previous_submission_id' => $previousSubmission ? $previousSubmission->id : null,
                'submitted_at' => Carbon::now(),
                'status' => $isLate ? 'late' : 'submitted',
                'student_notes' => $validated['student_notes'] ?? null,
                'is_late' => $isLate,
                'days_late' => $daysLate,
                'late_penalty' => $latePenalty,
            ]);

            // حفظ الملفات
            if (in_array('file', $submissionTypes) && $request->hasFile('files')) {
                foreach ($request->file('files') as $index => $file) {
                    $filePath = $file->store('assignments/submissions', 'public');
                    $submission->files()->create([
                        'file_path' => $filePath,
                        'file_name' => $file->getClientOriginalName(),
                        'file_size' => $file->getSize(),
                        'file_type' => $file->getMimeType(),
                        'sort_order' => $index,
                    ]);
                }
            }

            // حفظ النصوص
            if (in_array('text', $submissionTypes) && $request->filled('texts')) {
                foreach ($request->texts as $index => $text) {
                    if (!empty(trim($text))) {
                        $submission->texts()->create([
                            'content' => $text,
                            'sort_order' => $index,
                        ]);
                    }
                }
            }

            // حفظ الروابط
            if (in_array('link', $submissionTypes) && $request->filled('links')) {
                foreach ($request->links as $index => $link) {
                    if (!empty($link['url'])) {
                        $submission->links()->create([
                            'url' => $link['url'],
                            'title' => $link['title'] ?? null,
                            'description' => $link['description'] ?? null,
                            'link_type' => $link['link_type'] ?? 'other',
                            'sort_order' => $index,
                        ]);
                    }
                }
            }

            DB::commit();

            // TODO: إرسال إشعار للمعلم

            return redirect()->route('student.assignments.show', $id)
                ->with('success', 'تم تسليم الواجب بنجاح.');
        } catch (\Exception $e) {
            DB::rollBack();
            return back()->with('error', 'حدث خطأ أثناء تسليم الواجب: ' . $e->getMessage())->withInput();
        }
    }

    /**
     * عرض تسليم سابق
     */
    public function showSubmission(string $id)
    {
        $user = Auth::user();
        $student = $user->student;

        if (!$student) {
            return redirect()->route('login')->with('error', 'لا يوجد حساب طالب مرتبط بهذا المستخدم');
        }

        $submission = AssignmentSubmission::with([
            'assignment.subject',
            'assignment.teacher.user',
            'files',
            'texts',
            'links',
            'grader',
            'previousSubmission',
            'resubmissions'
        ])->findOrFail($id);

        // التحقق من ملكية التسليم
        if ($submission->student_id != $student->id) {
            abort(403, 'غير مصرح لك بالوصول إلى هذا التسليم.');
        }

        return view('student.pages.assignments.submissions.show', compact('submission'));
    }

    /**
     * إعادة تسليم
     */
    public function resubmit(Request $request, string $id)
    {
        $user = Auth::user();
        $student = $user->student;

        if (!$student) {
            return redirect()->route('login')->with('error', 'لا يوجد حساب طالب مرتبط بهذا المستخدم');
        }

        $submission = AssignmentSubmission::with('assignment')->findOrFail($id);

        // التحقق من ملكية التسليم
        if ($submission->student_id != $student->id) {
            abort(403, 'غير مصرح لك بالوصول إلى هذا التسليم.');
        }

        // التحقق من إمكانية إعادة التسليم
        if (!$submission->canResubmit()) {
            return redirect()->route('student.assignments.show', $submission->assignment_id)
                ->with('error', 'لا يمكن إعادة تسليم هذا الواجب.');
        }

        // إعادة توجيه إلى صفحة التسليم
        return redirect()->route('student.assignments.submit', $submission->assignment_id)
            ->with('info', 'يمكنك إعادة تسليم الواجب الآن.');
    }
}
