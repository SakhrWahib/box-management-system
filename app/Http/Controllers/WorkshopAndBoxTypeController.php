<?php

namespace App\Http\Controllers;

use App\Models\Workshop;
use App\Models\BoxType;
use App\Models\BoxUnderManufacturing;
use App\Models\WorkshopArchive;
use Illuminate\Http\Request;
use Illuminate\Support\Facades\DB;

class WorkshopAndBoxTypeController extends Controller
{
    public function index(Request $request)
    {
        $query = Workshop::query();

        // تطبيق البحث
        if ($request->has('search')) {
            $search = $request->search;
            $query->where(function($q) use ($search) {
                $q->where('name', 'like', "%{$search}%")
                  ->orWhere('workshop_number', 'like', "%{$search}%")
                  ->orWhere('owner_name', 'like', "%{$search}%")
                  ->orWhere('workshop_activity', 'like', "%{$search}%")
                  ->orWhere('commercial_record', 'like', "%{$search}%");
            });
        }

        $workshops = $query->latest()->paginate(10);
        $boxTypes = BoxType::all();

        return view('workshops_and_box.index', compact('workshops', 'boxTypes'));
    }

    public function store(Request $request)
    {
        $validated = $request->validate([
            'workshop_activity' => 'required|string|max:255',
            'name' => 'required|string|max:255',
            'workshop_number' => 'required|string|unique:workshops',
            'email' => 'required|email|unique:workshops',
            'manager_name' => 'required|string|max:255',
            'owner_name' => 'required|string|max:255',
            'commercial_record' => 'required|string|max:255',
            'location' => 'required|string',
            'bank_name' => 'required|string|max:255',
            'iban' => 'required|string|max:255',
            'records' => 'nullable|string'
        ]);

        try {
            $workshop = Workshop::create($validated);

            return response()->json([
                'success' => true,
                'message' => 'تم إضافة الورشة بنجاح',
                'workshop' => $workshop
            ]);
        } catch (\Exception $e) {
            return response()->json([
                'success' => false,
                'message' => 'حدث خطأ أثناء إضافة الورشة'
            ], 500);
        }
    }

    public function update(Request $request, $type, $id)
    {
        if ($type === 'workshop') {
            $validated = $request->validate([
                'name' => 'required|string|max:255',
                'workshop_number' => 'required|string|unique:workshops,workshop_number,'.$id,
                'email' => 'required|email|unique:workshops,email,'.$id,
                'manager_name' => 'required|string|max:255',
                'owner_name' => 'required|string|max:255',
                'commercial_record' => 'required|string|max:255',
                'location' => 'required|string',
                'bank_name' => 'required|string|max:255',
                'iban' => 'required|string|max:255',
                'records' => 'nullable|string'
            ]);

            try {
                $workshop = Workshop::findOrFail($id);
                $workshop->update($validated);

                return response()->json([
                    'success' => true,
                    'message' => 'تم تحديث بيانات الورشة بنجاح',
                    'workshop' => $workshop
                ]);
            } catch (\Exception $e) {
                return response()->json([
                    'success' => false,
                    'message' => 'حدث خطأ أثناء تحديث بيانات الورشة'
                ], 500);
            }
        }

        if ($type === 'box_type') {
            $boxType = BoxType::findOrFail($id);
            
            $validated = $request->validate([
                'name' => 'required|string|max:255',
                'description' => 'nullable|string'
            ]);

            $boxType->update($validated);

            return response()->json([
                'success' => true,
                'message' => 'تم تحديث نوع الصندوق بنجاح'
            ]);
        }
    }

    public function destroy($type, $id)
    {
        if ($type === 'workshop') {
            $workshop = Workshop::findOrFail($id);
            $workshop->delete();

            return response()->json([
                'success' => true,
                'message' => 'تم حذف الورشة بنجاح'
            ]);
        }

        if ($type === 'box_type') {
            $boxType = BoxType::findOrFail($id);
            $boxType->delete();

            return response()->json([
                'success' => true,
                'message' => 'تم حذف نوع الصندوق بنجاح'
            ]);
        }
    }

    public function show($type, $id)
    {
        if ($type === 'workshop') {
            $workshop = Workshop::findOrFail($id);
            return response()->json($workshop);
        }

        if ($type === 'box_type') {
            $boxType = BoxType::findOrFail($id);
            return response()->json($boxType);
        }
    }

    /**
     * عرض صفحة الأرشيف
     */
    public function archive(Request $request)
    {
        $query = WorkshopArchive::with(['workshop', 'boxType']);

        if ($request->search) {
            $searchTerm = '%' . $request->search . '%';
            $query->where(function($q) use ($searchTerm) {
                $q->where('invoice_number', 'like', $searchTerm)
                  ->orWhereHas('workshop', function($q) use ($searchTerm) {
                      $q->where('name', 'like', $searchTerm);
                  });
            });
        }

        $archivedBoxes = $query->orderBy('archived_at', 'desc')->paginate(10);

        return view('workshops_and_box.archive', compact('archivedBoxes'));
    }

    /**
     * أرشفة ورشة
     */
    public function archiveWorkshop($id)
    {
        $workshop = Workshop::findOrFail($id);
        
        // تحديث حالة الأرشفة
        $workshop->update([
            'is_archived' => true,
            'archived_at' => now()
        ]);

        return response()->json([
            'success' => true,
            'message' => 'تم أرشفة الورشة بنجاح'
        ]);
    }

    /**
     * استعادة صندوق من الأرشيف
     */
    public function restoreBox($id)
    {
        $box = BoxUnderManufacturing::findOrFail($id);
        
        // إلغاء الأرشفة
        $box->update([
            'is_archived' => false,
            'archived_at' => null
        ]);

        return response()->json([
            'success' => true,
            'message' => 'تم استعادة الصندوق بنجاح'
        ]);
    }

    /**
     * عرض نوع صندوق محدد
     */
    public function showBoxType($id)
    {
        $boxType = BoxType::findOrFail($id);
        return response()->json($boxType);
    }

    /**
     * إضافة نوع صندوق جديد
     */
    public function storeBoxType(Request $request)
    {
        $validated = $request->validate([
            'name' => 'required|string|max:255',
            'description' => 'nullable|string'
        ]);

        $boxType = BoxType::create($validated);

        return response()->json([
            'success' => true,
            'message' => 'تم إضافة نوع الصندوق بنجاح',
            'data' => $boxType
        ]);
    }

    /**
     * تحديث نوع صندوق
     */
    public function updateBoxType(Request $request, $id)
    {
        $boxType = BoxType::findOrFail($id);
        
        $validated = $request->validate([
            'name' => 'required|string|max:255',
            'description' => 'nullable|string'
        ]);

        $boxType->update($validated);

        return response()->json([
            'success' => true,
            'message' => 'تم تحديث نوع الصندوق بنجاح',
            'data' => $boxType
        ]);
    }

    /**
     * حذف نوع صندوق
     */
    public function destroyBoxType($id)
    {
        $boxType = BoxType::findOrFail($id);
        $boxType->delete();

        return response()->json([
            'success' => true,
            'message' => 'تم حذف نوع الصندوق بنجاح'
        ]);
    }
} 