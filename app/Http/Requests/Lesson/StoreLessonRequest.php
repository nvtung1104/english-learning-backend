<?php

namespace App\Http\Requests\Lesson;

use Illuminate\Foundation\Http\FormRequest;

class StoreLessonRequest extends FormRequest
{
    public function authorize(): bool
    {
        return true;
    }

    public function rules(): array
    {
        return [
            'section_id' => 'required|exists:course_sections,id',
            'title' => 'required|string|max:255',
            'content' => 'nullable|string',
            'lesson_type_id' => 'required|exists:lesson_types,id',
            'order_number' => 'required|integer|min:1',
            'video_url' => 'nullable|string|max:255',
        ];
    }
}