<?php

namespace App\Http\Controllers\Api;

use App\Models\Role;
use App\Models\User;
use Illuminate\Http\Request;
use App\Http\Controllers\Controller;
use App\Http\Resources\UserResource;
use Illuminate\Support\Facades\Hash;
use Illuminate\Validation\ValidationException;
use App\Http\Requests\LoginRequest;
use App\Http\Requests\RegisterRequest;

class AuthController extends Controller
{
    public function register(RegisterRequest $request)
    {
        $user = User::create([
            'name'   => $request->name,
            'email'  => $request->email,
            'password' => $request->password,
            'phone'  => $request->phone,
            'status' => 1,
        ]);

        $studentRole = Role::where('name', $request->role ?? 'student')->first()
            ?? Role::where('name', 'student')->first();
        if ($studentRole) {
            $user->roles()->syncWithoutDetaching([$studentRole->id]);
        }

        $token = $user->createToken('auth_token')->plainTextToken;

        return response()->json([
            'success'    => true,
            'message'    => 'Đăng ký thành công.',
            'user'       => new UserResource($user->load('roles')),
            'token'      => $token,
            'token_type' => 'Bearer',
        ], 201);
    }

    public function login(LoginRequest $request)
    {
        $user = User::where('email', $request->email)->first();

        if (! $user || ! Hash::check($request->password, $user->password)) {
            throw ValidationException::withMessages([
                'email' => ['Email hoặc mật khẩu không đúng.'],
            ]);
        }

        if ((int) $user->status !== 1) {
            return response()->json([
                'success' => false,
                'message' => 'Tài khoản đã bị khóa.',
            ], 403);
        }

        $user->update(['last_login' => now()]);

        $token = $user->createToken('auth_token')->plainTextToken;

        return response()->json([
            'success'    => true,
            'message'    => 'Đăng nhập thành công.',
            'user'       => new UserResource($user->load('roles')),
            'token'      => $token,
            'token_type' => 'Bearer',
        ]);
    }

    public function me(Request $request)
    {
        return response()->json([
            'success' => true,
            'data'    => new UserResource($request->user()->load('roles')),
        ]);
    }

    public function logout(Request $request)
    {
        $request->user()->currentAccessToken()->delete();

        return response()->json([
            'success' => true,
            'message' => 'Đăng xuất thành công.',
        ]);
    }

    public function logoutAll(Request $request)
    {
        $request->user()->tokens()->delete();

        return response()->json([
            'success' => true,
            'message' => 'Đăng xuất tất cả thiết bị thành công.',
        ]);
    }
}
