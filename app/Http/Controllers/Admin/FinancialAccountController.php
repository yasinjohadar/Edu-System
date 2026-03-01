<?php

namespace App\Http\Controllers\Admin;

use App\Http\Controllers\Controller;
use App\Models\FinancialAccount;
use App\Models\Student;
use Illuminate\Http\Request;

class FinancialAccountController extends Controller
{
    public function __construct()
    {
        $this->middleware('permission:financial-account-list|financial-account-view', ['only' => ['index', 'show']]);
    }

    public function index(Request $request)
    {
        $query = FinancialAccount::with('student.user');

        if ($request->filled('student_id')) {
            $query->where('student_id', $request->student_id);
        }

        if ($request->filled('account_number')) {
            $query->where('account_number', 'like', '%' . $request->account_number . '%');
        }

        $accounts = $query->orderBy('created_at', 'desc')->paginate(50);
        $students = Student::with('user')->where('status', 'active')->get();

        return view('admin.pages.financial-accounts.index', compact('accounts', 'students'));
    }

    public function show(string $id)
    {
        $account = FinancialAccount::with([
            'student.user',
            'invoices' => function($query) {
                $query->orderBy('invoice_date', 'desc');
            },
            'payments' => function($query) {
                $query->orderBy('payment_date', 'desc');
            }
        ])->findOrFail($id);

        // تحديث رصيد الحساب
        $account->updateBalance();

        return view('admin.pages.financial-accounts.show', compact('account'));
    }
}
