<?php

namespace App\Http\Controllers\Api;

use App\Models\Notification;
use Illuminate\Http\Request;
use App\Http\Controllers\Controller;

class NotificationController extends Controller
{
    public function index(Request $request)
    {
        $items = Notification::where('user_id', $request->user()->id)
            ->latest()
            ->get();

        return response()->json($items);
    }

    public function store(Request $request)
    {
        $validated = $request->validate([
            'user_id' => 'required|exists:users,id',
            'title' => 'required|string|max:255',
            'message' => 'required|string',
        ]);

        $item = Notification::create([
            'user_id' => $validated['user_id'],
            'title' => $validated['title'],
            'message' => $validated['message'],
            'is_read' => false,
        ]);

        return response()->json([
            'message' => 'Tạo thông báo thành công',
            'data' => $item,
        ], 201);
    }

    public function show(Request $request, string $id)
    {
        $item = Notification::where('user_id', $request->user()->id)->findOrFail($id);

        return response()->json($item);
    }

    public function update(Request $request, string $id)
    {
        $item = Notification::where('user_id', $request->user()->id)->findOrFail($id);

        $validated = $request->validate([
            'title' => 'required|string|max:255',
            'message' => 'required|string',
            'is_read' => 'required|boolean',
        ]);

        $item->update([
            'title' => $validated['title'],
            'message' => $validated['message'],
            'is_read' => $validated['is_read'],
            'read_at' => $validated['is_read'] ? now() : null,
        ]);

        return response()->json([
            'message' => 'Cập nhật thông báo thành công',
            'data' => $item,
        ]);
    }

    public function destroy(Request $request, string $id)
    {
        $item = Notification::where('user_id', $request->user()->id)->findOrFail($id);
        $item->delete();

        return response()->json([
            'message' => 'Xóa thông báo thành công',
        ]);
    }

    public function markAsRead(Request $request, string $id)
    {
        $item = Notification::where('user_id', $request->user()->id)->findOrFail($id);

        $item->update([
            'is_read' => true,
            'read_at' => now(),
        ]);

        return response()->json([
            'message' => 'Đánh dấu đã đọc thành công',
            'data' => $item,
        ]);
    }
}