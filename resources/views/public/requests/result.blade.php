<!DOCTYPE html>
<html lang="ar" dir="rtl">
<head>
    <meta charset="UTF-8">
    <meta name="viewport" content="width=device-width, initial-scale=1.0">
    <title>نتيجة البحث - {{ $req->tracking_number }}</title>
    <script src="https://cdn.tailwindcss.com"></script>
</head>
<body class="bg-gray-50">
    <div class="min-h-screen py-12 px-4">
        <div class="max-w-3xl mx-auto">
            <div class="bg-white shadow-lg rounded-lg overflow-hidden">
                <div class="bg-blue-600 text-white px-6 py-4">
                    <h1 class="text-2xl font-bold">معلومات الطلب</h1>
                </div>

                <div class="p-6">
                    <div class="grid grid-cols-1 md:grid-cols-2 gap-6 mb-6">
                        <div>
                            <label class="block text-sm font-medium text-gray-600">رقم التتبع</label>
                            <p class="text-lg font-bold text-blue-600">{{ $req->tracking_number }}</p>
                        </div>

                        <div>
                            <label class="block text-sm font-medium text-gray-600">الحالة</label>
                            <div class="inline-block px-3 py-1 rounded-full text-sm font-semibold mt-1"
                                 style="background-color: {{ $req->status->color }}20; color: {{ $req->status->color }}">
                                {{ $req->status->name }}
                            </div>
                        </div>

                        <div>
                            <label class="block text-sm font-medium text-gray-600">الاسم</label>
                            <p class="text-lg">{{ $req->name }}</p>
                        </div>

                        <div>
                            <label class="block text-sm font-medium text-gray-600">المركز</label>
                            <p class="text-lg">{{ $req->center->name }}</p>
                        </div>

                        <div>
                            <label class="block text-sm font-medium text-gray-600">نوع الطلب</label>
                            <p class="text-lg">{{ $req->requestType->name }}</p>
                        </div>

                        <div>
                            <label class="block text-sm font-medium text-gray-600">تاريخ التقديم</label>
                            <p class="text-lg">{{ $req->created_at->format('Y-m-d') }}</p>
                        </div>

                        <div class="col-span-2">
                            <label class="block text-sm font-medium text-gray-600">آخر تحديث</label>
                            <p class="text-lg">{{ $req->updated_at->diffForHumans(['locale' => 'ar']) }}</p>
                        </div>
                    </div>

                    @if($req->rejection_reason)
                    <div class="bg-red-50 border border-red-200 rounded-lg p-4 mb-6">
                        <h3 class="font-semibold text-red-900 mb-2">سبب الرفض</h3>
                        <p class="text-red-800">{{ $req->rejection_reason }}</p>
                    </div>
                    @endif

                    @if($req->response_document)
                    <div class="bg-green-50 border border-green-200 rounded-lg p-4 mb-6">
                        <h3 class="font-semibold text-green-900 mb-2">يتوفر رد على طلبك</h3>
                        <a href="{{ route('public.requests.download-response', $req->tracking_number) }}" 
                           class="inline-block bg-green-600 hover:bg-green-700 text-white font-medium py-2 px-4 rounded-lg transition">
                            تحميل الرد
                        </a>
                    </div>
                    @endif

                    <div class="pt-6 border-t border-gray-200 text-center space-x-3 space-x-reverse">
                        <a href="{{ route('public.requests.track') }}" class="inline-block bg-gray-200 hover:bg-gray-300 text-gray-800 font-medium py-2 px-6 rounded-lg transition">
                            بحث عن طلب آخر
                        </a>
                        <a href="{{ route('public.requests.create') }}" class="inline-block bg-blue-600 hover:bg-blue-700 text-white font-medium py-2 px-6 rounded-lg transition">
                            تقديم طلب جديد
                        </a>
                    </div>
                </div>
            </div>
        </div>
    </div>
</body>
</html>

