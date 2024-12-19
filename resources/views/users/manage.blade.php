@extends('layouts.app')

@section('content')
<!-- Main Content Container -->
<div class="p-6 bg-gray-50">
    <div class="flex justify-between items-center mb-6">
        <div class="flex items-center space-x-4 space-x-reverse">
            <h2 class="text-2xl font-bold text-gray-800">إدارة المستخدمين</h2>
            <!-- Search Box -->
            <div class="relative">
                <input type="text" 
                    id="searchInput" 
                    placeholder="بحث بالاسم أو رقم الهاتف" 
                    class="pr-10 pl-4 py-2 border rounded-lg focus:outline-none focus:ring-2 focus:ring-indigo-500 focus:border-indigo-500 shadow-sm">
                <i class="fas fa-search absolute right-3 top-3 text-indigo-500"></i>
            </div>
        </div>
        <button onclick="openCreateModal()" class="bg-gradient-to-r from-indigo-500 to-indigo-600 hover:from-indigo-600 hover:to-indigo-700 text-white px-6 py-2.5 rounded-lg flex items-center shadow-md transition duration-200 ease-in-out transform hover:-translate-y-0.5">
            <i class="fas fa-user-plus ml-2"></i>
            إضافة مستخدم جديد
        </button>
    </div>

    <!-- Users Table -->
    <div class="bg-white rounded-xl shadow-lg overflow-hidden">
        <table class="min-w-full divide-y divide-gray-200">
            <thead class="bg-gradient-to-r from-gray-50 to-gray-100">
                <tr>
                    <th class="px-6 py-4 text-right text-xs font-semibold text-gray-600 uppercase tracking-wider">الاسم</th>
                    <th class="px-6 py-4 text-right text-xs font-semibold text-gray-600 uppercase tracking-wider">البريد الإلكتروني</th>
                    <th class="px-6 py-4 text-right text-xs font-semibold text-gray-600 uppercase tracking-wider">رقم الهاتف</th>
                    <th class="px-6 py-4 text-right text-xs font-semibold text-gray-600 uppercase tracking-wider">البصمة</th>
                    <th class="px-6 py-4 text-right text-xs font-semibold text-gray-600 uppercase tracking-wider">معرف الجهاز</th>
                    <th class="px-6 py-4 text-right text-xs font-semibold text-gray-600 uppercase tracking-wider">تاريخ التسجيل</th>
                    <th class="px-6 py-4 text-right text-xs font-semibold text-gray-600 uppercase tracking-wider">الإجراءات</th>
                </tr>
            </thead>
            <tbody class="bg-white divide-y divide-gray-200" id="usersTableBody">
                @foreach($users as $user)
                <tr class="hover:bg-gray-50 transition-colors duration-200">
                    <td class="px-6 py-4 whitespace-nowrap">
                        <div class="flex items-center">
                            <div class="bg-indigo-100 p-2 rounded-lg ml-2">
                                <i class="fas fa-user text-indigo-600"></i>
                            </div>
                            <span class="text-gray-700">{{ $user->name }}</span>
                        </div>
                    </td>
                    <td class="px-6 py-4 whitespace-nowrap text-gray-600">{{ $user->email }}</td>
                    <td class="px-6 py-4 whitespace-nowrap text-gray-600">{{ $user->phone_number }}</td>
                    <td class="px-6 py-4 whitespace-nowrap">
                        @if($user->use_fingerprint)
                            <span class="px-3 py-1 inline-flex text-xs leading-5 font-semibold rounded-full bg-emerald-100 text-emerald-800">
                                مفعل
                            </span>
                        @else
                            <span class="px-3 py-1 inline-flex text-xs leading-5 font-semibold rounded-full bg-gray-100 text-gray-800">
                                غير مفعل
                            </span>
                        @endif
                    </td>
                    <td class="px-6 py-4 whitespace-nowrap text-gray-600">{{ $user->device_id ?? 'لا يوجد' }}</td>
                    <td class="px-6 py-4 whitespace-nowrap text-gray-600">{{ $user->created_at->format('Y-m-d') }}</td>
                    <td class="px-6 py-4 whitespace-nowrap">
                        <div class="flex space-x-3 space-x-reverse">
                            <button onclick="openShowModal({{ $user->id }})" class="bg-indigo-100 p-2 rounded-lg hover:bg-indigo-200 transition-colors">
                                <i class="fas fa-eye text-indigo-600"></i>
                            </button>
                            <button onclick="openEditModal({{ $user->id }})" class="bg-amber-100 p-2 rounded-lg hover:bg-amber-200 transition-colors">
                                <i class="fas fa-edit text-amber-600"></i>
                            </button>
                            <button onclick="deleteUser({{ $user->id }})" class="bg-red-100 p-2 rounded-lg hover:bg-red-200 transition-colors">
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
        {{ $users->links() }}
    </div>
</div>

