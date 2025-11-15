<x-app-layout>
    <div class="page-header">
        <div>
            <h1 class="page-title">الأدوار</h1>
            <p class="page-subtitle">إدارة أدوار المستخدمين في النظام</p>
        </div>
        <a href="{{ route('roles.create') }}" class="btn-primary">
            <svg class="w-5 h-5" fill="none" stroke="currentColor" viewBox="0 0 24 24">
                <path stroke-linecap="round" stroke-linejoin="round" stroke-width="2" d="M12 6v6m0 0v6m0-6h6m-6 0H6"></path>
            </svg>
            إضافة دور جديد
        </a>
    </div>

    @if(session('success'))
        <div class="mb-4 bg-green-100 border border-green-400 text-green-700 px-4 py-3 rounded-lg">
            {{ session('success') }}
        </div>
    @endif

    @if(session('error'))
        <div class="mb-4 bg-red-100 border border-red-400 text-red-700 px-4 py-3 rounded-lg">
            {{ session('error') }}
        </div>
    @endif

    <table id="roles-table" class="table">
        <thead>
            <tr>
                <th>اسم الدور</th>
                <th>الصلاحيات</th>
                <th>تاريخ الإنشاء</th>
                <th>الإجراءات</th>
            </tr>
        </thead>
    </table>

    <!-- Custom Confirmation Modal -->
    <div id="customModal" class="custom-modal" style="display: none;">
        <div class="custom-modal-overlay" onclick="closeCustomModal()"></div>
        <div class="custom-modal-content" dir="rtl">
            <div class="custom-modal-header">
                <h5 class="custom-modal-title" id="modalTitle">تأكيد الحذف</h5>
                <button type="button" class="custom-modal-close" onclick="closeCustomModal()">&times;</button>
            </div>
            <div class="custom-modal-body">
                <div class="custom-modal-icon">
                    <svg width="48" height="48" fill="none" stroke="currentColor" viewBox="0 0 24 24">
                        <path stroke-linecap="round" stroke-linejoin="round" stroke-width="2" d="M12 9v2m0 4h.01m-6.938 4h13.856c1.54 0 2.502-1.667 1.732-2.5L13.732 4c-.77-.833-1.964-.833-2.732 0L3.732 16c-.77.833.192 2.5 1.732 2.5z"></path>
                    </svg>
                </div>
                <p id="modalMessage">هل أنت متأكد من الحذف؟</p>
            </div>
            <div class="custom-modal-footer">
                <button type="button" class="custom-btn custom-btn-secondary" onclick="closeCustomModal()">إلغاء</button>
                <button type="button" class="custom-btn custom-btn-danger" id="confirmDeleteBtn">حذف</button>
            </div>
        </div>
    </div>

    <script>
        $(document).ready(function() {
            console.log('Document ready - initializing roles DataTable...');
            
            $('#roles-table').DataTable({
                ...window.datatableConfig,
                processing: true,
                serverSide: true,
                ajax: {
                    url: "{{ route('roles.index') }}",
                    type: 'GET',
                    headers: {
                        'X-Requested-With': 'XMLHttpRequest'
                    },
                    error: function(xhr, error, thrown) {
                        console.error('DataTables AJAX Error:', error);
                        console.error('Response:', xhr.responseText);
                    }
                },
                columns: [
                    {data: 'name', name: 'name'},
                    {data: 'permissions', name: 'permissions', orderable: false, searchable: false},
                    {data: 'created_at_formatted', name: 'created_at'},
                    {data: 'actions', name: 'actions', orderable: false, searchable: false}
                ],
                order: [[2, 'desc']]
            });
        });
    </script>
</x-app-layout>
