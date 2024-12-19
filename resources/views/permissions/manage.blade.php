@extends('layouts.app')

@section('content')
<div class="p-6 bg-gradient-to-br from-gray-50 to-gray-100">
    <div class="flex justify-between items-center mb-6">
        <div class="flex items-center space-x-4 space-x-reverse">
            <h2 class="text-2xl font-bold text-gray-800">
                <span class="bg-indigo-500 bg-opacity-10 p-2 rounded-lg inline-flex items-center justify-center">
                    <i class="fas fa-user-shield text-indigo-600"></i>
                </span>
                <span class="mr-2">إدارة المستخدمين والصلاحيات</span>
            </h2>
            <div class="relative">
                <input type="text" 
                    id="searchInput" 
                    placeholder="بحث باسم المستخدم أو البريد الإلكتروني" 
                    class="pr-10 pl-4 py-2 border border-gray-300 rounded-lg focus:ring-2 focus:ring-indigo-500 focus:border-indigo-500 transition duration-150 ease-in-out shadow-sm">
                <i class="fas fa-search absolute right-3 top-3 text-gray-400"></i>
            </div>
        </div>
        <button onclick="openCreateModal()" class="bg-gradient-to-r from-indigo-600 to-indigo-700 hover:from-indigo-700 hover:to-indigo-800 text-white px-6 py-2.5 rounded-lg flex items-center transition duration-150 ease-in-out shadow-md">
            <i class="fas fa-plus-circle ml-2"></i>
            إضافة مستخدم جديد
        </button>
    </div>

    <!-- Users Table -->
    <div class="bg-white rounded-xl shadow-md overflow-hidden border border-gray-200">
        <table class="min-w-full divide-y divide-gray-200">
            <thead class="bg-gradient-to-r from-gray-50 to-gray-100">
                <tr>
                    <th class="px-6 py-4 text-right text-xs font-medium text-gray-600 uppercase tracking-wider">المستخدم</th>
                    <th class="px-6 py-4 text-right text-xs font-medium text-gray-600 uppercase tracking-wider">البريد الإلكتروني</th>
                    <th class="px-6 py-4 text-right text-xs font-medium text-gray-600 uppercase tracking-wider">الدور</th>
                    <th class="px-6 py-4 text-right text-xs font-medium text-gray-600 uppercase tracking-wider">الحالة</th>
                    <th class="px-6 py-4 text-right text-xs font-medium text-gray-600 uppercase tracking-wider">تاريخ التسجيل</th>
                    <th class="px-6 py-4 text-right text-xs font-medium text-gray-600 uppercase tracking-wider">الإجراءات</th>
                </tr>
            </thead>
            <tbody class="bg-white divide-y divide-gray-200">
                @foreach($admins as $admin)
                <tr class="hover:bg-gray-50 transition duration-150 ease-in-out">
                    <td class="px-6 py-4 whitespace-nowrap font-medium text-gray-900">{{ $admin->name }}</td>
                    <td class="px-6 py-4 whitespace-nowrap text-gray-600">{{ $admin->email }}</td>
                    <td class="px-6 py-4 whitespace-nowrap">
                        <span class="px-3 py-1 inline-flex text-sm leading-5 font-semibold rounded-full {{ $admin->role == 'admin' ? 'bg-emerald-100 text-emerald-800' : 'bg-blue-100 text-blue-800' }}">
                            {{ $admin->role == 'admin' ? 'مدير' : 'مستخدم' }}
                        </span>
                    </td>
                    <td class="px-6 py-4 whitespace-nowrap">
                        <span class="px-3 py-1 inline-flex text-sm leading-5 font-semibold rounded-full {{ $admin->status == 'active' ? 'bg-emerald-100 text-emerald-800' : 'bg-red-100 text-red-800' }}">
                            {{ $admin->status == 'active' ? 'نشط' : 'غير نشط' }}
                        </span>
                    </td>
                    <td class="px-6 py-4 whitespace-nowrap text-gray-600">{{ $admin->created_at->format('Y-m-d') }}</td>
                    <td class="px-6 py-4 whitespace-nowrap text-sm font-medium space-x-2 space-x-reverse">
                        <button onclick="showUser({{ $admin->id }})" class="bg-indigo-100 text-indigo-600 hover:bg-indigo-200 p-2 rounded-lg transition duration-150 ease-in-out">
                            <i class="fas fa-eye"></i>
                        </button>
                        <button onclick="editUser({{ $admin->id }})" class="bg-amber-100 text-amber-600 hover:bg-amber-200 p-2 rounded-lg transition duration-150 ease-in-out">
                            <i class="fas fa-edit"></i>
                        </button>
                        <button onclick="deleteUser({{ $admin->id }})" class="bg-red-100 text-red-600 hover:bg-red-200 p-2 rounded-lg transition duration-150 ease-in-out">
                            <i class="fas fa-trash"></i>
                        </button>
                    </td>
                </tr>
                @endforeach
            </tbody>
        </table>
    </div>

    <div class="mt-4">
        {{ $admins->links() }}
    </div>
