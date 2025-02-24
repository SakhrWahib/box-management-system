@extends('layouts.storehouse')

@section('content')
<div class="p-6 bg-gray-50">
    <!-- Workshops Section -->
    <div class="bg-white rounded-xl shadow-lg mb-6">
        <div class="p-6 border-b border-gray-100">
            <div class="flex justify-between items-center">
                <div class="flex items-center space-x-4 space-x-reverse">
                    <h2 class="text-xl font-bold text-gray-800">سجلات الورش  </h2>
                    <div class="relative">
                        <input type="text" 
                            id="workshopSearch" 
                            placeholder="بحث في الورش..." 
                            class="pr-10 pl-4 py-2 border rounded-lg focus:outline-none focus:ring-2 focus:ring-indigo-500 focus:border-indigo-500 shadow-sm">
                        <i class="fas fa-search absolute right-3 top-3 text-indigo-500"></i>
                    </div>
                </div>
                <button onclick="openCreateWorkshopModal()" 
                    class="bg-gradient-to-r from-indigo-500 to-indigo-600 hover:from-indigo-600 hover:to-indigo-700 text-white px-6 py-2.5 rounded-lg flex items-center shadow-md transition duration-200 ease-in-out transform hover:-translate-y-0.5">
                    <i class="fas fa-plus ml-2"></i>
                    إضافة ورشة جديدة
                </button>
            </div>
        </div>

        <div class="overflow-x-auto">
            <table class="min-w-full divide-y divide-gray-200">
                <thead class="bg-gray-50">
                    <tr>
                        <th class="px-6 py-3 text-right text-xs font-medium text-gray-500 uppercase tracking-wider">نشاط الورشة</th>
                        <th class="px-6 py-3 text-right text-xs font-medium text-gray-500 uppercase tracking-wider">اسم الورشة</th>
                        <th class="px-6 py-3 text-right text-xs font-medium text-gray-500 uppercase tracking-wider">رقم التواصل</th>
                        <th class="px-6 py-3 text-right text-xs font-medium text-gray-500 uppercase tracking-wider">البريد الإلكتروني</th>
                        <th class="px-6 py-3 text-right text-xs font-medium text-gray-500 uppercase tracking-wider">اسم المدير</th>
                        <th class="px-6 py-3 text-right text-xs font-medium text-gray-500 uppercase tracking-wider">اسم المالك</th>
                        <th class="px-6 py-3 text-right text-xs font-medium text-gray-500 uppercase tracking-wider">السجل التجاري</th>
                        <th class="px-6 py-3 text-right text-xs font-medium text-gray-500 uppercase tracking-wider">المنطقة / الحي</th>
                        <th class="px-6 py-3 text-right text-xs font-medium text-gray-500 uppercase tracking-wider">اسم البنك</th>
                        <th class="px-6 py-3 text-right text-xs font-medium text-gray-500 uppercase tracking-wider">رقم الآيبان</th>
                        <th class="px-6 py-3 text-right text-xs font-medium text-gray-500 uppercase tracking-wider">ملاحظات</th>
                        <th class="px-6 py-3 text-right text-xs font-medium text-gray-500 uppercase tracking-wider">التعاملات السابقة</th>
                        <th class="px-6 py-3 text-right text-xs font-medium text-gray-500 uppercase tracking-wider">التعديل</th>
                    </tr>
                </thead>
                <tbody class="bg-white divide-y divide-gray-200">
                    @foreach($workshops as $workshop)
                    <tr class="hover:bg-gray-50">
                        <td class="px-6 py-4 whitespace-nowrap text-sm text-gray-500">{{ $workshop->workshop_activity }}</td>
                        <td class="px-6 py-4 whitespace-nowrap text-sm font-medium text-gray-900">{{ $workshop->name }}</td>
                        <td class="px-6 py-4 whitespace-nowrap text-sm text-gray-500">{{ $workshop->workshop_number }}</td>
                        <td class="px-6 py-4 whitespace-nowrap text-sm text-gray-500">{{ $workshop->email }}</td>
                        <td class="px-6 py-4 whitespace-nowrap text-sm text-gray-500">{{ $workshop->manager_name }}</td>
                        <td class="px-6 py-4 whitespace-nowrap text-sm text-gray-500">{{ $workshop->owner_name }}</td>
                        <td class="px-6 py-4 whitespace-nowrap text-sm text-gray-500">{{ $workshop->commercial_record }}</td>
                        <td class="px-6 py-4 whitespace-nowrap text-sm text-gray-500">{{ $workshop->location }}</td>
                        <td class="px-6 py-4 whitespace-nowrap text-sm text-gray-500">{{ $workshop->bank_name }}</td>
                        <td class="px-6 py-4 whitespace-nowrap text-sm text-gray-500">{{ $workshop->iban }}</td>
                        <td class="px-6 py-4 whitespace-nowrap text-sm text-gray-500">
                            @if($workshop->records)
                                <button onclick="showNotesModal('{{ $workshop->records }}')" 
                                        class="bg-blue-100 text-blue-600 px-3 py-1 rounded-full hover:bg-blue-200 transition-colors">
                                    <i class="fas fa-eye ml-1"></i>
                                    عرض
                                </button>
                            @else
                                -
                            @endif
                        </td>
                        <td class="px-6 py-4 whitespace-nowrap text-left text-sm font-medium">
                            @if($workshop->hasTransactions)
                                <button onclick="showWorkshopTransactions({{ $workshop->id }})" 
                                        class="bg-green-100 text-green-600 px-3 py-1 rounded-full hover:bg-green-200 transition-colors">
                                    عرض
                                </button>
                            @else
                                <span class="bg-gray-100 text-gray-500 px-3 py-1 rounded-full">
                                    لا توجد تعاملات
                                </span>
                            @endif
                        </td>
                        <td class="px-6 py-4 whitespace-nowrap text-left text-sm font-medium">
                            <button onclick="editWorkshop({{ $workshop->id }})" 
                                    class="text-indigo-600 hover:text-indigo-900 transition duration-150 ease-in-out">
                                <i class="fas fa-edit"></i>
                            </button>
                        </td>
                    </tr>
                    @endforeach
                </tbody>
            </table>
            <div class="px-6 py-4">
                {{ $workshops->links() }}
            </div>
        </div>
    </div>
