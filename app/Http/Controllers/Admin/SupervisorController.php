<?php

namespace App\Http\Controllers\Admin;

use App\Http\Controllers\Concerns\AuthorizesAdminResource;
use App\Http\Controllers\Controller;
use App\Models\Supervisor;
use App\Models\User;
use Illuminate\Http\Request;
use Illuminate\Validation\Rule;

class SupervisorController extends Controller
{
    use AuthorizesAdminResource;

    public function __construct()
    {
        $this->middleware('auth');
        $this->authorizeAdminResource('supervisor');
    }

    public function index()
    {
        $supervisors = Supervisor::with('user')->orderBy('supervisor_code')->paginate(15);

        return view('admin.pages.transport.supervisors.index', compact('supervisors'));
    }

    public function create()
    {
        $users = User::orderBy('name')->get();

        return view('admin.pages.transport.supervisors.create', compact('users'));
    }

    public function store(Request $request)
    {
        $validated = $request->validate($this->rules());

        Supervisor::create($validated);

        return redirect()->route('admin.supervisors.index')
            ->with('success', 'تم إضافة المشرف بنجاح.');
    }

    public function show(Supervisor $supervisor)
    {
        $supervisor->load(['user', 'studentTransports.student.user']);

        return view('admin.pages.transport.supervisors.show', compact('supervisor'));
    }

    public function edit(Supervisor $supervisor)
    {
        $users = User::orderBy('name')->get();

        return view('admin.pages.transport.supervisors.edit', compact('supervisor', 'users'));
    }

    public function update(Request $request, Supervisor $supervisor)
    {
        $validated = $request->validate($this->rules($supervisor->id));

        $supervisor->update($validated);

        return redirect()->route('admin.supervisors.index')
            ->with('success', 'تم تحديث بيانات المشرف بنجاح.');
    }

    public function destroy(Supervisor $supervisor)
    {
        if ($supervisor->studentTransports()->exists()) {
            return redirect()->route('admin.supervisors.index')
                ->with('error', 'لا يمكن حذف المشرف لأنه مرتبط بنقل طلاب.');
        }

        $supervisor->delete();

        return redirect()->route('admin.supervisors.index')
            ->with('success', 'تم حذف المشرف بنجاح.');
    }

    private function rules(?int $ignoreId = null): array
    {
        return [
            'user_id' => 'nullable|exists:users,id',
            'supervisor_code' => [
                'required',
                'string',
                'max:50',
                Rule::unique('supervisors', 'supervisor_code')->ignore($ignoreId),
            ],
            'phone' => 'nullable|string|max:20',
            'status' => 'required|in:active,inactive',
        ];
    }
}
