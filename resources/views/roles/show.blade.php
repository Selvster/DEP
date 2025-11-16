<x-app-layout>
    <div class="page-header">
        <div>
            <h1 class="page-title">تفاصيل الدور</h1>
            <p class="page-subtitle">معلومات الدور: {{ $role->name }}</p>
        </div>
        <div class="flex space-x-3 space-x-reverse">
            <a href="{{ route('roles.edit', $role) }}" class="btn-primary">
                <svg class="w-5 h-5" fill="none" stroke="currentColor" viewBox="0 0 24 24">
                    <path stroke-linecap="round" stroke-linejoin="round" stroke-width="2" d="M11 5H6a2 2 0 00-2 2v11a2 2 0 002 2h11a2 2 0 002-2v-5m-1.414-9.414a2 2 0 112.828 2.828L11.828 15H9v-2.828l8.586-8.586z"></path>
                </svg>
                تعديل
            </a>
            <a href="{{ route('roles.index') }}" class="btn-secondary">
                <svg class="w-5 h-5" fill="none" stroke="currentColor" viewBox="0 0 24 24">
                    <path stroke-linecap="round" stroke-linejoin="round" stroke-width="2" d="M10 19l-7-7m0 0l7-7m-7 7h18"></path>
                </svg>
                العودة
            </a>
        </div>
    </div>

    <div class="bg-white shadow-sm rounded-lg p-6">
        <div class="grid grid-cols-1 md:grid-cols-2 gap-6">
            <div>
                <h3 class="text-lg font-medium text-gray-900 mb-4">معلومات الدور</h3>
                <dl class="space-y-3">
                    <div>
                        <dt class="text-sm font-medium text-gray-500">اسم الدور</dt>
                        <dd class="text-sm text-gray-900">{{ $role->name }}</dd>
                    </div>
                    <div>
                        <dt class="text-sm font-medium text-gray-500">تاريخ الإنشاء</dt>
                        <dd class="text-sm text-gray-900">{{ $role->created_at->format('Y-m-d H:i:s') }}</dd>
                    </div>
                    <div>
                        <dt class="text-sm font-medium text-gray-500">تاريخ آخر تحديث</dt>
                        <dd class="text-sm text-gray-900">{{ $role->updated_at->format('Y-m-d H:i:s') }}</dd>
                    </div>
                </dl>
            </div>

			<div>
				<h3 class="text-lg font-medium text-gray-900 mb-4">الصلاحيات</h3>
				@if($role->permissions->count() > 0)
					@php
					
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
					@endphp
					<div class="space-y-2">
						@foreach($grouped as $module => $actions)
							<div class="flex items-center flex-wrap border-b pb-2 mb-2">
								<div class="font-semibold text-gray-800 ml-2">{{ $module }}:</div>
								@foreach($actions as $action)
									@php
										$colorClass = match ($action) {
											'عرض' => 'bg-blue-100 text-blue-700 border-blue-300',
											'إضافة' => 'bg-green-100 text-green-700 border-green-300',
											'تعديل' => 'bg-yellow-100 text-yellow-700 border-yellow-300',
											'حذف' => 'bg-red-100 text-red-700 border-red-300',
											default => 'bg-gray-100 text-gray-700 border-gray-300',
										};
									@endphp
									<span class="inline-flex items-center px-2 py-1 rounded-full text-xs font-medium border {{ $colorClass }} mr-1">
										{{ $action }}
									</span>
								@endforeach
							</div>
						@endforeach
					</div>
				@else
					<p class="text-gray-500">لا توجد صلاحيات مرتبطة بهذا الدور</p>
				@endif
			</div>

        </div>
    </div>
</x-app-layout>
