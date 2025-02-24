@extends('layouts.storehouse')

@section('content')
<div class="p-6 bg-gray-50">
    <!-- Archive Section -->
    <div class="bg-white rounded-xl shadow-lg mb-6">
        <div class="p-6 border-b border-gray-100">
            <div class="flex justify-between items-center">
                <div class="flex items-center space-x-4 space-x-reverse">
                    <h2 class="text-xl font-bold text-gray-800">أرشيف </h2>
                    <div class="relative">
                        <input type="text" 
                            id="archiveSearch" 
                            placeholder="بحث في الأرشيف..." 
                            class="pr-10 pl-4 py-2 border rounded-lg focus:outline-none focus:ring-2 focus:ring-indigo-500 focus:border-indigo-500 shadow-sm"
                            value="{{ request('search') }}">
                        <i class="fas fa-search absolute right-3 top-3 text-indigo-500"></i>
                    </div>
                </div>
            </div>
        </div>

        <div class="overflow-x-auto">
            <table class="min-w-full divide-y divide-gray-200">
                <thead class="bg-gray-50">
                    <tr>
                        <th class="px-6 py-3 text-right text-xs font-medium text-gray-500 uppercase tracking-wider">رقم الفاتورة</th>
                        <th class="px-6 py-3 text-right text-xs font-medium text-gray-500 uppercase tracking-wider">اسم الورشة</th>
                        <th class="px-6 py-3 text-right text-xs font-medium text-gray-500 uppercase tracking-wider">نوع الصندوق</th>
                        <th class="px-6 py-3 text-right text-xs font-medium text-gray-500 uppercase tracking-wider">الكمية</th>
                        <th class="px-6 py-3 text-right text-xs font-medium text-gray-500 uppercase tracking-wider">الكمية المستلمة</th>
                        <th class="px-6 py-3 text-right text-xs font-medium text-gray-500 uppercase tracking-wider">تاريخ الطلب</th>
                        <th class="px-6 py-3 text-right text-xs font-medium text-gray-500 uppercase tracking-wider">تاريخ الاستلام</th>
                        <th class="px-6 py-3 text-right text-xs font-medium text-gray-500 uppercase tracking-wider">التقييم</th>
                        <th class="px-6 py-3 text-right text-xs font-medium text-gray-500 uppercase tracking-wider">ملاحظات</th>
                    </tr>
                </thead>
                <tbody class="bg-white divide-y divide-gray-200">
                    @foreach($archivedBoxes as $box)
                    <tr class="hover:bg-gray-50">
                        <td class="px-6 py-4 whitespace-nowrap text-sm text-gray-500">{{ $box->invoice_number }}</td>
                        <td class="px-6 py-4 whitespace-nowrap">
                            <div class="mr-4">
                                <div class="text-sm font-medium text-gray-900">{{ $box->workshop->name }}</div>
                            </div>
                        </td>
                        <td class="px-6 py-4 whitespace-nowrap text-sm text-gray-500">{{ $box->boxType->name }}</td>
                        <td class="px-6 py-4 whitespace-nowrap text-sm text-gray-500">{{ $box->quantity }}</td>
                        <td class="px-6 py-4 whitespace-nowrap text-sm text-gray-500">{{ $box->received_quantity }}</td>
                        <td class="px-6 py-4 whitespace-nowrap text-sm text-gray-500">{{ $box->order_date->format('Y-m-d') }}</td>
                        <td class="px-6 py-4 whitespace-nowrap text-sm text-gray-500">
                            {{ $box->actual_delivery_date ? $box->actual_delivery_date->format('Y-m-d') : '-' }}
                        </td>
                        <td class="px-6 py-4 whitespace-nowrap text-sm text-gray-500">
                            <select onchange="updateRating({{ $box->id }}, this.value)" class="border rounded px-2 py-1">
                                @for($i = 0; $i <= 5; $i++)
                                    <option value="{{ $i }}" {{ $box->rating == $i ? 'selected' : '' }}>{{ $i }}</option>
                                @endfor
                            </select>
                        </td>
                        <td class="px-6 py-4 whitespace-nowrap text-sm text-gray-500">
                            @if($box->notes)
                                <button onclick="showArchiveNotes('{{ $box->notes }}')" 
                                        class="bg-blue-100 text-blue-600 px-3 py-1 rounded-full hover:bg-blue-200 transition-colors">
                                    <i class="fas fa-eye ml-1"></i>
                                    عرض
                                </button>
                            @else
                                -
                            @endif
                        </td>
                    </tr>
                    @endforeach
                </tbody>
            </table>
            <div class="px-6 py-4">
                {{ $archivedBoxes->links() }}
            </div>
        </div>
    </div>
