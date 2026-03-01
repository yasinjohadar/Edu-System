<?php

namespace App\Http\Controllers\Admin;

use App\Http\Controllers\Controller;
use App\Models\FeeType;
use Illuminate\Http\Request;

class FeeTypeController extends Controller
{
    public function __construct()
    {
        $this->middleware('permission:fee-type-list|fee-type-create|fee-type-edit|fee-type-delete', ['only' => ['index', 'show']]);
        $this->middleware('permission:fee-type-create', ['only' => ['create', 'store']]);
        $this->middleware('permission:fee-type-edit', ['only' => ['edit', 'update']]);
        $this->middleware('permission:fee-type-delete', ['only' => ['destroy']]);
    }

    public function index()
    {
        $feeTypes = FeeType::orderBy('sort_order')->orderBy('name')->paginate(50);
        return view('admin.pages.fee-types.index', compact('feeTypes'));
    }

    public function create()
    {
        return view('admin.pages.fee-types.create');
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
            'is_recurring' => 'nullable|boolean',
            'recurring_period' => 'nullable|in:monthly,quarterly,semester,yearly',
            'is_active' => 'nullable|boolean',
            'sort_order' => 'nullable|integer|min:0',
        ]);

        FeeType::create($validated);

        return redirect()->route('admin.fee-types.index')
            ->with('success', 'تم إنشاء نوع الرسوم بنجاح.');
    }

    public function show(string $id)
    {
        $feeType = FeeType::findOrFail($id);
        return view('admin.pages.fee-types.show', compact('feeType'));
    }

    public function edit(string $id)
    {
        $feeType = FeeType::findOrFail($id);
        return view('admin.pages.fee-types.edit', compact('feeType'));
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
            'is_recurring' => 'nullable|boolean',
            'recurring_period' => 'nullable|in:monthly,quarterly,semester,yearly',
            'is_active' => 'nullable|boolean',
            'sort_order' => 'nullable|integer|min:0',
        ]);

        $feeType->update($validated);

        return redirect()->route('admin.fee-types.index')
            ->with('success', 'تم تحديث نوع الرسوم بنجاح.');
    }

    public function destroy(string $id)
    {
        $feeType = FeeType::findOrFail($id);
        
        // التحقق من وجود فواتير تستخدم هذا النوع
        if ($feeType->invoiceItems()->count() > 0) {
            return redirect()->route('admin.fee-types.index')
                ->with('error', 'لا يمكن حذف نوع الرسوم لأنه مستخدم في فواتير موجودة.');
        }

        $feeType->delete();

        return redirect()->route('admin.fee-types.index')
            ->with('success', 'تم حذف نوع الرسوم بنجاح.');
    }
}
