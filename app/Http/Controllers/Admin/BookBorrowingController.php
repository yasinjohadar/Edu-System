<?php

namespace App\Http\Controllers\Admin;

use App\Http\Controllers\Controller;
use App\Models\BookBorrowing;
use App\Models\Book;
use App\Models\Student;
use Illuminate\Http\Request;
use Carbon\Carbon;

class BookBorrowingController extends Controller
{
    public function __construct()
    {
        $this->middleware('auth');
        $this->middleware('permission:book-borrowing-list')->only('index', 'show');
        $this->middleware('permission:book-borrowing-create')->only('create', 'store');
        $this->middleware('permission:book-borrowing-edit')->only('edit', 'update', 'return');
        $this->middleware('permission:book-borrowing-delete')->only('destroy');
    }

    public function index(Request $request)
    {
        $borrowingsQuery = BookBorrowing::with(['book', 'student.user', 'borrower'])->orderBy('borrow_date', 'desc');

        if ($request->filled('status')) {
            $borrowingsQuery->where('status', $request->input('status'));
        }

        if ($request->filled('student_id')) {
            $borrowingsQuery->where('student_id', $request->input('student_id'));
        }

        if ($request->filled('book_id')) {
            $borrowingsQuery->where('book_id', $request->input('book_id'));
        }

        $borrowings = $borrowingsQuery->paginate(20);
        $students = Student::with('user')->where('status', 'active')->get();
        $books = Book::where('is_active', true)->where('available_copies', '>', 0)->get();

        return view('admin.pages.book-borrowings.index', compact('borrowings', 'students', 'books'));
    }

    public function create()
    {
        $students = Student::with('user')->where('status', 'active')->get();
        $books = Book::where('is_active', true)->where('available_copies', '>', 0)->get();
        return view('admin.pages.book-borrowings.create', compact('students', 'books'));
    }

    public function store(Request $request)
    {
        $request->validate([
            'book_id' => 'required|exists:books,id',
            'student_id' => 'required|exists:students,id',
            'borrow_date' => 'required|date',
            'due_date' => 'required|date|after:borrow_date',
            'notes' => 'nullable|string',
        ]);

        $book = Book::findOrFail($request->book_id);

        if (!$book->isAvailable()) {
            return redirect()->back()->with('error', 'الكتاب غير متاح للاستعارة');
        }

        // التحقق من عدم وجود استعارة نشطة لنفس الكتاب والطالب
        $existingBorrowing = BookBorrowing::where('book_id', $request->book_id)
            ->where('student_id', $request->student_id)
            ->whereIn('status', ['borrowed', 'overdue'])
            ->first();

        if ($existingBorrowing) {
            return redirect()->back()->with('error', 'الطالب لديه استعارة نشطة لهذا الكتاب');
        }

        $borrowing = BookBorrowing::create([
            'book_id' => $request->book_id,
            'student_id' => $request->student_id,
            'borrowing_number' => BookBorrowing::generateBorrowingNumber(),
            'borrow_date' => $request->borrow_date,
            'due_date' => $request->due_date,
            'status' => 'borrowed',
            'notes' => $request->notes,
            'borrowed_by' => auth()->id(),
        ]);

        $book->updateAvailableCopies();

        return redirect()->route('admin.book-borrowings.index')->with('success', 'تم تسجيل الاستعارة بنجاح');
    }

    public function show(string $id)
    {
        $borrowing = BookBorrowing::with(['book.category', 'student.user', 'borrower', 'returner', 'fine'])->findOrFail($id);
        return view('admin.pages.book-borrowings.show', compact('borrowing'));
    }

    public function edit(string $id)
    {
        $borrowing = BookBorrowing::findOrFail($id);
        $students = Student::with('user')->where('status', 'active')->get();
        $books = Book::where('is_active', true)->get();
        return view('admin.pages.book-borrowings.edit', compact('borrowing', 'students', 'books'));
    }

    public function update(Request $request, string $id)
    {
        $borrowing = BookBorrowing::findOrFail($id);

        $request->validate([
            'book_id' => 'required|exists:books,id',
            'student_id' => 'required|exists:students,id',
            'borrow_date' => 'required|date',
            'due_date' => 'required|date|after:borrow_date',
            'notes' => 'nullable|string',
        ]);

        $borrowing->update($request->all());

        return redirect()->route('admin.book-borrowings.index')->with('success', 'تم تحديث الاستعارة بنجاح');
    }

    public function return(Request $request, string $id)
    {
        $borrowing = BookBorrowing::findOrFail($id);

        if ($borrowing->status === 'returned') {
            return redirect()->back()->with('error', 'الكتاب تم إرجاعه مسبقاً');
        }

        $request->validate([
            'return_date' => 'required|date|after_or_equal:' . $borrowing->borrow_date->format('Y-m-d'),
            'status' => 'required|in:returned,damaged,lost',
            'notes' => 'nullable|string',
        ]);

        $borrowing->update([
            'return_date' => $request->return_date,
            'status' => $request->status,
            'notes' => $request->notes,
            'returned_by' => auth()->id(),
        ]);

        $borrowing->book->updateAvailableCopies();

        return redirect()->route('admin.book-borrowings.index')->with('success', 'تم إرجاع الكتاب بنجاح');
    }

    public function destroy(string $id)
    {
        $borrowing = BookBorrowing::findOrFail($id);

        if ($borrowing->status === 'borrowed' || $borrowing->status === 'overdue') {
            return redirect()->route('admin.book-borrowings.index')
                ->with('error', 'لا يمكن حذف الاستعارة لأن الكتاب لم يتم إرجاعه');
        }

        $borrowing->delete();

        return redirect()->route('admin.book-borrowings.index')->with('success', 'تم حذف الاستعارة بنجاح');
    }
}
