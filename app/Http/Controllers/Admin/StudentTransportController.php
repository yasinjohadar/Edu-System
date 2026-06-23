<?php

namespace App\Http\Controllers\Admin;

use App\Http\Controllers\Concerns\AuthorizesAdminResource;
use App\Http\Controllers\Controller;
use App\Models\BusRoute;
use App\Models\BusStop;
use App\Models\Driver;
use App\Models\Student;
use App\Models\StudentTransport;
use App\Models\Supervisor;
use Illuminate\Http\Request;
use Illuminate\Validation\Rule;

class StudentTransportController extends Controller
{
    use AuthorizesAdminResource;

    public function __construct()
    {
        $this->middleware('auth');
        $this->authorizeAdminResource('student-transport');
    }

    public function index()
    {
        $transports = StudentTransport::with([
            'student.user',
            'route',
            'stop',
            'driver.user',
            'supervisor.user',
        ])->orderByDesc('start_date')->paginate(15);

        return view('admin.pages.transport.student-transports.index', compact('transports'));
    }

    public function create()
    {
        return view('admin.pages.transport.student-transports.create', $this->formData());
    }

    public function store(Request $request)
    {
        $validated = $request->validate($this->rules($request));

        StudentTransport::create($validated);

        return redirect()->route('admin.student-transports.index')
            ->with('success', 'تم تسجيل نقل الطالب بنجاح.');
    }

    public function show(StudentTransport $studentTransport)
    {
        $studentTransport->load([
            'student.user',
            'route',
            'stop',
            'driver.user',
            'supervisor.user',
        ]);

        return view('admin.pages.transport.student-transports.show', compact('studentTransport'));
    }

    public function edit(StudentTransport $studentTransport)
    {
        return view('admin.pages.transport.student-transports.edit', [
            'transport' => $studentTransport,
            ...$this->formData(),
        ]);
    }

    public function update(Request $request, StudentTransport $studentTransport)
    {
        $validated = $request->validate($this->rules($request));

        $studentTransport->update($validated);

        return redirect()->route('admin.student-transports.index')
            ->with('success', 'تم تحديث نقل الطالب بنجاح.');
    }

    public function destroy(StudentTransport $studentTransport)
    {
        $studentTransport->delete();

        return redirect()->route('admin.student-transports.index')
            ->with('success', 'تم حذف سجل النقل بنجاح.');
    }

    private function formData(): array
    {
        $stops = BusStop::orderBy('route_id')->orderBy('order')->get();

        return [
            'students' => Student::with('user')->where('status', 'active')->orderBy('student_code')->get(),
            'routes' => BusRoute::where('is_active', true)->orderBy('route_name')->get(),
            'stops' => $stops,
            'stopsByRoute' => $stops->groupBy('route_id'),
            'drivers' => Driver::with('user')->where('status', 'active')->orderBy('driver_code')->get(),
            'supervisors' => Supervisor::with('user')->where('status', 'active')->orderBy('supervisor_code')->get(),
        ];
    }

    private function rules(Request $request, ?int $ignoreId = null): array
    {
        return [
            'student_id' => 'required|exists:students,id',
            'route_id' => 'required|exists:bus_routes,id',
            'stop_id' => [
                'nullable',
                'exists:bus_stops,id',
                Rule::exists('bus_stops', 'id')->where(fn ($q) => $q->where('route_id', $request->input('route_id'))),
            ],
            'driver_id' => 'nullable|exists:drivers,id',
            'supervisor_id' => 'nullable|exists:supervisors,id',
            'start_date' => 'required|date',
            'end_date' => 'nullable|date|after_or_equal:start_date',
            'status' => 'required|in:active,inactive,suspended',
        ];
    }
}
