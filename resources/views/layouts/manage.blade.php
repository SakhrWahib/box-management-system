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
    </style>
</head>
<body class="bg-gray-100">
    <!-- Sidebar -->
    <div class="fixed right-0 top-0 w-64 h-full bg-gray-900 p-4">
        <div class="flex items-center justify-center mb-8">
            <h1 class="text-white text-2xl font-bold">لوحة التحكم</h1>
        </div>
        <nav>
            <ul class="space-y-2">
                <li>
                    <a href="{{ route('dashboard') }}" class="flex items-center text-gray-300 hover:bg-gray-700 p-2 rounded-lg">
                        <i class="fas fa-home ml-2"></i>
                        الرئيسية
                    </a>
                </li>
                <li>
                    <a href="#" class="flex items-center text-gray-300 hover:bg-gray-700 p-2 rounded-lg">
                        <i class="fas fa-laptop ml-2"></i>
                        الأجهزة
                    </a>
                </li>
                <li>
                    <a href="{{ route('users.manage') }}" class="flex items-center text-gray-300 hover:bg-gray-700 p-2 rounded-lg">
                        <i class="fas fa-users-cog ml-2"></i>
                        إدارة المستخدمين
                    </a>
                </li>

                <li>
                    <a href="#" class="flex items-center text-gray-300 hover:bg-gray-700 p-2 rounded-lg">
                        <i class="fas fa-chart-line ml-2"></i>
                        التقارير
                    </a>
                </li>
                <li>
                    <a href="#" class="flex items-center text-gray-300 hover:bg-gray-700 p-2 rounded-lg">
                        <i class="fas fa-bell ml-2"></i>
                        الإشعارات
                    </a>
                </li>
                <li>
                    <a href="#" class="flex items-center text-gray-300 hover:bg-gray-700 p-2 rounded-lg">
                        <i class="fas fa-cog ml-2"></i>
                        الإعدادات
                    </a>
                </li>
                <li>
                    <form action="{{ route('admin.logout') }}" method="POST" class="w-full">
                        @csrf
                        <button type="submit" class="w-full flex items-center text-gray-300 hover:bg-gray-700 p-2 rounded-lg">
                            <i class="fas fa-sign-out-alt ml-2"></i>
                            تسجيل الخروج
                        </button>
                    </form>
                </li>
            </ul>
        </nav>
    </div>

    <!-- Main Content -->
    <div class="mr-64 min-h-screen">
        @yield('content')
    </div>

    <!-- Scripts -->
    <script src="https://cdn.jsdelivr.net/npm/sweetalert2@11"></script>
    @yield('scripts')

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
