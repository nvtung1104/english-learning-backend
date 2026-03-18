<?php

namespace App\Http\Controllers\Api;

use App\Models\SpeakingAttempt;
use Illuminate\Http\Request;
use App\Http\Controllers\Controller;
use App\Http\Resources\SpeakingAttemptResource;

class SpeakingAttemptController extends Controller
{
    public function index(Request $request)
    {
        $query = SpeakingAttempt::with(['user', 'lesson'])
            ->when($request->lesson_id, fn($q) => $q->where('lesson_id', $request->lesson_id))
            ->when($request->user_id, fn($q) => $q->where('user_id', $request->user_id));

        $items = $query->latest()->paginate(15);

        return SpeakingAttemptResource::collection($items);
    }

    public function store(Request $request)
    {
        $validated = $request->validate([
            'user_id'   => 'required|exists:users,id',
            'lesson_id' => 'required|exists:lessons,id',
            'audio_url' => 'nullable|string|max:255',
            'score'     => 'nullable|numeric|min:0|max:100',
            'feedback'  => 'nullable|string',
        ]);

        $item = SpeakingAttempt::create($validated);

        return response()->json([
            'success' => true,
            'message' => 'Tạo bài speaking thành công.',
            'data'    => new SpeakingAttemptResource($item->load(['user', 'lesson'])),
        ], 201);
    }

    public function show(SpeakingAttempt $speakingAttempt)
    {
        return response()->json([
            'success' => true,
            'data'    => new SpeakingAttemptResource($speakingAttempt->load(['user', 'lesson'])),
        ]);
    }

    public function update(Request $request, SpeakingAttempt $speakingAttempt)
    {
        $validated = $request->validate([
            'audio_url' => 'nullable|string|max:255',
            'score'     => 'nullable|numeric|min:0|max:100',
            'feedback'  => 'nullable|string',
        ]);

        $speakingAttempt->update($validated);

        return response()->json([
            'success' => true,
            'message' => 'Cập nhật bài speaking thành công.',
            'data'    => new SpeakingAttemptResource($speakingAttempt),
        ]);
    }

    public function destroy(SpeakingAttempt $speakingAttempt)
    {
        $speakingAttempt->delete();

        return response()->json([
            'success' => true,
            'message' => 'Xóa bài speaking thành công.',
        ]);
    }
}