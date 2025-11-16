<?php

namespace App\Http\Controllers;

use Illuminate\Http\Request;
use Illuminate\Http\RedirectResponse;
use Illuminate\View\View;
use Illuminate\Http\JsonResponse;
use Spatie\Permission\Models\Role;
use Spatie\Permission\Models\Permission;
use Spatie\Activitylog\Models\Activity;
use Yajra\DataTables\Facades\DataTables;

class RolesController extends Controller
{
    /**
     * Display a listing of the roles.
     */
    public function index(Request $request): View|JsonResponse
    {
        if ($request->ajax()) {
            $roles = Role::with(['permissions'])
                ->select(['id', 'name', 'created_at'])
                ->orderBy('created_at', 'desc');

            return DataTables::of($roles)
				->addColumn('permissions', function ($role) {
					if ($role->permissions->count() === 0) {
						return '<span class="text-gray-500 text-sm">لا توجد صلاحيات</span>';
					}
					$grouped = [];
					foreach ($role->permissions as $permission) {
						$parts = explode('_', $permission->name);
						$action = array_shift($parts); 
						$module = implode('_', $parts);
						$translatedModule = __('modules.' . $module);
						if ($translatedModule === 'modules.' . $module) {
							$translatedModule = ucfirst(str_replace('_', ' ', $module));
						}
						$translatedAction = __('permissions.' . $action);
						if ($translatedAction === 'permissions.' . $action) {
							$translatedAction = $action;
						}
						$grouped[$translatedModule][] = $translatedAction;
					}
						$output = '<div class="space-y-2">';
						foreach ($grouped as $module => $actions) {
							$output .= '<div class="flex items-center flex-wrap border-b pb-2 mb-2">';
							$output .= '<div class="font-semibold text-gray-800 ml-2">' . e($module) . ':</div>';
							foreach ($actions as $action) {
								$colorClass = match ($action) {
									'عرض' => 'bg-blue-100 text-blue-700 border-blue-300',
									'إضافة' => 'bg-green-100 text-green-700 border-green-300',
									'تعديل' => 'bg-yellow-100 text-yellow-700 border-yellow-300',
									'حذف' => 'bg-red-100 text-red-700 border-red-300',
									default => 'bg-gray-100 text-gray-700 border-gray-300',
								};
						
								$output .= '<span class="inline-flex items-center px-2 py-1 rounded-full text-xs font-medium border ' . $colorClass . ' mr-1">'
										. e($action) . '</span>';
							}
							$output .= '</div>';
						}
						$output .= '</div>';
					return $output;
				})
                ->addColumn('created_at_formatted', function ($role) {
                   return $role->created_at->locale('ar')->translatedFormat('l، j F Y - h:i A');

                })
                ->addColumn('actions', function ($role) {
                    return view('roles.partials.actions', compact('role'))->render();
                })
                ->rawColumns(['permissions', 'actions'])
                ->make(true);
        }

        return view('roles.index');
    }

    /**
     * Show the form for creating a new role.
     */
    public function create(): View
    {
        $permissions = Permission::orderBy('name')->get();
        return view('roles.create', compact('permissions'));
    }

    /**
     * Store a newly created role in storage.
     */
    public function store(Request $request): RedirectResponse
    {
        $request->validate([
            'name' => 'required|string|max:255|unique:roles,name',
            'permissions' => 'array',
            'permissions.*' => 'exists:permissions,id'
        ], [
            'name.required' => 'اسم الدور مطلوب',
            'name.unique' => 'هذا الدور موجود بالفعل',
            'permissions.array' => 'الصلاحيات يجب أن تكون مصفوفة',
            'permissions.*.exists' => 'إحدى الصلاحيات غير صحيحة'
        ]);

        $role = Role::create([
            'name' => $request->name,
            'guard_name' => 'web'
        ]);

        if ($request->has('permissions')) {
            $permissions = Permission::whereIn('id', $request->permissions)->get();
            $role->syncPermissions($permissions);
        }

        // Log activity
        $roleData = $this->resolveForeignKeyNames($role->toArray());
        $permissionsData = $role->permissions->pluck('name')->toArray();
        $roleData['permissions'] = $permissionsData;

        activity()
            ->causedBy(auth()->user())
            ->performedOn($role)
            ->withProperties([
                'action' => 'created',
                'role_name' => $role->name,
                'role_data' => $roleData
            ])
            ->log('تم إنشاء دور جديد');

        return redirect()->route('roles.index')
            ->with('success', 'تم إنشاء الدور بنجاح');
    }

