<?php

namespace App\Http\Controllers;

use App\Models\InventoryBox;
use App\Models\BoxStatus;
use App\Models\Workshop;
use App\Models\BoxType;
use Illuminate\Http\Request;
use Illuminate\Support\Facades\DB;

class InventoryBoxController extends Controller
{
    public function index(Request $request)
    {
        $query = InventoryBox::query();

        // تطبيق الفلاتر
        if ($request->has('search')) {
            $search = $request->search;
            $query->whereHas('boxType', function($q) use ($search) {
                $q->where('name', 'like', "%{$search}%");
            });
        }

        // إحصائيات الكميات لكل نوع
        $boxTypeStats = InventoryBox::select('box_type_id', DB::raw('SUM(received_quantity) as total_quantity'))
            ->groupBy('box_type_id')
            ->with('boxType')
            ->get()
            ->map(function ($stat) {
                return [
                    'name' => $stat->boxType->name,
                    'total_quantity' => $stat->total_quantity,
                    'color' => 'bg-' . $this->getRandomColor() . '-100'
                ];
            });

        // إجمالي الكميات
        $totalBoxes = $boxTypeStats->sum('total_quantity');

        $inventoryBoxes = $query->with(['boxType'])
                        ->orderBy('created_at', 'desc')
                        ->paginate(10);

        $boxTypes = BoxType::all();

        return view('inventory_boxes.index', compact('inventoryBoxes', 'boxTypes', 'boxTypeStats', 'totalBoxes'));
    }

    private function getRandomColor()
    {
        $colors = ['blue', 'green', 'yellow', 'red', 'purple', 'pink', 'indigo'];
        return $colors[array_rand($colors)];
    }

    public function show($id)
    {
        $box = InventoryBox::with(['boxType'])->findOrFail($id);
        return response()->json($box);
    }

    public function update(Request $request, $id)
    {
        $box = InventoryBox::findOrFail($id);
        $box->update($request->all());
        return response()->json(['success' => true, 'message' => 'تم تحديث البيانات بنجاح']);
    }
} 