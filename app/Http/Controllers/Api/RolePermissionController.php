<?php

namespace App\Http\Controllers\Api;

use App\Models\RolePermission;
use Illuminate\Http\Request;
use App\Http\Controllers\Controller;

class RolePermissionController extends Controller
{
    public function index(Request $request)
    {
        $items = RolePermission::with(['role', 'permission'])
            ->when($request->role_id, fn($q) => $q->where('role_id', $request->role_id))
            ->when($request->permission_id, fn($q) => $q->where('permission_id', $request->permission_id))
            ->get();

        return response()->json($items);
    }

    public function store(Request $request)
    {
        $validated = $request->validate([
            'role_id' => 'required|exists:roles,id',
            'permission_id' => 'required|exists:permissions,id',
        ]);

        $exists = RolePermission::where('role_id', $validated['role_id'])
            ->where('permission_id', $validated['permission_id'])
            ->exists();

        if ($exists) {
            return response()->json([
                'message' => 'Role đã có permission này rồi',
            ], 422);
        }

        $item = RolePermission::create($validated);

        return response()->json([
            'message' => 'Gán permission cho role thành công',
            'data' => $item->load(['role', 'permission']),
        ], 201);
    }

    public function show(string $id)
    {
        return response()->json([
            'message' => 'Bảng role_permissions dùng khóa chính kép, hãy dùng index hoặc lọc theo role_id/permission_id'
        ], 400);
    }

    public function update(Request $request, string $id)
    {
        return response()->json([
            'message' => 'Bảng role_permissions dùng khóa chính kép, không hỗ trợ update theo id'
        ], 400);
    }

    public function destroy(string $id)
    {
        return response()->json([
            'message' => 'Bảng role_permissions dùng khóa chính kép, hãy xóa bằng role_id + permission_id'
        ], 400);
    }

    public function remove(Request $request)
    {
        $validated = $request->validate([
            'role_id' => 'required|exists:roles,id',
            'permission_id' => 'required|exists:permissions,id',
        ]);

        RolePermission::where('role_id', $validated['role_id'])
            ->where('permission_id', $validated['permission_id'])
            ->delete();

        return response()->json([
            'message' => 'Xóa permission khỏi role thành công',
        ]);
    }
}