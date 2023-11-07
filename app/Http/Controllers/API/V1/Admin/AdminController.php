<?php

namespace App\Http\Controllers\API\V1\Admin;

use App\Http\Controllers\Controller;
use App\Models\Admin;
use Illuminate\Http\Request;
use Illuminate\Support\Facades\Auth;
use Illuminate\Support\Facades\Hash;
use Tymon\JWTAuth\Facades\JWTAuth;

class AdminController extends Controller
{
    public function register(Request $request)
    {
        $admin = $request->validated();

        $admin = Admin::create([
            'name' => $request->name,
            'email' => $request->email,
            'password' => Hash::make($request->password),
        ]);

        $token = Auth::guard('admin')->login($admin);

        return response()->json([
            'message' => 'Admin created successfully',
            'admin' => $admin,
            'authorization' => [
                'token' => $token,
                'type' => 'bearer',
            ]
        ], 201);
    }

    public function login(Request $request)
    {
        $request->validate([
            'email' => 'required|string|email',
            'password' => 'required|string',
        ]);

        $credentials = $request->only('email', 'password');

        if (!$token = Auth::guard('admin')->attempt($credentials)) {
            return response()->json([
                'error' => 'Unauthorized'
            ], 401);
        }

        return response()->json([
            'access_token' => $token,
            'token_type' => 'bearer',
            'expires_in' => JWTAuth::factory()->getTTL() * 60,
        ]);
    }

    public function logout()
    {
        Auth::guard('admin')->logout();

        return response()->json(['message' => 'Successfully logged out']);
    }

    public function refresh()
    {
        $newToken = JWTAuth::parseToken()->refresh();

        return response()->json([
            'access_token' => $newToken,
            'token_type' => 'bearer',
            'expires_in' => JWTAuth::factory()->getTTL() * 60,
        ]);
    }

    public function profile()
    {
        return response()->json(Auth::guard('admin')->user());
    }

        // public function changePassword(Request $request)
    // {
    //     $validated = $request->validate([
    //         'password' => 'required|min:6'
    //     ]);

    //     $user = $request->user();

    //     $user->password = Hash::make($validated['password']);
    //     $user->save();

    //     foreach ($user->tokens as $token) {
    //         $token->delete();
    //     }

    //     $token = Auth::guard('employees')->login($user);

    //     $response = [
    //         'data' => [
    //             'message' => 'Password change successfully',
    //             'token' => $token,
    //         ]
    //     ];

    //     return response()->json($response, 200);
    // }
}
