<!DOCTYPE html>
<html lang="ar" dir="rtl">
<head>
    <meta charset="UTF-8">
    <meta name="viewport" content="width=device-width, initial-scale=1.0">
    <meta name="csrf-token" content="{{ csrf_token() }}">
    <title>المستودع - نظام إدارة المخزون</title>
    
    <!-- Styles -->
    <script src="https://cdn.tailwindcss.com"></script>
    <link rel="stylesheet" href="https://cdnjs.cloudflare.com/ajax/libs/font-awesome/6.0.0/css/all.min.css">
    
    <style>
        @import url('https://fonts.googleapis.com/css2?family=Cairo:wght@400;600;700&display=swap');
        body {
            font-family: 'Cairo', sans-serif;
            background: #f3f4f6;
        }
        .sidebar-item {
            transition: all 0.3s ease;
        }
        .sidebar-item:hover {
            background-color: rgba(99, 102, 241, 0.1);
            transform: translateX(-4px);
        }
    </style>
    @stack('styles')
</head>
<body>
    <!-- Top Navigation Bar -->
    <nav class="nav-container shadow-lg fixed w-full top-0 z-50 bg-white">
        <div class="max-w-full mx-auto px-4">
            <div class="flex justify-between h-14">
                <div class="flex items-center space-x-4 space-x-reverse">
                    <div class="flex-shrink-0 flex items-center">
                        <div class="bg-indigo-100 p-2 rounded-full ml-2">
                            <i class="fas fa-warehouse text-indigo-600 text-xl"></i>
                        </div>
                        <span class="text-lg font-bold text-gray-800">الدعم الفني </span>
                    </div>
                    
                    <!-- Back to Dashboard Button -->
                    <a href="{{ route('dashboard') }}" 
                       class="nav-item flex items-center px-4 py-2 text-gray-700 hover:bg-gray-100 rounded-lg transition-all duration-200">
                        <i class="fas fa-arrow-right ml-2 text-indigo-600"></i>
                        <span>العودة للوحة التحكم</span>
                    </a>
                </div>
                
                <div class="flex items-center gap-6">
                    <!-- Time and Date -->
                    <div class="flex items-center text-sm text-gray-600 bg-gray-50 px-3 py-1.5 rounded-lg">
                        <i class="fas fa-calendar-alt ml-2 text-indigo-500"></i>
                        <span>{{ now()->format('H:i') }}</span>
                        <span class="mx-2 text-gray-300">|</span>
                        <span>{{ now()->format('Y-m-d') }}</span>
                    </div>

                    <!-- User Profile -->
                    <div class="flex items-center gap-3">
                        <span class="text-gray-700">مرحباً بك</span>
                        <div class="bg-indigo-100 w-10 h-10 rounded-full flex items-center justify-center">
                            <i class="fas fa-user text-indigo-600"></i>
                        </div>
                    </div>
                </div>
            </div>
        </div>
    </nav>

    <!-- Sidebar -->
    <aside class="fixed right-0 top-14 w-64 h-full bg-[#1e1b4b] text-white">
        <div class="flex items-center justify-center h-16 border-b border-indigo-900/30">
            <div class="flex items-center">
                <div class="bg-indigo-500/20 p-2 rounded-lg ml-2">
                    <i class="fas fa-warehouse text-indigo-300"></i>
                </div>
                <h1 class="text-xl font-bold text-gray-100">الدعم الفني</h1>
            </div>
        </div>
        <nav class="mt-6">
            <div class="px-4 space-y-2">
                <a href="{{ route('storehouse.index') }}" 
                   class="flex items-center text-gray-100 p-3 rounded-lg hover:bg-white/10 transition-all duration-300 group transform hover:-translate-x-1">
                    <div class="bg-indigo-500/20 p-2.5 rounded-lg ml-3 group-hover:bg-indigo-500/30 transition-colors duration-300">
                        <i class="fas fa-home text-indigo-300 text-lg"></i>
                    </div>
                    <span class="font-medium tracking-wide">الرئيسية</span>
                </a>

             



               

                


        </nav>
    </aside>

    <!-- Main Content -->
    <main class="mr-64 mt-14 p-6">
        @yield('content')
    </main>

    <!-- Scripts -->
    <script src="https://cdn.jsdelivr.net/npm/sweetalert2@11"></script>
    @stack('scripts')
</body>
</html>
