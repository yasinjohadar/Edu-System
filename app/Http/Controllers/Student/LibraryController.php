<?php

namespace App\Http\Controllers\Student;

use App\Http\Controllers\Controller;
use Illuminate\Http\Request;
use Illuminate\Support\Facades\Auth;
use App\Models\Student;
use App\Models\BookBorrowing;
use App\Models\Fine;

class LibraryController extends Controller
{
    public function __construct()
    {
        $this->middleware('auth');
        $this->middleware('role:student');
    }

    /**
     * عرض الاستعارات
     */
    public function borrowings(Request $request)
    {
        $user = Auth::user();
        $student = $user->student;

        if (!$student) {
            return redirect()->route('login')->with('error', 'لا يوجد حساب طالب مرتبط بهذا المستخدم');
        }

        $query = $student->bookBorrowings()
            ->with(['book.category', 'borrowedBy', 'returnedBy', 'fine'])
            ->orderBy('borrow_date', 'desc');

        // فلترة حسب الحالة
        if ($request->filled('status')) {
            $query->where('status', $request->status);
        }

        $borrowings = $query->paginate(20);

        // إحصائيات
        $totalBorrowings = $student->bookBorrowings()->count();
        $activeBorrowings = $student->bookBorrowings()->whereIn('status', ['borrowed', 'overdue'])->count();
        $returnedBorrowings = $student->bookBorrowings()->where('status', 'returned')->count();
        $overdueBorrowings = $student->bookBorrowings()->where('status', 'overdue')->count();

        $stats = [
            'total_borrowings' => $totalBorrowings,
            'active_borrowings' => $activeBorrowings,
            'returned_borrowings' => $returnedBorrowings,
            'overdue_borrowings' => $overdueBorrowings,
        ];

        return view('student.pages.library.borrowings', compact('borrowings', 'stats'));
    }

    /**
     * عرض تفاصيل الاستعارة
     */
    public function showBorrowing($id)
    {
        $user = Auth::user();
        $student = $user->student;

        if (!$student) {
            return redirect()->route('login')->with('error', 'لا يوجد حساب طالب مرتبط بهذا المستخدم');
        }

        $borrowing = BookBorrowing::where('student_id', $student->id)
            ->with(['book.category', 'borrower', 'returner', 'fine'])
            ->findOrFail($id);

        return view('student.pages.library.show-borrowing', compact('borrowing'));
    }

    /**
     * عرض الغرامات
     */
    public function fines(Request $request)
    {
        $user = Auth::user();
        $student = $user->student;

        if (!$student) {
            return redirect()->route('login')->with('error', 'لا يوجد حساب طالب مرتبط بهذا المستخدم');
        }

        $query = $student->fines()
            ->with(['borrowing.book'])
            ->orderBy('fine_date', 'desc');

        // فلترة حسب الحالة
        if ($request->filled('status')) {
            $query->where('status', $request->status);
        }

        $fines = $query->paginate(20);

        // إحصائيات
        $totalFines = $student->fines()->count();
        $pendingFines = $student->fines()->where('status', 'pending')->count();
        $paidFines = $student->fines()->where('status', 'paid')->count();
        $totalAmount = $student->fines()->sum('amount');
        $paidAmount = $student->fines()->where('status', 'paid')->sum('amount');
        $pendingAmount = $student->fines()->where('status', 'pending')->sum('amount');

        $stats = [
            'total_fines' => $totalFines,
            'pending_fines' => $pendingFines,
            'paid_fines' => $paidFines,
            'total_amount' => $totalAmount,
            'paid_amount' => $paidAmount,
            'pending_amount' => $pendingAmount,
        ];

        return view('student.pages.library.fines', compact('fines', 'stats'));
    }
}

