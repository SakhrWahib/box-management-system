<?php

namespace App\Http\Controllers;

use App\Models\User;
use Illuminate\Http\Request;
use Illuminate\Support\Facades\Hash;

class UserManagementController extends Controller
{
    public function index(Request $request)
    {
        $query = User::query();

        // Search functionality
        if ($request->search) {
            $query->where(function($q) use ($request) {
                $q->where('name', 'like', '%' . $request->search . '%')
                  ->orWhere('phone_number', 'like', '%' . $request->search . '%');
            });
        }

        // Get latest 10 users with pagination
        $users = $query->latest()->paginate(10);
        
        if ($request->ajax()) {
            return response()->json([
                'users' => $users,
                'pagination' => view('users.pagination', compact('users'))->render()
            ]);
        }

        return view('users.manage', compact('users'));
    }

    public function show($id)
    {
        $user = User::findOrFail($id);
        return response()->json($user);
    }

    public function store(Request $request)
    {
        $validated = $request->validate([
            'name' => 'required|string|max:255',
            'email' => 'required|string|email|max:255|unique:users',
            'phone_number' => 'required|string|unique:users',
            'password' => 'required|string|min:8',
            'use_fingerprint' => 'boolean',
            'fingerprint_data' => 'nullable|string',
            'device_id' => 'nullable|string'
        ]);

        $user = User::create([
            'name' => $validated['name'],
            'email' => $validated['email'],
            'phone_number' => $validated['phone_number'],
            'password' => Hash::make($validated['password']),
            'use_fingerprint' => $request->use_fingerprint ?? false,
            'fingerprint_data' => $request->fingerprint_data,
            'device_id' => $request->device_id
        ]);

        return response()->json($user, 201);
    }

    public function update(Request $request, $id)
    {
        $user = User::findOrFail($id);
        
        $validated = $request->validate([
            'name' => 'sometimes|required|string|max:255',
            'email' => 'sometimes|required|string|email|max:255|unique:users,email,' . $id,
            'phone_number' => 'sometimes|required|string|unique:users,phone_number,' . $id,
            'use_fingerprint' => 'sometimes|boolean',
            'fingerprint_data' => 'nullable|string',
            'device_id' => 'nullable|string'
        ]);

        try {
            $user->update($validated);
            return response()->json($user);
        } catch (\Exception $e) {
            return response()->json([
                'error' => 'حدث خطأ أثناء تحديث البيانات',
                'details' => $e->getMessage()
            ], 500);
        }
    }

    public function destroy($id)
    {
        $user = User::findOrFail($id);
        $user->delete();

        return response()->json([
            'success' => true,
            'message' => 'تم حذف المستخدم بنجاح'
        ]);
    }
}
