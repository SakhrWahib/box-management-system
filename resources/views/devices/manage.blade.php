@extends('layouts.app')

@section('content')
<!-- Main Content Container -->

<div class="p-6 bg-gray-50">
    <div class="flex justify-between items-center mb-6">
        <div class="flex items-center space-x-4 space-x-reverse">
            <h2 class="text-2xl font-bold text-gray-800">إدارة الأجهزة</h2>
            <!-- Search Box -->
            <div class="relative">
                <input type="text" 
                    id="searchInput" 
                    placeholder="بحث باسم الجهاز أو عنوان MAC" 
                    class="pr-10 pl-4 py-2 border rounded-lg focus:outline-none focus:ring-2 focus:ring-indigo-500 focus:border-indigo-500">
                <i class="fas fa-search absolute right-3 top-3 text-indigo-500"></i>
            </div>
        </div>
        <button onclick="openCreateModal()" class="bg-gradient-to-r from-indigo-500 to-indigo-600 hover:from-indigo-600 hover:to-indigo-700 text-white px-6 py-2.5 rounded-lg flex items-center shadow-md transition duration-200 ease-in-out transform hover:-translate-y-0.5">
            <i class="fas fa-plus-circle ml-2"></i>
            إضافة جهاز جديد
        </button>
    </div>

    <!-- Devices Table -->
    <div class="bg-white rounded-xl shadow-lg overflow-hidden">
        <table class="min-w-full divide-y divide-gray-200">
            <thead class="bg-gradient-to-r from-gray-50 to-gray-100">
                <tr>
                    <th class="px-6 py-4 text-right text-xs font-semibold text-gray-600 uppercase tracking-wider">اسم الجهاز</th>
                    <th class="px-6 py-4 text-right text-xs font-semibold text-gray-600 uppercase tracking-wider">عنوان MAC</th>
                    <th class="px-6 py-4 text-right text-xs font-semibold text-gray-600 uppercase tracking-wider">رمز المستخدم</th>
                    <th class="px-6 py-4 text-right text-xs font-semibold text-gray-600 uppercase tracking-wider">المستخدم</th>
                    <th class="px-6 py-4 text-right text-xs font-semibold text-gray-600 uppercase tracking-wider">الموقع</th>
                    <th class="px-6 py-4 text-right text-xs font-semibold text-gray-600 uppercase tracking-wider">الحالة</th>
                    <th class="px-6 py-4 text-right text-xs font-semibold text-gray-600 uppercase tracking-wider">تاريخ الإضافة</th>
                    <th class="px-6 py-4 text-right text-xs font-semibold text-gray-600 uppercase tracking-wider">الإجراءات</th>
                </tr>
            </thead>
            <tbody class="bg-white divide-y divide-gray-200" id="devicesTableBody">
                @foreach($devices as $device)
                <tr class="hover:bg-gray-50 transition-colors duration-200">
                    <td class="px-6 py-4 whitespace-nowrap">
                        <div class="flex items-center">
                            <div class="bg-indigo-100 p-2 rounded-lg ml-2">
                                <i class="fas fa-mobile-alt text-indigo-600"></i>
                            </div>
                            <span class="text-gray-700">{{ $device->device_name }}</span>
                        </div>
                    </td>
                    <td class="px-6 py-4 whitespace-nowrap font-mono text-gray-600">{{ $device->mac_address }}</td>
                    <td class="px-6 py-4 whitespace-nowrap text-gray-600">{{ $device->usercode }}</td>
                    <td class="px-6 py-4 whitespace-nowrap text-gray-600">{{ $device->user->name }}</td>
                    <td class="px-6 py-4 whitespace-nowrap text-gray-600">{{ $device->site_data ?: 'غير محدد' }}</td>
                    <td class="px-6 py-4 whitespace-nowrap">
                        <span class="px-3 py-1 inline-flex text-xs leading-5 font-semibold rounded-full {{ $device->status ? 'bg-emerald-100 text-emerald-800' : 'bg-red-100 text-red-800' }}">
                            {{ $device->status ? 'نشط' : 'غير نشط' }}
                        </span>
                    </td>
                    <td class="px-6 py-4 whitespace-nowrap text-gray-600">{{ $device->created_at->format('Y-m-d') }}</td>
                    <td class="px-6 py-4 whitespace-nowrap">
                        <div class="flex space-x-3 space-x-reverse">
                            <button onclick="openShowModal({{ $device->id }})" class="bg-indigo-100 p-2 rounded-lg hover:bg-indigo-200 transition-colors">
                                <i class="fas fa-eye text-indigo-600"></i>
                            </button>
                            <button onclick="openEditModal({{ $device->id }})" class="bg-amber-100 p-2 rounded-lg hover:bg-amber-200 transition-colors">
                                <i class="fas fa-edit text-amber-600"></i>
                            </button>
                            <button onclick="deleteDevice({{ $device->id }})" class="bg-red-100 p-2 rounded-lg hover:bg-red-200 transition-colors">
                                <i class="fas fa-trash-alt text-red-600"></i>
                            </button>
                        </div>
                    </td>
                </tr>
                @endforeach
            </tbody>
        </table>
    </div>

    <!-- Pagination -->
    <div class="mt-6" id="pagination">
        {{ $devices->links() }}
    </div>
