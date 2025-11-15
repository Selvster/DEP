<x-app-layout>
    <div class="mb-6">
        <div class="flex justify-between items-center">
            <div>
                <h1 class="text-2xl font-bold text-gray-900">تفاصيل نوع الطلب: {{ $requestType->name }}</h1>
                <p class="text-gray-600 mt-1">عرض معلومات نوع الطلب</p>
            </div>
            <div class="flex gap-2">
                @can('edit_request_type')
                <a href="{{ route('request-types.edit', $requestType) }}" class="bg-blue-600 hover:bg-blue-700 text-white font-medium py-2 px-4 rounded-lg transition-colors duration-200">
                    <svg class="w-5 h-5 inline-block ml-1" fill="none" stroke="currentColor" viewBox="0 0 24 24">
                        <path stroke-linecap="round" stroke-linejoin="round" stroke-width="2" d="M11 5H6a2 2 0 00-2 2v11a2 2 0 002 2h11a2 2 0 002-2v-5m-1.414-9.414a2 2 0 112.828 2.828L11.828 15H9v-2.828l8.586-8.586z"></path>
                    </svg>
                    تعديل
                </a>
                @endcan
                <a href="{{ route('request-types.index') }}" class="bg-gray-300 hover:bg-gray-400 text-gray-800 font-medium py-2 px-4 rounded-lg transition-colors duration-200">
                    رجوع
                </a>
            </div>
        </div>
    </div>

    <div class="grid grid-cols-1 lg:grid-cols-3 gap-6">
        <!-- Main Information -->
        <div class="lg:col-span-2">
            <div class="bg-white overflow-hidden shadow-sm rounded-lg">
                <div class="p-6">
                    <h2 class="text-lg font-semibold text-gray-900 mb-4">معلومات نوع الطلب</h2>
                    <div class="space-y-4">
                        <div>
                            <label class="block text-sm font-medium text-gray-500">اسم النوع</label>
                            <p class="mt-1 text-gray-900 text-lg">{{ $requestType->name }}</p>
                        </div>
                        <div>
                            <label class="block text-sm font-medium text-gray-500">الحالة</label>
                            <p class="mt-1">
                                @if($requestType->is_active)
                                <span class="px-3 py-1 text-sm font-semibold rounded-full bg-green-100 text-green-800">نشط</span>
                                @else
                                <span class="px-3 py-1 text-sm font-semibold rounded-full bg-red-100 text-red-800">غير نشط</span>
                                @endif
                            </p>
                        </div>
                        <div>
                            <label class="block text-sm font-medium text-gray-500">أنشئ بواسطة</label>
                            <p class="mt-1 text-gray-900">{{ $requestType->creator ? $requestType->creator->name : 'غير محدد' }}</p>
                        </div>
                        <div class="grid grid-cols-2 gap-4">
                            <div>
                                <label class="block text-sm font-medium text-gray-500">تاريخ الإنشاء</label>
                                <p class="mt-1 text-gray-900">{{ $requestType->created_at->format('Y-m-d H:i') }}</p>
                            </div>
                            <div>
                                <label class="block text-sm font-medium text-gray-500">آخر تحديث</label>
                                <p class="mt-1 text-gray-900">{{ $requestType->updated_at->format('Y-m-d H:i') }}</p>
                            </div>
                        </div>
                    </div>
                </div>
            </div>
        </div>

        <!-- Statistics -->
        <div>
            <div class="bg-white overflow-hidden shadow-sm rounded-lg">
                <div class="p-6">
                    <h2 class="text-lg font-semibold text-gray-900 mb-4">الإحصائيات</h2>
                    <div class="space-y-3">
                        <div class="flex justify-between items-center p-3 bg-blue-50 rounded-lg">
                            <span class="text-sm text-gray-600">عدد الطلبات</span>
                            <span class="text-xl font-bold text-blue-600">{{ $requestType->requests()->count() }}</span>
                        </div>
                    </div>
                </div>
            </div>
        </div>
    </div>
</x-app-layout>

