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
        .nav-item::before {
            content: '';
            position: absolute;
            top: 0;
            right: 0;
            width: 4px;
            height: 100%;
            background: #6366f1;
            transform: translateX(4px);
            transition: transform 0.3s ease;
        }
        .nav-item:hover::before {
            transform: translateX(0);
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
       INFO  Ctrl+D.
    
    
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
                        آخر تسجيل دخول: {{ now()->format('Y-m-d H:i:s') }}
                    </p>
                </div>
                
                <!-- Date Filter Form -->
                <div class="bg-white rounded-lg border border-gray-100">
                    <form action="{{ route('dashboard') }}" method="GET" class="flex gap-4 items-end" id="dateFilterForm">
                        <div>
                            <label class="block text-sm font-medium text-gray-700 mb-1">نطاق التاريخ</label>
                            <select name="date_filter" id="dateFilter" class="mt-1 block w-full rounded-lg border-gray-200 shadow-sm focus:border-indigo-500 focus:ring-indigo-500 text-sm">
                                <option value="today" {{ request('date_filter') == 'today' ? 'selected' : '' }}>اليوم</option>
                                <option value="week" {{ request('date_filter') == 'week' ? 'selected' : '' }}>الأسبوع</option>
                                <option value="month" {{ request('date_filter') == 'month' ? 'selected' : '' }}>الشهر</option>
                                <option value="year" {{ request('date_filter') == 'year' ? 'selected' : '' }}>السنة</option>
                                <option value="custom" {{ request('date_filter') == 'custom' ? 'selected' : '' }}>مخصص</option>
                            </select>
                        </div>
                        
                        <div class="custom-date-inputs {{ request('date_filter') == 'custom' ? '' : 'hidden' }}">
                            <div class="flex gap-4">
                                <div>
                                    <label class="block text-sm font-medium text-gray-700 mb-1">من</label>
                                    <input type="date" name="start_date" value="{{ request('start_date') }}" 
                                           class="mt-1 block w-full rounded-lg border-gray-200 shadow-sm focus:border-indigo-500 focus:ring-indigo-500 text-sm">
                                </div>
                                <div>
                                    <label class="block text-sm font-medium text-gray-700 mb-1">إلى</label>
                                    <input type="date" name="end_date" value="{{ request('end_date') }}"
                                           class="mt-1 block w-full rounded-lg border-gray-200 shadow-sm focus:border-indigo-500 focus:ring-indigo-500 text-sm">
                                </div>
                            </div>
                        </div>
                        
                        <button type="submit" class="bg-gradient-to-r from-indigo-600 to-indigo-500 text-white px-4 py-2 rounded-lg hover:from-indigo-700 hover:to-indigo-600 transition-all duration-200 text-sm font-medium shadow-sm hover:shadow-md">
                            تطبيق
                        </button>
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
                        <h3 class="text-gray-500 text-sm font-medium">إجمالي الأجهزة</h3>
                        <p class="text-2xl font-bold text-gray-800">{{ $stats['total_devices'] }}</p>
                    </div>
                </div>
            </div>
            
            <div class="bg-white rounded-lg shadow-sm p-6 border border-gray-100 hover:shadow-md transition-all duration-300">
                <div class="flex items-center">
                    <div class="p-3 rounded-lg bg-gradient-to-br from-emerald-500 to-emerald-600 shadow-lg">
                        <i class="fas fa-check-circle text-white text-2xl"></i>
                    </div>
                    <div class="mr-4">
                        <h3 class="text-gray-500 text-sm font-medium">الأجهزة النشطة</h3>
                        <p class="text-2xl font-bold text-gray-800">{{ $stats['active_devices'] }}</p>
                    </div>
                </div>
            </div>
            
            <div class="bg-white rounded-lg shadow-sm p-6 border border-gray-100 hover:shadow-md transition-all duration-300">
                <div class="flex items-center">
                    <div class="p-3 rounded-lg bg-gradient-to-br from-amber-500 to-amber-600 shadow-lg">
                        <i class="fas fa-calendar-day text-white text-2xl"></i>
                    </div>
                    <div class="mr-4">
                        <h3 class="text-gray-500 text-sm font-medium">أحداث اليوم</h3>
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
        </div>

        <!-- Charts Grid -->
        <div class="grid grid-cols-1 md:grid-cols-2 gap-6 mb-6">
            <!-- Device Status Chart -->
            <div class="bg-white rounded-lg shadow-sm p-4 border border-gray-100">
                <h3 class="text-sm font-bold mb-3 text-gray-700 flex items-center">
                    <div class="bg-indigo-100 p-1.5 rounded-md ml-2">
                        <i class="fas fa-chart-pie text-indigo-600"></i>
                    </div>
                    حالة الأجهزة
                </h3>
                <canvas id="deviceStatusChart" height="120"></canvas>
            </div>

            <!-- Weekly Events Chart -->
            <div class="bg-white rounded-lg shadow-sm p-4 border border-gray-100">
                <h3 class="text-sm font-bold mb-3 text-gray-700 flex items-center">
                    <div class="bg-emerald-100 p-1.5 rounded-md ml-2">
                        <i class="fas fa-chart-line text-emerald-600"></i>
                    </div>
                    أحداث الفترة
                </h3>
                <canvas id="weeklyEventsChart" height="120"></canvas>
            </div>

            <!-- Events by Type Chart -->
            <div class="bg-white rounded-lg shadow-sm p-4 border border-gray-100">
                <h3 class="text-sm font-bold mb-3 text-gray-700 flex items-center">
                    <div class="bg-amber-100 p-1.5 rounded-md ml-2">
                        <i class="fas fa-chart-bar text-amber-600"></i>
                    </div>
                    توزيع الأحداث حسب النوع
                </h3>
                <canvas id="eventsByTypeChart" height="120"></canvas>
            </div>

            <!-- Notifications by Type Chart -->
            <div class="bg-white rounded-lg shadow-sm p-4 border border-gray-100">
                <h3 class="text-sm font-bold mb-3 text-gray-700 flex items-center">
                    <div class="bg-violet-100 p-1.5 rounded-md ml-2">
                        <i class="fas fa-chart-area text-violet-600"></i>
                    </div>
                    توزيع الإشعارات
                </h3>
                <canvas id="notificationsByTypeChart" height="120"></canvas>
            </div>
        </div>

        <!-- Activity Heatmap -->
        <div class="bg-white rounded-lg shadow-sm p-4 border border-gray-100 mb-6">
            <h3 class="text-sm font-bold mb-3 text-gray-700 flex items-center">
                <div class="bg-blue-100 p-1.5 rounded-md ml-2">
                    <i class="fas fa-clock text-blue-600"></i>
                </div>
                نشاط الأجهزة خلال اليوم
            </h3>
            <canvas id="activityHeatmap" height="30"></canvas>
        </div>

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

        // رسم بياني لحالة الأجهزة
        new Chart(document.getElementById('deviceStatusChart').getContext('2d'), {
            type: 'doughnut',
            data: {
                labels: ['نشط', 'غير نشط'],
                datasets: [{
                    data: [{{ $deviceStatus['active'] }}, {{ $deviceStatus['inactive'] }}],
                    backgroundColor: [colors.emerald.primary, colors.gray]
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
                },
                scales: {
                    y: {
                        beginAtZero: true,
                        ticks: {
                            font: {
                                size: 9
                            }
                        }
                    },
                    x: {
                        ticks: {
                            font: {
                                size: 9
                            }
                        }
                    }
                }
            }
        });

        // رسم بياني لأحداث الفترة
        new Chart(document.getElementById('weeklyEventsChart').getContext('2d'), {
            type: 'line',
            data: {
                labels: {!! json_encode(array_map(function($date) {
                    return \Carbon\Carbon::parse($date)->locale('ar')->format('l j F');
                }, array_keys($periodEvents))) !!},
                datasets: [{
                    label: 'عدد الأحداث',
                    data: {!! json_encode(array_values($periodEvents)) !!},
                    borderColor: colors.blue.primary,
                    backgroundColor: colors.blue.primary + '20',
                    fill: true,
                    tension: 0.4
                }]
            },
            options: {
                responsive: true,
                maintainAspectRatio: true,
                aspectRatio: 2,
                scales: {
                    y: {
                        beginAtZero: true,
                        ticks: {
                            font: {
                                size: 9
                            }
                        }
                    },
                    x: {
                        ticks: {
                            font: {
                                size: 9
                            }
                        }
                    }
                },
                plugins: {
                    legend: {
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

        // رسم بياني لتوزيع الأحداث
        new Chart(document.getElementById('eventsByTypeChart').getContext('2d'), {
            type: 'bar',
            data: {
                labels: {!! json_encode(array_keys($eventTypes)) !!},
                datasets: [{
                    label: 'عدد الأحداث',
                    data: {!! json_encode(array_values($eventTypes)) !!},
                    backgroundColor: [
                        colors.blue.primary,
                        colors.emerald.primary,
                        colors.amber.primary,
                        colors.violet.primary
                    ]
                }]
            },
            options: {
                responsive: true,
                maintainAspectRatio: true,
                aspectRatio: 2,
                scales: {
                    y: {
                        beginAtZero: true,
                        ticks: {
                            font: {
                                size: 9
                            }
                        }
                    },
                    x: {
                        ticks: {
                            font: {
                                size: 9
                            }
                        }
                    }
                },
                plugins: {
                    legend: {
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
    </script>
</body>
</html>
