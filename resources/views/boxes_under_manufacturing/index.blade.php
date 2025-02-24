@extends('layouts.storehouse')

@section('content')
<div class="p-6 bg-gray-50">
    <div class="bg-white rounded-xl shadow-lg mb-6">
        <div class="p-6 border-b border-gray-100">
            <div class="flex justify-between items-center">
                <div class="flex items-center">
                    <i class="fas fa-box-open text-2xl text-indigo-600 ml-2"></i>
                    <h2 class="text-xl font-bold text-gray-900">نظام الصناديق تحت التصنيع</h2>
                </div>
                <button onclick="openAddModal()" 
                        class="bg-indigo-600 text-white px-4 py-2 rounded-lg hover:bg-indigo-700 transition-colors flex items-center">
                    <i class="fas fa-plus ml-2"></i>
                    إضافة صندوق جديد
                </button>
            </div>
        </div>

        <!-- Search and Filter Section -->
        <div class="p-4 border-b border-gray-100">
            <div class="flex items-center gap-4 max-w-3xl">
                <div class="relative w-64">
                    <i class="fas fa-search absolute right-3 top-2.5 text-gray-400"></i>
                    <input type="text" 
                           id="searchInput" 
                           placeholder="بحث..." 
                           class="w-full pl-3 pr-10 py-2 text-sm border rounded-lg focus:ring-2 focus:ring-indigo-500">
                </div>
                <div class="relative w-48">
                    <i class="fas fa-warehouse absolute right-3 top-2.5 text-gray-400"></i>
                    <select id="workshopFilter" 
                            class="w-full pl-3 pr-10 py-2 text-sm border rounded-lg focus:ring-2 focus:ring-indigo-500 appearance-none">
                        <option value="">كل الورش</option>
                        @foreach($workshops as $workshop)
                            <option value="{{ $workshop->id }}">{{ $workshop->name }}</option>
                        @endforeach
                    </select>
                </div>
            </div>
        </div>

        <!-- Table Section -->
        <div class="overflow-x-auto">
            <table class="min-w-full divide-y divide-gray-200">
                <thead class="bg-gray-50">
                    <tr>
                        <th class="px-6 py-3 text-right text-xs font-medium text-gray-500 uppercase tracking-wider">رقم الفاتورة</th>
                        <th class="px-6 py-3 text-right text-xs font-medium text-gray-500 uppercase tracking-wider">رقم الباركود</th>
                        <th class="px-6 py-3 text-right text-xs font-medium text-gray-500 uppercase tracking-wider">اسم الورشة</th>
                        <th class="px-6 py-3 text-right text-xs font-medium text-gray-500 uppercase tracking-wider">نوع الصندوق</th>
                        <th class="px-6 py-3 text-right text-xs font-medium text-gray-500 uppercase tracking-wider">الكمية</th>
                        <th class="px-6 py-3 text-right text-xs font-medium text-gray-500 uppercase tracking-wider">الكمية المستلمة</th>
                        <th class="px-6 py-3 text-right text-xs font-medium text-gray-500 uppercase tracking-wider">تاريخ الطلب</th>
                        <th class="px-6 py-3 text-right text-xs font-medium text-gray-500 uppercase tracking-wider">تاريخ التسليم</th>
                        <th class="px-6 py-3 text-right text-xs font-medium text-gray-500 uppercase tracking-wider">الملاحظات</th>
                        <th class="px-6 py-3 text-right text-xs font-medium text-gray-500 uppercase tracking-wider">الإجراءات</th>
                    </tr>
                </thead>
                <tbody class="bg-white divide-y divide-gray-200">
                    @foreach($boxes as $box)
                    @php
                        \Log::info('Box data:', ['barcode' => $box->barcode]);
                    @endphp
                    
                    <tr>
                        <td class="px-6 py-4 whitespace-nowrap text-sm text-gray-900">{{ $box->invoice_number }}</td>
                        <td class="px-6 py-4 whitespace-nowrap">
                            <div class="flex items-center space-x-2 space-x-reverse">
                                <i class="fas fa-barcode text-indigo-600"></i>
                                <span class="text-sm font-mono bg-gray-100 px-2 py-1 rounded">
                                    {{ $box->barcode }}
                                </span>
                            </div>
                        </td>
                        <td class="px-6 py-4 whitespace-nowrap text-sm text-gray-900">{{ $box->workshop->name }}</td>
                        <td class="px-6 py-4 whitespace-nowrap text-sm text-gray-900">{{ $box->boxType->name }}</td>
                        <td class="px-6 py-4 whitespace-nowrap text-sm text-gray-900">{{ $box->quantity }}</td>
                        <td class="px-6 py-4 whitespace-nowrap text-sm text-gray-900">{{ $box->received_quantity }}</td>
                        <td class="px-6 py-4 whitespace-nowrap text-sm text-gray-900">{{ date('Y-m-d', strtotime($box->order_date)) }}</td>
                        <td class="px-6 py-4 whitespace-nowrap text-sm text-gray-900">{{ $box->actual_delivery_date ? date('Y-m-d', strtotime($box->actual_delivery_date)) : '-' }}</td>
                        <td class="px-6 py-4 whitespace-nowrap text-sm text-gray-500">
                            @if($box->notes)
                                <button onclick="showNotes('{{ $box->notes }}')" 
                                        class="bg-blue-100 text-blue-600 px-3 py-1 rounded-full hover:bg-blue-200 transition-colors">
                                    <i class="fas fa-eye ml-1"></i>
                                    عرض
                                </button>
                            @else
                                -
                            @endif
                        </td>
                        <td class="px-6 py-4">
                            <div class="flex space-x-2 space-x-reverse">
                                <button onclick="openEditModal({{ $box->id }})" 
                                        class="text-indigo-600 hover:text-indigo-900 transition-colors flex items-center">
                                    <i class="fas fa-edit text-lg ml-1"></i>
                                    <span class="text-sm">تعديل</span>
                                </button>
                                <button onclick="openUpdateQuantityModal({{ $box->id }})" 
                                        class="text-blue-600 hover:text-blue-900 transition-colors flex items-center mr-2">
                                    <i class="fas fa-sync-alt text-lg ml-1"></i>
                                    <span class="text-sm">تحديث</span>
                                </button>
                                <button onclick="markAsCompleted({{ $box->id }})" 
                                        class="text-green-600 hover:text-green-900 transition-colors flex items-center mr-2">
                                    <i class="fas fa-check-circle text-lg ml-1"></i>
                                    <span class="text-sm">تم</span>
                                </button>
                            </div>
                        </td>
                    </tr>
                    @endforeach
                </tbody>
            </table>
        </div>

        <!-- Pagination -->
        <div class="p-4">
            {{ $boxes->links() }}
        </div>
    </div>
