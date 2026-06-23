<?php

use Illuminate\Support\Facades\Route;
use App\Http\Controllers\Student\DashboardController;
use App\Http\Controllers\Student\AttendanceController;
use App\Http\Controllers\Student\GradeController;
use App\Http\Controllers\Student\ScheduleController;
use App\Http\Controllers\Student\InvoiceController;
use App\Http\Controllers\Student\LibraryController;
use App\Http\Controllers\Student\OnlineLectureController;
use App\Http\Controllers\Student\AssignmentController;
use App\Http\Controllers\Student\ExamController;

Route::middleware('auth')->prefix('student')->name('student.')->group(function () {
    Route::get('/dashboard', [DashboardController::class, 'index'])
        ->middleware('role_or_permission:student|student-dashboard-view')
        ->name('dashboard');

    Route::get('/attendance', [AttendanceController::class, 'index'])
        ->middleware('role_or_permission:student|student-attendance-view')
        ->name('attendance.index');
    Route::get('/attendance/{id}', [AttendanceController::class, 'show'])
        ->middleware('role_or_permission:student|student-attendance-view')
        ->name('attendance.show');

    Route::get('/grades', [GradeController::class, 'index'])
        ->middleware('role_or_permission:student|student-grades-view')
        ->name('grades.index');
    Route::get('/grades/{id}', [GradeController::class, 'show'])
        ->middleware('role_or_permission:student|student-grades-view')
        ->name('grades.show');

    Route::get('/schedule', [ScheduleController::class, 'index'])
        ->middleware('role_or_permission:student|student-schedule-view')
        ->name('schedule.index');

    Route::get('/invoices', [InvoiceController::class, 'index'])
        ->middleware('role_or_permission:student|student-invoices-view')
        ->name('invoices.index');
    Route::get('/invoices/{id}', [InvoiceController::class, 'show'])
        ->middleware('role_or_permission:student|student-invoices-view')
        ->name('invoices.show');

    Route::get('/library/borrowings', [LibraryController::class, 'borrowings'])
        ->middleware('role_or_permission:student|student-library-view')
        ->name('library.borrowings');
    Route::get('/library/borrowings/{id}', [LibraryController::class, 'showBorrowing'])
        ->middleware('role_or_permission:student|student-library-view')
        ->name('library.borrowings.show');
    Route::get('/library/fines', [LibraryController::class, 'fines'])
        ->middleware('role_or_permission:student|student-library-view')
        ->name('library.fines');

    Route::get('/lectures', [OnlineLectureController::class, 'index'])
        ->middleware('role_or_permission:student|student-lecture-view')
        ->name('lectures.index');
    Route::get('/lectures/{id}', [OnlineLectureController::class, 'show'])
        ->middleware('role_or_permission:student|student-lecture-view')
        ->name('lectures.show');

    Route::get('/assignments', [AssignmentController::class, 'index'])
        ->middleware('role_or_permission:student|student-assignments-view')
        ->name('assignments.index');
    Route::get('/assignments/{id}', [AssignmentController::class, 'show'])
        ->middleware('role_or_permission:student|student-assignments-view')
        ->name('assignments.show');
    Route::get('/assignments/{id}/submit', [AssignmentController::class, 'submit'])
        ->middleware('role_or_permission:student|assignment-submit')
        ->name('assignments.submit');
    Route::post('/assignments/{id}/submit', [AssignmentController::class, 'storeSubmission'])
        ->middleware('role_or_permission:student|assignment-submit')
        ->name('assignments.store-submission');
    Route::get('/assignments/submissions/{id}', [AssignmentController::class, 'showSubmission'])
        ->middleware('role_or_permission:student|student-assignments-view')
        ->name('assignments.submissions.show');
    Route::post('/assignments/submissions/{id}/resubmit', [AssignmentController::class, 'resubmit'])
        ->middleware('role_or_permission:student|assignment-submit')
        ->name('assignments.submissions.resubmit');

    Route::get('/exams', [ExamController::class, 'index'])
        ->middleware('role_or_permission:student|student-exam-view')
        ->name('exams.index');
    Route::get('/exams/{exam}/take', [ExamController::class, 'take'])
        ->middleware('role_or_permission:student|student-exam-take')
        ->name('exams.take');
    Route::post('/exams/{exam}/submit', [ExamController::class, 'submit'])
        ->middleware('role_or_permission:student|student-exam-take')
        ->name('exams.submit');
    Route::get('/exams/results/{result}', [ExamController::class, 'result'])
        ->middleware('role_or_permission:student|student-exam-view')
        ->name('exams.result');
    Route::get('/exams/results/{result}/review', [ExamController::class, 'review'])
        ->middleware('role_or_permission:student|student-exam-view')
        ->name('exams.review');
});
