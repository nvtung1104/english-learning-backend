<?php

namespace App\Http\Controllers\Api;

use App\Models\Permission;
use Illuminate\Http\Request;
use App\Http\Controllers\Controller;

class PermissionController extends Controller
{
    public function index()
    {
        return response()->json(Permission::latest()->get());
    }

    public function store(Request $request)
    {
        $validated = $request->validate([
            'name' => 'required|string|max:100',
            'slug' => 'required|string|max:100|unique:permissions,slug',
        ]);

        $item = Permission::create($validated);

        return response()->json([
            'message' => 'Tạo permission thành công',
            'data' => $item,
        ], 201);
    }

    public function show(string $id)
    {
        return response()->json(
            Permission::with('roles')->findOrFail($id)
        );
    }

    public function update(Request $request, string $id)
    {
        $item = Permission::findOrFail($id);

        $validated = $request->validate([
            'name' => 'required|string|max:100',
            'slug' => 'required|string|max:100|unique:permissions,slug,' . $item->id,
        ]);

        $item->update($validated);

        return response()->json([
            'message' => 'Cập nhật permission thành công',
            'data' => $item,
        ]);
    }

    public function destroy(string $id)
    {
        Permission::findOrFail($id)->delete();

        return response()->json([
            'message' => 'Xóa permission thành công',
        ]);
    }
}