<?php

use Illuminate\Support\Facades\Route;
use App\Http\Controllers\Admin\UserController;
use App\Http\Controllers\Admin\RoleController;
use App\Http\Controllers\Admin\GradeController;
use App\Http\Controllers\Admin\ClassController;
use App\Http\Controllers\Admin\SectionController;
use App\Http\Controllers\Admin\SubjectController;
use App\Http\Controllers\Admin\TeacherController;
use App\Http\Controllers\Admin\AttendanceController;
use App\Http\Controllers\Admin\StudentController;
use App\Http\Controllers\Admin\ScheduleController;
use App\Http\Controllers\Admin\GradeRecordController;
use App\Http\Controllers\Admin\FeeTypeController;
use App\Http\Controllers\Admin\InvoiceController;
use App\Http\Controllers\Admin\PaymentController;
use App\Http\Controllers\Admin\FinancialAccountController;
use App\Http\Controllers\Admin\BookCategoryController;
use App\Http\Controllers\Admin\BookController;
use App\Http\Controllers\Admin\BookBorrowingController;
use App\Http\Controllers\Admin\FineController;
use App\Http\Controllers\Admin\OnlineLectureController;
use App\Http\Controllers\Admin\LectureMaterialController;
use App\Http\Controllers\Admin\LectureAttendanceController;
use App\Http\Controllers\Admin\AssignmentController;
use App\Http\Controllers\Admin\AssignmentSubmissionController;
use App\Http\Controllers\Admin\SmtpSettingController;
use App\Http\Controllers\Admin\ExamController;
use App\Http\Controllers\Admin\QuestionController;
use App\Http\Controllers\Admin\ExamQuestionController;
use App\Http\Controllers\Admin\ExamAnswerController;
use App\Http\Controllers\Admin\ExamResultController;
use App\Http\Controllers\Admin\RubricController;
use App\Http\Controllers\Admin\EssayEvaluationController;
use App\Http\Controllers\Admin\ReportController;
use App\Http\Controllers\Admin\ExportController;
use App\Http\Controllers\Admin\EventController;
use App\Http\Controllers\Admin\CalendarController;
use App\Http\Controllers\Admin\AcademicCalendarController;
use App\Http\Controllers\Admin\CertificateController;
use App\Http\Controllers\Admin\CertificateTemplateController;
use App\Http\Controllers\Admin\BusRouteController;
use App\Http\Controllers\Admin\BusStopController;
use App\Http\Controllers\Admin\DriverController;
use App\Http\Controllers\Admin\SupervisorController;
use App\Http\Controllers\Admin\StudentTransportController;
use App\Http\Controllers\Admin\HostelController;
use App\Http\Controllers\Admin\RoomController;
use App\Http\Controllers\Admin\StudentAccommodationController;
use App\Http\Controllers\Admin\VisitorController;
use App\Http\Controllers\Admin\AlumniController;
use App\Http\Controllers\Admin\AlumniEventController;
use App\Http\Controllers\Admin\JobPostingController;
use App\Http\Controllers\Admin\AlumniDonationController;

