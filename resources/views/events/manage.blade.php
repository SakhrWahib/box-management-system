@extends('layouts.app')

@section('content')
<!-- Main Content Container -->
<div class="p-6 bg-gradient-to-br from-gray-50 to-gray-100">
    <div class="flex justify-between items-center mb-6">
        <div class="flex items-center space-x-4 space-x-reverse">
            <h2 class="text-2xl font-bold text-gray-800">
                <span class="bg-indigo-500 bg-opacity-10 p-2 rounded-lg inline-flex items-center justify-center">
                    <i class="fas fa-history text-indigo-600"></i>
                </span>
                <span class="mr-2">التقارير والأحداث</span>
            </h2>
            <!-- Search Box -->
            <div class="relative">
                <input type="text" 
                    id="searchInput" 
                    placeholder="بحث في التقارير" 
                    class="pr-10 pl-4 py-2 border border-gray-300 rounded-lg focus:ring-2 focus:ring-indigo-500 focus:border-indigo-500 transition duration-150 ease-in-out shadow-sm">
                <i class="fas fa-search absolute right-3 top-3 text-gray-400"></i>
            </div>
        </div>
 
    </div>

    <!-- Filters -->


    <!-- Events Table -->
    <div class="bg-white rounded-xl shadow-md overflow-hidden border border-gray-200">
        <table class="min-w-full divide-y divide-gray-200">
            <thead class="bg-gradient-to-r from-gray-50 to-gray-100">
                <tr>
                    <th class="px-6 py-4 text-right text-xs font-medium text-gray-600 uppercase tracking-wider">اسم الجهاز</th>
                    <th class="px-6 py-4 text-right text-xs font-medium text-gray-600 uppercase tracking-wider">نوع الحدث</th>
                    <th class="px-6 py-4 text-right text-xs font-medium text-gray-600 uppercase tracking-wider">نوع الطريقة</th>
                    <th class="px-6 py-4 text-right text-xs font-medium text-gray-600 uppercase tracking-wider">التاريخ والوقت</th>
                    <th class="px-6 py-4 text-right text-xs font-medium text-gray-600 uppercase tracking-wider">الإجراءات</th>
                </tr>
            </thead>
            <tbody class="bg-white divide-y divide-gray-200" id="eventsTableBody">
                @foreach($events as $event)
                <tr class="hover:bg-gray-50 transition duration-150 ease-in-out">
                    <td class="px-6 py-4 whitespace-nowrap">
                        <div class="flex items-center">
                            <span class="bg-indigo-100 p-2 rounded-lg ml-2">
                                <i class="fas fa-mobile-alt text-indigo-600"></i>
                            </span>
                            <span class="font-medium text-gray-900">{{ $event->device->device_name }}</span>
                        </div>
                    </td>
                    <td class="px-6 py-4 whitespace-nowrap">
                        <span class="px-3 py-1 inline-flex text-sm leading-5 font-semibold rounded-full bg-emerald-100 text-emerald-800">
                            {{ $event->event_type }}
                        </span>
                    </td>
                    <td class="px-6 py-4 whitespace-nowrap">
                        <span class="px-3 py-1 inline-flex text-sm leading-5 font-semibold rounded-full bg-blue-100 text-blue-800">
                            {{ $event->method_type }}
                        </span>
                    </td>
                    <td class="px-6 py-4 whitespace-nowrap text-gray-600">
                        <i class="far fa-clock ml-2 text-indigo-500"></i>
                        {{ $event->timestamp }}
                    </td>
                    <td class="px-6 py-4 whitespace-nowrap">
                        <button onclick="openShowModal({{ $event->id }})" class="bg-indigo-100 text-indigo-600 hover:bg-indigo-200 p-2 rounded-lg transition duration-150 ease-in-out">
                            <i class="fas fa-eye"></i>
                        </button>
                    </td>
                </tr>
                @endforeach
            </tbody>
        </table>
    </div>

    <!-- Pagination -->
    <div class="mt-4" id="pagination">
        {{ $events->links() }}
    </div>
</div>

