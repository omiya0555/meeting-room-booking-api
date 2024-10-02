<?php

namespace App\Http\Controllers;
use Illuminate\Http\Request;
use Illuminate\Support\Facades\Auth;
use App\Models\User;

class AuthController extends Controller
{
    // リクエスト中のemail,passwordを用いて認証を行い、
    // 成功した場合はトークンを発行
    public function login(Request $request)
    {
        $credentials = $request->only('email', 'password');
    
        if (Auth::attempt($credentials)) {
            // 認証成功
            $user = Auth::user();
            $token = $user->createToken('meeting-room-booking-app')->plainTextToken;
            return response()->json(['token' => $token, 'user' => $user], 200);
        }
        // 認証失敗
        return response()->json(['message' => 'Invalid credentials'], 401);
    }
}