Route::middleware(['auth', 'check.user.active'])->prefix('admin')->name('admin.')->group(function () {
    Route::resource('users', UserController::class);
    Route::resource('roles', RoleController::class);
    Route::put('users/{user}/change-password', [UserController::class, 'updatePassword'])->name('users.update-password');
    
    // النظام الأكاديمي
    Route::resource('grades', GradeController::class);
    Route::resource('classes', ClassController::class);
    Route::resource('sections', SectionController::class);
    Route::resource('subjects', SubjectController::class);
    Route::resource('teachers', TeacherController::class);
    
    // نظام إدارة الطلاب
    Route::resource('students', StudentController::class);
    
    // نظام الحضور والغياب
    Route::resource('attendances', AttendanceController::class);
    
    // نظام الجدول الدراسي
    Route::resource('schedules', ScheduleController::class);
    
    // نظام الدرجات والتقييم
    Route::resource('grade-records', GradeRecordController::class);
    
    // النظام المالي
    Route::resource('fee-types', FeeTypeController::class);
    Route::resource('invoices', InvoiceController::class);
    Route::get('invoices-json', [InvoiceController::class, 'getInvoicesJson'])->name('invoices.json');
    Route::resource('payments', PaymentController::class);
    Route::resource('financial-accounts', FinancialAccountController::class)->only(['index', 'show']);
    
    // نظام المكتبة
    Route::resource('book-categories', BookCategoryController::class);
    Route::resource('books', BookController::class);
    Route::resource('book-borrowings', BookBorrowingController::class);
    Route::post('book-borrowings/{id}/return', [BookBorrowingController::class, 'return'])->name('book-borrowings.return');
    Route::resource('fines', FineController::class);
    Route::post('fines/{id}/pay', [FineController::class, 'pay'])->name('fines.pay');
    
    // نظام المحاضرات الإلكترونية
    Route::resource('online-lectures', OnlineLectureController::class);
    Route::resource('lecture-materials', LectureMaterialController::class);
    Route::resource('lecture-attendance', LectureAttendanceController::class);
    
    // نظام الواجبات
    Route::resource('assignments', AssignmentController::class);
    Route::post('assignments/{id}/publish', [AssignmentController::class, 'publish'])->name('assignments.publish');
    Route::post('assignments/{id}/close', [AssignmentController::class, 'close'])->name('assignments.close');
    Route::get('assignments/{assignmentId}/submissions', [AssignmentSubmissionController::class, 'index'])->name('assignments.submissions.index');
    Route::get('assignments/{assignmentId}/submissions/{submissionId}', [AssignmentSubmissionController::class, 'show'])->name('assignments.submissions.show');
    Route::post('assignments/{assignmentId}/submissions/{submissionId}/grade', [AssignmentSubmissionController::class, 'grade'])->name('assignments.submissions.grade');
    Route::post('assignments/{assignmentId}/submissions/{submissionId}/request-resubmission', [AssignmentSubmissionController::class, 'requestResubmission'])->name('assignments.submissions.request-resubmission');
    Route::get('assignments/{assignmentId}/submissions/{submissionId}/download-files', [AssignmentSubmissionController::class, 'downloadFiles'])->name('assignments.submissions.download-files');
    
    // نظام إعدادات SMTP
    Route::resource('smtp-settings', SmtpSettingController::class);
    Route::post('smtp-settings/test-connection', [SmtpSettingController::class, 'testConnection'])->name('smtp-settings.test-connection');
    Route::post('smtp-settings/{id}/set-default', [SmtpSettingController::class, 'setDefault'])->name('smtp-settings.set-default');
    Route::post('smtp-settings/{id}/toggle-active', [SmtpSettingController::class, 'toggleActive'])->name('smtp-settings.toggle-active');
    
    // نظام الأسئلة والاختبارات
    Route::resource('questions', QuestionController::class);
    Route::get('questions/types', [QuestionController::class, 'getQuestionTypes'])->name('questions.types');
    Route::get('questions/difficulty-levels', [QuestionController::class, 'getDifficultyLevels'])->name('questions.difficulty-levels');
    
    Route::resource('exams', ExamController::class);
    Route::post('exams/{exam}/publish', [ExamController::class, 'publish'])->name('exams.publish');
    Route::post('exams/{exam}/unpublish', [ExamController::class, 'unpublish'])->name('exams.unpublish');
    Route::get('exams/{exam}/statistics', [ExamController::class, 'statistics'])->name('exams.statistics');
    Route::get('exams/{exam}/questions', [ExamQuestionController::class, 'index'])->name('exams.questions.index');
    Route::post('exams/{exam}/questions', [ExamQuestionController::class, 'store'])->name('exams.questions.store');
    Route::put('exams/{exam}/questions/{question}', [ExamQuestionController::class, 'update'])->name('exams.questions.update');
    Route::delete('exams/{exam}/questions/{question}', [ExamQuestionController::class, 'destroy'])->name('exams.questions.destroy');
    Route::post('exams/{exam}/questions/{question}/reorder', [ExamQuestionController::class, 'reorder'])->name('exams.questions.reorder');
    
    Route::resource('exam-answers', ExamAnswerController::class);
    Route::post('exam-answers/{answer}/auto-grade', [ExamAnswerController::class, 'autoGrade'])->name('exam-answers.auto-grade');
    
    Route::resource('exam-results', ExamResultController::class);
    Route::get('exam-results/export', [ExamResultController::class, 'export'])->name('exam-results.export');
    Route::get('exam-results/{result}/statistics', [ExamResultController::class, 'statistics'])->name('exam-results.statistics');
    
    Route::resource('rubrics', RubricController::class);
    Route::get('rubrics/{rubric}/usage', [RubricController::class, 'checkUsage'])->name('rubrics.usage');
    
    Route::resource('essay-evaluations', EssayEvaluationController::class);
    Route::post('essay-evaluations/{evaluation}/recalculate', [EssayEvaluationController::class, 'recalculateResult'])->name('essay-evaluations.recalculate');
    
    // نظام التصدير
    Route::prefix('export')->name('export.')->group(function () {
        Route::get('/students', [ExportController::class, 'students'])->name('students');
        Route::get('/teachers', [ExportController::class, 'teachers'])->name('teachers');
        Route::get('/classes', [ExportController::class, 'classes'])->name('classes');
        Route::get('/sections', [ExportController::class, 'sections'])->name('sections');
        Route::get('/subjects', [ExportController::class, 'subjects'])->name('subjects');
        Route::get('/attendances', [ExportController::class, 'attendances'])->name('attendances');
        Route::get('/grade-records', [ExportController::class, 'gradeRecords'])->name('grade-records');
        Route::get('/invoices', [ExportController::class, 'invoices'])->name('invoices');
        Route::get('/payments', [ExportController::class, 'payments'])->name('payments');
        Route::get('/books', [ExportController::class, 'books'])->name('books');
        Route::get('/book-borrowings', [ExportController::class, 'bookBorrowings'])->name('book-borrowings');
        Route::get('/exams', [ExportController::class, 'exams'])->name('exams');
        Route::get('/assignments', [ExportController::class, 'assignments'])->name('assignments');
    });
    
    // نظام التقارير
    Route::prefix('reports')->name('reports.')->group(function () {
        Route::get('/', [ReportController::class, 'index'])->name('index');
        Route::get('/create', [ReportController::class, 'create'])->name('create');
        Route::get('/student-performance', [ReportController::class, 'studentPerformance'])->name('student-performance');
        Route::get('/class-performance', [ReportController::class, 'classPerformance'])->name('class-performance');
        Route::get('/teacher-performance', [ReportController::class, 'teacherPerformance'])->name('teacher-performance');
        Route::get('/attendance', [ReportController::class, 'attendance'])->name('attendance');
        Route::get('/financial', [ReportController::class, 'financial'])->name('financial');
        Route::get('/library', [ReportController::class, 'library'])->name('library');
        Route::get('/exams', [ReportController::class, 'exams'])->name('exams');
        Route::get('/assignments', [ReportController::class, 'assignments'])->name('assignments');
        Route::get('/grades', [ReportController::class, 'grades'])->name('grades');
        Route::post('/export', [ReportController::class, 'export'])->name('export');
    });
    
    // نظام الأحداث والتقويم
    Route::resource('events', EventController::class);
    Route::get('calendar/month', [CalendarController::class, 'month'])->name('calendar.month');
    Route::get('calendar/week', [CalendarController::class, 'week'])->name('calendar.week');
    Route::get('calendar/day', [CalendarController::class, 'day'])->name('calendar.day');
    Route::resource('academic-calendars', AcademicCalendarController::class);
    
    // نظام الشهادات
    Route::resource('certificates', CertificateController::class);
    Route::resource('certificate-templates', CertificateTemplateController::class);
    
    // نظام النقل
    Route::resource('bus-routes', BusRouteController::class);
    Route::resource('bus-stops', BusStopController::class);
    Route::resource('drivers', DriverController::class);
    Route::resource('supervisors', SupervisorController::class);
    Route::resource('student-transports', StudentTransportController::class);
    
    // نظام السكن
    Route::resource('hostels', HostelController::class);
    Route::resource('rooms', RoomController::class);
    Route::resource('student-accommodations', StudentAccommodationController::class);
    Route::resource('visitors', VisitorController::class);
    
    // نظام الخريجين
    Route::resource('alumni', AlumniController::class);
    Route::resource('alumni-events', AlumniEventController::class);
    Route::resource('job-postings', JobPostingController::class);
    Route::resource('alumni-donations', AlumniDonationController::class);
});