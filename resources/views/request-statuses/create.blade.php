<x-app-layout>
    <div class="mb-6">
        <h1 class="text-2xl font-bold text-gray-900">إضافة حالة طلب جديدة</h1>
    </div>

    <div class="bg-white overflow-hidden shadow-sm rounded-lg">
        <div class="p-6">
            <form action="{{ route('request-statuses.store') }}" method="POST">
                @csrf
                
                <div class="mb-4">
                    <label for="name" class="block text-sm font-medium text-gray-700 mb-2">اسم الحالة</label>
                    <input type="text" name="name" id="name" value="{{ old('name') }}" required
                        class="w-full px-3 py-2 border border-gray-300 rounded-md focus:outline-none focus:ring-2 focus:ring-blue-500 @error('name') border-red-500 @enderror">
                    @error('name')
                        <p class="text-red-500 text-xs mt-1">{{ $message }}</p>
                    @enderror
                </div>

                <div class="mb-4">
                    <label for="color" class="block text-sm font-medium text-gray-700 mb-2">اللون</label>
                    <input type="color" name="color" id="color" value="{{ old('color', '#3B82F6') }}" required
                        class="w-32 h-10 border border-gray-300 rounded-md cursor-pointer @error('color') border-red-500 @enderror">
                    @error('color')
                        <p class="text-red-500 text-xs mt-1">{{ $message }}</p>
                    @enderror
                </div>

                <div class="mb-4">
                    <label class="flex items-center">
                        <input type="checkbox" name="is_active" value="1" checked class="rounded border-gray-300 text-blue-600 shadow-sm focus:border-blue-300 focus:ring focus:ring-blue-200 focus:ring-opacity-50">
                        <span class="mr-2 text-sm text-gray-700">نشط</span>
                    </label>
                </div>

                <div class="flex justify-end gap-2">
                    <a href="{{ route('request-statuses.index') }}" class="bg-gray-300 hover:bg-gray-400 text-gray-800 font-medium py-2 px-4 rounded-lg">
                        إلغاء
                    </a>
                    <button type="submit" class="bg-blue-600 hover:bg-blue-700 text-white font-medium py-2 px-4 rounded-lg">
                        حفظ
                    </button>
                </div>
            </form>
        </div>
    </div>
</x-app-layout>