</div>

<!-- Workshop Transactions Modal -->
<div id="workshopTransactionsModal" class="fixed inset-0 bg-gray-600 bg-opacity-50 overflow-y-auto h-full w-full hidden">
    <div class="relative top-20 mx-auto p-5 border w-3/4 shadow-lg rounded-lg bg-white">
        <div class="flex justify-between items-center border-b pb-3">
            <h3 class="text-xl font-bold text-gray-900">تعاملات الورشة</h3>
            <button onclick="closeWorkshopTransactions()" class="text-gray-500 hover:text-gray-700">
                <i class="fas fa-times"></i>
            </button>
        </div>
        <div class="mt-4">
            <div class="overflow-x-auto">
                <table class="min-w-full divide-y divide-gray-200">
                    <thead class="bg-gray-50">
                        <tr>
                            <th class="px-6 py-3 text-right text-xs font-medium text-gray-500 uppercase">رقم الفاتورة</th>
                            <th class="px-6 py-3 text-right text-xs font-medium text-gray-500 uppercase">نوع الصندوق</th>
                            <th class="px-6 py-3 text-right text-xs font-medium text-gray-500 uppercase">الكمية</th>
                            <th class="px-6 py-3 text-right text-xs font-medium text-gray-500 uppercase">تاريخ الطلب</th>
                            <th class="px-6 py-3 text-right text-xs font-medium text-gray-500 uppercase">تاريخ التسليم</th>
                            <th class="px-6 py-3 text-right text-xs font-medium text-gray-500 uppercase">الحالة</th>
                        </tr>
                    </thead>
                    <tbody id="workshopTransactionsBody" class="bg-white divide-y divide-gray-200">
                        <!-- سيتم تعبئة البيانات هنا -->
                    </tbody>
                </table>
            </div>
        </div>
    </div>
</div>

<!-- Notes Modal -->
<div id="notesModal" class="fixed inset-0 bg-gray-600 bg-opacity-50 overflow-y-auto h-full w-full hidden" 
     style="z-index: 50;">
    <div class="relative top-20 mx-auto p-5 border w-96 shadow-lg rounded-lg bg-white">
        <div class="flex flex-col">
            <!-- Header -->
            <div class="flex items-center justify-between border-b pb-3">
                <div class="text-lg font-semibold text-gray-800">
                    <i class="fas fa-sticky-note text-indigo-500 ml-2"></i>
                    ملاحظات الورشة
                </div>
                <button onclick="closeNotesModal()" class="text-gray-400 hover:text-gray-600">
                    <i class="fas fa-times"></i>
                </button>
            </div>
            
            <!-- Content -->
            <div class="mt-4 mb-6">
                <p id="notesContent" class="text-gray-600 text-sm leading-relaxed break-words"></p>
            </div>
            
            <!-- Footer -->
            <div class="flex justify-end">
                <button onclick="closeNotesModal()" 
                        class="bg-gray-100 text-gray-700 px-4 py-2 rounded-lg hover:bg-gray-200 transition-colors">
                    إغلاق
                </button>
            </div>
        </div>
    </div>