<!-- Create User Modal -->
<div id="createModal" class="fixed inset-0 bg-gray-600 bg-opacity-50 hidden z-50">
    <div class="modal-container bg-white w-96 mx-auto mt-20 rounded-xl shadow-xl transform transition-all">
        <div class="modal-header border-b px-6 py-4 flex justify-between items-center bg-gradient-to-r from-indigo-500 to-indigo-600 rounded-t-xl">
            <h3 class="text-xl font-bold text-white">إضافة مستخدم جديد</h3>
            <button onclick="closeModal('createModal')" class="text-white hover:text-gray-200 transition-colors">
                <i class="fas fa-times"></i>
            </button>
        </div>
        <div class="modal-body p-6">
            <form id="createUserForm" onsubmit="submitCreateForm(event)">
                <div class="space-y-4">
                    <div>
                        <label class="block text-sm font-medium text-gray-700 mb-1">الاسم</label>
                        <input type="text" name="name" required class="w-full px-4 py-2 border border-gray-300 rounded-lg focus:ring-2 focus:ring-indigo-500 focus:border-indigo-500">
                    </div>
                    <div>
                        <label class="block text-sm font-medium text-gray-700 mb-1">البريد الإلكتروني</label>
                        <input type="email" name="email" required class="w-full px-4 py-2 border border-gray-300 rounded-lg focus:ring-2 focus:ring-indigo-500 focus:border-indigo-500">
                    </div>
                    <div>
                        <label class="block text-sm font-medium text-gray-700 mb-1">رقم الهاتف</label>
                        <input type="text" name="phone_number" required class="w-full px-4 py-2 border border-gray-300 rounded-lg focus:ring-2 focus:ring-indigo-500 focus:border-indigo-500">
                    </div>
                    <div>
                        <label class="block text-sm font-medium text-gray-700 mb-1">كلمة المرور</label>
                        <input type="password" name="password" required class="w-full px-4 py-2 border border-gray-300 rounded-lg focus:ring-2 focus:ring-indigo-500 focus:border-indigo-500">
                    </div>
                    <div class="flex items-center">
                        <input type="checkbox" name="use_fingerprint" class="w-5 h-5 rounded border-gray-300 text-indigo-600 focus:ring-indigo-500">
                        <label class="mr-2 text-sm font-medium text-gray-700">تفعيل البصمة</label>
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

<!-- Edit User Modal -->
<div id="editModal" class="fixed inset-0 bg-gray-600 bg-opacity-50 hidden z-50">
    <div class="modal-container bg-white w-96 mx-auto mt-20 rounded-xl shadow-xl transform transition-all">
        <div class="modal-header border-b px-6 py-4 flex justify-between items-center bg-gradient-to-r from-indigo-500 to-indigo-600 rounded-t-xl">
            <h3 class="text-xl font-bold text-white">تعديل بيانات المستخدم</h3>
            <button onclick="closeModal('editModal')" class="text-white hover:text-gray-200 transition-colors">
                <i class="fas fa-times"></i>
            </button>
        </div>
        <div class="modal-body p-6">
            <form id="editUserForm" onsubmit="submitEditForm(event)">
                <input type="hidden" name="user_id" id="edit_user_id">
                <div class="space-y-4">
                    <div>
                        <label class="block text-sm font-medium text-gray-700 mb-1">الاسم</label>
                        <input type="text" name="name" id="edit_name" required class="w-full px-4 py-2 border border-gray-300 rounded-lg focus:ring-2 focus:ring-indigo-500 focus:border-indigo-500">
                    </div>
                    <div>
                        <label class="block text-sm font-medium text-gray-700 mb-1">البريد الإلكتروني</label>
                        <input type="email" name="email" id="edit_email" required class="w-full px-4 py-2 border border-gray-300 rounded-lg focus:ring-2 focus:ring-indigo-500 focus:border-indigo-500">
                    </div>
                    <div>
                        <label class="block text-sm font-medium text-gray-700 mb-1">رقم الهاتف</label>
                        <input type="text" name="phone_number" id="edit_phone_number" required class="w-full px-4 py-2 border border-gray-300 rounded-lg focus:ring-2 focus:ring-indigo-500 focus:border-indigo-500">
                    </div>
                    <div class="flex items-center">
                        <input type="checkbox" name="use_fingerprint" id="edit_use_fingerprint" class="w-5 h-5 rounded border-gray-300 text-indigo-600 focus:ring-indigo-500">
                        <label class="mr-2 text-sm font-medium text-gray-700">تفعيل البصمة</label>
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

