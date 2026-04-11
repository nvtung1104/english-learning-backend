<?php

namespace App\Http\Controllers\Api;

use App\Models\QuizQuestion;
use Illuminate\Http\Request;
use App\Http\Controllers\Controller;
use Illuminate\Support\Facades\DB;

class QuizQuestionController extends Controller
{
    public function daily()
    {
        $total = QuizQuestion::whereNull('parent_id')->count();
        if ($total === 0) return response()->json(null);

        $seed = (int) now()->format('Ymd');
        mt_srand($seed);
        $index = mt_rand(0, $total - 1);

        $question = QuizQuestion::with(['answers', 'subQuestions'])
            ->whereNull('parent_id')
            ->skip($index)
            ->first();

        return response()->json($question);
    }

    public function index(Request $request)
    {
        $items = QuizQuestion::with(['answers', 'subQuestions', 'quiz'])
            ->whereNull('parent_id')
            ->when($request->quiz_id, fn($q) => $q->where('quiz_id', $request->quiz_id))
            ->get()
            ->map(fn($q) => $this->formatQuestion($q));

        return response()->json($items);
    }

    public function store(Request $request)
    {
        $type = $request->input('question_type', 'multiple_choice');

        DB::beginTransaction();
        try {
            $question = $this->createQuestion($request->all(), $type);
            DB::commit();

            return response()->json([
                'message' => 'Tạo câu hỏi thành công',
                'data'    => $this->formatQuestion($question->fresh(['answers', 'subQuestions'])),
            ], 201);
        } catch (\Throwable $e) {
            DB::rollBack();
            return response()->json(['message' => $e->getMessage()], 422);
        }
    }

    public function show(string $id)
    {
        $q = QuizQuestion::with(['answers', 'subQuestions'])->findOrFail($id);
        return response()->json($this->formatQuestion($q));
    }

    public function update(Request $request, string $id)
    {
        $question = QuizQuestion::findOrFail($id);
        $type = $request->input('question_type', $question->question_type);

        DB::beginTransaction();
        try {
            $this->updateQuestion($question, $request->all(), $type);
            DB::commit();

            return response()->json([
                'message' => 'Cập nhật câu hỏi thành công',
                'data'    => $this->formatQuestion($question->fresh(['answers', 'subQuestions'])),
            ]);
        } catch (\Throwable $e) {
            DB::rollBack();
            return response()->json(['message' => $e->getMessage()], 422);
        }
    }

    public function destroy(string $id)
    {
        QuizQuestion::findOrFail($id)->delete();
        return response()->json(['message' => 'Xóa câu hỏi thành công']);
    }

    // ─── helpers ────────────────────────────────────────────────────────────

    private function createQuestion(array $data, string $type): QuizQuestion
    {
        if ($this->isGroupType($type)) {
            return $this->createGroupQuestion($data, $type);
        }

        if ($type === 'writing') {
            return $this->createWritingQuestion($data);
        }

        if ($type === 'ordering') {
            return $this->createOrderingQuestion($data);
        }

        if ($type === 'matching') {
            return $this->createMatchingQuestion($data);
        }

        // legacy simple multiple_choice / true_false / fill_blank
        return $this->createSimpleQuestion($data, $type);
    }

    private function updateQuestion(QuizQuestion $question, array $data, string $type): void
    {
        if ($this->isGroupType($type)) {
            $this->updateGroupQuestion($question, $data, $type);
            return;
        }

        if ($type === 'writing') {
            $question->update([
                'quiz_id'       => $data['quiz_id'],
                'question_type' => 'writing',
                'question'      => $data['question'] ?? '',
                'meta'          => $data['meta'] ?? null,
            ]);
            return;
        }

        if ($type === 'ordering') {
            $question->update([
                'quiz_id'       => $data['quiz_id'],
                'question_type' => 'ordering',
                'question'      => $data['question'] ?? '',
                'meta'          => $data['meta'] ?? null,
            ]);
            return;
        }

        if ($type === 'matching') {
            $question->update([
                'quiz_id'       => $data['quiz_id'],
                'question_type' => 'matching',
                'question'      => $data['question'] ?? '',
                'meta'          => $data['meta'] ?? null,
            ]);
            return;
        }

        // simple
        $question->update([
            'quiz_id'       => $data['quiz_id'],
            'question'      => $data['question'] ?? '',
            'question_type' => $type,
            'image_url'     => $data['image_url'] ?? null,
            'audio_url'     => $data['audio_url'] ?? null,
        ]);
    }

