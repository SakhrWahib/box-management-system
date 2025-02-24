@extends('layouts.storehouse')

@section('content')
<div class="p-6 bg-gray-50">
    <div class="flex justify-between items-center mb-6">
        <div class="flex items-center space-x-4 space-x-reverse">
            <h2 class="text-2xl font-bold text-gray-800">إدارة مستخدمي المستودع</h2>
            <div class="relative">
                <input type="text" 
                    id="searchInput" 
                    placeholder="بحث بالاسم أو البريد الإلكتروني" 
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
                    <th class="px-6 py-4 text-right text-xs font-semibold text-gray-600 uppercase tracking-wider">الدور</th>
                    <th class="px-6 py-4 text-right text-xs font-semibold text-gray-600 uppercase tracking-wider">الحالة</th>
                    <th class="px-6 py-4 text-right text-xs font-semibold text-gray-600 uppercase tracking-wider">الإجراءات</th>
                </tr>
            </thead>
            <tbody class="bg-white divide-y divide-gray-200">
                @foreach($users as $user)
                <tr class="hover:bg-gray-50">
                    <td class="px-6 py-4 whitespace-nowrap">
                        <div class="flex items-center">
                            <div class="flex-shrink-0 h-10 w-10">
                                <div class="h-10 w-10 rounded-full bg-indigo-100 flex items-center justify-center">
                                    <i class="fas fa-user text-indigo-600"></i>
                                </div>
                            </div>
                            <div class="mr-4">
                                <div class="text-sm font-medium text-gray-900">{{ $user->name }}</div>
                            </div>
                        </div>
                    </td>
                    <td class="px-6 py-4 whitespace-nowrap">
                        <div class="text-sm text-gray-900">{{ $user->email }}</div>
                    </td>
                    <td class="px-6 py-4 whitespace-nowrap">
                        <span class="px-3 py-1 inline-flex text-xs leading-5 font-semibold rounded-full {{ $user->role === 'admin' ? 'bg-purple-100 text-purple-800' : 'bg-green-100 text-green-800' }}">
                            {{ $user->role === 'admin' ? 'مدير' : 'موظف' }}
                        </span>
                    </td>
                    <td class="px-6 py-4 whitespace-nowrap">
                        <span class="px-3 py-1 inline-flex text-xs leading-5 font-semibold rounded-full {{ $user->status ? 'bg-green-100 text-green-800' : 'bg-red-100 text-red-800' }}">
                            {{ $user->status ? 'نشط' : 'غير نشط' }}
                        </span>
                    </td>
                    <td class="px-6 py-4 whitespace-nowrap text-left text-sm font-medium">
                        <button onclick="showUser({{ $user->id }})" class="text-blue-600 hover:text-blue-900 ml-3">
                            <i class="fas fa-eye"></i>
                        </button>
                        <button onclick="editUser({{ $user->id }})" class="text-indigo-600 hover:text-indigo-900 ml-3">
                            <i class="fas fa-edit"></i>
                        </button>
                        <button onclick="deleteUser({{ $user->id }})" class="text-red-600 hover:text-red-900">
                            <i class="fas fa-trash"></i>
                        </button>
                    </td>
                </tr>
                @endforeach
            </tbody>
        </table>
        <div class="px-6 py-4">
            {{ $users->links() }}
        </div>
    </div>

    <!-- Create Modal -->
    <div id="createModal" class="fixed inset-0 bg-gray-600 bg-opacity-50 hidden overflow-y-auto h-full w-full">
        <div class="relative top-20 mx-auto p-5 border w-96 shadow-lg rounded-md bg-white">
            <div class="mt-3">
                <h3 class="text-lg leading-6 font-medium text-gray-900">إضافة مستخدم جديد</h3>
                <form id="createForm" class="mt-4">
                    @csrf
                    <div class="mb-4">
                        <label class="block text-gray-700 text-sm font-bold mb-2" for="name">الاسم</label>
                        <input type="text" name="name" id="name" class="shadow appearance-none border rounded w-full py-2 px-3 text-gray-700 leading-tight focus:outline-none focus:shadow-outline">
                    </div>
                    <div class="mb-4">
                        <label class="block text-gray-700 text-sm font-bold mb-2" for="email">البريد الإلكتروني</label>
                        <input type="email" name="email" id="email" class="shadow appearance-none border rounded w-full py-2 px-3 text-gray-700 leading-tight focus:outline-none focus:shadow-outline">
                    </div>
                    <div class="mb-4">
                        <label class="block text-gray-700 text-sm font-bold mb-2" for="password">كلمة المرور</label>
                        <input type="password" name="password" id="password" class="shadow appearance-none border rounded w-full py-2 px-3 text-gray-700 leading-tight focus:outline-none focus:shadow-outline">
                    </div>
                    <div class="mb-4">
                        <label class="block text-gray-700 text-sm font-bold mb-2" for="role">الدور</label>
                        <select name="role" id="role" class="shadow border rounded w-full py-2 px-3 text-gray-700 leading-tight focus:outline-none focus:shadow-outline">
                            <option value="admin">مدير</option>
                            <option value="employee">موظف</option>
                        </select>
                    </div>
                    <div class="flex justify-end">
                        <button type="button" onclick="closeCreateModal()" class="ml-3 px-4 py-2 bg-gray-300 text-gray-800 rounded hover:bg-gray-400">إلغاء</button>
                        <button type="submit" class="px-4 py-2 bg-indigo-600 text-white rounded hover:bg-indigo-700">حفظ</button>
                    </div>
                </form>
            </div>
        </div>
    </div>

    <!-- Edit Modal -->
    <div id="editModal" class="fixed inset-0 bg-gray-600 bg-opacity-50 hidden overflow-y-auto h-full w-full">
        <div class="relative top-20 mx-auto p-5 border w-96 shadow-lg rounded-md bg-white">
            <div class="mt-3 text-center">
                <h3 class="text-lg leading-6 font-medium text-gray-900">تعديل بيانات المستخدم</h3>
                <form id="editForm" class="mt-4">
                    <input type="hidden" id="edit_id">
                    <div class="mb-4">
                        <label class="block text-gray-700 text-sm font-bold mb-2">الاسم</label>
                        <input type="text" id="edit_name" class="shadow appearance-none border rounded w-full py-2 px-3 text-gray-700 leading-tight focus:outline-none focus:shadow-outline">
                    </div>
                    <div class="mb-4">
                        <label class="block text-gray-700 text-sm font-bold mb-2">البريد الإلكتروني</label>
                        <input type="email" id="edit_email" class="shadow appearance-none border rounded w-full py-2 px-3 text-gray-700 leading-tight focus:outline-none focus:shadow-outline">
                    </div>
                    <div class="mb-4">
                        <label class="block text-gray-700 text-sm font-bold mb-2">الدور</label>
                        <select id="edit_role" class="shadow border rounded w-full py-2 px-3 text-gray-700 leading-tight focus:outline-none focus:shadow-outline">
                            <option value="admin">مدير</option>
                            <option value="employee">موظف</option>
                        </select>
                    </div>
                    <div class="mb-4">
                        <label class="block text-gray-700 text-sm font-bold mb-2">كلمة المرور (اتركها فارغة إذا لم ترد تغييرها)</label>
                        <input type="password" id="edit_password" class="shadow appearance-none border rounded w-full py-2 px-3 text-gray-700 leading-tight focus:outline-none focus:shadow-outline">
                    </div>
                    <div class="flex justify-between mt-4">
                        <button type="submit" class="bg-indigo-500 text-white px-4 py-2 rounded-lg hover:bg-indigo-600">حفظ التغييرات</button>
                        <button type="button" onclick="closeEditModal()" class="bg-gray-500 text-white px-4 py-2 rounded-lg hover:bg-gray-600">إلغاء</button>
                    </div>
                </form>
            </div>
        </div>
    </div>

    <!-- Show Modal -->
    <div id="showModal" class="fixed inset-0 bg-gray-600 bg-opacity-50 hidden overflow-y-auto h-full w-full">
        <div class="relative top-20 mx-auto p-5 border w-96 shadow-lg rounded-md bg-white">
            <div class="mt-3">
                <h3 class="text-lg leading-6 font-medium text-gray-900">تفاصيل المستخدم</h3>
                <div class="mt-4">
                    <div class="mb-4">
                        <label class="block text-gray-700 text-sm font-bold mb-2">الاسم</label>
                        <p id="show_name" class="text-gray-600"></p>
                    </div>
                    <div class="mb-4">
                        <label class="block text-gray-700 text-sm font-bold mb-2">البريد الإلكتروني</label>
                        <p id="show_email" class="text-gray-600"></p>
                    </div>
                    <div class="mb-4">
                        <label class="block text-gray-700 text-sm font-bold mb-2">الدور</label>
                        <p id="show_role" class="text-gray-600"></p>
                    </div>
                    <div class="mb-4">
                        <label class="block text-gray-700 text-sm font-bold mb-2">الحالة</label>
                        <p id="show_status" class="text-gray-600"></p>
                    </div>
                    <div class="flex justify-end">
                        <button type="button" onclick="closeShowModal()" class="px-4 py-2 bg-gray-300 text-gray-800 rounded hover:bg-gray-400">إغلاق</button>
                    </div>
                </div>
            </div>
        </div>
    </div>