</div>

<!-- Create Device Modal -->
<div id="createModal" class="fixed inset-0 bg-gray-600 bg-opacity-50 hidden z-50">
    <div class="modal-container bg-white w-96 mx-auto mt-20 rounded-xl shadow-xl transform transition-all">
        <div class="modal-header border-b px-6 py-4 flex justify-between items-center bg-gradient-to-r from-indigo-500 to-indigo-600 rounded-t-xl">
            <h3 class="text-xl font-bold text-white">إضافة جهاز جديد</h3>
            <button onclick="closeModal('createModal')" class="text-white hover:text-gray-200 transition-colors">
                <i class="fas fa-times"></i>
            </button>
        </div>
        <div class="modal-body p-6">
            <form id="createDeviceForm" onsubmit="submitCreateForm(event)">
                <div class="space-y-4">
                    <div>
                        <label class="block text-sm font-medium text-gray-700 mb-1">اسم الجهاز</label>
                        <input type="text" name="device_name" required class="w-full px-4 py-2 border border-gray-300 rounded-lg focus:ring-2 focus:ring-indigo-500 focus:border-indigo-500">
                    </div>
                    <div>
                        <label class="block text-sm font-medium text-gray-700 mb-1">عنوان MAC</label>
                        <input type="text" name="mac_address" required class="w-full px-4 py-2 border border-gray-300 rounded-lg focus:ring-2 focus:ring-indigo-500 focus:border-indigo-500">
                    </div>
                    <div>
                        <label class="block text-sm font-medium text-gray-700 mb-1">رمز المستخدم</label>
                        <input type="text" name="usercode" required class="w-full px-4 py-2 border border-gray-300 rounded-lg focus:ring-2 focus:ring-indigo-500 focus:border-indigo-500">
                    </div>
                    <div>
                        <label class="block text-sm font-medium text-gray-700 mb-1">المستخدم</label>
                        <select name="user_id" class="w-full px-4 py-2 border border-gray-300 rounded-lg focus:ring-2 focus:ring-indigo-500 focus:border-indigo-500">
                            <option value="">اختر مستخدم</option>
                            @foreach($users as $user)
                                <option value="{{ $user->id }}">{{ $user->name }}</option>
                            @endforeach
                        </select>
                    </div>
                    <div>
                        <label class="block text-sm font-medium text-gray-700 mb-1">الموقع</label>
                        <input type="text" name="site_data" class="w-full px-4 py-2 border border-gray-300 rounded-lg focus:ring-2 focus:ring-indigo-500 focus:border-indigo-500">
                    </div>
                    <div>
                        <label class="block text-sm font-medium text-gray-700 mb-1">الحالة</label>
                        <select name="status" class="w-full px-4 py-2 border border-gray-300 rounded-lg focus:ring-2 focus:ring-indigo-500 focus:border-indigo-500">
                            <option value="1">نشط</option>
                            <option value="0">غير نشط</option>
                        </select>
                    </div>
                </div>
                <div class="mt-6 flex justify-end space-x-3 space-x-reverse">
                    <button type="button" onclick="closeModal('createModal')" class="px-4 py-2 border border-gray-300 rounded-lg text-gray-700 hover:bg-gray-50 transition-colors">
                        إلغاء
                    </button>
                    <button type="submit" class="px-4 py-2 bg-gradient-to-r from-indigo-500 to-indigo-600 text-white rounded-lg hover:from-indigo-600 hover:to-indigo-700 transition-colors">
                        حفظ
                    </button>
                </div>
            </form>
        </div>
    </div>