    private function createGroupQuestion(array $data, string $type): QuizQuestion
    {
        $parent = QuizQuestion::create([
            'quiz_id'       => $data['quiz_id'],
            'question_type' => $type,
            'question'      => $data['title'] ?? '',
            'title'         => $data['title'] ?? '',
            'passage'       => $data['passage'] ?? null,
            'image_url'     => $data['image_url'] ?? null,
            'audio_url'     => $data['audio_url'] ?? null,
            'meta'          => array_filter([
                'instruction' => $data['instruction'] ?? null,
                'note'        => $data['note'] ?? null,
            ]),
        ]);

        foreach ($data['questions'] ?? [] as $qData) {
            $this->createSubQuestion($parent, $qData, $type);
        }

        return $parent;
    }

    private function updateGroupQuestion(QuizQuestion $parent, array $data, string $type): void
    {
        $parent->update([
            'quiz_id'       => $data['quiz_id'],
            'question_type' => $type,
            'question'      => $data['title'] ?? '',
            'title'         => $data['title'] ?? '',
            'passage'       => $data['passage'] ?? null,
            'image_url'     => $data['image_url'] ?? null,
            'audio_url'     => $data['audio_url'] ?? null,
            'meta'          => array_filter([
                'instruction' => $data['instruction'] ?? null,
                'note'        => $data['note'] ?? null,
            ]),
        ]);

        // Delete old sub-questions and recreate
        $parent->subQuestions()->delete();

        foreach ($data['questions'] ?? [] as $qData) {
            $this->createSubQuestion($parent, $qData, $type);
        }
    }

    private function createSubQuestion(QuizQuestion $parent, array $qData, string $type): QuizQuestion
    {
        $sub = QuizQuestion::create([
            'quiz_id'       => $parent->quiz_id,
            'parent_id'     => $parent->id,
            'question_type' => $type,
            'question'      => $qData['question'] ?? '',
            'meta'          => $this->buildSubMeta($qData, $type),
        ]);

        if (in_array($type, ['multiple_choice_group', 'listening_group', 'reading_group'])) {
            foreach ($qData['answers'] ?? [] as $ans) {
                if (empty($ans['answer_text'])) continue;
                $sub->answers()->create([
                    'answer_text' => $ans['answer_text'],
                    'is_correct'  => (bool) ($ans['is_correct'] ?? false),
                ]);
            }
        }

        return $sub;
    }

    private function buildSubMeta(array $qData, string $type): ?array
    {
        if ($type === 'fill_blank_group') {
            return [
                'correct_text'     => $qData['correct_text'] ?? '',
                'accepted_answers' => $qData['accepted_answers'] ?? [],
            ];
        }

        if ($type === 'true_false_group') {
            return ['correct_boolean' => $qData['correct_boolean'] ?? true];
        }

        return null;
    }

    private function createWritingQuestion(array $data): QuizQuestion
    {
        return QuizQuestion::create([
            'quiz_id'       => $data['quiz_id'],
            'question_type' => 'writing',
            'question'      => $data['question'] ?? '',
            'meta'          => $data['meta'] ?? null,
        ]);
    }

    private function createOrderingQuestion(array $data): QuizQuestion
    {
        return QuizQuestion::create([
            'quiz_id'       => $data['quiz_id'],
            'question_type' => 'ordering',
            'question'      => $data['question'] ?? '',
            'meta'          => $data['meta'] ?? null,
        ]);
    }

    private function createMatchingQuestion(array $data): QuizQuestion
    {
        return QuizQuestion::create([
            'quiz_id'       => $data['quiz_id'],
            'question_type' => 'matching',
            'question'      => $data['question'] ?? '',
            'meta'          => $data['meta'] ?? null,
        ]);
    }

