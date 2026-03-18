<?php

namespace App\Http\Controllers\Api;

use App\Models\Vocabulary;
use Illuminate\Http\Request;
use App\Http\Controllers\Controller;
use App\Http\Resources\VocabularyResource;

class VocabularyController extends Controller
{
    public function index(Request $request)
    {
        $items = Vocabulary::when($request->lesson_id, fn($q) => $q->where('lesson_id', $request->lesson_id))
            ->orderBy('id')
            ->get();

        return VocabularyResource::collection($items);
    }

    public function store(Request $request)
    {
        $validated = $request->validate([
            'lesson_id' => 'required|exists:lessons,id',
            'word'      => 'required|string|max:255',
            'meaning'   => 'required|string|max:255',
            'example'   => 'nullable|string',
            'audio'     => 'nullable|string|max:255',
        ]);

        $item = Vocabulary::create($validated);

        return response()->json([
            'success' => true,
            'message' => 'Tạo từ vựng thành công.',
            'data'    => new VocabularyResource($item),
        ], 201);
    }

    public function show(Vocabulary $vocabulary)
    {
        return response()->json([
            'success' => true,
            'data'    => new VocabularyResource($vocabulary),
        ]);
    }

    public function update(Request $request, Vocabulary $vocabulary)
    {
        $validated = $request->validate([
            'lesson_id' => 'sometimes|required|exists:lessons,id',
            'word'      => 'sometimes|required|string|max:255',
            'meaning'   => 'sometimes|required|string|max:255',
            'example'   => 'nullable|string',
            'audio'     => 'nullable|string|max:255',
        ]);

        $vocabulary->update($validated);

        return response()->json([
            'success' => true,
            'message' => 'Cập nhật từ vựng thành công.',
            'data'    => new VocabularyResource($vocabulary),
        ]);
    }

    public function destroy(Vocabulary $vocabulary)
    {
        $vocabulary->delete();

        return response()->json([
            'success' => true,
            'message' => 'Xóa từ vựng thành công.',
        ]);
    }
}