<x-app-layout>
    <div class="page-header">
        <div>
            <h1 class="page-title">سجل الأنشطة</h1>
            <p class="page-subtitle">تتبع جميع العمليات والأنشطة في النظام</p>
        </div>
        @role('super_admin')
        <div>
            <button onclick="showDeleteAllModal()" class="bg-red-600 hover:bg-red-700 text-white font-medium py-2 px-4 rounded-lg transition-colors duration-200">
                حذف الكل
            </button>
        </div>
        @endrole
    </div>

    @if(session('success'))
        <div class="mb-4 bg-green-100 border border-green-400 text-green-700 px-4 py-3 rounded-lg">
            {{ session('success') }}
        </div>
    @endif

    <table id="activities-table" class="table">
        <thead>
            <tr>
                <th>التاريخ والوقت</th>
                <th>المستخدم</th>
                <th>النشاط</th>
                <th>الوصف</th>
                <th>الجهة</th>
            </tr>
        </thead>
    </table>

    <!-- Details Modal -->
    <div id="detailsModal" class="custom-modal" style="display: none;">
        <div class="custom-modal-overlay" onclick="closeDetailsModal()"></div>
        <div class="custom-modal-content">
            <div class="custom-modal-header">
                <h3 class="custom-modal-title">تفاصيل إضافية</h3>
                <button onclick="closeDetailsModal()" class="custom-modal-close">
                    <svg class="w-5 h-5" fill="currentColor" viewBox="0 0 20 20">
                        <path fill-rule="evenodd" d="M4.293 4.293a1 1 0 011.414 0L10 8.586l4.293-4.293a1 1 0 111.414 1.414L11.414 10l4.293 4.293a1 1 0 01-1.414 1.414L10 11.414l-4.293 4.293a1 1 0 01-1.414-1.414L8.586 10 4.293 5.707a1 1 0 010-1.414z" clip-rule="evenodd"></path>
                    </svg>
                </button>
            </div>
            <div class="custom-modal-body">
                <div id="detailsContent">
                    <!-- Details will be loaded here -->
                </div>
            </div>
            <div class="custom-modal-footer">
                <button onclick="closeDetailsModal()" class="custom-btn custom-btn-secondary">إغلاق</button>
            </div>
        </div>
    </div>

    <!-- Delete All Modal -->
    <div id="deleteAllModal" class="custom-modal" style="display: none;">
        <div class="custom-modal-overlay" onclick="closeDeleteAllModal()"></div>
        <div class="custom-modal-content" dir="rtl">
            <div class="custom-modal-header">
                <h5 class="custom-modal-title">تأكيد الحذف</h5>
                <button type="button" class="custom-modal-close" onclick="closeDeleteAllModal()">&times;</button>
            </div>
            <div class="custom-modal-body">
                <div class="custom-modal-icon">
                    <svg width="48" height="48" fill="none" stroke="currentColor" viewBox="0 0 24 24">
                        <path stroke-linecap="round" stroke-linejoin="round" stroke-width="2" d="M12 9v2m0 4h.01m-6.938 4h13.856c1.54 0 2.502-1.667 1.732-2.5L13.732 4c-.77-.833-1.964-.833-2.732 0L3.732 16c-.77.833.192 2.5 1.732 2.5z"></path>
                    </svg>
                </div>
                <p id="deleteAllMessage">هل أنت متأكد من حذف جميع سجلات النشاط؟ لا يمكن التراجع عن هذا الإجراء.</p>
            </div>
            <div class="custom-modal-footer">
                <button type="button" class="custom-btn custom-btn-secondary" onclick="closeDeleteAllModal()">إلغاء</button>
                <button type="button" class="custom-btn custom-btn-danger" id="confirmDeleteAllBtn">حذف الكل</button>
            </div>
        </div>
    </div>

    <script>
        function showDetailsModal(activityId, propertiesStr) {
            let detailsHtml = '';
            try {
                const properties = JSON.parse(propertiesStr);
                
                // Extract important information
                detailsHtml += '<div class="space-y-3">';
                
                if (properties.action) {
                    detailsHtml += '<div><strong>نوع العملية:</strong> ' + getActionLabel(properties.action) + '</div>';
                }
                
                if (properties.individual_name) {
                    detailsHtml += '<div><strong>اسم المواطن:</strong> ' + properties.individual_name + '</div>';
                }
                
                if (properties.category_name) {
                    detailsHtml += '<div><strong>التصنيف:</strong> ' + properties.category_name + '</div>';
                }
                
                if (properties.national_id) {
                    detailsHtml += '<div><strong>رقم البطاقة:</strong> ' + properties.national_id + '</div>';
                }
                
                if (properties.job_name) {
                    detailsHtml += '<div><strong>الوظيفة:</strong> ' + properties.job_name + '</div>';
                }
                
                if (properties.address_name) {
                    detailsHtml += '<div><strong>العنوان:</strong> ' + properties.address_name + '</div>';
                }
                
                if (properties.ip_address) {
                    detailsHtml += '<div><strong>عنوان IP:</strong> ' + properties.ip_address + '</div>';
                }
                
                if (properties.user_name) {
                    detailsHtml += '<div><strong>اسم المستخدم:</strong> ' + properties.user_name + '</div>';
                }
                
                if (properties.user_email) {
                    detailsHtml += '<div><strong>البريد الإلكتروني:</strong> ' + properties.user_email + '</div>';
                }
                
                if (properties.delivery_company_name) {
                    detailsHtml += '<div><strong>اسم شركة الديليفري:</strong> ' + properties.delivery_company_name + '</div>';
                }
                
                // Request system properties
                if (properties.tracking_number) {
                    detailsHtml += '<div><strong>رقم التتبع:</strong> ' + properties.tracking_number + '</div>';
                }
                
                if (properties.requester_name) {
                    detailsHtml += '<div><strong>اسم مقدم الطلب:</strong> ' + properties.requester_name + '</div>';
                }
                
                if (properties.center_name) {
                    detailsHtml += '<div><strong>المركز:</strong> ' + properties.center_name + '</div>';
                }
                
                if (properties.request_type_name) {
                    detailsHtml += '<div><strong>نوع الطلب:</strong> ' + properties.request_type_name + '</div>';
                }
                
                if (properties.status_name) {
                    detailsHtml += '<div><strong>حالة الطلب:</strong> ' + properties.status_name + '</div>';
                }
                
                if (properties.note) {
                    detailsHtml += '<div><strong>محتوى الملاحظة:</strong></div>';
                    detailsHtml += '<div class="bg-blue-50 p-3 rounded text-sm border-l-4 border-blue-400">' + properties.note + '</div>';
                }
                
                if (properties.old_data && properties.new_data) {
                    const filteredOldData = filterTimestamps(properties.old_data);
                    const filteredNewData = filterTimestamps(properties.new_data);
                    
                    detailsHtml += '<div><strong>التغييرات:</strong></div>';
                    detailsHtml += '<div class="bg-gray-50 p-3 rounded text-sm">';
                    
                    // Create a combined list of all keys from both old and new data
                    const allKeys = new Set([...Object.keys(filteredOldData), ...Object.keys(filteredNewData)]);
                    
                    for (const key of allKeys) {
                        const oldValue = filteredOldData[key];
                        const newValue = filteredNewData[key];
                        
                        // Skip if values are the same or both undefined
                        if (oldValue === newValue) continue;
                        
                        detailsHtml += '<div class="mb-1">';
                        detailsHtml += '<strong>' + getFieldLabel(key) + ':</strong> ';
                        
                        // Handle undefined/null values
                        const displayOldValue = oldValue || 'غير محدد';
                        const displayNewValue = newValue || 'غير محدد';
                        
                        if (displayOldValue === 'غير محدد') {
                            detailsHtml += '<span class="text-green-600">' + displayNewValue + '</span> (جديد)';
                        } else if (displayNewValue === 'غير محدد') {
                            detailsHtml += '<span class="text-red-600 line-through">' + displayOldValue + '</span> (تم إزالته)';
                        } else {
                            detailsHtml += '<span class="text-red-600 line-through">' + displayOldValue + '</span> ';
                            detailsHtml += '⟷ <span class="text-green-600">' + displayNewValue + '</span>';
                        }
                        detailsHtml += '</div>';
                    }
                    detailsHtml += '</div>';
                }
                
                if (properties.deleted_data) {
                    const filteredDeletedData = filterTimestamps(properties.deleted_data);
                    
                    detailsHtml += '<div><strong>البيانات المحذوفة:</strong></div>';
                    detailsHtml += '<div class="bg-red-50 p-3 rounded text-sm">';
                    for (const key in filteredDeletedData) {
                        detailsHtml += '<div><strong>' + getFieldLabel(key) + ':</strong> ' + filteredDeletedData[key] + '</div>';
                    }
                    detailsHtml += '</div>';
                }
                
                detailsHtml += '</div>';
                
            } catch (e) {
                detailsHtml = '<p class="text-red-500">خطأ في تحليل البيانات</p>';
            }
            
            document.getElementById('detailsContent').innerHTML = detailsHtml;
            document.getElementById('detailsModal').style.display = 'flex';
        }
        
        function getActionLabel(action) {
            const labels = {
                'created': 'إنشاء',
                'updated': 'تحديث',
                'deleted': 'حذف',
                'login': 'تسجيل دخول',
                'image_uploaded': 'رفع صورة',
                'image_removed': 'حذف صورة',
                'family_member_created': 'إضافة فرد أسرة',
                'family_member_updated': 'تحديث فرد أسرة',
                'family_member_deleted': 'حذف فرد أسرة',
                'job_created': 'إضافة وظيفة',
                'job_updated': 'تحديث وظيفة',
                'job_deleted': 'حذف وظيفة',
                'address_created': 'إضافة عنوان',
                'address_updated': 'تحديث عنوان',
                'address_deleted': 'حذف عنوان',
                'note_created': 'إضافة ملاحظة',
                'note_updated': 'تحديث ملاحظة',
                'note_deleted': 'حذف ملاحظة',
                'deleted_all_logs': 'حذف جميع السجلات',
                'excel_uploaded': 'رفع ملف Excel'
            };
            return labels[action] || action;
        }
        
        function getFieldLabel(field) {
            const labels = {
                'name': 'الاسم',
                'national_id': 'رقم البطاقة',
                'mobile_phone': 'رقم الموبايل',
                'address': 'العنوان',
                'workplace': 'جهة العمل',
                'category_id': 'التصنيف',
                'category_name': 'التصنيف',
                'email': 'البريد الإلكتروني',
                'password': 'كلمة المرور',
                'latest_job': 'الوظيفة',
                'job_name': 'الوظيفة',
                'address_name': 'العنوان',
                'latest_address': 'العنوان',
                'created_by': 'منشئ السجل',
                'created_by_name': 'منشئ السجل',
                'company_name': 'اسم الشركة',
                'owner': 'المالك',
                'activity': 'النشاط',
                'region': 'المنطقة',
                'plot_number': 'رقم القطعة',
                'area': 'المساحة',
                'capital': 'رأس المال (جنيه)',
                'employees_count': 'عدد العمالة',
                'responsible_manager': 'المدير المسؤول',
                'notes': 'ملاحظات',
                'phone_number': 'رقم الموبايل',
                'mobile_phone': 'رقم الموبايل',
                'address': 'العنوان',
                'last_order_date': 'تاريخ اخر اوردر',
                'delivery_company_name': 'اسم شركة الديليفري',
                'user_name': 'اسم المستخدم',
                'user_email': 'البريد الإلكتروني',
                'role': 'الدور',
                'roles': 'الأدوار',
                'role_name': 'اسم الدور',
                'permission_name': 'اسم الصلاحية',
                'permissions': 'الصلاحيات',
                'email_verified_at': 'تاريخ التحقق من البريد',
                'created_at': 'تاريخ الإنشاء',
                'updated_at': 'تاريخ التحديث',
                // Request system fields
                'tracking_number': 'رقم التتبع',
                'requester_name': 'اسم مقدم الطلب',
                'center_name': 'المركز',
                'center_id': 'المركز',
                'request_type_name': 'نوع الطلب',
                'request_type_id': 'نوع الطلب',
                'status_name': 'حالة الطلب',
                'status_id': 'حالة الطلب',
                'description': 'وصف الطلب',
                'rejection_reason': 'سبب الرفض',
                'response_document': 'مستند الرد',
                'documents': 'المستندات',
                'phone': 'رقم الهاتف'
            };
            return labels[field] || field;
        }
        
        // Filter out timestamp fields and prefer name fields over ID fields
        function filterTimestamps(data) {
            if (!data) return data;
            const filtered = {};
            for (const key in data) {
                if (!key.includes('_at') && key !== 'created_at' && key !== 'updated_at' && key !== 'deleted_at' && key !== 'missing_national_id' && key !== 'individual_id' && key !== 'id') {
                    // Handle latest_job object - extract the name
                    if (key === 'latest_job' && data[key] && typeof data[key] === 'object') {
                        filtered[key] = data[key].name || 'غير محدد';
                    } else {
                        // Format last_order_date if it exists
                        if (key === 'last_order_date' && data[key]) {
                            // Check if it's already formatted (YYYY-MM-DD)
                            if (typeof data[key] === 'string' && /^\d{4}-\d{2}-\d{2}$/.test(data[key])) {
                                filtered[key] = data[key];
                            } else {
                                // Try to format the date
                                try {
                                    const date = new Date(data[key]);
                                    filtered[key] = date.toISOString().split('T')[0];
                                } catch (e) {
                                    filtered[key] = data[key];
                                }
                            }
                        } else {
                            filtered[key] = data[key];
                        }
                    }
                }
            }
            
            // Remove ID fields if corresponding name fields exist
            if (filtered.category_name) {
                delete filtered.category_id;
            }
            if (filtered.created_by_name) {
                delete filtered.created_by;
            }
            if (filtered.role_name) {
                delete filtered.role_id;
            }
            if (filtered.permission_name) {
                delete filtered.permission_id;
            }
            if (filtered.company_name) {
                delete filtered.company_id;
            }
            if (filtered.delivery_company_name) {
                delete filtered.delivery_company_id;
            }
            if (filtered.center_name) {
                delete filtered.center_id;
            }
            if (filtered.request_type_name) {
                delete filtered.request_type_id;
            }
            if (filtered.status_name) {
                delete filtered.status_id;
            }
            
            return filtered;
        }
        
        function closeDetailsModal() {
            document.getElementById('detailsModal').style.display = 'none';
        }

        function showDeleteAllModal() {
            document.getElementById('deleteAllModal').style.display = 'flex';
        }

        function closeDeleteAllModal() {
            document.getElementById('deleteAllModal').style.display = 'none';
        }

        $(document).ready(function() {
            $('#activities-table').DataTable({
                ...window.datatableConfig,
                processing: true,
                serverSide: true,
                ajax: {
                    url: "{{ route('activity-log.index') }}",
                    type: 'GET',
                },
                columns: [
                    {data: 'datetime', name: 'created_at', orderable: true, searchable: false},
                    {data: 'user', name: 'causer_id', orderable: false, searchable: false},
                    {data: 'action', name: 'action', orderable: false, searchable: false},
                    {data: 'description', name: 'description', orderable: false, searchable: true},
                    {data: 'subject', name: 'subject_id', orderable: false, searchable: false}
                ],
                order: [[0, 'desc']]
            });

            // Use event delegation for dynamically created buttons
            $(document).on('click', '[data-details-modal]', function() {
                const activityId = $(this).data('activity-id');
                const properties = $(this).data('properties');
                showDetailsModal(activityId, JSON.stringify(properties));
            });

            // Handle delete all confirmation
            $('#confirmDeleteAllBtn').on('click', function() {
                // Create a form and submit it
                const form = document.createElement('form');
                form.method = 'POST';
                form.action = '{{ route("activity-log.delete-all") }}';
                
                // Add CSRF token
                const csrfToken = document.createElement('input');
                csrfToken.type = 'hidden';
                csrfToken.name = '_token';
                csrfToken.value = '{{ csrf_token() }}';
                form.appendChild(csrfToken);
                
                // Add DELETE method
                const methodInput = document.createElement('input');
                methodInput.type = 'hidden';
                methodInput.name = '_method';
                methodInput.value = 'DELETE';
                form.appendChild(methodInput);
                
                document.body.appendChild(form);
                form.submit();
            });
        });
    </script>
</x-app-layout>
