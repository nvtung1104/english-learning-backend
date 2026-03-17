<?php

namespace App\Http\Controllers\Api;

use App\Models\UserRole;
use Illuminate\Http\Request;
use App\Http\Controllers\Controller;

class UserRoleController extends Controller
{
    public function index(Request $request)
    {
        $items = UserRole::with(['user', 'role'])
            ->when($request->user_id, fn($q) => $q->where('user_id', $request->user_id))
            ->when($request->role_id, fn($q) => $q->where('role_id', $request->role_id))
            ->get();

        return response()->json($items);
    }

    public function store(Request $request)
    {
        $validated = $request->validate([
            'user_id' => 'required|exists:users,id',
            'role_id' => 'required|exists:roles,id',
        ]);

        $exists = UserRole::where('user_id', $validated['user_id'])
            ->where('role_id', $validated['role_id'])
            ->exists();

        if ($exists) {
            return response()->json([
                'message' => 'User đã có role này rồi',
            ], 422);
        }

        $item = UserRole::create($validated);

        return response()->json([
            'message' => 'Gán role cho user thành công',
            'data' => $item->load(['user', 'role']),
        ], 201);
    }

    public function show(string $id)
    {
        return response()->json([
            'message' => 'Bảng user_roles dùng khóa chính kép, hãy dùng index hoặc lọc theo user_id/role_id'
        ], 400);
    }

    public function update(Request $request, string $id)
    {
        return response()->json([
            'message' => 'Bảng user_roles dùng khóa chính kép, không hỗ trợ update theo id'
        ], 400);
    }

    public function destroy(string $id)
    {
        return response()->json([
            'message' => 'Bảng user_roles dùng khóa chính kép, hãy xóa bằng user_id + role_id'
        ], 400);
    }

    public function remove(Request $request)
    {
        $validated = $request->validate([
            'user_id' => 'required|exists:users,id',
            'role_id' => 'required|exists:roles,id',
        ]);

        UserRole::where('user_id', $validated['user_id'])
            ->where('role_id', $validated['role_id'])
            ->delete();

        return response()->json([
            'message' => 'Xóa role khỏi user thành công',
        ]);
    }
}