</div>

@push('scripts')
<script>
function openCreateWorkshopModal() {
    Swal.fire({
        title: 'إضافة ورشة جديدة',
        html: `
            <form id="createWorkshopForm" class="grid grid-cols-2 gap-3 text-right max-w-3xl mx-auto p-2">
                <div class="mb-2">
                    <label class="block text-gray-700 text-xs font-bold mb-1">نشاط الورشة</label>
                    <input type="text" name="workshop_activity" class="w-full p-1.5 border rounded text-sm" required>
                </div>
                <div class="mb-2">
                    <label class="block text-gray-700 text-xs font-bold mb-1">اسم الورشة</label>
                    <input type="text" name="name" class="w-full p-1.5 border rounded text-sm" required>
                </div>
                <div class="mb-2">
                    <label class="block text-gray-700 text-xs font-bold mb-1">رقم التواصل</label>
                    <input type="text" name="workshop_number" class="w-full p-1.5 border rounded text-sm" required>
                </div>
                <div class="mb-2">
                    <label class="block text-gray-700 text-xs font-bold mb-1">البريد الإلكتروني</label>
                    <input type="email" name="email" class="w-full p-1.5 border rounded text-sm" required>
                </div>
                <div class="mb-2">
                    <label class="block text-gray-700 text-xs font-bold mb-1">اسم المدير</label>
                    <input type="text" name="manager_name" class="w-full p-1.5 border rounded text-sm" required>
                </div>
                <div class="mb-2">
                    <label class="block text-gray-700 text-xs font-bold mb-1">اسم المالك</label>
                    <input type="text" name="owner_name" class="w-full p-1.5 border rounded text-sm" required>
                </div>
                <div class="mb-2">
                    <label class="block text-gray-700 text-xs font-bold mb-1">السجل التجاري</label>
                    <input type="text" name="commercial_record" class="w-full p-1.5 border rounded text-sm" required>
                </div>
                <div class="mb-2">
                    <label class="block text-gray-700 text-xs font-bold mb-1">الموقع</label>
                    <input type="text" name="location" class="w-full p-1.5 border rounded text-sm" required>
                </div>
                <div class="mb-2">
                    <label class="block text-gray-700 text-xs font-bold mb-1">اسم البنك</label>
                    <input type="text" name="bank_name" class="w-full p-1.5 border rounded text-sm" required>
                </div>
                <div class="mb-2">
                    <label class="block text-gray-700 text-xs font-bold mb-1">رقم الآيبان</label>
                    <input type="text" name="iban" class="w-full p-1.5 border rounded text-sm" required>
                </div>
                <div class="col-span-2">
                    <label class="block text-gray-700 text-xs font-bold mb-1">ملاحظات</label>
                    <textarea name="records" class="w-full p-1.5 border rounded text-sm" rows="2"></textarea>
                </div>
            </form>
        `,
        width: '50%',
        showCancelButton: true,
        confirmButtonText: 'إضافة',
        cancelButtonText: 'إلغاء',
        preConfirm: () => {
            const form = document.getElementById('createWorkshopForm');
            const formData = new FormData(form);
            
            return fetch('/workshops-and-box-types', {
                method: 'POST',
                headers: {
                    'X-CSRF-TOKEN': document.querySelector('meta[name="csrf-token"]').content,
                    'Accept': 'application/json',
                    'Content-Type': 'application/json'
                },
                body: JSON.stringify(Object.fromEntries(formData))
            })
            .then(response => response.json())
            .then(data => {
                if (!data.success) {
                    throw new Error(data.message || 'حدث خطأ أثناء إضافة الورشة');
                }
                return data;
            });
        }
    }).then((result) => {
        if (result.isConfirmed) {
            window.location.reload();
        }
    });
}

