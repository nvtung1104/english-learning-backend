<?php

namespace App\Http\Controllers\Api;

use Illuminate\Http\Request;
use App\Http\Controllers\Controller;
use App\Http\Resources\UserResource;
use Illuminate\Support\Facades\Hash;
use Illuminate\Support\Facades\Storage;
use Illuminate\Validation\ValidationException;

class ProfileController extends Controller
{
    public function show(Request $request)
    {
        return response()->json([
            'success' => true,
            'data'    => new UserResource($request->user()->load('roles')),
        ]);
    }

    public function update(Request $request)
    {
        $user = $request->user();

        $validated = $request->validate([
            'name'   => 'sometimes|required|string|max:255',
            'phone'  => 'nullable|string|max:50',
            'avatar' => 'nullable|string|max:500',
        ]);

        $user->update($validated);

        return response()->json([
            'success' => true,
            'message' => 'Cập nhật thông tin thành công.',
            'data'    => new UserResource($user->load('roles')),
        ]);
    }

    public function uploadAvatar(Request $request)
    {
        if (!$request->hasFile('avatar')) {
            return response()->json(['message' => 'No avatar file received.'], 422);
        }

        $file = $request->file('avatar');
        $ext  = strtolower($file->getClientOriginalExtension());

        if (!in_array($ext, ['jpg', 'jpeg', 'png', 'gif', 'webp'])) {
            return response()->json(['message' => 'Invalid image type.'], 422);
        }

        if ($file->getSize() > 10 * 1024 * 1024) {
            return response()->json(['message' => 'Ảnh không được vượt quá 10MB.'], 422);
        }

        $user = $request->user();

        if ($user->avatar && str_starts_with($user->avatar, '/storage/')) {
            Storage::disk('public')->delete(str_replace('/storage/', '', $user->avatar));
        }

        $filename = 'avatars/' . uniqid() . '.' . $ext;
        Storage::disk('public')->put($filename, file_get_contents($file->getRealPath()));
        $url = '/storage/' . $filename;
        $user->update(['avatar' => $url]);

        return response()->json([
            'success' => true,
            'message' => 'Avatar updated.',
            'data'    => ['url' => $url],
        ]);
    }

    /**
     * Generic image upload via multipart FormData
     * type: thumbnails | lessons | materials | avatars
     */
    public function uploadImage(Request $request)
    {
        if (!$request->hasFile('image')) {
            return response()->json(['message' => 'No image file received.'], 422);
        }

        $file = $request->file('image');
        $ext  = strtolower($file->getClientOriginalExtension());

        if (!in_array($ext, ['jpg', 'jpeg', 'png', 'gif', 'webp'])) {
            return response()->json(['message' => 'Invalid image type. Allowed: jpg, png, gif, webp.'], 422);
        }

        $folder = in_array($request->input('type'), ['thumbnails', 'lessons', 'materials', 'avatars'])
            ? $request->input('type')
            : 'thumbnails';

        $filename = uniqid() . '.' . $ext;
        $file->storeAs($folder, $filename, 'public');

        return response()->json(['success' => true, 'data' => ['url' => '/storage/' . $folder . '/' . $filename]]);
    }

    /**
     * Video upload via multipart FormData
     */
    public function uploadVideo(Request $request)
    {
        if (!$request->hasFile('video')) {
            return response()->json(['message' => 'No video file received.'], 422);
        }

        $file = $request->file('video');
        $ext  = strtolower($file->getClientOriginalExtension());

        if (!in_array($ext, ['mp4', 'mov', 'avi', 'webm', 'mkv'])) {
            return response()->json(['message' => 'Invalid video type. Allowed: mp4, mov, avi, webm.'], 422);
        }

        $filename = uniqid() . '.' . $ext;
        $file->storeAs('videos/lessons', $filename, 'public');

        return response()->json(['success' => true, 'data' => ['url' => '/storage/videos/lessons/' . $filename]]);
    }

    /**
     * Audio upload via multipart FormData
     */
    public function uploadAudio(Request $request)
    {
        if (!$request->hasFile('audio')) {
            return response()->json(['message' => 'No audio file received.'], 422);
        }

        $file = $request->file('audio');
        $ext  = strtolower($file->getClientOriginalExtension());

        if (!in_array($ext, ['mp3', 'wav', 'ogg', 'm4a', 'aac'])) {
            return response()->json(['message' => 'Invalid audio type. Allowed: mp3, wav, ogg, m4a, aac.'], 422);
        }

        $filename = uniqid() . '.' . $ext;
        $file->storeAs('audio', $filename, 'public');

        return response()->json(['success' => true, 'data' => ['url' => '/storage/audio/' . $filename]]);
    }

    public function changePassword(Request $request)
    {
        $validated = $request->validate([
            'current_password' => 'required|string',
            'password'         => 'required|string|min:6|confirmed',
        ]);

        $user = $request->user();

        if (! Hash::check($validated['current_password'], $user->password)) {
            throw ValidationException::withMessages([
                'current_password' => ['Mật khẩu hiện tại không đúng.'],
            ]);
        }

        $user->update(['password' => $validated['password']]);

        return response()->json([
            'success' => true,
            'message' => 'Đổi mật khẩu thành công.',
        ]);
    }
}
