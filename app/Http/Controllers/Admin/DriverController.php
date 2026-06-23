<?php

namespace App\Http\Controllers\Admin;

use App\Http\Controllers\Concerns\AuthorizesAdminResource;
use App\Http\Controllers\Controller;
use App\Models\Driver;
use App\Models\User;
use Illuminate\Http\Request;
use Illuminate\Validation\Rule;

class DriverController extends Controller
{
    use AuthorizesAdminResource;

    public function __construct()
    {
        $this->middleware('auth');
        $this->authorizeAdminResource('driver');
    }

    public function index()
    {
        $drivers = Driver::with('user')->orderBy('driver_code')->paginate(15);

        return view('admin.pages.transport.drivers.index', compact('drivers'));
    }

    public function create()
    {
        $users = User::orderBy('name')->get();

        return view('admin.pages.transport.drivers.create', compact('users'));
    }

    public function store(Request $request)
    {
        $validated = $request->validate($this->rules());

        Driver::create($validated);

        return redirect()->route('admin.drivers.index')
            ->with('success', 'تم إضافة السائق بنجاح.');
    }

    public function show(Driver $driver)
    {
        $driver->load(['user', 'studentTransports.student.user']);

        return view('admin.pages.transport.drivers.show', compact('driver'));
    }

    public function edit(Driver $driver)
    {
        $users = User::orderBy('name')->get();

        return view('admin.pages.transport.drivers.edit', compact('driver', 'users'));
    }

    public function update(Request $request, Driver $driver)
    {
        $validated = $request->validate($this->rules($driver->id));

        $driver->update($validated);

        return redirect()->route('admin.drivers.index')
            ->with('success', 'تم تحديث بيانات السائق بنجاح.');
    }

    public function destroy(Driver $driver)
    {
        if ($driver->studentTransports()->exists()) {
            return redirect()->route('admin.drivers.index')
                ->with('error', 'لا يمكن حذف السائق لأنه مرتبط بنقل طلاب.');
        }

        $driver->delete();

        return redirect()->route('admin.drivers.index')
            ->with('success', 'تم حذف السائق بنجاح.');
    }

    private function rules(?int $ignoreId = null): array
    {
        return [
            'user_id' => 'nullable|exists:users,id',
            'driver_code' => [
                'required',
                'string',
                'max:50',
                Rule::unique('drivers', 'driver_code')->ignore($ignoreId),
            ],
            'license_number' => [
                'required',
                'string',
                'max:100',
                Rule::unique('drivers', 'license_number')->ignore($ignoreId),
            ],
            'license_expiry' => 'nullable|date',
            'phone' => 'nullable|string|max:20',
            'address' => 'nullable|string|max:500',
            'status' => 'required|in:active,inactive,on_leave',
        ];
    }
}