function editWorkshop(id) {
    fetch(`/workshops-and-box-types/workshop/${id}`, {
        method: 'GET',
        headers: {
            'X-CSRF-TOKEN': document.querySelector('meta[name="csrf-token"]').content,
            'Accept': 'application/json',
            'Content-Type': 'application/json'
        },
        credentials: 'same-origin'
    })
    .then(response => {
        if (!response.ok) {
            throw new Error('حدث خطأ في الاتصال بالخادم');
        }
        return response.json();
    })
    .then(workshop => {
        if (!workshop) {
            throw new Error('لم يتم العثور على بيانات الورشة');
        }

        Swal.fire({
            title: 'تعديل الورشة',
            html: `
                <form id="editWorkshopForm" class="grid grid-cols-2 gap-3 text-right max-w-3xl mx-auto p-2">
                    <div class="mb-2">
                        <label class="block text-gray-700 text-xs font-bold mb-1">نشاط الورشة</label>
                        <input type="text" name="workshop_activity" value="${workshop.workshop_activity || ''}" class="w-full p-1.5 border rounded text-sm" required>
                    </div>
                    <div class="mb-2">
                        <label class="block text-gray-700 text-xs font-bold mb-1">اسم الورشة</label>
                        <input type="text" name="name" value="${workshop.name || ''}" class="w-full p-1.5 border rounded text-sm" required>
                    </div>
                    <div class="mb-2">
                        <label class="block text-gray-700 text-xs font-bold mb-1">رقم التواصل</label>
                        <input type="text" name="workshop_number" value="${workshop.workshop_number || ''}" class="w-full p-1.5 border rounded text-sm" required>
                    </div>
                    <div class="mb-2">
                        <label class="block text-gray-700 text-xs font-bold mb-1">البريد الإلكتروني</label>
                        <input type="email" name="email" value="${workshop.email || ''}" class="w-full p-1.5 border rounded text-sm" required>
                    </div>
                    <div class="mb-2">
                        <label class="block text-gray-700 text-xs font-bold mb-1">اسم المدير</label>
                        <input type="text" name="manager_name" value="${workshop.manager_name || ''}" class="w-full p-1.5 border rounded text-sm" required>
                    </div>
                    <div class="mb-2">
                        <label class="block text-gray-700 text-xs font-bold mb-1">اسم المالك</label>
                        <input type="text" name="owner_name" value="${workshop.owner_name || ''}" class="w-full p-1.5 border rounded text-sm" required>
                    </div>
                    <div class="mb-2">
                        <label class="block text-gray-700 text-xs font-bold mb-1">السجل التجاري</label>
                        <input type="text" name="commercial_record" value="${workshop.commercial_record || ''}" class="w-full p-1.5 border rounded text-sm" required>
                    </div>
                    <div class="mb-2">
                        <label class="block text-gray-700 text-xs font-bold mb-1">الموقع</label>
                        <input type="text" name="location" value="${workshop.location || ''}" class="w-full p-1.5 border rounded text-sm" required>
                    </div>
                    <div class="mb-2">
                        <label class="block text-gray-700 text-xs font-bold mb-1">اسم البنك</label>
                        <input type="text" name="bank_name" value="${workshop.bank_name || ''}" class="w-full p-1.5 border rounded text-sm" required>
                    </div>
                    <div class="mb-2">
                        <label class="block text-gray-700 text-xs font-bold mb-1">رقم الآيبان</label>
                        <input type="text" name="iban" value="${workshop.iban || ''}" class="w-full p-1.5 border rounded text-sm" required>
                    </div>
                    <div class="col-span-2">
                        <label class="block text-gray-700 text-xs font-bold mb-1">ملاحظات</label>
                        <textarea name="records" class="w-full p-1.5 border rounded text-sm" rows="2">${workshop.records || ''}</textarea>
                    </div>
                </form>
            `,
            width: '50%',
            showCancelButton: true,
            confirmButtonText: 'حفظ التغييرات',
            cancelButtonText: 'إلغاء',
            showLoaderOnConfirm: true,
            preConfirm: () => {
                const form = document.getElementById('editWorkshopForm');
                const formData = new FormData(form);
                const data = Object.fromEntries(formData);
                
                return fetch(`/workshops-and-box-types/workshop/${id}`, {
                    method: 'POST',
                    headers: {
                        'X-CSRF-TOKEN': document.querySelector('meta[name="csrf-token"]').content,
                        'Accept': 'application/json',
                        'Content-Type': 'application/json',
                        'X-HTTP-Method-Override': 'PUT'
                    },
                    credentials: 'same-origin',
                    body: JSON.stringify(data)
                })
                .then(response => {
                    if (!response.ok) {
                        throw new Error('حدث خطأ أثناء تحديث البيانات');
                    }
                    return response.json();
                })
                .catch(error => {
                    Swal.showValidationMessage(error.message);
                });
            }
        }).then((result) => {
            if (result.isConfirmed) {
                Swal.fire({
                    title: 'تم التحديث بنجاح',
                    icon: 'success',
                    timer: 1500,
                    showConfirmButton: false
                }).then(() => {
                    window.location.reload();
                });
            }
        });
    })
    .catch(error => {
        Swal.fire({
            title: 'خطأ',
            text: error.message,
            icon: 'error'
        });
    });
}

