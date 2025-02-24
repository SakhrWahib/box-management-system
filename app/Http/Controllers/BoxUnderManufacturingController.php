<?php

namespace App\Http\Controllers;

use Illuminate\Http\Request;
use Illuminate\Support\Facades\DB;
use Illuminate\Support\Facades\Log;
use App\Models\BoxUnderManufacturing;
use App\Models\Workshop;
use App\Models\BoxType;
use App\Models\ManufacturedBox;
use App\Models\InventoryBox;
use App\Models\BoxStatus;
use App\Models\WorkshopArchive;

class BoxUnderManufacturingController extends Controller
{
    public function index(Request $request)
    {
        $query = BoxUnderManufacturing::query();

        // تطبيق الفلاتر
        if ($request->has('search')) {
            $search = $request->search;
            $query->where(function($q) use ($search) {
                $q->where('invoice_number', 'like', "%{$search}%")
                  ->orWhereHas('workshop', function($q) use ($search) {
                      $q->where('name', 'like', "%{$search}%");
                  });
            });
        }

        // الإحصائيات
        $stats = [
            'total_boxes' => BoxUnderManufacturing::count()
        ];

        $boxes = $query->with(['workshop', 'boxType'])
                      ->latest()
                      ->paginate(10);

        $workshops = Workshop::all();
        $boxTypes = BoxType::all();

        return view('boxes_under_manufacturing.index', compact('boxes', 'stats', 'workshops', 'boxTypes'));
    }

    public function store(Request $request)
    {
        $validated = $request->validate([
            'invoice_number' => 'required|string',
            'workshop_id' => 'required|exists:workshops,id',
            'box_type_id' => 'required|exists:box_types,id',
            'quantity' => 'required|integer|min:1',
            'unit_price' => 'nullable|numeric|min:0',
            'paid_amount' => 'nullable|numeric|min:0',
            'order_date' => 'required|date',
            'actual_delivery_date' => 'nullable|date',
            'barcode' => 'nullable|string',
            'notes' => 'nullable|string'
        ]);

        try {
            DB::beginTransaction();
            
            \Illuminate\Support\Facades\Log::info('Validated data:', $validated);
            
            $box = BoxUnderManufacturing::create($validated);
            
            DB::commit();

            return response()->json([
                'success' => true,
                'message' => 'تم إضافة الصندوق بنجاح',
                'data' => $box
            ]);
        } catch (\Exception $e) {
            DB::rollBack();
            \Illuminate\Support\Facades\Log::error('Error in store BoxUnderManufacturing: ' . $e->getMessage());
            return response()->json([
                'success' => false,
                'message' => 'حدث خطأ أثناء إضافة الصندوق'
            ], 500);
        }
    }

    public function show($id)
    {
        try {
            $box = BoxUnderManufacturing::findOrFail($id);
            return response()->json($box);
        } catch (\Exception $e) {
            return response()->json(['error' => 'Box not found'], 404);
        }
    }

    public function update(Request $request, $id)
    {
        $box = BoxUnderManufacturing::findOrFail($id);

        $validated = $request->validate([
            'invoice_number' => 'required|string',
            'workshop_id' => 'required|exists:workshops,id',
            'box_type_id' => 'required|exists:box_types,id',
            'quantity' => 'required|integer|min:1',
            'unit_price' => 'nullable|numeric|min:0',
            'paid_amount' => 'nullable|numeric|min:0',
            'order_date' => 'required|date',
            'actual_delivery_date' => 'nullable|date',
            'barcode' => 'nullable|string|unique:boxes_under_manufacturing,barcode,'.$id,
            'notes' => 'nullable|string'
        ]);

        try {
            DB::beginTransaction();
            
            $box->update($validated);
            
            DB::commit();

            return response()->json([
                'success' => true,
                'message' => 'تم تحديث الصندوق بنجاح',
                'data' => $box
            ]);
        } catch (\Exception $e) {
            DB::rollBack();
            \Illuminate\Support\Facades\Log::error('Error in update BoxUnderManufacturing: ' . $e->getMessage());
            return response()->json([
                'success' => false,
                'message' => 'حدث خطأ أثناء تحديث الصندوق'
            ], 500);
        }
    }