    private function createSimpleQuestion(array $data, string $type): QuizQuestion
    {
        $question = QuizQuestion::create([
            'quiz_id'       => $data['quiz_id'],
            'question'      => $data['question'] ?? '',
            'question_type' => $type,
            'image_url'     => $data['image_url'] ?? null,
            'audio_url'     => $data['audio_url'] ?? null,
        ]);

        foreach ($data['answers'] ?? [] as $answer) {
            if (empty($answer['answer_text'])) continue;
            $question->answers()->create([
                'answer_text' => $answer['answer_text'],
                'is_correct'  => (bool) ($answer['is_correct'] ?? false),
            ]);
        }

        return $question;
    }

    private function isGroupType(string $type): bool
    {
        return str_ends_with($type, '_group');
    }

    private function formatQuestion(QuizQuestion $q): array
    {
        $data = $q->toArray();

        if ($this->isGroupType($q->question_type)) {
            $subs = $q->subQuestions ?? $q->relationLoaded('subQuestions')
                ? $q->subQuestions
                : $q->subQuestions()->with('answers')->get();

            $data['questions'] = $subs->map(function ($sub) use ($q) {
                $item = [
                    'id'       => $sub->id,
                    'question' => $sub->question,
                ];

                if (in_array($q->question_type, ['multiple_choice_group', 'listening_group', 'reading_group'])) {
                    $item['answers'] = $sub->answers->map(fn($a) => [
                        'id'          => $a->id,
                        'answer_text' => $a->answer_text,
                        'is_correct'  => $a->is_correct,
                    ])->values()->toArray();
                }

                if ($q->question_type === 'fill_blank_group') {
                    $item['correct_text']     = $sub->meta['correct_text'] ?? '';
                    $item['accepted_answers'] = $sub->meta['accepted_answers'] ?? [];
                }

                if ($q->question_type === 'true_false_group') {
                    $item['correct_boolean'] = $sub->meta['correct_boolean'] ?? true;
                }

                return $item;
            })->values()->toArray();

            $data['instruction'] = $q->meta['instruction'] ?? '';
            $data['note']        = $q->meta['note'] ?? '';
        }

        return $data;
    }

    // ─── import / parse ─────────────────────────────────────────────────────

    public function parseDocx(Request $request)
    {
        $request->validate([
            'file' => 'required|file|mimes:docx,zip|max:10240',
        ]);

        $path = $request->file('file')->getRealPath();

        if (!class_exists('ZipArchive')) {
            return response()->json(['message' => 'ZipArchive extension not available.'], 500);
        }

        $zip = new \ZipArchive();
        if ($zip->open($path) !== true) {
            return response()->json(['message' => 'Cannot open DOCX file.'], 422);
        }

        $xml = $zip->getFromName('word/document.xml');
        $zip->close();

        if ($xml === false) {
            return response()->json(['message' => 'Invalid DOCX file.'], 422);
        }

        $text = preg_replace('/<[^>]+>/', ' ', $xml);
        $text = preg_replace('/\s+/', ' ', $text);
        $text = trim($text);

        $questions = $this->parseExamText($text);

        return response()->json([
            'questions' => $questions,
            'total'     => count($questions),
        ]);
    }

    private function parseExamText(string $text): array
    {
        $parts = preg_split('/Question\s+(\d+)\s*[.\-:]/u', $text, -1, PREG_SPLIT_DELIM_CAPTURE);
        $questions = [];

        for ($i = 1; $i < count($parts) - 1; $i += 2) {
            $body = trim($parts[$i + 1]);
            $answerPattern = '/\b([A-D])\s*[.\-]\s*/u';
            $segments = preg_split($answerPattern, $body, -1, PREG_SPLIT_DELIM_CAPTURE);

            if (count($segments) < 3) continue;

            $questionText = trim($segments[0]);
            if (empty($questionText)) continue;

            $answers = [];
            for ($j = 1; $j < count($segments) - 1; $j += 2) {
                $answerText = trim(preg_replace('/\s+/', ' ', $segments[$j + 1]));
                if (empty($answerText)) continue;
                $answers[] = [
                    'label'       => $segments[$j],
                    'answer_text' => $answerText,
                    'is_correct'  => false,
                ];
            }

            if (count($answers) >= 2) {
                $questions[] = [
                    'number'        => (int) $parts[$i],
                    'question'      => $questionText,
                    'question_type' => 'multiple_choice',
                    'answers'       => $answers,
                ];
            }
        }

        return $questions;
    }

