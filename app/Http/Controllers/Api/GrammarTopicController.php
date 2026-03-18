<?php

namespace App\Http\Controllers\Api;

use App\Models\GrammarTopic;
use Illuminate\Http\Request;
use App\Http\Controllers\Controller;
use App\Http\Resources\GrammarTopicResource;

class GrammarTopicController extends Controller
{
    public function index(Request $request)
    {
        $items = GrammarTopic::when($request->lesson_id, fn($q) => $q->where('lesson_id', $request->lesson_id))
            ->orderBy('id')
            ->get();

        return GrammarTopicResource::collection($items);
    }

    public function store(Request $request)
    {
        $validated = $request->validate([
            'lesson_id'   => 'required|exists:lessons,id',
            'title'       => 'required|string|max:255',
            'explanation' => 'nullable|string',
        ]);

        $item = GrammarTopic::create($validated);

        return response()->json([
            'success' => true,
            'message' => 'Tạo chủ đề ngữ pháp thành công.',
            'data'    => new GrammarTopicResource($item),
        ], 201);
    }

    public function show(GrammarTopic $grammarTopic)
    {
        return response()->json([
            'success' => true,
            'data'    => new GrammarTopicResource($grammarTopic),
        ]);
    }

    public function update(Request $request, GrammarTopic $grammarTopic)
    {
        $validated = $request->validate([
            'lesson_id'   => 'sometimes|required|exists:lessons,id',
            'title'       => 'sometimes|required|string|max:255',
            'explanation' => 'nullable|string',
        ]);

        $grammarTopic->update($validated);

        return response()->json([
            'success' => true,
            'message' => 'Cập nhật chủ đề ngữ pháp thành công.',
            'data'    => new GrammarTopicResource($grammarTopic),
        ]);
    }

    public function destroy(GrammarTopic $grammarTopic)
    {
        $grammarTopic->delete();

        return response()->json([
            'success' => true,
            'message' => 'Xóa chủ đề ngữ pháp thành công.',
        ]);
    }
}