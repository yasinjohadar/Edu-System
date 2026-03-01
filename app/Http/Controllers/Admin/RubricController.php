<?php

namespace App\Http\Controllers\Admin;

use App\Http\Controllers\Controller;
use App\Models\Rubric;
use Illuminate\Http\Request;

class RubricController extends Controller
{
    /**
     * Display a listing of the resource.
     */
    public function index()
    {
        $rubrics = Rubric::with('creator')
            ->orderBy('created_at', 'desc')
            ->paginate(20);
        
        return view('admin.rubrics.index', compact('rubrics'));
    }

    /**
     * Show the form for creating a new resource.
     */
    public function create()
    {
        return view('admin.rubrics.create');
    }

    /**
     * Store a newly created resource in storage.
     */
    public function store(Request $request)
    {
        $validated = $request->validate([
            'name' => 'required|string|max:255',
            'description' => 'nullable|string',
            'max_points' => 'required|numeric|min:1',
            'criteria' => 'nullable|array',
            'criteria.*.name' => 'required|string|max:255',
            'criteria.*.max_points' => 'required|numeric|min:1',
            'criteria.*.description' => 'nullable|string',
        ]);

        $rubric = Rubric::create($validated);

        return redirect()
            ->route('admin.rubrics.index')
            ->with('success', 'تم إنشاء معيار التقييم بنجاح');
    }

    /**
     * Show the form for editing the specified resource.
     */
    public function edit(Rubric $rubric)
    {
        $rubric->load('creator');
        
        return view('admin.rubrics.edit', compact('rubric'));
    }

    /**
     * Update the specified resource in storage.
     */
    public function update(Request $request, Rubric $rubric)
    {
        $validated = $request->validate([
            'name' => 'required|string|max:255',
            'description' => 'nullable|string',
            'max_points' => 'required|numeric|min:1',
            'criteria' => 'nullable|array',
            'criteria.*.name' => 'required|string|max:255',
            'criteria.*.max_points' => 'required|numeric|min:1',
            'criteria.*.description' => 'nullable|string',
        ]);

        $rubric->update($validated);

        return redirect()
            ->route('admin.rubrics.index')
            ->with('success', 'تم تحديث معيار التقييم بنجاح');
    }

    /**
     * Remove the specified resource from storage.
     */
    public function destroy(Rubric $rubric)
    {
        // Check if rubric is used by essay questions
        $isUsed = $rubric->essayQuestions()->exists();
        
        if ($isUsed) {
            return back()->with('error', 'لا يمكن حذف معيار التقييم المستخدم');
        }

        $rubric->delete();

        return redirect()
            ->route('admin.rubrics.index')
            ->with('success', 'تم حذف معيار التقييم بنجاح');
    }
}
