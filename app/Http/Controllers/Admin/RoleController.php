<?php

namespace App\Http\Controllers\Admin;

use App\Support\Permissions;
use Illuminate\Http\Request;
use Spatie\Permission\Models\Permission;
use Spatie\Permission\Models\Role;
use App\Http\Controllers\Concerns\AuthorizesAdminResource;
use App\Http\Controllers\Controller;

class RoleController extends Controller
{
    use AuthorizesAdminResource;

    public function __construct()
    {
        $this->middleware('auth');
        $this->authorizeAdminResource('role', false);
    }

    public function index(Request $request)
    {
        $roles = $this->buildRolesQuery($request)->paginate(15)->withQueryString();

        if ($request->ajax()) {
            return response()->json([
                'body' => view('admin.partials.roles-table-body', compact('roles'))->render(),
                'extra' => view('admin.partials.roles-table-footer', compact('roles'))->render(),
                'from' => $roles->firstItem(),
                'to' => $roles->lastItem(),
                'total' => $roles->total(),
            ]);
        }

        return view('admin.pages.roles.index', compact('roles'));
    }

    private function buildRolesQuery(Request $request)
    {
        $query = Role::query()->withCount('permissions')->orderBy('name');

        if ($request->filled('query')) {
            $search = $request->input('query');
            $query->where('name', 'like', "%{$search}%");
        }

        return $query;
    }

    public function create()
    {
        $permissionNames = Permission::orderBy('name')->pluck('name');
        $permissionGroups = Permissions::groupedForPicker($permissionNames);

        return view('admin.pages.roles.create', compact('permissionGroups'));
    }

    public function store(Request $request)
    {
        $request->validate([
            'name' => 'required|string|max:255|unique:roles,name',
            'permissions' => 'nullable|array',
            'permissions.*' => 'string|exists:permissions,name',
        ], [
            'name.required' => 'اسم الدور مطلوب',
            'name.unique' => 'اسم الدور مستخدم بالفعل',
        ]);

        $role = Role::create(['name' => $request->name]);
        $role->syncPermissions($request->input('permissions', []));

        return redirect()->route('roles.index')->with('success', 'تم إضافة الدور بنجاح');
    }

    public function show(string $id)
    {
        //
    }

    public function edit(string $id)
    {
        $role = Role::with('permissions')->findOrFail($id);
        $permissionNames = Permission::orderBy('name')->pluck('name');
        $permissionGroups = Permissions::groupedForPicker($permissionNames);

        return view('admin.pages.roles.edit', compact('role', 'permissionGroups'));
    }

    public function update(Request $request, string $id)
    {
        $role = Role::findOrFail($id);

        $request->validate([
            'name' => 'required|string|max:255|unique:roles,name,' . $role->id,
            'permissions' => 'nullable|array',
            'permissions.*' => 'string|exists:permissions,name',
        ], [
            'name.required' => 'اسم الدور مطلوب',
            'name.unique' => 'اسم الدور مستخدم بالفعل',
        ]);

        $role->update(['name' => $request->name]);
        $role->syncPermissions($request->input('permissions', []));

        return redirect()->route('roles.index')->with('success', 'تم تعديل الدور بنجاح');
    }

    public function destroy(string $id)
    {
        $role = Role::findOrFail($id);
        $role->delete();

        return redirect()->route('roles.index')->with('success', 'تم حذف الدور بنجاح');
    }
}
