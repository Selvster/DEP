<x-app-layout>
    <div class="page-header">
        <div>
            <h1 class="page-title">تفاصيل الصلاحية</h1>
            <p class="page-subtitle">معلومات الصلاحية: {{ $permission->name }}</p>
        </div>
        <div class="flex space-x-3 space-x-reverse">
            <a href="{{ route('permissions.edit', $permission) }}" class="btn-primary">
                <svg class="w-5 h-5" fill="none" stroke="currentColor" viewBox="0 0 24 24">
                    <path stroke-linecap="round" stroke-linejoin="round" stroke-width="2" d="M11 5H6a2 2 0 00-2 2v11a2 2 0 002 2h11a2 2 0 002-2v-5m-1.414-9.414a2 2 0 112.828 2.828L11.828 15H9v-2.828l8.586-8.586z"></path>
                </svg>
                تعديل
            </a>
            <a href="{{ route('permissions.index') }}" class="btn-secondary">
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
                <h3 class="text-lg font-medium text-gray-900 mb-4">معلومات الصلاحية</h3>
                <dl class="space-y-3">
                    <div>
                        <dt class="text-sm font-medium text-gray-500">اسم الصلاحية</dt>
                        <dd class="text-sm text-gray-900">{{ $permission->name }}</dd>
                    </div>
                    <div>
                        <dt class="text-sm font-medium text-gray-500">تاريخ الإنشاء</dt>
                        <dd class="text-sm text-gray-900">{{ $permission->created_at->format('Y-m-d H:i:s') }}</dd>
                    </div>
                    <div>
                        <dt class="text-sm font-medium text-gray-500">تاريخ آخر تحديث</dt>
                        <dd class="text-sm text-gray-900">{{ $permission->updated_at->format('Y-m-d H:i:s') }}</dd>
                    </div>
                </dl>
            </div>

            <div>
                <h3 class="text-lg font-medium text-gray-900 mb-4">الأدوار المرتبطة</h3>
                @if($permission->roles->count() > 0)
                    <div class="space-y-2">
                        @foreach($permission->roles as $role)
                            <span class="inline-flex items-center px-3 py-1 rounded-full text-sm font-medium {{ $role->name === 'super_admin' ? 'bg-purple-100 text-purple-800' : 'bg-gray-100 text-gray-800' }} mr-2 mb-2">
                                {{  $role->name }}
                            </span>
                        @endforeach
                    </div>
                @else
                    <p class="text-gray-500">لا توجد أدوار مرتبطة بهذه الصلاحية</p>
                @endif
            </div>
        </div>
    </div>
</x-app-layout>
