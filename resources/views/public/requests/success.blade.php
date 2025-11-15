<!DOCTYPE html>
<html lang="ar" dir="rtl">
<head>
    <meta charset="UTF-8">
    <meta name="viewport" content="width=device-width, initial-scale=1.0">
    <title>تم تقديم الطلب بنجاح</title>
    <script src="https://cdn.tailwindcss.com"></script>
</head>
<body class="bg-gray-50">
    <div class="min-h-screen flex items-center justify-center py-12 px-4">
        <div class="max-w-md w-full">
            <div class="bg-white shadow-lg rounded-lg p-8 text-center">
                <div class="mb-4">
                    <svg class="w-16 h-16 text-green-500 mx-auto" fill="none" stroke="currentColor" viewBox="0 0 24 24">
                        <path stroke-linecap="round" stroke-linejoin="round" stroke-width="2" d="M9 12l2 2 4-4m6 2a9 9 0 11-18 0 9 9 0 0118 0z"></path>
                    </svg>
                </div>
                
                <h1 class="text-2xl font-bold text-gray-900 mb-2">تم تقديم طلبك بنجاح!</h1>
                <p class="text-gray-600 mb-6">يمكنك تتبع حالة طلبك باستخدام رقم التتبع التالي:</p>
                
                <div class="bg-blue-50 border-2 border-blue-200 rounded-lg p-4 mb-6">
                    <p class="text-sm text-gray-600 mb-1">رقم التتبع</p>
                    <p class="text-2xl font-bold text-blue-600">{{ $request->tracking_number }}</p>
                </div>

                <p class="text-sm text-gray-600 mb-6">
                    احتفظ بهذا الرقم لمتابعة حالة طلبك
                </p>

                <div class="space-y-3">
                    <a href="{{ route('public.requests.track') }}" class="block w-full bg-blue-600 hover:bg-blue-700 text-white font-medium py-3 px-6 rounded-lg transition">
                        تتبع الطلب
                    </a>
                    <a href="{{ route('public.requests.create') }}" class="block w-full bg-gray-200 hover:bg-gray-300 text-gray-800 font-medium py-3 px-6 rounded-lg transition">
                        تقديم طلب جديد
                    </a>
                </div>
            </div>
        </div>
    </div>
</body>
</html>