</div>

@push('scripts')
<script>
function openCreateModal() {
    document.getElementById('createModal').classList.remove('hidden');
}

function closeCreateModal() {
    document.getElementById('createModal').classList.add('hidden');
    document.getElementById('createForm').reset();
}

function openEditModal() {
    document.getElementById('editModal').classList.remove('hidden');
}

function closeEditModal() {
    document.getElementById('editModal').classList.add('hidden');
}

function openShowModal() {
    document.getElementById('showModal').classList.remove('hidden');
}

function closeShowModal() {
    document.getElementById('showModal').classList.add('hidden');
}

// Create Form Submit
document.getElementById('createForm').addEventListener('submit', function(e) {
    e.preventDefault();
    
    const formData = new FormData(this);
    const data = {};
    formData.forEach((value, key) => data[key] = value);
    
    fetch('/storehouse-users', {
        method: 'POST',
        headers: {
            'X-CSRF-TOKEN': document.querySelector('meta[name="csrf-token"]').content,
            'Accept': 'application/json',
            'Content-Type': 'application/json'
        },
        body: JSON.stringify(data)
    })
    .then(response => {
        if (!response.ok) {
            return response.json().then(err => Promise.reject(err));
        }
        return response.json();
    })
    .then(data => {
        if (data.success) {
            Swal.fire({
                icon: 'success',
                title: 'تم بنجاح',
                text: data.message
            }).then(() => {
                window.location.reload();
            });
        }
    })
    .catch(error => {
        Swal.fire({
            icon: 'error',
            title: 'خطأ',
            text: error.message || 'حدث خطأ أثناء إضافة المستخدم'
        });
    });
});