    /**
     * Display the specified role.
     */
    public function show(Role $role): View
    {
        $role->load('permissions');
        return view('roles.show', compact('role'));
    }

    /**
     * Show the form for editing the specified role.
     */
    public function edit(Role $role): View
    {
        $permissions = Permission::orderBy('name')->get();
        $role->load('permissions');
        return view('roles.edit', compact('role', 'permissions'));
    }

    /**
     * Update the specified role in storage.
     */
    public function update(Request $request, Role $role): RedirectResponse
    {
        $request->validate([
            'name' => 'required|string|max:255|unique:roles,name,' . $role->id,
            'permissions' => 'array',
            'permissions.*' => 'exists:permissions,id'
        ], [
            'name.required' => 'اسم الدور مطلوب',
            'name.unique' => 'هذا الدور موجود بالفعل',
            'permissions.array' => 'الصلاحيات يجب أن تكون مصفوفة',
            'permissions.*.exists' => 'إحدى الصلاحيات غير صحيحة'
        ]);

        $oldData = $this->resolveForeignKeyNames($role->toArray());
        $oldPermissions = $role->permissions->pluck('name')->toArray();
        $oldData['permissions'] = $oldPermissions;

        $role->update([
            'name' => $request->name
        ]);

        if ($request->has('permissions')) {
            $permissions = Permission::whereIn('id', $request->permissions)->get();
            $role->syncPermissions($permissions);
        } else {
            $role->syncPermissions([]);
        }

        // Log activity
        $newData = $this->resolveForeignKeyNames($role->fresh()->toArray());
        $newPermissions = $role->fresh()->permissions->pluck('name')->toArray();
        $newData['permissions'] = $newPermissions;

        activity()
            ->causedBy(auth()->user())
            ->performedOn($role)
            ->withProperties([
                'action' => 'updated',
                'role_name' => $role->name,
                'old_data' => $oldData,
                'new_data' => $newData
            ])
            ->log('تم تحديث الدور');

        return redirect()->route('roles.index')
            ->with('success', 'تم تحديث الدور بنجاح');
    }

    /**
     * Remove the specified role from storage.
     */
    public function destroy(Role $role): RedirectResponse
    {
        // Prevent deletion of super_admin role
        if ($role->name === 'super_admin') {
            return redirect()->route('roles.index')
                ->with('error', 'لا يمكن حذف دور المدير العام');
        }

        $roleData = $this->resolveForeignKeyNames($role->toArray());
        // Remove guard_name from logging
        unset($roleData['guard_name']);
        $permissionsData = $role->permissions->pluck('name')->toArray();
        $roleData['permissions'] = $permissionsData;

        $roleName = $role->name;

        // Log activity before deletion
        activity()
            ->causedBy(auth()->user())
            ->performedOn($role)
            ->withProperties([
                'action' => 'deleted',
                'role_name' => $roleName,
                'deleted_data' => $roleData
            ])
            ->log('تم حذف الدور');

        $role->delete();

        return redirect()->route('roles.index')
            ->with('success', 'تم حذف الدور بنجاح');
    }

    /**
     * Resolve foreign key names for activity logging
     */
    private function resolveForeignKeyNames(array $data): array
    {
        return $data;
    }
}