<?php

namespace App\Http\Controllers\Api;

use App\Models\GrammarTopic;
use Illuminate\Http\Request;
use App\Http\Controllers\Controller;

class GrammarTopicController extends Controller
{
    public function index(Request $request)
    {
        $items = GrammarTopic::when($request->lesson_id, fn($q) => $q->where('lesson_id', $request->lesson_id))
            ->get();

        return response()->json($items);
    }

    public function store(Request $request)
    {
        $validated = $request->validate([
            'lesson_id' => 'required|exists:lessons,id',
            'title' => 'required|string|max:255',
            'explanation' => 'nullable|string',
        ]);

        $item = GrammarTopic::create($validated);

        return response()->json([
            'message' => 'Tạo chủ đề ngữ pháp thành công',
            'data' => $item,
        ], 201);
    }

    public function show(string $id)
    {
        return response()->json(GrammarTopic::findOrFail($id));
    }

    public function update(Request $request, string $id)
    {
        $item = GrammarTopic::findOrFail($id);

        $validated = $request->validate([
            'lesson_id' => 'required|exists:lessons,id',
            'title' => 'required|string|max:255',
            'explanation' => 'nullable|string',
        ]);

        $item->update($validated);

        return response()->json([
            'message' => 'Cập nhật chủ đề ngữ pháp thành công',
            'data' => $item,
        ]);
    }

    public function destroy(string $id)
    {
        GrammarTopic::findOrFail($id)->delete();

        return response()->json([
            'message' => 'Xóa chủ đề ngữ pháp thành công',
        ]);
    }
}