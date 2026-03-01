<?php

namespace App\Http\Controllers\Admin;

use App\Http\Controllers\Controller;
use App\Exports\StudentsExport;
use App\Exports\TeachersExport;
use App\Exports\ClassesExport;
use App\Exports\SectionsExport;
use App\Exports\SubjectsExport;
use App\Exports\AttendancesExport;
use App\Exports\GradeRecordsExport;
use App\Exports\InvoicesExport;
use App\Exports\PaymentsExport;
use App\Exports\BooksExport;
use App\Exports\BookBorrowingsExport;
use App\Exports\ExamsExport;
use App\Exports\AssignmentsExport;
use Illuminate\Http\Request;
use Maatwebsite\Excel\Facades\Excel;

class ExportController extends Controller
{
    public function __construct()
    {
        $this->middleware('auth');
    }

    /**
     * تصدير الطلاب
     */
    public function students(Request $request)
    {
        $filters = $request->only(['status', 'class_id', 'section_id']);
        return Excel::download(new StudentsExport($filters), 'students_' . date('Y-m-d_His') . '.xlsx');
    }

    /**
     * تصدير المعلمين
     */
    public function teachers(Request $request)
    {
        $filters = $request->only(['status']);
        return Excel::download(new TeachersExport($filters), 'teachers_' . date('Y-m-d_His') . '.xlsx');
    }

    /**
     * تصدير الصفوف
     */
    public function classes(Request $request)
    {
        $filters = $request->only(['grade_id']);
        return Excel::download(new ClassesExport($filters), 'classes_' . date('Y-m-d_His') . '.xlsx');
    }

    /**
     * تصدير الفصول
     */
    public function sections(Request $request)
    {
        $filters = $request->only(['class_id']);
        return Excel::download(new SectionsExport($filters), 'sections_' . date('Y-m-d_His') . '.xlsx');
    }

    /**
     * تصدير المواد
     */
    public function subjects(Request $request)
    {
        return Excel::download(new SubjectsExport(), 'subjects_' . date('Y-m-d_His') . '.xlsx');
    }

    /**
     * تصدير الحضور
     */
    public function attendances(Request $request)
    {
        $filters = $request->only(['student_id', 'section_id', 'status', 'date_from', 'date_to']);
        return Excel::download(new AttendancesExport($filters), 'attendances_' . date('Y-m-d_His') . '.xlsx');
    }

    /**
     * تصدير الدرجات
     */
    public function gradeRecords(Request $request)
    {
        $filters = $request->only(['student_id', 'subject_id', 'date_from', 'date_to']);
        return Excel::download(new GradeRecordsExport($filters), 'grade_records_' . date('Y-m-d_His') . '.xlsx');
    }

    /**
     * تصدير الفواتير
     */
    public function invoices(Request $request)
    {
        $filters = $request->only(['student_id', 'status', 'date_from', 'date_to']);
        return Excel::download(new InvoicesExport($filters), 'invoices_' . date('Y-m-d_His') . '.xlsx');
    }

    /**
     * تصدير المدفوعات
     */
    public function payments(Request $request)
    {
        $filters = $request->only(['student_id', 'status', 'date_from', 'date_to']);
        return Excel::download(new PaymentsExport($filters), 'payments_' . date('Y-m-d_His') . '.xlsx');
    }

    /**
     * تصدير الكتب
     */
    public function books(Request $request)
    {
        $filters = $request->only(['category_id']);
        return Excel::download(new BooksExport($filters), 'books_' . date('Y-m-d_His') . '.xlsx');
    }

    /**
     * تصدير الاستعارات
     */
    public function bookBorrowings(Request $request)
    {
        $filters = $request->only(['student_id', 'status', 'date_from', 'date_to']);
        return Excel::download(new BookBorrowingsExport($filters), 'book_borrowings_' . date('Y-m-d_His') . '.xlsx');
    }

    /**
     * تصدير الاختبارات
     */
    public function exams(Request $request)
    {
        $filters = $request->only(['subject_id', 'section_id']);
        return Excel::download(new ExamsExport($filters), 'exams_' . date('Y-m-d_His') . '.xlsx');
    }

    /**
     * تصدير الواجبات
     */
    public function assignments(Request $request)
    {
        $filters = $request->only(['subject_id', 'section_id', 'status']);
        return Excel::download(new AssignmentsExport($filters), 'assignments_' . date('Y-m-d_His') . '.xlsx');
    }
}

