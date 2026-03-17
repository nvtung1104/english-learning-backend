<?php

namespace App\Http\Controllers\Api;

use App\Models\Vocabulary;
use Illuminate\Http\Request;
use App\Http\Controllers\Controller;

class VocabularyController extends Controller
{
    public function index(Request $request)
    {
        $items = Vocabulary::when($request->lesson_id, fn($q) => $q->where('lesson_id', $request->lesson_id))
            ->get();

        return response()->json($items);
    }

    public function store(Request $request)
    {
        $validated = $request->validate([
            'lesson_id' => 'required|exists:lessons,id',
            'word' => 'required|string|max:255',
            'meaning' => 'required|string|max:255',
            'example' => 'nullable|string',
            'audio' => 'nullable|string|max:255',
        ]);

        $item = Vocabulary::create($validated);

        return response()->json([
            'message' => 'Tạo từ vựng thành công',
            'data' => $item,
        ], 201);
    }

    public function show(string $id)
    {
        return response()->json(Vocabulary::findOrFail($id));
    }

    public function update(Request $request, string $id)
    {
        $item = Vocabulary::findOrFail($id);

        $validated = $request->validate([
            'lesson_id' => 'required|exists:lessons,id',
            'word' => 'required|string|max:255',
            'meaning' => 'required|string|max:255',
            'example' => 'nullable|string',
            'audio' => 'nullable|string|max:255',
        ]);

        $item->update($validated);

        return response()->json([
            'message' => 'Cập nhật từ vựng thành công',
            'data' => $item,
        ]);
    }

    public function destroy(string $id)
    {
        Vocabulary::findOrFail($id)->delete();

        return response()->json([
            'message' => 'Xóa từ vựng thành công',
        ]);
    }
}