</div>

<!-- Edit Device Modal -->
<div id="editModal" class="fixed inset-0 bg-gray-600 bg-opacity-50 hidden z-50">
    <div class="modal-container bg-white w-96 mx-auto mt-20 rounded-xl shadow-xl transform transition-all">
        <div class="modal-header border-b px-6 py-4 flex justify-between items-center bg-gradient-to-r from-indigo-500 to-indigo-600 rounded-t-xl">
            <h3 class="text-xl font-bold text-white">تعديل بيانات الجهاز</h3>
            <button onclick="closeModal('editModal')" class="text-white hover:text-gray-200 transition-colors">
                <i class="fas fa-times"></i>
            </button>
        </div>
        <div class="modal-body p-6">
            <form id="editDeviceForm" onsubmit="submitEditForm(event)">
                <input type="hidden" name="device_id" id="edit_device_id">
                <div class="space-y-4">
                    <div>
                        <label class="block text-sm font-medium text-gray-700 mb-1">اسم الجهاز</label>
                        <input type="text" name="device_name" id="edit_device_name" required class="w-full px-4 py-2 border border-gray-300 rounded-lg focus:ring-2 focus:ring-indigo-500 focus:border-indigo-500">
                    </div>
                    <div>
                        <label class="block text-sm font-medium text-gray-700 mb-1">عنوان MAC</label>
                        <input type="text" name="mac_address" id="edit_mac_address" required class="w-full px-4 py-2 border border-gray-300 rounded-lg focus:ring-2 focus:ring-indigo-500 focus:border-indigo-500">
                    </div>
                    <div>
                        <label class="block text-sm font-medium text-gray-700 mb-1">رمز المستخدم</label>
                        <input type="text" name="usercode" id="edit_usercode" required class="w-full px-4 py-2 border border-gray-300 rounded-lg focus:ring-2 focus:ring-indigo-500 focus:border-indigo-500">
                    </div>
                    <div>
                        <label class="block text-sm font-medium text-gray-700 mb-1">المستخدم</label>
                        <select name="user_id" id="edit_user_id" class="w-full px-4 py-2 border border-gray-300 rounded-lg focus:ring-2 focus:ring-indigo-500 focus:border-indigo-500">
                            <option value="">اختر مستخدم</option>
                            @foreach($users as $user)
                                <option value="{{ $user->id }}">{{ $user->name }}</option>
                            @endforeach
                        </select>
                    </div>
                    <div>
                        <label class="block text-sm font-medium text-gray-700 mb-1">الموقع</label>
                        <input type="text" name="site_data" id="edit_site_data" class="w-full px-4 py-2 border border-gray-300 rounded-lg focus:ring-2 focus:ring-indigo-500 focus:border-indigo-500">
                    </div>
                    <div>
                        <label class="block text-sm font-medium text-gray-700 mb-1">الحالة</label>
                        <select name="status" id="edit_status" class="w-full px-4 py-2 border border-gray-300 rounded-lg focus:ring-2 focus:ring-indigo-500 focus:border-indigo-500">
                            <option value="1">نشط</option>
                            <option value="0">غير نشط</option>
                        </select>
                    </div>
                </div>
                <div class="mt-6 flex justify-end space-x-3 space-x-reverse">
                    <button type="button" onclick="closeModal('editModal')" class="px-4 py-2 border border-gray-300 rounded-lg text-gray-700 hover:bg-gray-50 transition-colors">
                        إلغاء
                    </button>
                    <button type="submit" class="px-4 py-2 bg-gradient-to-r from-indigo-500 to-indigo-600 text-white rounded-lg hover:from-indigo-600 hover:to-indigo-700 transition-colors">
                        تحديث
                    </button>
                </div>
            </form>
        </div>
    </div>