// Edit User
function editUser(id) {
    fetch(`/storehouse-users/${id}`)
        .then(response => response.json())
        .then(user => {
            Swal.fire({
                title: 'تعديل المستخدم',
                html: `
                    <form id="editForm" class="text-right">
                        <div class="mb-4">
                            <label class="block text-gray-700 text-sm font-bold mb-2">الاسم</label>
                            <input type="text" name="name" value="${user.name}" 
                                   class="w-full p-2 border rounded">
                        </div>
                        <div class="mb-4">
                            <label class="block text-gray-700 text-sm font-bold mb-2">البريد الإلكتروني</label>
                            <input type="email" name="email" value="${user.email}" 
                                   class="w-full p-2 border rounded">
                        </div>
                        <div class="mb-4">
                            <label class="block text-gray-700 text-sm font-bold mb-2">الدور</label>
                            <select name="role" class="w-full p-2 border rounded">
                                <option value="employee" ${user.role === 'employee' ? 'selected' : ''}>موظف</option>
                                <option value="admin" ${user.role === 'admin' ? 'selected' : ''}>مدير</option>
                            </select>
                        </div>
                    </form>
                `,
                showCancelButton: true,
                confirmButtonText: 'حفظ',
                cancelButtonText: 'إلغاء',
                preConfirm: () => {
                    const form = document.getElementById('editForm');
                    const formData = new FormData(form);
                    const data = {};
                    formData.forEach((value, key) => data[key] = value);
                    
                    return fetch(`/storehouse-users/${id}`, {
                        method: 'PUT',
                        headers: {
                            'X-CSRF-TOKEN': document.querySelector('meta[name="csrf-token"]').content,
                            'Content-Type': 'application/json',
                            'Accept': 'application/json'
                        },
                        body: JSON.stringify(data)
                    })
                    .then(response => {
                        if (!response.ok) {
                            return response.json().then(err => Promise.reject(err));
                        }
                        return response.json();
                    });
                }
            }).then((result) => {
                if (result.isConfirmed) {
                    Swal.fire({
                        icon: 'success',
                        title: 'تم التحديث بنجاح',
                        text: 'تم تحديث بيانات المستخدم بنجاح'
                    }).then(() => {
                        window.location.reload();
                    });
                }
            }).catch(error => {
                Swal.fire({
                    icon: 'error',
                    title: 'خطأ',
                    text: error.message || 'حدث خطأ أثناء تحديث المستخدم'
                });
            });
        });
}