<!-- Show Event Modal -->
<div id="showModal" class="fixed inset-0 bg-black bg-opacity-50 hidden z-50">
    <div class="bg-white rounded-xl w-96 mx-auto mt-20 shadow-xl">
        <div class="bg-gradient-to-r from-indigo-600 to-indigo-700 text-white px-6 py-4 rounded-t-xl flex justify-between items-center">
            <h3 class="text-lg font-bold">تفاصيل الحدث</h3>
            <button onclick="closeModal('showModal')" class="text-white hover:text-gray-200 transition duration-150 ease-in-out">
                <i class="fas fa-times"></i>
            </button>
        </div>
        <div class="p-6">
            <div class="space-y-4">
                <div class="bg-gray-50 p-4 rounded-lg">
                    <label class="block text-sm font-medium text-gray-700 mb-1">
                        <span class="bg-indigo-100 p-1.5 rounded-lg inline-flex items-center justify-center ml-2">
                            <i class="fas fa-mobile-alt text-indigo-600"></i>
                        </span>
                        اسم الجهاز
                    </label>
                    <p id="show_device_name" class="text-gray-900 mt-1 pr-8"></p>
                </div>
                <div class="bg-gray-50 p-4 rounded-lg">
                    <label class="block text-sm font-medium text-gray-700 mb-1">
                        <span class="bg-indigo-100 p-1.5 rounded-lg inline-flex items-center justify-center ml-2">
                            <i class="fas fa-tag text-indigo-600"></i>
                        </span>
                        نوع الحدث
                    </label>
                    <p id="show_event_type" class="text-gray-900 mt-1 pr-8"></p>
                </div>
                <div class="bg-gray-50 p-4 rounded-lg">
                    <label class="block text-sm font-medium text-gray-700 mb-1">
                        <span class="bg-indigo-100 p-1.5 rounded-lg inline-flex items-center justify-center ml-2">
                            <i class="fas fa-code text-indigo-600"></i>
                        </span>
                        نوع الطريقة
                    </label>
                    <p id="show_method_type" class="text-gray-900 mt-1 pr-8"></p>
                </div>
                <div class="bg-gray-50 p-4 rounded-lg">
                    <label class="block text-sm font-medium text-gray-700 mb-1">
                        <span class="bg-indigo-100 p-1.5 rounded-lg inline-flex items-center justify-center ml-2">
                            <i class="fas fa-calendar text-indigo-600"></i>
                        </span>
                        التاريخ والوقت
                    </label>
                    <p id="show_timestamp" class="text-gray-900 mt-1 pr-8"></p>
                </div>
            </div>
            <div class="mt-6 flex justify-end">
                <button onclick="closeModal('showModal')" class="px-6 py-2.5 bg-gray-500 text-white rounded-lg hover:bg-gray-600 transition duration-150 ease-in-out">
                    <i class="fas fa-times ml-2"></i>إغلاق
                </button>
            </div>
        </div>
    </div>
</div>
@endsection

@section('scripts')
<script>
    $(document).ready(function() {
        // تهيئة البحث
        $('#searchInput').on('keyup', function() {
            applyFilters();
        });
    });

    // تطبيق الفلتر وجلب البيانات
    function applyFilters() {
        const startDate = $('#start_date').val();
        const endDate = $('#end_date').val();
        const eventType = $('#event_type').val();
        const searchQuery = $('#searchInput').val();

        $.ajax({
            url: '/events/manage',
            method: 'GET',
            data: {
                start_date: startDate,
                end_date: endDate,
                event_type: eventType,
                search: searchQuery
            },
            headers: {
                'X-CSRF-TOKEN': $('meta[name="csrf-token"]').attr('content'),
                'X-Requested-With': 'XMLHttpRequest'
            },
            success: function(response) {
                $('#eventsTableBody').html(response.events);
                $('#pagination').html(response.pagination);
            },
            error: function(xhr) {
                console.error('Error:', xhr);
                Swal.fire({
                    icon: 'error',
                    title: 'خطأ',
                    text: 'حدث خطأ أثناء تحديث البيانات'
                });
            }
        });
    }

    // تصدير التقرير
    function exportReport() {
        const startDate = $('#start_date').val();
        const endDate = $('#end_date').val();
        const eventType = $('#event_type').val();

        const params = new URLSearchParams({
            start_date: startDate,
            end_date: endDate,
            event_type: eventType
        });

        window.location.href = `/events/export?${params.toString()}`;
    }

    // عرض تفاصيل الحدث
    function openShowModal(eventId) {
        $.ajax({
            url: `/events/${eventId}`,
            method: 'GET',
            headers: {
                'X-CSRF-TOKEN': $('meta[name="csrf-token"]').attr('content'),
                'Accept': 'application/json'
            },
            success: function(response) {
                $('#show_device_name').text(response.device.device_name);
                $('#show_event_type').text(response.event_type);
                $('#show_method_type').text(response.method_type);
                $('#show_timestamp').text(response.timestamp);
                showModal('showModal');
            },
            error: function(xhr) {
                console.error('Error:', xhr);
                Swal.fire({
                    icon: 'error',
                    title: 'خطأ',
                    text: 'حدث خطأ أثناء جلب بيانات الحدث'
                });
            }
        });
    }

    // إغلاق النافذة المنبثقة
    function closeModal(modalId) {
        $(`#${modalId}`).addClass('hidden');
    }

    // عرض النافذة المنبثقة
    function showModal(modalId) {
        $(`#${modalId}`).removeClass('hidden');
    }
</script>
@endsection