</div>

<!-- Show Device Modal -->
<div id="showModal" class="fixed inset-0 bg-gray-600 bg-opacity-50 hidden z-50">
    <div class="modal-container bg-white w-96 mx-auto mt-20 rounded-xl shadow-xl transform transition-all">
        <div class="modal-header border-b px-6 py-4 flex justify-between items-center bg-gradient-to-r from-indigo-500 to-indigo-600 rounded-t-xl">
            <h3 class="text-xl font-bold text-white">تفاصيل الجهاز</h3>
            <button onclick="closeModal('showModal')" class="text-white hover:text-gray-200 transition-colors">
                <i class="fas fa-times"></i>
            </button>
        </div>
        <div class="modal-body p-6">
            <div class="space-y-4">
                <div>
                    <label class="block text-sm font-medium text-gray-500 mb-1">اسم الجهاز</label>
                    <p id="show_device_name" class="text-gray-900 font-medium"></p>
                </div>
                <div>
                    <label class="block text-sm font-medium text-gray-500 mb-1">عنوان MAC</label>
                    <p id="show_mac_address" class="text-gray-900 font-mono font-medium"></p>
                </div>
                <div>
                    <label class="block text-sm font-medium text-gray-500 mb-1">رمز المستخدم</label>
                    <p id="show_usercode" class="text-gray-900 font-medium"></p>
                </div>
                <div>
                    <label class="block text-sm font-medium text-gray-500 mb-1">المستخدم</label>
                    <p id="show_user_name" class="text-gray-900 font-medium"></p>
                </div>
                <div>
                    <label class="block text-sm font-medium text-gray-500 mb-1">الموقع</label>
                    <p id="show_site_data" class="text-gray-900 font-medium"></p>
                </div>
                <div>
                    <label class="block text-sm font-medium text-gray-500 mb-1">الحالة</label>
                    <p id="show_status" class="inline-flex px-3 py-1 text-sm font-semibold rounded-full"></p>
                </div>
                <div>
                    <label class="block text-sm font-medium text-gray-500 mb-1">تاريخ الإضافة</label>
                    <p id="show_created_at" class="text-gray-900 font-medium"></p>
                </div>
            </div>
            <div class="mt-6 flex justify-end">
                <button type="button" onclick="closeModal('showModal')" class="px-4 py-2 border border-gray-300 rounded-lg text-gray-700 hover:bg-gray-50 transition-colors">
                    إغلاق
                </button>
            </div>
        </div>
    </div>
</div>
@endsection