</div>

<!-- Create Modal -->
<div id="createModal" tabindex="-1" class="fixed inset-0 bg-black bg-opacity-50 hidden z-50">
    <div class="bg-white rounded-xl w-1/2 mx-auto mt-20 shadow-xl">
        <div class="bg-gradient-to-r from-indigo-600 to-indigo-700 text-white px-6 py-4 rounded-t-xl flex justify-between items-center">
            <h3 class="text-lg font-bold">إضافة مستخدم جديد</h3>
            <button onclick="closeModal('createModal')" class="text-white hover:text-gray-200 transition duration-150 ease-in-out">
                <i class="fas fa-times"></i>
            </button>
        </div>
        <div class="p-6">
            <form id="createForm" onsubmit="submitCreate(event)">
                @csrf
                <div class="space-y-4">
                    <div>
                        <label class="block text-sm font-medium text-gray-700 mb-1">الاسم</label>
                        <input type="text" name="name" required class="w-full px-4 py-2 border border-gray-300 rounded-lg focus:ring-2 focus:ring-indigo-500 focus:border-indigo-500 transition duration-150 ease-in-out">
                    </div>
                    <div>
                        <label class="block text-sm font-medium text-gray-700 mb-1">البريد الإلكتروني</label>
                        <input type="email" name="email" required class="w-full px-4 py-2 border border-gray-300 rounded-lg focus:ring-2 focus:ring-indigo-500 focus:border-indigo-500 transition duration-150 ease-in-out">
                    </div>
                    <div>
                        <label class="block text-sm font-medium text-gray-700 mb-1">كلمة المرور</label>
                        <input type="password" name="password" required class="w-full px-4 py-2 border border-gray-300 rounded-lg focus:ring-2 focus:ring-indigo-500 focus:border-indigo-500 transition duration-150 ease-in-out">
                    </div>
                    <div>
                        <label class="block text-sm font-medium text-gray-700 mb-1">الدور</label>
                        <select name="role" required class="w-full px-4 py-2 border border-gray-300 rounded-lg focus:ring-2 focus:ring-indigo-500 focus:border-indigo-500 transition duration-150 ease-in-out">
                            <option value="admin">مدير</option>
                            <option value="user">مستخدم</option>
                        </select>
                    </div>
                    <div>
                        <label class="block text-sm font-medium text-gray-700 mb-1">الحالة</label>
                        <select name="status" required class="w-full px-4 py-2 border border-gray-300 rounded-lg focus:ring-2 focus:ring-indigo-500 focus:border-indigo-500 transition duration-150 ease-in-out">
                            <option value="active">نشط</option>
                            <option value="inactive">غير نشط</option>
                        </select>
                    </div>
                </div>
                <div class="mt-6 flex justify-end space-x-3 space-x-reverse">
                    <button type="button" onclick="closeModal('createModal')" class="px-6 py-2.5 bg-gray-500 text-white rounded-lg hover:bg-gray-600 transition duration-150 ease-in-out">
                        إلغاء
                    </button>
                    <button type="submit" class="px-6 py-2.5 bg-gradient-to-r from-indigo-600 to-indigo-700 hover:from-indigo-700 hover:to-indigo-800 text-white rounded-lg transition duration-150 ease-in-out">
                        حفظ
                    </button>
                </div>
            </form>
        </div>
    </div>
</div>