</div>

<!-- Notes Modal -->
<div id="notesModal" class="fixed inset-0 bg-gray-600 bg-opacity-50 overflow-y-auto h-full w-full hidden" style="z-index: 50;">
    <div class="relative top-20 mx-auto p-5 border w-96 shadow-lg rounded-lg bg-white">
        <div class="flex flex-col">
            <!-- Header -->
            <div class="flex items-center justify-between border-b pb-3">
                <div class="text-lg font-semibold text-gray-800">
                    <i class="fas fa-sticky-note text-indigo-500 ml-2"></i>
                    الملاحظات
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

@include('boxes_under_manufacturing.modals.form')
@include('boxes_under_manufacturing.modals.update_quantity')

@push('scripts')
<script>
function showNotes(notes) {
    // تنظيف النص من علامات الاقتباس المزدوجة الإضافية إذا وجدت
    notes = notes.replace(/\\"/g, '"').replace(/^"|"$/g, '');
    
    // تعيين محتوى الملاحظات
    document.getElementById('notesContent').textContent = notes;
    
    // إظهار النافذة المنبثقة بتأثير متحرك
    const modal = document.getElementById('notesModal');
    modal.classList.remove('hidden');
    modal.classList.add('opacity-100');
    
    // تأثير ظهور تدريجي للمحتوى
    const modalContent = modal.querySelector('.relative');
    modalContent.classList.add('transform', 'translate-y-0', 'opacity-100');
    modalContent.classList.remove('translate-y-4', 'opacity-0');
}

function closeNotesModal() {
    // إخفاء النافذة المنبثقة بتأثير متحرك                                                                                       
    
    const modal = document.getElementById('notesModal');
    const modalContent = modal.querySelector('.relative');
    
    // تأثير اختفاء تدريجي
    modalContent.classList.add('transform', 'translate-y-4', 'opacity-0');
    modalContent.classList.remove('translate-y-0', 'opacity-100');
    
    // إخفاء النافذة بعد انتهاء التأثير
    setTimeout(() => {
        modal.classList.add('hidden');
        modal.classList.remove('opacity-100');
    }, 300);
}

// إغلاق النافذة المنبثقة عند النقر خارجها
document.getElementById('notesModal').addEventListener('click', function(e) {
    if (e.target === this) {
        closeNotesModal();
    }
});

function markAsCompleted(id) {
    Swal.fire({
        title: 'تأكيد الاكتمال',
        text: 'هل أنت متأكد من اكتمال هذا الصندوق؟ سيتم نقله إلى الصناديق المصنعة والورش',
        icon: 'warning',
        showCancelButton: true,
        confirmButtonText: 'نعم، تم الاكتمال',
        cancelButtonText: 'إلغاء'
    }).then((result) => {
        if (result.isConfirmed) {
            fetch(`/boxes-under-manufacturing/${id}/mark-completed`, {
                method: 'POST',
                headers: {
                    'X-CSRF-TOKEN': document.querySelector('meta[name="csrf-token"]').content,
                    'Content-Type': 'application/json'
                }
            })
            .then(response => response.json())
            .then(data => {
                if (data.success) {
                    Swal.fire({
                        title: 'تم بنجاح!',
                        text: 'تم نقل الصندوق إلى الصناديق المصنعة والورش',
                        icon: 'success',
                        confirmButtonText: 'حسناً'
                    }).then(() => {
                        window.location.reload();
                    });
                } else {
                    throw new Error(data.message);
                }
            })
            .catch(error => {
                Swal.fire({
                    title: 'خطأ!',
                    text: error.message,
                    icon: 'error',
                    confirmButtonText: 'حسناً'
                });
            });
        }
    });
}
</script>
@endpush
@endsection
