@extends('layouts.app')

@section('content')
<div class="p-6 bg-gray-50">
    <div class="flex justify-between items-center mb-6">
        <div class="flex items-center space-x-4 space-x-reverse">
            <h2 class="text-2xl font-bold text-gray-800">إدارة المستخدمين الفرعيين</h2>
            <div class="relative">
                <input type="text" 
                    id="searchInput" 
                    placeholder="بحث باسم المستخدم أو البريد الإلكتروني" 
                    class="pr-10 pl-4 py-2 border rounded-lg focus:outline-none focus:ring-2 focus:ring-indigo-500 focus:border-indigo-500 shadow-sm">
                <i class="fas fa-search absolute right-3 top-3 text-indigo-500"></i>
            </div>
        </div>
        <button onclick="openCreateModal()" class="bg-gradient-to-r from-indigo-500 to-indigo-600 hover:from-indigo-600 hover:to-indigo-700 text-white px-6 py-2.5 rounded-lg flex items-center shadow-md transition duration-200 ease-in-out transform hover:-translate-y-0.5">
            <i class="fas fa-user-plus ml-2"></i>
            إضافة مستخدم فرعي جديد
        </button>
    </div>

    <!-- Subusers Table -->
    <div class="bg-white rounded-xl shadow-lg overflow-hidden">
        <table class="min-w-full divide-y divide-gray-200">
            <thead class="bg-gradient-to-r from-gray-50 to-gray-100">
                <tr>
                    <th class="px-6 py-4 text-right text-xs font-semibold text-gray-600 uppercase tracking-wider">اسم المستخدم</th>
                    <th class="px-6 py-4 text-right text-xs font-semibold text-gray-600 uppercase tracking-wider">البريد الإلكتروني</th>
                    <th class="px-6 py-4 text-right text-xs font-semibold text-gray-600 uppercase tracking-wider">رقم الهاتف</th>
                    <th class="px-6 py-4 text-right text-xs font-semibold text-gray-600 uppercase tracking-wider">المستخدم الرئيسي</th>
                    <th class="px-6 py-4 text-right text-xs font-semibold text-gray-600 uppercase tracking-wider">الجهاز</th>
                    <th class="px-6 py-4 text-right text-xs font-semibold text-gray-600 uppercase tracking-wider">الكود الثابت</th>
                    <th class="px-6 py-4 text-right text-xs font-semibold text-gray-600 uppercase tracking-wider">الإجراءات</th>
                </tr>
            </thead>
            <tbody class="bg-white divide-y divide-gray-200">
                @foreach($subusers as $subuser)
                <tr class="hover:bg-gray-50">
                    <td class="px-6 py-4 whitespace-nowrap">
                        <div class="flex items-center">
                            <div class="flex-shrink-0 h-10 w-10">
                                <div class="h-10 w-10 rounded-full bg-indigo-100 flex items-center justify-center">
                                    <i class="fas fa-user text-indigo-600"></i>
                                </div>
                            </div>
                            <div class="mr-4">
                                <div class="text-sm font-medium text-gray-900">{{ $subuser->username }}</div>
                            </div>
                        </div>
                    </td>
                    <td class="px-6 py-4 whitespace-nowrap">
                        <div class="text-sm text-gray-900">{{ $subuser->email }}</div>
                    </td>
                    <td class="px-6 py-4 whitespace-nowrap">
                        <div class="text-sm text-gray-900">{{ $subuser->phone }}</div>
                    </td>
                    <td class="px-6 py-4 whitespace-nowrap">
                        <div class="text-sm text-gray-900">{{ $subuser->user->name }}</div>
                    </td>
                    <td class="px-6 py-4 whitespace-nowrap">
                        <div class="text-sm text-gray-900">
                            {{ $subuser->device ? $subuser->device->device_name : 'غير محدد' }}
                        </div>
                    </td>
                    <td class="px-6 py-4 whitespace-nowrap">
                        <div class="text-sm text-gray-900">{{ $subuser->const_code }}</div>
                    </td>
                    <td class="px-6 py-4 whitespace-nowrap text-left text-sm font-medium">
                        <button onclick="showSubuser({{ $subuser->id }})" class="text-blue-600 hover:text-blue-900 ml-3">
                            <i class="fas fa-eye"></i>
                        </button>
                        <button onclick="editSubuser({{ $subuser->id }})" class="text-indigo-600 hover:text-indigo-900 ml-3">
                            <i class="fas fa-edit"></i>
                        </button>
                        <button onclick="deleteSubuser({{ $subuser->id }})" class="text-red-600 hover:text-red-900">
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
        {{ $subusers->links() }}
    </div>
