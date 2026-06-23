<?php

namespace App\Http\Controllers\Admin;

use App\Http\Controllers\Concerns\AuthorizesAdminResource;
use App\Http\Controllers\Controller;
use App\Models\FeeType;
use Illuminate\Http\Request;

class FeeTypeController extends Controller
{
    use AuthorizesAdminResource;

    public function __construct()
    {
        $this->middleware('auth');
        $this->authorizeAdminResource('fee-type', false);
    }

    public function index(Request $request)
    {
        $feeTypes = $this->buildFeeTypesQuery($request)->paginate(25)->withQueryString();
        $categories = $this->categories();

        if ($request->ajax()) {
            return response()->json([
                'body' => view('admin.partials.fee-types-table-body', compact('feeTypes'))->render(),
                'extra' => view('admin.partials.fee-types-table-footer', compact('feeTypes'))->render(),
                'from' => $feeTypes->firstItem(),
                'to' => $feeTypes->lastItem(),
                'total' => $feeTypes->total(),
            ]);
        }

        return view('admin.pages.fee-types.index', compact('feeTypes', 'categories'));
    }

    private function buildFeeTypesQuery(Request $request)
    {
        $query = FeeType::query()->orderBy('sort_order')->orderBy('name');

        if ($request->filled('query')) {
            $search = $request->input('query');
            $query->where(function ($q) use ($search) {
                $q->where('name', 'like', "%{$search}%")
                    ->orWhere('name_en', 'like', "%{$search}%")
                    ->orWhere('code', 'like', "%{$search}%");
            });
        }

        if ($request->filled('category')) {
            $query->where('category', $request->category);
        }

        if ($request->filled('is_active')) {
            $query->where('is_active', $request->is_active);
        }

        if ($request->filled('is_recurring')) {
            $query->where('is_recurring', $request->is_recurring);
        }

        return $query;
    }

    private function categories(): array
    {
        return [
            'tuition' => 'رسوم دراسية',
            'registration' => 'رسوم تسجيل',
            'activity' => 'رسوم نشاطات',
            'book' => 'رسوم كتب',
            'uniform' => 'رسوم زي موحد',
            'transport' => 'رسوم مواصلات',
            'other' => 'أخرى',
        ];
    }

    private function recurringPeriods(): array
    {
        return [
            'monthly' => 'شهري',
            'quarterly' => 'ربع سنوي',
            'semester' => 'فصلي',
            'yearly' => 'سنوي',
        ];
    }

    public function create()
    {
        return view('admin.pages.fee-types.create', [
            'categories' => $this->categories(),
            'recurringPeriods' => $this->recurringPeriods(),
        ]);
    }

    public function store(Request $request)
    {
        $validated = $request->validate([
            'name' => 'required|string|max:255',
            'name_en' => 'nullable|string|max:255',
            'code' => 'required|string|max:50|unique:fee_types,code',
            'description' => 'nullable|string',
            'category' => 'required|in:tuition,registration,activity,book,uniform,transport,other',
            'default_amount' => 'required|numeric|min:0',
            'recurring_period' => 'nullable|in:monthly,quarterly,semester,yearly',
            'sort_order' => 'nullable|integer|min:0',
        ]);

        FeeType::create([
            ...$validated,
            'is_recurring' => $request->has('is_recurring'),
            'recurring_period' => $request->has('is_recurring') ? $request->recurring_period : null,
            'is_active' => (bool) $request->input('is_active', 1),
            'sort_order' => $validated['sort_order'] ?? 0,
        ]);

        return redirect()->route('admin.fee-types.index')
            ->with('success', 'تم إنشاء نوع الرسوم بنجاح.');
    }

    public function edit(string $id)
    {
        $feeType = FeeType::findOrFail($id);

        return view('admin.pages.fee-types.edit', [
            'feeType' => $feeType,
            'categories' => $this->categories(),
            'recurringPeriods' => $this->recurringPeriods(),
        ]);
    }

    public function update(Request $request, string $id)
    {
        $feeType = FeeType::findOrFail($id);

        $validated = $request->validate([
            'name' => 'required|string|max:255',
            'name_en' => 'nullable|string|max:255',
            'code' => 'required|string|max:50|unique:fee_types,code,' . $id,
            'description' => 'nullable|string',
            'category' => 'required|in:tuition,registration,activity,book,uniform,transport,other',
            'default_amount' => 'required|numeric|min:0',
            'recurring_period' => 'nullable|in:monthly,quarterly,semester,yearly',
            'sort_order' => 'nullable|integer|min:0',
        ]);

        $feeType->update([
            ...$validated,
            'is_recurring' => $request->has('is_recurring'),
            'recurring_period' => $request->has('is_recurring') ? $request->recurring_period : null,
            'is_active' => (bool) $request->input('is_active', 0),
            'sort_order' => $validated['sort_order'] ?? 0,
        ]);

        return redirect()->route('admin.fee-types.index')
            ->with('success', 'تم تحديث نوع الرسوم بنجاح.');
    }

    public function destroy(string $id)
    {
        $feeType = FeeType::findOrFail($id);

        if ($feeType->invoiceItems()->count() > 0) {
            return redirect()->route('admin.fee-types.index')
                ->with('error', 'لا يمكن حذف نوع الرسوم لأنه مستخدم في فواتير موجودة.');
        }

        $feeType->delete();

        return redirect()->route('admin.fee-types.index')
            ->with('success', 'تم حذف نوع الرسوم بنجاح.');
    }
}
