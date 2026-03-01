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

Route::middleware(['auth', 'role:student'])->prefix('student')->name('student.')->group(function () {
    // Dashboard
    Route::get('/dashboard', [DashboardController::class, 'index'])->name('dashboard');
    
    // Attendance
    Route::get('/attendance', [AttendanceController::class, 'index'])->name('attendance.index');
    Route::get('/attendance/{id}', [AttendanceController::class, 'show'])->name('attendance.show');
    
    // Grades
    Route::get('/grades', [GradeController::class, 'index'])->name('grades.index');
    Route::get('/grades/{id}', [GradeController::class, 'show'])->name('grades.show');
    
    // Schedule
    Route::get('/schedule', [ScheduleController::class, 'index'])->name('schedule.index');
    
    // Invoices
    Route::get('/invoices', [InvoiceController::class, 'index'])->name('invoices.index');
    Route::get('/invoices/{id}', [InvoiceController::class, 'show'])->name('invoices.show');
    
    // Library
    Route::get('/library/borrowings', [LibraryController::class, 'borrowings'])->name('library.borrowings');
    Route::get('/library/borrowings/{id}', [LibraryController::class, 'showBorrowing'])->name('library.borrowings.show');
    Route::get('/library/fines', [LibraryController::class, 'fines'])->name('library.fines');
    
    // Online Lectures
    Route::get('/lectures', [OnlineLectureController::class, 'index'])->name('lectures.index');
    Route::get('/lectures/{id}', [OnlineLectureController::class, 'show'])->name('lectures.show');
    
    // Assignments
    Route::get('/assignments', [AssignmentController::class, 'index'])->name('assignments.index');
    Route::get('/assignments/{id}', [AssignmentController::class, 'show'])->name('assignments.show');
    Route::get('/assignments/{id}/submit', [AssignmentController::class, 'submit'])->name('assignments.submit');
    Route::post('/assignments/{id}/submit', [AssignmentController::class, 'storeSubmission'])->name('assignments.store-submission');
    Route::get('/assignments/submissions/{id}', [AssignmentController::class, 'showSubmission'])->name('assignments.submissions.show');
    Route::post('/assignments/submissions/{id}/resubmit', [AssignmentController::class, 'resubmit'])->name('assignments.submissions.resubmit');
    
    // Exams
    Route::get('/exams', [ExamController::class, 'index'])->name('exams.index');
    Route::get('/exams/{exam}/take', [ExamController::class, 'take'])->name('exams.take');
    Route::post('/exams/{exam}/submit', [ExamController::class, 'submit'])->name('exams.submit');
    Route::get('/exams/results/{result}', [ExamController::class, 'result'])->name('exams.result');
    Route::get('/exams/results/{result}/review', [ExamController::class, 'review'])->name('exams.review');
});