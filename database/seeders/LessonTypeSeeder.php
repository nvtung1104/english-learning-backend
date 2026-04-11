<?php

namespace Database\Seeders;

use Illuminate\Database\Seeder;
use Illuminate\Support\Facades\DB;

class LessonTypeSeeder extends Seeder
{
    public function run(): void
    {
        $items = [
            [
                'name'        => 'Vocabulary',
                'icon'        => '📚',
                'description' => 'Từ vựng theo chủ đề',
                'category'    => 'vocabulary',
                'sub_topics'  => json_encode([
                    'Từ theo chủ đề (Gia đình, Công việc, Du lịch, Ăn uống...)',
                    'Collocations (cụm từ đi chung)',
                    'Phrasal Verbs',
                    'Idioms (thành ngữ)',
                ]),
            ],
            [
                'name'        => 'Grammar',
                'icon'        => '📘',
                'description' => 'Ngữ pháp tiếng Anh',
                'category'    => 'grammar',
                'sub_topics'  => json_encode([
                    'Các thì (Present Simple, Past Simple, Future...)',
                    'Cấu trúc câu (S + V + O)',
                    'Mệnh đề (Clauses)',
                    'Câu điều kiện (If clauses)',
                    'Bị động (Passive voice)',
                ]),
            ],
            [
                'name'        => 'Pronunciation',
                'icon'        => '🔊',
                'description' => 'Phát âm & ngữ điệu',
                'category'    => 'pronunciation',
                'sub_topics'  => json_encode([
                    'Bảng IPA',
                    'Trọng âm (Word stress)',
                    'Ngữ điệu (Intonation)',
                    'Nối âm (Linking sounds)',
                ]),
            ],
            [
                'name'        => 'Listening',
                'icon'        => '🎧',
                'description' => 'Nghe hiểu',
                'category'    => 'listening',
                'sub_topics'  => json_encode([
                    'Nghe hội thoại',
                    'Nghe tin tức',
                    'Nghe theo chủ đề',
                    'Nghe nhận diện từ khóa',
                ]),
            ],
            [
                'name'        => 'Speaking',
                'icon'        => '🗣️',
                'description' => 'Kỹ năng nói',
                'category'    => 'speaking',
                'sub_topics'  => json_encode([
                    'Giao tiếp hàng ngày',
                    'Trả lời câu hỏi',
                    'Thuyết trình',
                    'Phản xạ nhanh',
                ]),
            ],
            [
                'name'        => 'Writing',
                'icon'        => '✍️',
                'description' => 'Kỹ năng viết',
                'category'    => 'writing',
                'sub_topics'  => json_encode([
                    'Viết câu',
                    'Viết đoạn văn',
                    'Viết email',
                    'Viết essay',
                ]),
            ],
            [
                'name'        => 'Conversation',
                'icon'        => '💬',
                'description' => 'Giao tiếp thực tế',
                'category'    => 'conversation',
                'sub_topics'  => json_encode([
                    'Tình huống nhà hàng',
                    'Tình huống sân bay',
                    'Tình huống phỏng vấn',
                    'Role-play',
                ]),
            ],
            [
                'name'        => 'Advanced Thinking',
                'icon'        => '🧠',
                'description' => 'Tư duy tiếng Anh nâng cao',
                'category'    => 'advanced',
                'sub_topics'  => json_encode([
                    'Nghĩ bằng tiếng Anh',
                    'Paraphrase',
                    'Synonyms / Antonyms',
                    'Logic câu',
                ]),
            ],

        ];

        foreach ($items as $item) {
            DB::table('lesson_types')->updateOrInsert(
                ['name' => $item['name']],
                $item
            );
        }
    }
}
