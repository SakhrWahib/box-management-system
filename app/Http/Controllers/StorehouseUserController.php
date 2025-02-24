<?php

namespace App\Http\Controllers;

use App\Models\StorehouseUser;
use Illuminate\Http\Request;
use Illuminate\Support\Facades\Hash;

class StorehouseUserController extends Controller
{
    public function index()
    {
        $users = StorehouseUser::latest()->paginate(10);
        // للتأكد من وصول البيانات
        // dd($users);
        return view('storehouse_users.index', compact('users'));
    }

    public function store(Request $request)
    {
        $validated = $request->validate([
            'name' => 'required|string|max:255',
            'email' => 'required|string|email|max:255|unique:storehouse_users',
            'password' => 'required|string|min:8',
            'role' => 'required|in:admin,employee',
        ]);

        $validated['password'] = Hash::make($validated['password']);
        
        StorehouseUser::create($validated);

        return response()->json([
            'success' => true,
            'message' => 'تم إضافة المستخدم بنجاح'
        ]);
    }

    public function show($id)
    {
        $user = StorehouseUser::findOrFail($id);
        return response()->json($user);
    }

    public function update(Request $request, $id)
    {
        $user = StorehouseUser::findOrFail($id);

        $validated = $request->validate([
            'name' => 'required|string|max:255',
            'email' => 'required|string|email|max:255|unique:storehouse_users,email,'.$id,
            'role' => 'required|in:admin,employee',
            'password' => 'nullable|string|min:8',
        ]);

        if (isset($validated['password'])) {
            $validated['password'] = Hash::make($validated['password']);
        } else {
            unset($validated['password']);
        }

        $user->update($validated);

        return response()->json([
            'success' => true,
            'message' => 'تم تحديث بيانات المستخدم بنجاح'
        ]);
    }

    public function destroy($id)
    {
        $user = StorehouseUser::findOrFail($id);
        $user->delete();

        return response()->json([
            'success' => true,
            'message' => 'تم حذف المستخدم بنجاح'
        ]);
    }

    public function toggleStatus($id)
    {
        $user = StorehouseUser::findOrFail($id);
        $user->status = !$user->status;
        $user->save();

        return response()->json([
            'success' => true,
            'message' => 'تم تحديث حالة المستخدم بنجاح'
        ]);
    }
} 