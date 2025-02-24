@extends('layouts.storehouse')

@section('content')
<div class="p-6 bg-gray-50">
    <!-- Main Section -->
    <div class="bg-white rounded-xl shadow-lg mb-6">
        <div class="p-6 border-b border-gray-100">
            <div class="flex justify-between items-center">
                <h2 class="text-xl font-bold text-gray-800">الصناديق المصنعة</h2>
            </div>

            <!-- Filters Section -->
            <div class="mt-4 grid grid-cols-1 md:grid-cols-3 gap-4">
                <div class="relative">
                    <input 
                        type="text" 
                        id="searchInput" 
                        placeholder="بحث..." 
                        class="w-full pr-10 pl-4 py-2 border rounded-lg focus:outline-none focus:ring-2 focus:ring-indigo-500 focus:border-indigo-500 shadow-sm">
                    <i class="fas fa-search absolute right-3 top-3 text-gray-400"></i>
                </div>
                
                <div class="relative">
                    <select 
                        id="workshop_filter" 
                        class="w-full pr-10 pl-4 py-2 border rounded-lg focus:outline-none focus:ring-2 focus:ring-indigo-500 focus:border-indigo-500 shadow-sm">
                        <option value="">كل الورش</option>
                        @foreach($workshops as $workshop)
                            <option value="{{ $workshop->id }}">{{ $workshop->name }}</option>
                        @endforeach
                    </select>
                    <i class="fas fa-filter absolute right-3 top-3 text-gray-400"></i>
                </div>
                
                <div class="relative">
                    <select 
                        id="box_type_filter" 
                        class="w-full pr-10 pl-4 py-2 border rounded-lg focus:outline-none focus:ring-2 focus:ring-indigo-500 focus:border-indigo-500 shadow-sm">
                        <option value="">كل أنواع الصناديق</option>
                        @foreach($boxTypes as $type)
                            <option value="{{ $type->id }}">{{ $type->name }}</option>
                        @endforeach
                    </select>
                    <i class="fas fa-box absolute right-3 top-3 text-gray-400"></i>
                </div>
            </div>
        </div>

        <!-- Table Section -->
        <div class="overflow-x-auto">
            <table class="min-w-full divide-y divide-gray-200">
                <thead class="bg-gray-50">
                    <tr>
                        <th class="px-6 py-3 text-right text-xs font-medium text-gray-500 uppercase tracking-wider">رقم الفاتورة</th>
                        <th class="px-6 py-3 text-right text-xs font-medium text-gray-500 uppercase tracking-wider">اسم الورشة</th>
                        <th class="px-6 py-3 text-right text-xs font-medium text-gray-500 uppercase tracking-wider">نوع الصندوق</th>
                        <th class="px-6 py-3 text-right text-xs font-medium text-gray-500 uppercase tracking-wider">الكمية</th>
                        <th class="px-6 py-3 text-right text-xs font-medium text-gray-500 uppercase tracking-wider">تاريخ الطلب</th>
                        <th class="px-6 py-3 text-right text-xs font-medium text-gray-500 uppercase tracking-wider">تاريخ الاستلام</th>
                        <th class="px-6 py-3 text-right text-xs font-medium text-gray-500 uppercase tracking-wider">ملاحظات</th>
                        <th class="px-6 py-3 text-right text-xs font-medium text-gray-500 uppercase tracking-wider">أرشيف</th>
                    </tr>
                </thead>
                <tbody class="bg-white divide-y divide-gray-200">
                    @foreach($manufacturedBoxes as $box)
                    <tr class="hover:bg-gray-50">
                        <td class="px-6 py-4 whitespace-nowrap text-sm text-gray-900">{{ $box->invoice_number }}</td>
                        <td class="px-6 py-4 whitespace-nowrap text-sm text-gray-900">{{ $box->workshop->name }}</td>
                        <td class="px-6 py-4 whitespace-nowrap text-sm text-gray-900">{{ $box->boxType->name }}</td>
                        <td class="px-6 py-4 whitespace-nowrap text-sm text-gray-900">{{ number_format($box->quantity) }}</td>
                        <td class="px-6 py-4 whitespace-nowrap text-sm text-gray-900">{{ $box->order_date->format('Y-m-d') }}</td>
                        <td class="px-6 py-4 whitespace-nowrap text-sm text-gray-900">{{ $box->actual_delivery_date ? $box->actual_delivery_date->format('Y-m-d') : '-' }}</td>
                        <td class="px-6 py-4 whitespace-nowrap text-sm text-gray-500">
                            @if($box->notes)
                                <button onclick="showNotesModal('{{ $box->notes }}')" 
                                        class="bg-blue-100 text-blue-600 px-3 py-1 rounded-full hover:bg-blue-200 transition-colors">
                                    <i class="fas fa-eye ml-1"></i>
                                    عرض
                                </button>
                            @else
                                -
                            @endif
                        </td>
                        <td class="px-6 py-4 whitespace-nowrap text-left text-sm font-medium">
                            <button onclick="showRatingModal({{ $box->id }}, {{ $box->workshop_id }})" 
                                    class="text-indigo-600 hover:text-indigo-900 transition duration-150 ease-in-out"
                                    title="تقييم الورشة">
                                <i class="fas fa-archive"></i>
                            </button>
                        </td>
                    </tr>
                    @endforeach
                </tbody>
            </table>
        </div>

        <!-- Pagination -->
        <div class="px-6 py-4 bg-gray-50">
            {{ $manufacturedBoxes->links() }}
        </div>
    </div>
</div>