    public function updatePayment(Request $request, $id)
    {
        $validated = $request->validate([
            'paid_amount' => 'required|numeric|min:0'
        ]);

        try {
            $box = BoxUnderManufacturing::findOrFail($id);
            $box->paid_amount = $validated['paid_amount'];
            $box->save();

            return response()->json([
                'success' => true,
                'message' => 'تم تحديث المبلغ المدفوع بنجاح'
            ]);
        } catch (\Exception $e) {
            return response()->json([
                'success' => false,
                'message' => 'حدث خطأ أثناء تحديث المبلغ المدفوع: ' . $e->getMessage()
            ], 500);
        }
    }

    public function destroy($id)
    {
        try {
            $box = BoxUnderManufacturing::findOrFail($id);
            $box->delete();

            return response()->json([
                'success' => true,
                'message' => 'تم حذف الصندوق بنجاح'
            ]);
        } catch (\Exception $e) {
            return response()->json([
                'success' => false,
                'message' => 'حدث خطأ أثناء حذف الصندوق: ' . $e->getMessage()
            ], 500);
        }
    }

    public function updateQuantity(Request $request, $id)
    {
        try {
            DB::beginTransaction();
            
            $box = BoxUnderManufacturing::findOrFail($id);
            $originalQuantity = $box->quantity;
            $currentReceivedQuantity = $box->received_quantity ?? 0;
            $newReceivedQuantity = $request->received_quantity;
            $totalReceivedQuantity = $currentReceivedQuantity + $newReceivedQuantity;
            
            // التحقق من أن الكمية المستلمة لا تتجاوز الكمية الأصلية
            if ($totalReceivedQuantity > $originalQuantity) {
                throw new \Exception('الكمية المستلمة لا يمكن أن تتجاوز الكمية الأصلية');
            }
            
            // تحديث الكمية المستلمة
            $box->received_quantity = $totalReceivedQuantity;
            $box->actual_delivery_date = $request->actual_delivery_date;
            $box->save();

            // الحصول على حالات الصناديق
            $partialStatus = BoxStatus::where('name', 'مصنعة جزئياً')->first();
            $completedStatus = BoxStatus::where('name', 'مكتمل')->first();

            if ($totalReceivedQuantity == $originalQuantity) {
                // إذا تم استلام كامل الكمية، انقل إلى الصناديق المصنعة
                $this->moveToManufacturedBoxes($box);
                $box->delete(); // soft delete
            } else {
                // إذا تم استلام جزء من الكمية، انقل إلى الصناديق الموجودة
                $this->moveToInventoryBoxes($box, $totalReceivedQuantity, $partialStatus);
            }

            DB::commit();
            
            return response()->json([
                'success' => true,
                'message' => 'تم تحديث الكمية المستلمة بنجاح'
            ]);

        } catch (\Exception $e) {
            DB::rollback();
            return response()->json([
                'success' => false,
                'message' => $e->getMessage()
            ], 500);
        }
    }

    private function moveToInventoryBoxes($box, $quantity, $status)
    {
        // البحث عن سجل موجود لنفس نوع الصندوق ورقم الفاتورة
        $inventoryBox = InventoryBox::where('invoice_number', $box->invoice_number)
                                  ->where('box_type_id', $box->box_type_id)
                                  ->first();

        if ($inventoryBox) {
            // تحديث السجل الموجود
            $inventoryBox->update([
                'received_quantity' => $quantity,
                'actual_delivery_date' => $box->actual_delivery_date,
                'notes' => $box->notes,
                'box_status_id' => $status->id
            ]);
        } else {
            // إنشاء سجل جديد إذا لم يكن موجوداً
            InventoryBox::create([
                'invoice_number' => $box->invoice_number,
                'workshop_id' => $box->workshop_id,
                'box_type_id' => $box->box_type_id,
                'quantity' => $box->quantity,
                'received_quantity' => $quantity,
                'order_date' => $box->order_date,
                'actual_delivery_date' => $box->actual_delivery_date,
                'notes' => $box->notes,
                'box_status_id' => $status->id
            ]);
        }
    }

