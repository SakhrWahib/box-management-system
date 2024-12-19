<?php

namespace App\Http\Controllers\API;

use App\Http\Controllers\Controller;
use Illuminate\Http\Request;
use Illuminate\Support\Facades\Auth;
use Illuminate\Support\Facades\Hash;
use Illuminate\Support\Facades\Validator;
use App\Models\User;

class LoginController extends Controller
{
    public function login(Request $request)
    {
        // Validate the incoming request data using Validator::make
        $validator = Validator::make($request->all(), [
            'login' => 'required|string|max:255',
            'password' => 'required|string|min:8',
        ]);

        // If validation fails, return errors
        if ($validator->fails()) {
            return response()->json(['errors' => $validator->errors()], 400);
        }

        // تحديد ما إذا كان المدخل بريد إلكتروني أو رقم هاتف
        $loginType = filter_var($request->login, FILTER_VALIDATE_EMAIL) ? 'email' : 'phone_number';

        // البحث عن المستخدم إما بالبريد الإلكتروني أو رقم الهاتف
        $user = User::where($loginType, $request->login)->first();

        // If user is not found or password does not match
        if (!$user || !Hash::check($request->password, $user->password)) {
            return response()->json(['error' => 'Invalid credentials'], 401);
        }

        // Return a success response with the token
        return response()->json([
            'message' => 'Login successful',
            'data' => $user,
        ]);
    }
}