@push('scripts')
<script>
    $(document).ready(function() {
        // تهيئة البحث
        $('#searchInput').on('keyup', function() {
            var value = $(this).val().toLowerCase();
            $("#devicesTableBody tr").filter(function() {
                $(this).toggle($(this).text().toLowerCase().indexOf(value) > -1)
            });
        });
    });

    // عرض تفاصيل الجهاز
    function openShowModal(id) {
        $.get(`/devices/${id}`, function(device) {
            $('#show_device_name').text(device.device_name);
            $('#show_mac_address').text(device.mac_address);
            $('#show_usercode').text(device.usercode);
            $('#show_user_name').text(device.user.name);
            $('#show_site_data').text(device.site_data || 'غير محدد');
            
            // تحسين عرض حالة الجهاز
            const statusElement = $('#show_status');
            if (device.status) {
                statusElement.removeClass('bg-red-100 text-red-800').addClass('bg-emerald-100 text-emerald-800').text('نشط');
            } else {
                statusElement.removeClass('bg-emerald-100 text-emerald-800').addClass('bg-red-100 text-red-800').text('غير نشط');
            }
            
            $('#show_created_at').text(new Date(device.created_at).toLocaleDateString('ar-SA'));
            $('#showModal').removeClass('hidden');
        });
    }

    // تحرير الجهاز
    function openEditModal(id) {
        $.get(`/devices/${id}`, function(device) {
            $('#edit_device_id').val(device.id);
            $('#edit_device_name').val(device.device_name);
            $('#edit_mac_address').val(device.mac_address);
            $('#edit_usercode').val(device.usercode);
            $('#edit_user_id').val(device.user_id);
            $('#edit_site_data').val(device.site_data);
            $('#edit_status').val(device.status ? '1' : '0');
            $('#editModal').removeClass('hidden');
        });
    }

    // إضافة جهاز جديد
    function openCreateModal() {
        $('#createDeviceForm')[0].reset();
        $('#createModal').removeClass('hidden');
    }

    // إغلاق النوافذ المنبثقة
    function closeModal(modalId) {
        $(`#${modalId}`).addClass('hidden');
    }

    // حذف جهاز
    function deleteDevice(id) {
        if (confirm('هل أنت متأكد من حذف هذا الجهاز؟')) {
            $.ajax({
                url: `/devices/${id}`,
                type: 'DELETE',
                headers: {
                    'X-CSRF-TOKEN': $('meta[name="csrf-token"]').attr('content')
                },
                success: function(result) {
                    showNotification('تم حذف الجهاز بنجاح', 'success');
                    location.reload();
                },
                error: function(xhr) {
                    showNotification('حدث خطأ أثناء حذف الجهاز', 'error');
                }
            });
        }
    }

    // تقديم نموذج الإنشاء
    function submitCreateForm(event) {
        event.preventDefault();
        const form = $('#createDeviceForm');
        $.ajax({
            url: '/devices',
            type: 'POST',
            data: form.serialize(),
            headers: {
                'X-CSRF-TOKEN': $('meta[name="csrf-token"]').attr('content')
            },
            success: function(result) {
                closeModal('createModal');
                showNotification('تم إضافة الجهاز بنجاح', 'success');
                location.reload();
            },
            error: function(xhr) {
                showNotification('حدث خطأ أثناء إضافة الجهاز', 'error');
            }
        });
    }

    // تقديم نموذج التحرير
    function submitEditForm(event) {
        event.preventDefault();
        const form = $('#editDeviceForm');
        const id = $('#edit_device_id').val();
        $.ajax({
            url: `/devices/${id}`,
            type: 'PUT',
            data: form.serialize(),
            headers: {
                'X-CSRF-TOKEN': $('meta[name="csrf-token"]').attr('content')
            },
            success: function(result) {
                closeModal('editModal');
                showNotification('تم تحديث الجهاز بنجاح', 'success');
                location.reload();
            },
            error: function(xhr) {
                showNotification('حدث خطأ أثناء تحديث الجهاز', 'error');
            }
        });
    }

    // عرض الإشعارات
    function showNotification(message, type) {
        const bgColor = type === 'success' ? 'bg-emerald-100 text-emerald-800' : 'bg-red-100 text-red-800';
        const icon = type === 'success' ? 'check-circle' : 'exclamation-circle';
        
        const notification = $(`
            <div class="fixed top-4 left-4 max-w-sm w-full bg-white rounded-lg shadow-lg pointer-events-auto transform transition-all duration-300 ease-in-out opacity-0 translate-y-2">
                <div class="p-4">
                    <div class="flex items-center">
                        <div class="flex-shrink-0">
                            <i class="fas fa-${icon} text-lg ${type === 'success' ? 'text-emerald-500' : 'text-red-500'}"></i>
                        </div>
                        <div class="ml-3 w-0 flex-1">
                            <p class="text-sm font-medium text-gray-900">${message}</p>
                        </div>
                        <div class="ml-4 flex-shrink-0 flex">
                            <button class="inline-flex text-gray-400 hover:text-gray-500 focus:outline-none">
                                <i class="fas fa-times"></i>
                            </button>
                        </div>
                    </div>
                </div>
            </div>
        `);
        
        $('body').append(notification);
        setTimeout(() => notification.removeClass('opacity-0 translate-y-2'), 100);
        
        setTimeout(() => {
            notification.addClass('opacity-0 translate-y-2');
            setTimeout(() => notification.remove(), 300);
        }, 3000);
    }
</script>
@endpush
