<?php

namespace App\Http\Controllers\Admin;

use App\Http\Controllers\Controller;
use App\Models\Fine;
use App\Models\BookBorrowing;
use App\Models\Student;
use Illuminate\Http\Request;
use Carbon\Carbon;

class FineController extends Controller
{
    public function __construct()
    {
        $this->middleware('auth');
        $this->middleware('permission:fine-list')->only('index', 'show');
        $this->middleware('permission:fine-create')->only('create', 'store');
        $this->middleware('permission:fine-edit')->only('edit', 'update', 'pay');
        $this->middleware('permission:fine-delete')->only('destroy');
    }

    public function index(Request $request)
    {
        $finesQuery = Fine::with(['borrowing.book', 'student.user', 'creator'])->orderBy('created_at', 'desc');

        if ($request->filled('status')) {
            $finesQuery->where('status', $request->input('status'));
        }

        if ($request->filled('student_id')) {
            $finesQuery->where('student_id', $request->input('student_id'));
        }

        if ($request->filled('type')) {
            $finesQuery->where('type', $request->input('type'));
        }

        $fines = $finesQuery->paginate(20);
        $students = Student::with('user')->where('status', 'active')->get();

        return view('admin.pages.fines.index', compact('fines', 'students'));
    }

    public function create()
    {
        $borrowings = BookBorrowing::with(['book', 'student.user'])
            ->whereIn('status', ['overdue', 'borrowed'])
            ->whereDoesntHave('fine')
            ->get();
        $students = Student::with('user')->where('status', 'active')->get();
        return view('admin.pages.fines.create', compact('borrowings', 'students'));
    }

    public function store(Request $request)
    {
        $request->validate([
            'borrowing_id' => 'nullable|exists:book_borrowings,id',
            'student_id' => 'required|exists:students,id',
            'amount' => 'required|numeric|min:0',
            'type' => 'required|in:late_return,damaged,lost',
            'reason' => 'nullable|string',
            'due_date' => 'required|date',
            'notes' => 'nullable|string',
        ]);

        Fine::create([
            'borrowing_id' => $request->borrowing_id,
            'student_id' => $request->student_id,
            'fine_number' => Fine::generateFineNumber(),
            'amount' => $request->amount,
            'type' => $request->type,
            'reason' => $request->reason,
            'status' => 'pending',
            'due_date' => $request->due_date,
            'notes' => $request->notes,
            'created_by' => auth()->id(),
        ]);

        return redirect()->route('admin.fines.index')->with('success', 'تم إنشاء الغرامة بنجاح');
    }

    public function show(string $id)
    {
        $fine = Fine::with(['borrowing.book', 'student.user', 'creator', 'payer'])->findOrFail($id);
        return view('admin.pages.fines.show', compact('fine'));
    }

    public function edit(string $id)
    {
        $fine = Fine::findOrFail($id);
        $borrowings = BookBorrowing::with(['book', 'student.user'])->get();
        $students = Student::with('user')->where('status', 'active')->get();
        return view('admin.pages.fines.edit', compact('fine', 'borrowings', 'students'));
    }

    public function update(Request $request, string $id)
    {
        $fine = Fine::findOrFail($id);

        $request->validate([
            'borrowing_id' => 'nullable|exists:book_borrowings,id',
            'student_id' => 'required|exists:students,id',
            'amount' => 'required|numeric|min:0',
            'type' => 'required|in:late_return,damaged,lost',
            'reason' => 'nullable|string',
            'due_date' => 'required|date',
            'notes' => 'nullable|string',
        ]);

        $fine->update($request->all());

        return redirect()->route('admin.fines.index')->with('success', 'تم تحديث الغرامة بنجاح');
    }

    public function pay(Request $request, string $id)
    {
        $fine = Fine::findOrFail($id);

        if ($fine->status === 'paid') {
            return redirect()->back()->with('error', 'الغرامة مدفوعة مسبقاً');
        }

        $fine->update([
            'status' => 'paid',
            'paid_date' => Carbon::today(),
            'paid_by' => auth()->id(),
        ]);

        return redirect()->route('admin.fines.index')->with('success', 'تم تسجيل دفع الغرامة بنجاح');
    }

    public function destroy(string $id)
    {
        $fine = Fine::findOrFail($id);

        if ($fine->status === 'paid') {
            return redirect()->route('admin.fines.index')
                ->with('error', 'لا يمكن حذف الغرامة لأنها مدفوعة');
        }

        $fine->delete();

        return redirect()->route('admin.fines.index')->with('success', 'تم حذف الغرامة بنجاح');
    }
}