// Show User
function showUser(id) {
    fetch(`/storehouse-users/${id}`)
        .then(response => response.json())
        .then(user => {
            Swal.fire({
                title: 'تفاصيل المستخدم',
                html: `
                    <div class="text-right">
                        <p><strong>الاسم:</strong> ${user.name}</p>
                        <p><strong>البريد الإلكتروني:</strong> ${user.email}</p>
                        <p><strong>الدور:</strong> ${user.role === 'admin' ? 'مدير' : 'موظف'}</p>
                        <p><strong>الحالة:</strong> ${user.status ? 'نشط' : 'غير نشط'}</p>
                    </div>
                `,
                confirmButtonText: 'إغلاق'
            });
        });
}

// Delete User
function deleteUser(id) {
    if (confirm('هل أنت متأكد من حذف هذا المستخدم؟')) {
        fetch(`/storehouse-users/${id}`, {
            method: 'DELETE',
            headers: {
                'X-CSRF-TOKEN': document.querySelector('meta[name="csrf-token"]').getAttribute('content'),
                'Accept': 'application/json',
            }
        })
        .then(response => response.json())
        .then(data => {
            if (data.success) {
                location.reload();
            }
        });
    }
}

// Search functionality
document.getElementById('searchInput').addEventListener('input', function(e) {
    const searchTerm = e.target.value;
    fetch(`/storehouse-users?search=${searchTerm}`, {
        headers: {
            'X-Requested-With': 'XMLHttpRequest'
        }
    })
    .then(response => response.json())
    .then(data => {
        const tbody = document.querySelector('tbody');
        tbody.innerHTML = '';
        
        data.forEach(user => {
            tbody.innerHTML += `
                <tr class="hover:bg-gray-50">
                    <td class="px-6 py-4 whitespace-nowrap">
                        <div class="flex items-center">
                            <div class="flex-shrink-0 h-10 w-10">
                                <div class="h-10 w-10 rounded-full bg-indigo-100 flex items-center justify-center">
                                    <i class="fas fa-user text-indigo-600"></i>
                                </div>
                            </div>
                            <div class="mr-4">
                                <div class="text-sm font-medium text-gray-900">${user.name}</div>
                            </div>
                        </div>
                    </td>
                    <td class="px-6 py-4 whitespace-nowrap">
                        <div class="text-sm text-gray-900">${user.email}</div>
                    </td>
                    <td class="px-6 py-4 whitespace-nowrap">
                        <span class="px-3 py-1 inline-flex text-xs leading-5 font-semibold rounded-full ${user.role === 'admin' ? 'bg-purple-100 text-purple-800' : 'bg-green-100 text-green-800'}">
                            ${user.role === 'admin' ? 'مدير' : 'موظف'}
                        </span>
                    </td>
                    <td class="px-6 py-4 whitespace-nowrap">
                        <span class="px-3 py-1 inline-flex text-xs leading-5 font-semibold rounded-full ${user.status ? 'bg-green-100 text-green-800' : 'bg-red-100 text-red-800'}">
                            ${user.status ? 'نشط' : 'غير نشط'}
                        </span>
                    </td>
                    <td class="px-6 py-4 whitespace-nowrap text-left text-sm font-medium">
                        <button onclick="showUser(${user.id})" class="text-blue-600 hover:text-blue-900 ml-3">
                            <i class="fas fa-eye"></i>
                        </button>
                        <button onclick="editUser(${user.id})" class="text-indigo-600 hover:text-indigo-900 ml-3">
                            <i class="fas fa-edit"></i>
                        </button>
                        <button onclick="deleteUser(${user.id})" class="text-red-600 hover:text-red-900">
                            <i class="fas fa-trash"></i>
                        </button>
                    </td>
                </tr>
            `;
        });
    });
});

// Edit Form Submit
document.getElementById('editForm').addEventListener('submit', function(e) {
    e.preventDefault();
    const id = document.getElementById('edit_id').value;
    
    const formData = {
        name: document.getElementById('edit_name').value,
        email: document.getElementById('edit_email').value,
        role: document.getElementById('edit_role').value,
        password: document.getElementById('edit_password').value
    };
    
    fetch(`/storehouse-users/${id}`, {
        method: 'PUT',
        headers: {
            'Content-Type': 'application/json',
            'X-CSRF-TOKEN': document.querySelector('meta[name="csrf-token"]').getAttribute('content'),
            'Accept': 'application/json'
        },
        body: JSON.stringify(formData)
    })
    .then(response => response.json())
    .then(data => {
        if (data.success) {
            closeEditModal();
            location.reload();
        }
    })
    .catch(error => {
        console.error('Error:', error);
    });
});
</script>
@endpush
@endsection
