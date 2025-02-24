<!DOCTYPE html>
<html lang="ar" dir="rtl">
<head>
    <meta charset="UTF-8">
    <meta name="viewport" content="width=device-width, initial-scale=1.0">
    <title>لوحة التحكم - نظام إدارة الأجهزة وصل</title>
    <link href="https://fonts.googleapis.com/css2?family=Cairo:wght@200;300;400;500;600;700;800;900&display=swap" rel="stylesheet">
    <script src="https://cdn.tailwindcss.com"></script>
    <link rel="stylesheet" href="https://cdnjs.cloudflare.com/ajax/libs/font-awesome/6.0.0/css/all.min.css">
    <script src="https://cdn.jsdelivr.net/npm/chart.js"></script>
    <script defer src="https://unpkg.com/alpinejs@3.x.x/dist/cdn.min.js"></script>
    <style>
        body {
            font-family: 'Cairo', sans-serif;
            background: linear-gradient(135deg, #f3f4f6 0%, #e5e7eb 100%);
        }

        .nav-item {
            transition: all 0.3s ease;
            position: relative;
            overflow: hidden;
        }

        .nav-item:before {
            content: '';
            position: absolute;
            top: 0;
            right: 0;
            width: 4px;
            height: 100%;
            background: #6366f1;
        }

        .nav-item:hover:before {
            transform: translateX(4px);
        }

        .background {
            background: #6366f1;
        }

        .transform {
            transform: translateX(4px);
        }

        .nav-icon {
            transition: all 0.3s ease;
        }

        .nav-item:hover .nav-icon {
            transform: scale(1.1);
        }

        .nav-text {
            font-weight: 500;
            letter-spacing: 0.025em;
            transition: all 0.3s ease;
        }

        .nav-item:hover .nav-text {
            transform: translateX(-4px);
        }

        .section-title {
            position: relative;
            padding-right: 12px;
        }

        .section-title::before {
            content: '';
            position: absolute;
            right: 0;
            top: 50%;
            transform: translateY(-50%);
            width: 4px;
            height: 16px;
            background: #6366f1;
            border-radius: 2px;
        }

        .notification-badge {
            background: linear-gradient(135deg, #ef4444 0%, #dc2626 100%);
            box-shadow: 0 2px 4px rgba(239, 68, 68, 0.3);
        }

        .nav-container {
            background: rgba(255, 255, 255, 0.9);
            backdrop-filter: blur(10px);
        }

        .sidebar {
            background: linear-gradient(180deg, #1e1b4b 0%, #312e81 100%);
            backdrop-filter: blur(10px);
        }

        .nav-link {
            transition: all 0.3s ease;
        }

        .nav-link:hover {
            background: rgba(255, 255, 255, 0.1);
            transform: translateX(-4px);
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
                   <!-- زر المستودع -->
                   <a href="{{ route('boxes-under-manufacturing.index') }}" 
                   class="nav-item flex items-center px-4 py-2 text-gray-700 hover:bg-gray-100 rounded-lg transition-all duration-200">
                    <i class="fas fa-warehouse ml-2 text-indigo-600"></i>
                    <span>المستودع</span>
                </a>
                 <!-- زر الدعم الفني -->
                 <a href="{{ route('support.index') }}" 
                 class="nav-item flex items-center px-4 py-2 text-gray-700 hover:bg-gray-100 rounded-lg transition-all duration-200">
                  <i class="fas fa-headset ml-2 text-indigo-600"></i>
                  <span>الدعم الفني</span>
              </a>
                </div>
                
                <div class="flex items-center gap-6">
                    <!-- Time and Date -->
                    <div class="flex items-center gap-4">
                        <!-- Time Display -->
                        <div class="flex items-center text-sm bg-indigo-50 px-4 py-2 rounded-lg shadow-sm">
                            <i class="fas fa-clock text-indigo-600 text-lg ml-2"></i>
                            <div class="flex flex-col">
                                <span class="font-semibold text-gray-800">{{ now()->format('H:i:s') }}</span>
                                <span class="text-xs text-gray-500">توقيت مكة المكرمة</span>
                            </div>
                        </div>
                        
                        <!-- Date Display -->
                        <div class="flex items-center text-sm bg-emerald-50 px-4 py-2 rounded-lg shadow-sm">
                            <i class="fas fa-calendar-alt text-emerald-600 text-lg ml-2"></i>
                            <div class="flex flex-col">
                                <span class="font-semibold text-gray-800" dir="ltr">{{ now()->translatedFormat('Y-m-d') }}</span>
                                <span class="text-xs text-gray-500">{{ now()->translatedFormat('l') }}</span>
                            </div>
                        </div>
                    </div>

                    <!-- Notifications -->
                    <div class="relative" x-data="{ open: false }">
                        <button @click="open = !open" class="relative p-2 text-gray-600 hover:bg-indigo-50 rounded-full focus:outline-none focus:ring-2 focus:ring-indigo-500 transition-all duration-200">
                            <i class="fas fa-bell text-lg"></i>
                            <span class="absolute -top-1 -right-1 bg-red-500 text-white text-xs rounded-full h-4 w-4 flex items-center justify-center">
                                {{ \App\Models\Notification::where('is_read', false)->count() }}
                            </span>
                        </button>
                        <div x-show="open" @click.away="open = false" class="absolute left-0 mt-2 w-96 bg-white rounded-lg shadow-lg overflow-hidden border border-gray-100">
                            <div class="p-3 bg-indigo-50 border-b border-indigo-100">
                                <div class="flex items-center justify-between">
                                    <h3 class="text-sm font-bold text-indigo-900">الإشعارات</h3>
                                    <span class="text-xs text-indigo-600 bg-indigo-100 px-2 py-1 rounded-full">
                                        {{ \App\Models\Notification::where('is_read', false)->count() }} غير مقروء
                                    </span>
                                </div>
                            </div>
                            <div class="max-h-96 overflow-y-auto">
                                @foreach(\App\Models\Notification::with(['device', 'user'])->latest()->take(5)->get() as $notification)
                                <div class="p-4 border-b hover:bg-gray-50 {{ !$notification->is_read ? 'bg-indigo-50' : '' }} transition-colors duration-200">
                                    <div class="flex items-start space-x-3 space-x-reverse">
                                        <!-- Notification Icon based on type -->
                                        <div class="flex-shrink-0">
                                            @if($notification->type == 'maintenance')
                                                <div class="w-10 h-10 bg-blue-100 rounded-full flex items-center justify-center">
                                                    <i class="fas fa-tools text-blue-600"></i>
                                                </div>
                                            @else
                                                <div class="w-10 h-10 bg-gray-100 rounded-full flex items-center justify-center">
                                                    <i class="fas fa-bell text-gray-600"></i>
                                                </div>
                                            @endif
                                        </div>
                                        
                                        <div class="flex-1 min-w-0">
                                            <div class="flex items-center justify-between">
                                                <p class="text-sm font-medium text-gray-900">
                                                    {{ $notification->message }}
                                                </p>
                                                @if(!$notification->is_read)
                                                    <span class="inline-flex items-center px-2 py-0.5 rounded-full text-xs font-medium bg-indigo-100 text-indigo-800">
                                                        جديد
                                                    </span>
                                                @endif
                                            </div>
                                            <div class="mt-1 flex items-center justify-between">
                                                <div class="flex items-center text-xs text-gray-500">
                                                    @if($notification->device)
                                                        <span class="ml-2">
                                                            <i class="fas fa-microchip ml-1"></i>
                                                            {{ $notification->device->device_name }}
                                                        </span>
                                                    @endif
                                                    @if($notification->user)
                                                        <span>
                                                            <i class="fas fa-user ml-1"></i>
                                                            {{ $notification->user->name }}
                                                        </span>
                                                    @endif
                                                </div>
                                                <span class="text-xs text-gray-500" title="{{ $notification->created_at }}">
                                                    {{ $notification->created_at->diffForHumans() }}
                                                </span>
                                            </div>
                                        </div>
                                    </div>
                                </div>
                                @endforeach
                            </div>
                            @if(\App\Models\Notification::count() > 5)
                                <a href="{{ route('notifications.index') }}" class="block text-center py-3 text-sm text-indigo-600 bg-gray-50 hover:bg-gray-100 transition-colors duration-200 font-medium">
                                    عرض كل الإشعارات ({{ \App\Models\Notification::count() }})
                                </a>
                            @endif
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
                            <i class="fas fa-boxes text-indigo-300 text-lg"></i>
                        </div>
                        <span class="font-medium tracking-wide">الصناديق</span>
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
                    <a href="{{ route('subusers.index') }}" class="flex items-center text-gray-100 p-3 rounded-lg hover:bg-white/10 transition-all duration-300 group transform hover:-translate-x-1">
                        <div class="bg-indigo-500/20 p-2.5 rounded-lg ml-3 group-hover:bg-indigo-500/30 transition-colors duration-300">
                            <i class="fas fa-users-cog text-indigo-300 text-lg"></i>
                        </div>
                        <span class="font-medium tracking-wide">المستخدمين الفرعيين</span>
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
          {{-- 
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
--}}


                
                </li>
                    <!-- خريطة الصناديق -->
                    <li>
                    <a href="{{ route('map.index') }}" class="flex items-center text-gray-100 p-3 rounded-lg hover:bg-white/10 transition-all duration-300 group transform hover:-translate-x-1">
                        <div class="bg-indigo-500/20 p-2.5 rounded-lg ml-3 group-hover:bg-indigo-500/30 transition-colors duration-300">
                            <i class="fas fa-map-marker-alt text-indigo-300 text-lg"></i>
                        </div>
                        <span class="font-medium tracking-wide">خريطة الصناديق</span>
                    </a>
                </li>
                <li>
                    <a href="{{ route('maintenance.index') }}" class="flex items-center text-gray-100 p-3 rounded-lg hover:bg-white/10 transition-all duration-300 group transform hover:-translate-x-1">
                        <div class="bg-indigo-500/20 p-2.5 rounded-lg ml-3 group-hover:bg-indigo-500/30 transition-colors duration-300">
                            <i class="fas fa-tools text-indigo-300 text-lg"></i>
                        </div>
                        <span class="font-medium tracking-wide">الصيانة</span>
                    </a>
                </li>
            </ul>
        </nav>
    </div>

    <!-- Main Content -->
    <div class="mr-64 p-8">
        <!-- Header -->
        <div class="bg-white rounded-lg shadow-md p-6 mb-6 border border-gray-100">
            <div class="flex justify-between items-center">
                <div>
                    <div class="flex items-center space-x-3 space-x-reverse mb-1">
                        <div class="bg-indigo-100 p-2 rounded-lg">
                            <i class="fas fa-tachometer-alt text-indigo-600 text-xl"></i>
                        </div>
                        <h2 class="text-xl font-bold text-gray-800">لوحة التحكم</h2>
                    </div>
                    <p class="text-sm text-gray-600 flex items-center">
                        <div class="bg-gray-100 p-1 rounded-md ml-2">
                            <i class="fas fa-user-clock text-gray-500"></i>
                        </div>
                        اخر تسجيل دخول: {{ now()->format('Y-m-d H:i:s') }}
                    </p>
                </div>
                
                <!-- Date Filter Form -->
                <div class="bg-white rounded-xl shadow-sm border border-gray-100 p-4">
                    <form action="{{ route('dashboard') }}" method="GET" class="space-y-4" id="dateFilterForm">
                        <div class="flex flex-wrap gap-4 items-end">
                            <!-- Filter Type Selection -->
                            <div class="flex-1 min-w-[200px]">
                                <label class="block text-sm font-semibold text-gray-700 mb-2 flex items-center">
                                    <i class="fas fa-filter text-indigo-500 ml-2"></i>
                                    نطاق التاريخ
                                </label>
                                <div class="relative">
                                    <select name="date_filter" id="dateFilter" 
                                            class="block w-full rounded-lg border-gray-200 bg-gray-50 pr-10 py-2.5 text-sm transition duration-150 ease-in-out
                                                   hover:bg-gray-100 focus:border-indigo-500 focus:ring-indigo-500">
                                        <option value="today" {{ request('date_filter') == 'today' ? 'selected' : '' }}>اليوم</option>
                                        <option value="week" {{ request('date_filter') == 'week' ? 'selected' : '' }}>الأسبوع</option>
                                        <option value="month" {{ request('date_filter') == 'month' ? 'selected' : '' }}>الشهر</option>
                                        <option value="year" {{ request('date_filter') == 'year' ? 'selected' : '' }}>السنة</option>
                                        <option value="custom" {{ request('date_filter') == 'custom' ? 'selected' : '' }}>مخصص</option>
                                    </select>
                                    <div class="pointer-events-none absolute inset-y-0 left-0 flex items-center pl-3">
                                        <i class="fas fa-calendar text-gray-400"></i>
                                    </div>
                                </div> 
                            </div>
                            
                            <!-- Custom Date Range -->
                            <div class="custom-date-inputs {{ request('date_filter') == 'custom' ? 'flex-1' : 'hidden' }} min-w-[300px]">
                                <div class="grid grid-cols-2 gap-4">
                                    <div>
                                        <label class="block text-sm font-semibold text-gray-700 mb-2 flex items-center">
                                            <i class="fas fa-calendar-alt text-indigo-500 ml-2"></i>
                                            من
                                        </label>
                                        <div class="relative">
                                            <input type="date" name="start_date" value="{{ request('start_date') }}" 
                                                   class="block w-full rounded-lg border-gray-200 bg-gray-50 pr-10 py-2.5 text-sm
                                                          hover:bg-gray-100 focus:border-indigo-500 focus:ring-indigo-500">
                                            <div class="pointer-events-none absolute inset-y-0 left-0 flex items-center pl-3">
                                                <i class="fas fa-calendar-day text-gray-400"></i>
                                            </div>
                                        </div>
                                    </div>
                                    <div>
                                        <label class="block text-sm font-semibold text-gray-700 mb-2 flex items-center">
                                            <i class="fas fa-calendar-alt text-indigo-500 ml-2"></i>
                                            إلى
                                        </label>
                                        <div class="relative">
                                            <input type="date" name="end_date" value="{{ request('end_date') }}"
                                                   class="block w-full rounded-lg border-gray-200 bg-gray-50 pr-10 py-2.5 text-sm
                                                          hover:bg-gray-100 focus:border-indigo-500 focus:ring-indigo-500">
                                            <div class="pointer-events-none absolute inset-y-0 left-0 flex items-center pl-3">
                                                <i class="fas fa-calendar-day text-gray-400"></i>
                                            </div>
                                        </div>
                                    </div>
                                </div>
                            </div>
                            
                            <!-- Submit Button -->
                            <div>
                                <button type="submit" 
                                        class="inline-flex items-center px-6 py-2.5 rounded-lg text-sm font-medium text-white
                                               bg-gradient-to-r from-indigo-600 to-indigo-500 hover:from-indigo-700 hover:to-indigo-600
                                               transition-all duration-200 shadow-sm hover:shadow-md focus:ring-2 focus:ring-offset-2 focus:ring-indigo-500">
                                    <i class="fas fa-search ml-2"></i>
                                    تطبيق الفلتر
                                </button>
                            </div>
                        </div>
                    </form>
                </div>
           
            </div>
        </div>

        <!-- Stats Grid -->
        <div class="grid grid-cols-1 md:grid-cols-2 lg:grid-cols-4 gap-6 mb-6">
            <div class="bg-white rounded-lg shadow-sm p-6 border border-gray-100 hover:shadow-md transition-all duration-300">
                <div class="flex items-center">
                    <div class="p-3 rounded-lg bg-gradient-to-br from-indigo-500 to-indigo-600 shadow-lg">
                        <i class="fas fa-microchip text-white text-2xl"></i>
                    </div>
                    <div class="mr-4">
                        <h3 class="text-gray-500 text-sm font-medium">إجمالي الصناديق</h3>
                        <p class="text-2xl font-bold text-gray-800">{{ $stats['total_devices'] }}</p>
                    </div>
                </div>
            </div>
            
           
            
            <div class="bg-white rounded-lg shadow-sm p-6 border border-gray-100 hover:shadow-md transition-all duration-300">
                <div class="flex items-center">
                    <div class="p-3 rounded-lg bg-gradient-to-br from-amber-500 to-amber-600 shadow-lg">
                        <i class="fas fa-calendar-day text-white text-2xl"></i>
                    </div>
                    <div class="mr-4">
                        <h3 class="text-gray-500 text-sm font-medium">التقاريــر  </h3>
                        <p class="text-2xl font-bold text-gray-800">{{ $stats['today_events'] }}</p>
                    </div>
                </div>
            </div>
            
            <div class="bg-white rounded-lg shadow-sm p-6 border border-gray-100 hover:shadow-md transition-all duration-300">
                <div class="flex items-center">
                    <div class="p-3 rounded-lg bg-gradient-to-br from-violet-500 to-violet-600 shadow-lg">
                        <i class="fas fa-users text-white text-2xl"></i>
                    </div>
                    <div class="mr-4">
                        <h3 class="text-gray-500 text-sm font-medium">إجمالي المستخدمين</h3>
                        <p class="text-2xl font-bold text-gray-800">{{ $stats['total_users'] }}</p>
                    </div>
                </div>
            </div>

            <div class="bg-white rounded-lg shadow-sm p-6 border border-gray-100 hover:shadow-md transition-all duration-300">
                <div class="flex items-center">
                    <div class="p-3 rounded-lg bg-gradient-to-br from-red-500 to-red-600 shadow-lg">
                        <i class="fas fa-bell text-white text-2xl"></i>
                    </div>
                    <div class="mr-4">
                        <h3 class="text-gray-500 text-sm font-medium">الإشعارات غير المقروءة</h3>
                        <p class="text-2xl font-bold text-gray-800">{{ $stats['unread_notifications'] }}</p>
                    </div>
                </div>
            </div>
        </div>

        <!-- Charts Grid -->
        <div class="grid grid-cols-1 md:grid-cols-2 gap-6 mb-6">
            <!-- Device Status Chart -->
            <div class="bg-white rounded-lg shadow-sm p-4 border border-gray-100">
                <div class="mb-4">
                    <h3 class="text-sm font-bold mb-3 text-gray-700 flex items-center justify-between">
                        <div class="flex items-center">
                            <div class="bg-green-100 p-1.5 rounded-md ml-2">
                                <i class="fas fa-chart-line text-green-600"></i>
                            </div>
                            مؤشر المبيعات
                        </div>
                        <!-- إحصائيات سريعة -->
                        <div class="flex items-center space-x-4 space-x-reverse text-xs">
                            <div class="bg-gray-50 p-2 rounded-lg">
                                <span class="text-gray-600">إجمالي الصناديق:</span>
                                <span class="font-bold text-gray-800 mr-1">{{ array_sum($salesGrowth->pluck('count')->toArray()) }}</span>
                            </div>
                            <div class="bg-gray-50 p-2 rounded-lg">
                                <span class="text-gray-600">معدل النمو اليومي:</span>
                                <span class="font-bold {{ round(array_sum($salesGrowth->pluck('count')->toArray()) / max(1, $salesGrowth->count()), 1) > 0 ? 'text-green-600' : 'text-red-600' }} mr-1">
                                    {{ round(array_sum($salesGrowth->pluck('count')->toArray()) / max(1, $salesGrowth->count()), 1) }}
                                </span>
                            </div>
                            <div class="bg-gray-50 p-2 rounded-lg">
                                <span class="text-gray-600">أعلى قيمة:</span>
                                <span class="font-bold text-blue-600 mr-1">{{ max($salesGrowth->pluck('count')->toArray()) }}</span>
                            </div>
                        </div>
                    </h3>
                    <!-- شريط المؤشرات -->
                    <div class="grid grid-cols-3 gap-4 mb-3">
                        <div class="flex items-center p-2 bg-green-50 rounded-lg">
                            <i class="fas fa-arrow-trend-up text-green-500 ml-2"></i>
                            <div>
                                <div class="text-xs text-gray-600">نمو إيجابي</div>
                                <div class="font-bold text-green-600">
                                    {{ $salesGrowth->where('count', '>', 0)->count() }} أيام
                                </div>
                            </div>
                        </div>
                        <div class="flex items-center p-2 bg-blue-50 rounded-lg">
                            <i class="fas fa-equals text-blue-500 ml-2"></i>
                            <div>
                                <div class="text-xs text-gray-600">ثبات</div>
                                <div class="font-bold text-blue-600">
                                    {{ $salesGrowth->where('count', '=', 0)->count() }} أيام
                                </div>
                            </div>
                        </div>
                        <div class="flex items-center p-2 bg-red-50 rounded-lg">
                            <i class="fas fa-arrow-trend-down text-red-500 ml-2"></i>
                            <div>
                                <div class="text-xs text-gray-600">انخفاض</div>
                                <div class="font-bold text-red-600">
                                    {{ $salesGrowth->where('count', '<', 0)->count() }} أيام
                                </div>
                            </div>
                        </div>
                    </div>
                </div>

                <!-- الرسم البياني -->
                <div style="height: 300px;">
                    <canvas id="salesChart"></canvas>
                </div>
            </div>

            <!-- قسم أحداث الفترة -->
            <div class="bg-white rounded-lg shadow-sm p-4 border border-gray-100">
                <h3 class="text-sm font-bold mb-3 text-gray-700 flex items-center justify-between">
                    <div class="flex items-center">
                        <div class="bg-blue-100 p-1.5 rounded-md ml-2">
                            <i class="fas fa-chart-line text-blue-600"></i>
                        </div>
                        أحداث الفترة
                    </div>
                    <!-- إحصائيات سريعة -->
                    <div class="flex items-center space-x-4 space-x-reverse text-xs">
                        <div class="bg-gray-50 p-2 rounded-lg">
                            <span class="text-gray-600">إجمالي الأحداث:</span>
                            <span class="font-bold text-gray-800 mr-1">
                                {{ !empty($eventTypes) ? array_sum($eventTypes) : 0 }}
                            </span>
                        </div>
                        <div class="bg-gray-50 p-2 rounded-lg">
                            <span class="text-gray-600">أنواع:</span>
                            <span class="font-bold text-blue-600 mr-1">
                                {{ !empty($eventTypes) ? count($eventTypes) : 0 }}
                            </span>
                        </div>
                    </div>
                </h3>
                
                <!-- مؤشرات الأداء -->
                <div class="grid grid-cols-4 gap-3 mb-4">
                    <div class="bg-green-50 p-2 rounded-lg">
                        <div class="text-xs text-gray-600">أيام النشاط المرتفع</div>
                        <div class="font-bold text-green-600">
                            {{ count(array_filter($periodEvents, fn($count) => $count > array_sum($periodEvents) / count($periodEvents))) }}
                        </div>
                    </div>
                    <div class="bg-yellow-50 p-2 rounded-lg">
                        <div class="text-xs text-gray-600">أيام النشاط المتوسط</div>
                        <div class="font-bold text-yellow-600">
                            {{ count(array_filter($periodEvents, fn($count) => $count == array_sum($periodEvents) / count($periodEvents))) }}
                        </div>
                    </div>
                    <div class="bg-red-50 p-2 rounded-lg">
                        <div class="text-xs text-gray-600">أيام النشاط المنخفض</div>
                        <div class="font-bold text-red-600">
                            {{ count(array_filter($periodEvents, fn($count) => $count < array_sum($periodEvents) / count($periodEvents))) }}
                        </div>
                    </div>
                    <div class="bg-blue-50 p-2 rounded-lg">
                        <div class="text-xs text-gray-600">معدل النمو</div>
                        <div class="font-bold text-blue-600">
                            {{ round((end($periodEvents) - reset($periodEvents)) / max(1, reset($periodEvents)) * 100, 1) }}%
                        </div>
                    </div>
                </div>

                <canvas id="periodEventsChart" class="w-full"></canvas>
            </div>

            <!-- Events by Type Chart -->
            <div class="bg-white rounded-lg shadow-sm p-4 border border-gray-100">
                <h3 class="text-sm font-bold mb-3 text-gray-700 flex items-center justify-between">
                    <div class="flex items-center">
                        <div class="bg-amber-100 p-1.5 rounded-md ml-2">
                            <i class="fas fa-chart-bar text-amber-600"></i>
                        </div>
                        توزيع الأحداث حسب النوع
                    </div>
                    <!-- إحصائيات سريعة -->
                    <div class="flex items-center space-x-4 space-x-reverse text-xs">
                        <div class="bg-gray-50 p-2 rounded-lg">
                            <span class="text-gray-600">إجمالي الأحداث:</span>
                            <span class="font-bold text-gray-800 mr-1">{{ array_sum($eventTypes) }}</span>
                        </div>
                        <div class="bg-gray-50 p-2 rounded-lg">
                            <span class="text-gray-600">أكثر نوع:</span>
                            <span class="font-bold text-blue-600 mr-1">{{ array_search(max($eventTypes), $eventTypes) }}</span>
                        </div>
                    </div>
                </h3>

                <div class="grid grid-cols-1 md:grid-cols-2 gap-4 mb-4">
                    <!-- الرسم البياني -->
                    <div>
                        <canvas id="eventsByTypeChart" height="200"></canvas>
                    </div>
                    <!-- تفاصيل الأحداث -->
                    <div class="bg-gray-50 p-4 rounded-lg">
                        <h4 class="text-sm font-bold mb-3 text-gray-700">تفاصيل الأحداث</h4>
                        <div class="space-y-2">
                            @if(isset($eventTypes) && is_array($eventTypes) && count($eventTypes) > 0)
                                @php
                                    $totalEvents = array_sum($eventTypes);
                                    $colors = ['#3B82F6', '#10B981', '#F59E0B', '#8B5CF6', '#EF4444', '#EC4899', '#6366F1'];
                                @endphp
                                
                                @foreach($eventTypes as $type => $count)
                                    <div class="flex items-center justify-between p-2 bg-white rounded-lg">
                                        <div class="flex items-center">
                                            <div class="w-3 h-3 rounded-full mr-2" style="background-color: {{ $colors[$loop->index % max(1, count($colors))] }}"></div>
                                            <span class="text-sm text-gray-700">{{ $type }}</span>
                                        </div>
                                        <div class="flex items-center">
                                            <span class="text-sm font-bold text-gray-800">{{ $count }}</span>
                                            <span class="text-xs text-gray-500 mr-2">
                                                (@if($totalEvents > 0)
                                                    {{ round(($count/$totalEvents)*100, 1) }}
                                                @else
                                                    0
                                                @endif%)
                                            </span>
                                        </div>
                                    </div>
                                @endforeach
                            @else
                                <div class="text-center text-gray-500 py-4">
                                    <i class="fas fa-info-circle mb-2"></i>
                                    <p>لا توجد أحداث لعرضها حالياً</p>
                                </div>
                            @endif
                        </div>
                    </div>
                </div>
            </div>

            <!-- Notifications by Type Chart -->
     

 

        <!-- Latest Events Table -->
        <div class="bg-white rounded-lg shadow-sm p-6 border border-gray-100">
            <h3 class="text-lg font-bold mb-4 text-gray-800 flex items-center">
                <div class="bg-indigo-100 p-2 rounded-md ml-2">
                    <i class="fas fa-history text-indigo-600"></i>
                </div>
                آخر الأحداث
            </h3>
            <div class="overflow-x-auto">
                <table class="min-w-full divide-y divide-gray-200">
                    <thead>
                        <tr>
                            <th class="px-6 py-3 bg-gray-50 text-right text-xs font-medium text-gray-500 uppercase tracking-wider rounded-tr-lg">الجهاز</th>
                            <th class="px-6 py-3 bg-gray-50 text-right text-xs font-medium text-gray-500 uppercase tracking-wider">نوع الحدث</th>
                            <th class="px-6 py-3 bg-gray-50 text-right text-xs font-medium text-gray-500 uppercase tracking-wider">طريقة التنفيذ</th>
                            <th class="px-6 py-3 bg-gray-50 text-right text-xs font-medium text-gray-500 uppercase tracking-wider rounded-tl-lg">التوقيت</th>
                        </tr>
                    </thead>
                    <tbody class="bg-white divide-y divide-gray-200">
                        @foreach($latestEvents as $event)
                        <tr class="hover:bg-gray-50 transition-colors duration-200">
                            <td class="px-6 py-4 whitespace-nowrap text-sm text-gray-900">
                                <div class="flex items-center">
                                    <div class="bg-indigo-100 p-1.5 rounded-md ml-2">
                                        <i class="fas fa-laptop text-indigo-600"></i>
                                    </div>
                                    {{ $event->device->device_name }}
                                </div>
                            </td>
                            <td class="px-6 py-4 whitespace-nowrap text-sm text-gray-900">
                                <div class="flex items-center">
                                    <div class="bg-emerald-100 p-1.5 rounded-md ml-2">
                                        <i class="fas fa-tag text-emerald-600"></i>
                                    </div>
                                    {{ $event->event_type }}
                                </div>
                            </td>
                            <td class="px-6 py-4 whitespace-nowrap text-sm text-gray-900">
                                <div class="flex items-center">
                                    <div class="bg-amber-100 p-1.5 rounded-md ml-2">
                                        <i class="fas fa-code-branch text-amber-600"></i>
                                    </div>
                                    {{ $event->method_type }}
                                </div>
                            </td>
                            <td class="px-6 py-4 whitespace-nowrap text-sm text-gray-900">
                                <div class="flex items-center">
                                    <div class="bg-violet-100 p-1.5 rounded-md ml-2">
                                        <i class="fas fa-clock text-violet-600"></i>
                                    </div>
                                    {{ Carbon\Carbon::parse($event->timestamp)->format('Y-m-d H:i:s') }}
                                </div>
                            </td>
                        </tr>
                        @endforeach
                    </tbody>
                </table>
            </div>
        </div>

        <!-- Sales Growth Chart -->
      
    </div>

    <script>
        // تحديث الألوان المتدرجة
        const colors = {
            indigo: {
                primary: '#4F46E5',
                secondary: '#6366F1',
                light: '#E0E7FF'
            },
            emerald: {
                primary: '#059669',
                secondary: '#10B981',
                light: '#D1FAE5'
            },
            amber: {
                primary: '#D97706',
                secondary: '#F59E0B',
                light: '#FEF3C7'
            },
            violet: {
                primary: '#7C3AED',
                secondary: '#8B5CF6',
                light: '#EDE9FE'
            },
            blue: {
                primary: '#2563EB',
                secondary: '#3B82F6',
                light: '#DBEAFE'
            }
        };

        // تحديث التدرجات اللونية
        const gradients = {
            indigo: 'linear-gradient(135deg, #4F46E5, #6366F1)',
            emerald: 'linear-gradient(135deg, #059669, #10B981)',
            amber: 'linear-gradient(135deg, #D97706, #F59E0B)',
            violet: 'linear-gradient(135deg, #7C3AED, #8B5CF6)'
        };

        // إعداد الرسم البياني للمبيعات
        const salesCtx = document.getElementById('salesChart').getContext('2d');
        new Chart(salesCtx, {
            type: 'line',
            data: {
                labels: {!! json_encode($salesGrowth->pluck('date')) !!},
                datasets: [{
                    label: 'عدد الصناديق',
                    data: {!! json_encode($salesGrowth->pluck('count')) !!},
                    borderColor: '#10B981',
                    backgroundColor: 'rgba(16, 185, 129, 0.1)',
                    borderWidth: 2,
                    fill: true,
                    tension: 0.4,
                    pointBackgroundColor: '#10B981',
                    pointBorderColor: '#fff',
                    pointBorderWidth: 2,
                    pointRadius: 4,
                    pointHoverRadius: 6
                }]
            },
            options: {
                responsive: true,
                maintainAspectRatio: true,
                aspectRatio: 2,
                plugins: {
                    legend: {
                        display: true,
                        position: 'top',
                        align: 'start',
                        rtl: true,
                        labels: {
                            boxWidth: 40,
                            padding: 15,
                            font: {
                                family: 'Cairo',
                                size: 12
                            }
                        }
                    },
                    tooltip: {
                        backgroundColor: 'rgba(255, 255, 255, 0.9)',
                        titleColor: '#1F2937',
                        bodyColor: '#1F2937',
                        borderColor: '#E5E7EB',
                        borderWidth: 1,
                        padding: 12,
                        rtl: true,
                        titleFont: {
                            family: 'Cairo',
                            size: 14,
                            weight: 'bold'
                        },
                        bodyFont: {
                            family: 'Cairo',
                            size: 13
                        }
                    }
                },
                scales: {
                    y: {
                        beginAtZero: true,
                        grid: {
                            color: '#F3F4F6',
                            drawBorder: false
                        },
                        ticks: {
                            maxTicksLimit: 5,
                            padding: 10,
                            font: {
                                family: 'Cairo',
                                size: 11
                            }
                        }
                    },
                    x: {
                        grid: {
                            display: false
                        },
                        ticks: {
                            maxTicksLimit: 7,
                            padding: 10,
                            font: {
                                family: 'Cairo',
                                size: 11
                            }
                        }
                    }
                },
                layout: {
                    padding: {
                        left: 10,
                        right: 10,
                        top: 20,
                        bottom: 10
                    }
                }
            }
        });

        // تحديث إعدادات الرسم البياني
        new Chart(document.getElementById('periodEventsChart').getContext('2d'), {
            type: 'line',
            data: {
                labels: {!! json_encode(array_keys($periodEvents)) !!},
                datasets: [{
                    label: 'عدد الأحداث',
                    data: {!! json_encode(array_values($periodEvents)) !!},
                    borderColor: '#2563eb',
                    backgroundColor: '#93c5fd44',
                    fill: true,
                    tension: 0.4,
                    pointRadius: 4,
                    pointHoverRadius: 6,
                    pointBackgroundColor: '#2563eb'
                }]
            },
            options: {
                responsive: true,
                maintainAspectRatio: true,
                aspectRatio: 2.5,
                plugins: {
                    legend: {
                        display: false
                    },
                    tooltip: {
                        backgroundColor: '#1e293b',
                        titleFont: {
                            family: 'Cairo',
                            size: 13
                        },
                        bodyFont: {
                            family: 'Cairo',
                            size: 12
                        },
                        padding: 12,
                        displayColors: false,
                        callbacks: {
                            title: function(tooltipItems) {
                                return 'التاريخ: ' + tooltipItems[0].label;
                            },
                            label: function(context) {
                                let label = 'عدد الأحداث: ' + context.raw;
                                let avg = array_sum($periodEvents) / count($periodEvents);
                                if (context.raw > avg) {
                                    label += ' (↗️ نشاط مرتفع)';
                                } else if (context.raw < avg) {
                                    label += ' (↘️ نشاط منخفض)';
                                } else {
                                    label += ' (↔️ نشاط متوسط)';
                                }
                                return label;
                            }
                        }
                    }
                },
                scales: {
                    y: {
                        beginAtZero: true,
                        grid: {
                            color: '#e2e8f0'
                        },
                        ticks: {
                            font: {
                                family: 'Cairo',
                                size: 11
                            },
                            padding: 8,
                            callback: function(value) {
                                return value + ' حدث';
                            }
                        },
                        title: {
                            display: true,
                            text: 'عدد الأحداث',
                            font: {
                                family: 'Cairo',
                                size: 12,
                                weight: 'bold'
                            }
                        }
                    },
                    x: {
                        grid: {
                            display: false
                        },
                        ticks: {
                            font: {
                                family: 'Cairo',
                                size: 11
                            },
                            maxRotation: 45,
                            minRotation: 45
                        }
                    }
                }
            }
        });

        // رسم بياني لتوزيع الأحداث
        document.addEventListener('DOMContentLoaded', function() {
            const eventTypes = @json($eventTypes ?? []);
            const labels = Object.keys(eventTypes);
            const data = Object.values(eventTypes);
            
            const colors = {
                'البطارية': 'rgb(59, 130, 246)',        // أزرق
                'الباب مفتوح': 'rgb(16, 185, 129)',     // أخضر
                'فتح الباب': 'rgb(245, 158, 11)',       // برتقالي
                'الإنترنت': 'rgb(139, 92, 246)',        // بنفسجي
                'حالة القفل': 'rgb(239, 68, 68)',       // أحمر
                'الصيانة': 'rgb(236, 72, 153)',         // وردي
                'محاولة عبث': 'rgb(107, 114, 128)'      // رمادي
            };

            const ctx = document.getElementById('eventsByTypeChart').getContext('2d');
            new Chart(ctx, {
                type: 'bar',
                data: {
                    labels: labels.length > 0 ? labels : ['لا توجد بيانات'],
                    datasets: [{
                        data: data.length > 0 ? data : [0],
                        backgroundColor: labels.map(label => colors[label] ? `${colors[label]}80` : 'rgba(59, 130, 246, 0.5)'),
                        borderColor: labels.map(label => colors[label] || 'rgb(59, 130, 246)'),
                        borderWidth: 1
                    }]
                },
                options: {
                    responsive: true,
                    maintainAspectRatio: false,
                    plugins: {
                        legend: {
                            display: false
                        },
                        tooltip: {
                            callbacks: {
                                label: function(context) {
                                    return `عدد الأحداث: ${context.raw}`;
                                }
                            }
                        }
                    },
                    scales: {
                        y: {
                            beginAtZero: true,
                            ticks: {
                                stepSize: 1
                            }
                        }
                    }
                }
            });
        });

        // رسم بياني لتوزيع الإشعارات
        new Chart(document.getElementById('notificationsByTypeChart').getContext('2d'), {
            type: 'polarArea',
            data: {
                labels: {!! json_encode(array_keys($notificationTypes)) !!},
                datasets: [{
                    data: {!! json_encode(array_values($notificationTypes)) !!},
                    backgroundColor: [
                        colors.blue.primary,
                        colors.emerald.primary,
                        colors.amber.primary,
                        colors.violet.primary,
                        colors.indigo.primary
                    ]
                }]
            },
            options: {
                responsive: true,
                maintainAspectRatio: true,
                aspectRatio: 2,
                plugins: {
                    legend: {
                        position: 'bottom',
                        rtl: true,
                        labels: {
                            font: {
                                family: 'system-ui',
                                size: 9
                            },
                            padding: 4,
                            boxWidth: 10
                        }
                    }
                }
            }
        });

        // رسم بياني لنشاط الأجهزة خلال اليوم
        new Chart(document.getElementById('activityHeatmap').getContext('2d'), {
            type: 'bar',
            data: {
                labels: Array.from({length: 24}, (_, i) => `${i}:00`),
                datasets: [{
                    label: 'عدد الأحداث',
                    data: {!! json_encode(array_replace(array_fill(0, 24, 0), $hourlyActivity)) !!},
                    backgroundColor: colors.blue.primary + '80',
                    borderColor: colors.blue.primary,
                    borderWidth: 1
                }]
            },
            options: {
                responsive: true,
                maintainAspectRatio: true,
                aspectRatio: 3,
                plugins: {
                    legend: {
                        display: false
                    }
                },
                scales: {
                    y: {
                        beginAtZero: true,
                        ticks: {
                            font: {
                                size: 8
                            },
                            maxTicksLimit: 3
                        },
                        grid: {
                            display: false
                        }
                    },
                    x: {
                        ticks: {
                            font: {
                                size: 8
                            },
                            maxTicksLimit: 12
                        },
                        grid: {
                            display: false
                        }
                    }
                }
            }
        });

        // إضافة سكربت للتحكم في حقول التاريخ المخصص
        document.getElementById('dateFilter').addEventListener('change', function() {
            const customInputs = document.querySelector('.custom-date-inputs');
            if (this.value === 'custom') {
                customInputs.classList.remove('hidden');
            } else {
                customInputs.classList.add('hidden');
            }
        });

        // Filter Functionality
        document.addEventListener('DOMContentLoaded', function() {
            const dateFilter = document.getElementById('dateFilter');
            const customDateInputs = document.querySelector('.custom-date-inputs');
            
            // Animate custom date inputs visibility
            dateFilter.addEventListener('change', function() {
                if (this.value === 'custom') {
                    customDateInputs.style.display = 'block';
                    customDateInputs.classList.remove('hidden');
                    customDateInputs.classList.add('animate-fade-in');
                } else {
                    customDateInputs.classList.add('hidden');
                    customDateInputs.classList.remove('animate-fade-in');
                }
            });

            // Add hover effects to filter inputs
            const filterInputs = document.querySelectorAll('#dateFilterForm input, #dateFilterForm select');
            filterInputs.forEach(input => {
                input.addEventListener('mouseover', function() {
                    this.classList.add('shadow-sm');
                });
                input.addEventListener('mouseout', function() {
                    this.classList.remove('shadow-sm');
                });
            });

            // Validate date range
            const startDate = document.querySelector('input[name="start_date"]');
            const endDate = document.querySelector('input[name="end_date"]');
            const submitButton = document.querySelector('#dateFilterForm button[type="submit"]');

            function validateDates() {
                if (dateFilter.value === 'custom' && startDate.value && endDate.value) {
                    if (new Date(startDate.value) > new Date(endDate.value)) {
                        submitButton.disabled = true;
                        submitButton.classList.add('opacity-50', 'cursor-not-allowed');
                        startDate.classList.add('border-red-500');
                        endDate.classList.add('border-red-500');
                    } else {
                        submitButton.disabled = false;
                        submitButton.classList.remove('opacity-50', 'cursor-not-allowed');
                        startDate.classList.remove('border-red-500');
                        endDate.classList.remove('border-red-500');
                    }
                }
            }

            startDate.addEventListener('change', validateDates);
            endDate.addEventListener('change', validateDates);
        });
    </script>
</body>
</html>
