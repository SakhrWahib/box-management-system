<?php

namespace App\Http\Controllers;

use App\Models\User;
use Illuminate\Http\Request;
use Illuminate\Support\Facades\Hash;
use Illuminate\Support\Facades\Log;

class UserManagementController extends Controller
{
    public function index(Request $request)
    {
        $query = User::query();

        // تحسين البحث
        if ($request->search) {
            $searchTerm = '%' . $request->search . '%';
            $query->where(function($q) use ($searchTerm) {
                $q->where('name', 'like', $searchTerm)
                  ->orWhere('email', 'like', $searchTerm)
                  ->orWhere('phone_number', 'like', $searchTerm);
            });
        }

        $users = $query->latest()->paginate(10);
        
        if ($request->ajax()) {
            return response()->json([
                'users' => $users
            ]);
        }

        return view('users.manage', compact('users'));
    }

    public function store(Request $request)
    {
        try {
            $validated = $request->validate([
                'name' => 'required|string|max:255',
                'email' => 'required|string|email|max:255|unique:users',
                'phone_number' => 'required|string|unique:users',
                'password' => 'required|string|min:8',
            ]);

            $user = User::create([
                'name' => $validated['name'],
                'email' => $validated['email'],
                'phone_number' => $validated['phone_number'],
                'password' => Hash::make($validated['password']),
                'email_verified_at' => now(),
            ]);

            return response()->json([
                'success' => true,
                'message' => 'تم حضافة المستخدم بنجاح',
                'user' => $user
            ], 201);

        } catch (\Exception $e) {
            Log::error('خطأ في إنشاء المستخدم: ' . $e->getMessage());
            return response()->json([
                'success' => false,
                'message' => 'حدث خطأ أثناء إنشاء المستخدم',
                'error' => $e->getMessage()
            ], 500);
        }
    }

    public function show($id)
    {
        $user = User::findOrFail($id);
        return response()->json($user);
    }

    public function update(Request $request, $id)
    {
        try {
            $user = User::findOrFail($id);
            
            $validated = $request->validate([
                'name' => 'required|string|max:255',
                'email' => 'required|string|email|max:255|unique:users,email,' . $id,
                'phone_number' => 'required|string|unique:users,phone_number,' . $id,
            ]);

            $user->update($validated);

            return response()->json([
                'success' => true,
                'message' => 'تم تحديث المستخدم بنجاح',
                'user' => $user->fresh()
            ]);

        } catch (\Exception $e) {
            Log::error('خطأ في تحديث المستخدم: ' . $e->getMessage());
            return response()->json([
                'success' => false,
                'message' => 'حدث خطأ أثناء تحديث المستخدم',
                'error' => $e->getMessage()
            ], 500);
        }
    }

    public function destroy($id)
    {
        try {
            $user = User::findOrFail($id);
            $user->delete();

            return response()->json([
                'success' => true,
                'message' => 'تم حذف المستخدم بنجاح'
            ]);
        } catch (\Exception $e) {
            return response()->json([
                'error' => 'حدث خطأ أثناء حذف المستخدم',
                'details' => $e->getMessage()
            ], 500);
        }
    }
}