    private function moveToManufacturedBoxes($box)
    {
        try {
            DB::table('manufactured_boxes')->insert([
                'invoice_number' => $box->invoice_number,
                'workshop_id' => $box->workshop_id,
                'box_type_id' => $box->box_type_id,
                'quantity' => $box->quantity,
                'received_quantity' => $box->received_quantity,
                'order_date' => $box->order_date,
                'actual_delivery_date' => $box->actual_delivery_date,
                'notes' => $box->notes,
                'created_at' => now(),
                'updated_at' => now()
            ]);
        } catch (\Exception $e) {
            \Illuminate\Support\Facades\Log::error('Error in moveToManufacturedBoxes: ' . $e->getMessage());
            throw new \Exception('حدث خطأ أثناء نقل البيانات إلى الصناديق المصنعة');
        }
    }

    public function markAsCompleted(Request $request, $id)
    {
        try {
            DB::beginTransaction();
            
            $box = BoxUnderManufacturing::findOrFail($id);
            
            // تحديث الكمية المستلمة لتكون مساوية للكمية الكلية
            $box->received_quantity = $box->quantity;
            $box->actual_delivery_date = now();
            $box->save();

            // إنشاء نسخة في الصناديق المصنعة
            ManufacturedBox::create([
                'invoice_number' => $box->invoice_number,
                'workshop_id' => $box->workshop_id,
                'box_type_id' => $box->box_type_id,
                'quantity' => $box->quantity,
                'received_quantity' => $box->quantity,
                'order_date' => $box->order_date,
                'actual_delivery_date' => now(),
                'notes' => $box->notes
            ]);

            // إنشاء نسخة في أرشيف الورش
            WorkshopArchive::create([
                'invoice_number' => $box->invoice_number,
                'workshop_id' => $box->workshop_id,
                'box_type_id' => $box->box_type_id,
                'quantity' => $box->quantity,
                'received_quantity' => $box->quantity,
                'order_date' => $box->order_date,
                'actual_delivery_date' => now(),
                'notes' => $box->notes,
                'archived_at' => now()
            ]);

            // حذف الصندوق من تحت التصنيع
            $box->delete();

            DB::commit();

            return response()->json([
                'success' => true,
                'message' => 'تم تحديث حالة الصندوق بنجاح'
            ]);

        } catch (\Exception $e) {
            DB::rollback();
            return response()->json([
                'success' => false,
                'message' => 'حدث خطأ أثناء تحديث حالة الصندوق: ' . $e->getMessage()
            ], 500);
        }
    }

    public function updateReceivedQuantity(Request $request, $id)
    {
        try {
            DB::beginTransaction();

            $box = BoxUnderManufacturing::findOrFail($id);
            $receivedQuantity = $request->received_quantity;

            // التحقق من أن الكمية المستلمة لا تتجاوز الكمية الكلية
            if ($receivedQuantity > $box->quantity) {
                return response()->json([
                    'success' => false,
                    'message' => 'الكمية المستلمة لا يمكن أن تتجاوز الكمية الكلية'
                ], 422);
            }

            // تحديث الكمية المستلمة
            $box->received_quantity = $receivedQuantity;
            
            // إذا تم استلام كل الكمية، قم بتحديث الحالة
            if ($receivedQuantity == $box->quantity) {
                $completedStatus = BoxStatus::where('name', 'مكتمل')->first();
                if ($completedStatus) {
                    $box->status_id = $completedStatus->id;
                }
            }

            $box->save();

            // إنشاء سجل في الصناديق المصنعة
            if ($receivedQuantity > 0) {
                ManufacturedBox::create([
                    'box_under_manufacturing_id' => $box->id,
                    'box_type_id' => $box->box_type_id,
                    'quantity' => $receivedQuantity,
                    'manufacturing_date' => now(),
                    'workshop_id' => $box->workshop_id
                ]);
            }

            DB::commit();

            return response()->json([
                'success' => true,
                'message' => 'تم تحديث الكمية المستلمة بنجاح'
            ]);

        } catch (\Exception $e) {
            DB::rollBack();
            \Illuminate\Support\Facades\Log::error('Error in updateReceivedQuantity: ' . $e->getMessage());
            return response()->json([
                'success' => false,
                'message' => 'حدث خطأ أثناء تحديث الكمية المستلمة'
            ], 500);
        }
    }
}