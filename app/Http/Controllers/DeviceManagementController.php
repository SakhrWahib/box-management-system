<?php

namespace App\Http\Controllers;

use App\Models\Device;
use App\Models\User;
use Illuminate\Http\Request;
use Illuminate\Support\Facades\Validator;

class DeviceManagementController extends Controller
{
    public function index(Request $request)
    {
        $query = Device::with('user');

        // Search functionality
        if ($request->search) {
            $query->where(function($q) use ($request) {
                $q->where('device_name', 'like', '%' . $request->search . '%')
                  ->orWhere('mac_address', 'like', '%' . $request->search . '%')
                  ->orWhere('usercode', 'like', '%' . $request->search . '%');
            });
        }

        $devices = $query->latest()->paginate(10);
        
        if ($request->ajax()) {
            return response()->json([
                'devices' => $devices,
                'pagination' => view('devices.pagination', compact('devices'))->render()
            ]);
        }

        $users = User::all(); // للقائمة المنسدلة في نموذج الإضافة
        return view('devices.manage', compact('devices', 'users'));
    }

    public function store(Request $request)
    {
        $validated = $request->validate([
            'device_name' => 'required|string|max:255',
            'mac_address' => 'required|string|unique:devices,mac_address',
            'usercode' => 'required|string|size:4|regex:/^[0-9]+$/',
            'user_id' => 'required|exists:users,id',
            'site_data' => 'nullable|string|max:500',
            'status' => 'boolean'
        ]);

        try {
            // تحويل قيمة status إلى boolean
            $validated['status'] = filter_var($validated['status'] ?? true, FILTER_VALIDATE_BOOLEAN);
            
            $device = Device::create($validated);
            return response()->json($device, 201);
        } catch (\Exception $e) {
            return response()->json(['message' => 'حدث خطأ أثناء إنشاء الجهاز'], 500);
        }
    }

    public function show($id)
    {
        $device = Device::with('user')->findOrFail($id);
        return response()->json($device);
    }

    public function update(Request $request, $id)
    {
        $device = Device::findOrFail($id);
        
        $validated = $request->validate([
            'device_name' => 'sometimes|required|string|max:255',
            'mac_address' => 'sometimes|required|string|unique:devices,mac_address,' . $id,
            'usercode' => 'sometimes|required|string|size:4|regex:/^[0-9]+$/',
            'user_id' => 'sometimes|required|exists:users,id',
            'site_data' => 'nullable|string|max:500',
        ]);

        try {
            $device->update($validated);
            return response()->json($device);
        } catch (\Exception $e) {
            return response()->json([
                'error' => 'حدث خطأ أثناء تحديث بيانات الجهاز',
                'details' => $e->getMessage()
            ], 500);
        }
    }

    public function destroy($id)
    {
        try {
            $device = Device::findOrFail($id);
            $device->delete();
            return response()->json(['message' => 'تم حذف الجهاز بنجاح']);
        } catch (\Exception $e) {
            return response()->json([
                'error' => 'حدث خطأ أثناء حذف الجهاز',
                'details' => $e->getMessage()
            ], 500);
        }
    }
}
