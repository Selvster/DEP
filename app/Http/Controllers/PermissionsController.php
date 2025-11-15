<?php

namespace App\Http\Controllers;

use Illuminate\Http\Request;
use Illuminate\Http\RedirectResponse;
use Illuminate\View\View;
use Illuminate\Http\JsonResponse;
use Spatie\Permission\Models\Permission;
use Spatie\Permission\Models\Role;
use Spatie\Activitylog\Models\Activity;
use Yajra\DataTables\Facades\DataTables;

class PermissionsController extends Controller
{
    /**
     * Display a listing of the permissions.
     */
    public function index(Request $request): View|JsonResponse
    {
        if ($request->ajax()) {
            $permissions = Permission::with(['roles'])
                ->select(['id', 'name', 'created_at'])
                ->orderBy('created_at', 'desc');

            return DataTables::of($permissions)
                ->addColumn('roles', function ($permission) {
                    if ($permission->roles->count() > 0) {
                        $roles = '';
                        foreach ($permission->roles as $role) {
                            $badgeClass = $role->name === 'super_admin' ? 'bg-purple-50 text-purple-700 border-purple-200' : 'bg-gray-50 text-gray-700 border-gray-200';
                            $roleName = $role->name;
                            $roles .= '<span class="inline-flex items-center px-4 py-2 rounded-full text-sm font-medium border shadow-sm mr-2 mb-2 ' . $badgeClass . '">' . $roleName . '</span>';
                        }
                        return $roles;
                    }
                    return '<span class="text-gray-500 text-sm">لا توجد أدوار</span>';
                })
                ->addColumn('created_at_formatted', function ($permission) {
                    return $permission->created_at->format('Y-m-d H:i');
                })
                ->addColumn('actions', function ($permission) {
                    return view('permissions.partials.actions', compact('permission'))->render();
                })
                ->rawColumns(['roles', 'actions'])
                ->make(true);
        }

        return view('permissions.index');
    }

    /**
     * Show the form for creating a new permission.
     */
    public function create(): View
    {
        return view('permissions.create');
    }

    /**
     * Store a newly created permission in storage.
     */
    public function store(Request $request): RedirectResponse
    {
        $request->validate([
            'name' => 'required|string|max:255|unique:permissions,name'
        ], [
            'name.required' => 'اسم الصلاحية مطلوب',
            'name.unique' => 'هذه الصلاحية موجودة بالفعل'
        ]);

        $permission = Permission::create([
            'name' => $request->name,
            'guard_name' => 'web'
        ]);

        // Log activity
        $permissionData = $this->resolveForeignKeyNames($permission->toArray());

        activity()
            ->causedBy(auth()->user())
            ->performedOn($permission)
            ->withProperties([
                'action' => 'created',
                'permission_name' => $permission->name,
                'permission_data' => $permissionData
            ])
            ->log('تم إنشاء صلاحية جديدة');

        return redirect()->route('permissions.index')
            ->with('success', 'تم إنشاء الصلاحية بنجاح');
    }

    /**
     * Display the specified permission.
     */
    public function show(Permission $permission): View
    {
        $permission->load('roles');
        return view('permissions.show', compact('permission'));
    }

    /**
     * Show the form for editing the specified permission.
     */
    public function edit(Permission $permission): View
    {
        return view('permissions.edit', compact('permission'));
    }

    /**
     * Update the specified permission in storage.
     */
    public function update(Request $request, Permission $permission): RedirectResponse
    {
        $request->validate([
            'name' => 'required|string|max:255|unique:permissions,name,' . $permission->id
        ], [
            'name.required' => 'اسم الصلاحية مطلوب',
            'name.unique' => 'هذه الصلاحية موجودة بالفعل'
        ]);

        $oldData = $this->resolveForeignKeyNames($permission->toArray());

        $permission->update([
            'name' => $request->name
        ]);

        // Log activity
        $newData = $this->resolveForeignKeyNames($permission->fresh()->toArray());

        activity()
            ->causedBy(auth()->user())
            ->performedOn($permission)
            ->withProperties([
                'action' => 'updated',
                'permission_name' => $permission->name,
                'old_data' => $oldData,
                'new_data' => $newData
            ])
            ->log('تم تحديث الصلاحية');

        return redirect()->route('permissions.index')
            ->with('success', 'تم تحديث الصلاحية بنجاح');
    }

    /**
     * Remove the specified permission from storage.
     */
    public function destroy(Permission $permission): RedirectResponse
    {
        $permissionData = $this->resolveForeignKeyNames($permission->toArray());
        // Remove guard_name from logging
        unset($permissionData['guard_name']);
        $permissionName = $permission->name;

        // Log activity before deletion
        activity()
            ->causedBy(auth()->user())
            ->performedOn($permission)
            ->withProperties([
                'action' => 'deleted',
                'permission_name' => $permissionName,
                'deleted_data' => $permissionData
            ])
            ->log('تم حذف الصلاحية');

        $permission->delete();

        return redirect()->route('permissions.index')
            ->with('success', 'تم حذف الصلاحية بنجاح');
    }

    /**
     * Resolve foreign key names for activity logging
     */
    private function resolveForeignKeyNames(array $data): array
    {
        return $data;
    }
}