<!-- Edit Modal -->
<div id="editModal" tabindex="-1" class="fixed inset-0 bg-black bg-opacity-50 hidden z-50">
    <div class="bg-white rounded-xl w-1/2 mx-auto mt-20 shadow-xl">
        <div class="bg-gradient-to-r from-amber-500 to-amber-600 text-white px-6 py-4 rounded-t-xl flex justify-between items-center">
            <h3 class="text-lg font-bold">تعديل المستخدم</h3>
            <button onclick="closeModal('editModal')" class="text-white hover:text-gray-200 transition duration-150 ease-in-out">
                <i class="fas fa-times"></i>
            </button>
        </div>
        <div class="p-6">
            <form id="editForm" onsubmit="submitEdit(event)">
                @csrf
                @method('PUT')
                <input type="hidden" id="editUserId" name="id">
                <div class="space-y-4">
                    <div>
                        <label class="block text-sm font-medium text-gray-700 mb-1">الاسم</label>
                        <input type="text" id="editName" name="name" required class="w-full px-4 py-2 border border-gray-300 rounded-lg focus:ring-2 focus:ring-amber-500 focus:border-amber-500 transition duration-150 ease-in-out">
                    </div>
                    <div>
                        <label class="block text-sm font-medium text-gray-700 mb-1">البريد الإلكتروني</label>
                        <input type="email" id="editEmail" name="email" required class="w-full px-4 py-2 border border-gray-300 rounded-lg focus:ring-2 focus:ring-amber-500 focus:border-amber-500 transition duration-150 ease-in-out">
                    </div>
                    <div>
                        <label class="block text-sm font-medium text-gray-700 mb-1">كلمة المرور</label>
                        <input type="password" name="password" class="w-full px-4 py-2 border border-gray-300 rounded-lg focus:ring-2 focus:ring-amber-500 focus:border-amber-500 transition duration-150 ease-in-out">
                        <p class="mt-1 text-sm text-gray-500">اتركه فارغاً إذا لم ترد تغيير كلمة المرور</p>
                    </div>
                    <div>
                        <label class="block text-sm font-medium text-gray-700 mb-1">الدور</label>
                        <select id="editRole" name="role" required class="w-full px-4 py-2 border border-gray-300 rounded-lg focus:ring-2 focus:ring-amber-500 focus:border-amber-500 transition duration-150 ease-in-out">
                            <option value="admin">مدير</option>
                            <option value="user">مستخدم</option>
                        </select>
                    </div>
                    <div>
                        <label class="block text-sm font-medium text-gray-700 mb-1">الحالة</label>
                        <select id="editStatus" name="status" required class="w-full px-4 py-2 border border-gray-300 rounded-lg focus:ring-2 focus:ring-amber-500 focus:border-amber-500 transition duration-150 ease-in-out">
                            <option value="active">نشط</option>
                            <option value="inactive">غير نشط</option>
                        </select>
                    </div>
                </div>
                <div class="mt-6 flex justify-end space-x-3 space-x-reverse">
                    <button type="button" onclick="closeModal('editModal')" class="px-6 py-2.5 bg-gray-500 text-white rounded-lg hover:bg-gray-600 transition duration-150 ease-in-out">
                        إلغاء
                    </button>
                    <button type="submit" class="px-6 py-2.5 bg-gradient-to-r from-amber-500 to-amber-600 hover:from-amber-600 hover:to-amber-700 text-white rounded-lg transition duration-150 ease-in-out">
                        حفظ التغييرات
                    </button>
                </div>
            </form>
        </div>
    </div>
</div>

<!-- Show Modal -->
<div id="showModal" tabindex="-1" class="fixed inset-0 bg-black bg-opacity-50 hidden z-50">
    <div class="bg-white rounded-xl w-1/2 mx-auto mt-20 shadow-xl">
        <div class="bg-gradient-to-r from-indigo-600 to-indigo-700 text-white px-6 py-4 rounded-t-xl flex justify-between items-center">
            <h3 class="text-lg font-bold">عرض تفاصيل المستخدم</h3>
            <button onclick="closeModal('showModal')" class="text-white hover:text-gray-200 transition duration-150 ease-in-out">
                <i class="fas fa-times"></i>
            </button>
        </div>
        <div class="p-6">
            <div class="space-y-4">
                <div class="grid grid-cols-2 gap-4">
                    <div>
                        <label class="block text-sm font-medium text-gray-700 mb-1">الاسم</label>
                        <p id="showName" class="text-gray-900 bg-gray-50 px-4 py-2.5 rounded-lg"></p>
                    </div>
                    <div>
                        <label class="block text-sm font-medium text-gray-700 mb-1">البريد الإلكتروني</label>
                        <p id="showEmail" class="text-gray-900 bg-gray-50 px-4 py-2.5 rounded-lg"></p>
                    </div>
                    <div>
                        <label class="block text-sm font-medium text-gray-700 mb-1">الدور</label>
                        <p id="showRole" class="text-gray-900 bg-gray-50 px-4 py-2.5 rounded-lg"></p>
                    </div>
                    <div>
                        <label class="block text-sm font-medium text-gray-700 mb-1">الحالة</label>
                        <p id="showStatus" class="text-gray-900 bg-gray-50 px-4 py-2.5 rounded-lg"></p>
                    </div>
                </div>
            </div>
            <div class="mt-6 flex justify-end">
                <button onclick="closeModal('showModal')" class="px-6 py-2.5 bg-gray-500 text-white rounded-lg hover:bg-gray-600 transition duration-150 ease-in-out">
                    إغلاق
                </button>
            </div>
        </div>
    </div>