</div>

<!-- Create/Edit Modal -->
<div id="subuserModal" class="fixed inset-0 bg-gray-600 bg-opacity-50 hidden overflow-y-auto h-full w-full">
    <div class="relative top-20 mx-auto p-5 border w-96 shadow-lg rounded-md bg-white">
        <div class="mt-3">
            <h3 class="text-lg font-medium text-gray-900 mb-4" id="modalTitle">إضافة مستخدم فرعي جديد</h3>
            <form id="subuserForm" onsubmit="submitForm(event)">
                @csrf
                <input type="hidden" id="subuserId" name="id">
                
                <div class="mb-4">
                    <label class="block text-gray-700 text-sm font-bold mb-2" for="username">اسم المستخدم</label>
                    <input type="text" id="username" name="username" required
                        class="shadow appearance-none border rounded w-full py-2 px-3 text-gray-700 leading-tight focus:outline-none focus:shadow-outline">
                </div>

                <div class="mb-4">
                    <label class="block text-gray-700 text-sm font-bold mb-2" for="email">البريد الإلكتروني</label>
                    <input type="email" id="email" name="email" required
                        class="shadow appearance-none border rounded w-full py-2 px-3 text-gray-700 leading-tight focus:outline-none focus:shadow-outline">
                </div>

                <div class="mb-4">
                    <label class="block text-gray-700 text-sm font-bold mb-2" for="phone">رقم الهاتف</label>
                    <input type="text" id="phone" name="phone"
                        class="shadow appearance-none border rounded w-full py-2 px-3 text-gray-700 leading-tight focus:outline-none focus:shadow-outline">
                </div>

                <div class="mb-4">
                    <label class="block text-gray-700 text-sm font-bold mb-2" for="user_id">المستخدم الرئيسي</label>
                    <select id="user_id" name="user_id" required
                        class="shadow border rounded w-full py-2 px-3 text-gray-700 leading-tight focus:outline-none focus:shadow-outline">
                        <option value="">اختر المستخدم الرئيسي</option>
                        @foreach($users as $user)
                            <option value="{{ $user->id }}">{{ $user->name }}</option>
                        @endforeach
                    </select>
                </div>

                <div class="mb-4">
                    <label class="block text-gray-700 text-sm font-bold mb-2" for="device_id">الجهاز</label>
                    <select id="device_id" name="device_id" required
                        class="shadow appearance-none border rounded w-full py-2 px-3 text-gray-700 leading-tight focus:outline-none focus:shadow-outline">
                        <option value="">اختر الجهاز</option>
                        @foreach($devices as $device)
                            <option value="{{ $device->id }}">{{ $device->device_name }}</option>
                        @endforeach
                    </select>
                </div>

                <div class="mb-4">
                    <label class="block text-gray-700 text-sm font-bold mb-2" for="const_code">الكود الثابت</label>
                    <input type="number" id="const_code" name="const_code" required
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

<!-- Show Modal -->
<div id="showSubuserModal" class="fixed inset-0 bg-gray-600 bg-opacity-50 hidden overflow-y-auto h-full w-full">
    <div class="relative top-20 mx-auto p-5 border w-96 shadow-lg rounded-md bg-white">
        <div class="mt-3">
            <h3 class="text-lg font-medium text-gray-900 mb-4">تفاصيل المستخدم الفرعي</h3>
            <div class="space-y-4">
                <div>
                    <label class="block text-gray-700 text-sm font-bold mb-2">اسم المستخدم</label>
                    <p id="show_username" class="text-gray-600"></p>
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
                    <label class="block text-gray-700 text-sm font-bold mb-2">المستخدم الرئيسي</label>
                    <p id="show_user" class="text-gray-600"></p>
                </div>
                <div>
                    <label class="block text-gray-700 text-sm font-bold mb-2">الجهاز</label>
                    <p id="show_device" class="text-gray-600"></p>
                </div>
                <div>
                    <label class="block text-gray-700 text-sm font-bold mb-2">الكود الثابت</label>
                    <p id="show_const_code" class="text-gray-600"></p>
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
    document.getElementById('modalTitle').textContent = 'إضافة مستخدم فرعي جديد';
    document.getElementById('subuserForm').reset();
    document.getElementById('subuserModal').classList.remove('hidden');
}

