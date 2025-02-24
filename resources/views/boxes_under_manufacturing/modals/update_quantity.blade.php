<!-- Update Quantity Modal -->
<div id="updateQuantityModal" class="fixed inset-0 bg-gray-600 bg-opacity-50 overflow-y-auto h-full w-full hidden">
    <div class="relative top-20 mx-auto p-5 border w-96 shadow-lg rounded-lg bg-white">
        <div class="flex items-center justify-between mb-4">
            <h3 class="text-xl font-bold text-gray-900">تحديث الكمية المستلمة</h3>
            <button type="button" onclick="closeModal()" class="text-gray-600 hover:text-gray-800">
                <i class="fas fa-times"></i>
            </button>
        </div>

        <form id="updateQuantityForm" onsubmit="submitUpdateQuantity(event)">
            @csrf
            <input type="hidden" id="box_id_for_quantity" name="box_id">
            
            <!-- الكمية المستلمة -->
            <div class="mb-4">
                <label class="block text-sm font-medium text-gray-700 mb-2">الكمية المستلمة</label>
                <input type="number" 
                       id="received_quantity" 
                       name="received_quantity" 
                       required 
                       min="0"
                       class="w-full p-2 border rounded-lg focus:ring-2 focus:ring-indigo-500">
            </div>

            <!-- تاريخ الاستلام -->
            <div class="mb-4">
                <label class="block text-sm font-medium text-gray-700 mb-2">تاريخ الاستلام</label>
                <input type="date" 
                       id="actual_delivery_date" 
                       name="actual_delivery_date" 
                       required
                       class="w-full p-2 border rounded-lg focus:ring-2 focus:ring-indigo-500">
            </div>

            <div class="mt-6 flex justify-end">
                <button type="button" 
                        onclick="closeModal()"
                        class="bg-gray-500 text-white px-4 py-2 rounded-lg hover:bg-gray-600 transition-colors ml-2">
                    إلغاء
                </button>
                <button type="submit"
                        class="bg-indigo-600 text-white px-4 py-2 rounded-lg hover:bg-indigo-700 transition-colors">
                    تحديث
                </button>
            </div>
        </form>
    </div>
</div>

<script>
function closeModal() {
    document.getElementById('updateQuantityModal').classList.add('hidden');
}

function submitUpdateQuantity(event) {
    event.preventDefault();
    const id = document.getElementById('box_id_for_quantity').value;
    const formData = new FormData(document.getElementById('updateQuantityForm'));
    
    fetch(`/boxes-under-manufacturing/${id}/update-quantity`, {
        method: 'POST',
        headers: {
            'X-CSRF-TOKEN': document.querySelector('meta[name="csrf-token"]').content,
            'Accept': 'application/json'
        },
        body: formData
    })
    .then(response => response.json())
    .then(data => {
        if (data.success) {
            Swal.fire({
                title: 'نجاح',
                text: data.message,
                icon: 'success'
            }).then(() => {
                window.location.reload();
            });
        } else {
            Swal.fire({
                title: 'خطأ',
                text: data.message,
                icon: 'error'
            });
        }
    })
    .catch(error => {
        console.error('Error:', error);
        Swal.fire({
            title: 'خطأ',
            text: 'حدث خطأ أثناء تحديث الكمية',
            icon: 'error'
        });
    });
}

function openUpdateQuantityModal(id) {
    document.getElementById('box_id_for_quantity').value = id;
    document.getElementById('updateQuantityModal').classList.remove('hidden');
    
    // جلب بيانات الصندوق
    fetch(`/boxes-under-manufacturing/${id}`)
        .then(response => response.json())
        .then(data => {
            document.getElementById('received_quantity').value = data.received_quantity || 0;
            document.getElementById('actual_delivery_date').value = data.actual_delivery_date || '';
        })
        .catch(error => {
            console.error('Error:', error);
            Swal.fire({
                title: 'خطأ',
                text: 'حدث خطأ أثناء تحميل البيانات',
                icon: 'error'
            });
        });
}

function updateReceivedQuantity(boxId) {
    const receivedQuantity = document.getElementById('received_quantity').value;
    const actualDeliveryDate = document.getElementById('actual_delivery_date').value;

    fetch(`/boxes-under-manufacturing/${boxId}/update-quantity`, {
        method: 'POST',
        headers: {
            'Content-Type': 'application/json',
            'X-CSRF-TOKEN': document.querySelector('meta[name="csrf-token"]').content
        },
        body: JSON.stringify({
            received_quantity: receivedQuantity,
            actual_delivery_date: actualDeliveryDate
        })
    })
    .then(response => response.json())
    .then(data => {
        if (data.success) {
            Swal.fire({
                title: 'نجاح!',
                text: data.message,
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
</script> 