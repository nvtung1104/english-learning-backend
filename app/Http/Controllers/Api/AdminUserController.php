<?php

namespace App\Http\Controllers\Api;

use App\Models\User;
use Illuminate\Http\Request;
use App\Http\Controllers\Controller;
use App\Http\Resources\UserResource;
use Illuminate\Support\Facades\Hash;

class AdminUserController extends Controller
{
    public function index(Request $request)
    {
        $query = User::with('roles');

        if ($request->filled('search')) {
            $query->where(function ($q) use ($request) {
                $q->where('name', 'like', '%' . $request->search . '%')
                  ->orWhere('email', 'like', '%' . $request->search . '%');
            });
        }

        if ($request->filled('status')) {
            $query->where('status', $request->status);
        }

        if ($request->filled('role')) {
            $query->whereHas('roles', fn($q) => $q->where('name', $request->role));
        }

        $users = $query->latest()->paginate(15);

        return UserResource::collection($users);
    }

    public function show(User $user)
    {
        return response()->json([
            'success' => true,
            'data'    => new UserResource($user->load('roles')),
        ]);
    }

    public function store(Request $request)
    {
        $validated = $request->validate([
            'name'     => 'required|string|max:255',
            'email'    => 'required|email|unique:users,email',
            'password' => 'required|string|min:6',
            'phone'    => 'nullable|string|max:50',
            'status'   => 'nullable|boolean',
            'role'     => 'nullable|exists:roles,name',
        ]);

        $user = User::create([
            'name'     => $validated['name'],
            'email'    => $validated['email'],
            'password' => Hash::make($validated['password']),
            'phone'    => $validated['phone'] ?? null,
            'status'   => $validated['status'] ?? 1,
        ]);

        if (! empty($validated['role'])) {
            $role = \App\Models\Role::where('name', $validated['role'])->first();
            if ($role) {
                $user->roles()->syncWithoutDetaching([$role->id]);
            }
        }

        return response()->json([
            'success' => true,
            'message' => 'Tạo người dùng thành công.',
            'data'    => new UserResource($user->load('roles')),
        ], 201);
    }

    public function update(Request $request, User $user)
    {
        $validated = $request->validate([
            'name'   => 'sometimes|required|string|max:255',
            'email'  => 'sometimes|required|email|unique:users,email,' . $user->id,
            'phone'  => 'nullable|string|max:50',
            'avatar' => 'nullable|string|max:255',
            'status' => 'nullable|boolean',
        ]);

        $user->update($validated);

        return response()->json([
            'success' => true,
            'message' => 'Cập nhật người dùng thành công.',
            'data'    => new UserResource($user->load('roles')),
        ]);
    }

    public function destroy(User $user)
    {
        if ($user->id === auth()->id()) {
            return response()->json([
                'success' => false,
                'message' => 'Không thể xóa tài khoản đang đăng nhập.',
            ], 422);
        }

        $user->delete();

        return response()->json([
            'success' => true,
            'message' => 'Xóa người dùng thành công.',
        ]);
    }

    public function toggleStatus(User $user)
    {
        if ($user->id === auth()->id()) {
            return response()->json([
                'success' => false,
                'message' => 'Không thể khóa tài khoản đang đăng nhập.',
            ], 422);
        }

        $user->update(['status' => $user->status ? 0 : 1]);

        return response()->json([
            'success' => true,
            'message' => $user->status ? 'Kích hoạt tài khoản thành công.' : 'Khóa tài khoản thành công.',
            'data'    => new UserResource($user->load('roles')),
        ]);
    }

    public function resetPassword(Request $request, User $user)
    {
        $validated = $request->validate([
            'password' => 'required|string|min:6|confirmed',
        ]);

        $user->update(['password' => $validated['password']]);

        return response()->json([
            'success' => true,
            'message' => 'Reset mật khẩu thành công.',
        ]);
    }
}