<!-- View Box Modal -->
<div id="viewBoxModal" class="fixed inset-0 bg-gray-600 bg-opacity-50 overflow-y-auto h-full w-full hidden">
    <div class="relative top-20 mx-auto p-5 border w-96 shadow-lg rounded-lg bg-white">
        <div class="flex justify-between items-center mb-4">
            <h3 class="text-xl font-bold text-gray-900">تفاصيل الصندوق</h3>
            <button onclick="closeViewModal()" class="text-gray-600 hover:text-gray-800">
                <i class="fas fa-times"></i>
            </button>
        </div>
        <div id="boxDetails" class="space-y-4">
            <!-- Box details will be populated here -->
        </div>
    </div>
</div>

<!-- Rating Modal -->
<div id="ratingModal" class="fixed inset-0 bg-gray-600 bg-opacity-50 overflow-y-auto h-full w-full hidden">
    <div class="relative top-20 mx-auto p-5 border w-96 shadow-lg rounded-lg bg-white">
        <div class="flex justify-between items-center mb-4">
            <h3 class="text-xl font-bold text-gray-900">تقييم الورشة</h3>
            <button onclick="closeRatingModal()" class="text-gray-600 hover:text-gray-800">
                <i class="fas fa-times"></i>
            </button>
        </div>
        <div class="space-y-4">
            <div class="flex justify-center items-center space-x-4 space-x-reverse">
                <button class="text-3xl text-yellow-400 hover:text-yellow-500" onclick="setRating(1)">★</button>
                <button class="text-3xl text-yellow-400 hover:text-yellow-500" onclick="setRating(2)">★</button>
                <button class="text-3xl text-yellow-400 hover:text-yellow-500" onclick="setRating(3)">★</button>
                <button class="text-3xl text-yellow-400 hover:text-yellow-500" onclick="setRating(4)">★</button>
                <button class="text-3xl text-yellow-400 hover:text-yellow-500" onclick="setRating(5)">★</button>
            </div>
            <div class="flex justify-center mt-4">
                <button onclick="submitRating()" class="bg-indigo-600 text-white px-4 py-2 rounded-lg hover:bg-indigo-700">
                    حفظ التقييم
                </button>
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

@endsection

@push('scripts')
<script>
    function showBox(id) {
        fetch(`/manufactured-boxes/${id}`)
            .then(response => response.json())
            .then(data => {
                document.getElementById('boxDetails').innerHTML = `
                    <div class="space-y-3">
                        <p class="text-sm text-gray-600">رقم الفاتورة: <span class="font-semibold text-gray-900">${data.invoice_number}</span></p>
                        <p class="text-sm text-gray-600">الورشة: <span class="font-semibold text-gray-900">${data.workshop.name}</span></p>
                        <p class="text-sm text-gray-600">نوع الصندوق: <span class="font-semibold text-gray-900">${data.box_type.name}</span></p>
                        <p class="text-sm text-gray-600">الكمية: <span class="font-semibold text-gray-900">${data.quantity}</span></p>
                        <p class="text-sm text-gray-600">تاريخ الطلب: <span class="font-semibold text-gray-900">${data.order_date}</span></p>
                        <p class="text-sm text-gray-600">تاريخ التسليم الفعلي: <span class="font-semibold text-gray-900">${data.actual_delivery_date || '-'}</span></p>
                        <p class="text-sm text-gray-600">ملاحظات: <span class="font-semibold text-gray-900">${data.notes || '-'}</span></p>
                    </div>
                `;
                document.getElementById('viewBoxModal').classList.remove('hidden');
            });
    }

    function closeViewModal() {
        document.getElementById('viewBoxModal').classList.add('hidden');
    }

    // Search and filter functionality
    document.getElementById('searchInput').addEventListener('input', function(e) {
        // Implement search functionality
    });

    document.getElementById('workshop_filter').addEventListener('change', function(e) {
        // Implement workshop filter
    });

    document.getElementById('box_type_filter').addEventListener('change', function(e) {
        // Implement box type filter
    });

    let currentBoxId = null;
    let currentWorkshopId = null;
    let selectedRating = 0;

    function showRatingModal(boxId, workshopId) {
        currentBoxId = boxId;
        currentWorkshopId = workshopId;
        document.getElementById('ratingModal').classList.remove('hidden');
    }

    function closeRatingModal() {
        document.getElementById('ratingModal').classList.add('hidden');
        selectedRating = 0;
        resetStars();
    }

    function setRating(rating) {
        selectedRating = rating;
        const stars = document.querySelectorAll('#ratingModal button');
        stars.forEach((star, index) => {
            star.classList.toggle('text-yellow-600', index < rating);
        });
    }

    function resetStars() {
        const stars = document.querySelectorAll('#ratingModal button');
        stars.forEach(star => star.classList.remove('text-yellow-600'));
    }

    function submitRating() {
        if (selectedRating === 0) {
            alert('الرجاء اختيار تقييم');
            return;
        }

        fetch(`/manufactured-boxes/${currentBoxId}/update-rating`, {
            method: 'POST',
            headers: {
                'Content-Type': 'application/json',
                'X-CSRF-TOKEN': document.querySelector('meta[name="csrf-token"]').getAttribute('content'),
                'Accept': 'application/json'
            },
            body: JSON.stringify({ 
                workshop_id: currentWorkshopId,
                rating: selectedRating 
            })
        })
        .then(response => response.json())
        .then(data => {
            if (data.success) {
                closeRatingModal();
                alert('تم حفظ التقييم بنجاح');
            } else {
                alert('حدث خطأ أثناء حفظ التقييم');
            }
        })
        .catch(error => {
            console.error('Error:', error);
            alert('حدث خطأ أثناء حفظ التقييم');
        });
    }

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