function closeModal() {
    document.getElementById('subuserModal').classList.add('hidden');
}

function submitForm(e) {
    e.preventDefault();
    const form = document.getElementById('subuserForm');
    const formData = new FormData(form);
    const subuserId = document.getElementById('subuserId').value;
    const url = isEditing ? `/subusers/${subuserId}` : '/subusers';
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

function editSubuser(id) {
    isEditing = true;
    fetch(`/subusers/${id}`)
        .then(response => response.json())
        .then(subuser => {
            document.getElementById('modalTitle').textContent = 'تعديل المستخدم الفرعي';
            document.getElementById('subuserId').value = subuser.id;
            document.getElementById('username').value = subuser.username;
            document.getElementById('email').value = subuser.email;
            document.getElementById('phone').value = subuser.phone || '';
            document.getElementById('user_id').value = subuser.user_id;
            document.getElementById('device_id').value = subuser.device_id;
            document.getElementById('const_code').value = subuser.const_code;
            
            const form = document.getElementById('subuserForm');
            let methodInput = form.querySelector('input[name="_method"]');
            if (!methodInput) {
                methodInput = document.createElement('input');
                methodInput.type = 'hidden';
                methodInput.name = '_method';
                form.appendChild(methodInput);
            }
            methodInput.value = 'PUT';
            
            document.getElementById('subuserModal').classList.remove('hidden');
        })
        .catch(error => {
            console.error('Error:', error);
            alert('حدث خطأ أثناء تحميل بيانات المستخدم الفرعي');
        });
}

function deleteSubuser(id) {
    if (confirm('هل أنت متأكد من حذف هذا المستخدم الفرعي؟')) {
        fetch(`/subusers/${id}`, {
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
                alert('حدث خطأ أثناء حذف المستخدم الفرعي');
            }
        });
    }
}

function showSubuser(id) {
    fetch(`/subusers/${id}`)
        .then(response => response.json())
        .then(subuser => {
            document.getElementById('show_username').textContent = subuser.username;
            document.getElementById('show_email').textContent = subuser.email;
            document.getElementById('show_phone').textContent = subuser.phone || 'غير متوفر';
            document.getElementById('show_user').textContent = subuser.user.name;
            document.getElementById('show_device').textContent = subuser.device.device_name;
            document.getElementById('show_const_code').textContent = subuser.const_code;
            document.getElementById('showSubuserModal').classList.remove('hidden');
        });
}

function closeShowModal() {
    document.getElementById('showSubuserModal').classList.add('hidden');
}

// Search functionality
document.getElementById('searchInput').addEventListener('input', function(e) {
    const searchTerm = e.target.value;
    fetch(`/subusers?search=${searchTerm}`, {
        headers: {
            'X-Requested-With': 'XMLHttpRequest'
        }
    })
    .then(response => response.json())
    .then(data => {
        const tbody = document.querySelector('tbody');
        tbody.innerHTML = '';
        
        data.subusers.data.forEach(subuser => {
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
                                <div class="text-sm font-medium text-gray-900">${subuser.username}</div>
                            </div>
                        </div>
                    </td>
                    <td class="px-6 py-4 whitespace-nowrap">
                        <div class="text-sm text-gray-900">${subuser.email}</div>
                    </td>
                    <td class="px-6 py-4 whitespace-nowrap">
                        <div class="text-sm text-gray-900">${subuser.phone || ''}</div>
                    </td>
                    <td class="px-6 py-4 whitespace-nowrap">
                        <div class="text-sm text-gray-900">${subuser.user.name}</div>
                    </td>
                    <td class="px-6 py-4 whitespace-nowrap">
                        <div class="text-sm text-gray-900">${subuser.device ? subuser.device.device_name : 'غير محدد'}</div>
                    </td>
                    <td class="px-6 py-4 whitespace-nowrap">
                        <div class="text-sm text-gray-900">${subuser.const_code}</div>
                    </td>
                    <td class="px-6 py-4 whitespace-nowrap text-left text-sm font-medium">
                        <button onclick="showSubuser(${subuser.id})" class="text-blue-600 hover:text-blue-900 ml-3">
                            <i class="fas fa-eye"></i>
                        </button>
                        <button onclick="editSubuser(${subuser.id})" class="text-indigo-600 hover:text-indigo-900 ml-3">
                            <i class="fas fa-edit"></i>
                        </button>
                        <button onclick="deleteSubuser(${subuser.id})" class="text-red-600 hover:text-red-900">
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
