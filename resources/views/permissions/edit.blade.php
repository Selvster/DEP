<x-app-layout>
    <div class="page-header">
        <div>
            <h1 class="page-title">تعديل الصلاحية</h1>
            <p class="page-subtitle">تعديل بيانات الصلاحية: {{ $permission->name }}</p>
        </div>
    </div>

    <div class="bg-white shadow-sm rounded-lg p-6">
        <form action="{{ route('permissions.update', $permission) }}" method="POST">
            @csrf
            @method('PUT')
            
            <div class="mb-6">
                <label for="name" class="block text-sm font-medium text-gray-700 mb-2">
                    اسم الصلاحية <span class="text-red-500">*</span>
                </label>
                <input type="text" 
                       id="name" 
                       name="name" 
                       value="{{ old('name', $permission->name) }}"
                       class="w-full px-3 py-2 border border-gray-300 rounded-md shadow-sm focus:outline-none focus:ring-blue-500 focus:border-blue-500 @error('name') border-red-500 @enderror"
                       placeholder="أدخل اسم الصلاحية"
                       required>
                @error('name')
                    <p class="mt-1 text-sm text-red-600">{{ $message }}</p>
                @enderror
            </div>

            <div class="mt-6 flex justify-end space-x-3 space-x-reverse">
                <a href="{{ route('permissions.index') }}"
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
