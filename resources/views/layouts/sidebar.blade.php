<div x-data="{ sidebarOpen: false }" class="flex h-screen bg-gray-100">
    <!-- Sidebar -->
    <div :class="sidebarOpen ? 'translate-x-0' : 'translate-x-full'" 
         class="fixed inset-y-0 right-0 z-50 w-56 bg-white shadow-lg transform transition-transform duration-300 ease-in-out lg:translate-x-0 lg:static lg:inset-0">
        
        <!-- Sidebar Header -->
        <div class="flex items-center justify-between h-16 px-4 border-b border-gray-200">
            <div class="flex items-center">
                <a href="{{ route('dashboard') }}" class="flex items-center">
                    <x-application-logo class="block h-8 w-auto fill-current text-gray-800" />
                    <span class="mr-2 text-lg font-semibold text-gray-800">لوحة التحكم</span>
                </a>
            </div>
            <button @click="sidebarOpen = false" class="lg:hidden text-gray-500 hover:text-gray-700">
                <svg class="w-6 h-6" fill="none" stroke="currentColor" viewBox="0 0 24 24">
                    <path stroke-linecap="round" stroke-linejoin="round" stroke-width="2" d="M6 18L18 6M6 6l12 12"></path>
                </svg>
            </button>
        </div>

        <!-- Navigation Menu -->
        <nav class="mt-5 px-2">
            <div class="space-y-1" x-data="{ openRequestsDropdown: false }">

                <!-- Requests System Dropdown -->
                @canany(['create_request', 'edit_request', 'delete_request', 'create_center', 'edit_center', 'delete_center'])
                <div>
                    <button @click="openRequestsDropdown = !openRequestsDropdown" 
                            class="text-gray-600 hover:bg-gray-50 hover:text-gray-900 group flex items-center px-2 py-2 text-sm font-medium rounded-md transition-colors duration-200 w-full">
                        <svg class="text-gray-400 group-hover:text-gray-500 ml-3 flex-shrink-0 h-6 w-6" fill="none" stroke="currentColor" viewBox="0 0 24 24">
                            <path stroke-linecap="round" stroke-linejoin="round" stroke-width="2" d="M9 12h6m-6 4h6m2 5H7a2 2 0 01-2-2V5a2 2 0 012-2h5.586a1 1 0 01.707.293l5.414 5.414a1 1 0 01.293.707V19a2 2 0 01-2 2z"></path>
                        </svg>
                        نظام الطلبات
                        <svg class="ml-auto mr-2 h-4 w-4 transition-transform duration-200" :class="{'rotate-180': openRequestsDropdown}" fill="none" stroke="currentColor" viewBox="0 0 24 24">
                            <path stroke-linecap="round" stroke-linejoin="round" stroke-width="2" d="M19 9l-7 7-7-7"></path>
                        </svg>
                    </button>
                    
                    <div x-show="openRequestsDropdown" x-transition class="mt-1 space-y-1 mr-8">
                        @canany(['create_request', 'edit_request', 'delete_request'])
                        <a href="{{ route('requests.index') }}" class="{{ request()->routeIs('requests.*') ? 'bg-gray-100 text-gray-900' : 'text-gray-600' }} hover:bg-gray-50 hover:text-gray-900 group flex items-center px-2 py-2 text-sm font-medium rounded-md transition-colors duration-200">
                            <svg class="text-gray-400 group-hover:text-gray-500 ml-3 flex-shrink-0 h-5 w-5" fill="none" stroke="currentColor" viewBox="0 0 24 24">
                                <path stroke-linecap="round" stroke-linejoin="round" stroke-width="2" d="M9 12h6m-6 4h6m2 5H7a2 2 0 01-2-2V5a2 2 0 012-2h5.586a1 1 0 01.707.293l5.414 5.414a1 1 0 01.293.707V19a2 2 0 01-2 2z"></path>
                            </svg>
                            الطلبات
                        </a>
                        @endcanany
                        
                        @canany(['create_center', 'edit_center', 'delete_center'])
                        <a href="{{ route('centers.index') }}" class="{{ request()->routeIs('centers.*') ? 'bg-gray-100 text-gray-900' : 'text-gray-600' }} hover:bg-gray-50 hover:text-gray-900 group flex items-center px-2 py-2 text-sm font-medium rounded-md transition-colors duration-200">
                            <svg class="text-gray-400 group-hover:text-gray-500 ml-3 flex-shrink-0 h-5 w-5" fill="none" stroke="currentColor" viewBox="0 0 24 24">
                                <path stroke-linecap="round" stroke-linejoin="round" stroke-width="2" d="M19 21V5a2 2 0 00-2-2H7a2 2 0 00-2 2v16m14 0h2m-2 0h-5m-9 0H3m2 0h5M9 7h1m-1 4h1m4-4h1m-1 4h1m-5 10v-5a1 1 0 011-1h2a1 1 0 011 1v5m-4 0h4"></path>
                            </svg>
                            المراكز
                        </a>
                        @endcanany
                        
                        @canany(['create_request_type', 'edit_request_type', 'delete_request_type'])
                        <a href="{{ route('request-types.index') }}" class="{{ request()->routeIs('request-types.*') ? 'bg-gray-100 text-gray-900' : 'text-gray-600' }} hover:bg-gray-50 hover:text-gray-900 group flex items-center px-2 py-2 text-sm font-medium rounded-md transition-colors duration-200">
                            <svg class="text-gray-400 group-hover:text-gray-500 ml-3 flex-shrink-0 h-5 w-5" fill="none" stroke="currentColor" viewBox="0 0 24 24">
                                <path stroke-linecap="round" stroke-linejoin="round" stroke-width="2" d="M7 7h.01M7 3h5c.512 0 1.024.195 1.414.586l7 7a2 2 0 010 2.828l-7 7a2 2 0 01-2.828 0l-7-7A1.994 1.994 0 013 12V7a4 4 0 014-4z"></path>
                            </svg>
                            أنواع الطلبات
                        </a>
                        @endcanany
                        
                        @canany(['create_request_status', 'edit_request_status', 'delete_request_status'])
                        <a href="{{ route('request-statuses.index') }}" class="{{ request()->routeIs('request-statuses.*') ? 'bg-gray-100 text-gray-900' : 'text-gray-600' }} hover:bg-gray-50 hover:text-gray-900 group flex items-center px-2 py-2 text-sm font-medium rounded-md transition-colors duration-200">
                            <svg class="text-gray-400 group-hover:text-gray-500 ml-3 flex-shrink-0 h-5 w-5" fill="none" stroke="currentColor" viewBox="0 0 24 24">
                                <path stroke-linecap="round" stroke-linejoin="round" stroke-width="2" d="M9 12l2 2 4-4m6 2a9 9 0 11-18 0 9 9 0 0118 0z"></path>
                            </svg>
                            حالات الطلبات
                        </a>
                        @endcanany
                    </div>
                </div>
                @endcanany

                <hr>

                <!-- Activity Log Link -->
                <a href="{{ route('activity-log.index') }}" 
                   class="{{ request()->routeIs('activity-log.*') ? 'bg-gray-100 text-gray-900' : 'text-gray-600 hover:bg-gray-50 hover:text-gray-900' }} group flex items-center px-2 py-2 text-sm font-medium rounded-md transition-colors duration-200">
                    <svg class="{{ request()->routeIs('activity-log.*') ? 'text-gray-500' : 'text-gray-400 group-hover:text-gray-500' }} ml-3 flex-shrink-0 h-6 w-6" fill="none" stroke="currentColor" viewBox="0 0 24 24">
                        <path stroke-linecap="round" stroke-linejoin="round" stroke-width="2" d="M9 12h6m-6 4h6m2 5H7a2 2 0 01-2-2V5a2 2 0 012-2h5.586a1 1 0 01.707.293l5.414 5.414a1 1 0 01.293.707V19a2 2 0 01-2 2z"></path>
                    </svg>
                    سجل الأنشطة
                </a>

                <!-- Users Link (Super Admin Only) -->
                @role('super_admin')
                <a href="{{ route('users.index') }}" 
                   class="{{ request()->routeIs('users.*') ? 'bg-gray-100 text-gray-900' : 'text-gray-600 hover:bg-gray-50 hover:text-gray-900' }} group flex items-center px-2 py-2 text-sm font-medium rounded-md transition-colors duration-200">
                    <svg class="{{ request()->routeIs('users.*') ? 'text-gray-500' : 'text-gray-400 group-hover:text-gray-500' }} ml-3 flex-shrink-0 h-6 w-6" fill="none" stroke="currentColor" viewBox="0 0 24 24">
                        <path stroke-linecap="round" stroke-linejoin="round" stroke-width="2" d="M17 20h5v-2a3 3 0 00-5.356-1.857M17 20H7m10 0v-2c0-.656-.126-1.283-.356-1.857M7 20H2v-2a3 3 0 015.356-1.857M7 20v-2c0-.656.126-1.283.356-1.857m0 0a5.002 5.002 0 019.288 0M15 7a3 3 0 11-6 0 3 3 0 016 0zm6 3a2 2 0 11-4 0 2 2 0 014 0zM7 10a2 2 0 11-4 0 2 2 0 014 0z"></path>
                    </svg>
                    المستخدمون
                </a>
                @endrole

                <!-- Roles Link (Super Admin Only) -->
                @role('super_admin')
                <a href="{{ route('roles.index') }}" 
                   class="{{ request()->routeIs('roles.*') ? 'bg-gray-100 text-gray-900' : 'text-gray-600 hover:bg-gray-50 hover:text-gray-900' }} group flex items-center px-2 py-2 text-sm font-medium rounded-md transition-colors duration-200">
                    <svg class="{{ request()->routeIs('roles.*') ? 'text-gray-500' : 'text-gray-400 group-hover:text-gray-500' }} ml-3 flex-shrink-0 h-6 w-6" fill="none" stroke="currentColor" viewBox="0 0 24 24">
                        <path stroke-linecap="round" stroke-linejoin="round" stroke-width="2" d="M9 12l2 2 4-4m5.618-4.016A11.955 11.955 0 0112 2.944a11.955 11.955 0 01-8.618 3.04A12.02 12.02 0 003 9c0 5.591 3.824 10.29 9 11.622 5.176-1.332 9-6.03 9-11.622 0-1.042-.133-2.052-.382-3.016z"></path>
                    </svg>
                    الأدوار
                </a>
                @endrole

                <!-- Permissions Link (Super Admin Only) -->
                @role('super_admin')
                <a href="{{ route('permissions.index') }}" 
                   class="{{ request()->routeIs('permissions.*') ? 'bg-gray-100 text-gray-900' : 'text-gray-600 hover:bg-gray-50 hover:text-gray-900' }} group flex items-center px-2 py-2 text-sm font-medium rounded-md transition-colors duration-200">
                    <svg class="{{ request()->routeIs('permissions.*') ? 'text-gray-500' : 'text-gray-400 group-hover:text-gray-500' }} ml-3 flex-shrink-0 h-6 w-6" fill="none" stroke="currentColor" viewBox="0 0 24 24">
                        <path stroke-linecap="round" stroke-linejoin="round" stroke-width="2" d="M12 15v2m-6 4h12a2 2 0 002-2v-6a2 2 0 00-2-2H6a2 2 0 00-2 2v6a2 2 0 002 2zm10-10V7a4 4 0 00-8 0v4h8z"></path>
                    </svg>
                    الصلاحيات
                </a>
                @endrole

                <!-- Backups Link (Super Admin Only) -->
                @role('super_admin')
                <a href="{{ route('backups.index') }}" 
                   class="{{ request()->routeIs('backups.*') ? 'bg-gray-100 text-gray-900' : 'text-gray-600 hover:bg-gray-50 hover:text-gray-900' }} group flex items-center px-2 py-2 text-sm font-medium rounded-md transition-colors duration-200">
                    <svg class="{{ request()->routeIs('backups.*') ? 'text-gray-500' : 'text-gray-400 group-hover:text-gray-500' }} ml-3 flex-shrink-0 h-6 w-6" fill="none" stroke="currentColor" viewBox="0 0 24 24">
                        <path stroke-linecap="round" stroke-linejoin="round" stroke-width="2" d="M4 16v1a3 3 0 003 3h10a3 3 0 003-3v-1m-4-8l-4-4m0 0L8 8m4-4v12"></path>
                    </svg>
                    النسخ الاحتياطية
                </a>
                @endrole

                <!-- Profile Link -->
                <a href="{{ route('profile.edit') }}" 
                   class="{{ request()->routeIs('profile.*') ? 'bg-gray-100 text-gray-900' : 'text-gray-600 hover:bg-gray-50 hover:text-gray-900' }} group flex items-center px-2 py-2 text-sm font-medium rounded-md transition-colors duration-200">
                    <svg class="{{ request()->routeIs('profile.*') ? 'text-gray-500' : 'text-gray-400 group-hover:text-gray-500' }} ml-3 flex-shrink-0 h-6 w-6" fill="none" stroke="currentColor" viewBox="0 0 24 24">
                        <path stroke-linecap="round" stroke-linejoin="round" stroke-width="2" d="M16 7a4 4 0 11-8 0 4 4 0 018 0zM12 14a7 7 0 00-7 7h14a7 7 0 00-7-7z"></path>
                    </svg>
                    الملف الشخصي
                </a>

            </div>
        </nav>

        <!-- User Section at Bottom -->
        <div class="absolute bottom-0 w-full p-4 border-t border-gray-200">
            <div class="flex items-center space-x-3 space-x-reverse">
                <div class="flex-1 min-w-0">
                    <p class="text-sm font-medium text-gray-700 truncate">{{ Auth::user()->name }}</p>
                    <p class="text-xs text-gray-500 truncate">{{ Auth::user()->email }}</p>
                </div>
                <div class="flex-shrink-0">
                    <form method="POST" action="{{ route('logout') }}">
                        @csrf
                        <button type="submit" class="text-gray-400 hover:text-gray-600 transition-colors duration-200" title="تسجيل الخروج">
                            <svg class="w-5 h-5" fill="none" stroke="currentColor" viewBox="0 0 24 24">
                                <path stroke-linecap="round" stroke-linejoin="round" stroke-width="2" d="M17 16l4-4m0 0l-4-4m4 4H7m6 4v1a3 3 0 01-3 3H6a3 3 0 01-3-3V7a3 3 0 013-3h4a3 3 0 013 3v1"></path>
                            </svg>
                        </button>
                    </form>
                </div>
            </div>
        </div>
    </div>

    <!-- Mobile sidebar overlay -->
    <div x-show="sidebarOpen" 
         x-transition:enter="transition-opacity ease-linear duration-300"
         x-transition:enter-start="opacity-0"
         x-transition:enter-end="opacity-100"
         x-transition:leave="transition-opacity ease-linear duration-300"
         x-transition:leave-start="opacity-100"
         x-transition:leave-end="opacity-0"
         @click="sidebarOpen = false"
         class="fixed inset-0 z-40 bg-gray-600 bg-opacity-75 lg:hidden"></div>

    <!-- Main content -->
    <div class="flex-1 flex flex-col overflow-hidden">
        <!-- Top bar -->
        <div class="bg-white shadow-sm border-b border-gray-200 lg:hidden">
            <div class="flex items-center justify-between h-16 px-4">
                <button @click="sidebarOpen = true" class="text-gray-500 hover:text-gray-700">
                    <svg class="w-6 h-6" fill="none" stroke="currentColor" viewBox="0 0 24 24">
                        <path stroke-linecap="round" stroke-linejoin="round" stroke-width="2" d="M4 6h16M4 12h16M4 18h16"></path>
                    </svg>
                </button>
                <div class="flex items-center">
                    <a href="{{ route('dashboard') }}">
                        <x-application-logo class="block h-8 w-auto fill-current text-gray-800" />
                    </a>
                </div>
                <div class="w-6"></div> <!-- Spacer for centering -->
            </div>
        </div>

        <!-- Page content -->
        <main class="flex-1 overflow-x-hidden overflow-y-auto bg-gray-50">
            <div class="container mx-auto px-6 py-8">
                {{ $slot }}
            </div>
        </main>
    </div>
</div>
