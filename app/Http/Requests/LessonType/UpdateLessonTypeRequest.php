<?php

namespace App\Http\Requests\LessonType;

use Illuminate\Foundation\Http\FormRequest;

class UpdateLessonTypeRequest extends FormRequest
{
    public function authorize(): bool { return true; }

    public function rules(): array
    {
        return [
            'name' => 'sometimes|required|string|max:100|unique:lesson_types,name,' . $this->route('lessonType')?->id,
        ];
    }
}
