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
        $this->middleware('auth');
        $this->middleware('permission:financial-account-list|financial-account-view', ['only' => ['index', 'show']]);
    }

    public function index(Request $request)
    {
        $accounts = $this->buildAccountsQuery($request)->paginate(25)->withQueryString();
        $students = Student::with('user')->where('status', 'active')->get();

        if ($request->ajax()) {
            return response()->json([
                'body' => view('admin.partials.financial-accounts-table-body', compact('accounts'))->render(),
                'extra' => view('admin.partials.financial-accounts-table-footer', compact('accounts'))->render(),
                'from' => $accounts->firstItem(),
                'to' => $accounts->lastItem(),
                'total' => $accounts->total(),
            ]);
        }

        return view('admin.pages.financial-accounts.index', compact('accounts', 'students'));
    }

    private function buildAccountsQuery(Request $request)
    {
        $query = FinancialAccount::with('student.user');

        if ($request->filled('query')) {
            $search = $request->input('query');
            $query->where(function ($q) use ($search) {
                $q->where('account_number', 'like', "%{$search}%")
                    ->orWhereHas('student.user', function ($uq) use ($search) {
                        $uq->where('name', 'like', "%{$search}%");
                    })
                    ->orWhereHas('student', function ($sq) use ($search) {
                        $sq->where('student_code', 'like', "%{$search}%");
                    });
            });
        }

        if ($request->filled('student_id')) {
            $query->where('student_id', $request->student_id);
        }

        if ($request->filled('account_number')) {
            $query->where('account_number', 'like', '%' . $request->account_number . '%');
        }

        return $query->orderBy('created_at', 'desc');
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
