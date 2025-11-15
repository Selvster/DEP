<x-app-layout>
    <div class="mb-6">
        <h1 class="text-2xl font-bold text-gray-900">تعديل الطلب #{{ $req->tracking_number }}</h1>
    </div>

    <div class="bg-white overflow-hidden shadow-sm rounded-lg">
        <div class="p-6">
            <form action="{{ route('requests.update', $req) }}" method="POST" enctype="multipart/form-data">
                @csrf
                @method('PUT')
                
                <div class="grid grid-cols-1 md:grid-cols-2 gap-6 mb-6">
                    <!-- Name -->
                    <div>
                        <label for="name" class="block text-sm font-medium text-gray-700 mb-2">الاسم <span class="text-red-500">*</span></label>
                        <input type="text" name="name" id="name" value="{{ old('name', $req->name) }}" required
                            class="w-full px-3 py-2 border border-gray-300 rounded-md focus:outline-none focus:ring-2 focus:ring-blue-500 @error('name') border-red-500 @enderror">
                        @error('name')
                            <p class="text-red-500 text-xs mt-1">{{ $message }}</p>
                        @enderror
                    </div>

                    <!-- National ID -->
                    <div>
                        <label for="national_id" class="block text-sm font-medium text-gray-700 mb-2">الرقم القومي <span class="text-red-500">*</span></label>
                        <input type="text" name="national_id" id="national_id" value="{{ old('national_id', $req->national_id) }}" required maxlength="14"
                            class="w-full px-3 py-2 border border-gray-300 rounded-md focus:outline-none focus:ring-2 focus:ring-blue-500 @error('national_id') border-red-500 @enderror">
                        @error('national_id')
                            <p class="text-red-500 text-xs mt-1">{{ $message }}</p>
                        @enderror
                    </div>

                    <!-- Phone -->
                    <div>
                        <label for="phone" class="block text-sm font-medium text-gray-700 mb-2">رقم الهاتف <span class="text-red-500">*</span></label>
                        <input type="text" name="phone" id="phone" value="{{ old('phone', $req->phone) }}" required
                            class="w-full px-3 py-2 border border-gray-300 rounded-md focus:outline-none focus:ring-2 focus:ring-blue-500 @error('phone') border-red-500 @enderror">
                        @error('phone')
                            <p class="text-red-500 text-xs mt-1">{{ $message }}</p>
                        @enderror
                    </div>

                    <!-- Email -->
                    <div>
                        <label for="email" class="block text-sm font-medium text-gray-700 mb-2">البريد الإلكتروني</label>
                        <input type="email" name="email" id="email" value="{{ old('email', $req->email) }}"
                            class="w-full px-3 py-2 border border-gray-300 rounded-md focus:outline-none focus:ring-2 focus:ring-blue-500 @error('email') border-red-500 @enderror">
                        @error('email')
                            <p class="text-red-500 text-xs mt-1">{{ $message }}</p>
                        @enderror
                    </div>

                    <!-- Center -->
                    <div>
                        <label for="center_id" class="block text-sm font-medium text-gray-700 mb-2">المركز <span class="text-red-500">*</span></label>
                        <select name="center_id" id="center_id" required
                            class="w-full px-3 py-2 border border-gray-300 rounded-md focus:outline-none focus:ring-2 focus:ring-blue-500 @error('center_id') border-red-500 @enderror">
                            <option value="">اختر المركز</option>
                            @foreach($centers as $center)
                                <option value="{{ $center->id }}" {{ old('center_id', $req->center_id) == $center->id ? 'selected' : '' }}>
                                    {{ $center->name }}
                                </option>
                            @endforeach
                        </select>
                        @error('center_id')
                            <p class="text-red-500 text-xs mt-1">{{ $message }}</p>
                        @enderror
                    </div>

                    <!-- Request Type -->
                    <div>
                        <label for="request_type_id" class="block text-sm font-medium text-gray-700 mb-2">نوع الطلب <span class="text-red-500">*</span></label>
                        <select name="request_type_id" id="request_type_id" required
                            class="w-full px-3 py-2 border border-gray-300 rounded-md focus:outline-none focus:ring-2 focus:ring-blue-500 @error('request_type_id') border-red-500 @enderror">
                            <option value="">اختر نوع الطلب</option>
                            @foreach($requestTypes as $type)
                                <option value="{{ $type->id }}" {{ old('request_type_id', $req->request_type_id) == $type->id ? 'selected' : '' }}>
                                    {{ $type->name }}
                                </option>
                            @endforeach
                        </select>
                        @error('request_type_id')
                            <p class="text-red-500 text-xs mt-1">{{ $message }}</p>
                        @enderror
                    </div>

                    <!-- Status -->
                    <div>
                        <label for="status_id" class="block text-sm font-medium text-gray-700 mb-2">حالة الطلب <span class="text-red-500">*</span></label>
                        <select name="status_id" id="status_id" required
                            class="w-full px-3 py-2 border border-gray-300 rounded-md focus:outline-none focus:ring-2 focus:ring-blue-500 @error('status_id') border-red-500 @enderror">
                            <option value="">اختر الحالة</option>
                            @foreach($requestStatuses as $status)
                                <option value="{{ $status->id }}" {{ old('status_id', $req->status_id) == $status->id ? 'selected' : '' }}>
                                    {{ $status->name }}
                                </option>
                            @endforeach
                        </select>
                        @error('status_id')
                            <p class="text-red-500 text-xs mt-1">{{ $message }}</p>
                        @enderror
                    </div>
                </div>

                <!-- Description -->
                <div class="mb-6">
                    <label for="description" class="block text-sm font-medium text-gray-700 mb-2">وصف الطلب <span class="text-red-500">*</span></label>
                    <textarea name="description" id="description" rows="5" required
                        class="w-full px-3 py-2 border border-gray-300 rounded-md focus:outline-none focus:ring-2 focus:ring-blue-500 @error('description') border-red-500 @enderror">{{ old('description', $req->description) }}</textarea>
                    @error('description')
                        <p class="text-red-500 text-xs mt-1">{{ $message }}</p>
                    @enderror
                </div>

                <!-- Existing Documents -->
                @if($req->documents && count($req->documents) > 0)
                <div class="mb-6">
                    <label class="block text-sm font-medium text-gray-700 mb-2">المستندات الحالية</label>
                    <div class="grid grid-cols-2 md:grid-cols-4 gap-4">
                        @foreach($req->documents as $document)
                        <a href="{{ Storage::url($document) }}" target="_blank" class="border border-gray-300 rounded-lg p-3 hover:bg-gray-50 transition-colors">
                            <div class="flex flex-col items-center">
                                @php
                                    $extension = strtolower(pathinfo($document, PATHINFO_EXTENSION));
                                @endphp
                                @if(in_array($extension, ['jpg', 'jpeg', 'png', 'gif', 'webp']))
                                <img src="{{ Storage::url($document) }}" alt="Document" class="w-full h-20 object-cover rounded mb-2">
                                @else
                                <svg class="w-12 h-12 text-gray-400 mb-2" fill="none" stroke="currentColor" viewBox="0 0 24 24">
                                    <path stroke-linecap="round" stroke-linejoin="round" stroke-width="2" d="M7 21h10a2 2 0 002-2V9.414a1 1 0 00-.293-.707l-5.414-5.414A1 1 0 0012.586 3H7a2 2 0 00-2 2v14a2 2 0 002 2z"></path>
                                </svg>
                                @endif
                                <p class="text-xs text-gray-600 text-center truncate w-full">{{ basename($document) }}</p>
                            </div>
                        </a>
                        @endforeach
                    </div>
                </div>
                @endif

                <!-- Rejection Reason -->
                <div class="mb-6">
                    <label for="rejection_reason" class="block text-sm font-medium text-gray-700 mb-2">سبب الرفض (إن وجد)</label>
                    <textarea name="rejection_reason" id="rejection_reason" rows="3"
                        class="w-full px-3 py-2 border border-gray-300 rounded-md focus:outline-none focus:ring-2 focus:ring-blue-500 @error('rejection_reason') border-red-500 @enderror">{{ old('rejection_reason', $req->rejection_reason) }}</textarea>
                    @error('rejection_reason')
                        <p class="text-red-500 text-xs mt-1">{{ $message }}</p>
                    @enderror
                </div>

                <!-- Response Document -->
                <div class="mb-6">
                    <label for="response_document" class="block text-sm font-medium text-gray-700 mb-2">مستند الرد</label>
                    @if($req->response_document)
                    <div class="mb-2">
                        <a href="{{ Storage::url($req->response_document) }}" target="_blank" class="inline-flex items-center text-blue-600 hover:text-blue-800">
                            <svg class="w-5 h-5 ml-1" fill="none" stroke="currentColor" viewBox="0 0 24 24">
                                <path stroke-linecap="round" stroke-linejoin="round" stroke-width="2" d="M12 10v6m0 0l-3-3m3 3l3-3m2 8H7a2 2 0 01-2-2V5a2 2 0 012-2h5.586a1 1 0 01.707.293l5.414 5.414a1 1 0 01.293.707V19a2 2 0 01-2 2z"></path>
                            </svg>
                            عرض المستند الحالي
                        </a>
                    </div>
                    @endif
                    <input type="file" name="response_document" id="response_document" accept=".pdf,.jpg,.jpeg,.png"
                        class="w-full px-3 py-2 border border-gray-300 rounded-md focus:outline-none focus:ring-2 focus:ring-blue-500 @error('response_document') border-red-500 @enderror">
                    <p class="text-xs text-gray-500 mt-1">يمكنك رفع ملف PDF أو صورة (الحد الأقصى: 5 ميجابايت)</p>
                    @error('response_document')
                        <p class="text-red-500 text-xs mt-1">{{ $message }}</p>
                    @enderror
                </div>

                <!-- Buttons -->
                <div class="flex justify-end gap-2">
                    <a href="{{ route('requests.show', $req) }}" class="bg-gray-300 hover:bg-gray-400 text-gray-800 font-medium py-2 px-4 rounded-lg">
                        إلغاء
                    </a>
                    <button type="submit" class="bg-blue-600 hover:bg-blue-700 text-white font-medium py-2 px-4 rounded-lg">
                        تحديث الطلب
                    </button>
                </div>
            </form>
        </div>
    </div>
</x-app-layout>

