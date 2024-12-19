<?php

namespace App\Http\Controllers;

use Illuminate\Http\Request;
use App\Models\Admin;
use Carbon\Carbon;

class PermissionController extends Controller
{
    public function index(Request $request)
    {
        $admins = Admin::query();

        // تطبيق الفلاتر
        if ($request->filled('role')) {
            $admins->where('role', $request->role);
        }

        if ($request->filled('status')) {
            $admins->where('status', $request->status);
        }

        if ($request->filled('registration_date')) {
            $date = Carbon::parse($request->registration_date);
            $admins->whereDate('created_at', $date);
        }

        if ($request->filled('search')) {
            $admins->where(function($q) use ($request) {
                $q->where('name', 'like', '%' . $request->search . '%')
                  ->orWhere('email', 'like', '%' . $request->search . '%');
            });
        }

        $admins = $admins->latest()->paginate(10);
        
        return view('permissions.manage', compact('admins'));
    }

    public function create()
    {
        $admins = Admin::latest()->paginate(10);
        return view('permissions.manage', compact('admins'));
    }

    public function store(Request $request)
    {
        $validated = $request->validate([
            'name' => 'required|string|max:255',
            'email' => 'required|email|unique:admins',
            'password' => 'required|min:6',
            'role' => 'required|in:admin,user',
            'status' => 'required|in:active,inactive',
        ]);

        Admin::create([
            'name' => $validated['name'],
            'email' => $validated['email'],
            'password' => bcrypt($validated['password']),
            'role' => $validated['role'],
            'status' => $validated['status'],
        ]);

        return redirect()->route('permissions.index')
            ->with('success', 'تم إضافة المستخدم بنجاح');
    }

    public function show($id)
    {
        $admin = Admin::findOrFail($id);
        return response()->json([
            'name' => $admin->name,
            'email' => $admin->email,
            'role' => $admin->role,
            'status' => $admin->status,
            'created_at' => $admin->created_at->format('Y-m-d H:i:s'),
            'last_login' => $admin->last_login ? Carbon::parse($admin->last_login)->format('Y-m-d H:i:s') : 'لم يسجل الدخول بعد'
        ]);
    }

    public function edit($id)
    {
        $admin = Admin::findOrFail($id);
        $admins = Admin::latest()->paginate(10);
        return view('permissions.manage', compact('admin', 'admins'));
    }

    public function update(Request $request, $id)
    {
        $admin = Admin::findOrFail($id);
        
        $validated = $request->validate([
            'name' => 'required|string|max:255',
            'email' => 'required|email|unique:admins,email,' . $id,
            'password' => 'nullable|min:6',
            'role' => 'required|in:admin,user',
            'status' => 'required|in:active,inactive',
        ]);

        $admin->name = $validated['name'];
        $admin->email = $validated['email'];
        $admin->role = $validated['role'];
        $admin->status = $validated['status'];
        
        if (!empty($validated['password'])) {
            $admin->password = bcrypt($validated['password']);
        }

        $admin->save();

        return redirect()->route('permissions.index')
            ->with('success', 'تم تحديث المستخدم بنجاح');
    }

    public function destroy($id)
    {
        $admin = Admin::findOrFail($id);
        $admin->delete();

        return redirect()->route('permissions.index')
            ->with('success', 'تم حذف المستخدم بنجاح');
    }
}
