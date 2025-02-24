<?php

namespace App\Http\Controllers;

use App\Models\ManufacturedBox;
use App\Models\Workshop;
use App\Models\BoxType;
use Illuminate\Http\Request;
use Illuminate\Support\Facades\Cache;

class ManufacturedBoxController extends Controller
{
    public function index(Request $request)
    {
        $query = ManufacturedBox::with(['workshop', 'boxType']);

        // البحث
        if ($request->search) {
            $searchTerm = '%' . $request->search . '%';
            $query->where(function($q) use ($searchTerm) {
                $q->where('invoice_number', 'like', $searchTerm)
                  ->orWhereHas('workshop', function($q) use ($searchTerm) {
                      $q->where('name', 'like', $searchTerm);
                  });
            });
        }

        $manufacturedBoxes = $query->orderBy('created_at', 'desc')->paginate(10);
        
        // التحقق من الكميات المنخفضة
        $lowQuantityBoxes = $manufacturedBoxes->filter(function($box) {
            return $box->quantity <= 20;
        });

        if ($request->ajax()) {
            return response()->json([
                'manufacturedBoxes' => $manufacturedBoxes,
                'hasLowQuantity' => $lowQuantityBoxes->isNotEmpty(),
                'lowQuantityCount' => $lowQuantityBoxes->count()
            ]);
        }

        $workshops = Workshop::all();
        $boxTypes = BoxType::all();

        return view('manufactured_boxes.index', [
            'manufacturedBoxes' => $manufacturedBoxes,
            'workshops' => $workshops,
            'boxTypes' => $boxTypes,
            'hasLowQuantity' => $lowQuantityBoxes->isNotEmpty(),
            'lowQuantityCount' => $lowQuantityBoxes->count()
        ]);
    }

    public function show($id)
    {
        $box = ManufacturedBox::with(['workshop', 'boxType'])->findOrFail($id);
        return response()->json($box);
    }

    public function update(Request $request, $id)
    {
        $box = ManufacturedBox::findOrFail($id);
        $box->update($request->all());
        return response()->json(['success' => true, 'message' => 'تم تحديث البيانات بنجاح']);
    }

    public function updateRating(Request $request, $id)
    {
        try {
            $workshop = Workshop::findOrFail($request->workshop_id);
            
            // يمكننا تخزين التقييم في cache أو session
            Cache::put("workshop_rating_{$request->workshop_id}", $request->rating, now()->addDays(30));
            
            return response()->json([
                'success' => true,
                'message' => 'تم تحديث تقييم الورشة بنجاح'
            ]);
        } catch (\Exception $e) {
            return response()->json([
                'success' => false,
                'message' => 'حدث خطأ أثناء تحديث التقييم'
            ], 500);
        }
    }
} 