    public function import(Request $request)
    {
        $request->validate([
            'quiz_id' => 'required|exists:quizzes,id',
            'file'    => 'required|file|mimes:json,txt,csv|max:2048',
        ]);

        $content = file_get_contents($request->file('file')->getRealPath());
        $ext     = strtolower($request->file('file')->getClientOriginalExtension());

        if ($ext === 'json') {
            $rows = json_decode($content, true);
            if (json_last_error() !== JSON_ERROR_NONE || !is_array($rows)) {
                return response()->json(['message' => 'File JSON không hợp lệ.'], 422);
            }
        } elseif (in_array($ext, ['csv', 'txt'])) {
            $rows = $this->parseCsv($content);
        } else {
            return response()->json(['message' => 'Định dạng file không được hỗ trợ.'], 422);
        }

        if (empty($rows)) {
            return response()->json(['message' => 'File không có dữ liệu.'], 422);
        }

        $created = 0;

        DB::transaction(function () use ($rows, $request, &$created) {
            foreach ($rows as $row) {
                $row['quiz_id'] = $request->quiz_id;
                $type = $row['question_type'] ?? 'multiple_choice';

                try {
                    $this->createQuestion($row, $type);
                    $created++;
                } catch (\Throwable) {
                    // skip invalid rows
                }
            }
        });

        return response()->json([
            'message' => "Import thành công {$created} câu hỏi.",
            'created' => $created,
        ]);
    }

    public function importDocx(Request $request)
    {
        $request->validate([
            'quiz_id'   => 'required|exists:quizzes,id',
            'questions' => 'required|array|min:1',
        ]);

        $created = 0;

        DB::transaction(function () use ($request, &$created) {
            foreach ($request->questions as $qData) {
                if (empty($qData['question'])) continue;

                $question = QuizQuestion::create([
                    'quiz_id'       => $request->quiz_id,
                    'question'      => $qData['question'],
                    'question_type' => $qData['question_type'] ?? 'multiple_choice',
                    'image_url'     => $qData['image_url'] ?? null,
                    'audio_url'     => $qData['audio_url'] ?? null,
                ]);

                foreach ($qData['answers'] ?? [] as $ans) {
                    if (empty($ans['answer_text'])) continue;
                    $question->answers()->create([
                        'answer_text' => $ans['answer_text'],
                        'is_correct'  => (bool) ($ans['is_correct'] ?? false),
                    ]);
                }

                $created++;
            }
        });

        return response()->json([
            'message' => "Import thành công {$created} câu hỏi.",
            'created' => $created,
        ]);
    }

    private function parseCsv(string $content): array
    {
        $lines = array_filter(explode("\n", str_replace("\r\n", "\n", $content)));
        $lines = array_values($lines);

        if (count($lines) < 2) return [];

        $header = array_map('trim', str_getcsv(array_shift($lines)));
        $grouped = [];

        foreach ($lines as $line) {
            $cols = array_combine($header, str_getcsv($line));
            if (!$cols || empty($cols['question'])) continue;

            $q = trim($cols['question']);
            if (!isset($grouped[$q])) {
                $grouped[$q] = [
                    'question'      => $q,
                    'question_type' => trim($cols['question_type'] ?? 'multiple_choice'),
                    'image_url'     => trim($cols['image_url'] ?? '') ?: null,
                    'audio_url'     => trim($cols['audio_url'] ?? '') ?: null,
                    'answers'       => [],
                ];
            }

            if (!empty($cols['answer_text'])) {
                $grouped[$q]['answers'][] = [
                    'answer_text' => trim($cols['answer_text']),
                    'is_correct'  => in_array(strtolower(trim($cols['is_correct'] ?? '')), ['1', 'true', 'yes', 'đúng']),
                ];
            }
        }

        return array_values($grouped);
    }
}