function showWorkshopTransactions(workshopId) {
    fetch(`/workshops-and-box-types/workshop/${workshopId}/transactions`)
        .then(response => response.json())
        .then(data => {
            const tbody = document.getElementById('workshopTransactionsBody');
            tbody.innerHTML = '';
            
            data.transactions.forEach(transaction => {
                const statusClasses = {
                    'completed': 'bg-green-100 text-green-800',
                    'archived': 'bg-gray-100 text-gray-800',
                    'under_manufacturing': 'bg-yellow-100 text-yellow-800'
                };
                
                const statusText = {
                    'completed': 'مكتمل',
                    'archived': 'مؤرشف',
                    'under_manufacturing': 'قيد التصنيع'
                };
                
                const row = `
                    <tr class="hover:bg-gray-50">
                        <td class="px-6 py-4 whitespace-nowrap text-sm text-gray-900">${transaction.invoice_number}</td>
                        <td class="px-6 py-4 whitespace-nowrap text-sm text-gray-900">${transaction.box_type_name}</td>
                        <td class="px-6 py-4 whitespace-nowrap text-sm text-gray-900">${transaction.quantity}</td>
                        <td class="px-6 py-4 whitespace-nowrap text-sm text-gray-900">${transaction.order_date}</td>
                        <td class="px-6 py-4 whitespace-nowrap text-sm text-gray-900">${transaction.delivery_date || '-'}</td>
                        <td class="px-6 py-4 whitespace-nowrap text-sm">
                            <span class="px-2 inline-flex text-xs leading-5 font-semibold rounded-full ${statusClasses[transaction.status]}">
                                ${statusText[transaction.status]}
                            </span>
                        </td>
                    </tr>
                `;
                tbody.innerHTML += row;
            });
            
            document.getElementById('workshopTransactionsModal').classList.remove('hidden');
        })
        .catch(error => {
            console.error('Error fetching transactions:', error);
            alert('حدث خطأ أثناء جلب البيانات');
        });
}

function closeWorkshopTransactions() {
    document.getElementById('workshopTransactionsModal').classList.add('hidden');
}

// Search Function
document.getElementById('workshopSearch').addEventListener('input', function(e) {
    const searchTerm = e.target.value;
    window.location.href = `/workshops-and-box-types?search=${searchTerm}`;
});

// إضافة مستمع للحدث الخاص بإكمال الصندوق
window.addEventListener('boxCompleted', function(event) {
    if (event.detail && event.detail.workshop_id) {
        // تحديث تعاملات الورشة المعنية إذا كانت النافذة المنبثقة مفتوحة
        const modal = document.getElementById('workshopTransactionsModal');
        if (!modal.classList.contains('hidden')) {
            showWorkshopTransactions(event.detail.workshop_id);
        }
    }
});

function showNotesModal(notes) {
    // تنظيف النص من علامات الاقتباس المزدوجة الإضافية إذا وجدت
    notes = notes.replace(/\\"/g, '"').replace(/^"|"$/g, '');
    
    // تعيين محتوى الملاحظات
    document.getElementById('notesContent').textContent = notes;
    
    // إظهار النافذة المنبثقة بتأثير متحرك
    const modal = document.getElementById('notesModal');
    modal.classList.remove('hidden');
    
    // تأثير ظهور تدريجي
    setTimeout(() => {
        modal.querySelector('.relative').classList.add('transform', 'translate-y-0', 'opacity-100');
    }, 100);
}

function closeNotesModal() {
    // إخفاء النافذة المنبثقة بتأثير متحرك
    const modal = document.getElementById('notesModal');
    const modalContent = modal.querySelector('.relative');
    
    modalContent.classList.add('transform', 'translate-y-4', 'opacity-0');
    
    setTimeout(() => {
        modal.classList.add('hidden');
        modalContent.classList.remove('transform', 'translate-y-4', 'opacity-0');
    }, 300);
}

// إغلاق النافذة المنبثقة عند النقر خارجها
document.getElementById('notesModal').addEventListener('click', function(e) {
    if (e.target === this) {
        closeNotesModal();
    }
});
</script>
@endpush
@endsection