<!-- Show User Modal -->
<div id="showModal" class="fixed inset-0 bg-gray-600 bg-opacity-50 hidden z-50">
    <div class="modal-container bg-white w-96 mx-auto mt-20 rounded-xl shadow-xl transform transition-all">
        <div class="modal-header border-b px-6 py-4 flex justify-between items-center bg-gradient-to-r from-indigo-500 to-indigo-600 rounded-t-xl">
            <h3 class="text-xl font-bold text-white">تفاصيل المستخدم</h3>
            <button onclick="closeModal('showModal')" class="text-white hover:text-gray-200 transition-colors">
                <i class="fas fa-times"></i>
            </button>
        </div>
        <div class="modal-body p-6">
            <div class="space-y-4">
                <div>
                    <label class="block text-sm font-medium text-gray-500 mb-1">الاسم</label>
                    <p id="show_name" class="text-gray-900 font-medium"></p>
                </div>
                <div>
                    <label class="block text-sm font-medium text-gray-500 mb-1">البريد الإلكتروني</label>
                    <p id="show_email" class="text-gray-900 font-medium"></p>
                </div>
                <div>
                    <label class="block text-sm font-medium text-gray-500 mb-1">رقم الهاتف</label>
                    <p id="show_phone_number" class="text-gray-900 font-medium"></p>
                </div>
                <div>
                    <label class="block text-sm font-medium text-gray-500 mb-1">البصمة</label>
                    <p id="show_fingerprint" class="inline-flex px-3 py-1 text-sm font-semibold rounded-full"></p>
                </div>
                <div>
                    <label class="block text-sm font-medium text-gray-500 mb-1">معرف الجهاز</label>
                    <p id="show_device_id" class="text-gray-900 font-medium"></p>
                </div>
                <div>
                    <label class="block text-sm font-medium text-gray-500 mb-1">تاريخ التسجيل</label>
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
            $("#usersTableBody tr").filter(function() {
                $(this).toggle($(this).text().toLowerCase().indexOf(value) > -1)
            });
        });
    });

    // عرض تفاصيل المستخدم
    function openShowModal(id) {
        $.get(`/users/${id}`, function(user) {
            $('#show_name').text(user.name);
            $('#show_email').text(user.email);
            $('#show_phone_number').text(user.phone_number);
            
            // تحسين عرض حالة البصمة
            const fingerprintElement = $('#show_fingerprint');
            if (user.use_fingerprint) {
                fingerprintElement.removeClass('bg-gray-100 text-gray-800').addClass('bg-emerald-100 text-emerald-800').text('مفعل');
            } else {
                fingerprintElement.removeClass('bg-emerald-100 text-emerald-800').addClass('bg-gray-100 text-gray-800').text('غير مفعل');
            }
            
            $('#show_device_id').text(user.device_id || 'لا يوجد');
            $('#show_created_at').text(new Date(user.created_at).toLocaleDateString('ar-SA'));
            $('#showModal').removeClass('hidden');
        });
    }

    // تحرير المستخدم
    function openEditModal(id) {
        $.get(`/users/${id}`, function(user) {
            $('#edit_user_id').val(user.id);
            $('#edit_name').val(user.name);
            $('#edit_email').val(user.email);
            $('#edit_phone_number').val(user.phone_number);
            $('#edit_use_fingerprint').prop('checked', user.use_fingerprint);
            $('#editModal').removeClass('hidden');
        });
    }

    // إضافة مستخدم جديد
    function openCreateModal() {
        $('#createUserForm')[0].reset();
        $('#createModal').removeClass('hidden');
    }

    // إغلاق النوافذ المنبثقة
    function closeModal(modalId) {
        $(`#${modalId}`).addClass('hidden');
    }

    // حذف مستخدم
    function deleteUser(id) {
        if (confirm('هل أنت متأكد من حذف هذا المستخدم؟')) {
            $.ajax({
                url: `/users/${id}`,
                type: 'DELETE',
                headers: {
                    'X-CSRF-TOKEN': $('meta[name="csrf-token"]').attr('content')
                },
                success: function(result) {
                    showNotification('تم حذف المستخدم بنجاح', 'success');
                    location.reload();
                },
                error: function(xhr) {
                    showNotification('حدث خطأ أثناء حذف المستخدم', 'error');
                }
            });
        }
    }

    // تقديم نموذج الإنشاء
    function submitCreateForm(event) {
        event.preventDefault();
        const form = $('#createUserForm');
        $.ajax({
            url: '/users',
            type: 'POST',
            data: form.serialize(),
            headers: {
                'X-CSRF-TOKEN': $('meta[name="csrf-token"]').attr('content')
            },
            success: function(result) {
                closeModal('createModal');
                showNotification('تم إضافة المستخدم بنجاح', 'success');
                location.reload();
            },
            error: function(xhr) {
                showNotification('حدث خطأ أثناء إضافة المستخدم', 'error');
            }
        });
    }

    // تقديم نموذج التحرير
    function submitEditForm(event) {
        event.preventDefault();
        const form = $('#editUserForm');
        const id = $('#edit_user_id').val();
        $.ajax({
            url: `/users/${id}`,
            type: 'PUT',
            data: form.serialize(),
            headers: {
                'X-CSRF-TOKEN': $('meta[name="csrf-token"]').attr('content')
            },
            success: function(result) {
                closeModal('editModal');
                showNotification('تم تحديث بيانات المستخدم بنجاح', 'success');
                location.reload();
            },
            error: function(xhr) {
                showNotification('حدث خطأ أثناء تحديث بيانات المستخدم', 'error');
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
