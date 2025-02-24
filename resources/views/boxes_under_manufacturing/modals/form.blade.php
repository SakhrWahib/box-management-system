<!-- Add/Edit Box Modal -->
<div id="boxModal" class="fixed inset-0 bg-gray-600 bg-opacity-50 overflow-y-auto h-full w-full hidden">
ـ    <div class="relative top-20 mx-auto p-5 border w-full max-w-4xl shadow-lg rounded-lg bg-white">
        <div class="flex items-center justify-between mb-4">
            <div class="flex items-center">
                <i class="fas fa-box-open text-2xl text-indigo-600 ml-2"></i>
                <h3 class="text-xl font-bold text-gray-900" id="boxModalTitle">إضافة صندوق جديد</h3>
            </div>
            <button onclick="document.getElementById('boxModal').classList.add('hidden')" 
                    class="text-gray-600 hover:text-gray-800">
                <i class="fas fa-times text-xl"></i>
            </button>
        </div>

        <form id="boxForm" onsubmit="submitBoxForm(event)" class="space-y-4">
            @csrf
            <input type="hidden" id="box_id" name="id">
            
            <div class="grid grid-cols-1 md:grid-cols-2 gap-4">
                <div>
                    <label class="block text-sm font-medium text-gray-700 mb-1">رقم الفاتورة</label>
                    <input type="text" id="invoice_number" name="invoice_number" required
                           class="w-full p-2 border rounded-lg focus:ring-2 focus:ring-indigo-500">
                </div>

                <div>
                    <label class="block text-sm font-medium text-gray-700 mb-1">رقم الباركود</label>
                    <div class="relative">
                        <input type="text" 
                               id="barcode" 
                               name="barcode"
                               class="w-full p-2 border rounded-lg focus:ring-2 focus:ring-indigo-500 pl-10">
                        <i class="fas fa-barcode absolute left-3 top-3 text-gray-400"></i>
                    </div>
                </div>

                <div>
                    <label class="block text-sm font-medium text-gray-700 mb-1">الورشة</label>
                    <select id="workshop_id" name="workshop_id" required
                            class="w-full p-2 border rounded-lg focus:ring-2 focus:ring-indigo-500">
                        <option value="">اختر الورشة</option>
                        @foreach($workshops as $workshop)
                            <option value="{{ $workshop->id }}">{{ $workshop->name }}</option>
                        @endforeach
                    </select>
                </div>

                <div>
                    <label class="block text-sm font-medium text-gray-700 mb-1">نوع الصندوق</label>
                    <select id="box_type_id" name="box_type_id" required
                            class="w-full p-2 border rounded-lg focus:ring-2 focus:ring-indigo-500">
                        <option value="">اختر نوع الصندوق</option>
                        @foreach($boxTypes as $type)
                            <option value="{{ $type->id }}">{{ $type->name }}</option>
                        @endforeach
                    </select>
                </div>

                <div>
                    <label class="block text-sm font-medium text-gray-700 mb-1">الكمية</label>
                    <input type="number" id="quantity" name="quantity" required min="1"
                           class="w-full p-2 border rounded-lg focus:ring-2 focus:ring-indigo-500">
                </div>

                <div>
                    <label class="block text-sm font-medium text-gray-700 mb-1">تاريخ الطلب</label>
                    <input type="date" id="order_date" name="order_date" required
                           class="w-full p-2 border rounded-lg focus:ring-2 focus:ring-indigo-500">
                </div>

                <div>
                    <label class="block text-sm font-medium text-gray-700 mb-1">تاريخ التسليم المتوقع</label>
                    <input type="date" id="actual_delivery_date" name="actual_delivery_date"
                           class="w-full p-2 border rounded-lg focus:ring-2 focus:ring-indigo-500">
                </div>

                <div class="col-span-2">
                    <label class="block text-sm font-medium text-gray-700 mb-1">ملاحظات</label>
                    <textarea id="notes" name="notes" rows="3"
                              class="w-full p-2 border rounded-lg focus:ring-2 focus:ring-indigo-500"></textarea>
                </div>
            </div>

            <div class="flex justify-end space-x-4 space-x-reverse mt-4">
                <button type="submit" 
                        class="bg-indigo-600 text-white px-6 py-2 rounded-lg hover:bg-indigo-700 focus:outline-none focus:ring-2 focus:ring-indigo-500 focus:ring-offset-2">
                    حفظ
                </button>
                <button type="button" 
                        onclick="document.getElementById('boxModal').classList.add('hidden')"
                        class="bg-gray-100 text-gray-800 px-6 py-2 rounded-lg hover:bg-gray-200 focus:outline-none focus:ring-2 focus:ring-gray-500 focus:ring-offset-2">
                    إلغاء
                </button>
            </div>
        </form>
    </div>
</div>

<script>
function openAddModal() {
    document.getElementById('boxModalTitle').textContent = 'إضافة صندوق جديد';
    document.getElementById('boxForm').reset();
    document.getElementById('box_id').value = '';
    document.getElementById('boxModal').classList.remove('hidden');
}

function openEditModal(id) {
    document.getElementById('boxModalTitle').textContent = 'تعديل صندوق تحت التصنيع';
    fetch(`/boxes-under-manufacturing/${id}`)
        .then(response => response.json())
        .then(data => {
            document.getElementById('box_id').value = data.id;
            document.getElementById('invoice_number').value = data.invoice_number;
            document.getElementById('barcode').value = data.barcode || '';
            document.getElementById('workshop_id').value = data.workshop_id;
            document.getElementById('box_type_id').value = data.box_type_id;
            document.getElementById('quantity').value = data.quantity;
            document.getElementById('order_date').value = data.order_date;
            document.getElementById('actual_delivery_date').value = data.actual_delivery_date || '';
            document.getElementById('notes').value = data.notes || '';
            document.getElementById('boxModal').classList.remove('hidden');
        });
}

function submitBoxForm(event) {
    event.preventDefault();
    const id = document.getElementById('box_id').value;
    const method = id ? 'PUT' : 'POST';
    const url = id ? `/boxes-under-manufacturing/${id}` : '/boxes-under-manufacturing';

    const formData = {
        invoice_number: document.getElementById('invoice_number').value,
        workshop_id: document.getElementById('workshop_id').value,
        box_type_id: document.getElementById('box_type_id').value,
        quantity: document.getElementById('quantity').value,
        order_date: document.getElementById('order_date').value,
        actual_delivery_date: document.getElementById('actual_delivery_date').value || null,
        barcode: document.getElementById('barcode').value,
        notes: document.getElementById('notes').value
    };

    // للتأكد من البيانات
    console.log('Form Data:', formData);

    fetch(url, {
        method: method,
        headers: {
            'Content-Type': 'application/json',
            'X-CSRF-TOKEN': document.querySelector('meta[name="csrf-token"]').content
        },
        body: JSON.stringify(formData)
    })
    .then(response => response.json())
    .then(data => {
        if (data.success) {
            document.getElementById('boxModal').classList.add('hidden');
            window.location.reload();
        } else {
            alert(data.message);
        }
    })
    .catch(error => {
        console.error('Error:', error);
        alert('حدث خطأ أثناء حفظ البيانات');
    });
}
</script>
