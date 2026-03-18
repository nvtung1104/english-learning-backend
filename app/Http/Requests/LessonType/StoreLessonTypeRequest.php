<?php

namespace App\Http\Requests\LessonType;

use Illuminate\Foundation\Http\FormRequest;

class StoreLessonTypeRequest extends FormRequest
{
    public function authorize(): bool { return true; }

    public function rules(): array
    {
        return [
            'name' => 'required|string|max:100|unique:lesson_types,name',
        ];
    }
}
