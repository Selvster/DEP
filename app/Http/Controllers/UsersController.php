<?php

namespace App\Http\Controllers;

use Illuminate\Http\Request;
use Illuminate\Http\RedirectResponse;
use Illuminate\View\View;
use Illuminate\Http\JsonResponse;
use App\Models\User;
use Spatie\Activitylog\Models\Activity;
use Yajra\DataTables\Facades\DataTables;

class UsersController extends Controller
{

    /**
     * Display a listing of the users.
     */
    public function index(Request $request): View|JsonResponse
    {
        if ($request->ajax()) {
            $users = User::with(['roles'])
                ->select(['id', 'name', 'email', 'created_at'])
                ->orderBy('created_at', 'desc');

            $dataTable = DataTables::of($users)
                ->addColumn('roles', function ($user) {
                    if ($user->roles->count() > 0) {
                        $roles = '';
                        foreach ($user->roles as $role) {
                            $badgeClass =  'bg-purple-100 text-purple-700';
                            $roleName =  $role->name;
                            $roles .= '<span class="inline-flex items-center px-4 py-2 rounded-full text-sm font-medium border shadow-sm mr-2 mb-2 ' . $badgeClass . '">' . $roleName . '</span>';
                        }
                        return $roles;
                    }
                    return '<span class="text-gray-500">بدون دور</span>';
                })
                ->addColumn('created_at_formatted', function ($user) {
                    return $user->created_at->format('Y-m-d H:i');
                })
                ->addColumn('actions', function ($user) {
                    return view('users.partials.actions', compact('user'))->render();
                })
                ->rawColumns(['roles', 'actions']);

            return $dataTable->make(true);
        }

        return view('users.index');
    }

    /**
     * Show the form for creating a new user.
     */
    public function create(): View
    {
        $roles = \Spatie\Permission\Models\Role::orderBy('name')->get();
        return view('users.create', compact('roles'));
    }

    /**
     * Store a newly created user in storage.
     */
    public function store(Request $request): RedirectResponse
    {
        $validated = $request->validate([
            'name' => 'required|string|max:255',
            'email' => 'required|string|email|max:255|unique:users',
            'password' => 'required|string|min:8|confirmed',
            'roles' => 'array',
            'roles.*' => 'exists:roles,id'
        ], [
            'name.required' => 'الاسم مطلوب',
            'name.max' => 'الاسم يجب أن يكون أقل من 255 حرف',
            'email.required' => 'البريد الإلكتروني مطلوب',
            'email.email' => 'البريد الإلكتروني غير صحيح',
            'email.unique' => 'البريد الإلكتروني موجود مسبقاً',
            'password.required' => 'كلمة المرور مطلوبة',
            'password.min' => 'كلمة المرور يجب أن تكون 8 أحرف على الأقل',
            'password.confirmed' => 'تأكيد كلمة المرور غير مطابق',
            'roles.array' => 'الأدوار يجب أن تكون مصفوفة',
            'roles.*.exists' => 'إحدى الأدوار غير صحيحة',
        ]);

        $user = User::create([
            'name' => $validated['name'],
            'email' => $validated['email'],
            'password' => \Hash::make($validated['password']),
            'email_verified_at' => now(),
        ]);

        // Assign roles if provided
        if ($request->has('roles')) {
            $roles = \Spatie\Permission\Models\Role::whereIn('id', $request->roles)->get();
            $user->assignRole($roles);
        }

        // Log the creation activity
        $userData = $this->resolveForeignKeyNames($user->toArray());
        $rolesData = $user->roles->pluck('name')->toArray();
        $userData['roles'] = $rolesData;

        activity()
            ->causedBy(auth()->user())
            ->performedOn($user)
            ->withProperties([
                'action' => 'created',
                'user_name' => $user->name,
                'user_email' => $user->email,
                'user_data' => $userData,
            ])
            ->log('تم إنشاء مستخدم جديد: ' . $user->name);

        return redirect()->route('users.index')
            ->with('success', 'تم إنشاء المستخدم بنجاح');
    }

    /**
     * Display the specified user.
     */
    public function show(User $user): View
    {
        $user->load(['roles']);
        return view('users.show', compact('user'));
    }

