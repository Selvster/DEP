<x-app-layout>
    <div class="mb-6">
        <div class="flex justify-between items-center">
            <div>
                <h1 class="text-2xl font-bold text-gray-900">الطلبات</h1>
                <p class="text-gray-600 mt-1">إدارة جميع الطلبات المقدمة</p>
            </div>
            @can('create_request')
            <a href="{{ route('requests.create') }}" class="bg-blue-600 hover:bg-blue-700 text-white font-medium py-2 px-4 rounded-lg transition-colors duration-200">
                <svg class="w-5 h-5 inline-block ml-1" fill="none" stroke="currentColor" viewBox="0 0 24 24">
                    <path stroke-linecap="round" stroke-linejoin="round" stroke-width="2" d="M12 4v16m8-8H4"></path>
                </svg>
                إضافة طلب جديد
            </a>
            @endcan
        </div>
    </div>

    @if(session('success'))
    <div class="bg-green-100 border border-green-400 text-green-700 px-4 py-3 rounded relative mb-4" role="alert">
        <span class="block sm:inline">{{ session('success') }}</span>
    </div>
    @endif

    <div class="bg-white overflow-hidden shadow-sm rounded-lg">
        <div class="p-6">
            <table id="requests-table" class="w-full">
                <thead>
                    <tr>
                        <th class="text-right">رقم التتبع</th>
                        <th class="text-right">الاسم</th>
                        <th class="text-right">المركز</th>
                        <th class="text-right">نوع الطلب</th>
                        <th class="text-right">الحالة</th>
                        <th class="text-right">تاريخ التقديم</th>
                        <th class="text-right">الإجراءات</th>
                    </tr>
                </thead>
            </table>
        </div>
    </div>

    @push('scripts')
    <script>
        $(document).ready(function() {
            $('#requests-table').DataTable({
                processing: true,
                serverSide: true,
                ajax: '{{ route('requests.index') }}',
                columns: [
                    { data: 'tracking_number', name: 'tracking_number' },
                    { data: 'name', name: 'name' },
                    { data: 'center_name', name: 'center_name' },
                    { data: 'request_type_name', name: 'request_type_name' },
                    { data: 'status_name', name: 'status_name' },
                    { data: 'created_at_formatted', name: 'created_at' },
                    { data: 'actions', name: 'actions', orderable: false, searchable: false }
                ],
                language: {
                    url: '//cdn.datatables.net/plug-ins/1.13.6/i18n/ar.json'
                },
                dom: '<"flex justify-between items-center mb-4"lf>rtip',
                pageLength: 10,
                lengthMenu: [[10, 25, 50, -1], [10, 25, 50, "الكل"]],
                order: [[5, 'desc']]
            });
        });
    </script>
    @endpush
</x-app-layout>

