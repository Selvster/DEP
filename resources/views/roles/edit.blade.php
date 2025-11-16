<x-app-layout>
    <div class="page-header">
        <div>
            <h1 class="page-title">تعديل الدور</h1>
            <p class="page-subtitle">تعديل بيانات الدور: {{ $role->name }}</p>
        </div>
    </div>

    <div class="bg-white shadow-sm rounded-lg p-6">
        <form action="{{ route('roles.update', $role) }}" method="POST">
            @csrf
            @method('PUT')
            
            <div class="mb-6">
                <label for="name" class="block text-sm font-medium text-gray-700 mb-2">
                    اسم الدور <span class="text-red-500">*</span>
                </label>
                <input type="text" 
                       id="name" 
                       name="name" 
                       value="{{ old('name', $role->name) }}"
                       class="w-full px-3 py-2 border border-gray-300 rounded-md shadow-sm focus:outline-none focus:ring-blue-500 focus:border-blue-500 @error('name') border-red-500 @enderror"
                       placeholder="أدخل اسم الدور"
                       required>
                @error('name')
                    <p class="mt-1 text-sm text-red-600">{{ $message }}</p>
                @enderror
            </div>

			<div class="mb-6">
				<label class="block text-sm font-medium text-gray-700 mb-2">
					الصلاحيات
				</label>
			
				<div class="overflow-x-auto border rounded-lg">
					<table class="min-w-full divide-y divide-gray-200 text-right">
						<thead class="bg-gray-100">
							<tr class="text-center">
								<th class="px-4 py-2 text-sm font-semibold text-gray-700">الموديول</th>
								<th class="px-4 py-2 text-sm font-semibold text-gray-700">عرض</th>
								<th class="px-4 py-2 text-sm font-semibold text-gray-700">إنشاء</th>
								<th class="px-4 py-2 text-sm font-semibold text-gray-700">تعديل</th>
								<th class="px-4 py-2 text-sm font-semibold text-gray-700">حذف</th>
							</tr>
						</thead>
						<tbody class="divide-y divide-gray-100">
							@php
							
								$groupedPermissions = $permissions->groupBy(function ($permission) {
									$parts = explode('_', $permission->name);
									array_shift($parts);
									return strtolower(implode('_', $parts));
								});
								$rolePermissionIds = $role->permissions->pluck('id')->toArray();
							@endphp
			
							@foreach($groupedPermissions as $module => $modulePermissions)
								<tr>
									<td class="px-4 py-2 font-medium text-gray-800">
										{{ __('modules.' . $module) }}
									</td>
									@foreach (['view', 'create', 'edit', 'delete'] as $action)
										@php
											$perm = $permissions->first(function ($p) use ($action, $module) {
												return strtolower($p->name) === strtolower($action . '_' . $module);
											});
										@endphp
										<td class="px-4 py-2 text-center">
											@if($perm)
												<input type="checkbox"
													id="permission_{{ $perm->id }}"
													name="permissions[]"
													value="{{ $perm->id }}"
													class="h-4 w-4 text-blue-600 focus:ring-blue-500 border-gray-300 rounded"
													{{ in_array($perm->id, $rolePermissionIds) ? 'checked' : '' }}>
											@else
												<span class="text-gray-400">—</span>
											@endif
										</td>
									@endforeach
								</tr>
							@endforeach
						</tbody>
					</table>
				</div>
				@error('permissions')
					<p class="mt-1 text-sm text-red-600">{{ $message }}</p>
				@enderror
			</div>


            <div class="mt-6 flex justify-end space-x-3 space-x-reverse">
                <a href="{{ route('roles.index') }}"
                   class="bg-gray-300 hover:bg-gray-400 text-gray-800 font-medium py-2 px-4 rounded-lg transition-colors duration-200">
                    إلغاء
                </a>
                <button type="submit"
                        class="bg-blue-600 hover:bg-blue-700 text-white font-medium py-2 px-4 rounded-lg transition-colors duration-200">
                    تحديث
                </button>
            </div>
        </form>
    </div>
</x-app-layout>