    /**
     * Show the form for editing the specified user.
     */
    public function edit(User $user): View
    {
        $roles = \Spatie\Permission\Models\Role::orderBy('name')->get();
        $user->load('roles');
        return view('users.edit', compact('user', 'roles'));
    }

    /**
     * Update the specified user in storage.
     */
    public function update(Request $request, User $user): RedirectResponse
    {
        $validated = $request->validate([
            'name' => 'required|string|max:255',
            'email' => 'required|string|email|max:255|unique:users,email,' . $user->id,
            'password' => 'nullable|string|min:8|confirmed',
            'roles' => 'array',
            'roles.*' => 'exists:roles,id'
        ], [
            'name.required' => 'الاسم مطلوب',
            'name.max' => 'الاسم يجب أن يكون أقل من 255 حرف',
            'email.required' => 'البريد الإلكتروني مطلوب',
            'email.email' => 'البريد الإلكتروني غير صحيح',
            'email.unique' => 'البريد الإلكتروني موجود مسبقاً',
            'password.min' => 'كلمة المرور يجب أن تكون 8 أحرف على الأقل',
            'password.confirmed' => 'تأكيد كلمة المرور غير مطابق',
            'roles.array' => 'الأدوار يجب أن تكون مصفوفة',
            'roles.*.exists' => 'إحدى الأدوار غير صحيحة',
        ]);

        $oldData = $user->toArray();
        
        $updateData = [
            'name' => $validated['name'],
            'email' => $validated['email'],
        ];

        if (!empty($validated['password'])) {
            $updateData['password'] = \Hash::make($validated['password']);
        }

        $user->update($updateData);

        // Update roles if provided
        if ($request->has('roles')) {
            $roles = \Spatie\Permission\Models\Role::whereIn('id', $request->roles)->get();
            $user->syncRoles($roles);
        }

        // Resolve foreign key names for old and new data
        $oldDataWithNames = $this->resolveForeignKeyNames($oldData);
        $oldRolesData = $user->getRoleNames()->toArray();
        $oldDataWithNames['roles'] = $oldRolesData;

        $newDataWithNames = $this->resolveForeignKeyNames($user->fresh()->toArray());
        $newRolesData = $user->fresh()->getRoleNames()->toArray();
        $newDataWithNames['roles'] = $newRolesData;

        // Log the update activity
        activity()
            ->causedBy(auth()->user())
            ->performedOn($user)
            ->withProperties([
                'action' => 'updated',
                'user_name' => $user->name,
                'user_email' => $user->email,
                'old_data' => $oldDataWithNames,
                'new_data' => $newDataWithNames,
            ])
            ->log('تم تحديث بيانات المستخدم: ' . $user->name);

        return redirect()->route('users.index')
            ->with('success', 'تم تحديث بيانات المستخدم بنجاح');
    }

    /**
     * Remove the specified user from storage.
     */
    public function destroy(User $user): RedirectResponse
    {
        // Prevent deletion of super admin
        if ($user->hasRole('super_admin')) {
            return redirect()->route('users.index')
                ->with('error', 'لا يمكن حذف المستخدم الرئيسي');
        }

        $userData = $user->toArray();
        
        $user->delete();

        // Resolve foreign key names for deleted data
        $resolvedData = $this->resolveForeignKeyNames($userData);
        
        // Log the deletion activity
        activity()
            ->causedBy(auth()->user())
            ->performedOn($user)
            ->withProperties([
                'action' => 'deleted',
                'user_name' => $userData['name'],
                'user_email' => $userData['email'],
                'deleted_data' => $resolvedData,
            ])
            ->log('تم حذف المستخدم: ' . $userData['name']);

        return redirect()->route('users.index')
            ->with('success', 'تم حذف المستخدم بنجاح');
    }

    /**
     * Resolve foreign key IDs to their names
     */
    private function resolveForeignKeyNames(array $data): array
    {
        $resolvedData = $data;

        // For users, we don't have foreign keys to resolve, but we can add user-specific fields
        // This method is here for consistency and future extensibility
        
        return $resolvedData;
    }
}