</div>

@push('scripts')
<script>
    $(document).ready(function() {
        // تهيئة البحث
        $('#searchInput').on('keyup', function() {
            var value = $(this).val().toLowerCase();
            $('tbody tr').filter(function() {
                $(this).toggle($(this).text().toLowerCase().indexOf(value) > -1)
            });
        });
    });

    // دالة فتح نافذة الإضافة
    function openCreateModal() {
        showModal('createModal');
    }

    // دالة عرض المستخدم
    function showUser(id) {
        $.ajax({
            url: `/permissions/${id}`,
            method: 'GET',
            success: function(response) {
                $('#showName').text(response.name);
                $('#showEmail').text(response.email);
                $('#showRole').text(response.role === 'admin' ? 'مدير' : 'مستخدم');
                $('#showStatus').text(response.status === 'active' ? 'نشط' : 'غير نشط');
                showModal('showModal');
            },
            error: function(xhr) {
                Swal.fire({
                    title: 'خطأ!',
                    text: 'حدث خطأ أثناء جلب بيانات المستخدم',
                    icon: 'error'
                });
            }
        });
    }

    // دالة تعديل المستخدم
    function editUser(id) {
        $.ajax({
            url: `/permissions/${id}/edit`,
            method: 'GET',
            success: function(response) {
                $('#editUserId').val(response.id);
                $('#editName').val(response.name);
                $('#editEmail').val(response.email);
                $('#editRole').val(response.role);
                $('#editStatus').val(response.status);
                showModal('editModal');
            },
            error: function(xhr) {
                Swal.fire({
                    title: 'خطأ!',
                    text: 'حدث خطأ أثناء جلب بيانات المستخدم',
                    icon: 'error'
                });
            }
        });
    }

    // دالة حذف المستخدم
    function deleteUser(id) {
        Swal.fire({
            title: 'هل أنت متأكد؟',
            text: 'سيتم حذف المستخدم نهائياً',
            icon: 'warning',
            showCancelButton: true,
            confirmButtonText: 'نعم، احذف',
            cancelButtonText: 'إلغاء'
        }).then((result) => {
            if (result.isConfirmed) {
                $.ajax({
                    url: `/permissions/${id}`,
                    method: 'DELETE',
                    headers: {
                        'X-CSRF-TOKEN': $('meta[name="csrf-token"]').attr('content')
                    },
                    success: function(response) {
                        Swal.fire({
                            title: 'تم!',
                            text: 'تم حذف المستخدم بنجاح',
                            icon: 'success'
                        }).then(() => {
                            location.reload();
                        });
                    },
                    error: function(xhr) {
                        Swal.fire({
                            title: 'خطأ!',
                            text: 'حدث خطأ أثناء حذف المستخدم',
                            icon: 'error'
                        });
                    }
                });
            }
        });
    }

    // دالة إرسال نموذج الإضافة
    function submitCreate(event) {
        event.preventDefault();
        var form = $('#createForm');
        $.ajax({
            url: '/permissions',
            method: 'POST',
            data: form.serialize(),
            success: function(response) {
                hideModal('createModal');
                Swal.fire({
                    title: 'تم!',
                    text: 'تم إضافة المستخدم بنجاح',
                    icon: 'success'
                }).then(() => {
                    location.reload();
                });
            },
            error: function(xhr) {
                Swal.fire({
                    title: 'خطأ!',
                    text: 'حدث خطأ أثناء إضافة المستخدم',
                    icon: 'error'
                });
            }
        });
    }

    // دالة إرسال نموذج التعديل
    function submitEdit(event) {
        event.preventDefault();
        var form = $('#editForm');
        var id = $('#editUserId').val();
        $.ajax({
            url: `/permissions/${id}`,
            method: 'PUT',
            data: form.serialize(),
            success: function(response) {
                hideModal('editModal');
                Swal.fire({
                    title: 'تم!',
                    text: 'تم تحديث بيانات المستخدم بنجاح',
                    icon: 'success'
                }).then(() => {
                    location.reload();
                });
            },
            error: function(xhr) {
                Swal.fire({
                    title: 'خطأ!',
                    text: 'حدث خطأ أثناء تحديث بيانات المستخدم',
                    icon: 'error'
                });
            }
        });
    }

    // دالة إغلاق النوافذ المنبثقة
    function closeModal(modalId) {
        hideModal(modalId);
    }
</script>
@endpush
@endsection
