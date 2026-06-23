<?php

namespace App\Http\Controllers\Admin;

use App\Http\Controllers\Concerns\AuthorizesAdminResource;
use App\Http\Controllers\Controller;
use App\Models\BusRoute;
use App\Models\BusStop;
use App\Models\StudentTransport;
use Illuminate\Http\Request;
use Illuminate\Validation\Rule;

class BusStopController extends Controller
{
    use AuthorizesAdminResource;

    public function __construct()
    {
        $this->middleware('auth');
        $this->authorizeAdminResource('bus-stop');
    }

    public function index()
    {
        $stops = BusStop::with('route')->orderBy('route_id')->orderBy('order')->paginate(15);

        return view('admin.pages.transport.bus-stops.index', compact('stops'));
    }

    public function create()
    {
        $routes = BusRoute::where('is_active', true)->orderBy('route_name')->get();

        return view('admin.pages.transport.bus-stops.create', compact('routes'));
    }

    public function store(Request $request)
    {
        $validated = $request->validate($this->rules($request));

        BusStop::create($validated);

        return redirect()->route('admin.bus-stops.index')
            ->with('success', 'تم إنشاء المحطة بنجاح.');
    }

    public function show(BusStop $busStop)
    {
        $busStop->load('route');

        return view('admin.pages.transport.bus-stops.show', compact('busStop'));
    }

    public function edit(BusStop $busStop)
    {
        $routes = BusRoute::orderBy('route_name')->get();

        return view('admin.pages.transport.bus-stops.edit', compact('busStop', 'routes'));
    }

    public function update(Request $request, BusStop $busStop)
    {
        $validated = $request->validate($this->rules($request, $busStop->id));

        $busStop->update($validated);

        return redirect()->route('admin.bus-stops.index')
            ->with('success', 'تم تحديث المحطة بنجاح.');
    }

    public function destroy(BusStop $busStop)
    {
        if (StudentTransport::where('stop_id', $busStop->id)->exists()) {
            return redirect()->route('admin.bus-stops.index')
                ->with('error', 'لا يمكن حذف المحطة لأنها مرتبطة بنقل طلاب.');
        }

        $busStop->delete();

        return redirect()->route('admin.bus-stops.index')
            ->with('success', 'تم حذف المحطة بنجاح.');
    }

    private function rules(Request $request, ?int $ignoreId = null): array
    {
        return [
            'route_id' => 'required|exists:bus_routes,id',
            'stop_name' => 'required|string|max:255',
            'address' => 'nullable|string|max:500',
            'latitude' => 'nullable|numeric|between:-90,90',
            'longitude' => 'nullable|numeric|between:-180,180',
            'order' => 'nullable|integer|min:0',
            'arrival_time' => 'nullable|date_format:H:i',
        ];
    }
}
