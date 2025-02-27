<?php

namespace App\Http\Controllers\Auth;

use App\Http\Controllers\Controller;
use Illuminate\Http\Request;
use Illuminate\Support\Facades\Auth;

class AdminLoginController extends Controller
{
    public function __construct()
    {
        $this->middleware('guest:admin')->except('logout');
    }

    public function showLoginForm()
    {
        return view('auth.login');
    }

    public function login(Request $request)
    {
        $credentials = $request->validate([
            'email' => ['required', 'email'],
            'password' => ['required'],
        ]);

        if (Auth::guard('admin')->attempt($credentials, $request->remember)) {
            $admin = Auth::guard('admin')->user();
            
            if (!$admin->is_active) {
                Auth::guard('admin')->logout();
                return back()->withErrors([
                    'email' => 'هذا الحساب غير نشط. يرجى الاتصال بالمسؤول.',
                ]);
            }

            $request->session()->regenerate();
            return redirect()->intended(route('boxes-under-manufacturing.index'));
        }

        return back()->withErrors([
            'email' => 'بيانات الدخول غير صحيحة',
        ]);
    }

    public function logout(Request $request)
    {
        Auth::guard('admin')->logout();
        $request->session()->invalidate();
        $request->session()->regenerateToken();
        return redirect()->route('admin.login');
    }
}
