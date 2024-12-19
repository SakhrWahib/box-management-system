<!DOCTYPE html>
<html lang="ar" dir="rtl">
<head>
    <meta charset="UTF-8">
    <meta name="viewport" content="width=device-width, initial-scale=1.0">
    <meta name="csrf-token" content="{{ csrf_token() }}">
    <title>{{ config('app.name', 'لوحة التحكم') }}</title>
    
    <!-- Styles -->
    <script src="https://cdn.tailwindcss.com"></script>
    <link rel="stylesheet" href="https://cdnjs.cloudflare.com/ajax/libs/font-awesome/6.0.0/css/all.min.css">
    
    <!-- Additional CSS -->
    <style>
        @import url('https://fonts.googleapis.com/css2?family=Cairo:wght@400;600;700&display=swap');
        body {
            font-family: 'Cairo', sans-serif;
        }
        .modal {
            display: none;
        }
        .modal.show {
            display: block;
        }
    </style>
</head>
<body class="bg-gray-100">
    <!-- Navigation Bar -->
    <nav class="nav-container shadow-lg fixed w-full top-0 z-50">
        <div class="max-w-full mx-auto px-4">
            <div class="flex justify-between h-14">
                <div class="flex items-center space-x-4 space-x-reverse">
                    <div class="flex-shrink-0 flex items-center">
                        <div class="bg-indigo-100 p-2 rounded-full ml-2">
                            <i class="fas fa-microchip text-indigo-600 text-xl"></i>
                        </div>
                        <span class="text-lg font-bold text-gray-800">نظام إدارة الصناديق وصل</span>
                    </div>
                </div>
                <div class="flex items-center gap-6">
                    <!-- Time and Date -->
                    <div class="flex items-center text-sm text-gray-600 bg-gray-50 px-3 py-1.5 rounded-lg">
                        <i class="fas fa-calendar-alt ml-2 text-indigo-500"></i>
                        <span>{{ now()->format('H:i') }}</span>
                        <span class="mx-2 text-gray-300">|</span>
                        <span>{{ now()->format('Y-m-d') }}</span>
                    </div>

                    <!-- Notifications -->
                    <div class="relative" x-data="{ open: false }">
                        <button @click="open = !open" class="relative p-2 text-gray-600 hover:bg-indigo-50 rounded-full focus:outline-none focus:ring-2 focus:ring-indigo-500 transition-all duration-200">
                            <i class="fas fa-bell text-lg"></i>
                            <span class="absolute -top-1 -right-1 bg-red-500 text-white text-xs rounded-full h-4 w-4 flex items-center justify-center">
                                {{ \App\Models\Notification::where('is_read', false)->count() }}
                            </span>
                        </button>
                        <div x-show="open" @click.away="open = false" class="absolute left-0 mt-2 w-80 bg-white rounded-lg shadow-lg overflow-hidden border border-gray-100">
                            <div class="max-h-96 overflow-y-auto">
                                @foreach(\App\Models\Notification::latest()->take(5)->get() as $notification)
                                <div class="p-3 border-b hover:bg-gray-50 {{ !$notification->is_read ? 'bg-indigo-50' : '' }} transition-colors duration-200">
                                    <div class="flex items-start">
                                        <div class="flex-shrink-0">
                                            <i class="fas fa-info-circle text-indigo-500"></i>
                                        </div>
                                        <div class="mr-3 flex-1">
                                            <p class="text-sm font-medium text-gray-900">
                                                {{ $notification->title }}
                                            </p>
                                            <p class="text-xs text-gray-500 mt-1">
                                                {{ $notification->created_at->diffForHumans() }}
                                            </p>
                                        </div>
                                    </div>
                                </div>
                                @endforeach
                            </div>
                            <a href="{{ route('notifications.index') }}" class="block text-center py-2 text-sm text-indigo-600 border-t hover:bg-gray-50 transition-colors duration-200">
                                عرض كل الإشعارات
                            </a>
                        </div>
                    </div>
                    
                    <!-- User Menu -->
                    <div class="relative" x-data="{ open: false }">
                        <button @click="open = !open" class="flex items-center space-x-2 space-x-reverse hover:bg-indigo-50 rounded-lg px-3 py-2 transition-colors duration-200">
                            <div class="bg-indigo-100 p-2 rounded-full">
                                <i class="fas fa-user text-indigo-600"></i>
                            </div>
                            <span class="text-sm font-medium text-gray-700">المسؤول</span>
                            <i class="fas fa-chevron-down text-xs text-gray-400"></i>
                        </button>
                        
                        <div x-show="open" @click.away="open = false" class="absolute left-0 mt-2 w-48 bg-white rounded-lg shadow-lg overflow-hidden border border-gray-100">
                            <div class="py-1">
                                <form method="POST" action="{{ route('admin.logout') }}">
                                    @csrf
                                    <button type="submit" class="w-full text-right px-4 py-2 text-sm text-gray-700 hover:bg-gray-50 transition-colors duration-200">
                                        <i class="fas fa-sign-out-alt ml-2 text-red-500"></i>
                                        تسجيل الخروج
                                    </button>
                                </form>
                            </div>
                        </div>
                    </div>
                </div>
            </div>
        </div>
    </nav>

    <!-- Add margin top to main content -->
    <div class="pt-14">
    <!-- Sidebar -->
    <div class="fixed right-0 top-14 w-64 h-full" style="background: linear-gradient(135deg, #1e1b4b 0%, #312e81 100%); box-shadow: 4px 0 6px -1px rgba(0, 0, 0, 0.1);">
        <nav class="p-4">
            <ul class="space-y-1">
                <li>
                    <a href="{{ route('dashboard') }}" class="flex items-center text-gray-100 p-3 rounded-lg hover:bg-white/10 transition-all duration-300 group transform hover:-translate-x-1">
                        <div class="bg-indigo-500/20 p-2.5 rounded-lg ml-3 group-hover:bg-indigo-500/30 transition-colors duration-300">
                            <i class="fas fa-home text-indigo-300 text-lg"></i>
                        </div>
                        <span class="font-medium tracking-wide">الرئيسية</span>
                    </a>
                </li>
                <li>
                    <a href="{{ route('devices.manage') }}" class="flex items-center text-gray-100 p-3 rounded-lg hover:bg-white/10 transition-all duration-300 group transform hover:-translate-x-1">
                        <div class="bg-indigo-500/20 p-2.5 rounded-lg ml-3 group-hover:bg-indigo-500/30 transition-colors duration-300">
                            <i class="fas fa-microchip text-indigo-300 text-lg"></i>
                        </div>
                        <span class="font-medium tracking-wide">الأجهزة</span>
                    </a>
                </li>

                <div class="pt-4 pb-2">
                    <div class="text-xs uppercase font-semibold text-indigo-300/80 px-3 tracking-wider">إدارة المستخدمين</div>
                </div>
                <li>
                    <a href="{{ route('users.manage') }}" class="flex items-center text-gray-100 p-3 rounded-lg hover:bg-white/10 transition-all duration-300 group transform hover:-translate-x-1">
                        <div class="bg-indigo-500/20 p-2.5 rounded-lg ml-3 group-hover:bg-indigo-500/30 transition-colors duration-300">
                            <i class="fas fa-users-cog text-indigo-300 text-lg"></i>
                        </div>
                        <span class="font-medium tracking-wide">المستخدمين</span>
                    </a>
                </li>
                <li>
                    <a href="{{ route('permissions.index') }}" class="flex items-center text-gray-100 p-3 rounded-lg hover:bg-white/10 transition-all duration-300 group transform hover:-translate-x-1">
                        <div class="bg-indigo-500/20 p-2.5 rounded-lg ml-3 group-hover:bg-indigo-500/30 transition-colors duration-300">
                            <i class="fas fa-shield-alt text-indigo-300 text-lg"></i>
                        </div>
                        <span class="font-medium tracking-wide">الصلاحيات</span>
                    </a>
                </li>

                <div class="pt-4 pb-2">
                    <div class="text-xs uppercase font-semibold text-indigo-300/80 px-3 tracking-wider">التقارير والإشعارات</div>
                </div>
                <li>
                    <a href="{{ route('events.manage') }}" class="flex items-center text-gray-100 p-3 rounded-lg hover:bg-white/10 transition-all duration-300 group transform hover:-translate-x-1">
                        <div class="bg-indigo-500/20 p-2.5 rounded-lg ml-3 group-hover:bg-indigo-500/30 transition-colors duration-300">
                            <i class="fas fa-chart-line text-indigo-300 text-lg"></i>
                        </div>
                        <span class="font-medium tracking-wide">التقارير</span>
                    </a>
                </li>
                <li>
                    <a href="{{ route('notifications.index') }}" class="flex items-center text-gray-100 p-3 rounded-lg hover:bg-white/10 transition-all duration-300 group transform hover:-translate-x-1">
                        <div class="bg-indigo-500/20 p-2.5 rounded-lg ml-3 group-hover:bg-indigo-500/30 transition-colors duration-300">
                            <i class="fas fa-bell text-indigo-300 text-lg"></i>
                        </div>
                        <span class="font-medium tracking-wide">الإشعارات</span>
                        @if(\App\Models\Notification::where('is_read', false)->count() > 0)
                            <span class="mr-auto bg-red-500 text-white text-xs font-bold px-2.5 py-1 rounded-full animate-pulse">
                                {{ \App\Models\Notification::where('is_read', false)->count() }}
                            </span>
                        @endif
                    </a>
                </li>

                
                </li>
            </ul>
        </nav>
    </div>

    <!-- Main Content -->
    <div class="mr-64 min-h-screen">
        @yield('content')
    </div>

    <!-- Scripts -->
    <script src="https://code.jquery.com/jquery-3.6.0.min.js"></script>
    <script src="https://cdn.jsdelivr.net/npm/sweetalert2@11"></script>
    <script defer src="https://cdn.jsdelivr.net/npm/alpinejs@3.x.x/dist/cdn.min.js"></script>
    
    <script>
        // تهيئة AJAX
        $.ajaxSetup({
            headers: {
                'X-CSRF-TOKEN': $('meta[name="csrf-token"]').attr('content')
            }
        });
        
        // دالة عامة لإظهار النوافذ المنبثقة
        function showModal(modalId) {
            $('#' + modalId).removeClass('hidden').addClass('show');
        }
        
        // دالة عامة لإخفاء النوافذ المنبثقة
        function hideModal(modalId) {
            $('#' + modalId).removeClass('show').addClass('hidden');
        }
    </script>

    @stack('scripts')

    <!-- Flash Messages -->
    @if (session('success'))
    <script>
        Swal.fire({
            title: 'نجاح!',
            text: "{{ session('success') }}",
            icon: 'success',
            confirmButtonText: 'حسناً'
        });
    </script>
    @endif

    @if (session('error'))
    <script>
        Swal.fire({
            title: 'خطأ!',
            text: "{{ session('error') }}",
            icon: 'error',
            confirmButtonText: 'حسناً'
        });
    </script>
    @endif
</body>
</html>
