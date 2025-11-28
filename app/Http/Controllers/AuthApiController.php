<?php

namespace App\Http\Controllers;

use App\Http\Controllers\Controller;
use Illuminate\Http\Request;
use Illuminate\Support\Facades\Auth;

class AuthApiController extends Controller
{
    public function login(Request $request)
    {
        $credentials = $request->validate([
            'email' => ['required', 'email'],
            'password' => ['required'],
        ]);

        if (!Auth::attempt($credentials)) {
            return response()->json([
                'message' => 'Thông tin đăng nhập không chính xác',
            ], 401);
        }

        /** @var \App\Models\User $user */
        $user = $request->user();

        // load role để frontend biết điều hướng theo vai trò
        $user->load('role');

        // mỗi lần login ở một tab sẽ tạo một token mới
        $token = $user->createToken('tab-session')->plainTextToken;

        return response()->json([
            'token' => $token,
            'user'  => $user,
        ]);
    }

    public function logout(Request $request)
    {
        $user = $request->user();

        if ($user && $user->currentAccessToken()) {
            $user->currentAccessToken()->delete();
        }

        return response()->json([
            'message' => 'Đã đăng xuất',
        ]);
    }
}
