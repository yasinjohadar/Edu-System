<?php

namespace App\Http\Controllers\Admin;

use App\Http\Controllers\Concerns\AuthorizesAdminResource;
use App\Http\Controllers\Controller;
use App\Models\BusRoute;
use Illuminate\Http\Request;
use Illuminate\Validation\Rule;

class BusRouteController extends Controller
{
    use AuthorizesAdminResource;

    public function __construct()
    {
        $this->middleware('auth');
        $this->authorizeAdminResource('bus-route');
    }

    public function index()
    {
        $routes = BusRoute::orderBy('route_name')->paginate(15);

        return view('admin.pages.transport.bus-routes.index', compact('routes'));
    }

    public function create()
    {
        return view('admin.pages.transport.bus-routes.create');
    }

    public function store(Request $request)
    {
        $validated = $request->validate($this->rules());

        BusRoute::create([
            ...$validated,
            'is_active' => (bool) $request->input('is_active', 1),
        ]);

        return redirect()->route('admin.bus-routes.index')
            ->with('success', 'تم إنشاء مسار الحافلة بنجاح.');
    }

    public function show(BusRoute $busRoute)
    {
        $busRoute->load(['stops' => fn ($q) => $q->orderBy('order'), 'studentTransports.student.user']);

        return view('admin.pages.transport.bus-routes.show', compact('busRoute'));
    }

    public function edit(BusRoute $busRoute)
    {
        return view('admin.pages.transport.bus-routes.edit', compact('busRoute'));
    }

    public function update(Request $request, BusRoute $busRoute)
    {
        $validated = $request->validate($this->rules($busRoute->id));

        $busRoute->update([
            ...$validated,
            'is_active' => (bool) $request->input('is_active', 0),
        ]);

        return redirect()->route('admin.bus-routes.index')
            ->with('success', 'تم تحديث مسار الحافلة بنجاح.');
    }

    public function destroy(BusRoute $busRoute)
    {
        if ($busRoute->studentTransports()->exists()) {
            return redirect()->route('admin.bus-routes.index')
                ->with('error', 'لا يمكن حذف المسار لأنه مرتبط بنقل طلاب.');
        }

        $busRoute->delete();

        return redirect()->route('admin.bus-routes.index')
            ->with('success', 'تم حذف مسار الحافلة بنجاح.');
    }

    private function rules(?int $ignoreId = null): array
    {
        return [
            'route_name' => 'required|string|max:255',
            'route_number' => [
                'required',
                'string',
                'max:50',
                Rule::unique('bus_routes', 'route_number')->ignore($ignoreId),
            ],
            'description' => 'nullable|string',
            'distance' => 'nullable|numeric|min:0',
            'start_time' => 'required|date_format:H:i',
            'end_time' => 'required|date_format:H:i|after:start_time',
            'fee' => 'nullable|numeric|min:0',
        ];
    }
}
