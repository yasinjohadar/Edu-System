<?php

namespace App\Http\Controllers\Parent;

use App\Http\Controllers\Controller;
use Illuminate\Http\Request;
use Illuminate\Support\Facades\Auth;
use App\Models\ParentModel;

class DashboardController extends Controller
{
    public function __construct()
    {
        $this->middleware('auth');
        $this->middleware('role:parent');
    }

    /**
     * عرض لوحة تحكم ولي الأمر
     */
    public function index()
    {
        $user = Auth::user();
        $parent = $user->parent;

        if (!$parent) {
            return redirect()->route('login')->with('error', 'لا يوجد حساب ولي أمر مرتبط بهذا المستخدم');
        }

        // جلب جميع الأبناء المرتبطين
        $children = $parent->students()->with('user')->get();

        // إحصائيات لكل ابن
        $childrenStats = [];
        foreach ($children as $child) {
            $childrenStats[$child->id] = [
                'attendance_rate' => 0, // سيتم حسابها لاحقاً
                'average_grade' => 0, // سيتم حسابها لاحقاً
                'pending_assignments' => 0, // سيتم حسابها لاحقاً
            ];
        }

        return view('parent.pages.dashboard', compact('parent', 'children', 'childrenStats'));
    }
}
