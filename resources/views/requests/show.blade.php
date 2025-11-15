<x-app-layout>
    <div class="mb-6">
        <div class="flex justify-between items-center">
            <div>
                <h1 class="text-2xl font-bold text-gray-900">تفاصيل الطلب #{{ $req->tracking_number }}</h1>
                <p class="text-gray-600 mt-1">عرض معلومات الطلب</p>
            </div>
            <div class="flex gap-2">
                @can('edit_request')
                <a href="{{ route('requests.edit', $req) }}" class="bg-blue-600 hover:bg-blue-700 text-white font-medium py-2 px-4 rounded-lg transition-colors duration-200">
                    <svg class="w-5 h-5 inline-block ml-1" fill="none" stroke="currentColor" viewBox="0 0 24 24">
                        <path stroke-linecap="round" stroke-linejoin="round" stroke-width="2" d="M11 5H6a2 2 0 00-2 2v11a2 2 0 002 2h11a2 2 0 002-2v-5m-1.414-9.414a2 2 0 112.828 2.828L11.828 15H9v-2.828l8.586-8.586z"></path>
                    </svg>
                    تعديل
                </a>
                @endcan
                <a href="{{ route('requests.index') }}" class="bg-gray-300 hover:bg-gray-400 text-gray-800 font-medium py-2 px-4 rounded-lg transition-colors duration-200">
                    رجوع
                </a>
            </div>
        </div>
    </div>

    <div class="grid grid-cols-1 lg:grid-cols-3 gap-6">
        <!-- Main Information -->
        <div class="lg:col-span-2 space-y-6">
            <!-- Citizen Information -->
            <div class="bg-white overflow-hidden shadow-sm rounded-lg">
                <div class="p-6">
                    <h2 class="text-lg font-semibold text-gray-900 mb-4">معلومات المواطن</h2>
                    <div class="grid grid-cols-1 md:grid-cols-2 gap-4">
                        <div>
                            <label class="block text-sm font-medium text-gray-500">الاسم</label>
                            <p class="mt-1 text-gray-900">{{ $req->name }}</p>
                        </div>
                        <div>
                            <label class="block text-sm font-medium text-gray-500">الرقم القومي</label>
                            <p class="mt-1 text-gray-900 font-mono">{{ $req->national_id }}</p>
                        </div>
                        <div>
                            <label class="block text-sm font-medium text-gray-500">رقم الهاتف</label>
                            <p class="mt-1 text-gray-900 font-mono">{{ $req->phone }}</p>
                        </div>
                        <div>
                            <label class="block text-sm font-medium text-gray-500">البريد الإلكتروني</label>
                            <p class="mt-1 text-gray-900">{{ $req->email ?? 'غير محدد' }}</p>
                        </div>
                    </div>
                </div>
            </div>

            <!-- Request Details -->
            <div class="bg-white overflow-hidden shadow-sm rounded-lg">
                <div class="p-6">
                    <h2 class="text-lg font-semibold text-gray-900 mb-4">تفاصيل الطلب</h2>
                    <div class="space-y-4">
                        <div>
                            <label class="block text-sm font-medium text-gray-500">المركز</label>
                            <p class="mt-1 text-gray-900">{{ $req->center->name }}</p>
                        </div>
                        <div>
                            <label class="block text-sm font-medium text-gray-500">نوع الطلب</label>
                            <p class="mt-1 text-gray-900">{{ $req->requestType->name }}</p>
                        </div>
                        <div>
                            <label class="block text-sm font-medium text-gray-500">وصف الطلب</label>
                            <p class="mt-1 text-gray-900 whitespace-pre-line">{{ $req->description }}</p>
                        </div>
                        @if($req->rejection_reason)
                        <div class="border-r-4 border-red-500 bg-red-50 p-4 rounded">
                            <label class="block text-sm font-medium text-red-700">سبب الرفض</label>
                            <p class="mt-1 text-red-900 whitespace-pre-line">{{ $req->rejection_reason }}</p>
                        </div>
                        @endif
                    </div>
                </div>
            </div>

            <!-- Documents -->
            @if($req->documents && count($req->documents) > 0)
            <div class="bg-white overflow-hidden shadow-sm rounded-lg">
                <div class="p-6">
                    <h2 class="text-lg font-semibold text-gray-900 mb-4">المستندات المرفقة</h2>
                    <div class="grid grid-cols-2 md:grid-cols-3 gap-4">
                        @foreach($req->documents as $document)
                        <a href="{{ Storage::url($document) }}" target="_blank" class="border border-gray-300 rounded-lg p-4 hover:bg-gray-50 transition-colors">
                            <div class="flex flex-col items-center">
                                @php
                                    $extension = strtolower(pathinfo($document, PATHINFO_EXTENSION));
                                @endphp
                                @if(in_array($extension, ['jpg', 'jpeg', 'png', 'gif', 'webp']))
                                <img src="{{ Storage::url($document) }}" alt="Document" class="w-full h-32 object-cover rounded mb-2">
                                @else
                                <svg class="w-16 h-16 text-gray-400 mb-2" fill="none" stroke="currentColor" viewBox="0 0 24 24">
                                    <path stroke-linecap="round" stroke-linejoin="round" stroke-width="2" d="M7 21h10a2 2 0 002-2V9.414a1 1 0 00-.293-.707l-5.414-5.414A1 1 0 0012.586 3H7a2 2 0 00-2 2v14a2 2 0 002 2z"></path>
                                </svg>
                                @endif
                                <p class="text-xs text-gray-600 text-center truncate w-full">{{ basename($document) }}</p>
                            </div>
                        </a>
                        @endforeach
                    </div>
                </div>
            </div>
            @endif

            <!-- Response Document -->
            @if($req->response_document)
            <div class="bg-white overflow-hidden shadow-sm rounded-lg">
                <div class="p-6">
                    <h2 class="text-lg font-semibold text-gray-900 mb-4">مستند الرد</h2>
                    <a href="{{ Storage::url($req->response_document) }}" target="_blank" class="inline-flex items-center px-4 py-2 bg-green-600 hover:bg-green-700 text-white rounded-lg transition-colors">
                        <svg class="w-5 h-5 ml-2" fill="none" stroke="currentColor" viewBox="0 0 24 24">
                            <path stroke-linecap="round" stroke-linejoin="round" stroke-width="2" d="M12 10v6m0 0l-3-3m3 3l3-3m2 8H7a2 2 0 01-2-2V5a2 2 0 012-2h5.586a1 1 0 01.707.293l5.414 5.414a1 1 0 01.293.707V19a2 2 0 01-2 2z"></path>
                        </svg>
                        تحميل مستند الرد
                    </a>
                </div>
            </div>
            @endif
        </div>

        <!-- Sidebar -->
        <div class="space-y-6">
            <!-- Status Card -->
            <div class="bg-white overflow-hidden shadow-sm rounded-lg">
                <div class="p-6">
                    <h2 class="text-lg font-semibold text-gray-900 mb-4">حالة الطلب</h2>
                    <div class="flex items-center mb-4">
                        <div class="w-4 h-4 rounded-full ml-2" style="background-color: {{ $req->status->color }}"></div>
                        <span class="text-lg font-medium">{{ $req->status->name }}</span>
                    </div>
                    <div class="text-sm text-gray-500">
                        <p>آخر تحديث:</p>
                        <p class="font-medium text-gray-900">{{ $req->updated_at->locale('ar')->diffForHumans() }}</p>
                    </div>
                </div>
            </div>

            <!-- Tracking Info -->
            <div class="bg-white overflow-hidden shadow-sm rounded-lg">
                <div class="p-6">
                    <h2 class="text-lg font-semibold text-gray-900 mb-4">معلومات التتبع</h2>
                    <div class="space-y-3">
                        <div>
                            <label class="block text-sm font-medium text-gray-500">رقم التتبع</label>
                            <p class="mt-1 text-gray-900 font-mono font-bold text-lg">{{ $req->tracking_number }}</p>
                        </div>
                        <div>
                            <label class="block text-sm font-medium text-gray-500">تاريخ التقديم</label>
                            <p class="mt-1 text-gray-900">{{ $req->created_at->format('Y-m-d H:i') }}</p>
                        </div>
                        @if($req->creator)
                        <div>
                            <label class="block text-sm font-medium text-gray-500">أنشئ بواسطة</label>
                            <p class="mt-1 text-gray-900">{{ $req->creator->name }}</p>
                        </div>
                        @endif
                    </div>
                </div>
            </div>

            <!-- Public Tracking Link -->
            <div class="bg-blue-50 overflow-hidden shadow-sm rounded-lg border border-blue-200">
                <div class="p-6">
                    <h2 class="text-sm font-semibold text-blue-900 mb-2">رابط التتبع العام</h2>
                    <a href="{{ route('public.requests.track') }}?tracking_number={{ $req->tracking_number }}" target="_blank" class="text-xs text-blue-600 hover:text-blue-800 break-all">
                        {{ route('public.requests.track') }}?tracking_number={{ $req->tracking_number }}
                    </a>
                </div>
            </div>
        </div>
    </div>
</x-app-layout>

