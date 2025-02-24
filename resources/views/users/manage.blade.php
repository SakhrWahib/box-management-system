@extends('layouts.app')

@section('content')
<div class="p-6 bg-gray-50">
    <div class="flex justify-between items-center mb-6">
        <div class="flex items-center space-x-4 space-x-reverse">
            <h2 class="text-2xl font-bold text-gray-800">إدارة المستخدمين</h2>
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
                    <th class="px-6 py-4 text-right text-xs font-semibold text-gray-600 uppercase tracking-wider">رقم الهاتف</th>
                    <th class="px-6 py-4 text-right text-xs font-semibold text-gray-600 uppercase tracking-wider">تاريخ التسجيل</th>
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
                        <div class="text-sm text-gray-900">{{ $user->phone_number }}</div>
                    </td>
                    <td class="px-6 py-4 whitespace-nowrap text-sm text-gray-500">
                        {{ $user->created_at->format('Y/m/d') }}
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
    </div>

    <!-- Pagination -->
    <div class="mt-4">
        {{ $users->links() }}
    </div>
</div>

<!-- Create/Edit Modal -->
<div id="userModal" class="fixed inset-0 bg-gray-600 bg-opacity-50 hidden overflow-y-auto h-full w-full">
    <div class="relative top-20 mx-auto p-5 border w-96 shadow-lg rounded-md bg-white">
        <div class="mt-3">
            <h3 class="text-lg font-medium text-gray-900 mb-4" id="modalTitle">إضافة مستخدم جديد</h3>
            <form id="userForm" onsubmit="submitForm(event)">
                @csrf
                <input type="hidden" id="userId" name="id">
                
                <div class="mb-4">
                    <label class="block text-gray-700 text-sm font-bold mb-2" for="name">الاسم</label>
                    <input type="text" id="name" name="name" required
                        class="shadow appearance-none border rounded w-full py-2 px-3 text-gray-700 leading-tight focus:outline-none focus:shadow-outline">
                </div>

                <div class="mb-4">
                    <label class="block text-gray-700 text-sm font-bold mb-2" for="email">البريد الإلكتروني</label>
                    <input type="email" id="email" name="email" required
                        class="shadow appearance-none border rounded w-full py-2 px-3 text-gray-700 leading-tight focus:outline-none focus:shadow-outline">
                </div>

                <div class="mb-4">
                    <label class="block text-gray-700 text-sm font-bold mb-2" for="phone_number">رقم الهاتف</label>
                    <input type="text" id="phone_number" name="phone_number" required
                        class="shadow appearance-none border rounded w-full py-2 px-3 text-gray-700 leading-tight focus:outline-none focus:shadow-outline">
                </div>

                <div class="mb-4" id="passwordField">
                    <label class="block text-gray-700 text-sm font-bold mb-2" for="password">كلمة المرور</label>
                    <input type="password" id="password" name="password"
                        class="shadow appearance-none border rounded w-full py-2 px-3 text-gray-700 leading-tight focus:outline-none focus:shadow-outline">
                </div>

                <div class="flex justify-end">
                    <button type="button" onclick="closeModal()" class="bg-gray-500 text-white px-4 py-2 rounded-lg ml-2">إلغاء</button>
                    <button type="submit" class="bg-indigo-600 text-white px-4 py-2 rounded-lg">حفظ</button>
                </div>
            </form>
        </div>
    </div>
</div>

<!-- Show User Modal -->
<div id="showUserModal" class="fixed inset-0 bg-gray-600 bg-opacity-50 hidden overflow-y-auto h-full w-full">
    <div class="relative top-20 mx-auto p-5 border w-96 shadow-lg rounded-md bg-white">
        <div class="mt-3">
            <h3 class="text-lg font-medium text-gray-900 mb-4">تفاصيل المستخدم</h3>
            <div class="space-y-4">
                <div>
                    <label class="block text-gray-700 text-sm font-bold mb-2">الاسم</label>
                    <p id="show_name" class="text-gray-600"></p>
                </div>
                <div>
                    <label class="block text-gray-700 text-sm font-bold mb-2">البريد الإلكتروني</label>
                    <p id="show_email" class="text-gray-600"></p>
                </div>
                <div>
                    <label class="block text-gray-700 text-sm font-bold mb-2">رقم الهاتف</label>
                    <p id="show_phone" class="text-gray-600"></p>
                </div>
                <div>
                    <label class="block text-gray-700 text-sm font-bold mb-2">تاريخ التسجيل</label>
                    <p id="show_created_at" class="text-gray-600"></p>
                </div>
            </div>
            <div class="mt-4 flex justify-end">
                <button onclick="closeShowModal()" class="bg-gray-500 text-white px-4 py-2 rounded-lg">إغلاق</button>
            </div>
        </div>
    </div>
