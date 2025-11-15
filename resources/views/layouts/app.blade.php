<!DOCTYPE html>
<html lang="{{ str_replace('_', '-', app()->getLocale()) }}" dir="rtl">
    <head>
        <meta charset="utf-8">
        <meta name="viewport" content="width=device-width, initial-scale=1">
        <meta name="csrf-token" content="{{ csrf_token() }}">

        <title>{{ config('app.name', 'Laravel') }}</title>

        <!-- Fonts -->
        <link rel="preconnect" href="https://fonts.googleapis.com">
        <link rel="preconnect" href="https://fonts.gstatic.com" crossorigin>
        <link href="https://fonts.googleapis.com/css2?family=Cairo:wght@200;300;400;500;600;700;800;900&display=swap" rel="stylesheet">
        <link rel="preconnect" href="https://fonts.bunny.net">
        <link href="https://fonts.bunny.net/css?family=figtree:400,500,600&display=swap" rel="stylesheet" />

        <!-- Styles -->
        @vite(['resources/css/app.css', 'resources/js/app.js'])
        
        <!-- DataTables CSS -->
        <link rel="stylesheet" href="https://cdn.datatables.net/1.13.7/css/jquery.dataTables.min.css">
        <style>
            /* Fix horizontal scrolling issues */
            body {
                overflow-x: hidden;
            }
            
            .container {
                max-width: 100%;
                overflow-x: hidden;
            }
            
            /* Prevent horizontal scrolling on main content */
            main {
                overflow-x: hidden !important;
            }
            
            /* Fix grid layouts that might cause overflow */
            .grid {
                max-width: 100%;
                overflow-x: hidden;
            }
            
            /* Ensure cards don't overflow */
            .bg-white {
                max-width: 100%;
                word-wrap: break-word;
            }
            
            /* Fix DataTables overflow */
            .dataTables_wrapper {
                overflow-x: auto;
                max-width: 100%;
            }
            
            /* Prevent text from causing horizontal scroll */
            .text-2xl, .text-lg, .text-sm, .text-xs {
                word-wrap: break-word;
                overflow-wrap: break-word;
            }
            
            /* Global DataTables pagination styling */
            .dataTables_wrapper .dataTables_paginate {
                text-align: right;
                margin-top: 10px;
                display: inline-block;
            }

            .dataTables_wrapper .dataTables_paginate .paginate_button {
                box-sizing: border-box;
                display: inline-block;
                min-width: 1.5em;
                padding: 0.5em 1em;
                margin: 0 2px;
                text-align: center;
                text-decoration: none !important;
                cursor: pointer;
                border: 1px solid #e5e7eb;
                border-radius: 6px;
                background: white;
                color: #6b7280 !important;
                transition: all 0.2s ease;
                text-decoration: none !important;
            }

            /* Pagination button hover styling - Bootstrap classes */
            .dataTables_wrapper .dataTables_paginate .page-item:hover {
                background-color: #3b82f6 !important;
                border-color: #3b82f6 !important;
                border-radius: 6px !important;
            }


            /* Fallback for paginate_button hover */
            .dataTables_wrapper .dataTables_paginate .paginate_button:hover {
                background-color: #3b82f6 !important;
                color: white !important;
                border-color: #3b82f6 !important;
            }

            /* Active page button styling - Bootstrap classes */
            .dataTables_wrapper .dataTables_paginate .page-item.active {
                background-color: #7c3aed !important;
                border-color: #7c3aed !important;
                border-radius: 6px !important;
            }

            .dataTables_wrapper .dataTables_paginate .page-item.active .page-link {
                background-color: #7c3aed !important;
                border-color: #7c3aed !important;
                color: #fff !important;
            }

       

            /* Fallback for paginate_button.current if needed */
            .dataTables_wrapper .dataTables_paginate .paginate_button.current {
                background-color: #7c3aed !important;
                color: #fff !important;
                border-color: #7c3aed !important;
            }

            /* Disabled pagination button styling - Bootstrap classes */
            .dataTables_wrapper .dataTables_paginate .page-item.disabled {
                background-color: #f9fafb !important;
                border-color: #e5e7eb !important;
                cursor: not-allowed;
            }

            .dataTables_wrapper .dataTables_paginate .page-item.disabled .page-link {
                color: #9ca3af !important;
                background-color: #f9fafb !important;
                border-color: #e5e7eb !important;
                cursor: not-allowed;
            }

            /* Fallback for paginate_button.disabled */
            .dataTables_wrapper .dataTables_paginate .paginate_button.disabled {
                color: #9ca3af !important;
                border: 1px solid #e5e7eb;
                background: #f9fafb;
                cursor: not-allowed;
            }

            .dataTables_wrapper .dataTables_paginate .paginate_button.disabled:hover {
                color: #9ca3af !important;
                border: 1px solid #e5e7eb !important;
                background: #f9fafb !important;
            }

            /* Make DataTables take full width */
            .dataTables_wrapper table.dataTable {
                width: 100% !important;
            }

            /* Layout for length menu and filter on same row */
            .dataTables_wrapper .dataTables_length {
                float: right;
                margin-left: 1rem;
            }

            .dataTables_wrapper .dataTables_filter {
                float: left;
            }

            .dataTables_wrapper .dataTables_length label,
            .dataTables_wrapper .dataTables_filter label {
                display: flex;
                align-items: center;
                gap: 0.5rem;
            }

            .dataTables_wrapper .dataTables_length select {
                padding: 0.5rem;
                border: 1px solid #d1d5db;
                border-radius: 0.375rem;
                background-color: white;
                min-width: 80px;
            }

            .dataTables_wrapper .dataTables_filter input {
                padding: 0.5rem 1rem;
                border: 1px solid #d1d5db;
                border-radius: 0.375rem;
                margin-right: 0.5rem;
                min-width: 250px;
            }

            /* Clear float for the wrapper */
            .dataTables_wrapper::after {
                content: "";
                display: table;
                clear: both;
            }

            /* Responsive table wrapper */
            .dataTables_wrapper .dataTables_scroll {
                width: 100%;
                overflow-x: auto;
            }

            /* Table spacing */
            .dataTables_wrapper table.dataTable thead th,
            .dataTables_wrapper table.dataTable tbody td {
                white-space: nowrap;
            }

            /* Add margin between controls and table */
            .dataTables_wrapper .dataTables_length,
            .dataTables_wrapper .dataTables_filter {
                margin-bottom: 1.5rem;
            }

            /* Table borders - Custom styling without Bootstrap */
            .dataTables_wrapper table.dataTable {
                width: 100% !important;
                border: 1px solid #e5e7eb !important;
                border-collapse: collapse !important;
            }

            .dataTables_wrapper table.dataTable thead th {
                border: 1px solid #e5e7eb !important;
                background-color: #f9fafb !important;
                padding: 12px 24px !important;
                font-weight: 500 !important;
                color: #6b7280 !important;
                text-transform: uppercase !important;
                font-size: 0.75rem !important;
                letter-spacing: 0.05em !important;
            }

            .dataTables_wrapper table.dataTable tbody td {
                border: 1px solid #e5e7eb !important;
                padding: 12px 24px !important;
            }

            .dataTables_wrapper table.dataTable tbody tr:nth-child(even) {
                background-color: #f9fafb;
            }

            .dataTables_wrapper table.dataTable tbody tr:hover {
                background-color: #f3f4f6 !important;
            }

            /* Controls row spacing */
            .dataTables_wrapper > div:first-child {
                margin-bottom: 1.5rem;
            }

            /* Length dropdown styling */
            .dataTables_wrapper .dataTables_length {
                margin-top: 10px;
                direction: rtl;
                display: inline-block;
                margin-left: 20px;
            }

            .dataTables_wrapper .dataTables_length select {
                border: 1px solid #e5e7eb;
                border-radius: 6px;
                padding: 6px 12px;
                font-size: 14px;
                background: white;
                margin: 0 8px;
            }

            /* Info text styling */
            .dataTables_wrapper .dataTables_info {
                color: #6b7280;
                font-size: 14px;
                margin-top: 10px;
                display: inline-block;
                margin-left: 20px;
                direction: rtl;
            }

            /* Bottom row styling */
            .dataTables_wrapper .row:last-child {
                display: flex;
                align-items: center;
                justify-content: space-between;
                flex-wrap: wrap;
                gap: 20px;
                padding-top: 15px;
                border-top: 1px solid #f1f5f9;
                margin-top: 15px;
            }

            /* Global table styling */
            .table {
                border-collapse: separate;
                border-spacing: 0;
                width: 100%;
                background: white;
            }

            .table th {
                background-color: #f8fafc;
                font-weight: 600;
                color: #374151;
                padding: 16px 20px;
                border-bottom: 1px solid #e5e7eb;
                text-align: right !important;
                font-size: 14px;
            }

            /* Ensure DataTables headers are right-aligned */
            .dataTables_wrapper table th {
                text-align: right !important;
            }

            .table td {
                padding: 16px 20px;
                border-bottom: 1px solid #f1f5f9;
                text-align: right;
                font-size: 14px;
                color: #4b5563;
            }

            .table tbody tr:hover {
                background-color: #f8fafc;
            }

            .table tbody tr:last-child td {
                border-bottom: none;
            }

            /* Action buttons */
            .action-buttons {
                display: flex;
                gap: 8px;
                justify-content: flex-start;
                text-align: right !important;
                direction: rtl;
            }

            /* Ensure DataTables action buttons are right-aligned */
            .dataTables_wrapper table td .action-buttons {
                text-align: right !important;
                justify-content: flex-start;
                direction: rtl;
            }

            /* Action button cells */
            .dataTables_wrapper table td:last-child {
                text-align: right !important;
                direction: rtl;
            }

            .btn-action {
                padding: 6px 10px;
                border-radius: 6px;
                border: none;
                cursor: pointer;
                font-size: 12px;
                transition: all 0.2s ease;
                display: inline-flex;
                align-items: center;
                gap: 4px;
                min-width: 80px;
                justify-content: center;
                white-space: nowrap;
            }

            .btn-view {
                background-color: #1e40af;
                color: white;
            }

            .btn-view:hover {
                background-color: #1d4ed8;
            }

            .btn-edit {
                background-color: #7c3aed;
                color: white;
            }

            .btn-edit:hover {
                background-color: #8b5cf6;
            }

            .btn-delete {
                background-color: #dc2626;
                color: white;
            }

            .btn-delete:hover {
                background-color: #ef4444;
            }

            .btn-upload {
                background-color: #059669;
                color: white;
            }

            .btn-upload:hover {
                background-color: #10b981;
            }

            .btn-disabled {
                background-color: #6b7280;
                color: white;
            }

            .btn-disabled:hover {
                background-color: #6b7280;
                cursor: not-allowed;
            }

            .btn-orders {
                background-color: #059669;
                color: white;
            }

            .btn-orders:hover {
                background-color: #10b981;
            }


            /* Header section */
            .page-header {
                display: flex;
                justify-content: space-between;
                align-items: center;
                margin-bottom: 24px;
                padding-bottom: 16px;
                border-bottom: 1px solid #e5e7eb;
            }

            .page-title {
                font-size: 24px;
                font-weight: 700;
                color: #111827;
                margin: 0;
            }

            .page-subtitle {
                font-size: 14px;
                color: #6b7280;
                margin-top: 4px;
            }

            .btn-primary {
                background-color: #7c3aed;
                color: white;
                padding: 10px 20px;
                border-radius: 8px;
                border: none;
                font-weight: 500;
                cursor: pointer;
                transition: all 0.2s ease;
                display: inline-flex;
                align-items: center;
                gap: 8px;
            }

            .btn-primary:hover {
                background-color: #8b5cf6;
            }

            /* Custom Modal Styles */
            .custom-modal {
                position: fixed;
                top: 0;
                left: 0;
                width: 100%;
                height: 100%;
                z-index: 9999;
                display: flex;
                align-items: center;
                justify-content: center;
            }

            .custom-modal-overlay {
                position: absolute;
                top: 0;
                left: 0;
                width: 100%;
                height: 100%;
                background-color: rgba(0, 0, 0, 0.5);
                backdrop-filter: blur(2px);
            }

            .custom-modal-content {
                position: relative;
                background: white;
                border-radius: 12px;
                box-shadow: 0 20px 25px -5px rgba(0, 0, 0, 0.1), 0 10px 10px -5px rgba(0, 0, 0, 0.04);
                max-width: 400px;
                width: 90%;
                max-height: 90vh;
                overflow: hidden;
                animation: modalSlideIn 0.3s ease-out;
            }

            @keyframes modalSlideIn {
                from {
                    opacity: 0;
                    transform: translateY(-20px) scale(0.95);
                }
                to {
                    opacity: 1;
                    transform: translateY(0) scale(1);
                }
            }

            .custom-modal-header {
                display: flex;
                align-items: center;
                justify-content: space-between;
                padding: 20px 24px 16px;
                border-bottom: 1px solid #e5e7eb;
            }

            .custom-modal-title {
                font-size: 18px;
                font-weight: 600;
                color: #111827;
                margin: 0;
            }

            .custom-modal-close {
                background: none;
                border: none;
                font-size: 24px;
                color: #6b7280;
                cursor: pointer;
                padding: 0;
                width: 24px;
                height: 24px;
                display: flex;
                align-items: center;
                justify-content: center;
                border-radius: 4px;
                transition: all 0.2s ease;
            }

            .custom-modal-close:hover {
                background-color: #f3f4f6;
                color: #374151;
            }

            .custom-modal-body {
                padding: 24px;
                text-align: center;
            }

            .custom-modal-icon {
                width: 64px;
                height: 64px;
                margin: 0 auto 16px;
                background-color: #fef2f2;
                border-radius: 50%;
                display: flex;
                align-items: center;
                justify-content: center;
                color: #ef4444;
            }

            .custom-modal-body p {
                font-size: 16px;
                color: #374151;
                line-height: 1.5;
                margin: 0;
            }

            .custom-modal-footer {
                display: flex;
                gap: 12px;
                padding: 16px 24px 24px;
                justify-content: flex-end;
            }

            .custom-btn {
                padding: 10px 20px;
                border-radius: 8px;
                font-size: 14px;
                font-weight: 500;
                border: none;
                cursor: pointer;
                transition: all 0.2s ease;
                min-width: 80px;
            }

            .custom-btn-secondary {
                background-color: #f3f4f6;
                color: #374151;
            }

            .custom-btn-secondary:hover {
                background-color: #e5e7eb;
            }

            .custom-btn-danger {
                background-color: #ef4444;
                color: white;
            }

            .custom-btn-danger:hover {
                background-color: #dc2626;
            }

            form:not(.action-buttons form):not([action*="logout"]) button[type="submit"] {
                background-color: #2563eb !important;
                color: white !important;
                border: none !important;
                cursor: pointer !important;
                display: inline-block !important;
                visibility: visible !important;
                opacity: 1 !important;
            }

            form:not(.action-buttons form):not([action*="logout"]) button[type="submit"]:hover {
                background-color: #1d4ed8 !important;
            }
        </style>
        
        @stack('styles')
        
        <!-- Scripts -->
        <!-- jQuery for DataTables -->
        <script src="https://code.jquery.com/jquery-3.7.1.min.js"></script>
        <!-- Bootstrap JS -->
        <script src="https://cdn.jsdelivr.net/npm/bootstrap@5.3.2/dist/js/bootstrap.bundle.min.js"></script>
        <!-- DataTables JS -->
        <script src="https://cdn.datatables.net/1.13.7/js/jquery.dataTables.min.js"></script>
        <script src="https://cdn.datatables.net/1.13.7/js/dataTables.bootstrap5.min.js"></script>
        
        <script>
            // Global DataTables configuration
            window.datatableConfig = {
                language: {
                    "decimal": "",
                    "emptyTable": "لا توجد بيانات متاحة في الجدول",
                    "info": "إظهار _START_ إلى _END_ من أصل _TOTAL_ مدخل",
                    "infoEmpty": "إظهار 0 من أصل 0 مدخل",
                    "infoFiltered": "(تصفية من _MAX_ إجمالي مدخلات)",
                    "infoPostFix": "",
                    "thousands": ",",
                    "lengthMenu": "أظهر _MENU_ مدخلات",
                    "loadingRecords": "جارٍ التحميل...",
                    "processing": "جارٍ التحميل...",
                    "search": "البحث:",
                    "zeroRecords": "لم يتم العثور على سجلات مطابقة",
                    "paginate": {
                        "first": "الأول",
                        "last": "الأخير",
                        "next": "التالي",
                        "previous": "السابق"
                    },
                    "aria": {
                        "sortAscending": ": تفعيل لترتيب العمود تصاعدياً",
                        "sortDescending": ": تفعيل لترتيب العمود تنازلياً"
                    }
                },
                dom: '<"row"<"col-sm-12"tr>>' +
                     '<"row"<"col-sm-12"l><"col-sm-12"i><"col-sm-12"p>>',
                responsive: true,
                pageLength: 10,
                lengthMenu: [[10, 25, 50, -1], [10, 25, 50, "الكل"]],
                pagingType: 'simple_numbers',
                search: {
                    regex: false,
                    caseInsensitive: true
                },
                columnDefs: [
                    { orderable: false, targets: -1 }
                ]
            };

        </script>
        
        @stack('scripts')
        
        <!-- Global Modal Functions -->
        <script>
            // Global modal functions available to all pages
            function showDeleteModal(id, name, type) {
                const modal = document.getElementById('customModal');
                const message = document.getElementById('modalMessage');
                
                if (modal && message) {
                    message.innerHTML = 'هل أنت متأكد من حذف ال' + type + ' <strong>' + name + '</strong>؟ لا يمكن التراجع عن هذا الإجراء.';
                    document.getElementById('confirmDeleteBtn').onclick = function() {
                        document.getElementById('delete-form-' + id).submit();
                    };
                    modal.style.display = 'flex';
                }
            }

            function closeCustomModal() {
                const modal = document.getElementById('customModal');
                if (modal) {
                    modal.style.display = 'none';
                }
            }
        </script>
    </head>
    <body class="font-sans antialiased">
        @include('layouts.sidebar')
        @yield('content')
    </body>
</html>
