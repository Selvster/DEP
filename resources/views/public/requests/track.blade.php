<!DOCTYPE html>
<html lang="ar" dir="rtl">
<head>
    <meta charset="UTF-8">
    <meta name="viewport" content="width=device-width, initial-scale=1.0">
    <title>تتبع الطلب</title>
    <script src="https://cdn.tailwindcss.com"></script>
</head>
<body class="bg-gray-50">
    <div class="min-h-screen flex items-center justify-center py-12 px-4">
        <div class="max-w-md w-full">
            <div class="text-center mb-8">
                <h1 class="text-3xl font-bold text-gray-900">تتبع طلبك</h1>
                <p class="text-gray-600 mt-2">أدخل رقم التتبع أو الرقم القومي أو رقم الهاتف</p>
            </div>

            @if(session('error'))
            <div class="bg-red-100 border border-red-400 text-red-700 px-4 py-3 rounded mb-4">
                {{ session('error') }}
            </div>
            @endif

            <div class="bg-white shadow-lg rounded-lg p-8">
                <form action="{{ route('public.requests.track-result') }}" method="POST">
                    @csrf
                    
                    <div class="mb-6">
                        <label class="block text-sm font-medium text-gray-700 mb-2">رقم التتبع / الرقم القومي / رقم الهاتف</label>
                        <input type="text" name="search" value="{{ old('search') }}" required
                            class="w-full px-4 py-3 border border-gray-300 rounded-lg focus:ring-2 focus:ring-blue-500 text-lg"
                            placeholder="أدخل رقم التتبع أو الرقم القومي أو رقم الهاتف">
                        @error('search')<p class="text-red-500 text-xs mt-1">{{ $message }}</p>@enderror
                    </div>

                    <button type="submit" class="w-full bg-blue-600 hover:bg-blue-700 text-white font-medium py-3 px-6 rounded-lg transition">
                        بحث
                    </button>
                </form>

                <div class="mt-6 pt-6 border-t border-gray-200 text-center">
                    <a href="{{ route('public.requests.create') }}" class="text-blue-600 hover:underline">
                        تقديم طلب جديد
                    </a>
                </div>
            </div>
        </div>
    </div>
</body>
</html>