</div>

<!-- Archive Notes Modal -->
<div id="archiveNotesModal" class="fixed inset-0 bg-gray-600 bg-opacity-50 overflow-y-auto h-full w-full hidden" 
     style="z-index: 50;">
    <div class="relative top-20 mx-auto p-5 border w-96 shadow-lg rounded-lg bg-white">
        <div class="flex flex-col">
            <!-- Header -->
            <div class="flex items-center justify-between border-b pb-3">
                <div class="text-lg font-semibold text-gray-800">
                    <i class="fas fa-sticky-note text-indigo-500 ml-2"></i>
                    ملاحظات الأرشيف
                </div>
                <button onclick="closeArchiveNotes()" class="text-gray-400 hover:text-gray-600">
                    <i class="fas fa-times"></i>
                </button>
            </div>
            
            <!-- Content -->
            <div class="mt-4 mb-6">
                <p id="archiveNotesContent" class="text-gray-600 text-sm leading-relaxed break-words"></p>
            </div>
            
            <!-- Footer -->
            <div class="flex justify-end">
                <button onclick="closeArchiveNotes()" 
                        class="bg-gray-100 text-gray-700 px-4 py-2 rounded-lg hover:bg-gray-200 transition-colors">
                    إغلاق
                </button>
            </div>
        </div>
    </div>
</div>

@push('scripts')
<script>
document.getElementById('archiveSearch').addEventListener('input', function(e) {
    const searchTerm = e.target.value;
    const currentUrl = new URL(window.location.href);
    currentUrl.searchParams.set('search', searchTerm);
    window.location.href = currentUrl.toString();
});

function updateRating(boxId, rating) {
    fetch(`/update-box-rating/${boxId}`, {
        method: 'POST',
        headers: {
            'Content-Type': 'application/json',
            'X-CSRF-TOKEN': document.querySelector('meta[name="csrf-token"]').content
        },
        body: JSON.stringify({ rating: rating })
    })
    .then(response => response.json())
    .then(data => {
        if (data.success) {
            // Optional: Show success message
            console.log('Rating updated successfully');
        }
    })
    .catch(error => console.error('Error updating rating:', error));
}

function showArchiveNotes(notes) {
    // تنظيف النص من علامات الاقتباس المزدوجة الإضافية إذا وجدت
    notes = notes.replace(/\\"/g, '"').replace(/^"|"$/g, '');
    
    // تعيين محتوى الملاحظات
    document.getElementById('archiveNotesContent').textContent = notes;
    
    // إظهار النافذة المنبثقة بتأثير متحرك
    const modal = document.getElementById('archiveNotesModal');
    modal.classList.remove('hidden');
    modal.classList.add('opacity-100');
    
    // تأثير ظهور تدريجي للمحتوى
    const modalContent = modal.querySelector('.relative');
    modalContent.classList.add('transform', 'translate-y-0', 'opacity-100');
    modalContent.classList.remove('translate-y-4', 'opacity-0');
}

function closeArchiveNotes() {
    // إخفاء النافذة المنبثقة بتأثير متحرك
    const modal = document.getElementById('archiveNotesModal');
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
document.getElementById('archiveNotesModal').addEventListener('click', function(e) {
    if (e.target === this) {
        closeArchiveNotes();
    }
});
</script>
@endpush
@endsection
