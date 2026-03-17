<?php

namespace App\Http\Controllers\Api;

use App\Models\Role;
use Illuminate\Http\Request;
use App\Http\Controllers\Controller;

class RoleController extends Controller
{
    public function index()
    {
        return response()->json(Role::latest()->get());
    }

    public function store(Request $request)
    {
        $validated = $request->validate([
            'name' => 'required|string|max:100|unique:roles,name',
            'description' => 'nullable|string',
        ]);

        $item = Role::create($validated);

        return response()->json([
            'message' => 'Tạo role thành công',
            'data' => $item,
        ], 201);
    }

    public function show(string $id)
    {
        return response()->json(
            Role::with(['users', 'permissions'])->findOrFail($id)
        );
    }

    public function update(Request $request, string $id)
    {
        $item = Role::findOrFail($id);

        $validated = $request->validate([
            'name' => 'required|string|max:100|unique:roles,name,' . $item->id,
            'description' => 'nullable|string',
        ]);

        $item->update($validated);

        return response()->json([
            'message' => 'Cập nhật role thành công',
            'data' => $item,
        ]);
    }

    public function destroy(string $id)
    {
        Role::findOrFail($id)->delete();

        return response()->json([
            'message' => 'Xóa role thành công',
        ]);
    }
}