</div>

@push('scripts')
<script>
let isEditing = false;

function openCreateModal() {
    isEditing = false;
    document.getElementById('modalTitle').textContent = 'إضافة مستخدم جديد';
    document.getElementById('userForm').reset();
    document.getElementById('passwordField').style.display = 'block';
    document.getElementById('userModal').classList.remove('hidden');
}

function closeModal() {
    document.getElementById('userModal').classList.add('hidden');
}

function editUser(id) {
    isEditing = true;
    fetch(`/users/${id}`)
        .then(response => response.json())
        .then(user => {
            document.getElementById('modalTitle').textContent = 'تعديل المستخدم';
            document.getElementById('userId').value = user.id;
            document.getElementById('name').value = user.name;
            document.getElementById('email').value = user.email;
            document.getElementById('phone_number').value = user.phone_number;
            
            // إضافة حقل _method للتعامل مع PUT
            const form = document.getElementById('userForm');
            let methodInput = form.querySelector('input[name="_method"]');
            if (!methodInput) {
                methodInput = document.createElement('input');
                methodInput.type = 'hidden';
                methodInput.name = '_method';
                form.appendChild(methodInput);
            }
            methodInput.value = 'PUT';
            
            // إخفاء حقل كلمة المرور في حالة التعديل
            document.getElementById('passwordField').style.display = 'none';
            
            document.getElementById('userModal').classList.remove('hidden');
        })
        .catch(error => {
            console.error('Error:', error);
            alert('حدث خطأ أثناء تحميل بيانات المستخدم');
        });
}

function submitForm(e) {
    e.preventDefault();
    const form = document.getElementById('userForm');
    const formData = new FormData(form);
    const userId = document.getElementById('userId').value;
    const url = isEditing ? `/users/${userId}` : '/users';
    const method = isEditing ? 'PUT' : 'POST';

    const data = {};
    formData.forEach((value, key) => {
        if (key !== '_token' && key !== '_method') {
            data[key] = value;
        }
    });

    fetch(url, {
        method: method,
        headers: {
            'X-CSRF-TOKEN': document.querySelector('meta[name="csrf-token"]').content,
            'Accept': 'application/json',
            'Content-Type': 'application/json'
        },
        body: JSON.stringify(data)
    })
    .then(response => response.json())
    .then(data => {
        if (data.success) {
            alert(data.message);
            location.reload();
        } else {
            throw new Error(data.message || 'حدث خطأ أثناء حفظ البيانات');
        }
    })
    .catch(error => {
        console.error('Error:', error);
        alert('حدث خطأ: ' + error.message);
    });
}

function deleteUser(id) {
    if (confirm('هل أنت متأكد من حذف هذا المستخدم؟')) {
        fetch(`/users/${id}`, {
            method: 'DELETE',
            headers: {
                'X-CSRF-TOKEN': document.querySelector('meta[name="csrf-token"]').content,
                'Accept': 'application/json'
            }
        })
        .then(response => response.json())
        .then(data => {
            if (data.success) {
                location.reload();
            } else {
                alert('حدث خطأ أثناء حذف المستخدم');
            }
        });
    }
}

function showUser(id) {
    fetch(`/users/${id}`)
        .then(response => response.json())
        .then(user => {
            document.getElementById('show_name').textContent = user.name;
            document.getElementById('show_email').textContent = user.email;
            document.getElementById('show_phone').textContent = user.phone_number;
            document.getElementById('show_created_at').textContent = new Date(user.created_at).toLocaleDateString('ar-SA');
            document.getElementById('showUserModal').classList.remove('hidden');
        });
}

function closeShowModal() {
    document.getElementById('showUserModal').classList.add('hidden');
}

// Search functionality
document.getElementById('searchInput').addEventListener('input', function(e) {
    const searchTerm = e.target.value;
    fetch(`/users/manage?search=${searchTerm}`, {
        headers: {
            'X-Requested-With': 'XMLHttpRequest'
        }
    })
    .then(response => response.json())
    .then(data => {
        const tbody = document.querySelector('tbody');
        tbody.innerHTML = '';
        
        data.users.data.forEach(user => {
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
                        <div class="text-sm text-gray-900">${user.phone_number}</div>
                    </td>
                    <td class="px-6 py-4 whitespace-nowrap text-sm text-gray-500">
                        ${new Date(user.created_at).toLocaleDateString('ar-SA')}
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
</script>
@endpush
@endsection
