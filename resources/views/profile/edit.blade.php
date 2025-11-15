<x-app-layout>
    <div class="mb-6">
        <h1 class="text-2xl font-bold text-gray-900">الملف الشخصي</h1>
        <p class="text-gray-600 mt-1">إدارة معلومات حسابك الشخصي</p>
    </div>

    <div class="space-y-6">
            <div class="p-4 sm:p-8 bg-white shadow sm:rounded-lg">
                <div class="max-w-xl">
                    @include('profile.partials.update-profile-information-form')
                </div>
            </div>

            <div class="p-4 sm:p-8 bg-white shadow sm:rounded-lg">
                <div class="max-w-xl">
                    @include('profile.partials.update-password-form')
                </div>
            </div>
        </div>
    </div>
</x-app-layout>
