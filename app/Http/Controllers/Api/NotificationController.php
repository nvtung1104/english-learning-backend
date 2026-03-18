<?php

namespace App\Http\Controllers\Api;

use App\Models\Notification;
use Illuminate\Http\Request;
use App\Http\Controllers\Controller;
use App\Http\Resources\NotificationResource;

class NotificationController extends Controller
{
    public function index(Request $request)
    {
        $items = Notification::where('user_id', $request->user()->id)
            ->latest()
            ->paginate(20);

        return NotificationResource::collection($items);
    }

    public function store(Request $request)
    {
        $validated = $request->validate([
            'user_id' => 'required|exists:users,id',
            'title'   => 'required|string|max:255',
            'message' => 'required|string',
        ]);

        $item = Notification::create([
            'user_id' => $validated['user_id'],
            'title'   => $validated['title'],
            'message' => $validated['message'],
            'is_read' => false,
        ]);

        return response()->json([
            'success' => true,
            'message' => 'Tạo thông báo thành công.',
            'data'    => new NotificationResource($item),
        ], 201);
    }

    public function show(Request $request, Notification $notification)
    {
        if ($notification->user_id !== $request->user()->id) {
            return response()->json(['success' => false, 'message' => 'Forbidden.'], 403);
        }

        return response()->json([
            'success' => true,
            'data'    => new NotificationResource($notification),
        ]);
    }

    public function update(Request $request, Notification $notification)
    {
        $validated = $request->validate([
            'title'   => 'required|string|max:255',
            'message' => 'required|string',
            'is_read' => 'required|boolean',
        ]);

        $notification->update([
            'title'   => $validated['title'],
            'message' => $validated['message'],
            'is_read' => $validated['is_read'],
            'read_at' => $validated['is_read'] ? now() : null,
        ]);

        return response()->json([
            'success' => true,
            'message' => 'Cập nhật thông báo thành công.',
            'data'    => new NotificationResource($notification),
        ]);
    }

    public function destroy(Notification $notification)
    {
        $notification->delete();

        return response()->json([
            'success' => true,
            'message' => 'Xóa thông báo thành công.',
        ]);
    }

    public function markAsRead(Request $request, Notification $notification)
    {
        if ($notification->user_id !== $request->user()->id) {
            return response()->json(['success' => false, 'message' => 'Forbidden.'], 403);
        }

        $notification->update(['is_read' => true, 'read_at' => now()]);

        return response()->json([
            'success' => true,
            'message' => 'Đánh dấu đã đọc thành công.',
            'data'    => new NotificationResource($notification),
        ]);
    }
}